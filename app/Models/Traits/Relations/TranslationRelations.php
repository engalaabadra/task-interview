<?php

namespace App\Models\Traits\Relations;

trait TranslationRelations
{
    // Fetch all translations related
    public function translations()
    {
        return $this->hasMany(static::class, 'translate_id', 'id')->withoutGlobalScopes();
    }
}
