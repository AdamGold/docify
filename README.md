docify
======

Creates an array of comments from a specific file.

======

Usage
======

<pre>
&lt;?php
require 'lib/docify/Docify.php';

use docify\Docify;

$docify = new Docify;
$docify->parse('test.php');
</pre>