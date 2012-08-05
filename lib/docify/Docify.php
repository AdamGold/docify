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
     * parse file's comments
     *
     * @param  string $file file name
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

    /**
     * get all docblocks from file
     * 
     * @param  string $source file contents
     * 
     * @return array array of docblocks
     */
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
     * parse all docblocks and return organized array
     * 
     * @param  array $comments array of docblocks
     * 
     * @return array $docblocks organized array
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
                $docblocks[ $key ]['summary'] = $comment_lines[ $key ][1];

                $info = preg_replace('/^(\*\s+?)/', '', $value);
                // Get comment params
                if ($info[0] === "@") {
                    // Get the param name
                    preg_match('/@(\w+)/', $info, $matches);
                    $tag_type = $matches[1];
                    $tag_value = str_replace("@$tag_type ", '', $info);
                    $docblocks[ $key ]['tags'][] = array( 'type' => $tag_type, 'value' => trim($tag_value) );
                }

            }
        }

        return $docblocks;
    }

    /**
     * github markdown
     * 
     * @param string $text text to parse
     * 
     * @return string parsed text
     */
    protected function gfm($text)
    {
        # Extract pre blocks
        $extractions = array();

        $text = preg_replace_callback('/<pre>.*?<\/pre>/s', function($matches) use (&$extractions){
            $match = $matches[0];
            $md5 = md5($match);
            $extractions[$md5] = $match;
            return "{gfm-extraction-${md5}}";
        }, $text);

        # prevent foo_bar_baz from ending up with an italic word in the middle
        $text = preg_replace_callback('/(^(?! {4}|\t)\w+_\w+_\w[\w_]*)/s', function($matches){
            $x = $matches[0];
            $x_parts = str_split($x);
            sort($x_parts);
            if( substr(implode('', $x_parts), 0, 2) == '__' ){
                return str_replace('_', '\_', $x);
            }
        }, $text);

        # in very clear cases, let newlines become <br /> tags
        $text = preg_replace_callback('/^[\w\<][^\n]*\n+/m', function($matches){
            $x = $matches[0];
            if( !preg_match('/\n{2}/', $x) ){
                $x = trim($x);
                $x .= "  \n";
            }
            return $x;
        }, $text);

        # Insert pre block extractions
        $text = preg_replace_callback('/\{gfm-extraction-([0-9a-f]{32})\}/', function($matches) use (&$extractions){
            $match = $matches[1];
            return "\n\n" . $extractions[$match];
        }, $text);

        return $text;
    }
} // END class Docify