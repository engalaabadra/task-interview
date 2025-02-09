<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Task;
use App\Repositories\Modules\Task\TaskRepository;
use App\Services\Modules\Task\TaskService;
use App\Http\Requests\Task\StoreTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Resources\TaskResource;
use App\Http\Requests\File\UploadFilesRequest;

class TaskController extends Controller
{
    /**
     * @var TaskService
     */
    protected $taskService;
    /**
     * @var TaskRepository
     */
    protected $taskRepo;
        /**
     * @var Task
     */
    protected $task;
    /**
     * TaskController constructor.
     *
     */
    public function __construct( Task $task, TaskRepository $taskRepo, TaskService $taskService)
    {

        $this->task = $task;
        $this->taskRepo = $taskRepo;
        $this->taskService = $taskService;
    }

    /**
     * Display a listing of the resource (all , pagination).
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $tasks = $this->taskRepo->getData($this->task);
        $data = TaskResource::collection($tasks);
        if (page()) $data = $data->response()->getData(true);
        return successResponse(0,$data);
    }
    /**
     * search (all , pagination).
     *
     * @return \Illuminate\Http\Response
     */
    public function search(){
        $tasks = $this->taskRepo->search($this->task);
        $data = TaskResource::collection($tasks);
        if (page()) $data = getDataResponse($data);
        return  successResponse(0,$data);
    }
    /**
     * Show the specified resource.
     * @param $id
     * @return Responsable
     */
    public function show($id)
    {
        $task =   $this->taskRepo->show($id, $this->task);
        if(is_numeric($task)) return clientError(4);// Return 404 Not Found
        return successResponse(0, new TaskResource($task));
    }
    
    /**
     * store.
     * @param StoreTaskRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTaskRequest $request, $id = null){
        $task = $this->taskService->store($request,  $this->task, $id);
        if(is_string($task)) return clientError(0,$task);// Return the error message if data is missing
        if(is_numeric($task)) return clientError(4);// Return 404 Not Found
        return successResponse(1, new TaskResource($task));
    }
   
    /**
     * update.
     * @param UpdateTaskRequest $request
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTaskRequest $request,$id){
        $task = $this->taskService->update($request, $id, $this->task);
        if(is_numeric($task)) return clientError(4);// Return 404 not found
        if(is_string($task)) return clientError(0,$task);// Return the error message if data is missing
        return  successResponse(1,new TaskResource($task));
    }
     /**
     * changeActivate.
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function changeActivate($id){
        $task = $this->taskService->changeActivate($id, $this->task);
        if(is_numeric($task)) return clientError(4);// Return 404 not found
        return  successResponse(1,new TaskResource($task));
    }
    /**
     * delete one item or delete all items.
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id = null){
        $data=$this->taskService->destroy($id,$this->task);
        if(is_numeric($data)) return clientError(4);// Return 404 not found
        if(is_string($data)) return clientError(0,$data);// Return the error message if data is missing
        return  successResponse(2);
    }
     /**
     * destroyMany.
     * @return \Illuminate\Http\Response
     */
    public function destroyMany(){
        $task=$this->taskService->destroyMany($this->task);
        if(is_numeric($task)) return clientError(4);// Return 404 not found
        if(is_string($task)) return clientError(0,$task);// Return the error message if data is missing
        return successResponse(2);
    }

    /**
     * forceDelete one item or all items.
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function forceDelete($id = null){
        $task = $this->taskService->forceDelete($id,$this->task);
        if(is_numeric($task)) return clientError(4);// Return 404 not found
        if(is_string($task)) return clientError(0,$task);// Return the error message if data is missing
        return successResponse(2);
    }
    /**
     * forceDeleteMany.
     * @return \Illuminate\Http\Response
     */
    public function forceDeleteMany(){
        $task=$this->taskService->forceDeleteMany($this->task);
        if(is_numeric($task)) return clientError(4);// Return 404 not found
        if(is_string($task)) return clientError(0,$task);// Return the error message if data is missing
        return successResponse(2);
    }
    //for trash
    /**
     * Display a listing of the resource (all , pagination).
     *
     * @return \Illuminate\Http\Response
     */
    public function trash(){
        $tasks = $this->taskRepo->trash($this->task);
        $data = TaskResource::collection($tasks);
        if (page()) $data = getDataResponse($data);
        return successResponse(0,$data);
    }
    
    /**
     * Restore the specified resource.
     * @param $id
     * @return Responsable
     */
    public function restore($id)
    {
        $task =   $this->taskService->restore($id, $this->task);
        if(is_numeric($task)) return clientError(4);// Return 404 Not Found
        return successResponse(0, new TaskResource($task));
    }
    /**
     * restoreAll.
     * @return \Illuminate\Http\Response
     */
    public function restoreAll(){
        dd(2);
        $tasks=$this->taskService->restoreAll($this->task);
        if(is_numeric($tasks)) return clientError(4);// Return 404 not found
        $data = TaskResource::collection($tasks);
        if (page()) $data = getDataResponse($data);
        return successResponse(0,$data);
    }



      //////////////For Files///////////////////////
    /**
     * store file
     * @param UploadFileRequest $request
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function uploadFile(UploadFileRequest $request,$id){
        $task = $this->taskService->uploadFile($request, $id, $this->task);
        if(is_numeric($task)) return clientError(4);// Return 404 not found
        if(is_string($task)) return clientError(0,$task);// Return the error message if data is missing
        return  successResponse(1,new TaskResource($task));
    }
    /**
     * store files
     * @param UploadFilesRequest $request
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function uploadFiles(UploadFilesRequest $request,$id){
        $task = $this->taskService->uploadFiles($request, $id, $this->task);
        if(is_numeric($task)) return clientError(4);// Return 404 not found
        if(is_string($task)) return clientError(0,$task);// Return the error message if data is missing
        return  successResponse(1,new TaskResource($task));
    }
    /**
     * delete files
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function deleteFiles($id){
        $task = $this->taskService->deleteFiles($id, $this->task);
        if(is_numeric($task)) return clientError(4);// Return 404 not found
        if(is_string($task)) return clientError(0,$task);// Return the error message if data is missing
        return  successResponse(1,new TaskResource($task));
    }
    /**
     * delete file
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function deleteFile($id){
        $task = $this->taskService->deleteFile($id, $this->task);
        if(is_numeric($task)) return clientError(4);// Return 404 not found
        if(is_string($task)) return clientError(0,$task);// Return the error message if data is missing
        return  successResponse(1,new TaskResource($task));
    }

}
