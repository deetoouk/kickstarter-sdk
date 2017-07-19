<?php

namespace JTDSoft\EssentialsSdk\Core;

use DateTime;
use JsonSerializable;
use JTDSoft\EssentialsSdk\Contracts\Arrayable;
use JTDSoft\EssentialsSdk\Exceptions\ErrorException;
use ReflectionClass;

/**
 * @Annotation
 * Class Base
 *
 * @package JTDSoft\EssentialsSdk\Objects
 */
abstract class Object implements Arrayable, JsonSerializable
{
    /**
     * @var array
     */
    public $data = [];

    /**
     * @var array
     */
    public $expand = [];

    /**
     * @var array
     */
    public $options = [];

    /**
     * Creates new object
     * if data is object or array it clones all fields
     * else it sets the Id
     *
     * @param null|int|array|\StdClass $data
     */
    public function __construct($data = null)
    {
        if (is_object($data)) {
            $this->copy($data);
        } elseif (is_array($data)) {
            $this->copyFromArray($data);
        } else {
            $this->id = $data;
        }
    }

    /**
     * Copies attributes from target object
     *
     * @param Object $target
     *
     * @return $this
     */
    public function copy(Object $target)
    {
        $this->data = $target->data;

        return $this;
    }

    /**
     * Creates object from array
     *
     * @param array $array
     *
     * @return static
     */
    public function copyFromArray(array $array)
    {
        $to_scan = array_merge(class_parents($this), class_uses_deep($this), [get_class($this) => get_class($this)]);

        $properties = [];

        foreach ($to_scan as $object) {
            $reflect = new ReflectionClass($object);

            preg_match_all(
                '/(@property|@property\-read|@property\-write)\s+(.*)?\n/',
                $reflect->getDocComment(),
                $matches
            );

            foreach ($matches[2] as $match) {
                list($type, $value) = preg_split('/\s+/', $match);
                $properties[ltrim($value, '$')] = $type;
            }
        }

        foreach ($array as $property => $value) {
            if (isset($properties[$property])) {
                $type = $properties[$property];
                if (strpos($type, '[]') !== false) { //array
                    $type              = trim($type, '[]');
                    $this->{$property} = new Collection();
                    foreach ($value[$property] as $key => $single) {
                        $this->{$property}[$key] = static::castSingleProperty($type, $single);
                    }
                } else {
                    $this->{$property} = static::castSingleProperty($type, $value);
                }
            } else {
                $this->{$property} = $value;
            }
        }

        return $this;
    }

    /**
     * @param $type
     * @param $value
     *
     * @return DateTime|float|int|object
     */
    protected static function castSingleProperty($type, $value)
    {
        if ($type === 'int') {
            return intval($value);
        } elseif ($type === 'float') {
            return floatval($value);
        } elseif ($type === 'bool') {
            return boolval($value);
        } elseif ($type === 'object') {
            return (object)$value;
        } elseif (class_exists($type)) {
            if ($type === '\DateTime') {
                return (new \DateTime())->setTimestamp(strtotime($value));
            } else {
                return new $type($value);
            }
        } else { //all other types, including non specified arrays
            return $value;
        }
    }

    /**
     * @param $key
     *
     * @return mixed
     * @throws ErrorException
     */
    public function __get($key)
    {
        $method = 'get' . str_replace(' ', '', ucwords(str_replace(['_'], ' ', $key)));

        if (method_exists($this, $method)) {
            return $this->{$method}($key);
        }

        return $this->data[$key] ?? null;
    }

    /**
     * @param $key
     * @param $value
     *
     * @return mixed
     */
    public function __set($key, $value)
    {
        $method = 'set' . str_replace(' ', '', ucwords(str_replace(['_'], ' ', $key)));

        if (method_exists($this, $method)) {
            return $this->{$method}($key);
        }

        return $this->data[$key] = $value;
    }

    /**
     * @param $key
     *
     * @return bool
     */
    public function __isset($key)
    {
        return isset($this->data[$key]);
    }

    /**
     * @param $key
     */
    public function __unset($key)
    {
        unset($this->data[$key]);
    }

    /**
     * @return string
     */
    function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $array = [];

        foreach ($this->data as $name => $value) {
            if (is_array($value)) {
                foreach ($value as $key => $single) {
                    if ($single instanceof Arrayable) {
                        $array[$name][$key] = $single->toArray();
                    } else {
                        $array[$name][$key] = $single;
                    }
                }
            } else {
                if ($value instanceof Arrayable) {
                    $array[$name] = $value->toArray();
                } elseif ($value instanceof DateTime) {
                    $array[$name] = $value->format(DateTime::ISO8601);
                } else {
                    $array[$name] = $value;
                }
            }
        }

        return $array;
    }

    /**
     * @param array $expand
     *
     * @return $this
     */
    public function expand(array $expand = [])
    {
        $this->expand = $expand;

        return $this;
    }

    /**
     * @param array $options
     *
     * @return $this
     */
    public function options(array $options = [])
    {
        $this->options = $options;

        return $this;
    }

    /**
     * @return \JTDSoft\EssentialsSdk\Core\Service
     */
    protected function api()
    {
        $service = $this->service();

        if ($this->expand) {
            $service->setDefaultRequest('expand', $this->expand);
        }

        if ($this->options) {
            $service->setDefaultRequest('options', $this->options);
        }

        return $service;
    }

    /**
     * Override this method if using extended service
     *
     * @return \JTDSoft\EssentialsSdk\Core\Service
     */
    protected function service()
    {
        return new Service();
    }
}
