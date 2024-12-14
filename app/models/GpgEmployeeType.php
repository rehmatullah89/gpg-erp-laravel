<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class GpgEmployeeType extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;


	protected $primaryKey = 'type_id';

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'gpg_employee_type';
	

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array();

	/**
	*
	*/
	public function modules()
    {
    	//return $this->hasMany('table_name', 'foreign_key', 'local_key');
    	//return $this->belongsToMany('Gpg_module', 'gpg_mod_perm', 'GPG_ad_acc_id', 'GPG_module_id');
    }


}
