<?php

namespace iutnc\deefy\action;

use iutnc\deefy\audio\tracks\PodcastTrack;
use iutnc\deefy\repository\DeefyRepository;

/**
 * Gère l'ajout d'un podcast à la playlist courante.
 */
class AddPodcastTrackAction extends Action {

    /**
     * Affiche le formulaire d'ajout pour la playlist courante.
     *
     * @return string HTML du formulaire ou message d'accès refusé.
     */
    protected function get(): string {
        if (!isset($_SESSION['user'])) {
            return '<p class="message message-error">Vous devez être connecté pour ajouter une piste.</p>';
        }

        $playlistId = $_SESSION['current_playlist_id'] ?? null;
        $repository = DeefyRepository::getInstance();
        if (
            !is_int($playlistId)
            || !$repository->isPlaylistOwnedByUser((int) $_SESSION['user']['id'], $playlistId)
        ) {
            return '<p class="message message-error">Sélectionnez d’abord une playlist qui vous appartient.</p>';
        }

        $playlist = $repository->findPlaylistById($playlistId);
        $nomPlaylist = htmlspecialchars($playlist->nom, ENT_QUOTES, 'UTF-8');

        return <<<HTML
        <section class="form-section">
            <h2 class="page-title">Ajouter un podcast à « {$nomPlaylist} »</h2>

            <form class="form" method="POST" action="?action=add-track" enctype="multipart/form-data">

                <div class="form-group">
                    <label for="titre">Titre :</label>
                    <input type="text" name="titre" id="titre" required>
                </div>

                <div class="form-group">
                    <label for="auteur">Auteur :</label>
                    <input type="text" name="auteur" id="auteur" required>
                </div>

                <div class="form-group">
                    <label for="duree">Durée (secondes) :</label>
                    <input type="number" name="duree" id="duree" min="0" required>
                </div>

                <div class="form-group">
                    <label for="userfile">Fichier MP3 :</label>
                    <input type="file" name="userfile" id="userfile" accept=".mp3,audio/mpeg" required>
                </div>

                <button class="form-button" type="submit">Ajouter le podcast</button>

            </form>
        </section>
    HTML;
    }

    /**
     * Valide l'upload, enregistre le podcast et l'associe à la playlist courante.
     *
     * @return string HTML d'erreur ou chaîne vide après redirection.
     */
    protected function post(): string {

        $repository = DeefyRepository::getInstance();
        if (!isset($_SESSION['user'])) {
            return '<p class="message message-error">Vous devez être connecté pour ajouter une piste.</p>';
        }

        $playlistId = $_SESSION['current_playlist_id'] ?? null;
        if (
            !is_int($playlistId)
            || !$repository->isPlaylistOwnedByUser((int) $_SESSION['user']['id'], $playlistId)
        ) {
            return '<p class="message message-error">Sélectionnez d’abord une playlist qui vous appartient.</p>';
        }

        $titre = filter_input(INPUT_POST, 'titre', FILTER_SANITIZE_SPECIAL_CHARS);
        $auteur = filter_input(INPUT_POST, 'auteur', FILTER_SANITIZE_SPECIAL_CHARS);
        $duree = filter_input(INPUT_POST, 'duree', FILTER_VALIDATE_INT);

        if (
            !$titre || !$auteur
            || $duree === false || $duree === null || $duree < 0
        ) {
            return '<p class="message message-error">Veuillez remplir correctement tous les champs.</p>' . $this->get();
        }

        if (!isset($_FILES['userfile']) || !is_array($_FILES['userfile'])) {
            return '<p class="message message-error">Veuillez sélectionner un fichier MP3.</p>' . $this->get();
        }

        $fichier = $_FILES['userfile'];

        if (
            ($fichier['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK
            || substr($_FILES['userfile']['name'], -4) !== '.mp3'
            || ($fichier['type'] ?? '') !== 'audio/mpeg'
        ) {
            return '<p class="message message-error">Le fichier doit être un fichier MP3 valide.</p>' . $this->get();
        }

        $nomFichier = uniqid() . '.mp3';
        $dossierAudio = dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . 'audio';
        $cheminAbsolu = $dossierAudio . DIRECTORY_SEPARATOR . $nomFichier;
        $chemin = 'audio/' . $nomFichier;

        if (!move_uploaded_file($fichier['tmp_name'], $cheminAbsolu)) {
            return '<p class="message message-error">Impossible d\'enregistrer le fichier audio.</p>' . $this->get();
        }

        $piste = new PodcastTrack($titre, $chemin);
        $piste->auteur = $auteur;
        $piste->duree = $duree;

        $repository->saveTrack($piste);
        $repository->addTrackToPlaylist($playlistId, $piste->id);

        header('Location: ?action=current-playlist');
        return '';
    }
}