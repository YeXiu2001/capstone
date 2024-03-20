<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class rtMembers_model extends Model
{
    use HasFactory;

    protected $table = 'rt_members';

    protected $fillable = [
        'id',
        'team_id',
        'member_id',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
    ];

    protected $casts = ['foo' => 'json'];
    public function createdByUser(){
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedByUser(){
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function teamRefTeams(){
        return $this->belongsTo(responseTeam_model::class, 'team_id');
    }

    public function member() {
        return $this->belongsTo(User::class, 'member_id');
    }

}
