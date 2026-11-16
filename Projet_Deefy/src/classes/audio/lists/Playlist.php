<?php
namespace  iutnc\deefy\audio\lists;
use iutnc\deefy\audio\tracks\AudioTrack;

/**
 * Liste de lecture modifiable contenant des pistes audio.
 */
class Playlist extends AudioList {
    /**
     * Crée une playlist éventuellement remplie de pistes.
     */
    public function __construct(string $n, array $tab = []) {
        parent::__construct($n, $tab);
    }

    /**
     * Ajoute une piste et met à jour les compteurs de la playlist.
     */
    public function ajouterPiste(AudioTrack $piste): void {
        foreach ($this->tab_piste as $p) {
            if ($p->nom_fich === $piste->nom_fich) {
                return; // La piste existe déjà
            }
        }

        $this->tab_piste[] = $piste;
        $this->nb_piste++;
        $this->duree_tot += $piste->duree;
    }

    /**
     * Supprime la piste correspondant à un indice si elle existe.
     */
    public function supprimerPiste(int $indice): void {
        if (!isset($this->tab_piste[$indice])) {
            return;
        }

        $this->duree_tot -= $this->tab_piste[$indice]->duree;
        unset($this->tab_piste[$indice]);
        $this->nb_piste--;

        $this->tab_piste = array_values($this->tab_piste);
    }

    /**
     * Ajoute les pistes audio d'un tableau sans créer de doublons.
     */
    public function ajouterPlaylist(array $pistes): void {
        foreach ($pistes as $piste) {
            if (!$piste instanceof AudioTrack) {
                continue;
            }

            if (!in_array($piste, $this->tab_piste, true)) {
                $this->ajouterPiste($piste);
            }
        }
    }
}