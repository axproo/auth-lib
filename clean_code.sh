#!/bin/bash

# -----------------------------------------------
# Script de nettoyage automatique du code AXPROO
# -----------------------------------------------

# Répertoire du code source
SRC_DIR="src"

echo "🚀 Début du nettoyage du projet AXPROO"

# 1️⃣ Analyse statique avec PHPStan
echo "🔹 Analyse statique avec PHPStan..."
vendor/bin/phpstan analyse $SRC_DIR --level=max
echo "✅ Analyse PHPStan terminée"

# 2️⃣ Nettoyage automatique du code avec PHP-CS-Fixer
echo "🔹 Nettoyage automatique avec PHP-CS-Fixer..."
vendor/bin/php-cs-fixer fix $SRC_DIR --rules=@PSR12 --using-cache=no --verbose
echo "✅ Nettoyage PHP-CS-Fixer terminé"

# 3️⃣ Liste des fichiers modifiés (optionnel)
echo "📄 Fichiers modifiés :"
git status --short | grep '\.php'

echo "🎉 Nettoyage terminé !"
