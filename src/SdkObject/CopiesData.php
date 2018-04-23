<?php

namespace DeeToo\KickstarterSdk\SdkObject;

use Carbon\Carbon;
use DeeToo\KickstarterSdk\Collection;
use DeeToo\KickstarterSdk\SdkObject;

trait CopiesData
{
    /**
     * Copies attributes from target object
     *
     * @param SdkObject $target
     *
     * @return $this
     */
    public function copy(SdkObject $target)
    {
        foreach ($target as $attribute => $value) {
            $this->{$attribute} = $value;
        }

        $this->markSetDataAsDirty();

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
            if (static::hasProperty($property) && !is_null($value)) {
                $type = static::getPropertyType($property);
                if (strpos($type, '[]') !== false) { //array
                    $type                  = trim($type, '[]');
                    $this->data[$property] = new Collection();
                    foreach ($value as $key => $single) {
                        $this->data[$property]->put($key, static::castSingleProperty($type, $single));
                    }
                } else {
                    $this->data[$property] = static::castSingleProperty($type, $value);
                }
            } else {
                $this->data[$property] = $value;
            }
        }

        $this->markSetDataAsDirty();

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
        switch ($type) {
            case 'int':
            case 'integer':
                return intval($value);
            case 'float':
                return floatval($value);
            case 'object':
                return (object)$value;
            case '\DateTime':
                return Carbon::createFromTimestamp(strtotime($value));
            default:
                if (class_exists($type)) {
                    if (is_subclass_of($type, SdkObject::class)) {
                        return new $type($value, true);
                    }

                    return new $type($value);
                }

                return $value;
        }
    }
}
