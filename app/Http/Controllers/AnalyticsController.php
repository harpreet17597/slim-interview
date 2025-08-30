<?php

namespace App\Http\Controllers;

use Illuminate\Database\Capsule\Manager as DB;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AnalyticsController
{
    // 1. Most recent borrow log per book
    public function latestBorrowPerBook(Request $request, Response $response): Response
    {
        $results = DB::select("
            SELECT bookId, userId, borrowLogDateTime
            FROM (
                SELECT *,
                       ROW_NUMBER() OVER (PARTITION BY bookId ORDER BY borrowLogDateTime DESC) AS rn
                FROM borrowlog
            ) t
            WHERE rn = 1
        ");

        $response->getBody()->write(json_encode($results));
        return $response->withHeader('Content-Type', 'application/json');
    }

    // 2. Borrow rank per user-book pair
    public function borrowRankPerUser(Request $request, Response $response): Response
    {
        $results = DB::select("
            SELECT borrowLogId, userId, bookId, borrowLogDateTime,
                   ROW_NUMBER() OVER (PARTITION BY userId, bookId ORDER BY borrowLogDateTime ASC) AS borrowRank
            FROM borrowlog
        ");

        $response->getBody()->write(json_encode($results));
        return $response->withHeader('Content-Type', 'application/json');
    }

    // 3. Book summary (with optional search)
    public function bookSummary(Request $request, Response $response): Response
    {
        $queryParam = $request->getQueryParams()['query'] ?? null;

        $sql = "
            SELECT b.bookId, b.bookTitle, b.bookAuthor, b.bookPublishYear,
                   COUNT(bl.borrowLogId) AS borrowCount,
                   (
                        SELECT u.username
                        FROM borrowlog bl2
                        JOIN users u ON u.userId = bl2.userId
                        WHERE bl2.bookId = b.bookId
                        ORDER BY bl2.borrowLogDateTime DESC
                        LIMIT 1
                   ) AS lastBorrowedBy
            FROM books b
            LEFT JOIN borrowlog bl ON bl.bookId = b.bookId
        ";

        if ($queryParam) {
            $sql .= " WHERE MATCH(b.bookTitle, b.bookAuthor) AGAINST (? IN NATURAL LANGUAGE MODE) ";
            $results = DB::select($sql . " GROUP BY b.bookId", [$queryParam]);
        } else {
            $results = DB::select($sql . " GROUP BY b.bookId");
        }

        $response->getBody()->write(json_encode($results));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
