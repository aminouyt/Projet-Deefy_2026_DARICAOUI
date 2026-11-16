<?php
namespace iutnc\deefy\render;

/**
 * Définit le contrat commun des composants de rendu HTML.
 */
interface Renderer {
    public const COMPACT = 1;
    public const LONG = 2;

    /**
     * Génère le rendu correspondant au mode demandé.
     */
    public function render(int $selector): string;
}