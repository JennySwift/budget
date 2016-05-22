<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Pusher;
use Auth;

class FeedbackController extends Controller
{
    /**
     * POST /api/feedbacks
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
//        $pusher = new Pusher(env('PUSHER_PUBLIC_KEY'), env('PUSHER_SECRET_KEY'), env('PUSHER_APP_ID'));
//
//        $data = [
//            'submittedBy' => [
//                'id' => Auth::user()->id,
//                'name' => Auth::user()->name,
//                'email' => Auth::user()->email
//            ],
//            'title' => $request->get('title'),
//            'body' => $request->get('body'),
//            'priority' => $request->get('priority'),
//        ];
//
//        $pusher->trigger('myChannel', 'budgetAppFeedbackSubmitted', $data);
//
//        return response($data, Response::HTTP_OK);
    }
}
