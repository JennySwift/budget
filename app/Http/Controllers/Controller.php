<?php namespace App\Http\Controllers;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
//use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Serializer\DataArraySerializer;
use League\Fractal\TransformerAbstract;

abstract class Controller extends BaseController {

	use ValidatesRequests;

    /**
     * Create a 201 - Created response
     * @param Arrayable $data => Arrayable allows the method to accept any object with a toArray() method.
     * @return Response
     */
    public function responseCreated(Arrayable $data)
    {
        return response($data->toArray(), Response::HTTP_CREATED);
    }

    /**
     * Create a 200 - OK response
     * @param Arrayable $data
     * @return Response
     */
    public function responseOk(Arrayable $data)
    {
        return response($data->toArray(), Response::HTTP_OK);
    }

    /**
     * @param $resource
     * @return mixed
     */
    public function responseWithTransformer($resource, $code)
    {
        $manager = new Manager();
        $manager->setSerializer(new DataArraySerializer);

        $manager->parseIncludes(request()->get('includes', []));

        return response()->json(
            $manager->createData($resource)->toArray(),
            $code
        );
    }

    /**
     * Return response ok code with transformed resource
     * @param $resource
     * @return mixed
     */
    public function responseOkWithTransformer($resource, $transformer)
    {
        //Transform
        $resource = createItem($resource, $transformer);

        return response(transform($resource), Response::HTTP_OK);
    }

    /**
     * Return response created code with transformed resource
     * @param $resource
     * @return mixed
     */
    public function responseCreatedWithTransformer($resource, $transformer)
    {
        //Transform
        $resource = createItem($resource, $transformer);

        return response(transform($resource), Response::HTTP_CREATED);

        /**
         * @VP:
         * Why do all this stuff when I could just do this:
         * return response(transform($resource), Response::HTTP_CREATED);
         */

//        $manager = new Manager();
//        $manager->setSerializer(new DataArraySerializer);
//
//        $manager->parseIncludes(request()->get('includes', []));
//
//        return response()->json(
//            $manager->createData($resource)->toArray(),
//            Response::HTTP_CREATED
//        );
    }



    /**
     * Create a 204 - No content response
     * @return Response
     */
    public function responseNoContent()
    {
        return response([], Response::HTTP_NO_CONTENT);
    }

    /**
     * Create a 304 - No content response
     * @return Response
     */
    public function responseNotModified()
    {
        return response([], Response::HTTP_NOT_MODIFIED);
    }

    /**
     * For Fractal transformer
     * @param $resource
     * @param null $includes
     * @param Request $request
     * @return array
     */
    public function transform($resource, $includes = null, Request $request = null)
    {
        $manager = new Manager();
        $manager->setSerializer(new DataArraySerializer);

        //Includes passed to this method as a parameter
        if ($includes) {
            $manager->parseIncludes($includes);
        }

        //Includes in url
        if ($request && $request->has('include')) {
            $manager->parseIncludes($request->get('include'));
        }

        return $manager->createData($resource)->toArray();
    }

    /**
     * For Fractal transformer
     * @param EloquentCollection $collection
     * @param TransformerAbstract $transformer
     * @param null $key
     * @return Collection
     */
    public function createCollection(EloquentCollection $collection, TransformerAbstract $transformer, $key = null)
    {
        return new Collection($collection, $transformer, $key);
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
