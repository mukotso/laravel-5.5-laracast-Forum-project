<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{

    protected $guarded= [];

    public function owner(){
        return  $this->belongsTo(User::class ,'user_id');
    }

    public function favourites(){
        return $this->morphMany(Favorite::class, 'favorited');
    }

    public function favorite(){
        $attributes =['user_id' =>auth()->id()];
        if(! $this->favourites()->where($attributes)->exists()){
            $this->favourites()->create($attributes);
        }

    }
    public function isfavorited(){
        return $this->favorites()->where('user_id', auth()->id())->exists();
    }
}
