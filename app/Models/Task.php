<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
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
        'due_date',
        'active'
    ];
    protected $appends = ['original_active'];
    public static $eagerLoading = ['files'];
    public static $excludedFields = ['status']; // these fields to insert data that dont need translation for a record
    public static $translationFields = ['title', 'description']; // these fields to use it in request file to Add dynamic validation rules for each field in the translations in request
    public static $columnsSearch = ['title', 'status', 'description', 'due_date', 'created_at'];//fields for search
    

}
