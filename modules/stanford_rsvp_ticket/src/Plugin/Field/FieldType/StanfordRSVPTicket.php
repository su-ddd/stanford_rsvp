<?php

namespace Drupal\stanford_rsvp_ticket\Plugin\Field\FieldType;

use Drupal\Core\TypedData\DataDefinition;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Field\FieldItemInterface;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\Field\FieldItemBase;

/**
 * Field type "stanford_rsvp_ticket".
 *
 * @FieldType(
 *   id = "stanford_rsvp_ticket",
 *   label = @Translation("Ticket Type"),
 *   description = @Translation("Stanford RSVP Ticket Type."),
 *   category = @Translation("Ticket Types"),
 *   default_widget = "stanford_rsvp_ticket_default",
 *   default_formatter = "stanford_rsvp_ticket_default",
 * )
 */

class StanfordRSVPTicket extends FieldItemBase implements FieldItemInterface {
  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {

    $output = array();

    // Create basic column for ticket name.
    $output['columns']['name'] = array(
      'type' => 'varchar',
      'length' => 255,
    );

    $output['columns']['max_attendees'] = array(
      'type' => 'varchar',
      'length' => 255,
    );

    $output['columns']['max_waitlist'] = array(
      'type' => 'varchar',
      'length' => 255,
    );

    $output['columns']['ticket_type'] = array(
      'type' => 'varchar',
      'length' => 255,
    );

    $output['columns']['uuid'] = array(
      'type' => 'varchar',
      'length' => 128,
    );

    return $output;
  }

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {

    $properties['name'] = DataDefinition::create('string')
      ->setLabel(t('Name'))
      ->setRequired(TRUE);

    $properties['max_attendees'] = DataDefinition::create('string')
      ->setLabel(t('Max Attendees'))
      ->setRequired(FALSE);

    $properties['max_waitlist'] = DataDefinition::create('string')
      ->setLabel(t('Max Attendees'))
      ->setRequired(FALSE);

    $properties['ticket_type'] = DataDefinition::create('string')
      ->setLabel(t('Type of Option'))
      ->setRequired(TRUE);

    $properties['uuid'] = DataDefinition::create('string')
      ->setLabel(t('Unique ID'))
      ->setRequired(TRUE);

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public function isEmpty() {

    $item = $this->getValue();

    // Has the user entered a name for the Ticket?
    if (isset($item['name']) && !empty($item['name'])) {
      return FALSE;
    } else {
      return TRUE;
    }
  }

  /**
   * {@inheritdoc}
   */

  public static function defaultFieldSettings() {
    return array() + parent::defaultFieldSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function fieldSettingsForm(array $form, FormStateInterface $form_state) {
    return array();
  }
}
