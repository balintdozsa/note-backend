<?php

namespace App\Repositories;

abstract class BaseRepository
{
    protected $model;
    
    public function __construct() {
        $modelClass = $this->getModel();

        $this->model = new $modelClass();
    }

    abstract protected function getModel();
}