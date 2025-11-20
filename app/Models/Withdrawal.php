<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    protected $table = 'Withdrawals'; // Match your SQL table name
    protected $primaryKey = 'Id';     // UUID as primary key
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
    ];

    public function user()
    {
        return $this->belongsTo(LegacyUser::class, 'UserId', 'Id');
    }
}
