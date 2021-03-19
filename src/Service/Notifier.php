<?php


namespace Drupal\stanford_rsvp\Service;


use Drupal;
use Drupal\stanford_rsvp\Model\Event;
use Drupal\stanford_rsvp\Model\Ticket;
use Drupal\user\Entity\User;

class Notifier
{
    /**
     * @param User $user
     * @param Event $event
     * @param ?Ticket $ticket
     * @param int $result
     */
    public function notify(User $user, Event $event, ?Ticket $ticket, int $result) {
        $to = 'marco.wise@stanford.edu';
        $config = \Drupal::service('config.factory');
        $langcode = $config->get('system.site')->get('langcode');

        switch ($result) {
            case Registrar::RESULT_REGISTERED:
                \Drupal::service('plugin.manager.mail')->mail('stanford_rsvp', 'registration', $to, $langcode, ['event' => $event, 'user' => $user]);
                break;
            case Registrar::RESULT_CANCELLED:
                \Drupal::service('plugin.manager.mail')->mail('stanford_rsvp', 'cancellation', $to, $langcode, ['event' => $event, 'user' => $user]);
                break;
            case Registrar::RESULT_WAITLISTED:
                \Drupal::service('plugin.manager.mail')->mail('stanford_rsvp', 'waitlist', $to, $langcode, ['event' => $event, 'user' => $user]);
                break;
            case Registrar::RESULT_REGISTERED_FROM_WAITLIST:
                \Drupal::service('plugin.manager.mail')->mail('stanford_rsvp', 'registration_from_waitlist', $to, $langcode, ['event' => $event, 'user' => $user]);
                break;
            case Registrar::RESULT_WAITLISTED_AFTER_REGISTRATION_FULL:
                \Drupal::service('plugin.manager.mail')->mail('stanford_rsvp', 'waitlisted_after_registration_full', $to, $langcode, ['event' => $event, 'user' => $user]);
                break;
            default:
                Drupal::messenger()->addStatus('To: ' . $user->getDisplayName() . ' being notified about ' . $event->getTicketTypeById($ticket->getTicketTypeId())->getName() . ' with status: ' . $ticket->getStatus());
        }
    }

}