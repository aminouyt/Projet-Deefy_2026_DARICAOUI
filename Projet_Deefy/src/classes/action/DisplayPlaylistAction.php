<?php

namespace iutnc\deefy\action;

use iutnc\deefy\repository\DeefyRepository;

/**
 * Affiche les playlists appartenant à l'utilisateur connecté.
 */
class DisplayPlaylistAction extends Action {

    /**
     * Charge les playlists de l'utilisateur et génère leurs liens d'accès.
     *
     * @return string HTML de la liste des playlists.
     */
    protected function get(): string {
        if (!isset($_SESSION['user'])) {
            return '<p class="message message-error">Vous devez être connecté pour voir vos playlists.</p>';
        }

        $repository = DeefyRepository::getInstance();
        $playlistSummaries = $repository->findPlaylistsByUserId((int) $_SESSION['user']['id']);
        if ($playlistSummaries === []) {
            return '<p class="message message-info">Aucune playlist créée pour le moment.</p>
                    <p class="action-link"><a href="?action=add-playlist">Créer une playlist</a></p>';
        }

        $html = '';
        foreach ($playlistSummaries as $playlistSummary) {
            $nom = htmlspecialchars($playlistSummary->nom, ENT_QUOTES, 'UTF-8');
            $html .= '<p class="playlist-link"><a href="?action=select-playlist&id='
                . $playlistSummary->id . '">' . $nom . '</a></p>';
        }

        if (isset($_SESSION['current_playlist_id'])) {
            $html .= '<p class="action-link"><a href="?action=current-playlist">Afficher la playlist courante</a></p>';
        }

        foreach ($playlistSummaries as $playlistSummary) {
            $playlist = $repository->findPlaylistById($playlistSummary->id);
            $html .= '<section class="playlist-summary">';
            $html .= '<h2 class="playlist-title">' . htmlspecialchars($playlist->nom, ENT_QUOTES, 'UTF-8') . '</h2>';
            $html .= '<p><a href="?action=select-playlist&id=' . $playlist->id . '">Sélectionner</a></p>';
            $html .= '<form class="delete-form" method="POST" action="?action=delete-playlist">';
            $html .= '<input type="hidden" name="playlist" value="' . $playlist->id . '">';
            $html .= '<button class="delete-button" type="submit">Supprimer la playlist</button>';
            $html .= '</form>';
            $html .= '</section>';
        }

        return $html;
    }
}