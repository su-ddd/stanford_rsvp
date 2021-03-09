<?php


namespace Drupal\stanford_rsvp\Service;


use Drupal;
use Drupal\stanford_rsvp\Model\Ticket;
use Drupal\stanford_rsvp\Model\Event;
use Drupal\stanford_rsvp\Model\TicketType;
use Drupal\user\Entity\User;
use Exception;

class Registrar
{

    const RESULT_NO_ROOM = 0;
    const RESULT_REGISTERED_SUCCESSFULLY = 1;
    /*
No room (no email)
Registered successfully
Tried to register but was waitlisted instead
Waitlisted successfully
Cancelled successfully
    */

    /**
     * @var EventLoader
     */
    private $eventLoader;

    /**
     * @var TicketLoader
     */
    private $ticketLoader;

    /**
     * @var Notifier
     */
    private $notifier;

    public function __construct(EventLoader $eventLoader,
                                TicketLoader $ticketLoader,
                                Notifier $notifier)
    {
        $this->eventLoader = $eventLoader;
        $this->ticketLoader = $ticketLoader;
        $this->notifier = $notifier;
    }

    /**
     * @param User $user
     * @param Event $event
     * @param TicketType $ticket_type
     * @return Ticket|null
     */
    public function register(User $user, Event $event, TicketType $ticket_type): ?Ticket
    {
        if ($ticket_type->getTicketType() == TicketType::TYPE_CANCELLATION) {
            $ticket = $this->save($user, $ticket_type, Ticket::STATUS_REGISTERED);
            if ($ticket) {
                $this->notifier->notify($user, $event, $ticket);
                return $ticket;
            }
        } elseif ($event->hasSpaceAvailable() && $ticket_type->hasSpaceAvailable()) {
            $ticket = $this->save($user, $ticket_type, Ticket::STATUS_REGISTERED);
            if ($ticket) {
                $this->notifier->notify($user, $event, $ticket);
                //TODO: send calendar invite
                return $ticket;
            }
        } elseif ($ticket_type->hasWaitlistAvailable()) {
            return $this->waitlist($user, $event, $ticket_type);
        } else {
            Drupal::messenger()->addWarning(t('No space available'));
            return null;
        }
    }

    /**
     * @param User $user
     * @param Event $event
     * @param TicketType $ticket_type
     * @return Ticket|null
     */
    public function waitlist(User $user, Event $event, TicketType $ticket_type): ?Ticket
    {
        if ($ticket_type->hasWaitlistAvailable()) {
            $ticket = $this->save($user, $ticket_type, Ticket::STATUS_WAITLISTED);
            if ($ticket) {
                $this->notifier->notify($user, $event, $ticket);
                return $ticket;
            }
        } else {
            return null;
        }
    }

    /**
     * @param User $user
     * @param Event $event
     * @param TicketType $ticket_type
     * @return Ticket
     */
    public function cancel(User $user, Event $event, TicketType $ticket_type): Ticket
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
    public function delete(User $user, TicketType $ticketType)
    {

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
     * @return ?Ticket
     */
    private function save(User $user, TicketType $ticket_type, int $status): ?Ticket
    {
        $now = time();

        $event_id = $ticket_type->getEventId();

        $ticket = new Ticket($user, $status, $event_id, $ticket_type->getId(), $now);

        $database = Drupal::database();

        try {
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
        } catch (Exception $exception) {
            return null;
        }
    }

}