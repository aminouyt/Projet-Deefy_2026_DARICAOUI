# Tableau de bord - Mini-projet Deefy

## Fonctionnalités réalisées

| Fonctionnalité | État | Détails |
|---|---|---|
| Inscription | Réalisée | Création d'un compte avec email, mot de passe confirmé et rôle STANDARD. |
| Authentification | Réalisée | Connexion avec vérification du mot de passe haché avec bcrypt. |
| Déconnexion | Réalisée | Suppression de l'utilisateur et de la playlist courante en session. |
| Création d'une playlist | Réalisée | Une playlist vide est créée en base et associée à l'utilisateur connecté. |
| Mes playlists | Réalisée | L'utilisateur voit uniquement les playlists qui lui appartiennent. |
| Sélection d'une playlist | Réalisée | Un clic rend la playlist sélectionnée courante dans la session. |
| Affichage de la playlist courante | Réalisée | La playlist courante et ses pistes sont affichées depuis la base. |
| Ajout d'un podcast | Réalisée | Le fichier MP3 est enregistré puis associé à la playlist courante. |
| Suppression d'une piste | Réalisée | Possible uniquement dans une playlist appartenant à l'utilisateur. |
| Suppression d'une playlist | Réalisée | Les relations sont supprimées avant la playlist. |
| Protection des playlists | Réalisée | Les contrôles de propriété sont effectués côté serveur. |
| Protection contre les injections SQL | Réalisée | Les requêtes utilisent PDO et des requêtes préparées. |
| Protection contre les XSS | Réalisée | Les données affichées dans le HTML sont échappées. |
| Documentation PHPDoc | Réalisée | Les classes et méthodes principales sont documentées. |

## Fonctionnalités à vérifier manuellement

- Tester la connexion avec plusieurs utilisateurs.
- Vérifier qu'un utilisateur ne voit pas les playlists d'un autre.
- Vérifier qu'un utilisateur ne peut pas sélectionner ou modifier une playlist qui ne lui appartient pas.
- Tester la création d'une playlist, sa sélection et l'ajout d'un podcast.
- Vérifier la suppression d'une piste et d'une playlist.
- Vérifier le comportement après déconnexion.

## Fonctionnalités non incluses dans le périmètre actuel

- Protection CSRF des formulaires.
