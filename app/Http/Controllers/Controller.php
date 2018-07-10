<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Serializer\DataArraySerializer;
use League\Fractal\TransformerAbstract;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     *
     * @param $array
     * @return array
     */
    public function getPaginationProperties($array)
    {
        return [
            'total' => $array->total(),
            'per_page' => $array->perPage(),
            'current_page' => $array->currentPage(),
            'last_page' => $array->lastPage(),
            'next_page_url' => $array->nextPageUrl(),
            'prev_page_url' => $array->previousPageUrl(),
            'from' => $array->firstItem(),
            'to' => $array->lastItem(),
        ];
    }

    /**
     *
     * @param Model $model
     * @param array $fields
     * @return array
     */
    public function getData(Model $model, array $fields)
    {
        return array_compare($model->toArray(), $fields);
    }

    /**
     *
     * @param Model $model
     * @param null $name
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * @throws \ReflectionException
     */
    protected function destroyModel(Model $model, $name = null)
    {
        try {
            $model->delete();

            return $this->respondDestroy();
        } catch (Exception $e) {

            //Integrity constraint violation
            if ($e->getCode() === '23000') {
                $name = (new \ReflectionClass($model))->getShortName();
                $message = $name . ' could not be deleted. It is in use.';
            } else {
                $message = 'There was an error';
            }

            return response([
                'error' => $message,
                'status' => Response::HTTP_BAD_REQUEST
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    protected function respondDestroy()
    {
        return response([], Response::HTTP_NO_CONTENT);
    }

    /**
     *
     * @param $model
     * @param $transformer
     * @param array $includes
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    protected function respondShow($model, $transformer, array $includes = null)
    {
        $model = $this->transformItem($model, $transformer, $includes);

        return response($model, Response::HTTP_OK);
    }

    /**
     *
     * @param $collection
     * @param $transformer
     * @param array $includes
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    protected function respondShowWithPagination($collection, $transformer, array $includes)
    {
        return response(
            [
                'data' => $this->transform($this->createCollection($collection, $transformer),
                    $includes)['data'],
                'pagination' => $this->getPaginationProperties($collection)
            ],
            Response::HTTP_OK
        );
    }

    /**
     *
     * @param $collection
     * @param $transformer
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    protected function respondIndexWithPagination($collection, $transformer)
    {
        return response(
            [
                'data' => $this->transform($this->createCollection($collection, $transformer))['data'],
                'pagination' => $this->getPaginationProperties($collection)
            ],
            Response::HTTP_OK
        );
    }

    /**
     *
     * @param $model
     * @param $transformer
     * @param array $includes
     * @return mixed
     */
    private function transformItem($model, $transformer, array $includes = null)
    {
        return $this->transform($this->createItem($model, $transformer), $includes)['data'];
    }

    /**
     *
     * @param $model
     * @param $transformer
     * @param array|null $includes
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    protected function respondUpdate($model, $transformer, array $includes = null)
    {
        $model = $this->transformItem($model, $transformer, $includes);

        return response($model, Response::HTTP_OK);
    }

    /**
     *
     * @param $model
     * @param $transformer
     * @param array|null $includes
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    protected function respondStore($model, $transformer, array $includes = null)
    {
        $model = $this->transformItem($model, $transformer, $includes);

        return response($model, Response::HTTP_CREATED);
    }

    /**
     *
     * @param $collection
     * @param $transformer
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    protected function respondIndex($collection, $transformer)
    {
        $collection = $this->transformCollection($collection, $transformer);

        return response($collection, Response::HTTP_OK);
    }

    /**
     *
     * @param $collection
     * @param $transformer
     * @return mixed
     */
    private function transformCollection($collection, $transformer)
    {
        return $this->transform($this->createCollection($collection, $transformer))['data'];
    }


    /**
     * For Fractal transformer
     * @param $resource
     * @param null $includes
     * @return array
     */
    public function transform($resource, $includes = null)
    {
        $manager = new Manager();
        $manager->setSerializer(new DataArraySerializer);

        if ($includes) {
            $manager->parseIncludes($includes);
        }

        return $manager->createData($resource)->toArray();
    }

    /**
     * For Fractal transformer
     * @param $model
     * @param TransformerAbstract $transformer
     * @param null $key
     * @return Collection
     */
    public function createCollection($model, TransformerAbstract $transformer, $key = null)
    {
        return new Collection($model, $transformer, $key);
    }

    /**
     *
     * @param $model
     * @param TransformerAbstract $transformer
     * @param null $key
     * @return Item
     */
    public function createItem($model, TransformerAbstract $transformer, $key = null)
    {
        return new Item($model, $transformer, $key);
    }
}
