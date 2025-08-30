<?php

namespace App\Http\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\Book;
use App\Models\User;
use App\Models\BorrowLog;
use Respect\Validation\Validator as v;

class BooksController
{
    public function index(Request $request, Response $response): Response
    {
        $books = Book::all();
        $response->getBody()->write($books->toJson());
        return $response->withHeader('Content-Type', 'application/json');
    }
    public function store(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();

        if (isset($data['bookPublishYear'])) {
            $data['bookPublishYear'] = (int) $data['bookPublishYear'];
        }
        // Validation
        $validation = v::key('bookTitle', v::stringType()->notEmpty())
            ->key('bookAuthor', v::optional(v::stringType()))
            ->key('bookPublishYear', v::optional(v::intType()->between(1500, (int)date('Y'))));

        try {
            $validation->assert($data);
        } catch (\Respect\Validation\Exceptions\NestedValidationException $e) {
            $response->getBody()->write(json_encode([
                'errors' => $e->getMessages()
            ]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(422);
        }

        //  Save after validation
        $book = Book::create([
            'bookTitle'       => $data['bookTitle'],
            'bookAuthor'      => $data['bookAuthor'] ?? null,
            'bookPublishYear' => $data['bookPublishYear'] ?? null,
        ]);

        $response->getBody()->write($book->toJson());
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    }

    public function borrow(Request $request, Response $response, $bookId): Response
    {
        $data = $request->getParsedBody();

        $user = $request->getAttribute('user');

        if (!Book::find($bookId)) {
            return $this->errorResponse($response, 'Book not found', 404);
        }

        $borrow = BorrowLog::create([
            'bookId'            => $bookId,
            'userId'            => $user->userId,
            'borrowLogDateTime' => date('Y-m-d H:i:s')
        ]);

        $response->getBody()->write($borrow->toJson());
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    }

    public function borrows(Request $request, Response $response, $bookId): Response
    {
        if (!Book::find($bookId)) {
            return $this->errorResponse($response, 'Book not found', 404);
        }
        $logs = BorrowLog::with(['book'])
            ->where('bookId', $bookId)
            ->orderBy('borrowLogDateTime', 'desc')
            ->get();

        $response->getBody()->write($logs->toJson());
        return $response->withHeader('Content-Type', 'application/json');
    }

    private function errorResponse(Response $response, string $message, int $status): Response
    {
        $response->getBody()->write(json_encode(['error' => $message]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus($status);
    }
}
