<?php

namespace iutnc\deefy\action;

use iutnc\deefy\repository\DeefyRepository;

/**
 * Supprime une piste d'une playlist appartenant à l'utilisateur connecté.
 */
class DeleteTrackAction extends Action {

    /**
     * Vérifie la propriété de la playlist avant de retirer la piste.
     *
     * @return string Chaîne vide après redirection.
     */
    protected function post(): string {
        if (!isset($_SESSION['user'])) {
            header('Location: ?action=signin');
            return '';
        }

        $playlistId = filter_input(INPUT_POST, 'playlist', FILTER_VALIDATE_INT);
        $trackId = filter_input(INPUT_POST, 'track', FILTER_VALIDATE_INT);

        if (
            $playlistId === false || $playlistId === null
            || $trackId === false || $trackId === null
            || !DeefyRepository::getInstance()->isPlaylistOwnedByUser(
                (int) $_SESSION['user']['id'],
                $playlistId
            )
        ) {
            header('Location: ?action=playlist');
            return '';
        }

        DeefyRepository::getInstance()->removeTrackFromPlaylist($playlistId, $trackId);

        header('Location: ?action=playlist');
        return '';
    }
}
