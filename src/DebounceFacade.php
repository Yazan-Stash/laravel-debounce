<?php

namespace YazanStash\LaravelDebounce;

use Illuminate\Support\Facades\Facade;

class DebounceFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'debounce';
    }
}
