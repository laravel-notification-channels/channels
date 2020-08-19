<?php

return [
  /**
  *  @var string phone number for sender;
  * ex: +12345556789, +442012345678
  **/
  'username' => env('SIGNAL_USERNAME'),
  'java_home' => env('JAVA_HOME'),

 /**
 * Path to signal_cli binary file
 **/
  'signal_cli' => env('SIGNAL_CLI_LOCATION'),
]
