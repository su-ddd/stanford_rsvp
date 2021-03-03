<?php


namespace Drupal\stanford_rsvp\Service;

use Drupal\stanford_rsvp\Model\TicketType;

class TicketTypeLoader
{
    /**
     * @param $node
     * @return TicketType[]
     */
    public function getTicketTypesByNode($node): array
    {
        $tickets = array();

        foreach ($node->get('field_stanford_rsvp_ticket_types')->getValue() as $ticket) {
            $tickets[] = new TicketType($ticket['uuid'],
                $ticket['name'],
                (int) $ticket['max_attendees'],
                (int) $ticket['max_waitlist'],
                $ticket['ticket_type']);
        }

        return $tickets;
    }

}