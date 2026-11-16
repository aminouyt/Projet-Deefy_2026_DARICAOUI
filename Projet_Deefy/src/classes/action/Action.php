<?php

namespace iutnc\deefy\action;

/**
 * Classe de base des actions exécutées par l'application.
 */
abstract class Action {

    protected ?string $http_method = null;
    protected ?string $hostname = null;
    protected ?string $script_name = null;

    protected string $html = '';

    /**
     * Initialise l'action avec les informations de la requête HTTP courante.
     */
    public function __construct() {
        $this->http_method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $this->hostname = $_SERVER['HTTP_HOST'] ?? null;
        $this->script_name = $_SERVER['SCRIPT_NAME'] ?? null;

        $this->html = $this->execute();
    }

    /**
     * Exécute la méthode correspondant à la méthode HTTP reçue.
     *
     * @return string HTML produit par l'action.
     */
    public function execute(): string {
        if ($this->http_method === 'GET') {
            return $this->get();
        }

        if ($this->http_method === 'POST') {
            return $this->post();
        }

        return '<p>Méthode HTTP non supportée.</p>';
    }

    /**
     * Traite une requête GET.
     *
     * @return string Message par défaut indiquant que GET n'est pas implémenté.
     */
    protected function get(): string {
        return '<p>Requête GET non implémentée.</p>';
    }

    /**
     * Traite une requête POST.
     *
     * @return string Message par défaut indiquant que POST n'est pas implémenté.
     */
    protected function post(): string {
        return '<p>Requête POST non implémentée.</p>';
    }

    /**
     * Retourne le HTML généré lors de l'exécution de l'action.
     *
     * @return string Contenu HTML de l'action.
     */
    public function getHtml(): string {
        return $this->html;
    }
}
