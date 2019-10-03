<?php

declare(strict_types=1);

namespace ReliqArts;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use JsonSerializable;

class Result implements Arrayable, Jsonable, JsonSerializable
{
    private const KEY_SUCCESS = 'success';
    private const KEY_ERROR = 'error';
    private const KEY_MESSAGES = 'messages';
    private const KEY_DATA = 'data';

    /**
     * @var bool
     */
    private $success;

    /**
     * @var string
     */
    private $error;

    /**
     * @var string[]
     */
    private $messages;

    /**
     * @var null|mixed
     */
    private $data;

    /**
     * Result constructor.
     *
     * @param bool     $success
     * @param string   $error
     * @param string[] $messages
     * @param mixed    $data
     */
    public function __construct(
        bool $success = true,
        string $error = '',
        array $messages = [],
        $data = null
    ) {
        $this->success = $success;
        $this->error = $error;
        $this->messages = $messages;
        $this->data = $data;
    }

    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->success;
    }

    /**
     * @param bool $success
     *
     * @return self
     */
    public function setSuccess(bool $success): self
    {
        $clone = clone $this;
        $clone->success = $success;

        return $clone;
    }

    /**
     * @return string
     */
    public function getError(): string
    {
        return $this->error;
    }

    /**
     * @param string $error
     *
     * @return self
     */
    public function setError(string $error): self
    {
        $clone = clone $this;
        $clone->error = $error;
        $clone->success = false;

        return $clone;
    }

    /**
     * @return null|mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     *
     * @return self
     */
    public function setData($data): self
    {
        $clone = clone $this;
        $clone->data = $data;

        return $clone;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return empty($this->messages) ? '' : current($this->messages);
    }

    /**
     * @param string $message
     *
     * @return self
     */
    public function setMessage(string $message): self
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

    /**
     * @param string ...$messages
     *
     * @return self
     */
    public function setMessages(string ...$messages): self
    {
        $clone = clone $this;
        $clone->messages = $messages;

        return $clone;
    }

    /**
     * @param string $message
     *
     * @return self
     */
    public function addMessage(string $message): self
    {
        $clone = clone $this;
        $clone->messages[] = $message;

        return $clone;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            self::KEY_SUCCESS => $this->success,
            self::KEY_ERROR => $this->error,
            self::KEY_MESSAGES => $this->messages,
            self::KEY_DATA => $this->data,
        ];
    }

    /**
     * {@inheritdoc}
     *
     * @param int $options
     *
     * @return string
     */
    public function toJson($options = 0): string
    {
        return json_encode($this->jsonSerialize(), $options);
    }
}
