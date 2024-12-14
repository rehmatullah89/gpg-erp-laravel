<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Gpg_module extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	protected $primaryKey = 'id';

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'gpg_module';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array();

	/*public static function fetch_user_modules($admin_id)
	{
		//$admin_id = Session::get('gpg_admin_id');
		return $this->find($admin_id)->my_modules;

	}*/

	public function gpg_ad_accs()
    {
    	//return $this->hasMany('Comment', 'foreign_key', 'local_key');
    	return $this->belongsToMany('Role', 'user_roles', 'user_id', 'foo_id');
    }

    public function get_parent(){
    	return ($this->parent!=0?$this->hasOne('Gpg_module','parent','id'):0);
    }


}
