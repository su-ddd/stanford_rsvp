<?php

namespace Drupal\stanford_rsvp;

/**
 * A Stanford RSVP Event
 */

class StanfordRsvpEvent {

  /**
   * The node on which this is built.
   *
   * @var \Drupal\Core\Entity\Node
   */
  protected $node;
  public $max;
  public $name;
  public $location;
  public $zoom_id;
  public $info_url;
  public $text;
  public $start_date;
  public $end_date;
  public $ticket_tickets;

  // ticket types
  // published (is live?)

  /**
   * Constructs a new StanfordRsvpEvent.
   *
   * @param \Drupal\Core\Entity\Node $node
   *   The node on which this is built.
   */

  public function __construct(\Drupal\node\Entity\Node $node) {
    // TODO: is this node of type RSVP?

    $this->node     = $node;
    $this->max      = $this->node->get('field_stanford_rsvp_max')->getString();
    $this->name     = $this->node->getTitle();
    $this->location = $this->node->get('field_stanford_rsvp_location')->getString();
    $this->zoom_id  = $this->node->get('field_stanford_rsvp_zoom_id')->getString();
    $this->info_url = $this->node->get('field_stanford_rsvp_info_url')->getString();
    $this->text     = $this->node->field_stanford_rsvp_text->get(0)->value;
    $this->start_date = $this->node->field_stanford_rsvp_date->get(0)->start_date;
    $this->end_date   = $this->node->field_stanford_rsvp_date->get(0)->end_date;

    // load the tickets from the DB
    $tickets  = $this->node->get('field_stanford_rsvp_ticket_types')->getValue();
    foreach ($tickets as $ticket) {
        $this->ticket_types[] = new StanfordRsvpTicketType($ticket);
    }
  }

  public function getTicketType($ticket_id) {
    foreach ($this->ticket_types as $ticket_type) {
      if ($ticket_type->ticket_id == $ticket_id) {
        return $ticket_type;
      }
    }
  }

  /**
   * Determines if the event still has spaces available
   *
   * @return bool
   *   TRUE if there are still spaces available or there is no maximum set,
   #   FALSE otherwise.
   */
  public function hasSpacesAvailable() {
    $max = $this->max;
    if (empty($max)) {
      return TRUE;
    } else {
      return FALSE;
    }
/*
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
*/
  }

  public function debug() {
    return;
    $message = '';
    $message .= 'max: ' . $this->max . "\n";
    $message .= 'name: ' . $this->name . "\n";
    $message .= 'location: ' . $this->location . "\n";
    $message .= 'zoom: ' . $this->zoom_id . "\n";
    $message .= 'info_url: ' . $this->info_url . "\n";
    $message .= 'text: ' . $this->text . "\n";
    $message .= 'start_date: ' . print_r($this->start_date, true) . "\n";
    $message .= 'end_date: ' . print_r($this->end_date, true) . "\n";
    $message .= 'tickets: ' . print_r($this->ticket_types, true) . "\n";
    return $message;
  }
}