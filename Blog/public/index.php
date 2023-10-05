<?php
use Ijdb\Website;
use Website\EntryPoint;

include __DIR__ . '/../includes/autoload.php';

$uri = strtok(ltrim($_SERVER['REQUEST_URI'], '/'), '?');

$website = new Website();
$entryPoint = new EntryPoint($website);
$entryPoint->run($uri, $_SERVER['REQUEST_METHOD']);
