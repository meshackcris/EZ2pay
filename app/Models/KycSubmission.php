<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\LegacyUser;

class KycSubmission extends Model
{
    protected $table = 'kyc_submissions';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'identification_type',
        'other_id_type',
        'document_front_path',
        'document_back_path',
        'video_path',
        'status',
        'submitted_at',
        'from_admin',
        'poa_path',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(LegacyUser::class, 'user_id', 'Id');
    }
    
    public function getBrandAttribute()
    {
        if($this->relationLoaded('user')) {
            return $this->user?->brand;
        }
        return $this->user()->with('brand')->first()?->brand;
    }
}
