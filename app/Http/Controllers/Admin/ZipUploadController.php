<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use XBase\TableReader;
use ZipArchive;
use App\Models\Activity1;
use App\Models\Activity2;
use App\Models\Activity3;
use App\Models\Student1;
use App\Models\Student2;
use App\Models\Student3;
use App\Models\Grade1;
use App\Models\Grade2;
use App\Models\Grade3;
use App\Models\Subject1;
use App\Models\Subject2;
use App\Models\Subject3;
use App\Models\Schedule1;
use App\Models\Schedule2;
use App\Models\Schedule3;
use App\Models\Group;
use Carbon\Carbon;

class ZipUploadController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'zip_file' => 'required|mimes:zip',
        ]);

        // Upload ZIP file to temporary folder
        $file = $request->file('zip_file');
        $path = $file->storeAs('uploads', 'uploaded.zip');

        if (!Storage::exists($path)) {
            return redirect()->route('upload.form')->with('error', 'Failed to upload file.');
        }

        // Extract ZIP file
        $zip = new ZipArchive;
        if ($zip->open(Storage::path($path)) === TRUE) {
            $extractPath = storage_path('app/uploads/unzipped');
            $zip->extractTo($extractPath);
            $zip->close();
            Storage::delete($path);

            // Process .dbf files
            for ($level = 1; $level <= 3; $level++) {
                $this->processDbfFiles($extractPath, $level);
            }

            // Process the GROUP.dbf file
            $groupDbfPath = "$extractPath/1215040001/GROUP.dbf";
            if (File::exists($groupDbfPath)) {
                $this->importDbfData($groupDbfPath, Group::class);
            }

            // Delete extracted folder
            //File::deleteDirectory($extractPath);


            return view('admin.upload')->with('success', 'Files uploaded and data imported successfully.');
        } else {
            return view('admin.upload')->with('error', 'Failed to extract ZIP file.');
        }
    }

    protected function processDbfFiles($extractPath, $level)
    {
        $files = ['student', 'activity', 'grade', 'subject', 'schedule'];
        foreach ($files as $file) {
            $dbfPath = "$extractPath/1215040001/$level/{$file}.dbf";
            if (File::exists($dbfPath)) {
                $modelClass = $this->getModelClass($file, $level);
                if ($modelClass) {
                    $this->importDbfData($dbfPath, $modelClass);
                }
            }
        }
    }

    protected function getModelClass($file, $level)
    {
        $modelClasses = [
            'student' => [Student1::class, Student2::class, Student3::class],
            'activity' => [Activity1::class, Activity2::class, Activity3::class],
            'grade' => [Grade1::class, Grade2::class, Grade3::class],
            'subject' => [Subject1::class, Subject2::class, Subject3::class],
            'schedule' => [Schedule1::class, Schedule2::class, Schedule3::class],
        ];

        return $modelClasses[$file][$level - 1] ?? null;
    }

    protected function importDbfData($dbfPath, $modelClass)
    {
        // Load DBF file using XBase\Table
        $dbf = new TableReader($dbfPath);
        // if (class_exists($modelClass)) {
        //     // Get fillable fields from the model
        //     $fillableFields = (new $modelClass())->getFillable();
            
        //     while ($record = $table->nextRecord()) {
        //         // Display the record for debugging purposes
        //         // echo "ID = ".$record->ID .'<br>';
        //         // echo "STD_CODE = ".$record->STD_CODE .'<br>';
        //         // echo "NAME = ".$record->NAME .'<br>';
        //         // echo "SURNAME = ".$record->SURNAME .'<br>';
    
        //         // Prepare data array with fillable fields
        //         $data = [];
        //         foreach ($fillableFields as $field) {
        //             //echo $field." => ".$record->$field.'<br>';  
        //             if ($field == 'CREATED_AT' || $field == 'UPDATED_AT') {
        //                 //echo 'STD_CODE = 55555'.' <br>';
        //                 // $data[$field] = null;
        //                 continue;
        //             }else{
        //                 //echo 'NOT STD_CODE <br>';
        //                 $data[$field] = $record->$field;
        //             }
        //         }

        //         echo '$data = '.json_encode($data).'<br>';
    
        //         // Check if STD_CODE is set and not null
        //         // if (isset($data['STD_CODE']) && $data['STD_CODE'] !== null) {
        //         //     // Upsert the data
        //         //     $modelClass::upsert(
        //         //         [$data],
        //         //         ['STD_CODE', 'ID'], // Unique keys
        //         //         array_keys($data)    // Columns to update
        //         //     );
        //         // } else {
        //         //     // Handle the case where STD_CODE is missing or null
        //         //     echo "STD_CODE is missing or null for record with STD_CODE = " . $record->STD_CODE . "<br>";
        //         // }
        //     }
        // }
    }
    
      
}
