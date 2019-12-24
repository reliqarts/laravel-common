<?php

declare(strict_types=1);

namespace ReliqArts\Helper;

final class UI
{
    public function getMessageList(string ...$messages): string
    {
        return sprintf(
            '<ul>%s</ul>',
            implode(
                '',
                array_map(
                    fn (string $message) => sprintf(
                        '<li>%s</li>',
                        $message
                    ),
                    $messages
                )
            )
        );
    }
}
