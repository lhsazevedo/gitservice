#!/usr/bin/env php
<?php

$user = $argv[1];

// TODO: Validade SSH_ORIGINAL_COMMAND
[$command, $repo] = explode(' ', getenv('SSH_ORIGINAL_COMMAND'), 2);

// Remove quotes
$repo = trim($repo, "'");

// Remote leading dash
$repo = ltrim($repo, "/");

// TODO: Validade $command and $repo

// TODO: Authorize

chdir('/app/storage/app/repos');

// TODO: Unsafe? See https://www.php.net/manual/en/function.passthru.php#refsect1-function.passthru-notes
file_put_contents("/app/storage/logs/gitssh.log", "$command '$repo'\n", FILE_APPEND);

passthru("$command '$repo'");
