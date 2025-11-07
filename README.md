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
├── src/
│   ├── Services/
│   │   ├── AuthService.php
│   │   ├── TokenManager.php
│   │   └── PasswordHasher.php
│   ├── Models/
|   |   ├── RuleModel.php
|   |   └── UserModel.php
│   ├── Entities/
|   |   ├── RoleEntity.php
|   |   └── UserEntity.php
│   └── Configs/
├── tests/
│   └── AuthTest.php
├── composer.json
└── phpunit.xml
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

| Élément                   | Description                               |
| --------------------------| ----------------------------------------- |
| `TokenManager`            | Classe principale de gestion des JWT      |
| `generateToken()`         | Crée un token d’accès avec durée définie  |
| `generateRefreshToken()`  | Rafraichir la durée du token d'accès      |
| `validateToken()`         | Vérifie la validité d’un token            |
| `renewToken()`            | Renouveller le token d'accès              |
| `.env`                    | Contient les clés et durées configurables |

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
php spark make:seeder tenant --suffix
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
php spark db:seed Axproo\\Auth\\Database\\Seeders\\TenantSeeder
```

### 3️⃣ Lancer les tests unitaires (si installés)

Si vous avez activé PHPUnit :

```bash
vendor/bin/phpunit
```

### 4️⃣ Connexion à l’aide des données seedées

Vous pouvez ensuite tester la connexion via :

```php
$auth = new \Axproo\Auth\Services\AuthService();
$response = $auth->login();
print_r($response);
```

Exemple d'appel de formulaire login

```php
$fields = ['email','password']; // ex: ['email','password','code','rememberMe']

$overrides = [
    'email' => ['isLabel' => false],
    'password' => ['required' => true]
]; // Pour le formatage des champs

$form = new FormBuilder('/login') // url pour l'action du formulaire
print_r($form->build($fields, $overrides));
```

## Gestion des formulaires dynamiques

Pour la gestion des formulaires statiques et dynamique, veuillez installer le repo **AXPROO Form Library** github.

```bash
"repositories": [
  {
    "type": "vcs",
    "url": "https://github.com/axproo/form-lib.git"
  }
]
```

Puis executer

```bash
composer require axproo/form-lib:dev-main
```

Consulter la documentation (README.md) du repo **Axproo Form Library** pour plus de details.

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
