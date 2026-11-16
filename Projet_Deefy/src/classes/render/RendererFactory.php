<?php

namespace iutnc\deefy\render;
use InvalidArgumentException;
use iutnc\deefy\audio\tracks\AlbumTrack;
use iutnc\deefy\audio\tracks\PodcastTrack;
use iutnc\deefy\audio\lists\AudioList;

/**
 * Fabrique le renderer adapté au type d'objet audio fourni.
 */
class RendererFactory {
    /**
     * Retourne le renderer correspondant à une piste ou une liste audio.
     *
     * @throws InvalidArgumentException si aucun renderer n'est disponible.
     */
    public static function getRenderer(object $objet): Renderer
    {
        if ($objet instanceof AlbumTrack) {
            return new AlbumTrackRenderer($objet);
        }

        if ($objet instanceof PodcastTrack) {
            return new PodcastRenderer($objet);
        }

        if ($objet instanceof AudioList) {
            return new AudioListRenderer($objet);
        }

        throw new InvalidArgumentException("Aucun renderer disponible pour cet objet.");
    }
}