<?php

namespace iutnc\deefy\action;

/**
 * Fabrique les actions correspondant au paramètre de routage.
 */
class ActionFactory {

    /**
     * Crée l'action associée à un nom d'action HTTP.
     *
     * @param string $action Nom de l'action demandé.
     *
     * @return Action Action correspondante ou action par défaut.
     */
    public static function getAction(string $action): Action {
        switch ($action) {
            case 'default':
                return new DefaultAction();

            case 'playlist':
                return new DisplayPlaylistAction();

            case 'select-playlist':
                return new SelectPlaylistAction();

            case 'current-playlist':
                return new CurrentPlaylistAction();

            case 'add-playlist':
                return new AddPlaylistAction();

            case 'add-track':
                return new AddPodcastTrackAction();

            case 'add-user':
                return new AddUserAction();

            case 'signin':
                return new SigninAction();

            case 'delete-playlist':
                return new DeletePlaylistAction();

            case 'delete-track':
                return new DeleteTrackAction();

            case 'logout':
                return new LogoutAction();

            default:
                return new DefaultAction();
        }
    }
}
