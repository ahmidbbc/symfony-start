<?php


namespace App\Service;


use Psr\Log\LoggerInterface;

class GreetingService
{

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var string
     */
    private $from;

    /**
     * GreetingService constructor.
     * @param LoggerInterface $logger
     * @param string $from
     */
    public function __construct(LoggerInterface $logger, string $from)
    {
        $this->logger = $logger;
        $this->from = $from;
    }


    public function greet($name){
        $this->logger->info("$name greeted by {$this->from}");
        return "Hello $name from {$this->from}";
    }

}