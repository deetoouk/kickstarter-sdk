<?php

namespace JTDSoft\EssentialsSdk\Core;

use JTDSoft\EssentialsSdk\Exceptions\ErrorException;

/**
 * Class Cast
 *
 * @package JTDSoft\EssentialsSdk\Core
 */
class Cast
{
    /**
     * @param string $cast
     * @param        $response
     *
     * @return mixed
     */
    public static function single(string $cast, $response)
    {
        return new $cast($response);
    }

    /**
     * @param      $response
     * @param null $cast
     *
     * @return mixed
     * @throws ErrorException
     */
    public static function many($cast, $response)
    {
        $result = [];

        if (!$response) {
            return $result;
        }

        if (isset($response['total']) && isset($response['data'])) { //for paging
            $paging = new Paging();

            $paging->setPage($response['current_page']);
            $paging->setTotal($response['total']);
            $paging->setLastPage($response['last_page']);
            $paging->setFrom($response['from']);
            $paging->setTo($response['to']);
            $paging->setItems(static::castMany($cast, $response['data']));

            return $paging;
        }

        foreach ($response as $key => $value) {
            $result[] = self::cast($cast, $value);
        }

        return new Collection($result);
    }
}
