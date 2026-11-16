<?php

namespace iutnc\deefy\action;

use iutnc\deefy\auth\AuthnProvider;
use iutnc\deefy\exception\AuthnException;

/**
 * Gère l'affichage et le traitement du formulaire d'inscription.
 */
class AddUserAction extends Action {

    /**
     * Affiche le formulaire d'inscription.
     *
     * @return string HTML du formulaire.
     */
    protected function get(): string {
        return <<<HTML
        <section class="form-section">
            <h2 class="page-title">Inscription</h2>

            <form class="form" method="POST" action="?action=add-user">
                <div class="form-group">
                    <label for="email">Email :</label>
                    <input type="email" name="email" id="email" required>
                </div>

                <div class="form-group">
                    <label for="passwd">Mot de passe :</label>
                    <input type="password" name="passwd" id="passwd" required>
                </div>

                <div class="form-group">
                    <label for="passwd-confirm">Confirmation du mot de passe :</label>
                    <input type="password" name="passwd-confirm" id="passwd-confirm" required>
                </div>

                <button class="form-button" type="submit">S'inscrire</button>
            </form>
        </section>
    HTML;
    }

    /**
     * Valide puis crée le compte utilisateur.
     *
     * @return string HTML d'erreur ou message de succès.
     */
    protected function post(): string {
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = $_POST['passwd'] ?? '';
        $passwordConfirmation = $_POST['passwd-confirm'] ?? '';

        if (
            $email === null || $email === false
            || $password === ''
            || $password !== $passwordConfirmation
        ) {
            return '<p class="message message-error">Les informations saisies sont invalides ou les mots de passe ne correspondent pas.</p>'
                . $this->get();
        }

        try {
            AuthnProvider::register($email, $password);
        } catch (AuthnException $exception) {
            return '<p class="message message-error">'
                . htmlspecialchars($exception->getMessage(), ENT_QUOTES, 'UTF-8')
                . '</p>' . $this->get();
        }

        return '<p class="message message-success">Inscription réussie.</p>';
    }
}
