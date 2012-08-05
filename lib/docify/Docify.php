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
        $comments = $this->match($source);
        $parse = $this->parse_block($comments);
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
                $comment = '';
            }
        }

        return $comments;
    }

    /**
     * Parse each line in the docblock
     */
    protected function parse_block($comments) {
        $comment_lines = array();
        $docblocks = array();
        foreach ($comments as $key => $comment) {
            // Strip the opening and closing tags of the docblock
            $comment = substr($comment, 3, -2);

            // Split into arrays of lines
            $comment = preg_split('/\r?\n\r?/', $comment);

            // Trim asterisks and whitespace from the beginning and whitespace from the end of lines
            $comment = array_map(function($line) {
              return ltrim(rtrim($line), "* \t\n\r\0\x0B");
            }, $comment);

            $comment_lines[ $key ] = array_filter($comment);
            foreach ($comment_lines[ $key ] as $tag => $value) {
                $info = preg_replace('/^(\*\s+?)/', '', $value);
                // Get comment params
                if ($info[0] === "@") {
                    // Get the param name
                    preg_match('/@(\w+)/', $info, $matches);
                    $tag_type = $matches[1];
                    $tag_value = str_replace("@$tag_type ", '', $info);
                    $docblocks[ $key ]['tags'][] = array( 'type' => $tag_type, 'value' => trim($tag_value) );
                }
                $docblocks[ $key ]['summary'] = $comment_lines[ $key ][1];
            }
        }

        return $docblocks;
    }
} // END class Docify