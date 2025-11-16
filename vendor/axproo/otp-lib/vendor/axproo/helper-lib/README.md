# Axproo Helper library

**AXPROO Helper Library** fournit un ensemble de fonctions utilitaires prêtes à l’emploi pour CI4 ou PHP native.  
Lors de l’installation, les fichiers sont automatiquement copiés dans `app/Helpers`.

## Installation

You can install the library via Composer:

Ajouter dans votre composer.json de votre projet:

```bash
"repositories": [
  "type": "vcs",
  "url": "https://github.com/axproo/helper-lib.git"
],
```

Puis exécutez:

```bash
composer require axproo/helper-lib:dev-main
```

Si Composer demande une autorisation d’exécuter le plugin, tapez "**y**".

## 🪄 Helpers inclus

| Helper                | Description                               |
| --------------------- | ----------------------------------------- |
| `response_helper.php` | Gestion simplifiée des réponses JSON      |
| `generate_helper.php` | Gestion de la generation de code et time  |
| `string_helper.php`   | Aide pour le traitement de chaînes        |

## Usage (utilisation)

🔹 1. Chargement automatique (recommandé – CodeIgniter 4)

Pour charger le helper automatiquement, ouvrez le fichier :

app/Config/Autoload.php
et ajoutez le helper **response** à la propriété $helpers :

```markdown
public $helpers = ['response'];
```

Ainsi, vos fonctions axprooResponse() et json_response() seront disponibles globalement dans toute votre application.

🔹 2. Chargement manuel

Vous pouvez aussi charger le helper à la demande :

```php
helper('response');

$data = ['message' => 'Bienvenue sur AXPROO!'];
json_response($data, 200);
```

## 💡 Exemple avec CodeIgniter

Si vous souhaitez profiter du système de réponse intégré à CodeIgniter :

```php
return axprooResponse(200, 'Connexion réussie', ['user' => $user]);
```

Et pour une utilisation simple (hors framework ou script isolé) :

```php
json_response(['message' => 'Exécution terminée avec succès']);
```

Résultat :

```json
{
  "status": 200,
  "data": {
    "message": "Bienvenue sur AXPROO!"
  }
}
```

## 📁 Structure après installation

```markdown
app/
└── Helpers/
    ├── response_helper.php
    ├── date_helper.php
    └── string_helper.php
```

## License

MIT © 2025 Axproo Security Labs
