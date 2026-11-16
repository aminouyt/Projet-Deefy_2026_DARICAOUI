<?php

namespace iutnc\deefy\action;

use iutnc\deefy\audio\lists\Playlist;
use iutnc\deefy\repository\DeefyRepository;

/**
 * Affiche le formulaire et crée une playlist appartenant à l'utilisateur connecté.
 */
class AddPlaylistAction extends Action {

    /**
     * Affiche le formulaire de création d'une playlist.
     *
     * @return string HTML du formulaire ou message d'accès refusé.
     */
    protected function get(): string {
        if (!isset($_SESSION['user'])) {
            return '<p class="message message-error">Vous devez être connecté pour créer une playlist.</p>';
        }

        return <<<HTML
        <section class="form-section">
            <h2 class="page-title">Créer une playlist</h2>

            <form class="form" method="POST" action="?action=add-playlist">
                <div class="form-group">
                    <label for="nom">Nom de la playlist :</label>
                    <input type="text" name="nom" id="nom" required>
                </div>

                <button class="form-button" type="submit">Créer</button>
            </form>
        </section>
    HTML;
    }

    /**
     * Valide puis enregistre la playlist envoyée par formulaire.
     *
     * La nouvelle playlist devient également la playlist courante en session.
     *
     * @return string HTML d'erreur ou chaîne vide après redirection.
     */
    protected function post(): string {
        if (!isset($_SESSION['user'])) {
            return '<p class="message message-error">Vous devez être connecté pour créer une playlist.</p>';
        }

        $nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_SPECIAL_CHARS);

        if ($nom === null || $nom === false || trim($nom) === '') {
            return '<p>Veuillez saisir un nom de playlist valide.</p>' . $this->get();
        }

        $playlist = new Playlist(trim($nom));
        $repository = DeefyRepository::getInstance();
        $repository->saveEmptyPlaylist($playlist);
        $repository->addPlaylistToUser((int) $_SESSION['user']['id'], $playlist->id);
        $_SESSION['current_playlist_id'] = $playlist->id;

        header('Location: ?action=current-playlist');
        return '';
    }
}