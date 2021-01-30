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

  protected $rsvp_found = FALSE;

  public $status; // registered or waitlisted?

    /**
     * Constructs a new StanfordRsvp.
     *
     * @param \Drupal\stanford_rsvp\StanfordRsvpEvent $event
     * @param \Drupal\Core\Session\AccountProxy $user
     */
  public function __construct(\Drupal\stanford_rsvp\StanfordRsvpEvent $event, \Drupal\Core\Session\AccountProxy $user) {
    $this->event = $event;
    $this->user = $user;

    // is there an existing Rsvp?
    $current_rsvp = $this->getRsvp();

    if ($current_rsvp) {
      $this->ticket = $this->event->getTicket($current_rsvp->tid);
      $this->status = $current_rsvp->status;
      $this->rsvp_found = TRUE;
    }
  }

  public function hasRsvp() {
    return $this->rsvp_found;
  }

  // load the RSVP from the stanford_rsvp_rsvps table

  public function getRsvp() {
    $database = \Drupal::database();
    $query = $database->select('stanford_rsvp_rsvps', 'srr');
    $query->addField('srr', 'tid');
    $query->addField('srr', 'status');
    $query->condition('srr.uid', $this->user->id(), '=');
    $query->condition('srr.nid', $this->event->id, '=');
    $result = $query->execute();
    return $result->fetch();
  }

  // update the User's RSVP with a new ID as well as status
 
  public function setRsvp($ticket_id, $status) {
    $database = \Drupal::database();
    $database->merge('stanford_rsvp_rsvps')
	->key('uid', $this->user->id())
	->key('nid', $this->event->id)
	->fields([
		'tid' => $ticket_id,
		'status' => $status,
                'created' => REQUEST_TIME,
		])
	->execute();
  }

  // Delete a user's RSVP
    public function deleteRSVP() {
        $database = \Drupal::database();
        $query = $database->delete('stanford_rsvp_rsvps')
            ->condition('uid', $this->user->id(), '=')
            ->condition('nid', $this->event->id, '=');
        $result = $query->execute();
        dsm($result);
        return $result;
    }
}
