<?php
/**
 * Example File
 *
 * PHP version 5.4.4
 *
 * @category  Codify_Example
 * @package   Codify_Example
 * @author    AdamGold <adamgold7@gmail.com>
 * @copyright 2012 AdamGold
 * @version   1.0
 * @since     2012
 */

/**
 * Example Class
 *
 * Example:
 *
 * $foo = Foo:singleton;
 *
 * @category  Codify_Example
 * @package   Codify_Example
 * @author    AdamGold <adamgold7@gmail.com>
 * @copyright 2012 AdamGold
 */
class Foo
{
    /**
     * singleton variable
     *
     * @var    boolean
     * @access protected
     **/
    protected static $singleton;

    /**
     * does something awesome
     * 
     * @return string something awesome
     */
    public static function singleton()
    {
        return null == self::singleton
        ? self::$singleton = new static
        : self::singleton;
    }
} // END class Foo
