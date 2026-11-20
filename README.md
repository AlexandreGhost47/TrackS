# TrackS - Suivi des étudiants

Application web de suivi des étudiants d'un établissement, réalisée avec PHP, MySQL et Bootstrap.

## Fonctionnalités

- Connexion 
- Gestion des étudiants : ajout, modification, suppression, recherche
- Gestion des classes
- Suivi des absences (justifiées ou non)
- Saisie des notes et calcul de la moyenne pondérée par les coefficients
- Tableau de bord avec statistiques
- Interface responsive (ordinateur et téléphone)

## Technologies

- HTML, CSS, JavaScript
- Bootstrap 5
- PHP (PDO)
- MySQL / MariaDB

## Installation

1. Installer [XAMPP](https://www.apachefriends.org/) et démarrer Apache et MySQL.
2. Cloner le projet dans le dossier `htdocs` :
   ```bash
   cd /opt/lampp/htdocs
   git clone https://github.com/AlexandreGhost47/TrackS.git tracks
   ```
3. Dans phpMyAdmin, créer une base `tracks_db`, puis importer le fichier `database/tracks_db.sql`.
4. Copier `config/db.example.php` en `config/db.php` et y renseigner ses identifiants MySQL.
5. Ouvrir `http://localhost/tracks`.

## Connexion de démonstration

- Email : [ton email de démonstration]
- Mot de passe : [ton mot de passe de démonstration]

## Structure du projet

```
tracks/
├── assets/      Styles, scripts et images
├── config/      Connexion à la base de données
├── database/    Export SQL de la base
├── includes/    Parties communes (menu, pied de page, vérification de connexion)
├── pages/       Pages de l'application
└── index.php    Point d'entrée
```

## Auteur

Alexandre Polaski - Étudiant en génie informatique, ESTLC, Université d'Ebolowa.