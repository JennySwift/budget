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
     * @var array
     */
    private $fields = ['name', 'filter'];

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request)
    {
        $savedFilters = SavedFilter::forCurrentUser()->get();

        return $this->respondIndex($savedFilters, new SavedFilterTransformer);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function store(Request $request)
    {
        $savedFilter = new SavedFilter($request->only($this->fields));
        $savedFilter->user()->associate(Auth::user());
        $savedFilter->save();

        return $this->respondStore($savedFilter, new SavedFilterTransformer);
    }

    /**
     *
     * @param Request $request
     * @param SavedFilter $savedFilter
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * @throws \ReflectionException
     */
    public function destroy(Request $request, SavedFilter $savedFilter)
    {
        return $this->destroyModel($savedFilter);
    }
}
