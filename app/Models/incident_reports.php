<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class incident_reports extends Model
{
    use HasFactory;

    protected $table = 'incidents';

    protected $fillable = [
        'id',
        'reporter',
        'contact',
        'address',
        'lat',
        'long',
        'incident',
        'eventdesc',
        'imagedir',
        'status',
        'created_at',
        'updated_at',
    ];

    public function modelref_incidenttype(){
        return $this->belongsTo(IncidentTypes::class, 'incident');
    }

    CONST INCIDENT_STATUS = [
        'pending' => 'Pending',
        'ongoing' => 'Ongoing',
        'resolved' => 'Resolved',
        'dismissed' => 'Dismissed'
    ];
}
