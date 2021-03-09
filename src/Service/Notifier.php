<?php


namespace Drupal\stanford_rsvp\Service;


use Drupal;
use Drupal\stanford_rsvp\Model\Event;
use Drupal\stanford_rsvp\Model\Ticket;
use Drupal\user\Entity\User;

class Notifier
{
    /**
     * @param User $user
     * @param Event $event
     * @param Ticket $ticket
     */
    public function notify(User $user, Event $event, Ticket $ticket) {
        Drupal::messenger()->addStatus($user->getDisplayName() . ' being notified about ' . $event->getTicketTypeById($ticket->getTicketTypeId())->getName() . ' with status: ' . $ticket->getStatus());
    }

}