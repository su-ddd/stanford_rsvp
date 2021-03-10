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
    const RESULT_REGISTERED = 1;
    const RESULT_WAITLISTED = 2;
    const RESULT_CANCELLED = 3;
    const RESULT_WAITLISTED_AFTER_REGISTRATION_FULL = 4;
    const RESULT_ERROR = 5;

    /**
     * @var EventLoader
     */
    private $eventLoader;

    /**
     * @var TicketTypeLoader
     */
    private $ticketTypeLoader;

    /**
     * @var TicketLoader
     */
    private $ticketLoader;

    /**
     * @var Notifier
     */
    private $notifier;

    /**
     * @var Calendar
     */
    private $calendar;

    public function __construct(EventLoader $eventLoader,
                                TicketTypeLoader $ticketTypeLoader,
                                TicketLoader $ticketLoader,
                                Notifier $notifier,
                                Calendar $calendar)
    {
        $this->eventLoader = $eventLoader;
        $this->ticketTypeLoader = $ticketTypeLoader;
        $this->ticketLoader = $ticketLoader;
        $this->notifier = $notifier;
        $this->calendar = $calendar;
    }

    /**
     * @param User $user
     * @param Event $event
     * @param TicketType $ticket_type
     * @param Ticket|null $previous_ticket
     * @return int
     */
    public function register(User $user, Event $event, TicketType $ticket_type, ?Ticket $previous_ticket = null): int
    {

        // People can register for a ticket of type CANCELLATION (e.g. they are RSVP'ing NO to an invitation).
        // This type of ticket is recorded. There is no space limit for these types of ticket.

        if ($ticket_type->getTicketType() == TicketType::TYPE_CANCELLATION) {
            $ticket = $this->save($user, $ticket_type, Ticket::STATUS_REGISTERED);
            if ($ticket) {
                $this->notifier->notify($user, $event, $ticket, Registrar::RESULT_REGISTERED);
                return Registrar::RESULT_REGISTERED;
            }
            else {
                return Registrar::RESULT_ERROR;
            }
        }

        // Handle other types of ticket (e.g. In person, Remote)

        if ($event->hasSpaceAvailable() && $ticket_type->hasSpaceAvailable()) {
            $ticket = $this->save($user, $ticket_type, Ticket::STATUS_REGISTERED);
            // If the registration was successful
            if ($ticket) {
                // TODO: Notify User
                $this->notifier->notify($user, $event, $ticket, Registrar::RESULT_REGISTERED);
                // TODO: Calendar Invite
                $this->calendar->invite($user);

                if ($previous_ticket) {
                    $this->updateWaitlist($previous_ticket);
                }
                return self::RESULT_REGISTERED;
            }
            else {
                return self::RESULT_ERROR;
            }
        } elseif ($ticket_type->hasWaitlistAvailable()) {
            return $this->waitlist($user, $event, $ticket_type, true, $previous_ticket);
        } else {
            Drupal::messenger()->addWarning(t('The option you selected is now full.'));
            return Registrar::RESULT_NO_ROOM;
        }
    }

    /**
     * @param User $user
     * @param Event $event
     * @param TicketType $ticket_type
     * @param bool $attempted_registration
     * @param Ticket|null $previous_ticket
     * @return int
     */
    public function waitlist(User $user, Event $event, TicketType $ticket_type, $attempted_registration = false, ?Ticket $previous_ticket = null): int
    {
        if ($ticket_type->hasWaitlistAvailable()) {
            $ticket = $this->save($user, $ticket_type, Ticket::STATUS_WAITLISTED);
            if ($ticket) {
                if ($attempted_registration) {
                    $result_code = self::RESULT_WAITLISTED_AFTER_REGISTRATION_FULL;
                }
                else {
                    $result_code = self::RESULT_WAITLISTED;
                }
                $this->notifier->notify($user, $event, $ticket, $result_code);
                if ($previous_ticket) {
                    $this->updateWaitlist($previous_ticket);
                }
                return $result_code;
            } else {
                return self::RESULT_ERROR;
            }
        } else {
            return self::RESULT_ERROR;
        }
    }

    /**
     * @param User $user
     * @param Event $event
     * @param TicketType $ticket_type
     * @param Ticket|null $current_ticket
     * @return int
     */
    public function cancel(User $user, Event $event, TicketType $ticket_type, ?Ticket $current_ticket): int
    {
        $database = Drupal::database();

        try {
            $database->delete('stanford_rsvp_rsvps')
                ->condition('uid', $user->id())
                ->condition('tid', $ticket_type->getId())
                ->execute();
            $this->notifier->notify($user, $event, null, Registrar::RESULT_CANCELLED);
            $this->updateWaitlist($current_ticket);
            return self::RESULT_CANCELLED;
        }
        catch (Exception $exception) {
            return self::RESULT_ERROR;
        }
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

    private function updateWaitlist(Ticket $previous_ticket)
    {
        /*
         * TODO:
         * get the tickettype of this ticket
         * check to see if there is a waitlist
         * check to see if there is space
         * if there is, get the first person on the list
         * register that person
         */

        $event_id = $previous_ticket->getEventId();
        $event = $this->eventLoader->getEventById($event_id);
        $ticket_type = $event->getTicketTypeById($previous_ticket->getTicketTypeId());

        if ($event->hasSpaceAvailable() && $ticket_type->hasSpaceAvailable()) {
            $waitlist = $ticket_type->getWaitlistedAttendees();
            if ($waitlist) {
                $first_user_in_waitlist = $waitlist[0]['user'];
                $this->register($first_user_in_waitlist, $event, $ticket_type, null);
            }
        }
    }

}