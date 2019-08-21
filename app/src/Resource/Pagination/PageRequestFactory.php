<?php

namespace App\Resource\Pagination;

use Symfony\Component\HttpFoundation\Request;

class PageRequestFactory
{
    private const KEY_OFFSET = 'offset';
    private const KEY_LIMIT = 'limit';
    private const DEFAULT_OFFSET = 0;
    private const DEFAULT_LIMIT = 10;

    public function fromRequest(Request $request): Page
    {
        $offset = $request->get(self::KEY_OFFSET, self::DEFAULT_OFFSET);
        $limit  = $request->get(self::KEY_LIMIT, self::DEFAULT_LIMIT);

        return new Page($offset, $limit);
    }
}
