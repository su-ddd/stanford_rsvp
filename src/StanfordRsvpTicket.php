<?php

namespace Drupal\stanford_rsvp;

/**
 * A ticket type associated with a particular Stanford RSVP Event
 */

class StanfordRsvpTicket {

  /**
   * The node on which this is built.
   *
   * @var \Drupal\Core\Entity\Node
   */
  protected $node;

  public $id;
  public $name;
  public $max_attendees;
  public $total_attendees;
  public $max_waitlist;
  public $total_waitlist;
  public $ticket_type;
  public $ticket_found = FALSE;

  /**
   * Constructs a new StanfordRsvpTicket.
   *
   * @param array $ticket
   *   The values for this ticket type
   */

  public function __construct($ticket) {
      $this->id = $ticket['uuid'];
      $this->name      = $ticket['name'];

      $this->ticket_type   = $ticket['ticket_type'];

      // Cancellation tickets have no limit, even if an admin entered one by mistake

      if ($this->ticket_type == 'cancel') {
          $this->max_attendees = '';
      } else {
          $this->max_attendees = $ticket['max_attendees'];
      }

      $this->total_attendees = $this->countTotalRegistrations();

      if ($this->ticket_type == 'cancel') {
          $this->max_waitlist = '';
      } else {
          $this->max_waitlist  = $ticket['max_waitlist'];
      }

      $this->total_waitlist = $this->countTotalWaitlisted();

      $this->ticket_found  = TRUE;
  }

  // how many people are registered for this option?
  // how many people are waitlisted for this option?
  // are there spaces available?
  // how many spaces are left?

  public function countTotalRegistrations() {
    $database = \Drupal::database();
    $query = $database->select('stanford_rsvp_rsvps', 'srr');
    $query->condition('srr.tid', $this->id, '=');
    $query->condition('srr.status', REGISTERED, '=');
    $num_rows = $query->countQuery()->execute()->fetchField();
    return $num_rows;
  }
  
  public function countTotalWaitlisted() {
    $database = \Drupal::database();
    $query = $database->select('stanford_rsvp_rsvps', 'srr');
    $query->condition('srr.tid', $this->id, '=');
    $query->condition('srr.status', WAITLISTED, '=');
    $num_rows = $query->countQuery()->execute()->fetchField();
    return $num_rows;
  }

  public function hasSpaceAvailable() {
    // if there is no max, or there are fewer registrations than the max
    // there is still space. If not, there isn't.
    if ((!isset($this->max_attendees)) ||
        ((empty($this->max_attendees)) && ($this->max_attendees != 0)) ||
        ($this->total_attendees < $this->max_attendees)) {
      return TRUE;
    } else {
      return FALSE;
    }
  }

  public function hasWaitlistAvailable() {
    // if there is no max, or there are fewer registrations than the max
    // there is still space. If not, there isn't.
    if ((!isset($this->max_waitlist)) ||
        ((empty($this->max_waitlist)) && ($this->max_waitlist != 0)) ||
        ($this->total_waitlist < $this->max_waitlist)) {
      return TRUE;
    } else {
      return FALSE;
    }
  }

  // load details based on the uuid
  // we might need this because we can't load field values from the revision table
  // without knowing the latest revision, so we need the node information, at least
  // we could load the latest revision if we have node and uuid
  // but do we have the node when we are submitting the form?
  // also, how to deal with multiple constructors?
  private function loadRsvpDetails() {
    // load all the ticket types for this particular node
    $ticket_types  = $this->node->get('field_stanford_rsvp_ticket_types')->getValue();

    // find the position of the ticket type with the current ticket ID (a uuid)
    $key = array_search($this->id, array_column($ticket_types, 'uuid'));

    // if we've found a position, fill in the details 
    if ($key !== FALSE) {
      $current_ticket_type = $ticket_types[$key];
      $this->name = $current_ticket_type['name'];
      $this->max_attendees = $current_ticket_type['max_attendees'];
      $this->max_waitlist = $current_ticket_type['max_waitlist'];
      $this->ticket_type = $current_ticket_type['ticket_type'];
      $this->ticket_found = TRUE;
    }
  }
}
