<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function employeeProfiles()
    {
        return $this->hasMany(Profile::class, 'company_id', 'id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'company_id', 'id');
    }

    public function categories()
    {
        return $this->hasMany(Category::class, 'company_id', 'id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'customer_id', 'id');
    }

    public function scopeIsAdmin($query)
    {
        return $query->role('Admin');
    }

    public function scopeIsCompany($query)
    {
        return $query->role('Company');
    }

    public function scopeIsEmployee($query)
    {
        return $query->role('Employee');
    }

    public function scopeIsCustomer($query)
    {
        return $query->role('Customer');
    }
}
