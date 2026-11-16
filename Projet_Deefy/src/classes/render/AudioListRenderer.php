<?php
namespace iutnc\deefy\render;

use InvalidArgumentException;
use iutnc\deefy\audio\lists\AudioList;
use iutnc\deefy\audio\lists\Playlist;


/**
 * Génère le rendu HTML d'une liste audio et de ses pistes.
 */
class AudioListRenderer implements Renderer {

    private AudioList $liste;
    private ?int $playlistIndex;

    /**
     * Crée un renderer pour la liste fournie.
     */
    public function __construct(AudioList $liste, ?int $playlistIndex = null) {
        $this->liste = $liste;
        $this->playlistIndex = $playlistIndex;
    }

    /**
     * Génère le rendu HTML de la liste.
     *
     * Le paramètre est conservé pour respecter le contrat Renderer.
     */
    public function render(int $selector =1): string {
        $html = '<section class="playlist">' . "\n";

        $html .= '<h2 class="playlist-title">' . htmlspecialchars($this->liste->nom) . " :</h2>\n";
        $html .= '<ul class="playlist-tracks">' . "\n";

        foreach ($this->liste as $piste) {
            try {
                $renderer = RendererFactory::getRenderer($piste);
            } catch (InvalidArgumentException $e) {
                continue;
            }

            $html .= '<li class="playlist-track">';
            $html .= $renderer->render(Renderer::COMPACT);
            if ($this->liste instanceof Playlist) {
                $html .= '<form class="delete-form" method="POST" action="?action=delete-track">';
                $html .= '<input type="hidden" name="playlist" value="' . $this->playlistIndex . '">';
                $html .= '<input type="hidden" name="track" value="' . $piste->id . '">';
                $html .= '<button class="delete-button" type="submit">Supprimer</button>';
                $html .= '</form>';
            }
            $html .= "</li>\n";
        }

        $html .= "</ul>\n";

        $html .= '<p class="playlist-info">Nombre de pistes : '
            . $this->liste->nb_piste . "</p>\n";

        $html .= '<p class="playlist-info">Durée totale : '
            . $this->liste->duree_tot . " secondes</p>\n";

        $html .= "</section>\n";

        return $html;
    }
}
