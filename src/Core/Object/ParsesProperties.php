<?php

namespace JTDSoft\EssentialsSdk\Core\Object;

use ReflectionClass;

/**
 * Trait ParsesProperties
 *
 * @package JTDSoft\EssentialsSdk\Core\Object
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
        if (!empty(static::$properties)) {
            return;
        }

        $to_scan = array_merge(
            class_parents(static::class),
            class_uses_deep(static::class),
            [static::class => static::class]
        );

        static::$properties = [];

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
                static::$properties[ltrim($value, '$')] = compact('type', 'read', 'write');
            }
        }
    }

    /**
     * @return array
     */
    public static function getProperties()
    {
        static::parseProperties();

        return static::$properties;
    }
}
