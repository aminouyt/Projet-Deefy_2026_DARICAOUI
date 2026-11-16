<?php
namespace  iutnc\deefy\audio\lists;

use iutnc\deefy\exception\InvalidPropertyValueException;


/**
 * Liste de pistes appartenant à un album et associée à un artiste et une date.
 */
class AlbumList extends AudioList{

    private string $artiste;
    private string $date;

    /**
     * Crée une liste d'album avec ses pistes initiales.
     */
    public function __construct(string $n, array $tab){
        parent::__construct($n, $tab);
    }


    /**
     * Définit l'artiste ou la date de l'album.
     *
     * @throws InvalidPropertyValueException si la propriété n'est pas autorisée.
     */
    public function __set(string $at, string $val): void{
        switch ($at){
            case "date" :
            case "artiste":
                $this->$at = $val;
                break;
            default :
                throw new InvalidPropertyValueException("Valeur invalide pour $at");
        }
    }

    /**
     * Accède aux métadonnées de l'album ou à celles de la liste.
     */
    public function __get(string $at): mixed {
        if ($at === "artiste" || $at === "date") {
            return $this->$at;
        }

        return parent::__get($at);
    }
}