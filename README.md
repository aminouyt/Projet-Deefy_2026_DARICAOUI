# Deefy

Application PHP de gestion de playlists et de podcasts réalisée dans le cadre du mini-projet Deefy.

## Pré-requis(si vous utilisez XAMPP en local) :

- XAMPP avec Apache et MySQL démarrés ;
- PHP avec l'extension `pdo_mysql` activée ;
- Composer.

## Installation

1. Placer le projet dans le dossier `htdocs` de XAMPP.
2. Créer une base de données nommée `deefy`.
3. Importer le fichier `data/deffy_db.sql` dans cette base avec phpMyAdmin.
4. Vérifier la configuration dans `src/config/deefy.db.ini`.


6. Ouvrir l'application dans le navigateur :

```text
http://localhost/Dev_web/TP-11/
```

## Utilisation

1. Créer un compte avec **Inscription**.
2. Se connecter avec **Connexion**.
3. Créer une playlist.
4. Cliquer sur une playlist pour la rendre courante.
5. Ajouter un podcast à la playlist courante.
6. Consulter ou supprimer les pistes et playlists depuis les pages correspondantes.
7. Utiliser **Déconnexion** pour fermer la session.

Chaque utilisateur ne peut consulter et modifier que ses propres playlists.

## Comptes présents dans le jeu de données

Le script SQL contient des comptes de test. Les mots de passe fournis par le sujet sont :

| Email | Mot de passe | Rôle |
|---|---|---|
| `user1@mail.com` | `user1` | STANDARD |
| `user2@mail.com` | `user2` | STANDARD |
| `user3@mail.com` | `user3` | STANDARD |
| `user4@mail.com` | `user4` | STANDARD |
| `admin@mail.com` | `admin` | ADMIN |

Pour un usage réel, il est préférable de créer un nouveau compte.




## Documentation complémentaire

Le détail des fonctionnalités réalisées se trouve dans `docs/tableau-de-bord.md`.
