<?php

/**
 * @file
 * Administration for the ServiceNow KB Module
 *
 */

function stanford_rsvp_attendees_page($form, &$form_state, $node) {
  $event = stanford_rsvp_event($node->nid);
  $content = '';

  foreach ($event->dates as $date) {
    $content .= '<h2>' . $date->name . '</h2>';
    foreach ($date->options as $option_id => $option_name) {
      $content .= '<h3>' . $option_name . '</h3>';
      $rsvps = stanford_rsvp_rsvps($option_id, REGISTERED);

      $rows = array();
      foreach ($rsvps as $rsvp) {
        $datetime = new DateTime("@$rsvp->created");
        $datetime->setTimezone(new DateTimeZone('America/Los_Angeles'));
        array_push($rows, array($rsvp->name, $rsvp->mail, $datetime->format('M dS, Y g:i a')));

      }
      $table = theme('table', (array('header' => array('Name', 'Email', 'Date'), 'rows' => $rows)));
      $content .= $table;
    }
  }
  $form['stanford_rsvp_report'] = array(
    '#markup' => $content
  );
  return $form;
}
