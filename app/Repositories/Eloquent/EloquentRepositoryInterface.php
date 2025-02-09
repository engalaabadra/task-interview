<?php
namespace App\Repositories\Eloquent;

interface EloquentRepositoryInterface
{
   public function getData($model);
   public function search($model);
   public function show($id,$model);
   public function trash($model);
}

