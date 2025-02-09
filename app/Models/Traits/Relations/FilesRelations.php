<?php
namespace App\Models\Traits\Relations;

use App\Models\File;

trait FilesRelations{
    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }

}
