<?php

namespace Drupal\stanford_rsvp;

use Drupal\stanford_rsvp\StanfordRsvpEvent;

/**
 * A specific user's RSVP for a specific Stanford RSVP Event
 */

class StanfordRsvpUserRsvp {

  /**
   * The current event
   *
   * @var \Drupal\stanford_rsvp\StanfordRsvpEvent
   */
  public $event;

  /**
   * The current user
   *
   * @var \Drupal\Core\Session\AccountProxy
   */
  public $user;

  public $ticket;

  public $type; // registration or waitlist?
 
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
  public function __construct(\Drupal\stanford_rsvp\StanfordRsvpEvent $event, \Drupal\Core\Session\AccountProxy $user) {
    $this->event = $event;
    $this->user = $user;

    // is there an existing Rsvp?
    $current_rsvp = $this->getRsvp();
    if ($current_rsvp) {
      $this->ticket = $this->event->getTicket($current_rsvp->tid);
      $this->status    = $current_rsvp->status;
      $this->ticket_found = TRUE;
    }
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

  // look inside the stanford_rsvp_rsvps for an entry with this user and event

  public function getRsvp() {
    $database = \Drupal::database();
    $query = $database->select('stanford_rsvp_rsvps', 'srr');
    $query->addField('srr', 'tid');
    $query->addField('srr', 'status');
    $query->condition('srr.uid', $this->user->id(), '=');
    $query->condition('srr.nid', $this->event->id, '=');
    $result = $query->execute();
    $record = $result->fetch();
    return $record;
  }

  // update the RSVP 
  public function setRsvp($ticket_id) {
    $database = \Drupal::database();
    $database->merge('stanford_rsvp_rsvps')
	->key('uid', $this->user->id())
	->key('nid', $this->event->id)
	->fields([
		'tid' => $ticket_id,
                'created' => REQUEST_TIME,
		])
	->execute();
  }
}
