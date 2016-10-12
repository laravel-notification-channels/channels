<?php

namespace NotificationChannels\SMSGatewayMe\Test;

use NotificationChannels\SMSGatewayMe\SMSGatewayMeChannel;
use NotificationChannels\SMSGatewayMe\SMSGatewayMeMessage;
use NotificationChannels\SMSGatewayMe\SMSGatewayMeServiceProvider;
use NotificationChannels\SMSGatewayMe\Test\HelloWorld;
use GuzzleHttp\Client;
use Illuminate\Notifications\Notifiable;

/**
 * SMSGatewayMe Test
 */
class Test extends \PHPUnit_Framework_TestCase
{
  use Notifiable;

  public function testMessage()
  {
    $message = (new SMSGatewayMeMessage)->text('Test');
    $this->assertEquals('Test', $message->text);
  }

  /**
   * @dataProvider  sendDataProvider
   */
  public function testSend($email, $password, $device_id)
  {
    // $this->notify(new HelloWorld());
    $channel = new SMSGatewayMeChannel(
      new Client(['base_uri' => 'http://smsgateway.me']),
      $email, $password, $device_id
    );
    $channel->send($this, new HelloWorld());
  }

  public function routeNotificationForSmsGatewayMe()
  {
      return ['sms-to-phone-number'];
  }

  public function sendDataProvider()
  {
    return [
      ['smsgateway-me-email', 'smsgateway-me-password', 'smsgateway-me-device_id']
    ];
  }
}
