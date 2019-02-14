<?php
namespace CakeDC\Roadrunner\Http;

/**
 * Override CakePHP Session to set isCli to false and manage sessions
 *
 * @package CakeDC\Roadrunner\Http
 */
class Session extends \Cake\Http\Session
{
    /**
     * Override the cli fixed session
     *
     * @return bool
     */
    public function start()
    {
        $this->_isCLI = false;

        return parent::start();
    }
}
