<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WrittenWorkScore extends Model
{
    public function number()
    {
    	return $this->belongsTo('App\WrittenWorkNumber', 'written_work_number', 'id');
    }

    public function student()
    {
    	return $this->belongsTo('App\StudentInfo', 'student_id', 'id');
    }
}
