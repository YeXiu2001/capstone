<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncidentTypes extends Model
{
    use HasFactory;

    protected $table = 'incident_types';

    protected $fillable = [
        'id',
        'cases',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];

    public function created_by(){
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updated_by(){
        return $this->belongsTo(User::class, 'updated_by');
    }
}
