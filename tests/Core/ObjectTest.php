<?php

namespace Tests\Core;

use Tests\TestCase;

class ObjectTest extends TestCase
{
    public function test_parses_properties()
    {
        $class = new Example();

        dd($class::getProperties());
    }

    public function test_support_dirt_data()
    {
        $example = new Example();

        $this->assertTrue($example->isClean());
        $this->assertFalse($example->isDirty());
        $this->assertEmpty($example->getDirty());

        $example->name = 'Jordan Dobrev';
        $example->age  = 27;

        $this->assertFalse($example->isClean());
        $this->assertTrue($example->isDirty());
        $this->assertTrue($example->isDirty('name'));
        $this->assertTrue($example->isDirty('age'));
        $this->assertFalse($example->isDirty('interests'));
        $this->assertSame($example->getDirty(), ['name' => "Jordan Dobrev", 'age' => 27]);

        $example->interests = null;

        $this->assertTrue($example->isDirty('interests'));

        $example->cleanDirtyAttributes();

        $this->assertTrue($example->isClean());

        $example2       = new Example();
        $example2->name = 'Luke Skywalker';

        $example->parent = $example2;

        $this->assertTrue($example->isDirty());

        $example->cleanDirtyAttributes();

        $this->assertTrue($example->isClean());

        $example->parent->name = 'Leia Skywalker';

        dd($example->getDirty());
    }
}
