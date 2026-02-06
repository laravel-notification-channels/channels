<?php

namespace Zilmoney\OnlineCheckWriter\Message;

abstract class OnlineCheckWriterMessage
{
    protected array $sender = [];
    protected array $metadata = [];

    /**
     * Set the sender/return address.
     */
    public function from(array $address): static
    {
        $this->sender = $address;
        return $this;
    }

    /**
     * Add custom metadata.
     */
    public function metadata(array $metadata): static
    {
        $this->metadata = array_merge($this->metadata, $metadata);
        return $this;
    }

    /**
     * Set the recipient address from an array.
     */
    abstract public function to(array $address): static;

    /**
     * Convert the message to an array for the API.
     */
    abstract public function toArray(): array;

    /**
     * Get the sender, using defaults if not set.
     */
    protected function getSender(): array
    {
        if (!empty($this->sender)) {
            return $this->sender;
        }

        return config('onlinecheckwriter.default_sender', []);
    }
}
