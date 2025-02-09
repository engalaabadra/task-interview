<?php
namespace App\Models\Traits\Relations;

use App\Models\File;

trait FileRelations{
    public function file(){
        return $this->morphOne(File::class, 'fileable');
    }
}
