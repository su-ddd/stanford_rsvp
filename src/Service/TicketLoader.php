<?php


namespace Drupal\stanford_rsvp\Service;


use Drupal;
use Drupal\stanford_rsvp\Model\Event;
use Drupal\stanford_rsvp\Model\Ticket;
use Drupal\stanford_rsvp\Model\TicketType;
use Drupal\user\Entity\User;


class TicketLoader
{
    /**
     * @param Event $event
     * @param User $user
     * @return Ticket|null
     */
    public function loadTicket(Event $event, User $user): ?Ticket
    {
        $database = Drupal::database();
        $query = $database->select('stanford_rsvp_rsvps', 'srr');
        $query->addField('srr', 'tid');
        $query->addField('srr', 'status');
        $query->addField('srr', 'created');
        $query->condition('srr.uid', $user->id());
        $query->condition('srr.nid', $event->getId());
        $result = $query->execute();

        $current_rsvp = $result->fetch();

        if ($current_rsvp) {
            $ticket_type = $event->getTicketTypeById($current_rsvp->tid);
            return new Ticket($user, $current_rsvp->status, $event->getId(), $ticket_type->getId(), $current_rsvp->created);
        }
        else {
            return null;
        }
    }

    /**
     * @param string $ticketTypeId
     * @return Ticket[]
     */
    public function loadTicketsByTicketTypeId(string $ticketTypeId): array
    {

        $database = Drupal::database();
        $query = $database->select('stanford_rsvp_rsvps', 'srr');
        $query->fields('srr');
        $query->condition('srr.tid', $ticketTypeId);
        $query->orderBy('created');
        $result = $query->execute()->fetchAll();

        $tickets = array();

        foreach ($result as $ticket) {
            $user = User::load($ticket->uid);
            $tickets[] = new Ticket($user, $ticket->status, $ticket->nid, $ticketTypeId, $ticket->created);
        }

        return $tickets;
    }
}