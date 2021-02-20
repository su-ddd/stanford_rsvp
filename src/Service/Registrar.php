<?php


namespace Drupal\stanford_rsvp\Service;


use Drupal;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\stanford_rsvp\Model\Event;
use Drupal\stanford_rsvp\Model\Ticket;
use Drupal\stanford_rsvp\Model\TicketType;
use Exception;

class Registrar
{
    /**
     * @param AccountProxyInterface $user
     * @param Event $event
     * @param TicketType $ticket_type
     * @return Ticket
     * @throws Exception
     */
    public function register(AccountProxyInterface $user, Event $event, TicketType $ticket_type): Ticket
    {
        // TODO: send notification
        return $this->save($user, $event, $ticket_type, Ticket::STATUS_REGISTERED);
    }

    /**
     * @param AccountProxyInterface $user
     * @param Event $event
     * @param TicketType $ticket_type
     * @return Ticket
     * @throws Exception
     */
    public function waitlist(AccountProxyInterface $user, Event $event, TicketType $ticket_type): Ticket
    {
        // TODO: send notification
        return $this->save($user, $event, $ticket_type, Ticket::STATUS_WAITLISTED);
    }

    /**
     * @param AccountProxyInterface $user
     * @param Event $event
     * @param TicketType $ticket_type
     * @return Ticket
     * @throws Exception
     */
    public function cancel(AccountProxyInterface $user, Event $event, TicketType $ticket_type): Ticket
    {
        // TODO: send notification
        return $this->save($user, $event, $ticket_type, Ticket::STATUS_CANCELLED);
    }

    /**
     * @param AccountProxyInterface $user
     * @param Event $event
     * @param TicketType $ticket_type
     * @param int $status
     * @return Ticket
     * @throws Exception
     */
    private function save(AccountProxyInterface $user, Event $event, TicketType $ticket_type, int $status): Ticket
    {
        $ticket = new Ticket($user, $status, $event, $ticket_type);

        $database = Drupal::database();

        $database->merge('stanford_rsvp_rsvps')
            ->key('uid', $user->id())
            ->key('nid', $event->getId())
            ->fields([
                'tid' => $ticket_type->getId(),
                'status' => $status,
                'created' => Drupal::time()->getRequestTime(),
            ])
            ->execute();

        return $ticket;
    }

}