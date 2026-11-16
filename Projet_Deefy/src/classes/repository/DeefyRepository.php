<?php

namespace iutnc\deefy\repository;

use iutnc\deefy\audio\lists\Playlist;
use iutnc\deefy\audio\tracks\AlbumTrack;
use iutnc\deefy\audio\tracks\AudioTrack;
use iutnc\deefy\audio\tracks\PodcastTrack;
use PDO;
use RuntimeException;

/**
 * Centralise les accès aux données de l'application Deefy.
 *
 * La classe utilise une instance PDO unique configurée au démarrage
 * de l'application.
 */
class DeefyRepository {

    private PDO $pdo;
    private static ?DeefyRepository $instance = null;
    private static array $config = [];

    /**
     * Initialise la connexion PDO à partir de la configuration fournie.
     *
     * @param array<string, string> $config Paramètres de connexion à la base.
     */
    private function __construct(array $config) {
        $dsn = $config['driver'] . ':host=' . $config['host'] . ';dbname=' . $config['database'] . ';charset=utf8';

        $this->pdo = new PDO($dsn, $config['username'], $config['password'],
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_STRINGIFY_FETCHES => false,
            ]
        );
    }

    /**
     * Charge la configuration de connexion et réinitialise le singleton.
     *
     * @param string $file Chemin du fichier INI de configuration.
     *
     * @throws RuntimeException Si le fichier est illisible.
     */
    public static function setConfig(string $file): void {
        $config = parse_ini_file($file);

        if ($config === false) {
            throw new RuntimeException(
                "Impossible de lire le fichier de configuration : $file"
            );
        }

        self::$config = $config;
        self::$instance = null;
    }

    /**
     * Retourne l'instance unique du Repository.
     *
     * @return self Instance configurée du Repository.
     *
     * @throws RuntimeException Si aucune configuration n'a été chargée.
     */
    public static function getInstance(): self {
        if (self::$instance === null) {
            if (self::$config === []) {
                throw new RuntimeException(
                    'La configuration du Repository n’a pas été définie.'
                );
            }

            self::$instance = new self(self::$config);
        }

        return self::$instance;
    }

    /**
     * Recherche un utilisateur à partir de son adresse email.
     *
     * @param string $email Adresse email recherchée.
     *
     * @return array{id: int|string, email: string, passwd: string, role: int|string}|null
     *         Données de l'utilisateur ou null si aucun résultat.
     */
    public function findUserByEmail(string $email): ?array {
        $statement = $this->pdo->prepare(
            'SELECT id, email, passwd, role FROM User WHERE email = :email'
        );
        $statement->execute(['email' => $email]);
        $user = $statement->fetch(PDO::FETCH_ASSOC);

        return $user === false ? null : $user;
    }

    /**
     * Crée un utilisateur standard avec un mot de passe déjà haché.
     *
     * @param string $email Adresse email du nouvel utilisateur.
     * @param string $passwordHash Mot de passe haché.
     *
     * @return array{id: int, email: string, role: int} Utilisateur créé.
     */
    public function createUser(string $email, string $passwordHash): array {
        $statement = $this->pdo->prepare(
            'INSERT INTO User (email, passwd, role)
             VALUES (:email, :passwd, 1)'
        );
        $statement->execute([
            'email' => $email,
            'passwd' => $passwordHash,
        ]);

        return [
            'id' => (int) $this->pdo->lastInsertId(),
            'email' => $email,
            'role' => 1,
        ];
    }

    /**
     * Retourne toutes les playlists présentes en base.
     *
     * Les pistes ne sont pas chargées à cette étape.
     *
     * @return Playlist[]
     */
    public function findAllPlaylists(): array {
        $statement = $this->pdo->query(
            'SELECT id, nom FROM playlist ORDER BY id'
        );

        $playlists = [];

        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $playlist = new Playlist($row['nom']);
            $playlist->setID((int) $row['id']);
            $playlists[] = $playlist;
        }

        return $playlists;
    }

    /**
     * Retourne uniquement les playlists appartenant à un utilisateur.
     *
     * @return Playlist[]
     */
    public function findPlaylistsByUserId(int $userId): array {
        $statement = $this->pdo->prepare(
            'SELECT p.id, p.nom
             FROM playlist p
             INNER JOIN user2playlist u2p ON u2p.id_pl = p.id
             WHERE u2p.id_user = :user_id
             ORDER BY p.id'
        );
        $statement->execute(['user_id' => $userId]);

        $playlists = [];
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $playlist = new Playlist($row['nom']);
            $playlist->setID((int) $row['id']);
            $playlists[] = $playlist;
        }

        return $playlists;
    }

    /**
     * Associe une playlist à un utilisateur.
     *
     * @param int $userId Identifiant de l'utilisateur.
     * @param int $playlistId Identifiant de la playlist.
     */
    public function addPlaylistToUser(int $userId, int $playlistId): void {
        $statement = $this->pdo->prepare(
            'INSERT INTO user2playlist (id_user, id_pl)
             VALUES (:user_id, :playlist_id)'
        );
        $statement->execute([
            'user_id' => $userId,
            'playlist_id' => $playlistId,
        ]);
    }

    /**
     * Vérifie qu'un utilisateur possède une playlist.
     *
     * @param int $userId Identifiant de l'utilisateur.
     * @param int $playlistId Identifiant de la playlist.
     *
     * @return bool True si la relation existe, false sinon.
     */
    public function isPlaylistOwnedByUser(int $userId, int $playlistId): bool {
        $statement = $this->pdo->prepare(
            'SELECT 1
             FROM user2playlist
             WHERE id_user = :user_id AND id_pl = :playlist_id'
        );
        $statement->execute([
            'user_id' => $userId,
            'playlist_id' => $playlistId,
        ]);

        return $statement->fetchColumn() !== false;
    }

    /**
     * Enregistre une playlist vide et lui attribue son identifiant.
     */
    public function saveEmptyPlaylist(Playlist $playlist): Playlist {
        $statement = $this->pdo->prepare(
            'INSERT INTO playlist (nom) VALUES (:nom)'
        );

        $statement->execute([
            'nom' => $playlist->nom,
        ]);

        $playlist->setID((int) $this->pdo->lastInsertId());

        return $playlist;
    }

    /**
     * Enregistre une piste et lui attribue son identifiant.
     */
    /**
     * Enregistre une piste audio et lui attribue son identifiant.
     *
     * @param AudioTrack $track Piste à enregistrer.
     *
     * @return AudioTrack Piste complétée avec son identifiant.
     */
    public function saveTrack(AudioTrack $track): AudioTrack {
        $type = $track instanceof PodcastTrack ? 'P' : 'A';
        $artiste = null;
        $titreAlbum = null;
        $numeroAlbum = null;
        $auteurPodcast = null;

        if ($track instanceof AlbumTrack) {
            $artiste = $track->artiste;
            $titreAlbum = $track->album;
            $numeroAlbum = $track->num_piste;
        }

        if ($track instanceof PodcastTrack) {
            $auteurPodcast = $track->auteur;
        }

        $statement = $this->pdo->prepare(
            'INSERT INTO track
                (titre, duree, filename, type, artiste_album, titre_album,
                 numero_album, auteur_podcast)
             VALUES
                (:titre, :duree, :filename, :type, :artiste, :titre_album,
                 :numero_album, :auteur_podcast)'
        );

        $statement->execute([
            'titre' => $track->titre,
            'duree' => $track->duree,
            'filename' => $track->nom_fich,
            'type' => $type,
            'artiste' => $artiste,
            'titre_album' => $titreAlbum,
            'numero_album' => $numeroAlbum,
            'auteur_podcast' => $auteurPodcast,
        ]);

        $track->setID((int) $this->pdo->lastInsertId());

        return $track;
    }

    /**
     * Ajoute une piste existante à une playlist existante.
     */
    /**
     * Ajoute une piste à la fin d'une playlist.
     *
     * @param int $playlistId Identifiant de la playlist.
     * @param int $trackId Identifiant de la piste.
     */
    public function addTrackToPlaylist(int $playlistId, int $trackId): void {
        $statement = $this->pdo->prepare(
            'SELECT COALESCE(MAX(no_piste_dans_liste), 0) + 1
             FROM playlist2track
             WHERE id_pl = :playlist_id'
        );
        $statement->execute(['playlist_id' => $playlistId]);
        $position = (int) $statement->fetchColumn();

        $statement = $this->pdo->prepare(
            'INSERT INTO playlist2track
                (id_pl, id_track, no_piste_dans_liste)
             VALUES
                (:playlist_id, :track_id, :position)'
        );

        $statement->execute([
            'playlist_id' => $playlistId,
            'track_id' => $trackId,
            'position' => $position,
        ]);
    }

    /**
     * Retire une piste d'une playlist.
     *
     * @param int $playlistId Identifiant de la playlist.
     * @param int $trackId Identifiant de la piste.
     */
    public function removeTrackFromPlaylist(int $playlistId, int $trackId): void {
        $statement = $this->pdo->prepare(
            'DELETE FROM playlist2track
             WHERE id_pl = :playlist_id AND id_track = :track_id'
        );
        $statement->execute([
            'playlist_id' => $playlistId,
            'track_id' => $trackId,
        ]);
    }

    /**
     * Supprime une playlist et ses relations dans une transaction.
     *
     * @param int $playlistId Identifiant de la playlist à supprimer.
     *
     * @throws \Throwable Si une étape de suppression échoue.
     */
    public function deletePlaylist(int $playlistId): void {
        $this->pdo->beginTransaction();

        try {
            $statement = $this->pdo->prepare(
                'DELETE FROM user2playlist WHERE id_pl = :playlist_id'
            );
            $statement->execute(['playlist_id' => $playlistId]);

            $statement = $this->pdo->prepare(
                'DELETE FROM playlist2track WHERE id_pl = :playlist_id'
            );
            $statement->execute(['playlist_id' => $playlistId]);

            $statement = $this->pdo->prepare(
                'DELETE FROM playlist WHERE id = :playlist_id'
            );
            $statement->execute(['playlist_id' => $playlistId]);

            $this->pdo->commit();
        } catch (\Throwable $exception) {
            $this->pdo->rollBack();
            throw $exception;
        }
    }

    /**
     * Recharge une playlist avec ses pistes.
     */
    /**
     * Recharge une playlist et toutes ses pistes depuis la base.
     *
     * @param int $playlistId Identifiant de la playlist.
     *
     * @return Playlist Playlist reconstituée.
     *
     * @throws RuntimeException Si la playlist n'existe pas.
     */
    public function findPlaylistById(int $playlistId): Playlist {
        $statement = $this->pdo->prepare(
            'SELECT id, nom FROM playlist WHERE id = :id'
        );
        $statement->execute(['id' => $playlistId]);
        $row = $statement->fetch(PDO::FETCH_ASSOC);

        if ($row === false) {
            throw new RuntimeException("Playlist introuvable : $playlistId");
        }

        $playlist = new Playlist($row['nom']);
        $playlist->setID((int) $row['id']);

        $statement = $this->pdo->prepare(
            'SELECT t.*
             FROM track t
             INNER JOIN playlist2track p2t ON p2t.id_track = t.id
             WHERE p2t.id_pl = :playlist_id
             ORDER BY p2t.no_piste_dans_liste'
        );
        $statement->execute(['playlist_id' => $playlistId]);

        while ($trackRow = $statement->fetch(PDO::FETCH_ASSOC)) {
            $track = $this->trackFromRow($trackRow);
            $playlist->ajouterPiste($track);
        }

        return $playlist;
    }

    /**
     * @return AudioTrack
     */
    /**
     * Transforme une ligne SQL en objet de piste adapté à son type.
     *
     * @param array<string, mixed> $row Ligne issue de la table track.
     *
     * @return AudioTrack Piste podcast ou album reconstituée.
     */
    private function trackFromRow(array $row): AudioTrack {
        if ($row['type'] === 'P') {
            $track = new PodcastTrack($row['titre'], $row['filename']);
            $track->auteur = $row['auteur_podcast'] ?? 'Inconnue';
        } else {
            $track = new AlbumTrack(
                $row['titre'],
                $row['filename'],
                $row['titre_album'] ?? 'Inconnue',
                (int) ($row['numero_album'] ?? 0)
            );
            $track->artiste = $row['artiste_album'] ?? 'Inconnue';
        }

        $track->setID((int) $row['id']);
        $track->duree = (int) ($row['duree'] ?? 0);

        return $track;
    }
}
