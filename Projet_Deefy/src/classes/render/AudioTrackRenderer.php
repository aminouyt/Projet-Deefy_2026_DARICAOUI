<?php
namespace iutnc\deefy\render;

/**
 * Classe de base des renderers spécialisés dans les pistes audio.
 */
abstract class AudioTrackRenderer implements Renderer {

    /**
     * Sélectionne le rendu compact ou détaillé.
     */
    public function render(int $selector): string {
        switch ($selector) {
            case Renderer::COMPACT:
                return $this->renderCompact();

            case Renderer::LONG:
                return $this->renderLong();

            default:
                return "Mode d'affichage inconnu";
        }
    }

    /**
     * Génère la représentation courte de la piste.
     */
    abstract protected function renderCompact(): string;

    /**
     * Génère la représentation détaillée de la piste.
     */
    abstract protected function renderLong(): string;
}