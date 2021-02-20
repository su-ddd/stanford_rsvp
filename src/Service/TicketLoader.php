<?php


namespace Drupal\stanford_rsvp\Service;


use Drupal;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\stanford_rsvp\Model\Event;
use Drupal\stanford_rsvp\Model\Ticket;
use Drupal\stanford_rsvp\Model\TicketType;

class TicketLoader
{
    /**
     * @param Event $event
     * @param AccountProxyInterface $user
     * @return Ticket|null
     */
    public function loadTicket(Event $event, AccountProxyInterface $user): ?Ticket
    {
        $database = Drupal::database();
        $query = $database->select('stanford_rsvp_rsvps', 'srr');
        $query->addField('srr', 'tid');
        $query->addField('srr', 'status');
        $query->condition('srr.uid', $user->id());
        $query->condition('srr.nid', $event->getId());
        $result = $query->execute();

        $current_rsvp = $result->fetch();

        if ($current_rsvp) {
            $ticket_type = $event->getTicketTypeById($current_rsvp->tid);
            return new Ticket($user, $current_rsvp->status, $event, $ticket_type);
        }
        else {
            return null;
        }
    }

    /**
     * @param TicketType $ticketType
     * @param int $status
     * @return int
     */
    public function countTicketsByTicketTypeAndStatus(TicketType $ticketType, int $status) {
        $database = \Drupal::database();
        $query = $database->select('stanford_rsvp_rsvps', 'srr');
        $query->condition('srr.tid', $ticketType->getId(), '=');
        $query->condition('srr.status', $status, '=');
        return $query->countQuery()->execute()->fetchField();
    }
}