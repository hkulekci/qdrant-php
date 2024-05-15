<?php
/**
 * @since     Apr 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Domain;

use Qdrant\Exception\InvalidArgumentException;
use Webmozart\Assert\Assert as BaseAssert;

class Assert extends BaseAssert
{
    public static function keysExists(array $array, array $keys, $message = ''): void
    {
        foreach ($keys as $key) {
            if (!(isset($array[$key]) || \array_key_exists($key, $array))) {
                static::reportInvalidArgument(\sprintf(
                    $message ?: 'Expected the key %s to exist.',
                    static::valueToString($key)
                ));
            }
        }
    }

    protected static function reportInvalidArgument($message): void
    {
        throw new InvalidArgumentException($message);
    }

    public static function keysExistsAtLeastOne(array $array, array $keys, $message = ''): void
    {
        foreach ($keys as $key) {
            if (isset($array[$key]) || \array_key_exists($key, $array)) {
                return;
            }
        }
        static::reportInvalidArgument(\sprintf(
            $message ?: 'Expected at least one of the %s to exist.',
            static::valueToString(implode(', ', $keys))
        ));
    }

    public static function keysNotExists(array $array, array $keys, $message = ''): void
    {
        foreach ($keys as $key) {
            if (isset($array[$key]) || \array_key_exists($key, $array)) {
                static::reportInvalidArgument(\sprintf(
                    $message ?: 'Expected the key %s to exist.',
                    static::valueToString($key)
                ));
            }
        }
    }
}