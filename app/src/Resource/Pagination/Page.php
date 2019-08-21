<?php

namespace App\Resource\Pagination;


class Page
{
    /**
     * @var int
     */
    private $page;
    /**
     * @var int
     */
    private $limit;

    /**
     * @var float|int
     */
    private $offset;

    /**
     * Page constructor.
     * @param int $offset
     * @param int $limit
     */
    public function __construct(int $offset, int $limit)
    {
        $this->offset = $offset;
        $this->limit = $limit;
        $this->page =  floor($offset / $limit) + 1;
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @return float|int
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * @return string
     */
    public function getHashKey()
    {
        return md5($this->page) . md5($this->limit) . md5($this->offset);
    }
}
