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
        'admin_handler',
        'deployed_rt',
        'created_at',
        'updated_at',
    ];

    public function handlerUser(){
        return $this->belongsTo(User::class, 'admin_handler');
    }

    public function deployedUser(){
        return $this->belongsTo(User::class, 'deployed_rt');
    }

    public function modelref_incidenttype(){
        return $this->belongsTo(IncidentTypes::class, 'incident');
    }
}
