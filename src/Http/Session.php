<?php

namespace CakeDC\Roadrunner\Http;

use Cake\Http\Session as BaseSession;

/**
 * Override CakePHP Session to set isCli to false and manage sessions.
 */
class Session extends BaseSession
{
    /**
     * Override the cli fixed session
     *
     * @return bool
     */
    public function start(): bool
    {
        $this->_isCLI = false;

        return parent::start();
    }
}
