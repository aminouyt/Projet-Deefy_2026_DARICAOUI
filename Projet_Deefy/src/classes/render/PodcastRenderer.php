<?php
namespace iutnc\deefy\render;
use iutnc\deefy\audio\tracks\PodcastTrack;



/**
 * Génère les rendus HTML d'une piste de podcast.
 */
class PodcastRenderer extends AudioTrackRenderer {

    private PodcastTrack $podcast;

    /**
     * Crée un renderer pour le podcast fourni.
     */
    public function __construct(PodcastTrack $podcast) {
        $this->podcast = $podcast;
    }
    /**
     * Génère le titre et l'auteur du podcast.
     */
    protected function renderCompact(): string {
        $titre = htmlspecialchars($this->podcast->titre, ENT_QUOTES, 'UTF-8');
        $auteur = htmlspecialchars($this->podcast->auteur, ENT_QUOTES, 'UTF-8');

        return "<p class=\"track-summary\"><span class=\"track-title\">$titre</span> - "
            . "<span class=\"track-author\">$auteur</span></p>";
    }

    /**
     * Génère les métadonnées détaillées du podcast.
     */
    protected function renderLong(): string {
        $titre = htmlspecialchars($this->podcast->titre, ENT_QUOTES, 'UTF-8');
        $auteur = htmlspecialchars($this->podcast->auteur, ENT_QUOTES, 'UTF-8');
        $date = htmlspecialchars($this->podcast->date, ENT_QUOTES, 'UTF-8');
        $genre = htmlspecialchars((string) $this->podcast->genre, ENT_QUOTES, 'UTF-8');

        return "<div class=\"track-details\">
            <h2 class=\"track-title\">$titre:</h2>
            <p class=\"track-author\">Auteur : $auteur</p>
            <p class=\"track-date\">Date : $date</p>
            <p class=\"track-genre\">Genre : $genre</p>
            <p class=\"track-duration\">Durée : {$this->podcast->duree} secondes</p>
        </div>";
    }
}