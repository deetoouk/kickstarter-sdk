<?php

namespace JTDSoft\EssentialsSdk;

use JTDSoft\EssentialsSdk\Exceptions\ErrorException;

/**
 * Class Cast
 *
 * @package JTDSoft\EssentialsSdk
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
    public static function many($cast, $response): iterable
    {
        $result = new Collection();

        if (!$response) {
            return $result;
        }

        if (isset($response['total']) && isset($response['data'])) { //for paging
            $paging = new Paging(static::many($cast, $response['data']));

            $paging->setPage($response['current_page']);
            $paging->setTotal($response['total']);
            $paging->setLastPage($response['last_page']);
            $paging->setFrom($response['from']);
            $paging->setTo($response['to']);

            return $paging;
        }

        foreach ($response as $key => $value) {
            $result->push(static::single($cast, $value));
        }

        return $result;
    }
}
