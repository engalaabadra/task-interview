<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\ActiveScope;
use App\Scopes\LanguageScope;
 

class BaseModel extends Model
{
    //Accessories
    // public function getActiveAttribute()
    // {
    //     return $this->attributes['active'] ?? null;
    // }

    public function getOriginalActiveAttribute()
    {
        return isset($this->attributes['active']) 
            ? trans($this->attributes['active'] ? 'attributes.Active' : 'attributes.Not Active')
            : null;
    }
    //mutators
    //global scopes
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new ActiveScope);
        static::addGlobalScope(new LanguageScope);
    }
}
