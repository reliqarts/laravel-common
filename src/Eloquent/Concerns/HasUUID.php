<?php

/** @noinspection PhpUndefinedMethodInspection */

declare(strict_types=1);

namespace ReliqArts\Eloquent\Concerns;

use Illuminate\Support\Str;

trait HasUUID
{
    public function getIncrementing()
    {
        return false;
    }

    public function getKeyType()
    {
        return 'string';
    }

    protected static function bootHasUUID()
    {
        static::creating(function ($model) {
            if (!$model->getKey()) {
                $model->{$model->getKeyName()} = (string)Str::uuid();
            }
        });
    }
}
