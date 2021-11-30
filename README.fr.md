# Installation

Télécharger le projet :
```bash
git clone git@github.com:pbreteche/sf21w47.git
```

Créer un fichier *.env.local*
et définir la variable d'environnement `DATABASE_URL`.

Télécharger les dépendances :
```bash
composer install
```

Créer la base de données :
```bash
php bin/console doctrine:database:create
php bin/console doctrine:schema:create
```
