<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class TasksVoter extends Voter
{
    public const EDIT = 'TASK_EDIT';
    public const VIEW = 'TASK_VIEW';

    public const DELETE = 'TASK_DELETE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::EDIT, self::VIEW, self::DELETE])
            && $subject instanceof \App\Entity\Tasks;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::EDIT:
                foreach ($subject->getUtilisateur() as $userr) {
                    if ($userr === $user || $user->getroles()[0] === 'ROLE_ADMIN') {
                        return true; // Retourne true si l'utilisateur est trouvé
                    }
                }
                break;

            case self::VIEW:
                return true;

            case self::DELETE:
                foreach ($subject->getUtilisateur() as $userr) {
                    if ($userr === $user || $user->getroles()[0] === 'ROLE_ADMIN') {
                        return true;
                    }
                }
                break;
        }

        return false;
    }
}
