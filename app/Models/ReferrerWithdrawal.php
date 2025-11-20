<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Referrer;
use App\Models\AptPayTransaction;

class ReferrerWithdrawal extends Model
{
    protected $table = 'ReferrerWithdrawals';
    protected $primaryKey = 'Id';
    public $incrementing = false;
    protected $keyType = 'string';

    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    protected $fillable = [
        'Id',
        'ReferrerId',
        'CryptoCurrency',
        'Currency',
        'Chain',
        'WalletAddress',
        'Amount',
        'Status',
        'TransactionHash',
        'ErrorMessage',
        'Rate',
        'FinalAmount',
    ];

    public function referrer()
    {
        return $this->belongsTo(Referrer::class, 'ReferrerId', 'ref_id');
    }

    public function transactions()
        {
            return $this->hasMany(AptPayTransaction::class, 'WithdrawalId', 'Id');
        }

    /**
     * Accessor to get the brand associated with the withdrawal.
     */
        public function getBrandAttribute()
    {
        $firstTrx = $this->transactions->first();

        if (!$firstTrx) {
            return null;
        }

        return $firstTrx->legacyUser->brand ?? null;
    }
    public function getFormattedDateAttribute()
    {
        return $this->created_at
            ? $this->created_at->format('M d, Y • h:i A')
            : null;
    }
}
