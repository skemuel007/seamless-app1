<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Course
 * @package App
 * @author Stanley-Kemuel Lloyd Salvation
 * @OA\Schema(
 *     title="Course model",
 *     description="Course model",
 *     required={"course_code", "course_name", "unit"},
 *     @OA\Xml(
 *        name="Course"
 *     )
 * )
 */
class Course extends Model
{
    //
    protected $fillable = [
        'course_name',
        'course_code',
        'units'
    ];

    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    protected $hidden = [
        'updated_at'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users() {
        return $this->belongsToMany(
            User::class,
            'course_registrations',
            'course_id',
            'user_id'
        )->withPivot(['id', 'created_at']);
    }
}
