
<?php
class TimeSheet extends Eloquent {

    protected $table = 'gpg_timesheet';
    
    public function many_method() {
        //return $this->belongsToMany('ClassName'); 
        //one to many
       // return $this->hasMany('ClassName', 'foreignKey');   

    	//one to one
    	// return $this->hasOne('ClassName');
    }
}
?>