<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Applicant extends Model
{
    protected $table = 'applicant_table';
    protected $fillable = [
        'status', 
        'erf_number',
        'first_name',
        'last_name',
        'full_name',
        'job_portal',
        'remarks',
        'update_remarks',
        'screen_date',
        'created_by',
        'created_at	',
        'updated_by',
        'updated_at	',
        'first_interview',
        'final_interview',
        'job_offer',
        'for_comparison',
        'cancelled',
        'rejected'
    ];
}
