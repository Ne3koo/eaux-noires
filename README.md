# CHRONIQUES DES EAUX NOIRES

(DESC PROJECT)
- Symfony v7.03
## PRÉREQUIS D'INSTALLATIONS

- Requires: PHP 8.2.0 or higher

- Requires: MySQL 8.2

- Requires: Composer 2.6

## PROCÉDURE D'INSTALLATION

1. Cloner le repository

2. Dupliquer le fichier `.env` et le renommer en `.env.local`

3. Installer les dépendances Composer
```bash
composer install
```

4. Renseigner la chaîne de connexion à la base de donnée

5. Créer la base de données
    ```bash
    php bin/console doctrine:database:create
    ```

5. Éxécuter les migrations
    ```bash
    php bin/console doctrine:migrations:migrate
    ```

## PROCÉDURE D'UTILISATION

1. Lancer le serveur Symfony
    ```bash
    symfony serve
    ```
