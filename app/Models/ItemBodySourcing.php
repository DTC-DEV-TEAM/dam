<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemBodySourcing extends Model{

    protected $table = 'item_sourcing_body';

    protected $fillable = [
        'header_request_id',
        'item_description',
        'category_id',
        'sub_category_id',
        'class_id',
        'sub_class_id',
        'sub_category_id',
        'brand',
        'model',
        'size',
        'actual_color',
        'quantity',
        'budget',
        'created_at',
        'material',
        'thickness',
        'lamination',
        'add_ons',
        'installation',
        'dismantling'
     ];

     public function scopeBody($query,$id){
        return $query->leftjoin('category', 'item_sourcing_body.category_id', '=', 'category.id')
        ->leftjoin('new_sub_category', 'item_sourcing_body.sub_category_id', '=', 'new_sub_category.id')
        ->leftjoin('class', 'item_sourcing_body.class_id', '=', 'class.id')
        ->leftjoin('new_sub_class', 'item_sourcing_body.sub_class_id', '=', 'new_sub_class.id')
        ->select(
          'item_sourcing_body.*',
          'item_sourcing_body.id as body_id',
          'category.*',
          'new_sub_category.*',
          'class.*',
          'new_sub_class.*',
        )
        ->where('item_sourcing_body.header_request_id', $id)
        ->get();
     }

     public function scopeBodyInfo($query,$id){
      return $query->leftjoin('category', 'item_sourcing_body.category_id', '=', 'category.id')
      ->leftjoin('new_sub_category', 'item_sourcing_body.sub_category_id', '=', 'new_sub_category.id')
      ->leftjoin('class', 'item_sourcing_body.class_id', '=', 'class.id')
      ->leftjoin('new_sub_class', 'item_sourcing_body.sub_class_id', '=', 'new_sub_class.id')
      ->where('item_sourcing_body.header_request_id',$id)->first();
  }

}