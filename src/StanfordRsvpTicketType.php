<?php

namespace Drupal\stanford_rsvp;

/**
 * A ticket type associated with a particular Stanford RSVP Event
 */

class StanfordRsvpTicketType {

  /**
   * The node on which this is built.
   *
   * @var \Drupal\Core\Entity\Node
   */
  protected $node;

  public $ticket_id;
  public $ticket_name;
  public $max_attendees;
  public $max_waitlist;
  public $ticket_type;
  public $ticket_found = FALSE;

  /**
   * Constructs a new StanfordRsvpTicketType.
   *
   * @param \Drupal\Core\Entity\Node $node
   *   The node on which this is built.
   *
   * @param string $tid
   *   The uuid for this ticket type
   */
  public function __construct(\Drupal\node\Entity\Node $node, $tid) {
    $this->node = $node;
    $this->ticket_id = $tid;

    if (!empty($this->ticket_id)) {
      $this->loadRsvpDetails();
    }
  }

  private function loadRsvpDetails() {
    // load all the ticket types for this particular node
    $ticket_types  = $this->node->get('field_stanford_rsvp_ticket_types')->getValue();

    // find the position of the ticket type with the current ticket ID (a uuid)
    $key = array_search($this->ticket_id, array_column($ticket_types, 'uuid'));

    // if we've found a position, fill in the details 
    if ($key !== FALSE) {
      $current_ticket_type = $ticket_types[$key];
      $this->ticket_name = $current_ticket_type['name'];
      $this->max_attendees = $current_ticket_type['max_attendees'];
      $this->max_waitlist = $current_ticket_type['max_waitlist'];
      $this->ticket_type = $current_ticket_type['ticket_type'];
      $this->ticket_found = TRUE;
    }
  }

  // how many people are registered for this option?
  // how many people are waitlisted for this option?
  // are there spaces available?
  // how many spaces are left?

  public function totalRegistrations() {
    $database = \Drupal::database();
    $query = $database->select('stanford_rsvp_rsvps', 'srr');
    $query->condition('srr.tid', $this->ticket_id, '=');
    $query->condition('srr.status', 1, '=');
    $num_rows = $query->countQuery()->execute()->fetchField();
    return $num_rows;
  }

  public function hasSpaceAvailable() {
    // if there is no max, or there are fewer registrations than the max
    // there is still space. If not, there isn't.
    if ((!isset($this->max_attendees)) ||
        (empty($this->max_attendees)) ||
        ($this->totalRegistrations < $this->max_attendees)) {
      return TRUE;
    } else {
      return FALSE;
    }
  }
}
