<?php
/**
 * Example File
 *
 * PHP version 5.4.4
 *
 * @category  PEAR_Example
 * @package   PEAR_Example
 * @author    AdamGold <ronalister@gmail.com>
 * @copyright 2012 AdamGold
 * @license   http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version   1.0
 * @link      http://themeforest.net/user/AdamGold
 * @since     2012
 */

/*
* Place includes, constant defines and $_GLOBAL settings here.
* Make sure they have appropriate docblocks to avoid phpDocumentor
* construing they are documented by the page-level docblock.
*/


/**
 * Example Class
 *
 * @category  PEAR_Example
 * @package   PEAR_Example
 * @author    AdamGold <ronalister@gmail.com>
 * @copyright 2012 AdamGOld
 * @license   http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link      http://themeforest.net/user/AdamGold
 */
class Foo_Bar
{
    /**
     * a normal variable
     *
     * @var    string
     * @access private
     **/
    private $_var = 'normal';
     
     /**
     * a normal variable
     *
     * @var    boolean
     * @access protected
     **/
    protected $is_it = false;

    /**
     * does something awesome
     * 
     * @return string something awesome
     */
    public function awesome()
    {
        if (true === $this->_is_it && $this->_var == 'not normal') {
            echo 'AWESOME!';
        }
    }

    /**
     * checks user's permissions
     * 
     * @param int    $user_id user's id
     * @param string $api     user's api code
     * 
     * @return string the string of the class awesome function
     */
    protected function checkUser($user_id, $api)
    {
        $this->_is_it = true;
        $this->_var = "not normal, that is {$this->_is_it}";
        return $this->awesome();
    }
} // END class Foo_Bar
