<?php

/**
 * @file
 * stanford_rsvp_content_type.features.field_instance.inc
 */

/**
 * Implements hook_field_default_field_instances().
 */
function stanford_rsvp_content_type_field_default_field_instances() {
  $field_instances = array();

  // Exported field_instance: 'node-stanford_rsvp-body'.
  $field_instances['node-stanford_rsvp-body'] = array(
    'bundle' => 'stanford_rsvp',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => '',
    'display' => array(
      'default' => array(
        'label' => 'hidden',
        'settings' => array(),
        'type' => 'hidden',
        'weight' => 0,
      ),
      'teaser' => array(
        'label' => 'hidden',
        'module' => 'text',
        'settings' => array(
          'trim_length' => 600,
        ),
        'type' => 'text_summary_or_trimmed',
        'weight' => 0,
      ),
    ),
    'entity_type' => 'node',
    'field_name' => 'body',
    'label' => 'RSVP Invitation Text',
    'required' => 0,
    'settings' => array(
      'better_formats' => array(
        'allowed_formats' => array(
          'contributor_text_format' => 'contributor_text_format',
          'full_html' => 0,
          'php_code' => 0,
          'plain_text' => 0,
          'shoutout' => 0,
        ),
        'allowed_formats_toggle' => 1,
        'default_order_toggle' => 0,
        'default_order_wrapper' => array(
          'formats' => array(
            'contributor_text_format' => array(
              'weight' => 0,
            ),
            'full_html' => array(
              'weight' => -10,
            ),
            'php_code' => array(
              'weight' => -7,
            ),
            'plain_text' => array(
              'weight' => -8,
            ),
            'shoutout' => array(
              'weight' => -9,
            ),
          ),
        ),
      ),
      'display_summary' => 0,
      'text_processing' => 1,
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'text',
      'settings' => array(
        'rows' => 20,
        'summary_rows' => 5,
      ),
      'type' => 'text_textarea_with_summary',
      'weight' => 4,
    ),
  );

  // Exported field_instance: 'node-stanford_rsvp-field_stanford_rsvp_dates'.
  $field_instances['node-stanford_rsvp-field_stanford_rsvp_dates'] = array(
    'bundle' => 'stanford_rsvp',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => 'Each RSVP has to have at least one date.',
    'display' => array(
      'default' => array(
        'label' => 'above',
        'settings' => array(),
        'type' => 'hidden',
        'weight' => 4,
      ),
      'teaser' => array(
        'label' => 'above',
        'settings' => array(),
        'type' => 'hidden',
        'weight' => 0,
      ),
    ),
    'entity_type' => 'node',
    'field_name' => 'field_stanford_rsvp_dates',
    'label' => 'Dates',
    'required' => 0,
    'settings' => array(
      'add_mode' => 'select',
      'allowed_bundles' => array(
        'card' => -1,
        'faq' => -1,
        'question_and_answer' => -1,
        'section_generic' => -1,
        'section_with_cards' => -1,
        'stanford_rsvp_date' => 'stanford_rsvp_date',
        'stanford_rsvp_ticket' => -1,
      ),
      'bundle_weights' => array(
        'card' => 2,
        'faq' => 3,
        'question_and_answer' => 4,
        'section_generic' => 5,
        'section_with_cards' => 6,
        'stanford_rsvp_date' => 7,
        'stanford_rsvp_ticket' => 8,
      ),
      'default_edit_mode' => 'open',
      'title' => 'Date',
      'title_multiple' => 'Dates',
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 0,
      'module' => 'paragraphs',
      'settings' => array(),
      'type' => 'paragraphs_embed',
      'weight' => 5,
    ),
  );

  // Exported field_instance: 'node-stanford_rsvp-field_stanford_rsvp_email'.
  $field_instances['node-stanford_rsvp-field_stanford_rsvp_email'] = array(
    'bundle' => 'stanford_rsvp',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => 'Email address to use for notifications (defaults to the website admin\'s email address).',
    'display' => array(
      'default' => array(
        'label' => 'above',
        'settings' => array(),
        'type' => 'hidden',
        'weight' => 5,
      ),
      'teaser' => array(
        'label' => 'above',
        'settings' => array(),
        'type' => 'hidden',
        'weight' => 0,
      ),
    ),
    'entity_type' => 'node',
    'field_name' => 'field_stanford_rsvp_email',
    'label' => 'Notification Address',
    'required' => 0,
    'settings' => array(
      'better_formats' => array(
        'allowed_formats' => array(
          'contributor_text_format' => 'contributor_text_format',
          'full_html' => 'full_html',
          'php_code' => 'php_code',
          'plain_text' => 'plain_text',
          'shoutout' => 'shoutout',
        ),
        'allowed_formats_toggle' => 0,
        'default_order_toggle' => 0,
        'default_order_wrapper' => array(
          'formats' => array(
            'contributor_text_format' => array(
              'weight' => 0,
            ),
            'full_html' => array(
              'weight' => -10,
            ),
            'php_code' => array(
              'weight' => -7,
            ),
            'plain_text' => array(
              'weight' => -8,
            ),
            'shoutout' => array(
              'weight' => -9,
            ),
          ),
        ),
      ),
      'text_processing' => 0,
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'text',
      'settings' => array(
        'size' => 60,
      ),
      'type' => 'text_textfield',
      'weight' => 8,
    ),
  );

  // Exported field_instance: 'node-stanford_rsvp-field_stanford_rsvp_info_url'.
  $field_instances['node-stanford_rsvp-field_stanford_rsvp_info_url'] = array(
    'bundle' => 'stanford_rsvp',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => 'URL to send people for more information about the event and to change their RSVP.',
    'display' => array(
      'default' => array(
        'label' => 'above',
        'settings' => array(),
        'type' => 'hidden',
        'weight' => 3,
      ),
      'teaser' => array(
        'label' => 'above',
        'settings' => array(),
        'type' => 'hidden',
        'weight' => 0,
      ),
    ),
    'entity_type' => 'node',
    'field_name' => 'field_stanford_rsvp_info_url',
    'label' => 'Information URL',
    'required' => 0,
    'settings' => array(
      'better_formats' => array(
        'allowed_formats' => array(
          'contributor_text_format' => 'contributor_text_format',
          'full_html' => 'full_html',
          'php_code' => 'php_code',
          'plain_text' => 'plain_text',
          'shoutout' => 'shoutout',
        ),
        'allowed_formats_toggle' => 0,
        'default_order_toggle' => 0,
        'default_order_wrapper' => array(
          'formats' => array(
            'contributor_text_format' => array(
              'weight' => 0,
            ),
            'full_html' => array(
              'weight' => -10,
            ),
            'php_code' => array(
              'weight' => -7,
            ),
            'plain_text' => array(
              'weight' => -8,
            ),
            'shoutout' => array(
              'weight' => -9,
            ),
          ),
        ),
      ),
      'text_processing' => 0,
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'text',
      'settings' => array(
        'size' => 60,
      ),
      'type' => 'text_textfield',
      'weight' => 3,
    ),
  );

  // Exported field_instance: 'node-stanford_rsvp-field_stanford_rsvp_location'.
  $field_instances['node-stanford_rsvp-field_stanford_rsvp_location'] = array(
    'bundle' => 'stanford_rsvp',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => 'Physical location, e.g. e.g. Huang Engineering, Room 300',
    'display' => array(
      'default' => array(
        'label' => 'above',
        'settings' => array(),
        'type' => 'hidden',
        'weight' => 1,
      ),
      'teaser' => array(
        'label' => 'above',
        'settings' => array(),
        'type' => 'hidden',
        'weight' => 0,
      ),
    ),
    'entity_type' => 'node',
    'field_name' => 'field_stanford_rsvp_location',
    'label' => 'Location',
    'required' => 0,
    'settings' => array(
      'better_formats' => array(
        'allowed_formats' => array(
          'contributor_text_format' => 'contributor_text_format',
          'full_html' => 'full_html',
          'php_code' => 'php_code',
          'plain_text' => 'plain_text',
          'shoutout' => 'shoutout',
        ),
        'allowed_formats_toggle' => 0,
        'default_order_toggle' => 0,
        'default_order_wrapper' => array(
          'formats' => array(
            'contributor_text_format' => array(
              'weight' => 0,
            ),
            'full_html' => array(
              'weight' => -10,
            ),
            'php_code' => array(
              'weight' => -7,
            ),
            'plain_text' => array(
              'weight' => -8,
            ),
            'shoutout' => array(
              'weight' => -9,
            ),
          ),
        ),
      ),
      'text_processing' => 0,
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'text',
      'settings' => array(
        'size' => 60,
      ),
      'type' => 'text_textfield',
      'weight' => 1,
    ),
  );

  // Exported field_instance: 'node-stanford_rsvp-field_stanford_rsvp_zoom_id'.
  $field_instances['node-stanford_rsvp-field_stanford_rsvp_zoom_id'] = array(
    'bundle' => 'stanford_rsvp',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => 'e.g. 982919231 - This will be sent to anyone signing up for a Zoom option.',
    'display' => array(
      'default' => array(
        'label' => 'above',
        'settings' => array(),
        'type' => 'hidden',
        'weight' => 2,
      ),
      'teaser' => array(
        'label' => 'above',
        'settings' => array(),
        'type' => 'hidden',
        'weight' => 0,
      ),
    ),
    'entity_type' => 'node',
    'field_name' => 'field_stanford_rsvp_zoom_id',
    'label' => 'Zoom ID',
    'required' => 0,
    'settings' => array(
      'better_formats' => array(
        'allowed_formats' => array(
          'contributor_text_format' => 'contributor_text_format',
          'full_html' => 'full_html',
          'php_code' => 'php_code',
          'plain_text' => 'plain_text',
          'shoutout' => 'shoutout',
        ),
        'allowed_formats_toggle' => 0,
        'default_order_toggle' => 0,
        'default_order_wrapper' => array(
          'formats' => array(
            'contributor_text_format' => array(
              'weight' => 0,
            ),
            'full_html' => array(
              'weight' => -10,
            ),
            'php_code' => array(
              'weight' => -7,
            ),
            'plain_text' => array(
              'weight' => -8,
            ),
            'shoutout' => array(
              'weight' => -9,
            ),
          ),
        ),
      ),
      'text_processing' => 0,
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'text',
      'settings' => array(
        'size' => 60,
      ),
      'type' => 'text_textfield',
      'weight' => 2,
    ),
  );

  // Exported field_instance:
  // 'paragraphs_item-stanford_rsvp_date-field_su_rsvp_d_date'.
  $field_instances['paragraphs_item-stanford_rsvp_date-field_su_rsvp_d_date'] = array(
    'bundle' => 'stanford_rsvp_date',
    'deleted' => 0,
    'description' => '',
    'display' => array(
      'default' => array(
        'label' => 'above',
        'module' => 'date',
        'settings' => array(
          'format_type' => 'long',
          'fromto' => 'both',
          'multiple_from' => '',
          'multiple_number' => '',
          'multiple_to' => '',
          'show_remaining_days' => FALSE,
        ),
        'type' => 'date_default',
        'weight' => 1,
      ),
      'paragraphs_editor_preview' => array(
        'label' => 'above',
        'settings' => array(),
        'type' => 'hidden',
        'weight' => 0,
      ),
    ),
    'entity_type' => 'paragraphs_item',
    'field_name' => 'field_su_rsvp_d_date',
    'label' => 'Date',
    'required' => 0,
    'settings' => array(
      'default_value' => 'now',
      'default_value2' => 'same',
      'default_value_code' => '',
      'default_value_code2' => '',
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'date',
      'settings' => array(
        'display_all_day' => 0,
        'increment' => 15,
        'input_format' => 'm/d/Y - H:i:s',
        'input_format_custom' => '',
        'label_position' => 'above',
        'no_fieldset' => 0,
        'text_parts' => array(),
        'year_range' => '-3:+3',
      ),
      'type' => 'date_select',
      'weight' => 2,
    ),
  );

  // Exported field_instance:
  // 'paragraphs_item-stanford_rsvp_date-field_su_rsvp_d_max_attendees'.
  $field_instances['paragraphs_item-stanford_rsvp_date-field_su_rsvp_d_max_attendees'] = array(
    'bundle' => 'stanford_rsvp_date',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => 'Specify the total number of tickets or attendees for this date.',
    'display' => array(
      'default' => array(
        'label' => 'above',
        'module' => 'number',
        'settings' => array(
          'decimal_separator' => '.',
          'prefix_suffix' => TRUE,
          'scale' => 0,
          'thousand_separator' => '',
        ),
        'type' => 'number_integer',
        'weight' => 2,
      ),
      'paragraphs_editor_preview' => array(
        'label' => 'above',
        'settings' => array(),
        'type' => 'hidden',
        'weight' => 0,
      ),
    ),
    'entity_type' => 'paragraphs_item',
    'field_name' => 'field_su_rsvp_d_max_attendees',
    'label' => 'Max Attendees',
    'required' => 0,
    'settings' => array(
      'max' => '',
      'min' => '',
      'prefix' => '',
      'suffix' => '',
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 0,
      'module' => 'number',
      'settings' => array(),
      'type' => 'number',
      'weight' => 3,
    ),
  );

  // Exported field_instance:
  // 'paragraphs_item-stanford_rsvp_date-field_su_rsvp_d_name'.
  $field_instances['paragraphs_item-stanford_rsvp_date-field_su_rsvp_d_name'] = array(
    'bundle' => 'stanford_rsvp_date',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => '',
    'display' => array(
      'default' => array(
        'label' => 'above',
        'module' => 'text',
        'settings' => array(),
        'type' => 'text_default',
        'weight' => 0,
      ),
      'paragraphs_editor_preview' => array(
        'label' => 'above',
        'settings' => array(),
        'type' => 'hidden',
        'weight' => 0,
      ),
    ),
    'entity_type' => 'paragraphs_item',
    'field_name' => 'field_su_rsvp_d_name',
    'label' => 'Name',
    'required' => 0,
    'settings' => array(
      'better_formats' => array(
        'allowed_formats' => array(
          'contributor_text_format' => 'contributor_text_format',
          'full_html' => 'full_html',
          'php_code' => 'php_code',
          'plain_text' => 'plain_text',
          'shoutout' => 'shoutout',
        ),
        'allowed_formats_toggle' => 0,
        'default_order_toggle' => 0,
        'default_order_wrapper' => array(
          'formats' => array(
            'contributor_text_format' => array(
              'weight' => 0,
            ),
            'full_html' => array(
              'weight' => -10,
            ),
            'php_code' => array(
              'weight' => -7,
            ),
            'plain_text' => array(
              'weight' => -8,
            ),
            'shoutout' => array(
              'weight' => -9,
            ),
          ),
        ),
      ),
      'text_processing' => 0,
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'text',
      'settings' => array(
        'size' => 60,
      ),
      'type' => 'text_textfield',
      'weight' => 1,
    ),
  );

  // Exported field_instance:
  // 'paragraphs_item-stanford_rsvp_date-field_su_rsvp_d_tickets'.
  $field_instances['paragraphs_item-stanford_rsvp_date-field_su_rsvp_d_tickets'] = array(
    'bundle' => 'stanford_rsvp_date',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => 'Add a ticket or attendance option such as In Zoom, or In Person, etc.',
    'display' => array(
      'default' => array(
        'label' => 'above',
        'module' => 'paragraphs',
        'settings' => array(
          'view_mode' => 'full',
        ),
        'type' => 'paragraphs_view',
        'weight' => 3,
      ),
      'paragraphs_editor_preview' => array(
        'label' => 'above',
        'settings' => array(),
        'type' => 'hidden',
        'weight' => 0,
      ),
    ),
    'entity_type' => 'paragraphs_item',
    'field_name' => 'field_su_rsvp_d_tickets',
    'label' => 'Tickets',
    'required' => 0,
    'settings' => array(
      'add_mode' => 'select',
      'allowed_bundles' => array(
        'card' => -1,
        'faq' => -1,
        'question_and_answer' => -1,
        'section_generic' => -1,
        'section_with_cards' => -1,
        'stanford_rsvp_date' => -1,
        'stanford_rsvp_ticket' => 'stanford_rsvp_ticket',
      ),
      'bundle_weights' => array(
        'card' => 2,
        'faq' => 3,
        'question_and_answer' => 4,
        'section_generic' => 5,
        'section_with_cards' => 6,
        'stanford_rsvp_date' => 7,
        'stanford_rsvp_ticket' => 8,
      ),
      'default_edit_mode' => 'open',
      'title' => 'Ticket Type',
      'title_multiple' => 'Ticket Types',
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 0,
      'module' => 'paragraphs',
      'settings' => array(),
      'type' => 'paragraphs_embed',
      'weight' => 4,
    ),
  );

  // Exported field_instance:
  // 'paragraphs_item-stanford_rsvp_ticket-field_su_rsvp_t_max_attendees'.
  $field_instances['paragraphs_item-stanford_rsvp_ticket-field_su_rsvp_t_max_attendees'] = array(
    'bundle' => 'stanford_rsvp_ticket',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => 'Specify the available number of slots for this ticket type. Leave blank for unlimited.',
    'display' => array(
      'default' => array(
        'label' => 'above',
        'module' => 'number',
        'settings' => array(
          'decimal_separator' => '.',
          'prefix_suffix' => TRUE,
          'scale' => 0,
          'thousand_separator' => '',
        ),
        'type' => 'number_integer',
        'weight' => 1,
      ),
      'paragraphs_editor_preview' => array(
        'label' => 'above',
        'settings' => array(),
        'type' => 'hidden',
        'weight' => 0,
      ),
    ),
    'entity_type' => 'paragraphs_item',
    'field_name' => 'field_su_rsvp_t_max_attendees',
    'label' => 'Max Attendees',
    'required' => 0,
    'settings' => array(
      'max' => '',
      'min' => '',
      'prefix' => '',
      'suffix' => '',
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 0,
      'module' => 'number',
      'settings' => array(),
      'type' => 'number',
      'weight' => 2,
    ),
  );

  // Exported field_instance:
  // 'paragraphs_item-stanford_rsvp_ticket-field_su_rsvp_t_max_waitlist'.
  $field_instances['paragraphs_item-stanford_rsvp_ticket-field_su_rsvp_t_max_waitlist'] = array(
    'bundle' => 'stanford_rsvp_ticket',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => 'Max Waitlist for this particular ticket (overriden by the max waitlist for the event)',
    'display' => array(
      'default' => array(
        'label' => 'above',
        'module' => 'number',
        'settings' => array(
          'decimal_separator' => '.',
          'prefix_suffix' => TRUE,
          'scale' => 0,
          'thousand_separator' => '',
        ),
        'type' => 'number_integer',
        'weight' => 2,
      ),
      'paragraphs_editor_preview' => array(
        'label' => 'above',
        'settings' => array(),
        'type' => 'hidden',
        'weight' => 0,
      ),
    ),
    'entity_type' => 'paragraphs_item',
    'field_name' => 'field_su_rsvp_t_max_waitlist',
    'label' => 'Max Waitlist',
    'required' => 0,
    'settings' => array(
      'max' => '',
      'min' => '',
      'prefix' => '',
      'suffix' => '',
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 0,
      'module' => 'number',
      'settings' => array(),
      'type' => 'number',
      'weight' => 3,
    ),
  );

  // Exported field_instance:
  // 'paragraphs_item-stanford_rsvp_ticket-field_su_rsvp_t_name'.
  $field_instances['paragraphs_item-stanford_rsvp_ticket-field_su_rsvp_t_name'] = array(
    'bundle' => 'stanford_rsvp_ticket',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => 'e.g. In person, On Zoom',
    'display' => array(
      'default' => array(
        'label' => 'above',
        'module' => 'text',
        'settings' => array(),
        'type' => 'text_default',
        'weight' => 0,
      ),
      'paragraphs_editor_preview' => array(
        'label' => 'above',
        'settings' => array(),
        'type' => 'hidden',
        'weight' => 0,
      ),
    ),
    'entity_type' => 'paragraphs_item',
    'field_name' => 'field_su_rsvp_t_name',
    'label' => 'Ticket Name',
    'required' => 1,
    'settings' => array(
      'better_formats' => array(
        'allowed_formats' => array(
          'contributor_text_format' => 'contributor_text_format',
          'full_html' => 'full_html',
          'php_code' => 'php_code',
          'plain_text' => 'plain_text',
          'shoutout' => 'shoutout',
        ),
        'allowed_formats_toggle' => 0,
        'default_order_toggle' => 0,
        'default_order_wrapper' => array(
          'formats' => array(
            'contributor_text_format' => array(
              'weight' => 0,
            ),
            'full_html' => array(
              'weight' => -10,
            ),
            'php_code' => array(
              'weight' => -7,
            ),
            'plain_text' => array(
              'weight' => -8,
            ),
            'shoutout' => array(
              'weight' => -9,
            ),
          ),
        ),
      ),
      'text_processing' => 0,
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'text',
      'settings' => array(
        'size' => 60,
      ),
      'type' => 'text_textfield',
      'weight' => 1,
    ),
  );

  // Exported field_instance:
  // 'paragraphs_item-stanford_rsvp_ticket-field_su_rsvp_t_option_type'.
  $field_instances['paragraphs_item-stanford_rsvp_ticket-field_su_rsvp_t_option_type'] = array(
    'bundle' => 'stanford_rsvp_ticket',
    'default_value' => array(
      0 => array(
        'value' => 'attend',
      ),
    ),
    'deleted' => 0,
    'description' => 'Select the type of option. "Cancellation Option" won\'t send out confirmations or an event invite, and the registration won\'t count towards the maximum of attendees or waitlist.',
    'display' => array(
      'default' => array(
        'label' => 'above',
        'module' => 'list',
        'settings' => array(),
        'type' => 'list_default',
        'weight' => 4,
      ),
      'paragraphs_editor_preview' => array(
        'label' => 'above',
        'settings' => array(),
        'type' => 'hidden',
        'weight' => 0,
      ),
    ),
    'entity_type' => 'paragraphs_item',
    'field_name' => 'field_su_rsvp_t_option_type',
    'label' => 'Type of Option',
    'required' => 0,
    'settings' => array(
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'options',
      'settings' => array(
        'apply_chosen' => '',
      ),
      'type' => 'options_select',
      'weight' => 5,
    ),
  );

  // Translatables
  // Included for use with string extractors like potx.
  t('Add a ticket or attendance option such as In Zoom, or In Person, etc.');
  t('Date');
  t('Dates');
  t('Each RSVP has to have at least one date.');
  t('Email address to use for notifications (defaults to the website admin\'s email address).');
  t('Information URL');
  t('Location');
  t('Max Attendees');
  t('Max Waitlist');
  t('Max Waitlist for this particular ticket (overriden by the max waitlist for the event)');
  t('Name');
  t('Notification Address');
  t('Physical location, e.g. e.g. Huang Engineering, Room 300');
  t('RSVP Invitation Text');
  t('Select the type of option. "Cancellation Option" won\'t send out confirmations or an event invite, and the registration won\'t count towards the maximum of attendees or waitlist.');
  t('Specify the available number of slots for this ticket type. Leave blank for unlimited.');
  t('Specify the total number of tickets or attendees for this date.');
  t('Ticket Name');
  t('Tickets');
  t('Type of Option');
  t('URL to send people for more information about the event and to change their RSVP.');
  t('Zoom ID');
  t('e.g. 982919231 - This will be sent to anyone signing up for a Zoom option.');
  t('e.g. In person, On Zoom');

  return $field_instances;
}
