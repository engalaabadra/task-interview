<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BaseResource extends JsonResource
{
    /**
    *  Fetch all translations for this item -> to use it rendering in resource
    * @param $translations
    * @param $model
    * @return array
    * like this array: 
    * [
    *   'lang' => $translation->lang,
    *   'username' => $translation->username,
    *   'full_name' => $translation->full_name,
    *   'nick_name' => $translation->nick_name,
    *   'address' => $translation->address
    *   ];
     */
    public function getTranslationData($translations, $model){
        // Fetch all translations for this item
        if (!$translations) {
            return [];
        }
        return $translations->map(function ($translation) use ($model) {
           // Access the $translationFields array dynamically
           $fields = $model::$translationFields;
           // Map only the fields specified in $translationFields
           $translationFields = [];
           foreach ($fields as $field) {
               // Add fields translations
               $translationFields[$field] = $translation->{$field};
           }
           // Add lang field to spasific record trans
           $translationFields['lang'] = $translation->lang;
           // Return the mapped data
           return $translationFields;
       })->toArray();
   }
}
