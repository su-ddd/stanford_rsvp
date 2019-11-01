<?php

namespace Drupal\stanford_rsvp_ticket\Plugin\Field\FieldWidget;

use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Field\WidgetInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Field widget "stanford_rsvp_ticket_default".
 *
 * @FieldWidget(
 *   id = "stanford_rsvp_ticket_default",
 *   label = @Translation("Stanford RSVP Ticket default"),
 *   field_types = {
 *     "stanford_rsvp_ticket",
 *   }
 * )
 */
class StanfordRSVPTicketDefaultWidget extends WidgetBase implements WidgetInterface {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {

    // $item is where the current saved values are stored.
    $item =& $items[$delta];

    // $element is already populated with #title, #description, #delta,
    // #required, #field_parents, etc.
    //
    // In this example, $element is a fieldset, but it could be any element
    // type (textfield, checkbox, etc.)
    $element += array(
      '#type' => 'fieldset',
    );

    // Array keys in $element correspond roughly
    // to array keys in $item, which correspond
    // roughly to columns in the database table.
    $element['name'] = array(
      '#title' => t('Ticket Name'),
      '#description'  => t('The name of the ticket, or attendance option. e.g. <em>In person</em>, <em>On Zoom</em>, <em>I won&rsquo;t be able to attend</em>. This will be shown as an option when registering.'),
      '#type' => 'textfield',
      // Use #default_value to pre-populate the element
      // with the current saved value.
      '#default_value' => isset($item->name) ? $item->name : '',
    );

    $element['max_attendees'] = array(
      '#title' => t('Max Attendees'),
      '#description'  => t('The maximum number of people that can attend with this type of ticket. e.g. 100'),
      '#type' => 'number',
      '#min'  => 0,
      '#step' => 1,
      '#size' => 5,
      // Use #default_value to pre-populate the element
      // with the current saved value.
      '#default_value' => isset($item->max_attendees) ? $item->max_attendees : '',
    );

    $element['max_waitlist'] = array(
      '#title' => t('Max Waitlist'),
      '#description'  => t('The maximum number of people who can join the waitlist once the maximum number of attendees is reached. e.g. 100'),
      '#type' => 'number',
      '#min'  => 0,
      '#step' => 1,
      '#size' => 5,
      // Use #default_value to pre-populate the element
      // with the current saved value.
      '#default_value' => isset($item->max_waitlist) ? $item->max_waitlist : '',
    );

    $element['ticket_type'] = array(
      '#title' => t('Ticket Type'),
      '#type' => 'select',
      '#options' => array('regular' => 'In Person Option', 'remote' => 'Zoom Option', 'cancel' => 'Cancellation Option'),
      '#description'  => t('The type of option. <em>In Person</em> will send the physical location information. <em>Zoom</em> will send the Zoom ID. <em>Cancellation Option will treat this selection as cancelling attendance.</em>'),
      // Use #default_value to pre-populate the element
      // with the current saved value.
      '#default_value' => isset($item->ticket_type) ? $item->ticket_type : '',
    );

    return $element;

  }

  /**
   * Form widget process callback.
   */
/*
  public static function processToppingsFieldset($element, FormStateInterface $form_state, array $form) {

    // The last fragment of the name, i.e. meat|toppings is not required
    // for structuring of values.
    $elem_key = array_pop($element['#parents']);

    return $element;

  }
*/
}
