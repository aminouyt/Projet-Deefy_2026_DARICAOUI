<?php
namespace iutnc\deefy\audio\tracks;
use iutnc\deefy\exception\InvalidPropertyNameException;
use iutnc\deefy\exception\InvalidPropertyValueException;


/**
 * Représente une piste audio issue d'un podcast.
 */
class PodcastTrack extends AudioTrack {
    private string $auteur = "Inconnue";

    /**
     * Crée une piste de podcast à partir de son titre et de son fichier.
     */
    public function __construct(string $titre, string $chemin) {
        parent::__construct($titre, $chemin);
    }

    /**
     * Accède à l'auteur du podcast ou à une propriété héritée.
     *
     * @throws InvalidPropertyNameException si la propriété n'existe pas.
     */
    public function __get(string $at): mixed {
        if ($at === "auteur") {
            return $this->auteur;
        }

        return parent::__get($at);
    }

    /**
     * Modifie l'auteur ou une métadonnée héritée autorisée.
     *
     * @throws InvalidPropertyValueException si la valeur est invalide.
     */
    public function __set(string $at, mixed $val): void {
        if ($at === "auteur") {
            if (!is_string($val)) {
                throw new InvalidPropertyValueException("Valeur invalide pour $at");
            }

            $this->auteur = $val;
            return;
        }

        parent::__set($at, $val);
    }
}
