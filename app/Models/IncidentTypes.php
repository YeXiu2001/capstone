<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncidentTypes extends Model
{
    use HasFactory;

    protected $table = 'case_types';

    protected $fillable = [
        'id',
        'cases',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];

    protected $casts = ['foo' => 'json'];
    
    public function createdByUser(){
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedByUser(){
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function incidents()
    {
        return $this->hasMany(incident_reports::class, 'incident');
    }
}
