<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lists extends Model
{
    protected $table = 'lists';

    protected $fillable = [
        'user_id', 'name'
    ];

    protected $appends = ['created_date'];

    public function getCreatedDateAttribute()
    {
        return $this->created_at->format('l, F jS, Y');
    }

    public function user()
    {
        return $this->belongsTo('App\User')->withTimestamps();
    }

    public function items()
    {
        return $this->hasMany('App\ListItems', 'list_id');
    }
}
