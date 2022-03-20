<?php

namespace Tests\Unit;

use App\Inspections\Spam;
use Tests\TestCase;

class SpamTest extends TestCase
{
    function test_it_checks_for_invalidKeyWords()
    {
        $spam = new Spam();
        $this->assertFalse($spam->detect('Innocent Reply Here'));

        $this->expectException('Exception');
        $this->assertFalse($spam->detect('Yahoo Customer Support'));
    }

    function test_it_checks_for_any_key_being_held_dowm(){
        $spam= new Spam();
        $this->expectException('Exception');
        $spam->detect('Hello World aaaaaaaaaa');

    }
}

