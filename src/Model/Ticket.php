<?php


namespace Drupal\stanford_rsvp\Model;

use DateTime;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\user\Entity\User;

class Ticket
{
    const STATUS_REGISTERED = 1;
    const STATUS_WAITLISTED = 2;
    const STATUS_CANCELLED  = 3;

    /**
     * @var User;
     */
    private $user;

    /**
     * @var int
     */
    private $status;

    /**
     * @var Event;
     */
    private $event;

    /**
     * @var TicketType;
     */
    private $ticket_type;

    /**
     * @var int
     */

    private $timestamp;

    /**
     * Ticket constructor.
     * @param User $user
     * @param int $status
     * @param Event $event
     * @param TicketType $ticket_type
     * @param int $timestamp
     */
    public function __construct(User $user, int $status, Event $event, TicketType $ticket_type, int $timestamp)
    {
        $this->setUser($user);
        $this->setStatus($status);
        $this->setEvent($event);
        $this->setTicketType($ticket_type);
        $this->setTimestamp($timestamp);
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param int $status
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    /**
     * @return Event
     */
    public function getEvent(): Event
    {
        return $this->event;
    }

    /**
     * @param Event $event
     */
    public function setEvent(Event $event): void
    {
        $this->event = $event;
    }

    /**
     * @return TicketType
     */
    public function getTicketType(): TicketType
    {
        return $this->ticket_type;
    }

    /**
     * @param TicketType $ticket_type
     */
    public function setTicketType(TicketType $ticket_type): void
    {
        $this->ticket_type = $ticket_type;
    }

    /**
     * @return int
     */
    public function getTimestamp(): int
    {
        return $this->timestamp;
    }

    /**
     * @param int $timestamp
     */
    public function setTimestamp(int $timestamp): void
    {
        $this->timestamp = $timestamp;
    }

    public function getFormattedCreatedDate()
    {
        return date('M dS, Y g:i a', $this->getTimestamp());
    }
}