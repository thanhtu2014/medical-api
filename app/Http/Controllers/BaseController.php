<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Response;

class BaseController extends Controller
{
    public function error($error, $code = 404)
    {
        return Response::json([
            'success' => false,
            'message' => $error,
        ], $code);
    }

    public function success($message, $data = NULL, $code = 200)
    {
        return Response::json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    public function successWithPaginate($message, $pagination = NULL, $resourceCollection, $code = 200)
    {
        // Re format pagination struct
        $data = null;
        $resourceCollectionInstance = "\\App\\Http\\Resources\\API\\V1\\$resourceCollection";
        if ($pagination instanceof \Illuminate\Pagination\LengthAwarePaginator) {
            $data = [
                'current_page' => (int) $pagination->currentPage(),
                'per_page' => (int) $pagination->perPage(),
                'last_page' => (int) $pagination->lastPage(),
                'total' => (int) $pagination->total(),
                'prev_page_url' => $pagination->previousPageUrl(),
                'next_page_url' => $pagination->nextPageUrl(),
                'items' => new $resourceCollectionInstance($pagination->items()),
            ];
        }

        return Response::json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $code);
    }
}
