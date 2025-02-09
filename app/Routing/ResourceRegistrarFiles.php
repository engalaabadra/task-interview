<?php

namespace App\Routing;

use Illuminate\Routing\ResourceRegistrar as OriginalRegistrar;

class ResourceRegistrarFiles extends OriginalRegistrar
{
    /**
     * The default actions for a resourceful controller.
     *
     * @var array
     */
    public $resourceDefaults = ['uploadFile', 'uploadFiles', 'deleteFile', 'deleteFiles'];
    // add data to the array
    public function registerCustomResource($name, $controller, $options = [])
    {
        // add custom resource methods.
        $this->addResourceUploadFile($name, $controller, $options);
        $this->addResourceUploadFiles($name, $controller, $options);
        $this->addResourceDeleteFile($name, $controller, $options);
        $this->addResourceDeleteFiles($name, $controller, $options);
    }
    /////////////////////////////For Files///////////////////////////////
    /**
     * Add the upload file for a resourceful route.
     *
     * @param  string  $name
     * @param  string  $controller
     * @param  array   $options
     * @return \Illuminate\Support\Facades\Route
     */
    public function addResourceUploadFile($name, $controller, $options)
    {
        $uri = $this->getResourceUri($name) . '/upload-file/{id}';
        $action = $this->getResourceAction($name, $controller, 'uploadFile', $options);
        return $this->router->post($uri, $action);
    }
    /**
     * Add the upload files for a resourceful route.
     *
     * @param  string  $name
     * @param  string  $controller
     * @param  array   $options
     * @return \Illuminate\Support\Facades\Route
     */
    public function addResourceUploadFiles($name, $controller, $options)
    {
        $uri = $this->getResourceUri($name) . '/upload-files/{id}';
        $action = $this->getResourceAction($name, $controller, 'uploadFiles', $options);
        return $this->router->post($uri, $action);
    }
    /**
     * Add the deleteFile method for a resourceful route.
     *
     * @param  string  $name
     * @param  string  $controller
     * @param  array   $options
     * @return \Illuminate\Support\Facades\Route
     */
    public function addResourceDeleteFile($name, $controller, $options)
    {
        $uri = $this->getResourceUri($name).'/delete-file/{id}';
        $action = $this->getResourceAction($name, $controller, 'deleteFile', $options);
        return $this->router->delete($uri, $action);
    }
    /**
     * Add the deleteFiles method for a resourceful route.
     *
     * @param  string  $name
     * @param  string  $controller
     * @param  array   $options
     * @return \Illuminate\Support\Facades\Route
     */
    public function addResourceDeleteFiles($name, $controller, $options)
    {
        $uri = $this->getResourceUri($name).'/delete-files/{id}';

        $action = $this->getResourceAction($name, $controller, 'deleteFiles', $options);

        return $this->router->delete($uri, $action);
    }
    
    
    
}
