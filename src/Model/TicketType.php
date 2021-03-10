<?php


namespace Drupal\stanford_rsvp\Model;


use Drupal\stanford_rsvp\Service\TicketLoader;

class TicketType
{

    const TYPE_CANCELLATION = 'cancel';
    const TYPE_IN_PERSON    = 'in_person';
    const TYPE_REMOTE       = 'remote';

    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var int|null
     */
    private $maxAttendees;

    /**
     * @var int|null
     */
    private $maxWaitlist;

    /**
     * @var string
     */
    private $ticket_type;

    /**
     * @var Ticket[]
     */
    private $tickets;

    /**
     * @var int
     */
    private $eventId;

    /**
     * TicketType constructor.
     * @param string $id
     * @param string $name
     * @param int|null $maxAttendees
     * @param int|null $maxWaitlist
     * @param string $ticket_type
     * @param int $eventId
     * @param Ticket[] $tickets
     */

    public function __construct(string $id, string $name, ?int $maxAttendees, ?int $maxWaitlist, string $ticket_type, int $eventId, array $tickets)
    {
        $this->setId($id);
        $this->setName($name);

        // override any limits on attendees and waitlist if this is a ticket
        // of type cancellation

        if ($ticket_type == TicketType::TYPE_CANCELLATION) {
            $this->setMaxAttendees(null);
            $this->setMaxWaitlist(null);
        }
        else {
            $this->setMaxAttendees($maxAttendees);
            $this->setMaxWaitlist($maxWaitlist);
        }

        $this->setTicketType($ticket_type);

        $this->setEventId($eventId);

        $this->setTickets($tickets);
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return int|null
     */
    public function getMaxAttendees(): ?int
    {
        return $this->maxAttendees;
    }

    /**
     * @param int|null $maxAttendees
     */
    public function setMaxAttendees(?int $maxAttendees): void
    {
        $this->maxAttendees = $maxAttendees;
    }

    /**
     * @return int|null
     */
    public function getMaxWaitlist(): ?int
    {
        return $this->maxWaitlist;
    }

    /**
     * @param int|null $maxWaitlist
     */
    public function setMaxWaitlist(?int $maxWaitlist): void
    {
        $this->maxWaitlist = $maxWaitlist;
    }

    /**
     * @return string
     */
    public function getTicketType(): string
    {
        return $this->ticket_type;
    }

    /**
     * @param string $ticket_type
     */
    public function setTicketType(string $ticket_type): void
    {
        $this->ticket_type = $ticket_type;
    }

    /**
     * @return int
     */
    public function getEventId(): int
    {
        return $this->eventId;
    }

    /**
     * @param int $eventId
     */
    public function setEventId(int $eventId): void
    {
        $this->eventId = $eventId;
    }

    /**
     * @return Ticket[]
     */
    public function getTickets(): array
    {
        return $this->tickets;
    }

    /**
     * @param Ticket[] $tickets
     */
    public function setTickets(array $tickets): void
    {
        $this->tickets = $tickets;
    }

    /**
     * @return bool
     */
    public function hasSpaceAvailable(): bool
    {
        if (is_null($this->getMaxAttendees())) {
            return true;
        }

        if ($this->countTotalRegistered() < $this->getMaxAttendees()) {
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * @return bool
     */
    public function hasWaitlistAvailable(): bool
    {
        if (is_null($this->getMaxWaitlist())) {
            return true;
        }

        if ($this->countTotalWaitlisted() < $this->getMaxWaitlist()) {
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * @return int
     */
    public function countTotalRegistered(): int
    {
        $total = 0;
        foreach ($this->getTickets() as $ticket) {
            if ($ticket->getStatus() == Ticket::STATUS_REGISTERED) {
                $total = $total + 1;
            }
        }
        return $total;
    }

    /**
     * @return int
     */
    public function countTotalWaitlisted(): int
    {
        $total = 0;
        foreach ($this->getTickets() as $ticket) {
            if ($ticket->getStatus() == Ticket::STATUS_WAITLISTED) {
                $total = $total + 1;
            }
        }
        return $total;
    }

    /**
     * @return array
     */
    public function getRegisteredAttendees(): array
    {
        return $this->getAttendeesByStatus(TICKET::STATUS_REGISTERED);
    }

    /**
     * @return array
     */
    public function getWaitlistedAttendees(): array
    {
        return $this->getAttendeesByStatus(TICKET::STATUS_WAITLISTED);
    }

    /**
     * @param int $status
     * @return array
     */
    public function getAttendeesByStatus($status): array
    {
        $attendees = array();
        foreach($this->getTickets() as $ticket) {
            if ($ticket->getStatus() == $status) {
                $attendees[] = array(
                    'name' => $ticket->getUser()->getDisplayName(),
                    'email' => $ticket->getUser()->getEmail(),
                    'date' => $ticket->getFormattedCreatedDate(),
                    'user' => $ticket->getUser()
                );
            }
        }
        return $attendees;
    }

    /**
     * @return Ticket[]
     */
    public function getWaitList(): array
    {
        $waitlist = array();
        foreach ($this->getTickets() as $ticket) {
            if ($ticket->getStatus() == Ticket::STATUS_WAITLISTED) {
                $waitlist[] = $ticket;
            }
        }
        return $waitlist;
    }
}