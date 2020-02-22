<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CourseRegistration extends Model
{
    // fillable properties
    protected $fillable = [
        'user_id',
        'course_id',
    ];

    // guarded properties from mass fillable
    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    protected $hidden = [
        'updated_at'
    ];
}
