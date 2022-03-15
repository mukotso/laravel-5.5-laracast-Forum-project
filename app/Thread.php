<?php

namespace App;

use App\Traits\RecordsActivity;
use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    use RecordsActivity;

    protected $guarded = [];
    protected $with = ['creator', 'channel'];
//    public function path (){;
//        return '/threads/'.$this->channel->slug.'/'.$this->id;
//    }

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('replyCount', function ($builder) {
            $builder->withCount('replies');
        });
//
//        static::addGlobalScope('creator', function($builder){
//            $builder->withCount('replies');
//        });

        static::deleting(function ($thread) {
            $thread->replies()->delete();
        });


    }


    public function path()
    {
        return '/threads/' . $this->channel->slug . '/' . $this->id;
    }

    public function replies()
    {
        return $this->hasMany(Reply::class)
            ->withCount('favorites')->with('owner');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function addReply($reply)
    {

        $reply = $this->replies()->create($reply);
//        $this->increments('replies_count');
        return $reply;


    }

    public function channel()
    {
        return $this->belongsTo(Channel::class);

    }

    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }

    public function subscribe($userId = null)
    {
        $this->subscriptions()->create([
            'user_id' => $userId ?: auth()->id()
        ]);
    }

    public function subscriptions()
    {
      return  $this->hasMany(ThreadSubscription::class);

    }

    function unsubscribe($userId = null)
    {
        $this->subscriptions()->where('user_id', $userId ?: auth()->id())->delete();
    }

}
