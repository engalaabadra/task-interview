<?php
namespace App\Services\Eloquent;

interface EloquentServiceInterface
{
   public function store($request, $model);
   public function update($request, $id, $model);
   public function destroy($id,$model);
   public function changeActivate($id, $model);
   public function forceDelete($id, $model);
   public function restore($id,$model);
   public function restoreAll($model);
}

