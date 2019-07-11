<?php

namespace Cache\Interfaces;

interface CacheItemInterface
{
    /**
     * Fetches a value from the Cache.
     *
     * @param string $key     The unique key of this item in the Cache.
     * @param mixed  $default Default value to return if the key does not exist.
     *
     * @return mixed The value of the item from the Cache, or $default in case of Cache miss.
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     *   MUST be thrown if the $key string is not a legal value.
     */
    public function get(string $key, $default = null);

    /**
     * Persists Data in the Cache, uniquely referenced by a key with an optional expiration TTL time.
     *
     * @param string                 $key   The key of the item to store.
     * @param mixed                  $value The value of the item to store. Must be serializable.
     * @param null|int|\DateInterval $ttl   Optional. The TTL value of this item. If no value is sent and
     *                                      the driver supports TTL then the library may set a default value
     *                                      for it or let the driver take care of that.
     *
     * @return bool True on success and false on failure.
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     *   MUST be thrown if the $key string is not a legal value.
     */
    public function set(string $key, $value, $ttl = null):bool;

    /**
     * Delete an item from the Cache by its unique key.
     *
     * @param string $key The unique Cache key of the item to delete.
     *
     * @return bool True if the item was successfully removed. False if there was an error.
     *
     * @throws InvalidArgumentException
     *   MUST be thrown if the $key string is not a legal value.
     */
    public function delete(string $key):bool;

    /**
     * Wipes clean the entire Cache's keys.
     *
     * @return bool True on success and false on failure.
     */
    public function clear():bool;

    /**
     * Determines whether an item is present in the Cache.
     *
     * NOTE: It is recommended that has() is only to be used for Cache warming type purposes
     * and not to be used within your live applications Operations for get/set, as this method
     * is subject to a race condition where your has() will return true and immediately after,
     * another script can remove it, making the state of your app out of date.
     *
     * @param string $key The Cache item key.
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     *   MUST be thrown if the $key string is not a legal value.
     */
    public function has(string $key):bool;
}