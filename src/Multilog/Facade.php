<?php
namespace Karlomikus\Multilog;

class Facade extends \Illuminate\Support\Facades\Facade
{
    protected static function getFacadeAccessor()
    {
        return 'Karlomikus\Multilog\Contracts\MultilogInterface';
    }
}
