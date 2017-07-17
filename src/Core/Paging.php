<?php

namespace JTDSoft\EssentialsSdk\Core;

/**
 * Class Paging
 *
 * @package JTDSoft\EssentialsSdk
 */
/**
 * Class Paging
 *
 * @package JTDSoft\EssentialsSdk
 */
class Paging extends Collection
{
    /**
     * @var int
     */
    protected $total;

    /**
     * @var int
     */
    protected $page;

    /**
     * @var int
     */
    protected $per_page;

    /**
     * @var int
     */
    protected $from;

    /**
     * @var int
     */
    protected $to;

    /**
     * @var int
     */
    protected $last_page;

    /**
     * @return int
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @param int $total
     */
    public function setTotal($total)
    {
        $this->total = $total;
    }

    /**
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param int $page
     */
    public function setPage($page)
    {
        $this->page = $page;
    }

    /**
     * @return int
     */
    public function getPerPage()
    {
        return $this->per_page;
    }

    /**
     * @param int $per_page
     */
    public function setPerPage($per_page)
    {
        $this->per_page = $per_page;
    }

    /**
     * @return int
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param int $from
     */
    public function setFrom($from)
    {
        $this->from = $from;
    }

    /**
     * @return int
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @param int $to
     */
    public function setTo($to)
    {
        $this->to = $to;
    }

    /**
     * @return int
     */
    public function getLastPage()
    {
        return $this->last_page;
    }

    /**
     * @param int $last_page
     */
    public function setLastPage($last_page)
    {
        $this->last_page = $last_page;
    }

    /**
     * @return bool
     */
    public function isLastPage()
    {
        return $this->page == $this->last_page;
    }

    /**
     * @return bool
     */
    public function isFirstPage()
    {
        return $this->page == 1;
    }
}
