<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentRequest extends Model
{
    protected $fillable = [
        'transaction_id',
        'user_id',
        'document_type',
        'purpose',
        'valid_id_type',
        'valid_id_number',
        'registered_voter',
        'length_of_residency',
        'barangay_id_number',
        'civil_status',
        'employment_status',
        'monthly_income',
        'requirement_file_path',
        'status',
        'processed_at',
        'processed_by',
        'released_at',
        'remarks',
    ];

    protected $casts = [
        'registered_voter' => 'boolean',
        'monthly_income' => 'decimal:2',
        'processed_at' => 'datetime',
        'released_at' => 'datetime',
    ];

    /**
     * Get the user that owns the document request.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the admin who processed the request.
     */
    public function processor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}
