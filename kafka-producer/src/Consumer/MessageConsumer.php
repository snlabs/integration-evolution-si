<?php

namespace App\Consumer;

use App\Manager\MessageManager;
use RdKafka\Message;
use SimPod\Kafka\Clients\Consumer\ConsumerConfig;
use SimPod\Kafka\Clients\Consumer\KafkaConsumer;
use SimPod\KafkaBundle\Kafka\Configuration;
use SimPod\KafkaBundle\Kafka\Clients\Consumer\NamedConsumer;

final class MessageConsumer implements NamedConsumer
{
    private const TIMEOUT_MS = 2000;

    public function __construct(
        private readonly Configuration $configuration,
        private readonly string $topic,
        private readonly string $groupId,
        private readonly string $name,
        private readonly MessageManager $messageManager,
    )
    {
    }

    public function run(): void
    {
        $kafkaConsumer = new KafkaConsumer($this->getConfig());dd($kafkaConsumer->getBroker);

        $kafkaConsumer->subscribe([$this->topic]);

        while (true) {
            $kafkaConsumer->start(
                self::TIMEOUT_MS,
                function (Message $message) use ($kafkaConsumer) : void {
                    
                    $data = json_decode($message->payload, true, 512, JSON_THROW_ON_ERROR);
                    $this->messageManager->createMessage( $data/*$this->name.' '.$data['text']*/);
                    
                    echo $this->name.' processed message: '/*.$data['id']*/."\n";
                     
                    $kafkaConsumer->commit($message);
                }
            );
        }
    }


    public function getRecordCount(string $topicName): int
    {

        $kafkaConsumer = new KafkaConsumer($this->getConfig());
        $metadata = $kafkaConsumer->getMetadata(false, null, 10000); // Timeout: 10 seconds
        $topic = $metadata->getTopics()->get($topicName);

        if ($topic === null) {
            throw new \RuntimeException(sprintf('Topic "%s" not found', $topicName));
        }

        $partitions = $topic->getPartitions();
        $recordCount = 0;

        foreach ($partitions as $partition) {
            $lowOffset = $kafkaConsumer->queryWatermarkOffsets($topicName, $partition->getId(), 10000)['low'];
            $highOffset = $kafkaConsumer->queryWatermarkOffsets($topicName, $partition->getId(), 10000)['high'];

            $recordCount += ($highOffset - $lowOffset);
        }

        return $recordCount;
    }

    public function getName(): string {
        return $this->name;
    }

    private function getConfig(): ConsumerConfig
    {
        $config = new ConsumerConfig();

        $config->set(ConsumerConfig::BOOTSTRAP_SERVERS_CONFIG, $this->configuration->getBootstrapServers());
        $config->set(ConsumerConfig::ENABLE_AUTO_COMMIT_CONFIG, false);
        $config->set(ConsumerConfig::CLIENT_ID_CONFIG, $this->configuration->getClientIdWithHostname());
        $config->set(ConsumerConfig::AUTO_OFFSET_RESET_CONFIG, 'earliest');
        $config->set(ConsumerConfig::GROUP_ID_CONFIG, $this->groupId);

        return $config;
    }

}
