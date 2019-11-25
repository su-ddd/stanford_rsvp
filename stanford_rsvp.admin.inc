<?php

/**
 * @file
 * Stanford RSVP module Admin Pages
 *
 */

function stanford_rsvp_attendees_page($form, &$form_state, $node) {
  $event = stanford_rsvp_event($node->nid);
  $content = '';

  foreach ($event->dates as $date) {
    $content .= '<h2>' . $date->name . '</h2>';
    foreach ($date->options as $option_id => $option_name) {
      if (stanford_rsvp_get_ticket_type($option_id) == 'cancel') {
        $rsvps = stanford_rsvp_rsvps($option_id, STANFORD_RSVP_CANCELLED);
        $table = stanford_rsvp_attendees_table($option_name, $rsvps);
        $content .= $table;
      } else {
        $rsvps = stanford_rsvp_rsvps($option_id, STANFORD_RSVP_REGISTERED);
        $table = stanford_rsvp_attendees_table($option_name, $rsvps);
        $content .= $table;
        $rsvps = stanford_rsvp_rsvps($option_id, STANFORD_RSVP_WAITLISTED);
        $table = stanford_rsvp_attendees_table($option_name . " - WAITLIST", $rsvps);
        $content .= $table;
      }
    }
  }
  $form['stanford_rsvp_report'] = array(
    '#markup' => $content
  );
  return $form;
}

function stanford_rsvp_attendees_table($option_name, $rsvps) {
  $total_rsvps = count($rsvps);
  $content .= '<h3>' . $option_name . ' (' . $total_rsvps . ')</h3>';

  $rows = array();
  foreach ($rsvps as $rsvp) {
    $datetime = new DateTime("@$rsvp->created");
    $datetime->setTimezone(new DateTimeZone('America/Los_Angeles'));
    array_push($rows, array($rsvp->name, $rsvp->mail, $datetime->format('M dS, Y g:i a')));
  }
  $table = theme('table', (array('header' => array('Name', 'Email', 'Date'), 'rows' => $rows)));
  $content .= $table;
  return $content;
}

