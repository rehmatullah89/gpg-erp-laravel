<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Gpg_field_service_work extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	const CREATED_AT = 'created_on';
	const UPDATED_AT = 'modified_on';


	protected $primaryKey = 'id';

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'gpg_field_service_work';
	

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array();

	/**
	*
	*/
	public function employee()
    {
    	return $this->hasMany('Gpg_employee', 'id', 'GPG_employee_id');
    	//return $this->belongsTo('Gpg_employee', 'gpg_mod_perm', 'GPG_ad_acc_id', 'GPG_module_id');
    }


}
