<?php
/**
 * Author: Hilmi Erdem KEREN
 * Date: 17/11/2016.
 */
namespace NotificationChannels\JetSMS;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;
use NotificationChannels\JetSMS\Clients\Http\JetSMSClient;
use NotificationChannels\JetSMS\Clients\JetSMSClientInterface;

/**
 * Class JetSMSServiceProvider.
 */
class JetSMSServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->app->when(JetSMSChannel::class)
                  ->needs(JetSMSClientInterface::class)
                  ->give(function () {
                      $config = config('services.JetSMS', [
                          'http'       => [
                              'endpoint' => '',
                          ],
                          'username'   => '',
                          'password'   => '',
                          'timeout'    => '',
                          'originator' => '',
                      ]);

                      $endpoint = $config['http']['endpoint'];
                      $username = $config['username'];
                      $password = $config['password'];
                      $timeout = $config['timeout'];
                      $originator = $config['originator'];

                      $guzzleHttpClient = new Client(['timeout' => $timeout]);
                      $jetSMSClient = new JetSMSClient($guzzleHttpClient, $endpoint, $username, $password, $originator);

                      return $jetSMSClient;
                  });
    }
}
