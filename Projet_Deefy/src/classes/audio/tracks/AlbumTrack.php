<?php
namespace iutnc\deefy\audio\tracks;
use iutnc\deefy\exception\InvalidPropertyValueException;


/**
 * Représente une piste appartenant à un album.
 */
class AlbumTrack extends AudioTrack {
    private string $artiste = "Inconnue";
    private string $album;
    private int $num_piste;

    /**
     * Crée une piste d'album avec son album et son numéro de piste.
     */
    public function __construct(string $titre, string $chemin, string $nom_alb, int $num) {
        parent::__construct($titre, $chemin);
        $this->album = $nom_alb;
        $this->num_piste = $num;
    }

    /**
     * Accède aux propriétés propres à l'album ou à celles de la piste.
     *
     * @throws InvalidPropertyNameException si la propriété n'existe pas.
     */
    public function __get(string $at): mixed {
        if ($at === "artiste" || $at === "album" || $at === "num_piste") {
            return $this->$at;
        }
        return parent::__get($at);
    }

    /**
     * Modifie l'artiste ou une métadonnée héritée autorisée.
     *
     * @throws InvalidPropertyValueException si la valeur est invalide.
     */
    public function __set(string $at, mixed $val): void {
        if ($at === "artiste") {
            if (!is_string($val)) {
                throw new InvalidPropertyValueException("Valeur invalide pour $at");
            }
            $this->artiste = $val;
            return;
        }
        parent::__set($at, $val);
    }
}
