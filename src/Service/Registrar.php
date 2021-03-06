<?php


namespace Drupal\stanford_rsvp\Service;


use Drupal;
use Drupal\stanford_rsvp\Model\Ticket;
use Drupal\stanford_rsvp\Model\TicketType;
use Drupal\user\Entity\User;
use Exception;

class Registrar
{
    private $eventLoader;

    private $ticketLoader;

    public function __construct(EventLoader $eventLoader, TicketLoader $ticketLoader) {
        $this->eventLoader = $eventLoader;
        $this->ticketLoader = $ticketLoader;
    }

    /**
     * @param User $user
     * @param TicketType $ticket_type
     * @return Ticket
     * @throws Exception
     */
    public function register(User $user, TicketType $ticket_type): Ticket
    {
        // TODO: send notification
        // TODO: if no room, and waitlist open, place person on waitlist, send appropriate message.
        // TODO: if no room, and no waitlist, return exception
        return $this->save($user, $ticket_type, Ticket::STATUS_REGISTERED);
    }

    /**
     * @param User $user
     * @param TicketType $ticket_type
     * @return Ticket
     * @throws Exception
     */
    public function waitlist(User $user, TicketType $ticket_type): Ticket
    {
        // TODO: send notification
        return $this->save($user, $ticket_type, Ticket::STATUS_WAITLISTED);
    }

    /**
     * @param User $user
     * @param TicketType $ticket_type
     * @return Ticket
     * @throws Exception
     */
    public function cancel(User $user, TicketType $ticket_type): Ticket
    {

        // TODO: waitlist
        // If there is a waitlist, delete the user, then register the first person on the waitlist
        // If there is no waitlist, delete the user

        // TODO: send notification
        return $this->save($user, $ticket_type, Ticket::STATUS_CANCELLED);

        // TODO: waitlist
        // Get the first person on the waitlist, if any

    }

    /**
     * @param User $user
     * @param TicketType $ticketType
     */
    public function delete(User $user, TicketType $ticketType) {

        $database = Drupal::database();

        $database->delete('stanford_rsvp_rsvps')
            ->condition('uid', $user->id())
            ->condition('tid', $ticketType->getId())
            ->execute();

        //TODO: find a better sentence
        Drupal::messenger()->addStatus(t('Ticket Cancelled'));


        $waitlist = $ticketType->getWaitList();

        Drupal::messenger()->addStatus("Waitlist Size: " . count($waitlist));
    }

    /**
     * @param User $user
     * @param TicketType $ticket_type
     * @param int $status
     * @return Ticket
     * @throws Exception
     */
    private function save(User $user, TicketType $ticket_type, int $status): Ticket
    {
        $now = time();

        $event_id = $ticket_type->getEventId();

        $ticket = new Ticket($user, $status, $event_id, $ticket_type->getId(), $now);

        $database = Drupal::database();

        $database->merge('stanford_rsvp_rsvps')
            ->key('uid', $user->id())
            ->key('nid', $event_id)
            ->fields([
                'tid' => $ticket_type->getId(),
                'status' => $status,
                'created' => Drupal::time()->getRequestTime(),
            ])
            ->execute();

        return $ticket;
    }

}