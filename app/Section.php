<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    public function grade_level()
    {
    	return $this->belongsTo('App\GradeLevel', 'level', 'id');
    }
}
