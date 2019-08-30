<?php

/**
 * @file
 * stanford_rsvp_content_type.field_group.inc
 */

/**
 * Implements hook_field_group_info().
 */
function stanford_rsvp_content_type_field_group_info() {
  $field_groups = array();

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_stanford_rsvp_options|node|stanford_rsvp|form';
  $field_group->group_name = 'group_stanford_rsvp_options';
  $field_group->entity_type = 'node';
  $field_group->bundle = 'stanford_rsvp';
  $field_group->mode = 'form';
  $field_group->parent_name = '';
  $field_group->data = array(
    'label' => 'Advanced Settings',
    'weight' => '6',
    'children' => array(
      0 => 'field_stanford_rsvp_email',
    ),
    'format_type' => 'fieldset',
    'format_settings' => array(
      'label' => 'Advanced Settings',
      'instance_settings' => array(
        'required_fields' => 1,
        'id' => '',
        'classes' => 'group-stanford-rsvp-options field-group-fieldset',
        'description' => '',
      ),
      'formatter' => 'collapsed',
    ),
  );
  $field_groups['group_stanford_rsvp_options|node|stanford_rsvp|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_su_rsvp_t_options|paragraphs_item|stanford_rsvp_ticket|form';
  $field_group->group_name = 'group_su_rsvp_t_options';
  $field_group->entity_type = 'paragraphs_item';
  $field_group->bundle = 'stanford_rsvp_ticket';
  $field_group->mode = 'form';
  $field_group->parent_name = '';
  $field_group->data = array(
    'label' => 'Options',
    'weight' => '4',
    'children' => array(
      0 => 'field_su_rsvp_t_max_attendees',
      1 => 'field_su_rsvp_t_max_waitlist',
      2 => 'field_su_rsvp_t_webhook_url',
      3 => 'field_su_rsvp_t_option_type',
    ),
    'format_type' => 'fieldset',
    'format_settings' => array(
      'label' => 'Options',
      'instance_settings' => array(
        'required_fields' => 1,
        'id' => '',
        'classes' => 'group-su-rsvp-t-options field-group-fieldset',
        'description' => '',
      ),
      'formatter' => 'collapsed',
    ),
  );
  $field_groups['group_su_rsvp_t_options|paragraphs_item|stanford_rsvp_ticket|form'] = $field_group;

  // Translatables
  // Included for use with string extractors like potx.
  t('Advanced Settings');
  t('Options');

  return $field_groups;
}
