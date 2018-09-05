<?php

namespace NotificationChannels\ExpoPushNotifications\Repositories;

use ExponentPhpSDK\ExpoRepository;
use NotificationChannels\ExpoPushNotifications\Models\Interest;

class ExpoDatabaseDriver implements ExpoRepository
{
    /**
     * Stores an Expo token with a given identifier.
     *
     * @param $key
     * @param $value
     *
     * @return bool
     */
    public function store($key, $value): bool
    {
        $interest = Interest::firstOrCreate([
            'key' => $key,
            'value' => $value,
        ]);

        return $interest instanceof Interest;
    }

    /**
     * Retrieves an Expo token with a given identifier.
     *
     * @param string $key
     *
     * @return array
     */
    public function retrieve(string $key)
    {
        return Interest::where('key', $key)->pluck('value')->toArray();
    }

    /**
     * Removes an Expo token with a given identifier.
     *
     * @param string $key
     * @param string $value
     *
     * @return bool
     */
    public function forget(string $key, string $value = null): bool
    {
        // Delete interest
        $delete = Interest::where('key', $key);

        if (isset($value)) {
            // Only delete this token
            $delete->where('value', $value);
        }

        $delete->delete();

        // Check if our interest exist
        $count = Interest::where('key', $key);

        if (isset($value)) {
            $count->where('value', $value);
        }

        return $count->count() === 0;
    }
}
