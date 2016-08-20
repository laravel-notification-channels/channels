<?php

namespace NotificationChannels\Gammu;

class GammuMessage
{
    const VERSION = '0.0.1';

    /**
     * @var array Params payload.
     */
    public $payload = [];

    /**
     * @var array Multipart chunks.
     */
    public $multiparts = [];

    /**
     * @param string $content
     *
     * @return static
     */
    public static function create($content = '')
    {
        return new static($content);
    }

    /**
     * Create a new message instance.
     *
     * @param string $content
     */
    public function __construct($content = '')
    {
        $this->content($content);
        $this->payload['CreatorID'] = class_basename($this).'/'.self::VERSION;
        $this->payload['MultiPart'] = 'false';
    }

    /**
     * Destination phone number.
     *
     * @param $phoneNumber
     *
     * @return $this
     */
    public function to($phoneNumber)
    {
        $this->payload['DestinationNumber'] = $phoneNumber;

        return $this;
    }

    /**
     * SMS message.
     *
     * @param $content
     *
     * @return $this
     */
    public function content($content)
    {
        // Check if long SMS
        if (strlen($content) > 160) {
            // Parse message to chunks
            // @ref: http://www.nowsms.com/long-sms-text-messages-and-the-160-character-limit
            $messages = str_split($content, 153);
            $messages = collect($messages);
            $messages_count = $messages->count();

            // Get first message
            $firstChunk = $messages->shift();

            // Generate UDH
            $ref = mt_rand(0, 255);
            $i = 1;
            $firstUDH = $this->generateUDH($messages_count, $i, $ref);

            $this->payload['TextDecoded'] = $firstChunk;
            $this->payload['UDH'] = $firstUDH;
            $this->payload['MultiPart'] = 'true';

            $i = 2;
            foreach ($messages as $chunk) {
                array_push($this->multiparts, [
                    'UDH' => $this->generateUDH($messages_count, $i, $ref),
                    'TextDecoded' => $chunk,
                    'SequencePosition' => $i,
                ]);
                ++$i;
            }
        } else {
            $this->payload['TextDecoded'] = $content;
        }

        return $this;
    }

    /**
     * Sender Phone ID.
     *
     * @param $phoneId
     *
     * @return $this
     */
    public function sender($phoneId = null)
    {
        $this->payload['SenderID'] = $phoneId;

        return $this;
    }

    /**
     * Determine if Sender Phone ID is not given.
     *
     * @return bool
     */
    public function senderNotGiven()
    {
        return ! isset($this->payload['SenderID']);
    }

    /**
     * Determine if Destination Phone Number is not given.
     *
     * @return bool
     */
    public function destinationNotGiven()
    {
        return ! isset($this->payload['DestinationNumber']);
    }

    /**
     * Returns params payload.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->payload;
    }

    /**
     * Returns multipart chunks.
     *
     * @return array
     */
    public function getMultipartChunks()
    {
        return $this->multiparts;
    }

    /**
     * Generate UDH part for long SMS.
     *
     * @link https://en.wikipedia.org/wiki/Concatenated_SMS#Sending_a_concatenated_SMS_using_a_User_Data_Header
     *
     * @return string
     */
    protected function generateUDH($total = 2, $sequence = 2, $ref = 0)
    {
        // Length of User Data Header, in this case 05
        $octet_1 = '05';

        // Information Element Identifier, equal to 00 (Concatenated short messages, 8-bit reference number)
        $octet_2 = '00';

        // Length of the header, excluding the first two fields; equal to 03
        $octet_3 = '03';

        // CSMS reference number, must be same for all the SMS parts in the CSMS
        $octet_4 = str_pad(dechex($ref), 2, '0', STR_PAD_LEFT);

        // Total number of parts
        $octet_5 = str_pad(dechex($total), 2, '0', STR_PAD_LEFT);

        // Part sequence
        $octet_6 = str_pad(dechex($sequence), 2, '0', STR_PAD_LEFT);

        $udh = implode('', [
            $octet_1, $octet_2, $octet_3, $octet_4, $octet_5, $octet_6,
        ]);

        return strtoupper($udh);
    }
}
