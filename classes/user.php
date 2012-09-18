<?php

class User extends Base{
	protected $name;
	protected $email;
	
	public function __construct() {
		parent::__construct('user');
	}
}