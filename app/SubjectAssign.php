<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubjectAssign extends Model
{
    public function subject()
    {
    	return $this->belongsTo('App\Subject', 'subject_id', 'id');
    }

    public function teacher()
    {
    	return $this->belongsTo('App\User', 'teacher_id', 'id');
    }

    public function section()
    {
    	return $this->belongsTo('App\Section', 'section_id', 'id');
    }
}
