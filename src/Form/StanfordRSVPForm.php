<?php

/**
 * @file
 * Contains \Drupal\stanford_rsvp\Form\StanfordRSVPForm.
 */

namespace Drupal\stanford_rsvp\Form;

use Drupal;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;
use Drupal\stanford_rsvp\Model\Event;
use Drupal\stanford_rsvp\Model\Ticket;
use Drupal\stanford_rsvp\Model\TicketType;
use Drupal\stanford_rsvp\Service\Notifier;
use Drupal\stanford_rsvp\Service\TicketLoader;
use Drupal\stanford_rsvp\Service\EventLoader;
use Drupal\stanford_rsvp\Service\Registrar;

use Drupal\user\Entity\User;
use Symfony\Component\DependencyInjection\ContainerInterface;


class StanfordRSVPForm extends FormBase
{
    /**
     * @var Ticket
     */
    private $current_rsvp;

    /**
     * @var TicketType
     */
    private $currentTicketType;

    /**
     * @var Event
     */
    private $event;

    /**
     * @var Node
     */
    private $node;

    /**
     * @var EventLoader
     */
    private $eventLoader;

    /**
     * @var TicketLoader
     */
    private $ticketLoader;

    /**
     * @var Registrar
     */
    private $registrar;

    /**
     * @var Notifier
     */
    private $notifier;


    public function __construct (EventLoader $event_loader, TicketLoader $ticket_loader, Registrar $registrar, Notifier $notifier) {
        $this->eventLoader = $event_loader;
        $this->ticketLoader = $ticket_loader;
        $this->registrar = $registrar;
        $this->notifier = $notifier;
    }


    /**
     * {@inheritdoc}
     */
    public static function create(ContainerInterface $container): StanfordRSVPForm
    {
        // Instantiates this form class.
        return new static(
        // Load the service required to construct this class.
            $container->get('stanford_rsvp.event_loader'),
            $container->get('stanford_rsvp.ticket_loader'),
            $container->get('stanford_rsvp.registrar'),
            $container->get('stanford_rsvp.notifier')
        );
    }

    /**
     * {@inheritdoc}
     */

    public function getFormId(): string
    {
        return 'stanford_rsvp_form';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state, Node $node = NULL): array
    {
        $this->node = $node;
        $this->event = $this->eventLoader->getEventByNode($node);
        $user = User::load(Drupal::currentUser()->id());

        $this->current_rsvp = $this->ticketLoader->loadTicket($this->event, $user);

        $form['event_id'] = array(
            '#type' => 'hidden',
            '#default_value' => $this->event->getId());

        // Print out the logged-in user's current status (registered, waitlisted or none)
        if ($this->current_rsvp) {
            $this->currentTicketType = $this->event->getTicketTypeById($this->current_rsvp->getTicketTypeId());

            $status_text = t('Your current selection is:');

            if ($this->current_rsvp->getStatus() == Ticket::STATUS_REGISTERED) {
                $status_text = t('You are currently <strong>registered</strong> for:');
            }

            if ($this->current_rsvp->getStatus() == Ticket::STATUS_WAITLISTED) {
                $status_text = t('You are currently <strong>waitlisted</strong> for:');
            }

            $form['status']['#markup'] = '<p>' . $status_text . '<br /><em>' . $this->currentTicketType->getName() . '</em></p>';
        } else {
            $form['status']['#markup'] = '<p>' . t('You haven&rsquo;t RSVP&rsquo;ed yet.') . '</p>';
        }

        $current_option = '';
        if ($this->current_rsvp) {
            $current_option = $this->current_rsvp->getTicketTypeId();
        }

        $ticket_types = $this->event->getTicketTypes();

        $rsvp_options = array();

        foreach ($ticket_types as $ticket_type) {
            if ($ticket_type->hasSpaceAvailable() ||
                ($ticket_type->getTicketType() == TicketType::TYPE_CANCELLATION) ||
                ($ticket_type->getId() == $current_option)) {
                // Use the regular name for the option
                $rsvp_options[$ticket_type->getId()] = $ticket_type->getName();
            } else {
                if ($ticket_type->hasWaitlistAvailable()) {
                    // Add WAITLIST to the option label
                    $rsvp_options[$ticket_type->getId()] = $ticket_type->getName() . t(' - WAITLIST');
                } else {
                    // Add FULL to the option label
                    $rsvp_options[$ticket_type->getId()] = $ticket_type->getName() . t(' - FULL');
                }
            }
        }


        $form['rsvp_options'] = array(
            '#required' => true,
            '#type' => 'select',
            '#multiple' => false,
            '#options' => $rsvp_options,
            '#default_value' => $current_option,
            '#empty_option' => ' Please select',
        );

        /*
         *
         * RSVP, Change RSVP and Cancel actions
         *
         */

        $form['actions']['#type'] = 'actions';

        if ($this->current_rsvp) {

            // Only show "Change RSVP" if there is an existing RSVP

            if (!empty($ticket_types)) {
                if (count($ticket_types) > 1) {
                    $form['actions']['submit'] = array(
                        '#attributes' => array('class' => array('btn')),
                        '#type' => 'submit',
                        '#button_type' => 'primary',
                    );
                    $form['actions']['submit']['#value'] = $this->t('Change RSVP');
                }
            }

            // Only show a "Cancel" button if this isn't already a cancellation
            // type of ticket type (e.g. I won't be able to attend)

            if (isset($this->currentTicketType) && ($this->currentTicketType->getTicketType() != TicketType::TYPE_CANCELLATION)) {
                $form['actions']['cancel'] = array(
                    '#attributes' => array('class' => array('btn', 'btn-danger')),
                    '#type' => 'submit',
                    '#value' => t('Cancel'),
                    '#submit' => array([$this, 'cancel']),
                );
            }

        } else {

            // Show "RSVP" as the action if there isn't an RSVP yet

            $form['actions']['submit'] = array(
                '#attributes' => array('class' => array('btn')),
                '#type' => 'submit',
                '#button_type' => 'primary',
            );
            $form['actions']['submit']['#value'] = $this->t('RSVP');
        }

        return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function validateForm(array &$form, FormStateInterface $form_state)
    {
        // TODO: validation
        // E.g. not a valid option, full option

        //   if (strlen($form_state->getValue('phone_number')) < 3) {
        //     $form_state->setErrorByName('phone_number', $this->t('The phone number is too short. Please enter a full phone number.'));
        //   }
/*
        $new_option_id = $form_state->getValue('rsvp_options');

        // If nothing was selected, do nothing.
        if (empty($new_option_id)) {
            $form_state->setErrorByName('rsvp_options', $this->t('No option selected. No change.'));
        }
*/
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $user = User::load(Drupal::currentUser()->id());

        $this->current_rsvp = $this->ticketLoader->loadTicket($this->event, $user);

        $new_option_id = $form_state->getValue('rsvp_options');

        // If the option selected is the same as the current RSVP, do nothing.
        if (isset($this->current_rsvp)) {
            if ($new_option_id == $this->current_rsvp->getTicketTypeId()) {
                Drupal::messenger()->addStatus(t('The option selected is the same as the current one. No change.'));
                return;
            }
        }

        // load the ticket option
        $new_option = $this->event->getTicketTypeById($new_option_id);

        // if the option chosen doesn't exist, do nothing
        if (!($new_option)) {
            Drupal::messenger()->addError(t('The option selected cannot be found.'));
            return;
        }

        // if the option chosen is a cancel option
        if ($new_option->getTicketType() == TicketType::TYPE_CANCELLATION) {
            $this->registrar->cancel($user, $new_option);
            return;
        }

        // Check to see if there are any spaces available for the option chosen.

        if ($new_option->hasSpaceAvailable()) {
            $ticket = $this->registrar->register($user, $new_option);
            $this->notifier->notify($user, $this->event, $ticket);

            // TODO: update registration
        } elseif ($new_option->hasWaitlistAvailable()) {
            $ticket = $this->registrar->waitlist($user, $new_option);
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

    public function cancel(array &$form, FormStateInterface &$form_state)
    {
        $user = User::load(Drupal::currentUser()->id());
        $this->registrar->delete($user, $this->currentTicketType);
    }
}
