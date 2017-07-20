<?php

namespace JTDSoft\EssentialsSdk\Core\Object;

trait HandlesDirtyAttributes
{
    /**
     * @var array
     */
    public $dirty_attributes = [];

    /**
     * Determine if the model or given attribute(s) have been modified.
     *
     * @param  array|string|null $attributes
     *
     * @return bool
     */
    public function isDirty($attributes = null)
    {
        $dirty = $this->getDirty();

        if (is_null($attributes)) {
            return count($dirty) > 0;
        }

        $attributes = is_array($attributes) ? $attributes : func_get_args();

        foreach ($attributes as $attribute) {
            if (array_key_exists($attribute, $dirty)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine if the object or given attribute(s) have remained the same.
     *
     * @param  array|string|null $attributes
     *
     * @return bool
     */
    public function isClean($attributes = null)
    {
        return !$this->isDirty(...func_get_args());
    }

    /**
     * Get the attributes that have been changed since last sync.
     *
     * @return array
     */
    public function getDirty()
    {
        foreach($this->data as $value) {
            if($value instanceof Arrayy)
        }
        return array_only($this->data, $this->dirty_attributes);
    }

    /**
     * @param string $attribute
     */
    public function addDirtyAttribute(string $attribute)
    {
        if (in_array($attribute, $this->dirty_attributes)) {
            return;
        }

        $this->dirty_attributes[] = $attribute;
    }

    /**
     * cleans dirty attributes
     */
    public function cleanDirtyAttributes()
    {
        $this->dirty_attributes = [];
    }
}
