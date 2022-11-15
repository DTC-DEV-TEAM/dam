<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApprovalMatrix extends Model
{
    protected $table = 'approval_matrices';

    protected $fillable = [
        'id_cms_privileges',
        'cms_users_id',
        'department_list',
        'status',
        'created_at',
        'created_by',
        'updated_by',
        'updated_at',
    ];
}
