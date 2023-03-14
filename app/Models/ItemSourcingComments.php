<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemSourcingComments extends Model
{
    protected $table = 'item_sourcing_comments';

    protected $fillable = [
       'item_header_id',	
       'user_id',
       'comments',
       'created_at',
       'updated_at',
    ];

}
