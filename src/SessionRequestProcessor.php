<?php

namespace Martiis\Monolog\Processor;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Class SessionRequestProcessor
 */
class SessionRequestProcessor
{
    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var string
     */
    private $token;

    /**
     * SessionRequestProcessor constructor.
     *
     * @param SessionInterface $session
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * Adds session id to log record.
     *
     * @param array $record
     *
     * @return array
     */
    public function processRecord(array $record)
    {
        if (null === $this->token) {
            try {
                $this->token = substr($this->session->getId(), 0, 8);
            } catch (\RuntimeException $e) {
                $this->token = '????????';
            }
            $this->token .= '-' . substr(uniqid(), -8);
        }
        $record['extra']['token'] = $this->token;

        return $record;
    }
}
