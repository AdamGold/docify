<?php
/**
 * Parse DocBlocks to array
 *
 * PHP version >= 5.4
 *
 * @package   Docify
 * @author    AdamGold <adamgold7@gmail.com>
 * @copyright 2012 AdamGold
 * @version   1.0
 */

namespace docify;

/**
 * Docify Class
 *
 * @category  [ Category ]
 * @author    AdamGold <adamgold7@gmail.com>
 * @copyright 2012 AdamGold
 * @version   1.0
 */
class Docify
{
    protected $file_contents = '';

    /**
     * Parse file's comments
     * 
     * @param  boolean $print print or return
     * 
     * @return array return array of comments if $print=1
     */
    public function parse($file, $print = true)
    {
        $absolute_path = dirname(dirname(__DIR__));
        $source = file_get_contents($absolute_path . '/' . $file);
        $parse = $this->match($source);
        if ( ! $print ) {
            return $parse;
        }
        echo "<pre>";
        print_r($parse);
    }

    protected function match($source)
    {
        $comments = array();
        $comment = '';
        $inside = false;
        $tokens = mb_strlen($source);
        for ($i = 0; $i < $tokens; $i++) {
            $char = $source[ $i ];
            // we don't want to check spaces..
            if ($char == ' ') {
                continue;
            }

            // We have a start of a comment!
            if (($char == '/') && ($source[ $i+1 ] == '*') && ($source[ $i+2 ] == '*')) {
                $inside = true;
            }

            // If we're in a comment..
            if (true === $inside) {
                $comment .= $char;
            }

            // We have an end of a comment..
            if (($char == '*') && ($source[ $i+1 ] == '/')) {
                $inside = false;
                $comments[] = $comment;
            }
        }

        return $comments;
    }
} // END class Docify