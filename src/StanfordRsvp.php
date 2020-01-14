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
   *   TRUE if there are still spaces available, FALSE otherwise.
   */
  public function hasSpacesAvailable() {
    // TODO:
    // Find the maximum number for the entire event
    // Find the total number of all registered users for all options 
    return TRUE;
  }
}
