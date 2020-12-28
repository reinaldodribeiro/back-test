<?php

namespace App\Traits;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

trait RabbitMQ
{
    use GenerateCSV;

    private $host = 'localhost';

    private $port = 5672;

    private $user = 'guest';

    private $password = 'guest';

    protected function connection()
    {
        return new AMQPStreamConnection($this->host, $this->port, $this->user, $this->password);
    }

    protected function channel()
    {
        return $this->connection()->channel();
    }

    protected function queueDeclare()
    {
        return $this->channel()->queue_declare('workflow', false, true, false, false);
    }

    public function sendMessageQueue($model)
    {
        $this->queueDeclare();
        $channel = $this->channel();
        $rabbitMsg = new AMQPMessage(strval($model->data).';['.implode(",", $model->steps)."]");
        $channel->basic_publish($rabbitMsg, '', 'workflow');
    }

    public function consume()
    {
        $channel = $this->channel();

        $callback = function ($message) {
            echo ' [x] Received ', $message->body, "\n";
            $this->generateFile($message->body);
            //sleep(substr_count($message->body, '.'));
            echo " [x] Done\n";
        };

        $channel->basic_consume(
            'workflow',
            '',
            false,
            true,
            false,
            false,
            $callback
        );

        while ($channel->is_consuming()) {
            $channel->wait();
        }

        $channel->close();
        $this->connection()->close();
        return true;
    }


}
