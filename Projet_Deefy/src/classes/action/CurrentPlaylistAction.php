<?php

namespace iutnc\deefy\action;

use iutnc\deefy\render\AudioListRenderer;
use iutnc\deefy\repository\DeefyRepository;

/**
 * Affiche la playlist courante mémorisée dans la session.
 */
class CurrentPlaylistAction extends Action {

    /**
     * Recharge et affiche la playlist courante de l'utilisateur.
     *
     * @return string HTML de la playlist ou message d'information.
     */
    protected function get(): string {
        if (!isset($_SESSION['user'])) {
            return '<p class="message message-error">Vous devez être connecté.</p>';
        }

        $playlistId = $_SESSION['current_playlist_id'] ?? null;
        $repository = DeefyRepository::getInstance();
        if (
            !is_int($playlistId)
            || !$repository->isPlaylistOwnedByUser((int) $_SESSION['user']['id'], $playlistId)
        ) {
            return '<p class="message message-info">Aucune playlist courante sélectionnée.</p>';
        }

        $playlist = $repository->findPlaylistById($playlistId);
        $html = '<p class="action-link"><a href="?action=add-track">Ajouter une piste</a></p>';
        $html .= '<form class="delete-form" method="POST" action="?action=delete-playlist">';
        $html .= '<input type="hidden" name="playlist" value="' . $playlist->id . '">';
        $html .= '<button class="delete-button" type="submit">Supprimer la playlist</button>';
        $html .= '</form>';
        $html .= (new AudioListRenderer($playlist, $playlist->id))->render();

        return $html;
    }
}
