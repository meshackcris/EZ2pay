<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Brand;
use App\Models\AptPayTransaction;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class LegacyUser extends Model
{
    use Notifiable;

    protected $table = 'Users'; // 👈 PascalCase, matches DB exactly
    protected $primaryKey = 'Id';
    public $incrementing = false; // important if UUID
    protected $keyType = 'string'; // UUIDs are strings
    const CREATED_AT = 'CreatedAt';
    const UPDATED_AT = 'LastUpdatedAt';

    protected $fillable = [
        'FirstName',
        'LastName',
        'Email',
        'email',
        'PhoneNumber',
        'password',
        'Balance',
        'VerificationStatus',
        'Brand_Id',
        'pad_agreement_path',
        'has_pad_agreement',
        'BankBalance',
        'BankInstitutionNumber',
        'BankTransitNumber',
        'BankAccountNumber',
        'ManualBankData',
        
    ];
    protected $hidden = [
        'password',
    ];
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->Id)) {
                $model->Id = (string) Str::uuid();
            }
        });
    }
    public function brand()
    {
        return $this->belongsTo(Brand::class, 'Brand_Id', 'id');
    }
    public function transactions()
{
    return $this->hasMany(AptPayTransaction::class, 'UserId', 'Id');
}

public function kycSubmission()
{
    return $this->hasOne(KycSubmission::class, 'user_id');
}

    public function getTotalDepositsAttribute()
{
    return $this->transactions()
        ->where('PaymentDirection', 1) // Deposits
        ->where('Status', 7)           // Approved
        ->sum('Amount');
}

public function getTotalWithdrawalsAttribute()
{
    return $this->transactions()
        ->where('PaymentDirection', 3) // Withdrawals
        ->where('Status', 7)           // Approved
        ->sum('Amount');
}

    public function getFullNameAttribute()
    {

        return $this->FirstName . ' ' . $this->LastName;
    }
}
