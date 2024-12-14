<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class GpgCustomer extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;
	const CREATED_AT = 'created_date';
	const UPDATED_AT = 'last_modified_date';
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'gpg_customer';
	protected $primaryKey = 'id';
	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('pwd');

}
