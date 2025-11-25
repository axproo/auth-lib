<?php 

namespace Axproo\Otp\Libraries;

use Axproo\Otp\Models\UserModel;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class QrCodeManager
{
    protected UserModel $model;
    protected SecureCrypto $crypto;

    public function __construct() {
        $this->model = new UserModel();
        $this->crypto = new SecureCrypto();
    }

    /**
     * Initialisation de la génération du TOTP encodé
     * @throws \Exception
     * @return array{qrcode: string, uri: string}
     */
    public function initialize(string $email, string $issuer): array {
        // Récupération de l'utilisateur
        $user = $this->model->where('email', $email)->first();
        if (! $user) throw new \Exception(lang('Users.missing'));

        // Si TOTP déjà activé -> renvoyer l'URI existant
        if (!empty($user->totp_secret) && $user->two_factor_enabled) {
            $uri = $this->crypto->decrypt($user->totp_uri);
            return [
                'uri' => $uri,
                'qrcode' => $this->generateQrImage($uri)
            ];
        }

        // Génération du secret aléatoire (Base32)
        $secret = $this->generateSecret(32);

        // Construction de l'URI TOTP complet
        $label = urlencode($email);
        $issuerEnc = rawurlencode($issuer);
        $uri = $this->getProvisionning($issuerEnc, $label, $secret);

        // Stockage chiffré
        $user->totp_secret = $this->crypto->encrypt($secret);
        $user->totp_uri = $this->crypto->encrypt($uri);
        $user->two_factor_enabled = true;
        
        $this->model->save($user);
        $this->logEvent($user->id, 'TOTP_CREATED');

        return [
            'uri' => $uri,
            'qrcode' => $this->generateQrImage($uri)
        ];
    }

    public function verify(string $email, string $code) : bool {
        $user = $this->model->where('email', $email)->first();
        if (! $user) throw new \Exception(lang('Users.missing'));

        // Forcer le code à 6 digits (garde les zéros)
        $code = str_pad((string) $code, 6, '0', STR_PAD_LEFT);

        // Décryptage du secret
        $secret = $this->crypto->decrypt($user->totp_secret);

        $timestamp = floor(time() / 30);
        $valid = false;

        for ($i=0; $i <= 1; $i++) { 
            $t = $timestamp + $i;
            $otp = $this->generateOtp($secret, $t);

            // hash_equals exige une string ->parfait ici
            if (hash_equals($otp, $code)) {
                $valid = true;
                break;
            }
        }
        $this->logEvent($user->id, $valid ? 'TOTP_SUCCESS' : 'TOTP_FAILED');
        return $valid;
    }

    /**
     * Génère un QR Code encodé en base64 pour affichage web
     *
     * @param string $data
     * @return string
     */
    private function generateQrImage(string $data) : string {
        $qr = new QrCode(
            data: $data,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::Low,
            size: 300,
            margin: 10
        );
        $writer = new PngWriter();
        $pngBinary = $writer->write($qr)->getString();

        return 'data:image/png;base64,' . base64_encode($pngBinary);
    }

    /**
     * Génère un secret aléatoire encodé en Base32
     *
     * @param integer $length
     * @return string
     */
    private function generateSecret(int $length = 20) : string {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567'; // Base32
        $secret = '';

        for ($i=0; $i < \strlen($length); $i++) { 
            $secret .= $chars[random_int(0, 31)];
        }
        return $secret;
    }

    private function generateOtp(string $secret, int $counter) : string {
        $secretBin = $this->base32Decode($secret);
        $counterBin = pack("N*", 0) . pack('N*', $counter);
        $hash = hash_hmac('sha1', $counterBin, $secretBin, true);
        $offset = \ord($hash[19]) & 0xf;
        $otp = ((\ord($hash[$offset])     & 0x7f) << 24) |
               ((\ord($hash[$offset + 1]) & 0xff) << 16) |
               ((\ord($hash[$offset + 2]) & 0xff) << 8) |
               (\ord($hash[$offset + 3]) & 0xff);
        return str_pad($otp % 1000000, 6, '0', STR_PAD_LEFT);
    }

    private function base32Decode(string $b32) : string {
        $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $b32 = strtoupper($b32);
        $binary = '';
        $buffer = 0;
        $bitsLeft = 0;

        for ($i=0; $i < \strlen($b32); $i++) { 
            $val = strpos($alphabet, $b32[$i]);
            if ($val === false) continue;
            $buffer = ($buffer << 5) | $val;
            $bitsLeft += 5;
            if ($bitsLeft >= 8) {
                $bitsLeft -= 8;
                $binary .= \chr(($buffer >> $bitsLeft) & 0xFF);
            }
        }
        return $binary;
    }

    /**
     * Génère l'URI du TOTP
     * @param mixed $issuer
     * @param mixed $label
     * @param mixed $secret
     * @return string
     */
    private function getProvisionning($issuer, $label, $secret) : string {
        return "otpauth://totp/{$issuer}:{$label}?secret={$secret}&issuer={$issuer}&algorithm=SHA1&digits=6&period=30";
    }

    /**
     * Récupération des logs
     * @param int $userId
     * @param string $event
     * @return void
     */
    private function logEvent(int $userId, string $event) : void {
        service('logger')->info("2FA Event [$event] for user #$userId");
    }
}