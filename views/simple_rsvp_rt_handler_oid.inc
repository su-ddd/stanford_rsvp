<?php

class simple_rsvp_rt_handler_oid extends views_handler_field {
	public function construct() {
		parent::construct();
		$this->additional_fields['name'] = array('table' => 'simple_rsvp_rt_options', 'field' => 'name');
	}
	function query() {
    	$this->ensure_my_table();
  		$join = new views_join();
    	$join->construct('simple_rsvp_rt_options', $this->table_alias, 'oid', 'id');
    	$this->options_table = $this->query->ensure_table('simple_rsvp_rt_options', $this->relationship, $join);
    	$this->field_alias = $this->query->add_field(NULL, "COALESCE($this->options_table.name, $this->table_alias.$this->field)", $this->table_alias . '_' . $this->field);
    	$this->add_additional_fields();
  	}
	function render($values) {
    	$value = $this->get_value($values);
    	return $this->sanitize_value($value);
  	}

}
