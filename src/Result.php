<?php

/** @noinspection PhpUnused */

declare(strict_types=1);

namespace ReliqArts;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use JsonException;
use JsonSerializable;

class Result implements Arrayable, Jsonable, JsonSerializable
{
    private const KEY_SUCCESS = 'success';

    private const KEY_ERROR = 'error';

    private const KEY_MESSAGES = 'messages';

    private const KEY_EXTRA = 'extra';

    /**
     * Result constructor.
     *
     * @param  string[]  $messages
     */
    public function __construct(
        private bool $success = true,
        private string $error = '',
        private array $messages = [],
        private mixed $extra = null
    ) {
    }

    public function isSuccess(): bool
    {
        return $this->success;
    }

    public function setSuccess(bool $success): static
    {
        $clone = clone $this;
        $clone->success = $success;

        return $clone;
    }

    public function getError(): string
    {
        return $this->error;
    }

    public function setError(string $error): static
    {
        $clone = clone $this;
        $clone->error = $error;
        $clone->success = false;

        return $clone;
    }

    public function getExtra(): mixed
    {
        return $this->extra;
    }

    public function setExtra(mixed $extra): static
    {
        $clone = clone $this;
        $clone->extra = $extra;

        return $clone;
    }

    public function getMessage(): string
    {
        return empty($this->messages) ? '' : current($this->messages);
    }

    public function setMessage(string $message): static
    {
        return $this->addMessage($message);
    }

    /**
     * @return string[]
     */
    public function getMessages(): array
    {
        return $this->messages;
    }

    public function setMessages(string ...$messages): static
    {
        $clone = clone $this;
        $clone->messages = $messages;

        return $clone;
    }

    public function addMessage(string $message): static
    {
        $clone = clone $this;
        $clone->messages[] = $message;

        return $clone;
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * {@inheritdoc}
     */
    public function toArray(): array
    {
        return [
            self::KEY_SUCCESS => $this->success,
            self::KEY_ERROR => $this->error,
            self::KEY_MESSAGES => $this->messages,
            self::KEY_EXTRA => $this->extra,
        ];
    }

    /**
     * {@inheritdoc}
     *
     * @param  int  $options
     *
     * @throws JsonException
     */
    public function toJson($options = 0): string
    {
        return json_encode($this->jsonSerialize(), JSON_THROW_ON_ERROR | $options);
    }
}
