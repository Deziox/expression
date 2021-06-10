<?php
$command = escapeshellcmd('/scripts/test.py');
$output = shell_exec($command);

echo $output;