<?php

namespace Drupal\stanford_rsvp\Controller;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Access\AccessResultAllowed;
use Drupal\Core\Access\AccessResultForbidden;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\Core\TypedData\Exception\MissingDataException;
use Drupal\node\Entity\Node;
use Drupal\stanford_rsvp\Service\EventLoader;
use Drupal\stanford_rsvp\Service\TicketLoader;
use Symfony\Component\DependencyInjection\ContainerInterface;



/**
 * An example controller.
 */
class AttendeesController extends ControllerBase
{
    /**
     * @var EventLoader
     */
    private $eventLoader;

    /**
     * @var ticketLoader
     */
    private $ticketLoader;


    public function __construct(EventLoader $eventLoader, TicketLoader $ticketLoader) {
        $this->eventLoader = $eventLoader;
        $this->ticketLoader = $ticketLoader;
    }

    public static function create(ContainerInterface $container): AttendeesController
    {
        return new static(
            $container->get('stanford_rsvp.event_loader'),
            $container->get('stanford_rsvp.ticket_loader')
        );
    }

    /**
     * Returns a render-able array for a test page.
     * @param $node
     * @return array
     * @throws MissingDataException
     */
    public function content($node): array
    {
        $event = $this->eventLoader->getEventById($node);

        $ticket_types = array();

        foreach ($event->getTicketTypes() as $ticketType) {
            $name = $ticketType->getName();

            $registered_attendees = $ticketType->getRegisteredAttendees();
            if ($registered_attendees) {
                $ticket_types[] = array('name' => $name, 'status' => 'Registered', 'total' => count($registered_attendees), 'attendees' => $registered_attendees);
            }

            $waitlisted_attendees = $ticketType->getWaitlistedAttendees();
            if ($waitlisted_attendees) {
                $ticket_types[] = array('name' => $name, 'status' => 'Waitlisted', 'total' => count($waitlisted_attendees), 'attendees' => $waitlisted_attendees);
            }

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