<?php

namespace App\Http\Controllers\API;

use App\Http\Transformers\SavedFilterTransformer;
use App\Models\SavedFilter;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SavedFiltersController extends Controller
{
    /**
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $filter = new SavedFilter($request->only('name', 'filter'));
        $filter->user()->associate(Auth::user());
        $filter->save();
        return $this->responseCreatedWithTransformer($filter, new SavedFilterTransformer);
    }
}
