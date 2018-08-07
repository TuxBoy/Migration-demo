<?php
require dirname(__DIR__) . '/vendor/autoload.php';

define('PUBLIC_PATH', __DIR__ . DIRECTORY_SEPARATOR);
define('PROJECT_ROOT', dirname(__DIR__) . DIRECTORY_SEPARATOR);

$settings = require PUBLIC_PATH . 'settings.php';

$app = new \Slim\App($settings);

// Get container
$container = $app->getContainer();

// Register component on container
$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig(PROJECT_ROOT . 'templates', [
        'cache' => false
    ]);

    // Instantiate and add Slim specific extension
    $basePath = rtrim(str_ireplace('index.php', '', $container->get('request')->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($container->get('router'), $basePath));

    return $view;
};

$config = [
    'database' => [
        'dbname'   => 'sdam_demo',
        'user'     => 'root',
        'password' => 'root',
        'host'     => 'localhost',
        'driver'   => 'pdo_mysql',
    ],
	\SDAM\Config::ENV_FILE => dirname(__DIR__)
];

$container['db'] = function () use ($config) {
    return \Doctrine\DBAL\DriverManager::getConnection($config['database']);
};

$app->add(new \Zeuxisoo\Whoops\Provider\Slim\WhoopsMiddleware($app));
$app->add(new \SDAM\Middleware\MaintainerMiddleware([\App\Entity\Post::class, \App\Entity\Category::class], $config));

$app->get('/posts', \App\Controller\PostController::class . ':index')->setName('posts.index');
$app->post('/posts', \App\Controller\PostController::class . ':addPost');
$app->get('/post/{slug}', \App\Controller\PostController::class . ':view')->setName('posts.view');

// Run app
$app->run();