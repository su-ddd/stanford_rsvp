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
    private $max_attendees;

    /**
     * @var int|null
     */
    private $max_waitlist;

    /**
     * @var string
     */
    private $ticket_type;

    /**
     * TicketType constructor.
     * @param string $id
     * @param string $name
     * @param int|null $max_attendees
     * @param int|null $max_waitlist
     * @param string $ticket_type
     */

    public function __construct(string $id, string $name, ?int $max_attendees, ?int $max_waitlist, string $ticket_type)
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
            $this->setMaxAttendees($max_attendees);
            $this->setMaxWaitlist($max_waitlist);
        }

        $this->setTicketType($ticket_type);
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
        return $this->max_attendees;
    }

    /**
     * @param int|null $max_attendees
     */
    public function setMaxAttendees(?int $max_attendees): void
    {
        $this->max_attendees = $max_attendees;
    }

    /**
     * @return int|null
     */
    public function getMaxWaitlist(): ?int
    {
        return $this->max_waitlist;
    }

    /**
     * @param int|null $max_waitlist
     */
    public function setMaxWaitlist(?int $max_waitlist): void
    {
        $this->max_waitlist = $max_waitlist;
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
        $ticket_loader = new TicketLoader();
        return $ticket_loader->countTicketsByTicketTypeAndStatus($this, Ticket::STATUS_REGISTERED);
    }

    /**
     * @return int
     */
    public function countTotalWaitlisted(): int
    {
        $ticket_loader = new TicketLoader();
        return $ticket_loader->countTicketsByTicketTypeAndStatus($this, Ticket::STATUS_WAITLISTED);
    }

}