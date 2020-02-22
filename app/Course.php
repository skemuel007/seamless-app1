<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Course
 * @package App
 * @SwG\Definition(
 *     definition="Course",
 *     required={"course_code", "course_name", "unit"},
 *     @SwG\Property(
 *          property="course_code",
 *          type="string",
 *          description="Unique code for a course",
 *          example="001, 101, 234"
 *     ),
 *     @SwG\Property(
 *          property="course_name",
 *          type="string",
 *          description="Name of course",
 *          example="Introduction to programming with python"
 *     ),
 *     @SwG\Property(
 *          property="unit",
 *          type="integer",
 *          description="Course Unit",
 *          example="1, 2, 4"
 *     ),
 *     @SwG\Property(
 *          property="text",
 *          type="string",
 *          description="Description of Courses",
 *          example="Mr, Mrs, Miss."
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
