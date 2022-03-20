<?php

namespace App;

use App\Traits\Favoritable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{

    use Favoritable;

    protected $guarded = [];

    protected $with = ['owner', 'favorites'];
    protected $appends = ['favoritesCount', 'isFavorited'];

    protected static function boot()
    {
        parent::boot();
        static::created(function ($reply) {
            $reply->thread->increment('replies_count');
        });
        static::deleted(function ($reply) {
            $reply->thread->decrement('replies_count');
        });
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function path()
    {
        return $this->thread->path() . "#reply-{$this->id}";
    }

    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }

    public function wasJustPublished()
    {
        return $this->created_at->gt(Carbon::now()->subMinute());
    }

    function mentionedUsers()
    {
        preg_match_all('/\@([^\s\.]+)/', $this->body, $matches);
        return $matches[1];
    }

    public function setBodyAttribute($body){
        $this->attributes['body'] =preg_replace('/\@([\w\-]+)/','<a href="/profiles"></a>',$body);
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
