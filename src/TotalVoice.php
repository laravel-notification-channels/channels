<?php

namespace NotificationChannels\TotalVoice;

use TotalVoice\Client as TotalVoiceService;
use NotificationChannels\TotalVoice\Exceptions\CouldNotSendNotification;

class TotalVoice
{
    /**
     * @var TotalVoiceService
     */
    protected $totalVoiceService;

    /**
     * @var TotalVoiceConfig
     */
    private $config;

    /**
     * TotalVoice constructor.
     *
     * @param TotalVoiceService $totalVoiceService
     * @param TotalVoiceConfig $config
     */
    public function __construct(TotalVoiceService $totalVoiceService, TotalVoiceConfig $config)
    {
        $this->totalVoiceService = $totalVoiceService;
        $this->config = $config;
    }

    /**
     * Send a TotalVoiceMessage to the a phone number.
     *
     * @param  TotalVoiceMessage $message
     * @param  string $to
     * @return mixed
     * @throws CouldNotSendNotification
     */
    public function sendMessage(TotalVoiceMessage $message, $to)
    {
        if ($message instanceof TotalVoiceSmsMessage) {
            return $this->sendSmsMessage($message, $to);
        }
        if ($message instanceof TotalVoiceTtsMessage) {
            return $this->sendTtsMessage($message, $to);
        }
        if ($message instanceof TotalVoiceAudioMessage) {
            return $this->sendAudioMessage($message, $to);
        }
        throw CouldNotSendNotification::invalidMessageObject($message);
    }

    /**
     * Send an sms message using the TotalVoice Service.
     *
     * @param TotalVoiceSmsMessage $message
     * @param string $to
     * @return mixed
     * @throws CouldNotSendNotification
     */
    public function sendSmsMessage(TotalVoiceSmsMessage $message, $to)
    {
        return $this->totalVoiceService->sms->enviar($to,
                                                    trim($message->content),
                                                    $message->provide_feedback,
                                                    $message->multi_part,
                                                    $message->scheduled_datetime);
    }

    /**
     * Make a text-to-speech call using the TotalVoice Service.
     *
     * @param TotalVoiceTtsMessage $message
     * @param string $to
     * @return mixed
     * @throws CouldNotSendNotification
     */
    public function sendTtsMessage(TotalVoiceTtsMessage $message, $to)
    {
        $optionalParams = [
            'velocidade' => $message->speed,
            'resposta_usuario' => $message->provide_feedback,
            'tipo_voz' => $message->voice_type,
            'bina' => $message->fake_number,
            'gravar_audio' => $message->record_audio,
            'detecta_caixa' => $message->detect_callbox,
        ];

        return $this->totalVoiceService->tts->enviar($to,
                                                    trim($message->content),
                                                    $optionalParams);
    }

    /**
     * Make a call using the TotalVoice Service.
     *
     * @param TotalVoiceAudioMessage $message
     * @param string $to
     * @return mixed
     * @throws CouldNotSendNotification
     */
    public function sendAudioMessage(TotalVoiceAudioMessage $message, $to)
    {
        return $this->totalVoiceService
                    ->audio
                    ->enviar($to,
                            trim($message->content),
                            $message->provide_feedback,
                            $message->fake_number,
                            $message->record_audio);
    }
}
