<?php

namespace App;

use App\Notifications\ThreadWasUpdated;
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

        $this->subscriptions->filter(function ($sub) use ($reply) {
            return $sub->user_id != $reply->user_id;
        })->each->notify($reply);
//            ->each(function ($sub) use ($reply) {
//                $sub->user->notify(new ThreadWasUpdated($this, $reply));
//            });
//        foreach ($this->subscriptions as $subscription) {
//            if ($subscription->user_id != $reply->user_id) {
//                $subscription->user->notify(new ThreadWasUpdated($this, $reply));
//            }
//        }
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

        return $this;
    }

    public function subscriptions()
    {
        return $this->hasMany(ThreadSubscription::class);

    }

    function unsubscribe($userId = null)
    {
        $this->subscriptions()->where('user_id', $userId ?: auth()->id())->delete();
    }

    public function getIsSubscribedToAttribute()
    {
        return $this->subscriptions()
            ->where('user_id', auth()->id())
            ->exists();
    }

}
