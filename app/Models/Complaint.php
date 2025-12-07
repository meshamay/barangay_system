<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Complaint extends Model
{
    protected $fillable = [
        'transaction_id',
        'user_id',
        'incident_date',
        'incident_time',
        'incident_location',
        'defendant_name',
        'defendant_address',
        'complaint_type',
        'urgency_level',
        'complaint_statement',
        'status',
        'admin_remarks',
        'resolved_at',
        'assigned_to',
    ];

    protected $casts = [
        'incident_date' => 'date',
        'incident_time' => 'datetime:H:i',
        'resolved_at' => 'datetime',
    ];

    /**
     * Get the user who filed the complaint.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the admin assigned to handle the complaint.
     */
    public function assignedAdmin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
