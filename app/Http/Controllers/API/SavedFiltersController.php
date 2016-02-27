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
     * GET /api/savedFilters
     * @return Response
     */
    public function index()
    {
        $savedFilters = SavedFilter::forCurrentUser()->get();
        $savedFilters = $this->transform($this->createCollection($savedFilters, new SavedFilterTransformer))['data'];
        return response($savedFilters, Response::HTTP_OK);
    }

    /**
     *
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        $filter = new SavedFilter($request->only(['name', 'filter']));
        $filter->user()->associate(Auth::user());
        $filter->save();

        return $this->responseCreatedWithTransformer($filter, new SavedFilterTransformer);
    }
}
