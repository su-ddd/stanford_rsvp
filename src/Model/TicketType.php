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
        $this->id = $id;
        $this->name = $name;
        $this->max_attendees = $max_attendees;
        $this->max_waitlist = $max_waitlist;
        $this->ticket_type = $ticket_type;
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

    public function hasSpaceAvailable()
    {
        // TODO: implement hasSpaceAvailable()
        return true;
    }

    public function hasWaitlistAvailable()
    {
        // TODO: implement hasWaitlistAvailable()
        return true;
    }

    public function countTotalRegistrations()
    {
        $ticket_loader = new TicketLoader();
        $tickets = $ticket_loader->loadTicketsByTicketType($this);
        return count($tickets);
    }
/*
    public function countTotalWaitlisted()
    {
        $database = \Drupal::database();
        $query = $database->select('stanford_rsvp_rsvps', 'srr');
        $query->condition('srr.tid', $this->id, '=');
        $query->condition('srr.status', WAITLISTED, '=');
        $num_rows = $query->countQuery()->execute()->fetchField();
        return $num_rows;
    }
*/

}