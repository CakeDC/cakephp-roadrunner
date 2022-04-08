<?php
declare(strict_types=1);

namespace CakeDC\Roadrunner\Http;

use Cake\Http\Session as BaseSession;

/**
 * Override CakePHP Session to set isCli to false and manage sessions.
 */
class Session extends BaseSession
{
    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $this->_isCLI = false;
    }

    /**
     * Returns whether a session exists
     *
     * @return bool
     */
    protected function _hasSession(): bool
    {
        $canUseCookies = !!ini_get('session.use_cookies') || !!ini_get('session.use_only_cookies');

        return !$canUseCookies
            || isset($_COOKIE[session_name()])
            || $this->_isCLI
            || (ini_get('session.use_trans_sid') && isset($_GET[session_name()]));
    }
}
