<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'country',
        'phone',
        'group_id',
        'role_id',
        'fixed_fees',
        'username',
        'status',
        'account_id',
        'allow_users',
        'add_users',
        'edit_users',
        'del_users',
        'allow_verifiers',
        'add_verifiers',
        'edit_verifiers',
        'del_verifiers',
        'allow_institutes',
        'add_institutes',
        'edit_institutes',
        'del_institutes',
        'allow_groups',
        'add_groups',
        'edit_groups',
        'del_groups',
        'allow_reports',
        'allow_settings',
        'allow_invoices',
        'allow_new_verification',
        'allow_verifications',
        'parent_verifier_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
