<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemBodySourcing extends Model{

    protected $table = 'item_sourcing_body';

    protected $fillable = [
        'header_request_id' ,
        'item_description' 	,
        'category_id' 		,
        'sub_category_id' 	,
        'class_id' 	        ,
        'sub_class_id' 	   ,
        'sub_category_id' 	,
        'brand' 	        ,
        'model' ,
        'size' 	,
        'actual_color',
        'quantity',
        'budget' ,
        'created_at' ,
      
     ];

}