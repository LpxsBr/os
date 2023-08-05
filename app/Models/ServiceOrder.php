<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class ServiceOrder extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
            'user_id',
            'equipament',
            'description',
            'area',
            'profile',
            'is_active',
            'is_preventive',
    ];

    protected $hidden = [];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [];
}
