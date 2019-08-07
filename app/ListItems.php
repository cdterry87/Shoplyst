<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ListItems extends Model
{
    protected $table = 'lists_items';

    protected $fillable = [
        'list_id', 'name', 'price', 'quantity', 'reminder'
    ];

    public function list()
    {
        return $this->belongsTo('App\Lists')->withTimestamps();
    }
}
