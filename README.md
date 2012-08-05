docify
======

Creates an array of comments from a specific file.

======

Usage
======

<code>
&lt;?php
require 'lib/docify/Docify.php';

use docify\Docify;

$docify = new Docify;
$docify->parse('test.php');
</code>