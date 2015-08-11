<?php

namespace App\Exceptions;

use RuntimeException;

/**
 * Class ModelAlreadyExistsException
 * @package App\Exceptions
 */
class ModelAlreadyExistsException extends RuntimeException{

    /**
     * @var string
     */
    public $model;

    public function __construct($model)
    {
        $this->model = $model;
    }

}