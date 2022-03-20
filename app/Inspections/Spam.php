<?php
namespace App\Inspections;
class Spam{

    protected $inspections=[
        invalidKeywords::class,
        keyHeldDown::class
    ];
    public function detect($body){
        foreach ($this->inspections as $inspection){
            app($inspection)->detect($body);
        }
        $this->detectKeyHeldDown($body);
        return false;
    }


    function detectKeyHeldDown($body)
    {

    }
}