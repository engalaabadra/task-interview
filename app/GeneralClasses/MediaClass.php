<?php
namespace App\GeneralClasses;

use Illuminate\Support\Facades\Storage;

class MediaClass{

    /**
     * Generate a filename for storing media.
     *
     * @param \Illuminate\Http\UploadedFile $media
     * @return array
     */
    public function typesThumbnail($media): array
    {
        if (empty($media)) return [];
        $filenameWithExtension = $media->getClientOriginalName();
        $filename = pathinfo($filenameWithExtension, PATHINFO_FILENAME);
        $extension = $media->getClientOriginalExtension();
        $filenameToStore = "{$filename}_" . time() . ".{$extension}";
        return ['filenameToStore' => $filenameToStore];
    }

    /**
     * Handle file upload for a given model and item.
     * 
     * This method checks if a file or multiple files are uploaded. If files are provided, they are stored
     * in the designated folder and associated with the given model/item. If no files are uploaded, the existing
     * files remain unchanged.
     * 
     * @param mixed $fileData The file or files data from the request.
     * @param string $modelName The name of the model associated with the files.
     * @param mixed $item The model instance to which the file(s) will be associated.
     * @return mixed The updated file or files associated with the item.
     */

    public function handleFileUpload($fileData , $folderName, $modelName, $item){
        //Check if an file is uploaded -> will upload files in folder & update or store files in db
        if(request()->hasFile('file') || request()->hasFile('image') || request()->hasFile('cv'))
        {
            $resTypesThumbnail = $this->typesThumbnail($fileData); 
            // Store the file in the 'uploads' directory with a custom name
            $filePathOriginal = $fileData->storeAs('uploads/'.$folderName, $resTypesThumbnail['filenameToStore'],'public');
            $urlFile = str_replace('public/', '', 'storage/'.$filePathOriginal);
            if (!$urlFile) throw new \Exception('File upload failed');
            // in db : Update or create file record associated with the item
            if ($item->file)  $item->file()->update(['url' => $urlFile]);// Update the existing file
            else $item->file()->create(['url' => $urlFile, 'fileable_id' => $item->id, 'fileable_type' => $modelName]);// Create a new file associated with the item
            return ['url' => $urlFile];
        }
        return $item->file;
    }
    /**
     * Handles the deletion of files and their associated database records.
     *
     * This method checks for a direct file or multiple associated files related to the given item
     * and deletes them from both the storage and the database if they exist.
     *
     * @param mixed $item The model instance containing file(s) relationships.
     * @return int The number of deleted files (for multiple files). Returns 0 if no files are deleted.
     */
    public function handleFileDeletion($item)
    {
        // Handle deletion of a single file directly associated with the item
        if ($item->file) {
            $fileItem = $item->file; // Access direct file relationship
            if ($fileItem) {
                $filePath = filePath($fileItem->url);
                // Delete the file from storage if it exists, then delete its database record
                if (Storage::exists($filePath)) {
                    Storage::delete($filePath);
                }
                $fileItem->delete();
            }
        }
        // Handle deletion of multiple files associated with the item
        $deletedCount = 0; // Counter for successfully deleted files
        if ($item->files) {
            $filesItems = $item->files;
            if (count($filesItems) !== 0) {
                foreach ($filesItems as $fileItem) {
                    $filePath = filePath($fileItem->url);
                    // Delete the file from storage if it exists, then delete its database record
                    if (Storage::exists($filePath)) {
                        Storage::delete($filePath);
                        $fileItem->delete();
                        $deletedCount++;
                    }
                }
            }
        }
        // Return the count of deleted files for multiple file deletion
        return $deletedCount;
    }
     
    public function handleMultipleFilesUpload($filesData , $folderName, $modelName, $item){
        //Check if an file is uploaded -> will upload files in folder & update or store files in db
        if(request()->hasFile('files')){
            $storedFiles = [];
            foreach ($filesData as $file) {
                $resTypesThumbnail = $this->typesThumbnail($file);
                // Store the file in the 'uploads' directory with a custom name
                $filePathOriginal =$file->storeAs('uploads/'.$folderName, $resTypesThumbnail['filenameToStore'],'public');
                $urlFile = str_replace('public/', '', 'storage/'.$filePathOriginal);
                if (!$urlFile) throw new \Exception('File upload failed');
                $storedFiles[] = ['url' => $urlFile];
            }
            // store in db
            if ($item->files) {
                // Delete old files and update with new files
                $item->files()->delete();
                $item->files()->createMany($storedFiles);
            } else {
                // Create new files associated with the item
                $item->files()->createMany($storedFiles);
            }
            return $storedFiles;
        }else{
            return $item->files;
        }
    }

    /**
     * Handles the deletion of file and their associated database record.
     *
     * This method checks for a direct file 
     * and deletes them from both the storage and the database if they exist.
     *
     * @param mixed $item The model instance containing file(s) relationships.
     * @return int The number of deleted files (for multiple files). Returns 0 if no files are deleted.
     */
    public function handleFilesDeletion($item, array $fileIds = [])
    {
        $deletedCount = 0;
        // Check if the model has a 'files' relationship before calling it
        if (method_exists($item, 'files') && $item->files()->exists()) {
            $query = $item->files();
            if (!empty($fileIds)) {
                $query->whereIn('id', $fileIds);
            }
            $filesToDelete = $query->get();
            foreach ($filesToDelete as $fileItem) {
                $filePath = filePath($fileItem->url);
                if (Storage::exists($filePath)) {
                    Storage::delete($filePath);
                }
                $fileItem->delete();
                $deletedCount++;
            }
        }

        // Check if the model has a 'file' relationship before calling it
        if (method_exists($item, 'file') && $item->file) {
            if (empty($fileIds) || in_array($item->file->id, $fileIds)) {
                $filePath = filePath($item->file->url);
                if (Storage::exists($filePath)) {
                    Storage::delete($filePath);
                }
                $item->file->delete();
                $deletedCount++;
            }
        }

        return $deletedCount;
    }

}
