<?php

require('../vendor/autoload.php');
require_once __DIR__ . '/../include/ApiException.php';


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
	$dotenv->required('CLEARDB_DATABASE_URL');
} else {
	$log->addDebug('.env not load');
}

$app = new \Slim\Slim();

$app->get('/hello/:name', function ($name) {
	echo "Hello, $name";
	phpinfo();
	global $log;
	$log->addDebug('Hello');
});

$app->get('/v1/sample', function () {
	global $log;
	try{
			try {
					$db_url = parse_url(getenv('CLEARDB_DATABASE_URL'));
					$dsn = sprintf('mysql:host=%s;dbname=%s', $db_url['host'], substr($db_url['path'], 1));
					$pdo = new PDO($dsn, $db_url['user'], $db_url['pass']);
			} catch (PDOException $e) {
					$apiException = new ApiException('Cannot access DB.', 500, 'Check DB status and CLEARDB_DATABASE_URL Config Vars', 500);
					$log->addCritical($apiException->getString());
					$log->addCritical($e->getMessage());
					throw $apiException;
			}

			$sql = 'select id,sample from samples';
			$statement = $pdo->query($sql);
			if ($statement === false) {
				$message = 'Cannot query: '.$sql;
				$apiException = new ApiException($message, 500, $message, 500);
				$log->addCritical($apiException->getString());
				throw $apiException;
			} else {
				$row = $statement->fetch(PDO::FETCH_ASSOC);
				$log->addDebug(json_encode($row));
				$result = array('result' => $row);
				print_r(json_encode($result));
			}
	} catch (ApiException $e) {
			$result = array('error' => $e->getError());
			print_r(json_encode($result));
	}
});

$app->run();
