<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OldSchoolYear extends Model
{
    public function year()
    {
    	return $this->belongsTo('App\SchoolYear', 'school_year_id', 'id');
    }
}
