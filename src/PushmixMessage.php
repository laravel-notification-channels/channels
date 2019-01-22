<?php

namespace NotificationChannels\Pushmix;

use Illuminate\Support\Arr;
use NotificationChannels\Pushmix\Exceptions\MissingParameter;

class PushmixMessage
{
    /** @var string */
    public $to;

    /** @var string */
    public $title;

    /** @var string */
    public $body;

    /** @var string */
    public $default_url;

    /** @var string */
    public $time_to_live;

    /** @var string */
    public $priority;

    /** @var string */
    public $icon;

    /** @var string */
    public $badge;


    /** @var array */
    public $buttons = [];


    /** @var array */
    public $extraParameters = [];

    /**
     * @param string $body
     *
     * @return static
     */
    public static function create($to)
    {

        return new static($to);
    }

    /**
     * @param string $body
     */
    public function __construct($to = '')
    {
        $this->to = !empty($to) ? $to : "all";
    }
    /***/




    /**
     * Set to parameter
     * @param  string $value specify subscribers topic,
     *  'all' -all subscribers,
     *  'one' - topic one subscribers,
     *  'two' - second topic subscribers
     *
     * @return $this
     */
    public function to($value)
    {
        $this->to = $value;

        return $this;
    }
    /***/

    /**
     * Set Notification Title
     * @param  string $value notification title
     * @return $this
     */
    public function title($value)
    {
        $this->title = $value;

        return $this;
    }
    /***/
    /**
     * Set the message body.
     *
     * @param string $value
     *
     * @return $this
     */
    public function body($value)
    {
        $this->body = $value;

        return $this;
    }
    /***/

    /**
     * Set Notification Default URL
     * @param  string $value notification default url
     * @return $this
     */
    public function url($value)
    {
        $this->default_url = $value;

        return $this;
    }
    /***/

    /**
     * Set Time To Live Parameter
     * @param  string $value notification time to live value
     * @return $this
     */
    public function ttl($value)
    {
        $this->time_to_live = $value;

        return $this;
    }
    /***/


    /**
     * Set Priority Parameter
     * @param  string $value notification priority value
     * @return $this
     */
    public function priority($value)
    {
        $this->priority = $value;

        return $this;
    }
    /***/

    /**
     * Set Icon Parameter
     * @param  string $value notification icon value
     * @return $this
     */
    public function icon($value)
    {
        $this->icon = $value;

        return $this;
    }
    /***/

    /**
     * Set Badge Parameter
     * @param  string $value notification badge value
     * @return $this
     */
    public function badge($value)
    {
        $this->badge = $value;

        return $this;
    }
    /***/

    /**
     * Set Large Image Parameter
     * @param  string $value notification large image
     * @return $this
     */
    public function image($value)
    {
        $this->image = $value;

        return $this;
    }
    /***/

    /**
     * Set Notification Buttons
     * @param  string $title button Title
     * @param  string $ur button url
     * @return $this
     */
    public function button($title, $url)
    {

        $cnt = count($this->buttons);
        // supporting maximum two buttons
        if( $cnt >= 2){

          return $this;

        }



        switch ($cnt) {
          case 0:
            array_push($this->buttons, [
              'action_title_one'  => $title,
              'action_url_one'    => $url
            ]);
            break;

            case 1:
              array_push($this->buttons, [
                'action_title_two'  => $title,
                'action_url_two'    => $url
              ]);
              break;

        }



        return $this;
    }
    /***/

    /**
     * Get Buttons
     * @return array return an array of buttons
     */
    public function getButtons(){
      return $this->buttons;
    }
    /***/



    /**
     * Create Array of Parameters
     * @return array
     */
    public function toArray()
    {

      if( empty( $this->to ) ){
        throw MissingParameter::error('to');
      }

      if( empty( $this->title ) ){
        throw MissingParameter::error('title');
      }

      if( empty( $this->body ) ){
        throw MissingParameter::error('body');
      }

      if( empty( $this->default_url ) ){
        throw MissingParameter::error('url');
      }

        $message = [
            'to'          => $this->to,
            'title'       => $this->title,
            'body'        => $this->body,
            'default_url' => $this->default_url
        ];

        /**
         * Set Optional Parameters
         * @var [type]
         */
        if( !empty($this->priority) ){
          $message['priority'] = $this->priority;
        }

        if( !empty($this->time_to_live) ){
          $message['time_to_live'] = $this->time_to_live;
        }


        if( !empty($this->icon) ){
          $message['icon'] = $this->icon;
        }

        if( !empty($this->badge) ){
          $message['badge'] = $this->badge;
        }

        if( !empty($this->image) ){
          $message['image'] = $this->image;
        }


        foreach ($this->buttons as $data => $value) {
          foreach ($value as $key => $val) {
            Arr::set($message, $key, $val);
          }
        }

        return $message;
    }
    /***/
}
