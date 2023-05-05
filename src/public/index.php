<?php
use Phalcon\Di\FactoryDefault;
use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Application;
use Phalcon\Url;
use Phalcon\Db\Adapter\Pdo\Mysql;
use Phalcon\Config;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Session\Manager;
use Phalcon\Session\Adapter\Stream as StreamSession;
use Phalcon\Escaper;
use Phalcon\Http\Response\Cookies;
use Phalcon\Logger;
use Phalcon\Logger\Adapter\Stream as StreamLogger;
use MyApp\Handlers\Listener;
use Phalcon\Events\Manager as EventsManager;

$config = new Config([]);

// Define some absolute path constants to aid in locating resources
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

// Register an autoloader
$loader = new Loader();

$loader->registerDirs(
    [
        APP_PATH . "/controllers/",
        APP_PATH . "/models/",
    ]
);
$loader->registerNamespaces(
    [
        'MyApp\Handlers' => APP_PATH . '/handlers/',
        'MyApp\Controllers' => APP_PATH . '/controllers/',
        'MyApp\Models' => APP_PATH . '/models/',
        'Tests' => APP_PATH . '/../tests/',
    ]
);

$loader->register();

$container = new FactoryDefault();
// $container->set('locale', (new Locale())->getTranslator());
// $container->set(
//     'view',
//     function () {
//         $view = new View();
//         $view->setViewsDir(APP_PATH . '/views/');
//         return $view;
//     }
// );
$container->set(
    'view',
    function () {
        $view = new View();
        $view->setViewsDir(APP_PATH . '/views/');
        return $view;
    }
);

$container->set(
    'url',
    function () {
        $url = new Url();
        $url->setBaseUri('/');
        return $url;
    }
);

$container->set(
    'config',
    function () {
        $fileName = APP_PATH . '/assets/config.php';
        $factory  = new ConfigFactory();
        $config = $factory->newInstance('php', $fileName);
        return $config;
    }
  
);
$container->set(
    'db',
    function () {
     return new Mysql($this["config"]->db->toArrray());
    }
);
$container->set(
    'db',
    function () {
        return new Mysql(
            [
                'host'     => 'mysql-server',
                'username' => 'root',
                'password' => 'secret',
                'dbname'   => 'testPhlacon',
                ]
            );
        }
);

$container->set(
    'mongo',
    function () {
        $mongo = new MongoClient();

        return $mongo->selectDB('phalt');
    },
    true
);

$container->set(
    'dispatcher',
    function () {
        $dispatcher = new Dispatcher();

        $dispatcher->setDefaultNamespace(
            'MyApp\Controllers'
        );

        return $dispatcher;
    }
);

$container->set(
    'logger',
    function () {
        $adapter = new StreamLogger(APP_PATH.'/storage/logs/main.log');
        $logger  = new Logger(
            'messages',
            [
                'main' => $adapter,
            ]
        );

        return $logger;
    }
);

$container->set(
    'session',
    function () {
        $session = new Manager();
        $files = new StreamSession(
            [
                'savePath' => '/tmp',
            ]
        );
        $session
            ->setAdapter($files)
            ->start();
        return $session;
    }
);

$container->set(
    'escaper',
    function () {
        return new Escaper();
    }
);

$container->set(
    'cookies',
    function () {
        $cookies = new Cookies();
        $cookies->useEncryption(false);
        return $cookies;
    }
);
$application = new Application($container);

$eventsManager=new EventsManager();
$eventsManager->attach(
    'application:beforeHandleRequest',
    new Listener()
);
$container->set(
    'EventsManager',
    $eventsManager
);
$application->setEventsManager($eventsManager);

try {
    // Handle the request
    $response = $application->handle(
        $_SERVER["REQUEST_URI"]
    );

    $response->send();
} catch (\Exception $e) {
    echo 'Exception: ', $e->getMessage();
}
