<?php
namespace SlaxWeb\Session\Storage\PhpStorage;

/**
 * Default PHP storage handling class.
 * Enables retrieving data from the PHP session storage, writing data to it, etc.
 * Uses the \Session\Storage\iStorage interface
 * Copyright (c) 2013 Tomaz Lovrec (tomaz.lovrec@gmail.com)
 *
 * @author Tomaz Lovrec <tomaz.lovrec@gmail.com>
 */
class PhpStorage implements \SlaxWeb\Session\Storage\iStorage
{
    protected $_variables = array ();

    /**
     * Default class constructor
     */
    public function __construct()
    {
        // copy whole session to a local property
        $this->_getVariables();
    }

    /**
     * Get the session variable.
     *
     * @param $name string Name of the session variable
     * @return mixed Returns the value of a session variable, or false if it was not found
     *
     * TODO:
     * - serialize if serializable
     */
    public function getVariable($name)
    {
        return isset($this->_variables[$name]) ? $this->_variables[$name] : false;
    }

    /**
     * Get all session variables.
     *
     * @return array Returns all session variables as an array
     */
    public function getAllVariables()
    {
        return $this->_variables;
    }

    /**
     * Set session variable
     *
     * @param $name string Name of the session variable
     * @param $value mixed Value of the session variable
     *
     * TODO:
     * - unserialize if it's srialized
     */
    public function setVariable($name, $value)
    {
        // set the local property
        $this->_variables[$name] = $value;
        // also set the superglobal for persistance across refreshes
        $_SESSION[$name] = $value;
    }

    /**
     * Set multiple session variables
     *
     * @param $variables array Array of the session variables that need to be set
     * @param bool Returns false if par
     */
    public function setVariables(array $variables)
    {
        foreach ($variables as $name => $value) {
            $this->setVariable($name, $value);
        }
    }

    /**
     * Remove session variable
     *
     * @param $name string Name of the session variable
     */
    public function removeVariable($name)
    {
        // remove from property
        unset($this->_variables[$name]);
        // also remove from superglobal
        unset($_SESSION[$name]);
    }

    /**
     * Remove all session variables
     */
    public function removeAllVariables()
    {
        // remove from property
        $this->_variables = array ();
        // also remove from superglobal
        $_SESSION = array ();
    }

    /**
     * Destroys the session and removes all variables
     */
    public function destroySession()
    {
        // remove the session variables from the local property
        $this->_variables = array ();
        session_destroy();
    }

    /**
     * Refill session data
     *
     * After session ID regeneration, the data is cleared out, it needs to be re-set
     */
    public function refillSession()
    {
        $_SESSION = $this->_variables;
    }

    /**
     * Copies all session variables to $this->_variables
     */
    protected function _getVariables()
    {
        $this->_variables = $_SESSION;
    }
}

/**
 * End of file ./SlaxWeb/Session/PhpStorage/PhpStorage.php
 */
