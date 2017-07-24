<?php
/**
 * Setup routes with a single request method:
 *
 * $app->get('/', App\Action\HomePageAction::class, 'home');
 * $app->post('/album', App\Action\AlbumCreateAction::class, 'album.create');
 * $app->put('/album/:id', App\Action\AlbumUpdateAction::class, 'album.put');
 * $app->patch('/album/:id', App\Action\AlbumUpdateAction::class, 'album.patch');
 * $app->delete('/album/:id', App\Action\AlbumDeleteAction::class, 'album.delete');
 *
 * Or with multiple request methods:
 *
 * $app->route('/contact', App\Action\ContactAction::class, ['GET', 'POST', ...], 'contact');
 *
 * Or handling all request methods:
 *
 * $app->route('/contact', App\Action\ContactAction::class)->setName('contact');
 *
 * or:
 *
 * $app->route(
 *     '/contact',
 *     App\Action\ContactAction::class,
 *     Zend\Expressive\Router\Route::HTTP_METHOD_ANY,
 *     'contact'
 * );
 */

//$app->get('/', App\Action\HomePageAction::class, 'home');

$app->get('/chocolates', \App\Action\ChocolatesAction::class, 'chocolates');

$app->get(
    '/chocolate/{id:[0-9a-f]{8}\-[0-9a-f]{4}\-[0-9a-f]{4}\-[0-9a-f]{4}\-[0-9a-f]{12}}',
    \App\Action\ChocolateDetailsAction::class,
    'chocolate-details'
);

//$app->get('/login/form', \App\Action\ViewLoginAction::class, 'login-view');

$app->post('/login', \App\Action\LoginAction::class, 'login');

$app->get('/users', \App\Action\UsersAction::class, 'users');

//$app->get('/submit/form', \App\Action\ViewSubmitChocolatesAction::class, 'submit-view');

$app->post('/submit', [
    \Middlewares\HttpAuthentication::class,
    \App\Action\SubmitChocolatesAction::class
], 'submit');
