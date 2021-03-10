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
     * @param ?Ticket $ticket
     * @param int $result
     */
    public function notify(User $user, Event $event, ?Ticket $ticket, int $result) {
        switch ($result) {
            case Registrar::RESULT_CANCELLED:
                Drupal::messenger()->addStatus('To: ' . $user->getDisplayName() . ' You cancelled your registration for ' . $event->getName());
                break;
            default:
                Drupal::messenger()->addStatus('To: ' . $user->getDisplayName() . ' being notified about ' . $event->getTicketTypeById($ticket->getTicketTypeId())->getName() . ' with status: ' . $ticket->getStatus());
        }
    }

}