<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Illuminate\Notifications\Notifiable;

class Brand extends Model
{
    use Notifiable;
    use HasFactory;
    protected $table = 'brands';


    protected $fillable = [
        'brand_name',
        'username',
        'email',
        'description',
        'referrer',
        'status',
        'password',
        'eft_fees',
        'interac_fees',
        'interacftd_fees',
        'meft_fees',
        'mwire_fees',
        'cc_fees',
        'reft_fees',
        'rinterac_fees',
        'rinteracftd_fees',
        'rmeft_fees',
        'rmwire_fees',
        'rcc_fees',
    ];


    protected static function booted()
    {
        static::creating(function ($brand) {
            if (empty($brand->ref_token)) {
                $brand->ref_token = 'orion_' . Str::random(8);
            }
        });
    }

    // One brand has many users
    public function legacyUsers()
    {
        return $this->hasMany(LegacyUser::class, 'Brand_Id', 'id');
    }
 public function referrerRelation()
    {
        return $this->belongsTo(Referrer::class, 'referrer', 'username');
    }
    public function getFormattedDateAttribute()
{
    return \Carbon\Carbon::parse($this->CreatedAt)->format('M d, Y • h:i A');
}
}
