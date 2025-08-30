<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\App;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\BooksController;
use App\Http\Controllers\AnalyticsController;
use App\OAuth\OAuthServerFactory;
use App\Http\Middleware\AuthMiddleware;

return function (App $app) {

    // Books APIs
    $app->group('/books', function ($group) {
        $group->post("", [BooksController::class, "store"]);
        $group->get("", [BooksController::class, "index"]);
        $group->post("/{bookId}/borrow", [BooksController::class, "borrow"]);
        $group->get("/{bookId}/borrows", [BooksController::class, "borrows"]);
    })->add(new AuthMiddleware());

    // Analytics APIs
    $app->group('/analytics', function ($group) {
        $group->get("/latest-borrow-per-book", [AnalyticsController::class, "latestBorrowPerBook"]);
        $group->get("/borrow-rank-per-user", [AnalyticsController::class, "borrowRankPerUser"]);
        $group->get("/book-summary", [AnalyticsController::class, "bookSummary"]);
    });

    $app->post('/oauth/token', function ($request, $response) {
        $server = OAuthServerFactory::create();
        try {
            return $server->respondToAccessTokenRequest($request, $response);
        } catch (\Throwable $e) {
            $response->getBody()->write(json_encode([
                'error' => 'invalid_request',
                'message' => $e->getMessage()
            ]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    });
};
