<?php

namespace Zilmoney\OnlineCheckWriter\Message;

class OnlineCheckWriterDocumentMail extends OnlineCheckWriterMessage
{
    protected ?string $filePath = null;
    protected ?string $attachmentUrl = null;
    protected ?string $documentTitle = null;
    protected ?string $name = null;
    protected ?string $company = null;
    protected ?string $address1 = null;
    protected ?string $address2 = null;
    protected ?string $city = null;
    protected ?string $state = null;
    protected ?string $zip = null;
    protected ?string $phone = null;
    protected ?string $email = null;
    protected int $shippingTypeId = 3; // Default shipping type

    /**
     * Create a new document mail message instance.
     */
    public static function create(): static
    {
        return new static();
    }

    /**
     * Set the file path to upload.
     */
    public function file(string $filePath): static
    {
        $this->filePath = $filePath;
        return $this;
    }

    /**
     * Set the attachment URL (if document is already uploaded).
     */
    public function attachmentUrl(string $url): static
    {
        $this->attachmentUrl = $url;
        return $this;
    }

    /**
     * Set the document/attachment title.
     */
    public function documentTitle(string $title): static
    {
        $this->documentTitle = $title;
        return $this;
    }

    /**
     * Set the recipient name.
     */
    public function name(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Set the recipient company.
     */
    public function company(string $company): static
    {
        $this->company = $company;
        return $this;
    }

    /**
     * Set the recipient address line 1.
     */
    public function address1(string $address1): static
    {
        $this->address1 = $address1;
        return $this;
    }

    /**
     * Set the recipient address line 2.
     */
    public function address2(string $address2): static
    {
        $this->address2 = $address2;
        return $this;
    }

    /**
     * Set the recipient city.
     */
    public function city(string $city): static
    {
        $this->city = $city;
        return $this;
    }

    /**
     * Set the recipient state.
     */
    public function state(string $state): static
    {
        $this->state = $state;
        return $this;
    }

    /**
     * Set the recipient zip code.
     */
    public function zip(string $zip): static
    {
        $this->zip = $zip;
        return $this;
    }

    /**
     * Set the recipient phone.
     */
    public function phone(string $phone): static
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * Set the recipient email.
     */
    public function email(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Set the shipping type ID.
     * Common values:
     * - 1: Standard
     * - 2: Express
     * - 3: Priority (default)
     */
    public function shippingType(int $shippingTypeId): static
    {
        $this->shippingTypeId = $shippingTypeId;
        return $this;
    }

    /**
     * Set the recipient address from an array.
     */
    public function to(array $address): static
    {
        $this->name = $address['name'] ?? null;
        $this->company = $address['company'] ?? null;
        $this->address1 = $address['address1'] ?? $address['address_line_1'] ?? null;
        $this->address2 = $address['address2'] ?? $address['address_line_2'] ?? null;
        $this->city = $address['city'] ?? null;
        $this->state = $address['state'] ?? null;
        $this->zip = $address['zip'] ?? $address['postal_code'] ?? null;
        $this->phone = $address['phone'] ?? null;
        $this->email = $address['email'] ?? null;

        return $this;
    }

    /**
     * Get the file path.
     */
    public function getFilePath(): ?string
    {
        return $this->filePath;
    }

    /**
     * Get the attachment URL.
     */
    public function getAttachmentUrl(): ?string
    {
        return $this->attachmentUrl;
    }

    /**
     * Get the document title.
     */
    public function getDocumentTitle(): ?string
    {
        return $this->documentTitle;
    }

    /**
     * Convert the document mail to an array for the API.
     * The attachmentUrl is passed as a parameter since it may come from upload response.
     */
    public function toArray(?string $attachmentUrl = null): array
    {
        $sender = $this->getSender();

        $data = [
            'name' => $this->name ?? '',
            'company' => $this->company ?? '',
            'address1' => $this->address1 ?? '',
            'address2' => $this->address2 ?? '',
            'city' => $this->city ?? '',
            'state' => $this->state ?? '',
            'zip' => $this->zip ?? '',
            'phone' => $this->phone ?? '',
            'email' => $this->email ?? '',
            'shippingTypeId' => $this->shippingTypeId,
            'attachmentUrl' => $attachmentUrl ?? $this->attachmentUrl,
            'attachmentTitle' => $this->documentTitle ?? 'Document',
            'fromAddress' => [
                'name' => $sender['name'] ?? '',
                'company' => $sender['company'] ?? '',
                'address1' => $sender['address_line_1'] ?? $sender['address1'] ?? '',
                'address2' => $sender['address_line_2'] ?? $sender['address2'] ?? '',
                'city' => $sender['city'] ?? '',
                'state' => $sender['state'] ?? '',
                'zip' => $sender['zip'] ?? '',
                'phone' => $sender['phone'] ?? '',
            ],
        ];

        if (!empty($this->metadata)) {
            $data['metadata'] = $this->metadata;
        }

        return $data;
    }
}
