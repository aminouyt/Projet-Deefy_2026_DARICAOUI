<?php

namespace iutnc\deefy\action;

/**
 * Déconnecte l'utilisateur et supprime les données de session associées.
 */
class LogoutAction extends Action {

    /**
     * Efface l'utilisateur et la playlist courante avant de rediriger.
     *
     * @return string Chaîne vide après redirection.
     */
    protected function get(): string {
        unset($_SESSION['user'], $_SESSION['current_playlist_id']);
        header('Location: ?action=default');
        return '';
    }
}
