<?php
declare(strict_types=1);

use App\Application\Actions\User;
use App\Application\Actions\Matches;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->group('/', function (Group $group) {
        $group->get('', function (Request $request, Response $response) {
            $response->getBody()->write('Hello world!');
            return $response;
        });
        $group->post('register', User\RegisterUserAction::class);
        $group->post('login', User\LoginUserAction::class);
    });

    $app->group('/users', function (Group $group) {
        $group->get('', User\ListUsersAction::class);
        $group->get('/{id}', User\ViewUserAction::class);
        $group->get('/search/{keyword}', User\SearchUsernameAction::class);
    });

    $app->group('/superstars', function (Group $group) {
        $group->get('', Matches\ListSuperstarsAction::class);
        $group->get('/{id}', Matches\ViewSuperstarAction::class);
    });
};
