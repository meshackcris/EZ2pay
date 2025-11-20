<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Illuminate\Notifications\Notifiable;

class Referrer extends Model
{
    use Notifiable;
    use HasFactory;


    protected $fillable = [
        'referrer_name',
        'username',
        'email',
        'description',
        'status',
        'password',
    ];


    protected static function booted()
    {
        
    }

    // One Referrer has many users
    public function brands()
    {
        return $this->hasMany(Brand::class, 'referrer', 'username');
    }
    // App\Models\Referrer.php
public function withdrawals()
{
    return $this->hasMany(ReferrerWithdrawal::class, 'ReferrerId', 'ref_id');
}

public function getTotalWithdrawalsAttribute()
{
    return $this->withdrawals()
                ->where('Status', 7) // ✅ only approved withdrawals
                ->sum('Amount');
}

    public function getFormattedDateAttribute()
{
    return \Carbon\Carbon::parse($this->CreatedAt)->format('M d, Y • h:i A');
}
}
