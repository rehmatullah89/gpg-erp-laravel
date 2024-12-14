<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Gpg_contract_attachment extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	const CREATED_AT = 'created_on';
	const UPDATED_AT = 'modified_on';


	protected $primaryKey = 'id';

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'gpg_consum_contract_attachment';
	

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array();
}
