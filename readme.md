# FuKANBAN2 - Application Kanban Symfony

FuKANBAN2 est une application Kanban développée en utilisant Symfony. Ce README fournit des instructions pour configurer
et exécuter l'application sur votre machine locale.

## Prérequis

Avant de commencer, assurez-vous d'avoir les éléments suivants installés sur votre système :

- PHP (version 7.4 ou supérieure)
- Composer
- Symfony CLI

Suivez ces étapes pour configurer et exécuter l'application :

1. Clonez le dépôt :

   ```bash
   git clone https://github.com/benji28000/FuKANBAN2
   ```
2. Installez les dépendances :

     ```bash
    composer install
     ```

3. Créez la base de données :

   ```bash
     symfony console doctrine:database:create
   ```

   ```bash
   symfony console doctrine:migrations:migrate
   ```
    
4. Chargez les données de test :
   ```bash
   symfony console doctrine:fixtures:load
   ```
    
5. Démarrez le serveur :
   ```bash
   symfony server:start
   ```
    
6. Ouvrez l'application dans votre navigateur :
   ```bash
   symfony open:local
   ```