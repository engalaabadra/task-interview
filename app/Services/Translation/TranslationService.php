<?php
namespace App\Services\Translation;

class TranslationService{
        /**
     * create translations for the record.
     *
     * @param array $translations
     * @param object $mainItem The main record.
     * @param object $model The model to query.
     * @return void
     * example:
     * $data = [
     *  "lang" => "ar"
     *   "username" => "يوزر1"
     *   "full_name" => "يوزر"
     *   "translate_id" => 90
     *   "email" => "student@nnn.5585000"
     *   "phone_no" => "71115534813410"
     *   "country_id" => "63"
     *   "gender" => null
     *   "birth_date" => null
     *   ]
     */
    public function createTranslations($translations, $mainItem, $model)
    {
        foreach ($translations as $transData) {
            $lang = $transData['lang'];
            // Skip if language is the default language
            if ($lang == config('app.locale')) continue;
            // Check if the language already exists for this item
            $existingTranslation = $model->withoutGlobalScope(\App\Scopes\LanguageScope::class)
                                            ->where('translate_id', $mainItem->id)
                                            ->where('lang', $lang)
                                            ->first();
            if ($existingTranslation) continue;
            // Prepare the data for storing translation
            $data = $this->prepareStoringTranslation($transData, $mainItem, $model);
            // Create the translation
            $model->create($data);
        }
    }
    
    /**
     * update translations for the record.
     *
     * @param array $translations
     * @param object $mainItem The main record.
     * @param object $model The model to query.
     * @return void|string
     * */
    public function updateTranslations($translations, $mainItem, $model)
    {
        // Delete all translations for this item
        // \App\Models\Profile::where('translate_id', $mainItem->id)->get();
        $mainItem->translations()->forceDelete();
        foreach ($translations as $transData) {
            $lang = $transData['lang'];
            // Skip if language is the default language
            if ($lang == config('app.locale')) continue;
            // Prepare the data for storing translation
            $data = $this->prepareStoringTranslation($transData, $mainItem, $model);
            // Create a new translation
            $model->create($data);
        }
    }

    /**
     * prepare Storing Translation.
     *
     * @param array $transData
     * @param object $mainItem
     * @param object $model
     * @return array
     */
    private function prepareStoringTranslation(array $transData, $mainItem, $model)
    {
        // Prepare translation data
        $translationData = array_merge($transData, [
            'translate_id' => $mainItem->id
        ]);
        // Merge excluded fields (non-translatable fields)
        $excludedFields = [];
        foreach ($model::$excludedFields as $field) {
            $excludedFields[$field] = $mainItem->$field;
        }
        //merge exluded fields(from model) with this translation data(from request)
       return array_merge($translationData, $excludedFields);//$translationData : contain data translation that from request , $excludedFields : contain data dont need translation in this model
    }
    
}