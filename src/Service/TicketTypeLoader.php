<?php


namespace Drupal\stanford_rsvp\Service;

use Drupal\node\Entity\Node;
use Drupal\stanford_rsvp\Model\TicketType;

class TicketTypeLoader
{
    /**
     * @var TicketLoader
     */
    private $ticketLoader;

    /**
     * TicketTypeLoader constructor.
     * @param TicketLoader $ticketLoader
     */
    public function __construct(TicketLoader $ticketLoader)
    {
        $this->ticketLoader = $ticketLoader;
    }

    /**
     * @param Node $node
     * @return TicketType[]
     */
    public function getTicketTypesByNode(Node $node): array
    {
        $ticketTypes = array();
        $event_id = $node->id();

        foreach ($node->get('field_stanford_rsvp_ticket_types')->getValue() as $ticketType) {

            $tickets = $this->ticketLoader->loadTicketsByTicketTypeId($ticketType['uuid']);

            $ticketTypes[] = new TicketType($ticketType['uuid'],
                $ticketType['name'],
                (int)$ticketType['max_attendees'],
                (int)$ticketType['max_waitlist'],
                $ticketType['ticket_type'],
                $event_id,
                $tickets
            );
        }

        return $ticketTypes;
    }

}