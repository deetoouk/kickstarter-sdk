<?php

namespace DeeToo\KickstarterSdk\SdkObject;

use Illuminate\Support\Collection;
use ReflectionClass;

/**
 * Trait ParsesProperties
 *
 * @package DeeToo\KickstarterSdk\SdkObject
 */
trait ParsesProperties
{
    /**
     * @var array
     */
    protected static $properties = [];

    /**
     * Parses properties from class DocBlock
     */
    protected static function parseProperties()
    {
        if (!empty(static::$properties[static::class])) {
            return;
        }

        $to_scan = array_merge(
            class_parents(static::class),
            class_uses_deep(static::class),
            [static::class => static::class]
        );

        static::$properties[static::class] = collect();

        foreach ($to_scan as $object) {
            $reflect = new ReflectionClass($object);

            preg_match_all(
                '/@property(\-read|\-write)?\s+(.*)?\n/',
                $reflect->getDocComment(),
                $matches
            );

            foreach ($matches[0] as $match) {
                list($annotation_type, $type, $value) = preg_split('/\s+/', $match);

                switch ($annotation_type) {
                    case "@property":
                        $read  = true;
                        $write = true;
                        break;
                    case "@property-read":
                        $read  = true;
                        $write = false;
                        break;
                    case "@property-write":
                        $read  = false;
                        $write = true;
                        break;
                }

                static::$properties[static::class]->put(
                    ltrim($value, '$'),
                    compact('type', 'read', 'write')
                );
            }
        }
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public static function getProperties(): Collection
    {
        static::parseProperties();

        return static::$properties[static::class];
    }

    /**
     *
     */
    public static function getWritableProperties(): Collection
    {
        return self::getProperties()->where('write', true);
    }

    /**
     *
     */
    public static function getReadableProperties(): Collection
    {
        return static::getProperties()->where('read', true);
    }

    /**
     * @param $key
     *
     * @return bool
     */
    public static function isPropertyWritable($key): bool
    {
        return static::getProperties()->get($key)['write'];
    }

    /**
     * @param $key
     *
     * @return bool
     */
    public static function isPropertyReadable($key): bool
    {
        return static::getProperties()->get($key)['read'];
    }

    /**
     * @param $key
     *
     * @return bool
     */
    public static function hasProperty($key): bool
    {
        return static::getProperties()->has($key);
    }

    /**
     * @param $key
     *
     * @return mixed
     */
    public static function getPropertyType($key)
    {
        return static::getProperties()->get($key)['type'];
    }
}
