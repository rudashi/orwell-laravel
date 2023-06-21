<?php

declare(strict_types=1);

namespace Rudashi\Orwell;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class WordController extends Controller
{
    public function __construct(
        private readonly WordRepository $repository
    ) {
    }

    public function search(string $letters): JsonResponse
    {
        try {
            $letters = $this->repository->prepareInputSearch($letters);
            $collection = $this->repository->anagram($letters, 25);

            return response()->json([
                'data' => collect([
                    'search' => $letters,
                    'words' => $collection,
                ]),
            ]);
        } catch (Exception $e) {
            return $this->responseException($e);
        }
    }

    public function allWords(string $letters): JsonResponse
    {
        try {
            $letters = $this->repository->prepareInputSearch($letters);
            $collection = $this->repository->anagram($letters);

            return response()->json($collection);
        } catch (Exception $e) {
            return $this->responseException($e);
        }
    }

    public function find(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'letters' => [
                'required',
                'string',
                'regex:' . $this->repository::REGEX,
                'max:255',
            ],
        ]);

        try {
            $letters = $this->repository->prepareInputSearch($validated['letters']);
            $collection = $this->repository->anagram($letters);

            return response()->json($collection);
        } catch (Exception $e) {
            return $this->responseException($e);
        }
    }

    public function responseError(string $message, int $statusCode = 400): JsonResponse
    {
        return response()->json(['error' => $message], $statusCode);
    }

    public function responseException(Exception $exception): JsonResponse
    {
        return match ($exception->getCode()) {
            7 => $this->responseError('Database Missing.', 500),
            '42P01' => $this->responseError('Table Missing.', 500),
            default => $this->responseError($exception->getMessage()),
        };
    }
}
