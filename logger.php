<?php
use Monolog\Logger;
use Monolog\Level;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;

// create a log channel
$logger = new Logger("example");

$stream_handler = new StreamHandler(__DIR__ . "/log/debug.log", Level::Debug);
$output = "%level_name% | %datetime% > %message% | %context% %extra%\n";
$stream_handler->setFormatter(new LineFormatter($output));
$logger->pushHandler($stream_handler);