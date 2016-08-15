<?php

// Load StyleCi-bridge
require_once __DIR__.'/vendor/sllh/php-cs-fixer-styleci-bridge/autoload.php';

use SLLH\StyleCIBridge\ConfigBridge;

return ConfigBridge::create(null, [
    __DIR__.'/src',
])->setUsingCache(true);