<?php


namespace Drupal\stanford_rsvp\Service;


use Drupal;
use Drupal\user\Entity\User;

class Calendar
{
    /**
     * @param User $user
     */
    public function invite(User $user) {
        Drupal::messenger()->addStatus('Invite ' . $user->getDisplayName());
    }

}