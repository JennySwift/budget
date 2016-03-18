<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Pusher;

class FeedbackController extends Controller
{
    /**
     * POST /api/feedbacks
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $pusher = new Pusher(env('PUSHER_PUBLIC_KEY'), env('PUSHER_SECRET_KEY'), env('PUSHER_APP_ID'));

        $data = [
            'title' => $request->get('title'),
            'body' => $request->get('body'),
            'priority' => $request->get('priority'),
        ];

        $pusher->trigger('myChannel', 'feedbackSubmitted', $data);

        return response($data, Response::HTTP_OK);
    }
}
