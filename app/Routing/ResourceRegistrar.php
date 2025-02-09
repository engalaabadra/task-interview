<?php

namespace App\Routing;

use Illuminate\Routing\ResourceRegistrar as OriginalRegistrar;

class ResourceRegistrar extends OriginalRegistrar
{
    /**
     * The default actions for a resourceful controller.
     *
     * @var array
     */
    public $resourceDefaults = ['index', 'create', 'store', 'search', 'trash', 'restoreAll', 'restore', 'show', 'edit', 'update', 'changeActivate', 'destroyMany', 'forceDelete',  'forceDeleteMany', 'destroy'];
    // add data to the array
    public function registerCustomResource($name, $controller, $options = [])
    {
        // add custom resource methods.
        $this->addResourceSearch($name, $controller, $options);
        $this->addResourceTrash($name, $controller, $options);
        $this->addResourceRestoreAll($name, $controller, $options);
        $this->addResourceRestore($name, $controller, $options);
        $this->addResourceChangeActivate($name, $controller, $options);
        $this->addResourceDestroyMany($name, $controller, $options);
        $this->addResourceForceDelete($name, $controller, $options);
        $this->addResourceForceDeleteMany($name, $controller, $options);
    }

     /**
     * Add the search method for a resourceful route.
     *
     * @param  string  $name
     * @param  string  $controller
     * @param  array   $options
     * @return \Illuminate\Support\Facades\Route
     */
    public function addResourceSearch($name, $controller, $options)
    {
        $uri = $this->getResourceUri($name).'/search';

        $action = $this->getResourceAction($name, $controller, 'search', $options);

        return $this->router->get($uri, $action);
    }
    
    /**
     * Add the trash method for a resourceful route.
     *
     * @param  string  $name
     * @param  string  $controller
     * @param  array   $options
     * @return \Illuminate\Support\Facades\Route
     */
    public function addResourceTrash($name, $controller, $options)
    {
        $uri = $this->getResourceUri($name).'/trash';
        $action = $this->getResourceAction($name, $controller, 'trash', $options);
        return $this->router->get($uri, $action);
    }
    /**
     * Add the RestoreAll method for a resourceful route.
     *
     * @param  string  $name
     * @param  string  $controller
     * @param  array   $options
     * @return \Illuminate\Support\Facades\Route
     */
    public function addResourceRestoreAll($name, $controller, $options)
    {
        $uri = $this->getResourceUri($name).'/restore-all';

        $action = $this->getResourceAction($name, $controller, 'restoreAll', $options);

        return $this->router->get($uri, $action);
    }
    /**
     * Add the Restore method for a resourceful route.
     *
     * @param  string  $name
     * @param  string  $controller
     * @param  array   $options
     * @return \Illuminate\Support\Facades\Route
     */
    public function addResourceRestore($name, $controller, $options)
    {
        $uri = $this->getResourceUri($name).'/restore/{id}';

        $action = $this->getResourceAction($name, $controller, 'restore', $options);

        return $this->router->get($uri, $action);
    }

    /**
     * Add the changeActivate method for a resourceful route.
     *
     * @param  string  $name
     * @param  string  $controller
     * @param  array   $options
     * @return \Illuminate\Support\Facades\Route
     */
    public function addResourceChangeActivate($name, $controller, $options)
    {
        $uri = $this->getResourceUri($name).'/activate/{id}';

        $action = $this->getResourceAction($name, $controller, 'changeActivate', $options);

        return $this->router->put($uri, $action);
    }
   
    
     /**
     * Add the destroy many method for a resourceful route.
     *
     * @param  string  $name
     * @param  string  $controller
     * @param  array   $options
     * @return \Illuminate\Support\Facades\Route
     */
    public function addResourceDestroyMany($name, $controller, $options)
    {
        $uri = $this->getResourceUri($name);
        $action = $this->getResourceAction($name, $controller, 'destroyMany', $options);
        return $this->router->delete($uri, $action);
    }
     
    /**
     * Add the ForceDelete method for a resourceful route.
     *
     * @param  string  $name
     * @param  string  $controller
     * @param  array   $options
     * @return \Illuminate\Support\Facades\Route
     */
    public function addResourceForceDelete($name, $controller, $options)
    {
        $uri = $this->getResourceUri($name) . '/force/{id}';
        $action = $this->getResourceAction($name, $controller, 'forceDelete', $options);
        return $this->router->delete($uri, $action);
    }
    /**
     * Add the ForceDelete many method for a resourceful route.
     *
     * @param  string  $name
     * @param  string  $controller
     * @param  array   $options
     * @return \Illuminate\Support\Facades\Route
     */
    public function addResourceForceDeleteMany($name, $controller, $options)
    {
        $uri = $this->getResourceUri($name) . '/force';
        $action = $this->getResourceAction($name, $controller, 'forceDeleteMany', $options);
        return $this->router->delete($uri, $action);
    } 
    
   
}
