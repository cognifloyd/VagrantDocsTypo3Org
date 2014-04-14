#!/usr/bin/env php
<?php

define('SETTING_FILE', 'scripts/settings.ini');

require_once('CookbookOpscode.php');
require_once('CookbookGit.php');
require_once('Bootstrap.php');

$bootstrap = new Bootstrap();
$bootstrap->syncOpscodeCookbooks();
$bootstrap->syncGitCookbooks();
