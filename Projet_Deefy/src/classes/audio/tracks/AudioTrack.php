<?php
namespace iutnc\deefy\audio\tracks;
use Exception;
use iutnc\deefy\exception\InvalidPropertyNameException;
use iutnc\deefy\exception\InvalidPropertyValueException;


/**
 * Représente une piste audio générique et ses métadonnées communes.
 */
class AudioTrack {
    protected ?int $id = null;
    protected string $titre;
    protected string $date = "Inconnue";
    protected int $genre = self::OTHER;

    protected int $duree = 0;
    protected string $nom_fich;


    // Constantes des genres musicaux ID3v1
    public const BLUES = 0;
    public const CLASSIC_ROCK = 1;
    public const COUNTRY = 2;
    public const DANCE = 3;
    public const DISCO = 4;
    public const FUNK = 5;
    public const GRUNGE = 6;
    public const HIP_HOP = 7;
    public const JAZZ = 8;
    public const METAL = 9;
    public const NEW_AGE = 10;
    public const OLDIES = 11;
    public const OTHER = 12;
    public const POP = 13;
    public const RNB = 14;
    public const RAP = 15;
    public const REGGAE = 16;
    public const ROCK = 17;
    public const TECHNO = 18;
    public const INDUSTRIAL = 19;
    public const ALTERNATIVE = 20;
    public const SKA = 21;
    public const DEATH_METAL = 22;
    public const PRANKS = 23;
    public const SOUNDTRACK = 24;
    public const EURO_TECHNO = 25;
    public const AMBIENT = 26;
    public const TRIP_HOP = 27;
    public const VOCAL = 28;
    public const JAZZ_FUNK = 29;
    public const FUSION = 30;
    public const TRANCE = 31;
    public const CLASSICAL = 32;
    public const INSTRUMENTAL = 33;
    public const ACID = 34;
    public const HOUSE = 35;
    public const GAME = 36;
    public const SOUND_CLIP = 37;
    public const GOSPEL = 38;
    public const NOISE = 39;
    public const ALTERN_ROCK = 40;
    public const BASS = 41;
    public const SOUL = 42;
    public const PUNK = 43;
    public const SPACE = 44;
    public const MEDITATIVE = 45;
    public const INSTRUMENTAL_POP = 46;
    public const INSTRUMENTAL_ROCK = 47;
    public const ETHNIC = 48;
    public const GOTHIC = 49;
    public const DARKWAVE = 50;
    public const TECHNO_INDUSTRIAL = 51;
    public const ELECTRONIC = 52;
    public const POP_FOLK = 53;
    public const EURODANCE = 54;
    public const DREAM = 55;
    public const SOUTHERN_ROCK = 56;
    public const COMEDY = 57;
    public const CULT = 58;
    public const GANGSTA = 59;
    public const TOP_40 = 60;
    public const CHRISTIAN_RAP = 61;
    public const POP_FUNK = 62;
    public const JUNGLE = 63;
    public const NATIVE_AMERICAN = 64;
    public const CABARET = 65;
    public const NEW_WAVE = 66;
    public const PSYCHEDELIC = 67;
    public const RAVE = 68;
    public const SHOWTUNES = 69;
    public const TRAILER = 70;
    public const LO_FI = 71;
    public const TRIBAL = 72;
    public const ACID_PUNK = 73;
    public const ACID_JAZZ = 74;
    public const POLKA = 75;
    public const RETRO = 76;
    public const MUSICAL = 77;
    public const ROCK_N_ROLL = 78;
    public const HARD_ROCK = 79;

    /**
     * Convertit un numéro de genre en chaîne de caractères.
     */



    /**
     * Crée une piste à partir de son titre et du chemin de son fichier.
     */
    public function __construct(string $titre, string $chemin) {
        $this->titre = $titre;
        $this->nom_fich = $chemin;
    }

    /**
     * Retourne les propriétés de la piste au format JSON.
     */
    public function __toString(): string {
        return json_encode(get_object_vars($this));
    }

    /**
     * @throws Exception
     */
    /**
     * Accède à une propriété exposée dynamiquement.
     *
     * @throws InvalidPropertyNameException si la propriété n'existe pas.
     */
    public function __get(string $at):mixed {
        if (property_exists ($this, $at)) return $this->$at;
        throw new InvalidPropertyNameException(" invalid property: $at") ;
    }

    /**
     * Définit l'identifiant de la piste après son insertion en base.
     */
    public function setID(int $id): void {
        $this->id = $id;
    }

    /**
     * @throws Exception
     */
    /**
     * Modifie une métadonnée autorisée après validation de sa valeur.
     *
     * @throws InvalidPropertyValueException si la propriété ou sa valeur est invalide.
     */
    public function __set(string $at, mixed $val): void {
        switch ($at) {
            case "genre":
                if (!is_int($val) || $val < 0 || $val > 79) {
                    throw new InvalidPropertyValueException("Valeur invalide pour $at");
                }
                $this->genre = $val;
                break;

            case "date":
                if (!is_string($val)) {
                    throw new InvalidPropertyValueException("Valeur invalide pour $at");
                }
                $this->date = $val;
                break;

            case "duree":
                if (!is_int($val) || $val < 0) {
                    throw new InvalidPropertyValueException("Votre valeur est invalide pour la $at");
                }
                $this->$at = $val;
                break;

            default:
                throw new InvalidPropertyValueException("Propriété non modifiable ou invalide : $at");
        }
    }

    /**
     * Convertit l'identifiant d'un genre musical en libellé lisible.
     *
     * @param int $genre Identifiant ID3v1 du genre.
     * @return string Le libellé du genre ou « Unknown » si l'identifiant est inconnu.
     */
    public static function getGenreAsString(int $genre): string
    {
        $genres = [
            self::BLUES => 'Blues',
            self::CLASSIC_ROCK => 'Classic Rock',
            self::COUNTRY => 'Country',
            self::DANCE => 'Dance',
            self::DISCO => 'Disco',
            self::FUNK => 'Funk',
            self::GRUNGE => 'Grunge',
            self::HIP_HOP => 'Hip-Hop',
            self::JAZZ => 'Jazz',
            self::METAL => 'Metal',
            self::NEW_AGE => 'New Age',
            self::OLDIES => 'Oldies',
            self::OTHER => 'Other',
            self::POP => 'Pop',
            self::RNB => 'R&B',
            self::RAP => 'Rap',
            self::REGGAE => 'Reggae',
            self::ROCK => 'Rock',
            self::TECHNO => 'Techno',
            self::INDUSTRIAL => 'Industrial',
            self::ALTERNATIVE => 'Alternative',
            self::SKA => 'Ska',
            self::DEATH_METAL => 'Death Metal',
            self::PRANKS => 'Pranks',
            self::SOUNDTRACK => 'Soundtrack',
            self::EURO_TECHNO => 'Euro-Techno',
            self::AMBIENT => 'Ambient',
            self::TRIP_HOP => 'Trip-Hop',
            self::VOCAL => 'Vocal',
            self::JAZZ_FUNK => 'Jazz+Funk',
            self::FUSION => 'Fusion',
            self::TRANCE => 'Trance',
            self::CLASSICAL => 'Classical',
            self::INSTRUMENTAL => 'Instrumental',
            self::ACID => 'Acid',
            self::HOUSE => 'House',
            self::GAME => 'Game',
            self::SOUND_CLIP => 'Sound Clip',
            self::GOSPEL => 'Gospel',
            self::NOISE => 'Noise',
            self::ALTERN_ROCK => 'AlternRock',
            self::BASS => 'Bass',
            self::SOUL => 'Soul',
            self::PUNK => 'Punk',
            self::SPACE => 'Space',
            self::MEDITATIVE => 'Meditative',
            self::INSTRUMENTAL_POP => 'Instrumental Pop',
            self::INSTRUMENTAL_ROCK => 'Instrumental Rock',
            self::ETHNIC => 'Ethnic',
            self::GOTHIC => 'Gothic',
            self::DARKWAVE => 'Darkwave',
            self::TECHNO_INDUSTRIAL => 'Techno-Industrial',
            self::ELECTRONIC => 'Electronic',
            self::POP_FOLK => 'Pop-Folk',
            self::EURODANCE => 'Eurodance',
            self::DREAM => 'Dream',
            self::SOUTHERN_ROCK => 'Southern Rock',
            self::COMEDY => 'Comedy',
            self::CULT => 'Cult',
            self::GANGSTA => 'Gangsta',
            self::TOP_40 => 'Top 40',
            self::CHRISTIAN_RAP => 'Christian Rap',
            self::POP_FUNK => 'Pop/Funk',
            self::JUNGLE => 'Jungle',
            self::NATIVE_AMERICAN => 'Native American',
            self::CABARET => 'Cabaret',
            self::NEW_WAVE => 'New Wave',
            self::PSYCHEDELIC => 'Psychedelic',
            self::RAVE => 'Rave',
            self::SHOWTUNES => 'Showtunes',
            self::TRAILER => 'Trailer',
            self::LO_FI => 'Lo-Fi',
            self::TRIBAL => 'Tribal',
            self::ACID_PUNK => 'Acid Punk',
            self::ACID_JAZZ => 'Acid Jazz',
            self::POLKA => 'Polka',
            self::RETRO => 'Retro',
            self::MUSICAL => 'Musical',
            self::ROCK_N_ROLL => "Rock 'n' Roll",
            self::HARD_ROCK => 'Hard Rock',
        ];

        return $genres[$genre] ?? 'Unknown';
    }
}
