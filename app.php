<?php
session_cache_limiter(false);
session_start();
include __DIR__ . '/vendor/autoload.php';
$config = require __DIR__ . '/config.php';
$app = new \Slim\Slim($config);

$app->container->singleton('mailManager', function ($c) {
    include 'sendmail.php';
    return new \SendMail($c);
});

$app->container->singleton('db', function ($c) use ($app) {
    return go\DB\DB::create($c['settings']['db'], 'mysql');
});



$app->map('/', function () use ($app) {
    $request = $app->request;
    if ($request->isPost()) {
        if ($app->mailManager->addEmail($request->post('email'))) {
            $app->flashNow('success', 'Адрес ' . $request->post('email') .' успешно добавлен в базу.');
            // $app->flashNow('info', 'Your credit card is expired ' . uniqid());
        } else {
            $app->flashNow('fail', $app->mailManager->error);
        }
    }
    $app->render('base/slim2.html.twig', [
        'app' => $app,
        'emails' => $app->mailManager->getListEmails(),
        'assetUri' => '',
    ]);
})->via('GET', 'POST');
$view = $app->view();
$app->setName($config['projectName']);
$app->run();
