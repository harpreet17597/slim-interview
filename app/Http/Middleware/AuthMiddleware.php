<?php

namespace App\Http\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use League\OAuth2\Server\ResourceServer;
use League\OAuth2\Server\CryptKey;
use App\OAuth\Repositories\AccessTokenRepository;
use Nyholm\Psr7\Response as Psr7Response;
use League\OAuth2\Server\Exception\OAuthServerException;
use Illuminate\Database\Capsule\Manager as DB;

class AuthMiddleware
{
    protected ResourceServer $server;

    public function __construct()
    {
        $this->server = new ResourceServer(
            new AccessTokenRepository(),
            new CryptKey(__DIR__ . '/../../../storage/oauth/public.key', null, false)
        );
    }

    public function __invoke(Request $request, $handler): Response
    {
        try {
            // Validate the request; adds oauth_* attributes on success
            $request = $this->server->validateAuthenticatedRequest($request);

            // Map to your app's attribute name
            $userId = $request->getAttribute('oauth_user_id');
            if ($userId !== null) {
                $user = DB::table('users')->where('userId', $userId)->first();

                if (!$user) {
                    $res = new Psr7Response(401);
                    $res->getBody()->write(json_encode([
                        'error' => 'invalid_user',
                        'message' => 'User not found',
                    ]));
                    return $res->withHeader('Content-Type', 'application/json');
                }

                // ✅ Attach whole user object instead of just id
                $request = $request->withAttribute('user', $user);
            }

            return $handler->handle($request);
        } catch (OAuthServerException $e) {
            $res = new Psr7Response($e->getHttpStatusCode());
            $res->getBody()->write(json_encode([
                'error' => $e->getErrorType(),
                'message' => $e->getMessage(),
            ]));
            return $res->withHeader('Content-Type', 'application/json');
        } catch (\Throwable $e) {
            $res = new Psr7Response(500);
            $res->getBody()->write(json_encode(['error' => 'server_error', 'message' => $e->getMessage()]));
            return $res->withHeader('Content-Type', 'application/json');
        }
    }
}
