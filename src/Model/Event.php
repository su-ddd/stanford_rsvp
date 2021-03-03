<?php


namespace Drupal\stanford_rsvp\Model;

use Drupal\Core\Datetime\DrupalDateTime;

class Event
{
    /**
     * @var int
     */
    private $id;

    /**
     * The event's name, e.g. January 2022 Town Hall
     * @var string
     */
    private $name;

    /**
     * Physical location of event, e.g. Main Quad, Room 123
     * @var string
     */
    private $location;

    /**
     * Remote location of event, e.g. Zoom URL with password
     * @var string
     */
    private $remoteLocation;

    /**
     * URL to display more information about the event
     * @var string
     */
    private $infoUrl;

    /**
     * Text to send with the calendar invitation
     * @var string
     */
    private $invitationText;

    /**
     * Email to use to send invitation text
     * @var string
     */
    private $invitationFromEmail;

    /**
     * The day and time the event starts
     * @var DrupalDateTime
     */
    private $startDate;

    /**
     * The day and time the event ends
     * @var DrupalDateTime
     */
    private $endDate;

    /**
     * The total number of attendees for the entire event
     * Note: individual ticket types might have their own maximum number of attendees
     * @var mixed
     */
    private $maxAttendees;

    /**
     * @var TicketType[]
     */
    private $ticketTypes;

    /**
     * Event constructor.
     * @param int $id
     * @param string $name
     * @param string $location
     * @param string $remoteLocation
     * @param string $infoUrl
     * @param string $invitationText
     * @param string $invitationFromEmail
     * @param DrupalDateTime $startDate
     * @param DrupalDateTime $endDate
     * @param string $maxAttendees
     * @param TicketType[] $ticketTypes
     */
    public function __construct(int $id, string $name, string $location, string $remoteLocation, string $infoUrl, string $invitationText, string $invitationFromEmail, DrupalDateTime $startDate, DrupalDateTime $endDate, string $maxAttendees, array $ticketTypes)
    {
        $this->setId($id);
        $this->setName($name);
        $this->setLocation($location);
        $this->setRemoteLocation($remoteLocation);
        $this->setInfoUrl($infoUrl);
        $this->setInvitationText($invitationText);
        $this->setInvitationFromEmail($invitationFromEmail);
        $this->setStartDate($startDate);
        $this->setEndDate($endDate);
        $this->setMaxAttendees($maxAttendees);
        $this->setTicketTypes($ticketTypes);
    }


    /**
     * @param $id
     * @return TicketType|null
     */
    public function getTicketTypeById($id)
    {
        foreach ($this->getTicketTypes() as $ticket_type) {
            if ($ticket_type->getId() == $id) {
                return $ticket_type;
            }
        }
        return null;
    }

    /**
     * An event has spaces available if at least one option has spaces available
     * and the total number of registered people is below the maximum for the
     * event itself.
     *
     * @return bool
     */
    public function hasSpaceAvailable(): bool
    {
        $total_attendees = $this->countAttendees();
        if ($total_attendees >= $this->getMaxAttendees()) {
            return false;
        }

        foreach ($this->getTicketTypes() as $ticketType) {
            if ($ticketType->hasSpaceAvailable()) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return int
     */
    public function countAttendees(): int
    {
        $total_attendees = 0;

        foreach ($this->getTicketTypes() as $ticketType) {
            if ($ticketType->getTicketType() != TicketType::TYPE_CANCELLATION) {
                $total_attendees = $total_attendees + $ticketType->countTotalRegistered();
            }
        }
        return $total_attendees;
    }

    /**
     *
     * Getters and Setters
     *
     */

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
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
     * @return string
     */
    public function getLocation(): string
    {
        return $this->location;
    }

    /**
     * @param string $location
     */
    public function setLocation(string $location): void
    {
        $this->location = $location;
    }

    /**
     * @return string
     */
    public function getRemoteLocation(): string
    {
        return $this->remoteLocation;
    }

    /**
     * @param string $remoteLocation
     */
    public function setRemoteLocation(string $remoteLocation): void
    {
        $this->remoteLocation = $remoteLocation;
    }

    /**
     * @return string
     */
    public function getInfoUrl(): string
    {
        return $this->infoUrl;
    }

    /**
     * @param string $infoUrl
     */
    public function setInfoUrl(string $infoUrl): void
    {
        $this->infoUrl = $infoUrl;
    }

    /**
     * @return string
     */
    public function getInvitationText(): string
    {
        return $this->invitationText;
    }

    /**
     * @param string $invitationText
     */
    public function setInvitationText(string $invitationText): void
    {
        $this->invitationText = $invitationText;
    }

    /**
     * @return mixed
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @param mixed $startDate
     */
    public function setStartDate($startDate): void
    {
        $this->startDate = $startDate;
    }

    /**
     * @return mixed
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @param mixed $endDate
     */
    public function setEndDate($endDate): void
    {
        $this->endDate = $endDate;
    }

    /**
     * @return mixed
     */
    public function getMaxAttendees()
    {
        return $this->maxAttendees;
    }

    /**
     * @param mixed $maxAttendees
     */
    public function setMaxAttendees($maxAttendees): void
    {
        $this->maxAttendees = $maxAttendees;
    }

    /**
     * @return TicketType[]
     */
    public function getTicketTypes()
    {
        return $this->ticketTypes;
    }

    /**
     * @param mixed $ticketTypes
     */
    public function setTicketTypes($ticketTypes): void
    {
        $this->ticketTypes = $ticketTypes;
    }

    /**
     * @return string
     */
    public function getInvitationFromEmail(): string
    {
        return $this->invitationFromEmail;
    }

    /**
     * @param string $invitationFromEmail
     */
    public function setInvitationFromEmail(string $invitationFromEmail): void
    {
        $this->invitationFromEmail = $invitationFromEmail;
    }

    public function __toString()
    {
        $string = '';
        $string .= 'Event Name: ' . $this->getName() . "<br />\n";
        return $string;
    }



}