<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * Class User
 * @package App
 * @author Stanley-Kemuel Lloyd Salvation
 * @OA\Schema(
 *     title="User model",
 *     description="User model",
 * )
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
     * Sets the user's name
     * @param $value
     *
     * @OA\Property(
     *     format="msisdn",
     *     description="Phone",
     *     title="Phone",
     * )
     *
     * @var string
     */
    public function setNameAttribute($value) {
        $this->attributes['name'] = $value;
    }

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
