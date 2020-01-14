<?php

namespace Drupal\stanford_rsvp;

/**
 * A Stanford RSVP Event
 */

class StanfordRsvp {

  /**
   * The node on which this is built.
   *
   * @var \Drupal\Core\Entity\Node
   */
  protected $node;

  /**
   * Constructs a new StanfordRsvp.
   *
   * @param \Drupal\Core\Entity\Node $node
   *   The node on which this is built.
   */
  public function __construct(\Drupal\node\Entity\Node $node) {
    $this->node = $node;
  }

  /**
   * Determines if the event still has spaces available
   *
   * @return bool
   *   TRUE if there are still spaces available or there is no maximum set,
   #   FALSE otherwise.
   */
  public function hasSpacesAvailable() {
    $max = $this->node->get('field_stanford_rsvp_max')->getString();
    if (empty($max)) {
      return TRUE;
    } else {

      $total_registrations = 0;
      $ticket_types = $this->getTicketTypes();
      foreach ($ticket_types as $ticket_type) {
        if ($ticket_type['ticket_type'] != 'cancel') {
          $ticket = new StanfordRsvpTicketType($this->node, $ticket_type['uuid']);
          $total_registrations = $total_registrations + $ticket->totalRegistrations();
        }
      }

      if ($max > $total_registrations) {
        return TRUE;
      } else {
        return FALSE;
      }
    }
  }

  public function getTicketTypes() {
    $ticket_types  = $this->node->get('field_stanford_rsvp_ticket_types')->getValue();
    return $ticket_types;
  }
}
