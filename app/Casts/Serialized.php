<?php

namespace App\Casts;

class Serialized
{
    /**
     * Cast the given value.
     *
     * @param $model
     * @param string $key
     * @param $value
     * @param array $attributes
     * @return mixed
     */
    public function get($model, string $key, $value, array $attributes): mixed
    {
        if (is_null($value)) {
            return null;
        }

        return unserialize($value, ['allowed_classes' => true]);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param $model
     * @param string $key
     * @param $value
     * @param array $attributes
     * @return mixed
     */
    public function set($model, string $key, $value, array $attributes): mixed
    {
        if (is_null($value)) {
            return null;
        }

        return serialize($value);
    }
}
