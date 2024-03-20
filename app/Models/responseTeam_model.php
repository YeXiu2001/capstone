<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class responseTeam_model extends Model
{
    use HasFactory;

    protected $table = 'response_teams';

    protected $fillable = [
        'id',
        'team_name',
        'status',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
    ];

    CONST STATUS_OPTIONS = [
        'available' => 'Available',
        'unavailable' => 'Inactive',
        'busy' => 'Busy',
    ];

    public function createdByUser(){
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedByUser(){
        return $this->belongsTo(User::class, 'updated_by');
    }

    // public function members(){
    //     return $this->hasMany(rtMembers_model::class, 'team_id');
    // }
    
}
