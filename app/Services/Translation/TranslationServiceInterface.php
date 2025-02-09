<?php
namespace App\Services\Translation;

interface TranslationService{
     
    public function createTranslations($translations, $newItem, $model);
    
    public function updateTranslations($translations, $newItem, $model);
    public function prepareStoringTranslation(array $transData, $newItem, $model);

}