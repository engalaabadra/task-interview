<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Relations\FilesRelations;
use App\Models\BaseModel;

class Task extends BaseModel
{
    use SoftDeletes, FilesRelations;
    public $fillable = [
        'lang',
        'translate_id',
        'title',
        'description',
        'status',
        'due_date'
    ];
    public static $eagerLoading = ['files'];
    public static $excludedFields = ['url']; // these fields to insert data that dont need translation for a record
    public static $translationFields = ['title', 'url', 'description']; // these fields to use it in request file to Add dynamic validation rules for each field in the translations in request
    public static $columnsSearch = ['title', 'url', 'description', 'url', 'created_at'];//fields for search
    protected $appends = ['original_active'];
    

}
