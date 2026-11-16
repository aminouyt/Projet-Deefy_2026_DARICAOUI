<?php

namespace iutnc\deefy\dispatch;

use iutnc\deefy\action\ActionFactory;

/**
 * Coordonne l'exécution d'une action et l'affichage de la page HTML.
 */
class Dispatcher {

    private string $action;

    /**
     * Récupère l'action demandée dans l'URL.
     */
    public function __construct() {
        $this->action = $_GET['action'] ?? 'default';
    }

    /**
     * Exécute l'action puis affiche la page complète.
     */
    public function run(): void {
        $action = ActionFactory::getAction($this->action);
        $this->renderPage($action->getHtml());
    }

    /**
     * Encapsule le contenu d'une action dans la structure HTML commune.
     *
     * @param string $html Contenu généré par l'action.
     */
    private function renderPage(string $html): void {
        $isAuthenticated = isset($_SESSION['user']);
        $navigation = '<a class="nav-link" href="?action=default">Accueil</a>';
        $navigation .= '<a class="nav-link" href="?action=add-user">Inscription</a>';

        if ($isAuthenticated) {
            $navigation .= '<a class="nav-link" href="?action=playlist">Mes playlists</a>';
            $navigation .= '<a class="nav-link" href="?action=add-playlist">Créer une playlist</a>';
            $navigation .= '<a class="nav-link" href="?action=current-playlist">Playlist courante</a>';
            $navigation .= '<a class="nav-link" href="?action=add-track">Ajouter un morceau</a>';
            $navigation .= '<a class="nav-link" href="?action=logout">Déconnexion</a>';
        } else {
            $navigation .= '<a class="nav-link" href="?action=signin">Connexion</a>';
        }

        echo <<<HTML
        <!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Deefy</title>
            <link rel="stylesheet" href="css/style.css">
        </head>
        <body class="site-body">
            <header class="site-header">
                <h1 class="site-title">Deefy</h1>
                <nav class="site-nav">
                    $navigation
                </nav>
            </header>
            <main class="site-main">$html</main>
        </body>
        </html>
        HTML;
    }
}
