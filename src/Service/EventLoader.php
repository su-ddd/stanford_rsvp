<?php

namespace Drupal\stanford_rsvp\Service;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\TypedData\Exception\MissingDataException;
use Drupal\node\Entity\Node;
use Drupal\node\NodeStorageInterface;
use Drupal\stanford_rsvp\Model\Event as Event;

class EventLoader
{
    /**
     * @var EntityTypeManagerInterface
     */
    protected $entityTypeManager;

    /**
     * @var NodeStorageInterface
     */
    protected $nodeStorage;

    /**
     * @var TicketTypeLoader
     */
    private $ticketTypeLoader;

    /**
     * @param EntityTypeManagerInterface $entity_type_manager
     * @param TicketTypeLoader $ticketTypeLoader
     */
    public function __construct(EntityTypeManagerInterface $entity_type_manager, TicketTypeLoader $ticketTypeLoader) {
        $this->entityTypeManager = $entity_type_manager;
        $this->nodeStorage =  $this->entityTypeManager->getStorage('node');
        $this->ticketTypeLoader = $ticketTypeLoader;
    }

    /**
     * @param Node $node
     * @return Event
     * @throws MissingDataException
     */
    public function getEventByNode(Node $node): Event
    {
        $ticketTypes = $this->ticketTypeLoader->getTicketTypesByNode($node);

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
     * @param int $nid
     * @return Event
     * @throws MissingDataException
     */
    public function getEventById(int $nid): Event
    {
        $node = $this->nodeStorage->load($nid);
        return $this->getEventByNode($node);
    }

}