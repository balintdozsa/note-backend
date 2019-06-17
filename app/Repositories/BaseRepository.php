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

    public function getById($id) {
        return $this->model->find($id);
    }

    public function getByIds($ids = []) {
        return $this->model->findMany($ids);
    }

    public function create($attributes = []) {
        return $this->model->create($attributes);
    }

    public function updateOrCreate($attributes = [], $updateAttributes = []) {
        $this->model->updateOrCreate($attributes, $updateAttributes);
    }

    public function modifyById($id, $attributes = []) {
        $this->getById($id)->update($attributes);
    }

    public function modifyByIds($ids = [], $attributes = []) {
        $items = $this->getByIds($ids);
        foreach ($items as $item) {
            $item->update($attributes);
        }
    }

    public function deleteById($id) {
        $this->getById($id)->delete();
    }
}