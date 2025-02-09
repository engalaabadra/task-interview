<?php
namespace App\Repositories\Eloquent;


class EloquentRepository implements EloquentRepositoryInterface{

    public function index($model){
        $items = $model->get();
        return $items;
    }
    public function show($model, $id){
        $item = $model->find($id);
        if(!$item) return 404;
        return $item;
    }
    
}