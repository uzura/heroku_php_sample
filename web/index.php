<?php

require('../vendor/autoload.php');


$log = new Monolog\Logger('Logger');
$log->pushHandler(new Monolog\Handler\ChromePHPHandler()); // Chrome Loggerエクステンションで見るため
$log->pushHandler(new Monolog\Handler\StreamHandler('php://stderr', Monolog\Logger::DEBUG)); // $ heroku addons:open papertrailなどで見るため

// ローカルで実行するときは.envファイルを環境変数としてロードする
// Herokuはheroku configの値を使う
$isHeroku = getenv("IS_HEROKU");
if (empty($isHeroku)) {
	$log->addDebug('.env loading');
	$dotenv = new Dotenv\Dotenv(__DIR__ . '/..');
	$dotenv->load();
	$dotenv->required('DATABASE_URL');
} else {
	$log->addDebug('.env not load');
}

$app = new \Slim\Slim();
$app->get('/hello/:name', function ($name) {
		    echo "Hello, $name";
			global $log;
			$log->addDebug('Foo');

			$db_url = parse_url(getenv('DATABASE_URL'));
			var_dump($db_url);
			$dsn = sprintf('pgsql:host=%s;dbname=%s', $db_url['host'], substr($db_url['path'], 1));
			$pdo = new PDO($dsn, $db_url['user'], $db_url['pass']);
			var_dump($pdo->getAttribute(PDO::ATTR_SERVER_VERSION));
});

$app->get('/', function () {
		phpinfo();
});

$app->run();
