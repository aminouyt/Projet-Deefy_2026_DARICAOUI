<?php

namespace iutnc\deefy\action;

use iutnc\deefy\repository\DeefyRepository;

/**
 * Sélectionne une playlist appartenant à l'utilisateur connecté.
 */
class SelectPlaylistAction extends Action {

    /**
     * Vérifie la propriété de la playlist et la mémorise en session.
     *
     * @return string HTML d'erreur ou chaîne vide après redirection.
     */
    protected function get(): string {
        if (!isset($_SESSION['user'])) {
            return '<p class="message message-error">Vous devez être connecté.</p>';
        }

        $playlistId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if (
            $playlistId === false || $playlistId === null
            || !DeefyRepository::getInstance()->isPlaylistOwnedByUser(
                (int) $_SESSION['user']['id'],
                $playlistId
            )
        ) {
            return '<p class="message message-error">Cette playlist ne vous appartient pas.</p>';
        }

        $_SESSION['current_playlist_id'] = $playlistId;
        header('Location: ?action=current-playlist');
        return '';
    }
}
