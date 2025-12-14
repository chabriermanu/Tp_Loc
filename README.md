# 🛠️ MyLoc - Plateforme de Prêt d'Outils Entre Particuliers

![Symfony](https://img.shields.io/badge/Symfony-7.4-000000?style=flat&logo=symfony)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat&logo=php)
![PostgreSQL](https://img.shields.io/badge/PostgreSQL-16-4169E1?style=flat&logo=postgresql)
![Docker](https://img.shields.io/badge/Docker-Ready-2496ED?style=flat&logo=docker)

## 📋 Description

**MyLoc** est une application web permettant aux utilisateurs de prêter et emprunter des outils entre particuliers. Cette plateforme facilite le partage de matériel et favorise l'économie collaborative au sein d'une communauté.

Projet développé dans le cadre de la formation **Développeur Web et Web Mobile** à l'AFPA Saint-Jean-de-Védas.

---

## ✨ Fonctionnalités

### 🔐 Gestion des Utilisateurs
- Inscription et connexion sécurisées
- Vérification d'email
- Profils utilisateurs personnalisables
- Gestion des objets possédés

### 📦 Gestion des Objets
- Ajout d'objets avec photos
- Catégorisation des objets (outils, électroménager, jardinage, etc.)
- Description détaillée de chaque objet
- Upload d'images

### 🤝 Système de Prêt
- Demande de prêt d'objets
- Gestion des dates de début et fin
- Suivi du statut des prêts (en cours, terminé, en retard)
- Historique des prêts
- Calcul automatique de la durée

### 🏷️ Catégories
- Système de points par catégorie
- Organisation des objets par type
- Filtrage par catégorie

### 👨‍💼 Interface d'Administration (EasyAdmin)
- Dashboard administrateur complet
- Gestion CRUD des catégories, objets et prêts
- Visualisation des statistiques
- Interface moderne et intuitive

---

## 🚀 Stack Technique

### Backend
- **Framework** : Symfony 7.4
- **Langage** : PHP 8.2+
- **ORM** : Doctrine
- **Sécurité** : Symfony Security Bundle
- **Admin** : EasyAdmin Bundle 4.27

### Base de Données
- **SGBD** : PostgreSQL 16
- **Migrations** : Doctrine Migrations

### Frontend
- **Template Engine** : Twig
- **CSS Framework** : Bootstrap 5
- **JavaScript** : Stimulus
- **Asset Management** : Asset Mapper

### DevOps
- **Conteneurisation** : Docker & Docker Compose
- **Tests** : PHPUnit 12.5

---

## 📦 Installation

### Prérequis

- PHP >= 8.2
- Composer
- Docker & Docker Compose (recommandé)
- PostgreSQL 16 (si installation sans Docker)

### Installation avec Docker (Recommandé)

```bash
# 1. Cloner le projet
git clone https://github.com/votre-username/myLoc.git
cd myLoc

# 2. Copier le fichier d'environnement
cp .env .env.local

# 3. Lancer Docker Compose
docker-compose up -d

# 4. Installer les dépendances
composer install

# 5. Créer la base de données
php bin/console doctrine:database:create

# 6. Exécuter les migrations
php bin/console doctrine:migrations:migrate

# 7. (Optionnel) Charger les fixtures
php bin/console doctrine:fixtures:load

# 8. Lancer le serveur Symfony
symfony serve
```

L'application sera accessible sur : `http://localhost:8000`

### Installation sans Docker

```bash
# 1. Cloner le projet
git clone https://github.com/votre-username/myLoc.git
cd myLoc

# 2. Installer les dépendances
composer install

# 3. Configurer la base de données
# Modifier le fichier .env.local avec vos paramètres PostgreSQL
DATABASE_URL="postgresql://user:password@127.0.0.1:5432/myLoc?serverVersion=16&charset=utf8"

# 4. Créer la base de données
php bin/console doctrine:database:create

# 5. Exécuter les migrations
php bin/console doctrine:migrations:migrate

# 6. Lancer le serveur
symfony serve
```

---

## 📁 Structure du Projet

```
myLoc/
├── config/                 # Configuration Symfony
│   ├── packages/          # Configuration des bundles
│   └── routes/            # Définition des routes
├── migrations/            # Migrations de base de données
├── public/                # Fichiers publics (point d'entrée)
│   └── uploads/          # Images uploadées
│       └── items/        # Photos des objets
├── src/
│   ├── Controller/        # Contrôleurs
│   │   ├── Admin/        # Contrôleurs EasyAdmin
│   │   ├── ItemController.php
│   │   ├── UserController.php
│   │   └── ...
│   ├── Entity/           # Entités Doctrine
│   │   ├── User.php
│   │   ├── Item.php
│   │   ├── Category.php
│   │   └── Loan.php
│   ├── Form/             # Formulaires
│   ├── Repository/       # Repositories Doctrine
│   ├── Security/         # Authentification
│   └── Service/          # Services métier
├── templates/            # Templates Twig
│   ├── base.html.twig
│   ├── item/
│   ├── user/
│   └── ...
├── tests/                # Tests unitaires et fonctionnels
├── .env                  # Variables d'environnement
├── compose.yaml          # Configuration Docker
└── composer.json         # Dépendances PHP
```

---

## 🗄️ Modèle de Données

### Entités Principales

#### 👤 User
- `id` : Identifiant unique
- `email` : Email (unique)
- `password` : Mot de passe hashé
- `firstName` : Prénom
- `lastName` : Nom
- `roles` : Rôles (ROLE_USER, ROLE_ADMIN)
- Relations : Possède plusieurs Items, plusieurs Loans

#### 📦 Item
- `id` : Identifiant unique
- `name` : Nom de l'objet
- `description` : Description détaillée
- `picture` : Chemin de l'image
- Relations : Appartient à une Category, appartient à un User, a plusieurs Loans

#### 🏷️ Category
- `id` : Identifiant unique
- `name` : Nom de la catégorie
- `points` : Points associés à la catégorie
- Relations : A plusieurs Items

#### 🤝 Loan
- `id` : Identifiant unique
- `start` : Date de début
- `fin` : Date de fin prévue
- `status` : Statut (in_progress, completed)
- `returnedAt` : Date de retour effective
- Relations : Concerne un Item, concerne un User (emprunteur)

### Schéma des Relations

```
User (1) ──────< (N) Item
User (1) ──────< (N) Loan
Item (N) >────── (1) Category
Item (1) ──────< (N) Loan
```

---

## 🎯 Utilisation

### Interface Utilisateur

1. **Inscription/Connexion**
   - Créer un compte avec email et mot de passe
   - Vérifier votre email

2. **Ajouter un Objet**
   - Accéder à "Mes Objets"
   - Cliquer sur "Ajouter un objet"
   - Remplir les informations et uploader une photo
   - Sélectionner la catégorie appropriée

3. **Emprunter un Objet**
   - Parcourir les objets disponibles
   - Sélectionner un objet
   - Faire une demande de prêt avec dates

4. **Gérer vos Prêts**
   - Voir vos prêts en cours
   - Marquer un objet comme rendu
   - Consulter l'historique

### Interface Admin

Accès : `http://localhost:8000/admin`

- Gestion complète des utilisateurs
- Gestion des catégories et objets
- Suivi des prêts
- Statistiques générales

**Compte admin par défaut** :
- Email : `admin@myloc.fr`
- Password : `admin123`

⚠️ **Pensez à modifier ces identifiants en production !**

---

## 🔒 Sécurité

- Mots de passe hashés avec Argon2
- Protection CSRF sur tous les formulaires
- Validation côté serveur
- Vérification d'email obligatoire
- Sessions sécurisées
- Protection contre les injections SQL (ORM Doctrine)

---

## 🧪 Tests

```bash
# Exécuter tous les tests
php bin/phpunit

# Exécuter les tests avec couverture
php bin/phpunit --coverage-html var/coverage
```

---

## 🌐 Variables d'Environnement

Créer un fichier `.env.local` avec :

```env
# Database
DATABASE_URL="postgresql://user:userpwd@database:5432/myLoc?serverVersion=16&charset=utf8"

# Mailer (pour vérification email)
MAILER_DSN=smtp://localhost:1025

# App
APP_ENV=dev
APP_SECRET=your-secret-key-here
```

---

## 🛠️ Développement

### Commandes Utiles

```bash
# Créer une nouvelle entité
php bin/console make:entity

# Créer une migration
php bin/console make:migration

# Créer un contrôleur
php bin/console make:controller

# Vider le cache
php bin/console cache:clear

# Mettre à jour la base de données
php bin/console doctrine:schema:update --force
```

### Bonnes Pratiques

- Respecter les standards PSR-12
- Documenter le code
- Écrire des tests
- Commiter régulièrement
- Utiliser des branches pour les features

---

## 📸 Captures d'Écran

_À venir : Ajoutez vos captures d'écran du projet ici_

---

## 🚧 Roadmap / Améliorations Futures

- [ ] Système de notation des utilisateurs
- [ ] Messagerie interne entre utilisateurs
- [ ] Notifications par email pour les prêts
- [ ] Géolocalisation des objets
- [ ] Application mobile (PWA)
- [ ] Système de caution
- [ ] Calendrier de disponibilité

---

## 👨‍💻 Auteur

**Emmanuel**
- Formation : Développeur Web et Web Mobile - AFPA Saint-Jean-de-Védas
- Période : Septembre 2025 - Avril 2026
- GitHub : [Votre profil GitHub]
- LinkedIn : [Votre profil LinkedIn]

---

## 📄 Licence

Ce projet est développé dans un cadre pédagogique.

---

## 🙏 Remerciements

- AFPA Saint-Jean-de-Védas
- La communauté Symfony
- Tous les contributeurs open-source

---

## 📞 Support

Pour toute question ou problème :
- Ouvrir une issue sur GitHub
- Contacter : [votre.email@example.com]

---

**Dernière mise à jour** : Décembre 2024