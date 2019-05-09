<?php

class simple_rsvp_rt_filter_handler_oid extends views_handler_filter_in_operator {
	public function get_value_options() {
    $options = parent::get_value_options();
    $rsvp_options = db_query("SELECT id, name FROM {simple_rsvp_rt_options}")->fetchAllKeyed();
    $this->value_options = $rsvp_options;
    return $this->value_options;
  }

}
