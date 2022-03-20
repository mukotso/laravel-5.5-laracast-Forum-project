<?php
namespace App;
class Spam{

    public function detect($body){
        $this->detectInvalidKeywords();
        return false;
    }

    function detectInvalidKeywords($body){
        $invalidKeywords=[
            'yahoo customer support'
        ];

        foreach($invalidKeywords  as $keyword){
            if(stripos($body,$keyword)!==false){
                throw new \Exception('Your reply contains spam');
            }
        }
    }
}