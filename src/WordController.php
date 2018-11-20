<?php

namespace Rudashi\Orwell;

use App\Http\Controllers\Controller;

class WordController extends Controller
{
    private $repository;

    public function __construct(WordRepository $repository)
    {
        $this->repository = $repository;
    }

    public function search(string $letters) : \Illuminate\Http\JsonResponse
    {
        try {
            $letters = $this->repository->prepareInputSearch($letters);
            $collection = $this->repository->anagram($letters, 25);

            return response()->json([
                'data' => collect([
                    'search' => $letters,
                    'words' => $collection
                ])
            ]);

        } catch (\Exception $e) {
            return $this->responseException($e);
        }

    }

    public function allWords(string $letters) : \Illuminate\Http\JsonResponse
    {
        try {
            $letters = $this->repository->prepareInputSearch($letters);
            $collection = $this->repository->anagram($letters);

            return response()->json($collection);

        } catch (\Exception $e) {
            return $this->responseException($e);
        }
    }

    public function responseError(string $message, int $statusCode = 400) : \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'error' => $message,
        ], $statusCode);
    }

    public function responseException(\Exception $exception) : \Illuminate\Http\JsonResponse
    {
        switch ($exception->getCode()) {
            case 7:
                return $this->responseError('Database Missing.', 400);
            case '42P01':
                return $this->responseError('Table Missing.', 400);
            default:
                return $this->responseError($exception->getMessage(), 400);
        }
    }
}