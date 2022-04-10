<?php

namespace Rudashi\Orwell;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WordController extends Controller
{
    private $repository;

    public function __construct(WordRepository $repository)
    {
        $this->repository = $repository;
    }

    public function search(string $letters) : JsonResponse
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

    public function allWords(string $letters) : JsonResponse
    {
        try {
            $letters = $this->repository->prepareInputSearch($letters);
            $collection = $this->repository->anagram($letters);

            return response()->json($collection);

        } catch (\Exception $e) {
            return $this->responseException($e);
        }
    }

    public function find(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'letters' => [
                'required',
                'string',
                'regex:/[A-ZĄĆĘŁŃÓŚŹŻ\?]/iu',
                'max:255'
            ],
        ]);

        try {
            $letters = $this->repository->prepareInputSearch($validated['letters']);
            $collection = $this->repository->anagram($letters);

            return response()->json($collection);

        } catch (\Exception $e) {
            return $this->responseException($e);
        }
    }

    public function responseError(string $message, int $statusCode = 400) : JsonResponse
    {
        return response()->json([
            'error' => $message,
        ], $statusCode);
    }

    public function responseException(\Exception $exception) : JsonResponse
    {
        switch ($exception->getCode()) {
            case 7:
                return $this->responseError('Database Missing.', 500);
            case '42P01':
                return $this->responseError('Table Missing.', 500);
            default:
                return $this->responseError($exception->getMessage());
        }
    }
}
