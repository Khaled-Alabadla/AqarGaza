<?php

function getSetting($key = '')
{
    return cache('settings')[$key] ?? '';
}
