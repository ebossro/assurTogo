# AssurTogo - Plateforme de Gestion d'Assurance Santé

AssurTogo est une application web moderne conçue pour simplifier la gestion des souscriptions, des renouvellements et des déclarations de sinistres pour les assurances santé. Elle offre une interface intuitive pour les assurés et un tableau de bord puissant pour les administrateurs.


## Fonctionnalités Principales

### Pour les Assurés
- **Souscription en ligne** : Processus simplifié pour souscrire à une nouvelle police d'assurance.
- **Gestion des Sinistres** : Déclaration de sinistres, téléchargement de documents justificatifs et suivi de l'état du dossier.
- **Tableau de Bord** : Vue d'ensemble des polices actives, des sinistres en cours et historique complet.
- **Documents** : Accès et téléchargement des attestations et autres documents contractuels (PDF).
- **Renouvellement** : Renouvellement facile des polices arrivant à échéance.

### Pour les Administrateurs
- **Tableau de Bord Analytique** : Statistiques en temps réel sur les revenus, les souscriptions et les sinistres.
- **Gestion des Utilisateurs** : Vue complète des utilisateurs inscrits et de leurs rôles (Admin, Assuré, Client).
- **Validation des Dossiers** : Flux de travail pour examiner et valider les polices et les sinistres.
- **Historique** : Traçabilité complète des actions effectuées sur la plateforme.

## Technologies Utilisées

- **Backend** : PHP 8.2+, Laravel Framework.
- **Frontend** : Blade Templates, Bootstrap 5 (Thème personnalisé "Deep Violet"), Bootstrap Icons.
- **Base de Données** : MySQL.
- **Outils** : Composer, NPM (Vite).
- **Autres** : DomPDF (Génération PDF), Charts.js (Graphiques).

## ⚙️ Pré-requis

Avant de commencer, assurez-vous d'avoir installé :
- [PHP](https://www.php.net/) (version 8.2 ou supérieure)
- [Composer](https://getcomposer.org/)
- [Node.js](https://nodejs.org/) & NPM
- [MySQL](https://www.mysql.com/)

## Installation

Suivez ces étapes pour configurer le projet localement :

1. **Cloner le dépôt**
   ```bash
   git clone https://github.com/votre-username/assurTogo.git
   cd assurTogo
   ```

2. **Installer les dépendances PHP**
   ```bash
   composer install
   ```

3. **Configurer l'environnement**
   Copiez le fichier d'exemple et générez la clé d'application :
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   *N'oubliez pas de configurer vos informations de base de données dans le fichier `.env` (DB_DATABASE, DB_USERNAME, etc.).*

4. **Installer les dépendances Front-end**
   ```bash
   npm install
   npm run build
   ```

5. **Préparer la Base de Données**
   Exécutez les migrations et les seeders pour peupler la base de données avec des données de test (comptes, rôles, polices factices) :
   ```bash
   php artisan migrate:fresh --seed
   ```

6. **Lancer le serveur**
   ```bash
   php artisan serve
   ```
   L'application sera accessible sur `http://localhost:8000`.

##  Comptes de Démonstration

Une fois le `db:seed` exécuté, vous pouvez vous connecter avec le compte administrateur suivant :

- **Email** : `emmanuel@assurtogo.com`
- **Mot de passe** : `ebossro`

*Note : De nombreux utilisateurs de test sont également générés.*

## Personnalisation

Le thème visuel utilise une palette "Deep Violet" personnalisée. Les modifications de style principales se trouvent dans :
- `resources/views/layouts/admin.blade.php`
- `resources/views/layouts/dashboard.blade.php`
- `resources/views/layouts/app.blade.php`

## 📄 Licence

Ce projet est sous licence MIT.
