<?php
namespace iutnc\deefy\render;

use iutnc\deefy\audio\tracks\AlbumTrack;



/**
 * Génère les rendus HTML d'une piste d'album.
 */
class AlbumTrackRenderer extends AudioTrackRenderer {

    private AlbumTrack $track;

    /**
     * Crée un renderer pour la piste d'album fournie.
     */
    public function __construct(AlbumTrack $track) {
        $this->track = $track;
    }

    /**
     * Génère le titre et l'artiste de la piste.
     */
    protected function renderCompact(): string {
        return "<p>{$this->track->titre} - {$this->track->artiste}</p>";
    }

    /**
     * Génère les métadonnées détaillées de la piste.
     */
    protected function renderLong(): string {
        return "<div>
            <h2>{$this->track->titre}</h2>
            <p>Artiste : {$this->track->artiste}</p>
            <p>Album : {$this->track->album}</p>
            <p>Date : {$this->track->date}</p>
            <p>Genre : {$this->track->genre}</p>
            <p>Durée : {$this->track->duree} secondes</p>
        </div>";
    }
}