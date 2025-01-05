<?php
namespace App\MessageHandler;

use App\Message\YourMessage;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;


#[AsMessageHandler]
class MessageHandler
{
    public function __invoke(YourMessage $message): void
    {
        // Traitez le message ici
        echo 'Message reçu de Kafka2222 : ' . $message->getContent() . PHP_EOL;
    }
}
