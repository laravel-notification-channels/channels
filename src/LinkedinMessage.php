<?php

namespace khaninejad\linkedin;

use Illuminate\Support\Arr;

class LinkedinMessage
{
    private $comment;

    private $apiEndpoint = 'v1/people/~/shares';

    public function __construct($comment)
    {
        $this->comment = $comment;
    }
    public function getApiEndpoint()
    {
        return $this->apiEndpoint;
    }
    public function getRequestBody()
    {
      $options = array('json'=>
    array(
        'comment' => $this->comment,
        'visibility' => array(
            'code' => 'anyone'
        )
    )
);
        return $options;
    }
}
