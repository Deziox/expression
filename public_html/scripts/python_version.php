<?php
$command = escapeshellcmd('python3 ./test.py');
$output = shell_exec($command)." test";

echo $output;