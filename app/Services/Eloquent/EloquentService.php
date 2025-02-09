<?php
namespace App\Services\Eloquent;

use App\GeneralClasses\MediaClass;
use App\Scopes\ActiveScope;
use App\Services\General\GeneralService;
use App\Services\Translation\TranslationService;

/**
 * EloquentService
 *
 * This is a base Service class implementing the EloquentServiceInterface.
 * It provides methods for using in whole project such as : store, update, update, destroy, foreDelete, changeActivate & another protected methods : handling file uploads and deletions using a MediaClass dependency.
 */
class EloquentService implements EloquentServiceInterface{

    /**
     * @var MediaClass Instance of the MediaClass for handling media-related operations.
     */
    protected $mediaClass;

    /**
     * @var GeneralService Instance of the GeneralService for handling logic methods .
     */
    protected $generalService;
    /**
     * @var TranslationService Instance of the TranslationService for handling logic methods .
     */
    protected $translationService;
    /**
     * Constructor
     *
     * Initializes the Service with a MediaClass instance.
     *
     * @param MediaClass $mediaClass Dependency for handling media uploads and deletions.
     * @param GeneralService $generalService Dependency
     * @param TranslationService $translationService Dependency
     
     */
    public function __construct( MediaClass $mediaClass, GeneralService $generalService, TranslationService $translationService)
    {
        $this->mediaClass = $mediaClass;
        $this->generalService = $generalService;
        $this->translationService = $translationService;
    }

     /**
     * Store a new record.
     * @param object $request The request object containing validated data.
     * @param object $model The model to query.
     * @return object Created record with optional eager loading.
     */
    public function store($request, $model)
    {
        // Get validated data and filter out 'file' or 'files'
        $data = $request->validated();
        $enteredData = array_diff_key($data, array_flip(['file','files']));// Filter out 'file' key and either update or create the model item
        // Create the model item
        $newItem = $model->create($enteredData);
        // create translations for this item //
        if($request->has('translations')){
            //data translations in array (translations) in request
            $transData = json_decode($request->get('translations'));

            //coverted this data to enter valid into method handleTranslation
            $convertedArray = [];
            if(isset($transData)){
                $convertedArray = array_map(function ($newItem) {
                                                return (array)$newItem;
                                            }, $transData);
            }
            
            $this->translationService->createTranslations($convertedArray, $newItem, $model);
        }
        // Handle file upload
        $modelName = modelName($model);
        // $this->mediaClass->handleFileUpload($data, $modelName, $newItem);
        return $model::$eagerLoading ? $newItem->load($model::$eagerLoading) : $newItem;
    }
    /**
     * Update a specific record.
     * @param object $request The request object containing validated data.
     * @param int $id The ID of the record to update.
     * @param object $model The model to query.
     * @return object Updated record.
     */
    public function update($request, $id, $model)
    {
        // Get validated data and find the record
        $data = $request->validated();
        $item = $model->find($id);
        if(!$item) return 404;
        $enteredData = array_diff_key($data, array_flip(['file','files']));// Filter out 'file' key and either update or create the model item
        // Update the record
        $item->update($enteredData);
        // Handle translations update
        if ($request->has('translations')) {
            // Data translations in array (translations) from request
            $transData = json_decode($request->get('translations'));
            // Convert data to valid format for processing
            $convertedArray = [];
            if (isset($transData)) {
                $convertedArray = array_map(function ($item) {
                    return (array) $item;
                }, $transData);
            }
            // Update translations
            $this->translationService->updateTranslations($convertedArray, $item, $model);
        }
        // Handle file upload
        $modelName = modelName($model);
       // $this->mediaClass->handleFileUpload($data, $modelName, $item);
       return $model::$eagerLoading ? $item->load($model::$eagerLoading) : $item;
    }
    /**
     * Toggle activation status for a record.
     * @param int $id The ID of the record to toggle activation.
     * @param object $model The model to query.
     * @return object Updated record with toggled activation status.
     */
    public function changeActivate($id, $model)
    {
        // Find the record or return 404
        $item = $model->withoutGlobalScopes()->find($id);
        if(!$item) return 404;
        // Toggle activation status
        $item->update(['active' => !$item->active]);
        return $model::$eagerLoading ? $item->load($model::$eagerLoading) : $item;
    }
     /**
     * delete one item .
     * @param object $model The model to query.
     * @return object Deleted record.
     */
    public function destroy($id, $model)
    {
        $item = $model->withoutGlobalScopes()->where('id', $id)->first();
        if (!$item) return 404;
        // If the item is already soft deleted, return 404
        if (!$item || $item->deleted_at !== null)  return 404;
        if (!isSoftDeletes($model)) {//this model not contain on trash , so delete will be permently -> will delete any relations with this model
            // Handle file deletetion
            if(isset($item->file, $model::$eagerLoading) && in_array('file',$model::$eagerLoading)) $this->mediaClass->handleFileDeletion($item);
            $item->delete();//delete this item(permently)
        }else{
            $item->delete();//delete this item(temporery)
            //this model  contain on trash , so will delete it in trash and return this item
            return $model::$eagerLoading? $item->load($model::$eagerLoading) : $item;
        }
    }
    
    /**
     * destroy many items.
     * @param object $model The model to query.
     * @return void.
     */
    public function destroyMany($model)
    {
        // Get 'ids' from the request (can be JSON array or string "all")
        $inputIds = request()->input('ids', []);
        // If the request is "all", fetch all items in the trash
        if ($inputIds === "all"){
            $staffs = $model->withoutGlobalScopes()
                                ->whereDoesntHave('roles', function ($query) {
                                $query->whereIn('name', ['admin', 'superadmin']);
                            })->get();
        } else {
           // Decode JSON if it's an array of IDs
           $ids = is_array($inputIds) ? $inputIds : json_decode($inputIds, true);
           if (!is_array($ids) || empty($ids)) return trans('messages.No valid IDs provided');
           // Fetch only the items  without global scopes only items in trash matching the provided IDs
            $items = $model->withoutGlobalScopes()->whereIn('id', $ids)->get();
        }
        // Return 404 if no items are found
        if ($items->isEmpty()) return 404;
        foreach ($items as $item) {
            // If the item is already soft deleted, continue
            if (!$item || $item->deleted_at !== null)  continue;
            if (!isSoftDeletes($model)) {//this model not contain on trash , so delete will be permently -> will delete any relations with this model
                // Handle file deletetion
                if(isset($item->file, $model::$eagerLoading) && in_array('file',$model::$eagerLoading)) $this->mediaClass->handleFileDeletion($item);
                $item->delete();//delete this item(permently)
            }else{
                $item->delete();//delete this item(temporery)
                //this model  contain on trash , so will delete it in trash and return this item
            }
        }
    }

    
    //for trash
    /**
     * Force delete - single item OR all items - a trashed record.
     * @param object $model The model to query.
     */
    public function forceDelete($id, $model)
    {
        // Fetch items without global scopes only items in trash
        $item = $model->onlyTrashed()->withoutGlobalScopes()->where('id', $id)->first();
        if(!$item) return 404;
        // Handle file deletetion
        if(isset($item->file, $model::$eagerLoading) && in_array('file',$model::$eagerLoading)) $this->mediaClass->handleFileDeletion($item);
        $item->forceDelete();
    }
    /**
     * Force delete - all items - a trashed record.
     * @param object $model The model to query.
     */
    public function forceDeleteMany($model)
    {
        // Get 'ids' from the request (can be JSON array or string "all")
        $inputIds = request()->input('ids', []);// Default to an empty array if null
        // If the request is "all", fetch all items in the trash
        if ($inputIds === "all") {
            $items = $model->onlyTrashed()->withoutGlobalScopes()
                                ->whereDoesntHave('roles', function ($query) {
                                $query->whereIn('name', ['admin', 'superadmin']);
                            })->get();
        } else {
            $ids = json_decode($inputIds);
            if (!is_array($ids) || empty($ids)) return trans('messages.No valid IDs provided');
            // Fetch items without global scopes only items in trash
            $items = $model->onlyTrashed()->withoutGlobalScopes()->whereDoesntHave('roles', function ($query) {
                                                $query->whereIn('name', ['admin', 'superadmin']);
                                            })->whereIn('id', $ids)->get();
            }
            if($items->isEmpty()) return 404;
            foreach ($items as $item) {
                // Handle file deletetion
                if(isset($item->file, $model::$eagerLoading) && in_array('file',$model::$eagerLoading)) $this->mediaClass->handleFileDeletion($item);
                $item->forceDelete();
            }
    }
    /**
     * Restore a trashed record.
     * @param int $id The ID of the trashed record to restore.
     * @param object $model The model to query.
     * @return object Restored record.
     */
    public function restore($id, $model)
    {
        // Find the trashed record or return 404
        $item = $model->withoutGlobalScopes()->onlyTrashed()->find($id);
        if(!$item) return 404;
        // Restore the record
        $item->restore();
        return $model::$eagerLoading ? $item->load($model::$eagerLoading) : $item;
    }
    /**
     * Restore all trashed records.
     * @param object $model The model to query.
     * @return array Restored records.
     */
    public function restoreAll($model)
    {
        // Get all trashed records
        $items =  $model->withoutGlobalScopes()->onlyTrashed()->get();
        if($items->isEmpty()) return 404;
        foreach($items as $item){
            // Restore all records
            $item->restore();
        }
        return $items;
    }

    /////////////////// For Files ////////////////////////////////
    public function uploadFile($request, $id, $model){
        $data = $request->validated();
        $staff = $model->find($id);
        if(!$staff) return 404;
        $folderName = modelName($model) . '-images';
        $this->mediaClass->handleFileUpload($data['file'] , $folderName, $model, $staff);
        //merge eager loading that it in user model & profile model , because i need all realtions(eagerloading) that it in 2 models 
        $eagerLoading = $model->getEagerLoadingUserProfile();
        return $eagerLoading ? $staff->load($eagerLoading) : $staff;
    }

    public function uploadFiles($request, $id, $model){
        $folderName = modelName($model) . 'files';
        $data = $request->validated();
        $item = $model->find($id);
        if(!$item) return 404;
        $this->mediaClass->handleMultipleFilesUpload($data['files'] , $folderName, $model, $item);
        return $model::$eagerLoading ? $item->load($model::$eagerLoading) : $item;
    }
    
    public function deleteFiles($id, $model){
        $item = $model->find($id);
        if(!$item) return 404;
        $fileIds = request()->input('file_ids', []); // Default to an empty array if null
        $this->mediaClass->handleFilesDeletion($item, $fileIds);
        return $model::$eagerLoading ? $item->load($model::$eagerLoading) : $item;
    }
    public function deleteFile($id, $model){
        $item = $model->find($id);
        if(!$item) return 404;
        $this->mediaClass->handleFileDeletion($item);
        return $model::$eagerLoading ? $item->load($model::$eagerLoading) : $item;
    }
    
}