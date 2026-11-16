<?php

namespace iutnc\deefy\action;

/**
 * Affiche la page d'accueil de l'application.
 */
class DefaultAction extends Action {

    /**
     * Génère le message d'accueil.
     *
     * @return string HTML de la page d'accueil.
     */
    public function get(): string {
        return '<h2 class="page-title">Bienvenue sur Deefy</h2>';
    }
}