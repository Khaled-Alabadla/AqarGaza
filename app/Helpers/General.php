<?php

namespace App\Helpers;

function getSetting($key = '')
{
    return cache('settings')[$key] ?? '';
}
