<?php

namespace iutnc\deefy\action;

use iutnc\deefy\repository\DeefyRepository;

/**
 * Supprime une playlist appartenant à l'utilisateur connecté.
 */
class DeletePlaylistAction extends Action {

    /**
     * Vérifie la propriété puis supprime la playlist demandée.
     *
     * @return string Chaîne vide après redirection.
     */
    protected function post(): string {
        if (!isset($_SESSION['user'])) {
            header('Location: ?action=signin');
            return '';
        }

        $indice = filter_input(INPUT_POST, 'playlist', FILTER_VALIDATE_INT);

        if (
            $indice !== false && $indice !== null
            && DeefyRepository::getInstance()->isPlaylistOwnedByUser(
                (int) $_SESSION['user']['id'],
                $indice
            )
        ) {
            DeefyRepository::getInstance()->deletePlaylist($indice);
            if (($_SESSION['current_playlist_id'] ?? null) === $indice) {
                unset($_SESSION['current_playlist_id']);
            }
        }

        header('Location: ?action=playlist');
        return '';
    }
}
