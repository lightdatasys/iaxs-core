<?php

chdir(dirname(__DIR__));

$autoloader = include __DIR__ . '/../init_autoloader.php';
$autoloader->add('IaxsCore\\', __DIR__ . '/../src');