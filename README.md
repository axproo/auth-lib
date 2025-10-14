# 🧩 AXPROO Auth Library

Une librairie PHP légère et réutilisable pour la gestion de l’authentification basée sur **JSON Web Token (JWT)**.
Elle peut être utilisée seule ou intégrée dans un projet **CodeIgniter 4** et **PHP standalone**.

## 🚀 Fonctionnalités

- 🔐 Génération de tokens JWT d’accès et de rafraîchissement.
- ✅ Validation et décodage sécurisés des tokens.
- ⚙️ Configuration dynamique à partir d’un fichier .env.
- 🧱 Compatible PHP pur ou CodeIgniter 4.
- ♻️ Réutilisable comme package Composer dans d’autres projets.

## 📦 Structure du projet

```css
auth-lib/
│
├── src/
│   ├── Config/
│   │   └── Auth.php
│   ├── AuthService.php
│   └── TokenManager.php
│
├── test.php
├── composer.json
└── README.md
```

## ⚙️ Installation

### 1. Utilisation en local (développement)

Clonez le dépôt :

```bash
git clone https://github.com/axproo/auth-lib.git
cd auth-lib
composer install
```

### 2. Ajout à un projet CodeIgniter 4
Ajoutez dans le composer.json de votre projet :

```json
"repositories": [
  {
    "type": "vcs",
    "url": "https://github.com/axproo/auth-lib.git"
  }
],
"require": {
  "axproo/auth-lib": "dev-main"
}
```

Puis exécutez :
```bash
composer update
```

## 🔑 Configuration
Créez un fichier .env à la racine de votre projet ou du répertoire auth-lib :

```init
JWT_SECRET=ma_cle_super_secrete
JWT_REFRESH_SECRET=ma_cle_refresh_encore_plus_secrete
JWT_EXPIRE=3600
```

⚠️ Si vous testez la librairie seule (en dehors de CodeIgniter 4), la classe Auth chargera automatiquement ce fichier .env.

## 💻 Utilisation

**Exemple rapide dans** test.php
```php
<?php
require __DIR__ . '/vendor/autoload.php';

use Axproo\Auth\TokenManager;

// Initialisation
$tokenManager = new TokenManager();

// Génération d’un token
$token = $tokenManager->generateToken(['user_id' => 1, 'role' => 'admin']);
echo "Token généré : $token\n";

// Validation
$decoded = $tokenManager->validateToken($token);
echo "Décodé : ";
print_r($decoded);
```

## 🧠 Concepts clés

| Élément           | Description                               |
| ----------------- | ----------------------------------------- |
| `TokenManager`    | Classe principale de gestion des JWT      |
| `generateToken()` | Crée un token d’accès avec durée définie  |
| `refreshToken()`  | Permet de décoder un refresh token        |
| `validateToken()` | Vérifie la validité d’un token            |
| `.env`            | Contient les clés et durées configurables |

## 🧪 Tests
Pour tester la librairie seule :

```bash
php test.php
```

Résultat attendue :
```java
Token généré : eyJ0eXAiOiJKV1QiLCJh...
Décodé : stdClass Object ( [user_id] => 1 [role] => admin [iat] => ... [exp] => ... )
```

La librairie **Axproo Auth** peut être testée localement avant intégration dans un projet existant.
Si vous souhaitez tester les fonctionnalités avec des données réelles (utilisateurs, rôles, etc.), suivez les étapes suivantes

### 1️⃣ Exécuter les migrations
Créez les tables nécessaires à l’authentification dans votre base de données :

```bash
php spark migrate --all
```

### 2️⃣ (Optionnel) Exécuter les seeders
Pour charger des données de test (utilisateur admin, rôles, etc.), commencez par ajouter les données dans vos tables avec la commande Seeder, ex:

```bash
php spark make:seeder role --suffix
php spark make:seeder user --suffix
```
Exemple de fichier RoleSeeder

```php
$data = [
    [
        'role_name'     => 'superadmin',
        'description'   => 'Administrateur avec tous les super privilèges'
    ],
    [
        'role_name'     => 'admin',
        'description'   => 'Administrateur avec tous les privilèges'
    ],
    [
        'role_name'     => 'user',
        'description'   => 'Utilisateurs avec droits limités'
    ],
];
$builder = $this->db->table('rules');

foreach ($data as $row) {
    $exists = $builder
        ->where('role_name', $row['role_name'])
        ->get()->getRow();
    if (!$exists) {
        $builder->insert($row);
    }
}
```

puis exécutez :

```bash
php spark db:seed Axproo\\Auth\\Database\\Seeders\\RoleSeeder
php spark db:seed Axproo\\Auth\\Database\\Seeders\\UserSeeder
```

## 🔒 Bonnes pratiques

- Ne jamais committer le .env dans le dépôt public.
- Toujours utiliser une clé forte et unique pour JWT_SECRET.
- Régénérer régulièrement vos clés.
- Utiliser HTTPS pour toutes les requêtes liées à l’authentification.

## 🤝 Contributeurs

- Christian Djomou — Fondateur & Développeur principal
- AXPROO Team — Cybersécurité & Infrastructure

## 📄 Licence

Ce projet est sous licence **MIT**.
Vous êtes libre de l’utiliser, le modifier et le redistribuer avec mention de l’auteur.

## 🧷 Liens utiles

- 🔗 CodeIgniter 4 Documentation
- 🔗 Firebase PHP JWT

© 2025 **AXPROO** — Tous droits réservés.