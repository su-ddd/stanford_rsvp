<?php

/**
 * @file
 * stanford_rsvp_content_type.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function stanford_rsvp_content_type_ctools_plugin_api($module = NULL, $api = NULL) {
  if ($module == "field_group" && $api == "field_group") {
    return array("version" => "1");
  }
  if ($module == "strongarm" && $api == "strongarm") {
    return array("version" => "1");
  }
}

/**
 * Implements hook_node_info().
 */
function stanford_rsvp_content_type_node_info() {
  $items = array(
    'stanford_rsvp' => array(
      'name' => t('Stanford RSVP'),
      'base' => 'node_content',
      'description' => '',
      'has_title' => '1',
      'title_label' => t('RSVP Name'),
      'help' => '',
    ),
  );
  drupal_alter('node_info', $items);
  return $items;
}

/**
 * Implements hook_paragraphs_info().
 */
function stanford_rsvp_content_type_paragraphs_info() {
  $items = array(
    'stanford_rsvp_date' => array(
      'name' => 'Stanford RSVP Date',
      'bundle' => 'stanford_rsvp_date',
      'locked' => '1',
    ),
    'stanford_rsvp_ticket' => array(
      'name' => 'Stanford RSVP Ticket',
      'bundle' => 'stanford_rsvp_ticket',
      'locked' => '1',
    ),
  );
  return $items;
}
