#!/usr/bin/env php
<?php

define('SETTING_FILE', 'scripts/cookbooks.ini');

require_once('CookbookManager.php');
require_once('CookbookOpscode.php');
require_once('CookbookGit.php');

$cookbook = new CookbookManager();
$cookbook->syncGitCookbooks();
$cookbook->syncOpscodeCookbooks();