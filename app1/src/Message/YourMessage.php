<?php
namespace App\Message;

class YourMessage
{
    public function __construct(
        private string $content
    ) {}

    public function getContent(): string
    {
        return $this->content;
    }
    
}
