<?php

namespace App\Http\Traits;

use Illuminate\Pagination\LengthAwarePaginator;

trait RestfulResponse
{

  public function respondWithFailure($message = '', $statusCode = 422)
  {
    return response()->json([
      'status'          => $statusCode,
      'message'         => $message
    ], $statusCode);
  }

  public function respondWithSuccess($message = '')
  {
    return response()->json([
      'status'          => 200,
      'message'         => $message,
    ], 200);
  }

  public function respondWithData($data = [], $message = "", $meta = [], $headers = [])
  {
    return response()->json([
      'status'       => 200,
      'message'      => $message,
      'data'         => $data,
      'meta'         => $meta
    ], 200)->withHeaders($headers);
  }

  public function respondWithPagination(LengthAwarePaginator $paginator, $meta = [], $headers = [])
  {
    return response()->json([
      'status'          => 200,
      'total_result'    => $paginator->total(),
      'last_page'       => $paginator->lastPage(),
      'data'            => array_values($paginator->items()),
      'meta'            => $meta
    ], 200)->withHeaders($headers);
  }
}
