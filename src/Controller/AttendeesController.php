<?php

namespace Drupal\stanford_rsvp\Controller;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Access\AccessResultAllowed;
use Drupal\Core\Access\AccessResultForbidden;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\node\Entity\Node;
use Drupal\stanford_rsvp\Model\Ticket;
use Drupal\stanford_rsvp\Service\EventLoader;
use Drupal\stanford_rsvp\Service\TicketLoader;


/**
 * An example controller.
 */
class AttendeesController extends ControllerBase
{

    /**
     * Returns a render-able array for a test page.
     * @param $node
     * @return array
     * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
     * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
     * @throws \Drupal\Core\TypedData\Exception\MissingDataException
     */
    public function content($node)
    {
        $eventLoader = new EventLoader;

        $event = $eventLoader->getEventById($node);

        $ticket_types = array();

        foreach ($event->getTicketTypes() as $ticketType) {
            $name = $ticketType->getName();
            $attendees = array();

            $ticketLoader = new TicketLoader();
            $tickets = $ticketLoader->loadTicketsByTicketTypeAndStatus($ticketType, Ticket::STATUS_REGISTERED);

            foreach ($tickets as $ticket) {
                $attendees[] = array(
                    'name' => $ticket->getUser()->getDisplayName(),
                    'email' => $ticket->getUser()->getEmail(),
                    'date' => $ticket->getFormattedCreatedDate(),
                );
            }

            $ticket_types[] = array('name' => $name, 'attendees' => $attendees);
        }

        return [
            '#theme' => 'stanford_rsvp_attendees',
            '#event' => $event,
            '#ticket_types' => $ticket_types,
            ];
    }

    /**
     * @return TranslatableMarkup
     */

    public function getTitle(): TranslatableMarkup
    {
        return t('Attendees');
    }

    /**
     * @param $node
     * @return AccessResultAllowed|AccessResultForbidden
     */
    public function access($node) {

        $node = Node::load($node);
        $node_type = $node->bundle();

        if ($node_type != 'stanford_rsvp') {
            $result = AccessResult::forbidden();
        }
        else {
            $result = AccessResult::allowed();
        }

        return $result;
    }

}