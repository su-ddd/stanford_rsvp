<?php


namespace Drupal\stanford_rsvp\Model;

use Drupal\Core\Session\AccountProxyInterface;

class Ticket
{
    const STATUS_REGISTERED = 1;
    const STATUS_WAITLISTED = 2;
    const STATUS_CANCELLED  = 3;

    /**
     * @var AccountProxyInterface;
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
     * Ticket constructor.
     * @param AccountProxyInterface $user
     * @param int $status
     * @param Event $event
     * @param TicketType $ticket_type
     */
    public function __construct(AccountProxyInterface $user, int $status, Event $event, TicketType $ticket_type)
    {
        $this->setUser($user);
        $this->setStatus($status);
        $this->setEvent($event);
        $this->setTicketType($ticket_type);
    }

    /**
     * @return AccountProxyInterface
     */
    public function getUser(): AccountProxyInterface
    {
        return $this->user;
    }

    /**
     * @param AccountProxyInterface $user
     */
    public function setUser(AccountProxyInterface $user): void
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

}