<?php

namespace App\Traits;


trait RecordsActivity{

    protected static function bootRecordsActivity(){
        if(auth()->guest())  return;
        foreach(static::getRecordEvents() as $event){
            static::$event(function ($model) use ($event) {
                $model->recordActivity($event);
            });

        }

    }
    public
    function recordActivity($event)
    {
        $this->activity()->create([
            'user_id'=>auth()->id(),
            'type'=>$this->getActivityType($event)

        ]);
//        Activity::create([
//            'user_id' => auth()->id(),
//            'type' =>$this->getActivityType($event),
//            'subject_id' => $this->id,
//            'subject_type' => 'App\Thread'
//
//        ]);
    }
    protected function getActivityType($event){
        $type=strtolower((new \ReflectionClass($this))->getShortName());
        return "($event .'_' .$type)";
    }

    public function activity(){
        return $this->morphMany('App\Activity','subject');
    }
}
