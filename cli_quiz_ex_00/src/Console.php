#!/bin/usr/env php
<?php

require __DIR__ . "/../vendor/autoload.php";

use Symfony\Component\Console\Application;
use Console\QuizPathCommand;

$application = new Application("Quizzer","0.0.1");
$application->add(new QuizPathCommand());
$application->run();
?>