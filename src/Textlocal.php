<?php

namespace NotificationChannels\Textlocal;

use Exception;

/**
 * Textlocal API2 Wrapper Class.
 *
 * This class is used to interface with the Textlocal API2 to send messages, manage contacts, retrieve messages from
 * inboxes, track message delivery statuses, access history reports
 *
 * @author     Andy Dixon <andy.dixon@tetxlocal.com>
 *
 * @version    1.4-IN
 * @string     $request_url       URL to make the request to
 * @const      REQUEST_TIMEOUT   Timeout in seconds for the HTTP request
 * @const      REQUEST_HANDLER   Handler to use when making the HTTP request (for future use)
 */
class Textlocal
{
    const REQUEST_TIMEOUT = 60;
    const REQUEST_HANDLER = 'curl';

    private $request_url;
    private $country;

    private $username;
    private $hash;
    private $apiKey;

    private $errorReporting = false;

    public $errors = [];
    public $warnings = [];

    public $lastRequest = [];
    public $treatAsUnicode = 0;

    /**
     * Instantiate the object.
     *
     * @param $username
     * @param $hash
     */
    public function __construct($username, $hash, $apiKey = false)
    {
        $this->username = $username;
        $this->hash = $hash;
        if ($apiKey) {
            $this->apiKey = $apiKey;
        }

        $this->country = config('textlocal.country');
        $this->request_url = config('textlocal.request_urls')[$this->country];
    }

    /**
     * Private function to construct and send the request and handle the response.
     *
     * @param  $command
     * @param array $params
     *
     * @throws Exception
     *
     * @return array|mixed
     *
     * @todo   Add additional request handlers - eg fopen, file_get_contacts
     */
    private function _sendRequest($command, $params = [])
    {
        if ($this->apiKey && ! empty($this->apiKey)) {
            $params['apiKey'] = $this->apiKey;
        } else {
            $params['hash'] = $this->hash;
        }
        // Create request string
        $params['username'] = $this->username;

        $this->lastRequest = $params;

        if (self::REQUEST_HANDLER == 'curl') {
            $rawResponse = $this->_sendRequestCurl($command, $params);
        } else {
            throw new Exception('Invalid request handler.');
        }

        $result = json_decode($rawResponse);
        if (isset($result->errors)) {
            if (count($result->errors) > 0) {
                foreach ($result->errors as $error) {
                    switch ($error->code) {
                    default:
                        throw new Exception($error->message);
                    }
                }
            }
        }

        return $result;
    }

    /**
     * Curl request handler.
     *
     * @param  $command
     * @param  $params
     *
     * @throws Exception
     *
     * @return mixed
     */
    private function _sendRequestCurl($command, $params)
    {
        $url = $this->request_url.$command.'/';

        // Initialize handle
        $ch = curl_init($url);
        curl_setopt_array(
            $ch, [
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $params,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_TIMEOUT        => self::REQUEST_TIMEOUT,
            ]
        );

        $rawResponse = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($rawResponse === false) {
            throw new Exception('Failed to connect to the Textlocal service: '.$error);
        } elseif ($httpCode != 200) {
            throw new Exception('Bad response from the Textlocal service: HTTP code '.$httpCode);
        }

        return $rawResponse;
    }

    /**
     * fopen() request handler.
     *
     * @param  $command
     * @param  $params
     *
     * @throws Exception
     */
    private function _sendRequestFopen($command, $params)
    {
        throw new Exception('Unsupported transfer method');
    }

    /**
     * Get last request's parameters.
     *
     * @return array
     */
    public function getLastRequest()
    {
        return $this->lastRequest;
    }

    /**
     * Send an SMS to one or more comma separated numbers.
     *
     * @param  $numbers
     * @param  $message
     * @param  $sender
     * @param null  $sched
     * @param false $test
     * @param null  $receiptURL
     * @param null  $custom
     * @param false $optouts
     * @param false $simpleReplyService
     *
     * @throws Exception
     *
     * @return array|mixed
     */
    public function sendSms($numbers, $message, $sender, $sched = null, $test = false, $receiptURL = null, $custom = null, $optouts = false, $simpleReplyService = false)
    {
        if (! is_array($numbers)) {
            throw new Exception('Invalid $numbers format. Must be an array');
        }
        if (empty($message)) {
            throw new Exception('Empty message');
        }
        if (empty($sender)) {
            throw new Exception('Empty sender name');
        }
        if (! is_null($sched) && ! is_numeric($sched)) {
            throw new Exception('Invalid date format. Use numeric epoch format');
        }

        $params = [
            'message'       =>  rawurlencode($message),
            'numbers'       =>  implode(',', $numbers),
            'sender'        =>  rawurlencode($sender),
            'schedule_time' =>  $sched,
            'test'          =>  $test,
            'receipt_url'   =>  $receiptURL,
            'custom'        =>  $custom,
            'optouts'       =>  $optouts,
            'simple_reply'  =>  $simpleReplyService,
            'unicode'       =>  $this->treatAsUnicode,
        ];

        return $this->_sendRequest('send', $params);
    }

    /**
     * Send an SMS to a Group of contacts - group IDs can be retrieved from getGroups().
     *
     * @param  $groupId
     * @param  $message
     * @param null  $sender
     * @param false $test
     * @param null  $receiptURL
     * @param numm  $custom
     * @param false $optouts
     * @param false $simpleReplyService
     *
     * @throws Exception
     *
     * @return array|mixed
     */
    public function sendSmsGroup($groupId, $message, $sender = null, $sched = null, $test = false, $receiptURL = null, $custom = null, $optouts = false, $simpleReplyService = false)
    {
        if (! is_numeric($groupId)) {
            throw new Exception('Invalid $groupId format. Must be a numeric group ID');
        }
        if (empty($message)) {
            throw new Exception('Empty message');
        }
        if (empty($sender)) {
            throw new Exception('Empty sender name');
        }
        if (! is_null($sched) && ! is_numeric($sched)) {
            throw new Exception('Invalid date format. Use numeric epoch format');
        }

        $params = [
        'message'       => rawurlencode($message),
        'group_id'      => $groupId,
        'sender'        => rawurlencode($sender),
        'schedule_time' => $sched,
        'test'          => $test,
        'receipt_url'   => $receiptURL,
        'custom'        => $custom,
        'optouts'       => $optouts,
        'simple_reply'  => $simpleReplyService,
        ];

        return $this->_sendRequest('send', $params);
    }

    /**
     * Send an MMS to a one or more comma separated contacts.
     *
     * @param  $numbers
     * @param  $fileSource - either an absolute or relative path, or http url to a file.
     * @param  $message
     * @param null  $sched
     * @param false $test
     * @param false $optouts
     *
     * @throws Exception
     *
     * @return array|mixed
     */
    public function sendMms($numbers, $fileSource, $message, $sched = null, $test = false, $optouts = false)
    {
        if (! is_array($numbers)) {
            throw new Exception('Invalid $numbers format. Must be an array');
        }
        if (empty($message)) {
            throw new Exception('Empty message');
        }
        if (empty($fileSource)) {
            throw new Exception('Empty file source');
        }
        if (! is_null($sched) && ! is_numeric($sched)) {
            throw new Exception('Invalid date format. Use numeric epoch format');
        }

        $params = [
        'message'       => rawurlencode($message),
        'numbers'       => implode(',', $numbers),
        'schedule_time' => $sched,
        'test'          => $test,
        'optouts'       => $optouts,
        ];

        /*
 * Local file. POST to service
*/
        if (is_readable($fileSource)) {
            $params['file'] = '@'.$fileSource;
        } else {
            $params['url'] = $fileSource;
        }

        return $this->_sendRequest('send_mms', $params);
    }

    /**
     * Send an MMS to a group - group IDs can be.
     *
     * @param  $groupId
     * @param  $fileSource
     * @param  $message
     * @param null  $sched
     * @param false $test
     * @param false $optouts
     *
     * @throws Exception
     *
     * @return array|mixed
     */
    public function sendMmsGroup($groupId, $fileSource, $message, $sched = null, $test = false, $optouts = false)
    {
        if (! is_numeric($groupId)) {
            throw new Exception('Invalid $groupId format. Must be a numeric group ID');
        }
        if (empty($message)) {
            throw new Exception('Empty message');
        }
        if (empty($fileSource)) {
            throw new Exception('Empty file source');
        }
        if (! is_null($sched) && ! is_numeric($sched)) {
            throw new Exception('Invalid date format. Use numeric epoch format');
        }

        $params = [
        'message'       => rawurlencode($message),
        'group_id'      => $groupId,
        'schedule_time' => $sched,
        'test'          => $test,
        'optouts'       => $optouts,
        ];

        /*
 * Local file. POST to service
*/
        if (is_readable($fileSource)) {
            $params['file'] = '@'.$fileSource;
        } else {
            $params['url'] = $fileSource;
        }

        return $this->_sendRequest('send_mms', $params);
    }

    /**
     * Returns reseller customer's ID's.
     *
     * @return array
     **/
    public function getUsers()
    {
        return $this->_sendRequest('get_users');
    }

    /**
     * Transfer credits to a reseller's customer.
     *
     * @param  $user - can be ID or Email
     * @param  $credits
     *
     * @throws Exception
     *
     * @return array|mixed
     **/
    public function transferCredits($user, $credits)
    {
        if (! is_numeric($credits)) {
            throw new Exception('Invalid credits format');
        }
        if (! is_numeric($user)) {
            throw new Exception('Invalid user');
        }
        if (empty($user)) {
            throw new Exception('No user specified');
        }
        if (empty($credits)) {
            throw new Exception('No credits specified');
        }

        if (is_int($user)) {
            $params = [
            'user_id' => $user,
            'credits' => $credits,
            ];
        } else {
            $params = [
            'user_email' => rawurlencode($user),
            'credits'    => $credits,
            ];
        }

        return $this->_sendRequest('transfer_credits', $params);
    }

    /**Get templates from an account **/

    public function getTemplates()
    {
        return $this->_sendRequest('get_templates');
    }

    /**
     * Check the availability of a keyword.
     *
     * @param $keyword
     * return array|mixed
     */
    public function checkKeyword($keyword)
    {
        $params = ['keyword' => $keyword];

        return $this->_sendRequest('check_keyword', $params);
    }

    /**
     * Create a new contact group.
     *
     * @param  $name
     *
     * @return array|mixed
     */
    public function createGroup($name)
    {
        $params = ['name' => $name];

        return $this->_sendRequest('create_group', $params);
    }

    /**
     * Get contacts from a group - Group IDs can be retrieved with the getGroups() function.
     *
     * @param  $groupId
     * @param  $limit
     * @param int $startPos
     *
     * @throws Exception
     *
     * @return array|mixed
     */
    public function getContacts($groupId, $limit, $startPos = 0)
    {
        if (! is_numeric($groupId)) {
            throw new Exception('Invalid $groupId format. Must be a numeric group ID');
        }
        if (! is_numeric($startPos) || $startPos < 0) {
            throw new Exception('Invalid $startPos format. Must be a numeric start position, 0 or above');
        }
        if (! is_numeric($limit) || $limit < 1) {
            throw new Exception('Invalid $limit format. Must be a numeric limit value, 1 or above');
        }

        $params = [
        'group_id' => $groupId,
        'start'    => $startPos,
        'limit'    => $limit,
        ];

        return $this->_sendRequest('get_contacts', $params);
    }

    /**
     * Create one or more number-only contacts in a specific group, defaults to 'My Contacts'.
     *
     * @param  $numbers
     * @param string $groupid
     *
     * @return array|mixed
     */
    public function createContacts($numbers, $groupid = '5')
    {
        $params = ['group_id' => $groupid];

        if (is_array($numbers)) {
            $params['numbers'] = implode(',', $numbers);
        } else {
            $params['numbers'] = $numbers;
        }

        return $this->_sendRequest('create_contacts', $params);
    }

    /**
     * Create bulk contacts - with name and custom information from an array of:
     * [first_name] [last_name] [number] [custom1] [custom2] [custom3].
     *
     * @param array  $contacts
     * @param string $groupid
     *
     * @return array|mixed
     */
    public function createContactsBulk($contacts, $groupid = '5')
    {
        // JSON & URL-encode array
        $contacts = rawurlencode(json_encode($contacts));

        $params = ['group_id' => $groupid, 'contacts' => $contacts];

        return $this->_sendRequest('create_contacts_bulk', $params);
    }

    /**
     * Get a list of groups and group IDs.
     *
     * @return array|mixed
     */
    public function getGroups()
    {
        return $this->_sendRequest('get_groups');
    }

    /**
     * Get the status of a message based on the Message ID - this can be taken from sendSMS or from a history report.
     *
     * @param  $messageid
     *
     * @return array|mixed
     */
    public function getMessageStatus($messageid)
    {
        $params = ['message_id' => $messageid];

        return $this->_sendRequest('status_message', $params);
    }

    /**
     * Get the status of a message based on the Batch ID - this can be taken from sendSMS or from a history report.
     *
     * @param  $batchid
     *
     * @return array|mixed
     */
    public function getBatchStatus($batchid)
    {
        $params = ['batch_id' => $batchid];

        return $this->_sendRequest('status_batch', $params);
    }

    /**
     * Get sender names.
     *
     * @return array|mixed
     */
    public function getSenderNames()
    {
        return $this->_sendRequest('get_sender_names');
    }

    /**
     * Get inboxes available on the account.
     *
     * @return array|mixed
     */
    public function getInboxes()
    {
        return $this->_sendRequest('get_inboxes');
    }

    /**
     * Get Credit Balances.
     *
     * @return array
     */
    public function getBalance()
    {
        $result = $this->_sendRequest('balance');

        return ['sms' => $result->balance->sms, 'mms' => $result->balance->mms];
    }

    /**
     * Get messages from an inbox - The ID can ge retrieved from getInboxes().
     *
     * @param  $inbox
     *
     * @return array|bool|mixed
     */
    public function getMessages($inbox)
    {
        if (! isset($inbox)) {
            return false;
        }
        $options = ['inbox_id' => $inbox];

        return $this->_sendRequest('get_messages', $options);
    }

    /**
     * Cancel a scheduled message based on a message ID from getScheduledMessages().
     *
     * @param  $id
     *
     * @return array|bool|mixed
     */
    public function cancelScheduledMessage($id)
    {
        if (! isset($id)) {
            return false;
        }
        $options = ['sent_id' => $id];

        return $this->_sendRequest('cancel_scheduled', $options);
    }

    /**
     * Get Scheduled Message information.
     *
     * @return array|mixed
     */
    public function getScheduledMessages()
    {
        return $this->_sendRequest('get_scheduled');
    }

    /**
     * Delete a contact based on telephone number from a group.
     *
     * @param  $number
     * @param int $groupid
     *
     * @return array|bool|mixed
     */
    public function deleteContact($number, $groupid = 5)
    {
        if (! isset($number)) {
            return false;
        }
        $options = ['number' => $number, 'group_id' => $groupid];

        return $this->_sendRequest('delete_contact', $options);
    }

    /**
     * Delete a group - Be careful, we can not recover any data deleted by mistake.
     *
     * @param  $groupid
     *
     * @return array|mixed
     */
    public function deleteGroup($groupid)
    {
        $options = ['group_id' => $groupid];

        return $this->_sendRequest('delete_group', $options);
    }

    /**
     * Get single SMS history (single numbers, comma seperated numbers when sending).
     *
     * @param  $start
     * @param  $limit
     * @param  $min_time             Unix timestamp
     * @param  $max_time             Unix timestamp
     *
     * @return array|bool|mixed
     */
    public function getSingleMessageHistory($start, $limit, $min_time, $max_time)
    {
        return $this->getHistory('get_history_single', $start, $limit, $min_time, $max_time);
    }

    /**
     * Get API SMS Message history.
     *
     * @param  $start
     * @param  $limit
     * @param  $min_time             Unix timestamp
     * @param  $max_time             Unix timestamp
     *
     * @return array|bool|mixed
     */
    public function getAPIMessageHistory($start, $limit, $min_time, $max_time)
    {
        return $this->getHistory('get_history_api', $start, $limit, $min_time, $max_time);
    }

    /**
     * Get Email to SMS History.
     *
     * @param  $start
     * @param  $limit
     * @param  $min_time             Unix timestamp
     * @param  $max_time             Unix timestamp
     *
     * @return array|bool|mixed
     */
    public function getEmailToSMSHistory($start, $limit, $min_time, $max_time)
    {
        return $this->getHistory('get_history_email', $start, $limit, $min_time, $max_time);
    }

    /**
     * Get group SMS history.
     *
     * @param  $start
     * @param  $limit
     * @param  $min_time             Unix timestamp
     * @param  $max_time             Unix timestamp
     *
     * @return array|bool|mixed
     */
    public function getGroupMessageHistory($start, $limit, $min_time, $max_time)
    {
        return $this->getHistory('get_history_group', $start, $limit, $min_time, $max_time);
    }

    /**
     * Generic function to provide validation and the request method for getting all types of history.
     *
     * @param  $type
     * @param  $start
     * @param  $limit
     * @param  $min_time
     * @param  $max_time
     *
     * @return array|bool|mixed
     */
    private function getHistory($type, $start, $limit, $min_time, $max_time)
    {
        if (! isset($start) || ! isset($limit) || ! isset($min_time) || ! isset($max_time)) {
            return false;
        }
        $options = ['start' => $start, 'limit' => $limit, 'min_time' => $min_time, 'max_time' => $max_time];

        return $this->_sendRequest($type, $options);
    }

    /**
     * Get a list of surveys.
     *
     * @return array|mixed
     */
    public function getSurveys()
    {
        return $this->_sendRequest('get_surveys');
    }

    /**
     * Get a deatils of a survey.
     *
     * @return array|mixed
     */
    public function getSurveyDetails()
    {
        $options = ['survey_id' => $surveyid];

        return $this->_sendRequest('get_survey_details');
    }

    /**
     * Get a the results of a given survey.
     *
     * @return array|mixed
     */
    public function getSurveyResults($surveyid, $start, $end)
    {
        $options = ['survey_id' => $surveyid, 'start_date' => $start, 'end_date' => $end];

        return $this->_sendRequest('get_surveys', $options);
    }

    /**
     * Get all account optouts.
     *
     * @return array|mixed
     */
    public function getOptouts($time = null)
    {
        return $this->_sendRequest('get_optouts');
    }
    
    /**
     * Set unicode mode
     * 
     * @param bool $mode
     * @return \NotificationChannels\Textlocal\Textlocal
     */
    public function setUnicodeMode(bool $mode)
    {
        $this->treatAsUnicode = (int) $mode;
        return $this;
    }
}

class Contact
{
    public $number;
    public $first_name;
    public $last_name;
    public $custom1;
    public $custom2;
    public $custom3;

    public $groupID;

    /**
     * Structure of a contact object.
     *
     * @param $number
     * @param string $firstname
     * @param string $lastname
     * @param string $custom1
     * @param string $custom2
     * @param string $custom3
     */
    public function __construct($number, $firstname = '', $lastname = '', $custom1 = '', $custom2 = '', $custom3 = '')
    {
        $this->number = $number;
        $this->first_name = $firstname;
        $this->last_name = $lastname;
        $this->custom1 = $custom1;
        $this->custom2 = $custom2;
        $this->custom3 = $custom3;
    }
}

/*
 * If the json_encode function does not exist, then create it..
 */

if (! function_exists('json_encode')) {
    function json_encode($a = false)
    {
        if (is_null($a)) {
            return 'null';
        }
        if ($a === false) {
            return 'false';
        }
        if ($a === true) {
            return 'true';
        }
        if (is_scalar($a)) {
            if (is_float($a)) {
                // Always use "." for floats.
                return floatval(str_replace(',', '.', strval($a)));
            }

            if (is_string($a)) {
                static $jsonReplaces = [['\\', '/', "\n", "\t", "\r", "\b", "\f", '"'], ['\\\\', '\\/', '\\n', '\\t', '\\r', '\\b', '\\f', '\"']];

                return '"'.str_replace($jsonReplaces[0], $jsonReplaces[1], $a).'"';
            } else {
                return $a;
            }
        }
        $isList = true;
        for ($i = 0, reset($a); $i < count($a); $i++, next($a)) {
            if (key($a) !== $i) {
                $isList = false;
                break;
            }
        }
        $result = [];
        if ($isList) {
            foreach ($a as $v) {
                $result[] = json_encode($v);
            }

            return '['.implode(',', $result).']';
        } else {
            foreach ($a as $k => $v) {
                $result[] = json_encode($k).':'.json_encode($v);
            }

            return '{'.implode(',', $result).'}';
        }
    }
}
