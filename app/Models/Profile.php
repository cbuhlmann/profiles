<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $table = 'profiles';
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'firstName',
        'lastName',
        'image',
        'status',
    ];

    const STATUS_WAITING  = 100;
    const STATUS_ACTIVE   = 200;
    const STATUS_INACTIVE = 300;

    const STATUSES = [
        self::STATUS_WAITING  => 'en attente',
        self::STATUS_ACTIVE   => 'active',
        self::STATUS_INACTIVE => 'inactive',
    ];

    // Get the human-readable status
    public function getStatusAttribute(): string
    {
        return self::STATUSES[$this->attributes['status']];
    }
}
