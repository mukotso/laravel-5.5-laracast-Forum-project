<?php

namespace App;

use App\Traits\Favoritable;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{

    use Favoritable;
    protected $guarded= [];

    protected $with =['owner', 'favorites'];

    public function owner(){
        return  $this->belongsTo(User::class ,'user_id');
    }

    public function path(){
        return $this->thread->path()."#reply-{$this->id}";
    }

//    public function favorites(){
//        return $this->morphMany(Favorite::class, 'favorited');
//    }
//
//    public function favorite(){
//        $attributes =['user_id' =>auth()->id()];
//        if(! $this->favorites()->where($attributes)->exists()){
//            $this->favorites()->create($attributes);
//        }
//
//    }
//    public function isfavorited(){
//        return !! $this->favorites()->where('user_id', auth()->id())->count();
//    }
//
//    public function getFavoritesCountAttribute(){
//        return $this->favorites->count();
//    }
}
