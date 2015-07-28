<?php
namespace Codex\Codex\Facades;

use Illuminate\Support\Facades\Facade;

class Codex extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'codex';
    }
}
