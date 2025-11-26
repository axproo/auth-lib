# LangManager

LangManager est une librairie PHP permettant de **générer automatiquement des fichiers de langue** (FR, EN ou autres) à partir de toutes les occurrences :

Elle scanne aussi bien :

- le projet principal
- les librairies externes (vendor, libs…)
- d’autres composants attachés à la solution

Elle génère ensuite des fichiers du type :

/Language/fr/Auth.php
/Language/en/Auth.php

```php

Avec une structure hiérarchique propre :

<?php

return [
    'login' => [
        'success' => 'Login réussi',
        'failed'  => 'Échec de connexion',
    ],
];
```

## ✨ Fonctionnalités

- **Scan automatique** de plusieurs dossiers (src, vendor, libs…)
- **Extraction** de toutes les clés lang('module.key')
- **Génération automatique** des fichiers de langue par module
- **Fusion automatique** avec les fichiers existants (aucune perte)
- Formatage propre en tableaux []
- Support multi-langues (ex : ['fr', 'en', 'es'])
- Réutilisable dans n’importe quel projet PHP ou framework (CI4, Laravel, Slim…)

## 📦 Installation

```bash
composer require axproo/lang-manager
```

Ou en local:

```nginx
composer install
```

## 🛠 Configuration basique

Dans votre projet :

Si vous lancer les test à partir de composer, vous pouvez faire ceci:

```bash
composer dump-autoload
```

Créer un fichier nommé example.php à la racine de votre projet,
et entré le code ci-dessous

```php
require __DIR__ . '/vendor/autoload.php';

use LangManager\LangManager;

$manager = new LangManager();

$manager->run(
    projectDir: './src', // Répertoire source à scanner pour rechercher les traduction ex: lang('Auth.login.success')
    outputDir: './src/Language', // Répertoire de destination des langues (/fr, /en ...)
    locales: ['en', 'fr'] // Définition des langues du projet ['en','fr','es']
);
```

en suite lancer dans votre CLI:

```bash
php exampe.php
```

## 📘 Exemple de fichiers générés

```css
src/
└── Language/
    ├── fr/
    │   ├── Auth.php
    │   ├── Users.php
    │   └── Token.php
    └── en/
        ├── Auth.php
        ├── Users.php
        └── Token.php
```

Chaque clé trouvée est automatiquement placée dans le bon module.

## 🧠 Ajout de nouvelles langues

Il suffit d’ajouter une langue supplémentaire :

```php
$locales = ['fr', 'en', 'es', 'de'];
```

La librairie générera automatiquement les fichiers nécessaires.

### 🛡 Protection des traductions existantes

Les fichiers existants **ne sont jamais écrasés**.
La librairie fusionne les données :

- les anciennes traductions restent
- les nouvelles clés sont ajoutées automatiquement

## 📄 Licence

MIT License – Vous pouvez utiliser cette librairie librement dans vos projets.

## 👨‍💻 Auteur

Développé par **Christian Djomou**.
Pour toute contribution, suggestion ou amélioration, ouvrez une issue ou un pull request.
