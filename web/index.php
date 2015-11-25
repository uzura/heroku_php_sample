<?php

require('../vendor/autoload.php');

$app = new \Slim\Slim();
$app->get('/hello/:name', function ($name) {
		    echo "Hello, $name";
			$log = new Monolog\Logger('Logger');
			$log->pushHandler(new Monolog\Handler\ChromePHPHandler()); // Chrome Loggerエクステンションで見るため
			$log->pushHandler(new Monolog\Handler\StreamHandler('php://stderr', Monolog\Logger::DEBUG)); // $ heroku addons:open papertrailなどで見るため
			$log->addDebug('Foo');
});
$app->run();
