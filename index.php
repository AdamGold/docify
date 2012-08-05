<?php
//require 'vendors/autoload.php';
require 'lib/docify/Docify.php';

use docify\Docify;

$docify = new Docify;
$docify->parse('test.php');