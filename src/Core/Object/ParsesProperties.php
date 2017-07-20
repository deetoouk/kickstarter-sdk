<?php
/**
 * Created by PhpStorm.
 * User: jordan
 * Date: 20/07/2017
 * Time: 10:56
 */

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
                '/(@property|@property\-read|@property\-write)\s+(.*)?\n/',
                $reflect->getDocComment(),
                $matches
            );

            foreach ($matches[2] as $match) {
                list($type, $value) = preg_split('/\s+/', $match);
                static::$properties[ltrim($value, '$')] = $type;
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
