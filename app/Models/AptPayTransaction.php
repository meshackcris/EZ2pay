<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\LegacyUser;

class AptPayTransaction extends Model
{
    protected $table = 'AptPayTransactions';

    // If your table uses a custom primary key
    protected $primaryKey = 'Id'; // optional if the default is "id"
    public $incrementing = false;
    protected $keyType = 'string';

    const CREATED_AT = 'CreatedAt';
    const UPDATED_AT = 'UpdatedAt';
    
    protected $fillable = [
        'Id',
        'UserId',
        'CryptoCurrency',
        'Chain',
        'WalletAddress',
        'Amount',
        'Status',
        'TransactionHash',
        'ErrorMessage',
        'Rate',
        'Fees',
        'FinalAmount',
        'ReferenceNumber',
        'DepositID',
        'WithdrawalId',
        'IsCommissionPaid',
    ];

    // If necessary, define fillable fields
    protected $guarded = [];

    public function legacyUser()
    {
        return $this->belongsTo(LegacyUser::class, 'UserId', 'Id');
    }

    public function getBrandAttribute()
    {
        if($this->relationLoaded('legacyUser')) {
            return $this->legacyUser?->brand;
        }
        return $this->legacyUser()->with('brand')->first()?->brand;
    }
    public function deposit()
    {
        return $this->belongsTo(AptPayTransaction::class, 'DepositId', 'ReferenceNumber');
    }

    public function getFormattedDateAttribute()
{
    return \Carbon\Carbon::parse($this->CreatedAt)->format('M d, Y • h:i A');
}

}
