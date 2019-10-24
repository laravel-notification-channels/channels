<?php
/**
 * Created by PhpStorm.
 * User: Neoson Lam
 * Date: 7/3/2019
 * Time: 9:27 AM.
 */

namespace NotificationChannels\MoceanApi\Test\Dummy;

use Mocean\Laravel\Manager;

class MockMoceanClient extends Manager
{
    public function message()
    {
        return parent::message();
    }
}
