<?php

namespace iutnc\deefy\action;

use iutnc\deefy\auth\AuthnProvider;
use iutnc\deefy\exception\AuthnException;

/**
 * Gère l'affichage et le traitement du formulaire de connexion.
 */
class SigninAction extends Action {

    /**
     * Affiche le formulaire de connexion.
     *
     * @return string HTML du formulaire.
     */
    protected function get(): string {
        return <<<HTML
        <section class="form-section">
            <h2 class="page-title">Connexion</h2>

            <form class="form" method="POST" action="?action=signin">
                <div class="form-group">
                    <label for="email">Email :</label>
                    <input type="email" name="email" id="email" required>
                </div>

                <div class="form-group">
                    <label for="passwd">Mot de passe :</label>
                    <input type="password" name="passwd" id="passwd" required>
                </div>

                <button class="form-button" type="submit">Se connecter</button>
            </form>
        </section>
        HTML;
    }

    /**
     * Authentifie l'utilisateur et initialise sa session.
     *
     * @return string HTML d'erreur ou chaîne vide après redirection.
     */
    protected function post(): string {
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = $_POST['passwd'] ?? '';

        if ($email === false || $email === null || $password === '') {
            return '<p class="message message-error">Veuillez remplir correctement tous les champs.</p>'
                . $this->get();
        }

        try {
            $user = AuthnProvider::signin($email, $password);
        } catch (AuthnException $exception) {
            return '<p class="message message-error">'
                . htmlspecialchars($exception->getMessage(), ENT_QUOTES, 'UTF-8')
                . '</p>' . $this->get();
        }

        $_SESSION['user'] = [
            'id' => (int) $user['id'],
            'email' => $user['email'],
            'role' => (int) $user['role'],
        ];

        session_regenerate_id(true);
        unset($_SESSION['current_playlist_id']);
        header('Location: ?action=playlist');
        return '';
    }
}
