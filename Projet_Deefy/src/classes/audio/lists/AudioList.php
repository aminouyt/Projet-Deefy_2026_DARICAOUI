<?php
namespace  iutnc\deefy\audio\lists;

use Iterator;
use iutnc\deefy\exception\InvalidPropertyNameException;

/**
 * Regroupe des pistes audio et permet de les parcourir comme un itérateur.
 */
class AudioList implements Iterator {

    protected ?int $id = null;
    protected string $nom;

    protected int $nb_piste;
    protected int $duree_tot;
    protected array $tab_piste;
    protected int $position;
    /**
     * Crée une liste et calcule son nombre de pistes et sa durée totale.
     */
    public function __construct(string $n, array $tab = []) {
        $this->nom = $n;
        $this->tab_piste = $tab;

        $this->nb_piste = count($tab);

        $this->duree_tot = 0;
        foreach ($tab as $piste) {
            $this->duree_tot += $piste->duree;
        }
    }


    /**
     * Accède à une propriété de la liste.
     *
     * @throws InvalidPropertyNameException si la propriété n'existe pas.
     */
    public function __get(string $at): mixed {
        if (property_exists($this, $at)) {
            return $this->$at;
        }

        throw new InvalidPropertyNameException("Propriété invalide : $at");
    }

    /**
     * Définit l'identifiant de la liste après son insertion en base.
     */
    public function setID(int $id): void {
        $this->id = $id;
    }

    /**
     * Retourne la piste située à la position courante.
     */
    public function current(): mixed
    {
        return $this->tab_piste[$this->position];
    }

    /**
     * Avance l'itérateur d'une position.
     */
    public function next(): void
    {
        $this->position++;
    }

    /**
     * Retourne l'index de la position courante.
     */
    public function key(): mixed
    {
        return $this->position;
    }

    /**
     * Indique si la position courante correspond à une piste.
     */
    public function valid(): bool
    {
        return isset($this->tab_piste[$this->position]);
    }

    /**
     * Replace l'itérateur au début de la liste.
     */
    public function rewind(): void
    {
       $this->position = 0;
    }
}