docify
======

Parses docblocks from a specific file and builds an organized array for each docblock.

======

Usage
======

<pre>
&lt;?php
require 'lib/docify/Docify.php'; // Path to Docify.php

use docify\Docify;

$docify = new Docify;

// print array
$docify->parse('FILETOPARSE.php', true);

// get array
$parsed_array = $docify->parse('FILETOPARSE.php', false);
</pre>