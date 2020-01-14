<?php

/**
 * @file
 * Contains \Drupal\stanford_rsvp\Form\StanfordRSVPForm.
 */

namespace Drupal\stanford_rsvp\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\stanford_rsvp\StanfordRsvp;
use Drupal\stanford_rsvp\StanfordRsvpUserRsvp;
use Drupal\stanford_rsvp\StanfordRsvpTicketType;

class StanfordRSVPForm extends FormBase {
  /**
   * {@inheritdoc}
   */

  public function getFormId() {
    return 'stanford_rsvp_form';
  }

  private $current_rsvp;
  private $event;
  private $node;

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $node = NULL) {

    $this->node  = $node;
    $this->event = new StanfordRsvp($node);

    $user     = \Drupal::currentUser();
    $this->current_rsvp = new StanfordRsvpUserRsvp($node, $user);

    $current_option = '';
    if ($this->current_rsvp->hasRsvp()) {
      $current_option = $this->current_rsvp->getTicketId();
    }

    if ($this->event->hasSpacesAvailable()) {
    }


    $tickets  = $node->get('field_stanford_rsvp_ticket_types')->getValue();

    $rsvp_options = array();

    foreach ($tickets as $rsvp_option) {
      $rsvp_options[$rsvp_option['uuid']] = $rsvp_option['name'];
    }

    if ($this->current_rsvp->hasRsvp()) {
      $form['status']['#markup'] = '<p>' . t('Your current selection is:') . '<br /><em>' . $this->current_rsvp->getTicketName() . '</em></p>';
    } else {
      $form['status']['#markup'] = '<p>' . t('You haven&rsquo;t RSVP&rsquo;ed yet.') . '</p>';
    }

    $form['rsvp_options'] = array(
      '#required' => true,
      '#type' => 'select',
      '#multiple' => false,
      '#options' => $rsvp_options,
      '#default_value' => $current_option,
      '#empty_option' => ' Please select',
  );

    $form['actions']['#type'] = 'actions';

    $form['actions']['submit'] = array(
      '#attributes' => array('class' => array('btn')),
      '#type' => 'submit',
      '#button_type' => 'primary',
    );

    if ($this->current_rsvp->hasRsvp()) {
      $form['actions']['submit']['#value'] = $this->t('Change RSVP');

      $form['actions']['cancel'] = array(
        '#attributes' => array('class' => array('btn', 'btn-danger')),
        '#type' => 'submit',
        '#value' => t('Cancel'),
        '#submit' => array('::cancel'),
      );
    } else {
      $form['actions']['submit']['#value'] = $this->t('RSVP');
    }

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
 //   if (strlen($form_state->getValue('phone_number')) < 3) {
 //     $form_state->setErrorByName('phone_number', $this->t('The phone number is too short. Please enter a full phone number.'));
 //   }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $new_option_id = $form_state->getValue('rsvp_options');

    // If nothing was selected, do nothing.
    if (empty($new_option_id)) {
      drupal_set_message(t('No option selected. No change.'));
      return;
    }

    $old_option_id = $this->current_rsvp->getTicketId();

    // if the chosen option is the same as the current one, do nothing
    if ($new_option_id == $old_option_id) {
      drupal_set_message(t('The option selected is the same as the current one. No change.'));
      return;
    }

    // load the ticket option
    $new_option = new StanfordRsvpTicketType($this->node, $new_option_id);

    // if the option chosen doesn't exist, do nothing
    if (!($new_option->ticket_found)) {
      dsm('The option selected is not found');
    }

    // if the option chosen is a cancel option
    if ($new_option->ticket_type == 'cancel') {
      dsm('you have chosen a cancel option');
      // if the user had something selected before
      // send a notification that they cancelled
      // cancel registration
    }

    dsm($new_option->totalRegistrations());

    // Check to see if there are any spaces available for the option chosen.

    if ($new_option->hasSpaceAvailable()) {
      dsm('there is room');
      $this->current_rsvp->setRsvp($new_option_id);
      // TODO: update registration
    } else {
      dsm('there is no room');
      // TODO: return error message
    }
      // update the rsvp
      // call the webhook
      // notify the user that they are registered
    // else if there is a waitlist available
      // update the rsvp
      // notify the user that they are on the waitlist
  }

  public function cancel() {
    dsm('you clicked cancel');
  }
}
