<?php

namespace JTDSoft\EssentialsSdk\Core\Object;

use JTDSoft\EssentialsSdk\Core\Collection;

trait CopiesData
{
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
        foreach ($array as $property => $value) {
            if (isset(static::$properties[$property])) {
                $type = static::$properties[$property]['type'];
                if (strpos($type, '[]') !== false) { //array
                    $type                  = trim($type, '[]');
                    $this->data[$property] = new Collection();
                    foreach ($value[$property] as $key => $single) {
                        $this->data[$property][$key] = static::castSingleProperty($type, $single);
                    }
                } else {
                    $this->data[$property] = static::castSingleProperty($type, $value);
                }
            } else {
                $this->data[$property] = $value;
            }
        }

        return $this;
    }

    /**
     * @param $type
     * @param $value
     *
     * @return mixed
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
}
