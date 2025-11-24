# 🧩 AXPROO Form Library

Une librairie PHP légère et réutilisable pour la gestion des formulaires dynamiques et validation unifiée.
Elle peut être utilisée seule ou intégrée dans un projet **CI4** et **PHP standalone**.

## 🚀 Fonctionnalités

- Génération de champs de formulaire dynamique
- Validation des champs (rules)
- Configuration des tables (form_type, form_static, form_dynamic)
- Compatible CodeIgniter 4
- Réutilisable comme package Composer dans d'autres projets.

## 📦 Structure du projet

```css
form-lib/
├── src/
│   ├── Database/
│   │   ├── Migrations/
│   │   │   ├── FormTypes.php
│   │   │   └── FormStatic.php
│   │   └── Seeds/
│   │   │   ├── FormTypeSeeder.php
│   │   │   └── FormStaticSeeder.php
│   ├── Libraries/
|   |   ├── BaseForm.php
|   |   └── StaticForm.php
│   ├── Services/
│   │   ├── FormBuilder.php
│   │   └── FormManager.php
│   ├── Models/
|   |   └── FormTypeModel.php
│   ├── Entities/
|   |   └── FormTypeEntity.php
│   └── Configs/
├── tests/
│   └── FormTest.php
├── composer.json
└── phpunit.xml
```

## ⚙️ Installation

### 1. Utilisation en local (développement)

Clonez le dépôt :

```bash
git clone https://github.com/axproo/form-lib.git
cd form-lib
composer install
```

### 2. Ajout à un projet CodeIgniter 4

Ajoutez dans le composer.json de votre projet :

```json
"repositories": [
  {
    "type": "vcs",
    "url": "https://github.com/axproo/form-lib.git"
  }
],
"require": {
  "axproo/form-lib": "dev-main"
}
```

Puis exécutez :

```bash
composer update
```

ou

```bash
composer require axproo/form-lib:dev-main
```

## 💻 Utilisation

```php
$forms = new FormBuilder('/');
$overrides = [];

$schema = ['email','password']; // Champs formaté et affiché dynamiquement
return axprooResponse(200, 'Login page', $forms->build($schema, $overrides));
```

## 🧠 Concepts clés

| Élément           | Description                                      |
| ----------------- | -------------------------------------------------|
| `FormBuilder`     | Classe principale de gestion des formulaire      |

## 🧪 Tests

Pour tester la librairie seule :

```bash
php FormTest.php
```

Résultat attendue :

```json
{
    "url": "/login",
    "path_url": "login",
    "dataForm": {
        "email": null,
        "password": null
    },
    "fieldData": [
        {
            "name": "email",
            "type": "text",
            "placeholder": "Email address",
            "required": false,
            "form_group": "mb-3",
            "attributes": {
                "class": "form-control"
            },
            "label": "email",
            "isLabel": false,
            "option_values": null,
            "provide_table": null,
            "provide_field": null,
            "provide_key": null,
            "provide_cond": null,
            "is_read_only": false,
            "legend": null
        },
        {
            "name": "password",
            "type": "password",
            "placeholder": "Mot de passe",
            "required": false,
            "form_group": "mb-3",
            "attributes": {
                "class": "form-control"
            },
            "label": "Mot de passe",
            "isLabel": false,
            "option_values": null,
            "provide_table": null,
            "provide_field": null,
            "provide_key": null,
            "provide_cond": null,
            "is_read_only": false,
            "legend": null
        }
    ]
},
```

La librairie **Axproo Form** peut être testée localement avant intégration dans un projet réel.
Si vous souhaitez tester les fonctionnalités avec des données réelles (utilisateurs, rôles, etc.), suivez les étapes suivantes:

### 1️⃣ Exécuter les migrations

Créez les tables nécessaires dans votre base de données, dans le repertoire Database de votre projet, charger les tables qui y figure et procédez comme suite:

```bash
php spark migrate --all
```

### 2️⃣ (Optionnel) Exécuter les seeders

Pour charger des données de test (utilisateur admin, rôles, etc.), commencez par ajouter les données dans vos tables avec la commande Seeder, ex:

```bash
php spark make:seeder form_type --suffix
php spark make:seeder form_static --suffix
```

Exemple de fichier RoleSeeder

```css
Répertoire: /Database/Seeds/
```

puis exécutez :

```bash
php spark db:seed Axproo\\Form\\Database\\Seeders\\FormTypeSeeder
php spark db:seed Axproo\\Form\\Database\\Seeders\\FormStaticSeeder
```

### 3️⃣ Lancer les tests unitaires (si installés)

Si vous avez activé PHPUnit :

```bash
vendor/bin/phpunit
```

### 4️⃣ Connexion à l’aide des données seedées

Vous pouvez ensuite tester la connexion via :

```php
$form = new \Axproo\Form\Services\FormBuilder();
print_r($form);
```

## 🤝 Contributeurs

- Christian Djomou — Fondateur & Développeur principal
- AXPROO Team — Cybersécurité & Infrastructure.

## 📄 Licence

Ce projet est sous licence **MIT**.
Vous êtes libre de l’utiliser, le modifier et le redistribuer avec mention de l’auteur.

## 🧷 Liens utiles

- 🔗 CodeIgniter 4 Documentation
- 🔗 Firebase PHP JWT

© 2025 **AXPROO** — Tous droits réservés.