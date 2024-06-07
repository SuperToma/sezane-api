<?php

namespace App\Pagination;

use Symfony\Component\HttpFoundation\Request;

class Paginator
{
    public const ITEMS_PER_PAGE = 20;
    private int $page;
    private int $limit;
    private int $offset;

    public function __construct(Request $request)
    {
        $this->page = (int) $request->get('page', 1);
        $this->limit = (int) $request->get('limit', self::ITEMS_PER_PAGE);
        $this->offset = $this->limit * ($this->page - 1);
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function setPage(int $page): void
    {
        $this->page = $page;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function setLimit(int $limit): void
    {
        $this->limit = $limit;
    }

    public function getOffset(): int
    {
        return $this->offset;
    }

    public function setOffset(int $offset): void
    {
        $this->offset = $offset;
    }
}
