<?php

namespace NotificationChannels\TotalVoice;

trait TotalVoiceMessageOptions
{
    /**
     * @var null|string
     */
    public $fake_number = null;

    /**
     * @var bool
     */
    public $record_audio = false;

    /**
     * @var bool
     */
    public $detect_callbox = false;

    /**
     * Define o número de telefone que aparecerá no identificador
     * de quem receber a chamada, formato DDD + Número exemplo: 4832830151.
     *
     * @param $fake_number
     * @return $this
     */
    public function fakeNumber($fake_number)
    {
        $this->fake_number = $fake_number;

        return $this;
    }

    /**
     * Define se vai gravar a chamada.
     *
     * @param bool $record_audio
     * @return $this
     */
    public function recordAudio($record_audio)
    {
        $this->record_audio = $record_audio;

        return $this;
    }

    /**
     * Define se vai desconectar em caso de cair na caixa postal
     * (vivo, claro, tim e oi).
     *
     * @param bool $detect_callbox
     * @return $this
     */
    public function detectCallbox($detect_callbox)
    {
        $this->detect_callbox = $detect_callbox;

        return $this;
    }
}
