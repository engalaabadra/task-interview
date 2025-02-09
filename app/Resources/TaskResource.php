<?php
namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // Dynamically fetch fields from the model's fillable property
        $mainFields = $this->getFillable();
        // Build the resource array based on fillable fields
        $data = [];
        foreach ($mainFields as $field) {
            $data[$field] = $this->{$field};
        }
        // Add additional fields if needed (e.g., appended attributes or relations)
        $data['original_active'] = $this->original_active ?? null;
        $translationData = [];
        if($this->translations){
            // Fetch all translations for this item
            $translationData = $this->getTranslationData($this->profile->translations, \App\Models\Profile::class);
        }

        // Return combined array
        return array_merge($data, ['translations' => $translationData]);
     
    }
}

