<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    //
    protected $table = 'cms_users';
    protected $fillable = [
        'name',
        'first_name',
        'last_name' ,
        'username',
        'photo',
        'email', 
        'id_cms_privileges',
        'password',
        'approver_id',
        'store_id'
    ] ;
}
