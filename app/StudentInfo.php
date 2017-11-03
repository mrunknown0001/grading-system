<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentInfo extends Model
{
    public function user()
    {
    	// return $this->belongsTo('App\User');
    	return $this->belongsTo('App\User', 'user_id', 'user_id')->orderBy('lastname', 'desc');

    }

    public function section1()
    {
    	return $this->belongsTo('App\Section', 'section', 'id');
    }
}
