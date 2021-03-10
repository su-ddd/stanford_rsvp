<?php


namespace Drupal\stanford_rsvp\Model;


use Drupal\user\Entity\User;

class Attendee extends User
{
    /**
     * @var int
     */
    private $date;

    /**
     * @return int
     */
    public function getDate(): int {
        return $this->date;
    }

    /**
     * @param int $date
     */
    public function setDate(int $date) {
        $this->date = $date;
    }
}