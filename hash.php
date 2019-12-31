<?php

if (defined('STDIN')) {
  $value = $argv[1];
} else { 
  $value = $_GET['arg'];
}

echo sha1(md5($value)) . "\n";

exit();