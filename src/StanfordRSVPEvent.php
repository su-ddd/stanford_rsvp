<?php
class StanfordRSVPEvent
{
  protected $id;
  protected $name = '';
  protected $description = '';
  protected $dates = array();

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
