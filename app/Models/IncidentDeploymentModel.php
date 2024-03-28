<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncidentDeploymentModel extends Model
{
    use HasFactory;

    protected $table = 'incident_deployements';

    protected $fillable = [
        'id',
        'incident_id',
        'deployed_rteam',
        'deployed_by',
        'created_at',
        'updated_at',
    ];

    public function incident(){
        return $this->belongsTo(incident_reports::class, 'incident_id');
    }

    public function deployedRteam(){
        return $this->belongsTo(responseTeam_model::class, 'deployed_rteam');
    }

    public function deployedBy(){
        return $this->belongsTo(User::class, 'deployed_by');
    }
}
