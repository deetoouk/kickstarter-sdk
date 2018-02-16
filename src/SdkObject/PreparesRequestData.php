<?php

namespace JTDSoft\EssentialsSdk\SdkObject;

/**
 * Trait PreparesRequestData
 *
 * @package JTDSoft\EssentialsSdk\SdkObject
 */
trait PreparesRequestData
{
    /**
     * @param bool $dirtyDataOnly
     *
     * @return array
     */
    public function prepareRequestData($dirtyDataOnly = true): array
    {
        $attributes = $dirtyDataOnly ? $this->getDirty() : $this->data;

        $data = array_only($attributes, static::getWritableProperties()->keys()->all());

        return $this->convertArrayToJsonSerializable($data);
    }
}
