<?php
namespace App\Traits;



use App\Favourite;

trait Favoritable
{
//public function favorites()
//{
//return $this->morphMany(Favorite::class, 'favorited');
//}
//
//public function favorite ()
//{
//$attributes = ['user_id' => auth()->id()];
//if ( ! $this->favorites()->where($attributes)->exists() ) {
//return $this->favorites()->create($attributes);
//}
//}
//
//public function isFavorited ()
//{
//return  $this->favorites->where('user_id', auth()->id())->count();
//}
//
//public function getFavoritesCountAttribute ()
//{
//return $this->favorites->count();
//}

    public function favorites(){
        return $this->morphMany(Favourite::class, 'favorited');
    }

    public function favorite(){
        $attributes =['user_id' =>auth()->id()];
        if(! $this->favorites()->where($attributes)->exists()){
            $this->favorites()->create($attributes);
        }

    }
    public function isFavorited(){
        return !! $this->favorites()->where('user_id', auth()->id())->count();
    }
    public function getIsFavouritedAttribute(){
        return $this->isFavorite();
    }

    public function getFavoritesCountAttribute(){
        return $this->favorites->count();
    }

    public function unfavorite(){
       $attributes=['user_id' =>auth()->id()] ;
       $this->favourites()->where($attributes)->delete();
    }
}
