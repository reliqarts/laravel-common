<?php

/** @noinspection PhpUndefinedMethodInspection */

declare(strict_types=1);

namespace ReliqArts\Eloquent\Concern;

use Illuminate\Support\Str;

trait HasUUID
{
    public function getIncrementing(): bool
    {
        return false;
    }

    public function getKeyType(): string
    {
        return 'string';
    }

    protected static function bootHasUUID(): void
    {
        static::creating(static function ($model) {
            if (!$model->getKey()) {
                $model->{$model->getKeyName()} = (string)Str::uuid();
            }
        });
    }
}
