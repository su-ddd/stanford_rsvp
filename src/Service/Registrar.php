<?php


namespace Drupal\stanford_rsvp\Service;


use Drupal;
use Drupal\stanford_rsvp\Model\Event;
use Drupal\stanford_rsvp\Model\Ticket;
use Drupal\stanford_rsvp\Model\TicketType;
use Drupal\user\Entity\User;
use Exception;

class Registrar
{
    /**
     * @param User $user
     * @param Event $event
     * @param TicketType $ticket_type
     * @return Ticket
     * @throws Exception
     */
    public function register(User $user, Event $event, TicketType $ticket_type): Ticket
    {
        // TODO: send notification
        return $this->save($user, $event, $ticket_type, Ticket::STATUS_REGISTERED);
    }

    /**
     * @param User $user
     * @param Event $event
     * @param TicketType $ticket_type
     * @return Ticket
     * @throws Exception
     */
    public function waitlist(User $user, Event $event, TicketType $ticket_type): Ticket
    {
        // TODO: send notification
        return $this->save($user, $event, $ticket_type, Ticket::STATUS_WAITLISTED);
    }

    /**
     * @param User $user
     * @param Event $event
     * @param TicketType $ticket_type
     * @return Ticket
     * @throws Exception
     */
    public function cancel(User $user, Event $event, TicketType $ticket_type): Ticket
    {
        // TODO: send notification
        return $this->save($user, $event, $ticket_type, Ticket::STATUS_CANCELLED);
    }

    /**
     * @param User $user
     * @param Event $event
     */
    public function delete(User $user, Event $event) {
        $database = Drupal::database();

        $database->delete('stanford_rsvp_rsvps')
            ->condition('uid', $user->id())
            ->condition('nid', $event->getId())
            ->execute();

        //TODO: find a better sentence
        Drupal::messenger()->addStatus(t('Ticket Cancelled'));
    }

    /**
     * @param User $user
     * @param Event $event
     * @param TicketType $ticket_type
     * @param int $status
     * @return Ticket
     * @throws Exception
     */
    private function save(User $user, Event $event, TicketType $ticket_type, int $status): Ticket
    {
        $now = time();
        $ticket = new Ticket($user, $status, $event, $ticket_type, $now);

        $database = Drupal::database();

        $database->merge('stanford_rsvp_rsvps')
            ->key('uid', $user->id())
            ->key('nid', $event->getId())
            ->fields([
                'tid' => $ticket_type->getId(),
                'status' => $status,
                'created' => Drupal::time()->getRequestTime(),
            ])
            ->execute();

        return $ticket;
    }

}