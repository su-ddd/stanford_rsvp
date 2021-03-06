<?php


namespace Drupal\stanford_rsvp\Model;

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
     * @var int;
     */
    private $eventId;

    /**
     * @var string;
     */
    private $ticketTypeId;

    /**
     * @var int
     */

    private $timestamp;

    /**
     * Ticket constructor.
     * @param User $user
     * @param int $status
     * @param int $eventId
     * @param string $ticketTypeId
     * @param int $timestamp
     */
    public function __construct(User $user, int $status, int $eventId, string $ticketTypeId, int $timestamp)
    {
        $this->setUser($user);
        $this->setStatus($status);
        $this->setEventId($eventId);
        $this->setTicketTypeId($ticketTypeId);
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
     * @return string
     */
    public function getTicketTypeId(): string
    {
        return $this->ticketTypeId;
    }

    /**
     * @param string $ticketTypeId
     */
    public function setTicketTypeId(string $ticketTypeId): void
    {
        $this->ticketTypeId = $ticketTypeId;
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