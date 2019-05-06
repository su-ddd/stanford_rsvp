<?php

/**
 * @file
 * event_feature.features.inc
 */

/**
 * Implements hook_node_info().
 */
function event_feature_node_info() {
  $items = array(
    'event' => array(
      'name' => t('Event'),
      'base' => 'node_content',
      'description' => '',
      'has_title' => '1',
      'title_label' => t('Event Name'),
      'help' => '',
    ),
  );
  drupal_alter('node_info', $items);
  return $items;
}

/**
 * Implements hook_paragraphs_info().
 */
function event_feature_paragraphs_info() {
  $items = array(
    'date' => array(
      'name' => 'Date',
      'bundle' => 'date',
      'locked' => '1',
    ),
    'ticket' => array(
      'name' => 'Ticket',
      'bundle' => 'ticket',
      'locked' => '1',
    ),
  );
  return $items;
}
