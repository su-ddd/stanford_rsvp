<?php
class StanfordRSVPUserTicket
{
  protected $id;
  protected $event_id;
  protected $date_id;
  protected $option_id;
  protected $uid;
  protected $status;
  protected $created_date;
  protected $name = '';

  public function __construct($id) {
   // $this->setName($name);
    $this->get_dates();
  }

  public function getName()
  {
    return $this->name;
  }
  
  public function getId()
  {
    return $this->id;
  }

  private function get_dates()
  {
    $dates = array();
    // load_from_drupal($this->id)
    // $this->dates[] = $date;
    // return $this->id;
  }
}
