<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * Class User
 * @package App
 * @SwG\Definition(
 *     definition="User",
 *     required={"email", "name", "password"},
 *     @SwG\Property(
 *          property="name",
 *          type="string",
 *          description="User's full name",
 *          example="Igboro, Yetunde, Yemisi"
 *     ),
 *     @SwG\Property(
 *          property="email",
 *          type="string",
 *          description="Staff's email address",
 *          example="john@gmail.com, kelvin@hotmail, segun@123xe.com"
 *     ),
 *     @SwG\Property(
 *          property="password",
 *          type="string",
 *          description="Applicants security key - password",
 *          example="k23@_f42, _#2*()"
 *     ),
 */
class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the identifier that will be stored in the subject
     * claim of the JWT
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom cliams to be added to
     * the JWT.
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function courses() {
        return $this->belongsToMany(
            Course::class,
            'course_registrations',
            'user_id',
            'course_id'
        )->withPivot(['id', 'created_at']);
    }
}
