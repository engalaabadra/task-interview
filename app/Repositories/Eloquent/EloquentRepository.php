<?php

namespace App\Repositories\Eloquent;

use App\Scopes\ActiveScope;
use App\Repositories\Eloquent\EloquentRepositoryInterface;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * EloquentRepository
 *
 * This is a base Repository class implementing the EloquentRepositoryInterface.
 * It provides methods for using in whole project such as : getData, search, show, trash
 */
class EloquentRepository  implements EloquentRepositoryInterface
{
 /**
     * Get Data (all, pagination) -> Taking into consideration language.
     * @param object $model The model to query.
     * @return array Paginated or full collection of results.
     */
    public function getData($model)
    {
        $query = $model;
        // // Filter data by main language
        // if(Schema::hasColumn($model->getTable(), 'lang') && lang()) $query->where('lang',lang());
        // Add active filter if specified
        if (active() !== null) $query->where('active', active());
        // Apply eager loading if specified
        if ($model::$eagerLoading)  $query = $query->with($model::$eagerLoading);
        // Paginate or fetch all records based on the `page()` value
        return page() ?  $query->paginate(total()) : $query->get();
    }

    /**
     * Search for records with optional eager loading and multiple column support.
     * 
     * @param object $model The model to query.
     * @return object Paginated or full collection of results.
     */
    public function search($model)
    {
        // Get the search term from the query string
        $word = query();
        $columnsSearch = $model::$columnsSearch;
        // Initialize the query with optional eager loading
        $query = $model->when($model::$eagerLoading, fn($q) => $q->with($model::$eagerLoading));
        // Add the search conditions for multiple columns
        $query->where(function ($q) use ($columnsSearch, $word) {
            foreach ($columnsSearch as $col) {
                $q->orWhere($col, 'like', '%' . $word . '%');
            }
        });
        // Return paginated or full results
        return page() ? $query->paginate(total()) : $query->get();
    }

    /**
     * Show a specific record.
     * @param int $id The ID of the record to show.
     * @param object $model The model to query.
     * @return object The requested record.
     */
    public function show($id, $model)
    {
        $query = $model->withoutGlobalScopes();
        // Find the record or return 404 if not found
        $item = $query->find($id);
        if(!$item) return 404;
        return $model::$eagerLoading ? $item->load($model::$eagerLoading) : $item;
    }
    
    //for trash
    /**
     * Get trashed records with optional eager loading (pagination).
     * @param object $model The model to query.
     * @return array Paginated or full collection of trashed records.
     */
    public function trash($model)
    {
        // Check if the model uses SoftDeletes
        if (!in_array(SoftDeletes::class, class_uses($model))) return 404;
        $query = $model;
        // Apply eager loading & get data
        $items = $query->withoutGlobalScopes()->onlyTrashed()->when($model::$eagerLoading, fn($q) => $q->with($model::$eagerLoading))->get();
        if (empty($items)) return 404;
        // Return paginated or full results
        return page() ? $query->paginate(total()) : $items;
    }
}

