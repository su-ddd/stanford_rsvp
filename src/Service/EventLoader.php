<?php

namespace Drupal\stanford_rsvp\Service;

use Drupal;
use Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException;
use Drupal\Component\Plugin\Exception\PluginNotFoundException;
use Drupal\Core\TypedData\Exception\MissingDataException;
use Drupal\node\Entity\Node;
use Drupal\stanford_rsvp\Model\Event as Event;

class EventLoader
{
    /**
     * @param Node $node
     * @return Event
     * @throws MissingDataException
     */
    public function getEventByNode(Node $node): Event
    {
        $ticketTypeLoader = new TicketTypeLoader();
        $ticketTypes = $ticketTypeLoader->getTicketTypesByNode($node);

        return new Event($node->id(),
            $node->getTitle(),
            $node->get('field_stanford_rsvp_location')->getString(),
            $node->get('field_stanford_rsvp_zoom_id')->getString(),
            $node->get('field_stanford_rsvp_info_url')->getString(),
            $node->get('field_stanford_rsvp_text')->get(0)->value,
            $node->get('field_stanford_rsvp_from_email')->get(0)->value,
            $node->get('field_stanford_rsvp_date')->get(0)->start_date,
            $node->get('field_stanford_rsvp_date')->get(0)->end_date,
            $node->get('field_stanford_rsvp_max')->getString(),
            $ticketTypes
        );
    }

    /**
     * @param $nid
     * @return Event
     * @throws InvalidPluginDefinitionException
     * @throws PluginNotFoundException
     * @throws MissingDataException
     */
    public function getEventById($nid): Event
    {
        $node = Drupal::entityTypeManager()->getStorage('node')->load($nid);
        return $this->getEventByNode($node);
    }

}