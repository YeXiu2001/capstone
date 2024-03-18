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
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
    ];


    public function createdByUser(){
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedByUser(){
        return $this->belongsTo(User::class, 'updated_by');
    }
    
}
