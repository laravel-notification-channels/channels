<?php

namespace Zilmoney\OnlineCheckWriter\Message;

class OnlineCheckWriterMailCheck extends OnlineCheckWriterMessage
{
    protected ?string $bankAccountId = null;
    protected string $accountType = 'bankaccount';
    protected float $amount = 0;
    protected ?string $memo = null;
    protected ?string $note = null;
    protected ?string $issueDate = null;
    protected ?string $recipientName = null;
    protected ?string $recipientCompany = null;
    protected ?string $recipientAddress1 = null;
    protected ?string $recipientAddress2 = null;
    protected ?string $recipientCity = null;
    protected ?string $recipientState = null;
    protected ?string $recipientZip = null;
    protected ?string $recipientPhone = null;
    protected ?string $recipientEmail = null;
    protected int $shippingTypeId = 1;

    /**
     * Create a new mail check message instance.
     */
    public static function create(): static
    {
        return new static();
    }

    /**
     * Set the bank account ID.
     */
    public function bankAccount(string $bankAccountId): static
    {
        $this->bankAccountId = $bankAccountId;
        return $this;
    }

    /**
     * Set the account type (default: bankaccount).
     */
    public function accountType(string $accountType): static
    {
        $this->accountType = $accountType;
        return $this;
    }

    /**
     * Set the check amount.
     */
    public function amount(float $amount): static
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * Set the memo line (appears on check).
     */
    public function memo(string $memo): static
    {
        $this->memo = $memo;
        return $this;
    }

    /**
     * Set internal note (not printed on check).
     */
    public function note(string $note): static
    {
        $this->note = $note;
        return $this;
    }

    /**
     * Set the issue date (YYYY-MM-DD format).
     */
    public function issueDate(string $date): static
    {
        $this->issueDate = $date;
        return $this;
    }

    /**
     * Set the recipient name.
     */
    public function name(string $name): static
    {
        $this->recipientName = $name;
        return $this;
    }

    /**
     * Set the recipient company.
     */
    public function company(string $company): static
    {
        $this->recipientCompany = $company;
        return $this;
    }

    /**
     * Set the recipient address line 1.
     */
    public function address1(string $address1): static
    {
        $this->recipientAddress1 = $address1;
        return $this;
    }

    /**
     * Set the recipient address line 2.
     */
    public function address2(string $address2): static
    {
        $this->recipientAddress2 = $address2;
        return $this;
    }

    /**
     * Set the recipient city.
     */
    public function city(string $city): static
    {
        $this->recipientCity = $city;
        return $this;
    }

    /**
     * Set the recipient state.
     */
    public function state(string $state): static
    {
        $this->recipientState = $state;
        return $this;
    }

    /**
     * Set the recipient zip code.
     */
    public function zip(string $zip): static
    {
        $this->recipientZip = $zip;
        return $this;
    }

    /**
     * Set the recipient phone.
     */
    public function phone(string $phone): static
    {
        $this->recipientPhone = $phone;
        return $this;
    }

    /**
     * Set the recipient email.
     */
    public function email(string $email): static
    {
        $this->recipientEmail = $email;
        return $this;
    }

    /**
     * Set shipping type ID.
     * 1 = Standard, 2 = Express, 3 = Priority
     */
    public function shippingType(int $shippingTypeId): static
    {
        $this->shippingTypeId = $shippingTypeId;
        return $this;
    }

    /**
     * Set destination from array (alternative to individual setters).
     */
    public function to(array $address): static
    {
        $this->recipientName = $address['name'] ?? $this->recipientName;
        $this->recipientCompany = $address['company'] ?? $this->recipientCompany;
        $this->recipientAddress1 = $address['address1'] ?? $address['address_line_1'] ?? $this->recipientAddress1;
        $this->recipientAddress2 = $address['address2'] ?? $address['address_line_2'] ?? $this->recipientAddress2;
        $this->recipientCity = $address['city'] ?? $this->recipientCity;
        $this->recipientState = $address['state'] ?? $this->recipientState;
        $this->recipientZip = $address['zip'] ?? $address['postal_code'] ?? $this->recipientZip;
        $this->recipientPhone = $address['phone'] ?? $this->recipientPhone;
        $this->recipientEmail = $address['email'] ?? $this->recipientEmail;

        return $this;
    }

    /**
     * Convert the mail check to an array for the API.
     */
    public function toArray(): array
    {
        $data = [
            'source' => [
                'accountType' => $this->accountType,
                'accountId' => $this->bankAccountId ?? config('onlinecheckwriter.default_bank_account_id'),
            ],
            'destination' => array_filter([
                'name' => $this->recipientName,
                'company' => $this->recipientCompany,
                'address1' => $this->recipientAddress1,
                'address2' => $this->recipientAddress2 ?? '',
                'city' => $this->recipientCity,
                'state' => $this->recipientState,
                'zip' => $this->recipientZip,
                'phone' => $this->recipientPhone,
                'email' => $this->recipientEmail,
                'shippingTypeId' => $this->shippingTypeId,
            ], fn($value) => $value !== null),
            'payment_details' => array_filter([
                'amount' => $this->amount,
                'memo' => $this->memo,
                'note' => $this->note,
                'issueDate' => $this->issueDate,
            ], fn($value) => $value !== null),
        ];

        // Ensure shippingTypeId is always set
        $data['destination']['shippingTypeId'] = $this->shippingTypeId;

        return $data;
    }
}
