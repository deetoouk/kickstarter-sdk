<?php

namespace JTDSoft\EssentialsSdk\Object;

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
    public function isDirty($attributes = null): bool
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
    public function isClean($attributes = null): bool
    {
        return !$this->isDirty(...func_get_args());
    }

    /**
     * Get the attributes that have been changed since last sync.
     *
     * @return array
     */
    public function getDirty(): array
    {
        return array_only($this->data, $this->dirty_attributes);
    }

    /**
     * @param string $attribute
     */
    public function addDirtyAttribute(string $attribute): void
    {
        if (in_array($attribute, $this->dirty_attributes)) {
            return;
        }

        $this->dirty_attributes[] = $attribute;
    }

    /**
     * cleans dirty attributes
     */
    public function cleanDirtyAttributes(): void
    {
        $this->dirty_attributes = [];
    }
}
