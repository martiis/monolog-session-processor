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
     * @var string
     */
    private $prefix;

    /**
     * SessionRequestProcessor constructor.
     *
     * @param SessionInterface $session
     * @param string           $prefix
     */
    public function __construct(SessionInterface $session, $prefix = '')
    {
        $this->session = $session;
        $this->prefix = $prefix;
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
            $this->token = $this->hash($this->token);
        }

        $record['extra']['token'] = $this->token;

        return $record;
    }

    /**
     * Hashes session session id.
     *
     * @param string $string
     *
     * @return string
     */
    private function hash($string)
    {
        return md5($this->prefix . sha1($string));
    }
}
