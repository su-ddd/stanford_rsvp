<?php

namespace Drupal\stanford_rsvp;

/**
 * A specific user's RSVP for a specific Stanford RSVP Event
 */

class StanfordRsvpUserRsvp {

  /**
   * The node on which this is built.
   *
   * @var \Drupal\Core\Entity\Node
   */
  protected $node;

  /**
   * The current user
   *
   * @var \Drupal\Core\Session\AccountProxy
   */
  protected $user;

  protected $ticket_id;
  protected $ticket_name;
  protected $max_attendees;
  protected $max_waitlist;
  protected $ticket_type;

  protected $ticket_found = FALSE;

  /**
   * Constructs a new StanfordRsvp.
   *
   * @param \Drupal\Core\Entity\Node $node
   *   The node on which this is built.
   *
   * @param Drupal\Core\Session\AccountProxy
   *   The Drupal user.
   */
  public function __construct(\Drupal\node\Entity\Node $node, \Drupal\Core\Session\AccountProxy $user) {
    $this->node = $node;
    $this->user = $user;

    // is there an existing Rsvp?
    $this->ticket_id = $this->getRsvp();

    if (!empty($this->ticket_id)) {
      $this->loadRsvpDetails();
    }
  }

  private function loadRsvpDetails() {
    $ticket_types  = $this->node->get('field_stanford_rsvp_ticket_types')->getValue();
    $key = array_search($this->ticket_id, array_column($ticket_types, 'uuid'));
    if ($key !== FALSE) {
      $current_ticket_type = $ticket_types[$key];
      $this->ticket_name = $current_ticket_type['name'];
      $this->max_attendees = $current_ticket_type['max_attendees'];
      $this->max_waitlist = $current_ticket_type['max_waitlist'];
      $this->ticket_type = $current_ticket_type['ticket_type'];
      $this->ticket_found = TRUE;
    }
  }

  public function getTicketId() {
    return $this->ticket_id;
  }

  public function getTicketName() {
    return $this->ticket_name;
  }
 

  /**
   * Determines if the event still has spaces available
   *
   * @return bool
   *   TRUE if there are still spaces available, FALSE otherwise.
   */
  public function hasRsvp() {
    // look inside the stanford_rsvp_rsvps for an entry with this user and event
    if ($this->ticket_found) { 
      return TRUE;
    } else {
      return FALSE;
    }
  }

  public function getRsvp() {
    // look inside the stanford_rsvp_rsvps for an entry with this user and event
    $database = \Drupal::database();
    $query = $database->select('stanford_rsvp_rsvps', 'srr');
    $query->addField('srr', 'tid');
    $query->condition('srr.uid', $this->user->id(), '=');
    $query->condition('srr.nid', $this->node->id(), '=');
    $result = $query->execute();
    $record = $result->fetchField();
    return $record;
  }
}
