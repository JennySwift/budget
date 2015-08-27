<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

abstract class Request extends FormRequest {

    /**
     * Get the response for a forbidden operation.
     *
     * @return \Illuminate\Http\Response
     */
    public function forbiddenResponse()
    {
        return new Response([
            'error' => 'Forbidden',
            'status' => Response::HTTP_FORBIDDEN
        ], Response::HTTP_FORBIDDEN);
    }

}
