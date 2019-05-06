<?php


/**
 * Implementation of hook_views_handlers() to register all of the basic handlers
 * views uses.
 */
function simple_rsvp_rt_views_handlers() {
  return array(
    'info' => array(
      'path' => drupal_get_path('module', 'simple_rsvp_rt'),
    ),
    'handlers' => array(
      // The name of my handler
      'simple_rsvp_rt_handler_oid' => array(
        // The name of the handler we are extending.
        'parent' => 'views_handler_field',
      ),
    ),
  );
}

/**
 * Implements hook_views_data().
 */
function simple_rsvp_rt_views_data() {
    $data = array(
	   'simple_rsvp_rt_options' => array(
            'table' => array(
                'group' => t('RSVP - Roundtable'),
            ),
            'id' => array(),
            'name' => array(),
        ),
        'simple_rsvp_rt_rsvps' => array(
            'table' => array(
                'group' => t('RSVP - Roundtable'),
                'join' => array(
                    'users' => array(
                        'left_field' => 'uid',
                        'field' => 'uid',
                    ),
                    'simple_rsvp_rt_options' => array(
                        'left_field' => 'id',
                        'field' => 'oid',
                    ),
                ),
            ),
           'oid' => array(
               'title' => t('RSVP'),
               'help'  => t('The option selected'),
               'field' => array(
                  'handler' => 'simple_rsvp_rt_handler_oid',
                  'click sortable' => TRUE,
                  ),
               'filter' => array(
                  'handler' => 'simple_rsvp_rt_filter_handler_oid',
                  ),
             ),
            'created' => array(
              'title' => t('RSVP Date'),
              'help'  => t('Date of RSVP'),
              'field' => array(
                  'handler' => 'views_handler_field_date',
                  ),
               'filter' => array(
                  'handler' => 'views_handler_filter_date',
                  ),
               'sort' => array(
                  'handler' => 'views_handler_sort_date',
                  ),
            ),
           ),
        );
     return $data;
 }

?>
