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
            return new Ticket($user, $current_rsvp->status, $event, $ticket_type, $current_rsvp->created);
        }
        else {
            return null;
        }
    }

    /**
     * @param TicketType $ticketType
     * @param int $status
     * @return Ticket[]
     * @throws Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
     * @throws Drupal\Component\Plugin\Exception\PluginNotFoundException
     * @throws Drupal\Core\TypedData\Exception\MissingDataException
     */
    public function loadTicketsByTicketTypeAndStatus(TicketType $ticketType, int $status): array
    {
        $database = Drupal::database();
        $query = $database->select('stanford_rsvp_rsvps', 'srr');
        $query->fields('srr');
        $query->condition('srr.tid', $ticketType->getId());
        $query->condition('srr.status', $status);
        $result = $query->execute()->fetchAll();

        $tickets = array();

        $eventLoader = new EventLoader();

        foreach ($result as $ticket) {
            $user = Drupal\user\Entity\User::load($ticket->uid);
            $event = $eventLoader->getEventById($ticket->nid);

            $tickets[] = new Ticket($user, $ticket->status, $event, $ticketType, $ticket->created);
        }

        return $tickets;

    }

    /**
     * @param TicketType $ticketType
     * @param int $status
     * @return int
     */
    public function countTicketsByTicketTypeAndStatus(TicketType $ticketType, int $status): int
    {
        $database = Drupal::database();
        $query = $database->select('stanford_rsvp_rsvps', 'srr');
        $query->condition('srr.tid', $ticketType->getId());
        $query->condition('srr.status', $status);
        return $query->countQuery()->execute()->fetchField();
    }
}