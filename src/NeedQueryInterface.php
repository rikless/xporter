<?php

namespace Rikless\Xporter;

use Illuminate\Http\Request;

/**
 * Interface NeedQueryInterface
 * @package Rikless\Xporter
 */
interface NeedQueryInterface
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function query(Request $request);
}
