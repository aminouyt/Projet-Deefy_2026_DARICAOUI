<?php

namespace iutnc\deefy\auth;

use iutnc\deefy\exception\AuthnException;
use iutnc\deefy\repository\DeefyRepository;

/**
 * Fournit les opérations d'inscription et de connexion des utilisateurs.
 */
class AuthnProvider {

    /**
     * Authentifie un utilisateur avec son email et son mot de passe.
     *
     * @param string $email Adresse email saisie.
     * @param string $password Mot de passe en clair saisi.
     *
     * @return array Données de l'utilisateur authentifié.
     *
     * @throws AuthnException Si les identifiants sont invalides.
     */
    public static function signin(string $email, string $password): array {
        $user = DeefyRepository::getInstance()->findUserByEmail($email);

        if ($user === null || !password_verify($password, $user['passwd'])) {
            throw new AuthnException('Email ou mot de passe incorrect.');
        }

        return $user;
    }

    /**
     * Inscrit un utilisateur standard après validation de son mot de passe.
     *
     * @param string $email Adresse email du nouvel utilisateur.
     * @param string $password Mot de passe en clair à hacher.
     *
     * @return array Données de l'utilisateur créé.
     *
     * @throws AuthnException Si le mot de passe est trop court ou l'email déjà utilisé.
     */
    public static function register(string $email, string $password): array {
        if (strlen($password) < 10) {
            throw new AuthnException(
                'Le mot de passe doit contenir au moins 10 caractères.'
            );
        }

        $repository = DeefyRepository::getInstance();

        if ($repository->findUserByEmail($email) !== null) {
            throw new AuthnException('Un compte existe déjà avec cet email.');
        }

        $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);

        return $repository->createUser($email, $hash);
    }
}
