<?php

/*
 * This file is part of the Laravel Multiselect package.
 *
 * (c) Andre Chalom <andrechalom@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AndreChalom\LaravelMultiselect;

use Illuminate\Support\Facades\Facade;

/**
 * @codeCoverageIgnore
 */
class MultiselectFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'multiselect';
    }
}
