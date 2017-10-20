<?php

namespace Tests;

class ObjectTest extends TestCase
{
    public function test_parses_properties()
    {
        $class = new Example();

        $this->assertSame(
            array_keys($class::getProperties()),
            [
                'name',
                'age',
                'interests',
                'orders',
                'note',
                'parent',
            ]
        );

        foreach ($class::getProperties() as $property) {
            $this->assertArrayHasKey('type', $property);
            $this->assertArrayHasKey('read', $property);
            $this->assertArrayHasKey('write', $property);
        }
    }

    /**
     * @expectedException \JTDSoft\EssentialsSdk\Exceptions\ErrorException
     * @expectedExceptionMessage Property orders is read-only!
     */
    public function test_read_only_properties()
    {
        $example = new Example();

        $var = $example->orders;

        $example->orders = 123;
    }

    /**
     * @expectedException \JTDSoft\EssentialsSdk\Exceptions\ErrorException
     * @expectedExceptionMessage Property note is write-only!
     */
    public function test_write_only_properties()
    {
        $example = new Example();

        $example->note = 'some note';

        $var = $example->note;
    }

    public function test_support_dirty_data()
    {
        $example = new Example();

        $this->assertTrue($example->isClean());
        $this->assertFalse($example->isDirty());
        $this->assertEmpty($example->getDirty());

        $example->name = 'Jordan Dobrev';

        $example->age = 27;

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

        $this->assertTrue($example->isClean());

        $example = new Example(['name' => 'Jordan Dobrev']);

        $this->assertTrue($example->isDirty());
    }
}
