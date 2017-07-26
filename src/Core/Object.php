<?php

namespace JTDSoft\EssentialsSdk\Core;

use DateTime;
use Illuminate\Contracts\Support\Jsonable;
use JsonSerializable;
use JTDSoft\EssentialsSdk\Contracts\Arrayable;
use JTDSoft\EssentialsSdk\Core\Object\CopiesData;
use JTDSoft\EssentialsSdk\Core\Object\HandlesDirtyAttributes;
use JTDSoft\EssentialsSdk\Core\Object\ParsesProperties;
use JTDSoft\EssentialsSdk\Exceptions\ErrorException;

/**
 * @Annotation
 * Class Base
 *
 * @package JTDSoft\EssentialsSdk\Objects
 */
abstract class Object implements Arrayable, JsonSerializable, Jsonable
{
    use HandlesDirtyAttributes,
        ParsesProperties,
        CopiesData;

    /**
     * @var array
     */
    public $data = [];

    /**
     * @var array
     */
    protected $expand = [];

    /**
     * @var array
     */
    protected $options = [];

    /**
     * Creates new object
     * if data is object or array it clones all fields
     * else it sets the Id
     *
     * @param null|int|array|\StdClass $data
     */
    public function __construct($data = null)
    {
        static::parseProperties();

        if ($data) {
            if ($data instanceof Object) {
                $this->copy($data);
            } elseif (is_object($data)) {
                $this->copyFromArray((array)$data);
            } elseif (is_array($data)) {
                $this->copyFromArray($data);
            } else {
                $this->id = $data;
            }
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
        if (array_key_exists($key, static::getProperties())) {
            if (!static::getProperties()[$key]['read']) {
                throw new ErrorException(sprintf('Property %1$s is write-only!', $key));
            }
        }

        $method = 'get' . str_replace(' ', '', ucwords(str_replace(['_'], ' ', $key))) . 'Property';

        if (method_exists($this, $method)) {
            return $this->{$method}($key);
        }

        return $this->data[$key] ?? null;
    }

    /**
     * @param $key
     * @param $value
     *
     * @throws \JTDSoft\EssentialsSdk\Exceptions\ErrorException
     */
    public function __set($key, $value)
    {
        if (array_key_exists($key, static::$properties)) {
            if (!static::getProperties()[$key]['write']) {
                throw new ErrorException(sprintf('Property %1$s is read-only!', $key));
            }
        }

        $method = 'set' . str_replace(' ', '', ucwords(str_replace(['_'], ' ', $key))) . 'Property';

        $before = $this->data[$key] ?? null;

        if (!array_key_exists($key, $this->data)) {
            $this->addDirtyAttribute($key);
        }

        if (method_exists($this, $method)) {
            $this->{$method}($value);

            if (!array_key_exists($key, $this->data)) {
                throw new ErrorException($method . ' should set value!');
            }
        } else {
            $this->data[$key] = $value;
        }

        $after = $this->data[$key] ?? null;

        if ($before !== $after) {
            $this->addDirtyAttribute($key);
        }
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
        if (array_key_exists($key, $this->data)) {
            $this->addDirtyAttribute($key);
            unset($this->data[$key]);
        }
    }

    /**
     * @return array
     */
    function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * Get the collection of items as JSON.
     *
     * @param  int $options
     *
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->jsonSerialize(), $options);
    }

    /**
     * Convert the collection to its string representation.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toJson();
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $array = [];

        foreach ($this->data as $name => $value) {
            if (is_iterable($value)) {
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
     * @return mixed
     */
    public function getExpand()
    {
        return $this->expand;
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
     * @return mixed
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     *
     */
    public function settings()
    {
        return [
            'expand'  => $this->expand,
            'options' => $this->options,
        ];
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
