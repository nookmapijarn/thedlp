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
use App\Models\LastModifiedFile;
use Illuminate\Support\Carbon;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class ZipUploadController extends Controller
{
    public $sc_code;

    public function index(){
        $lastmodified = [];
        $lastmodified = DB::table('lastmodifiedfile')->orderByDesc('UPLOADED')->get();
        return view('admin.upload', compact('lastmodified'));
    }

    public function upload(Request $request)
    {
        ini_set('max_execution_time', '2048M');

        $lastmodified = [];

        $request->validate([
            'zip_file' => 'required|mimes:zip',
        ],[
            'zip_file' => 'โปรดอัพโหลดไฟล์ Zip เท่านั้น !',
        ]);

        // Upload ZIP file to temporary folder
        $file = $request->file('zip_file');
        $path = $file->storeAs('uploads', 'uploaded.zip');

        if (!Storage::exists($path)) {
            return view('admin.upload', compact('student'))->with('error', 'Failed to upload file.');
        }

        // Extract ZIP file
        $zip = new ZipArchive;
        if ($zip->open(Storage::path($path)) === TRUE) {

            $extractPath = storage_path('app/uploads/unzipped/');

            // ลบไปใน Folder
            File::deleteDirectory($extractPath);

            // แตกไฟล์
            $zip->extractTo($extractPath);
            $zip->close();
            Storage::delete($path);

            // Get list of folders in the unzipped directory
            $directories = [];
            $allDirectories = glob($extractPath . '/*', GLOB_ONLYDIR);
            
            // วน loop หา folder ที่มีเลข 10 หลัก
            foreach ($allDirectories as $dir) {
                $dirName = basename($dir);
                if (is_dir($dir) && preg_match('/^\d{10}$/', $dirName)) {
                    $directories[] = $dirName;
                }
            }
            // กำใน array Store folder names in an array
            $folderNames = array_map('basename', $directories);

            
            // loop array และ SET this->S_CODE folder app/uploads/unzipped/{{รหัสสถานศึกษา}}/
            foreach ($folderNames as $folderName) {
                $this->sc_code = $folderName;
                // echo "<br><br> S_CODE : ".$folderName . "<br>";
            }

            Log::info($directories);
            Log::info('****************************************************'.$this->sc_code.'********************');
            
            // หาระดับชั้น
            $lavellist = [];
            // echo 'path : '.$extractPath.$this->sc_code.'<br>';
            $allLavel = glob($extractPath.$this->sc_code. '/*', GLOB_ONLYDIR);

            // ทำการแก้ไข
            foreach ($allLavel as $lavel) {
                $lavellist[] = basename($lavel);
            }

            //print_r($lavellist);

            // Process .dbf files
            foreach($lavellist as $lv){
                // echo 'LAVEL ***** '.$lv.'<br>';
                if($lv != 0){
                    $this->processDbfFiles($extractPath, $lv, $this->sc_code);
                }
            }
            // for ($level = 1; $level <= 3; $level++) {
            //     $this->processDbfFiles($extractPath, $level, $this->sc_code);
            // }

            // Process the GROUP.dbf file
            $groupDbfPath = "{$extractPath}{$this->sc_code}/GROUP.dbf";

            // ตรวจสอบว่าไฟล์มีอยู่หรือไม่ก่อนทำการเปลี่ยนแปลงสิทธิ์
            if (File::exists($groupDbfPath)) {
                // ใช้ File::chmod() เพื่อเปลี่ยนแปลงสิทธิ์ไฟล์
                if (File::chmod($groupDbfPath, 0777, true)) {
                    Log::info('Unlock $GroupdbfPath : ' . $groupDbfPath );
                } else {
                    Log::error('Failed to unlock $GroupdbfPath : ' . $groupDbfPath );
                }
            } else {
                Log::error('File not found: ' . $groupDbfPath);
            }

            $log_lastModified = DB::table('lastmodifiedfile')->where('FILE_NAME', 'Group')->first(['LAST_MODIFIED']);
            $lastModifiedtime = filemtime($groupDbfPath);
            $lastModifiedtime = date("Y-m-d H:i:s", $lastModifiedtime);

            if (File::exists($groupDbfPath) && $log_lastModified->LAST_MODIFIED !== $lastModifiedtime) {
                $this->importDbfData($groupDbfPath, Group::class, null);
                File::deleteDirectory($extractPath);
            }

            // Delete extracted folder
            File::deleteDirectory($extractPath);

            $lastmodified = DB::table('lastmodifiedfile')->get();

            return response()->json(['success' => true, 'message' => 'Upload สำเร็จ กรุณา reload '])
            ->header('Content-Type', 'application/json')
            ->header('X-Trigger', 'ajaxComplete');
            // return view('admin.upload', compact('lastmodified'))->with('success', 'Files uploaded and data imported successfully.');
            // $this->index();

        } else {
            return response()->json(['success' => false, 'message' => 'ไม่สามารถเปิด Flie Zip ได้กรุณาลองใหม่อีกครั้ง.'])
            ->header('Content-Type', 'application/json')
            ->header('X-Trigger', 'ajaxComplete');
        }
        // $this->index();
        // return view('admin.upload', compact('lastmodified'))->with('error', 'Failed to extract ZIP file.');
    }

    protected function processDbfFiles($extractPath, $level, $sc_code)
    {
        $files = ['student', 'grade', 'activity', 'schedule', 'subject'];
        $processedFiles = 0;

        foreach ($files as $file) {

            $dbfPath = "{$extractPath}{$sc_code}/$level/{$file}.dbf";

            // ตรวจสอบว่าไฟล์มีอยู่หรือไม่ก่อนทำการเปลี่ยนแปลงสิทธิ์
            if (File::exists($dbfPath)) {
                // ใช้ File::chmod() เพื่อเปลี่ยนแปลงสิทธิ์ไฟล์
                if (File::chmod($dbfPath, 0777, true)) {
                    Log::info('Unlock $dbfPath : ' . $dbfPath );
                } else {
                    Log::error('Failed to unlock $dbfPath : ' . $dbfPath);
                }
            } else {
                Log::error('File not found: ' . $dbfPath);
            }

            $log_lastModified = DB::table('lastmodifiedfile')->where('FILE_NAME', $file.$level)->first(['LAST_MODIFIED']);
            $lastModifiedtime = filemtime($dbfPath);
            $lastModifiedtime = date("Y-m-d H:i:s", $lastModifiedtime);
            // echo 'lastModified : '.'LAST_MODIF_LOG : '.$log_lastModified->LAST_MODIFIED.' || '.'LAST_MODIF_FILE : '.$lastModifiedtime.'<br>';

            if (File::exists($dbfPath) && $log_lastModified->LAST_MODIFIED !== $lastModifiedtime) {
                // echo 'Model : '.$file.$level.' : Starting ImportData >>> <br>';
                $model = $this->getModelClass($file, $level);

                // ล้างข้อมูล
                if($file == 'grade' || $file == 'activity' || $file == 'schedule'){$model::truncate();} 

                $this->importDbfData($dbfPath, $model, $level);
            } else {
                // echo 'Model : '.$file.$level.' :  Nothing ImportData !!! <br>';
            }
        }

        return $processedFiles;
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

    protected function importDbfData($dbfPath, $modelClass, $level)
    {

        $modelClassName = class_basename($modelClass);
        $fillableFields = (new $modelClass())->getFillable();

        //Load DBF file using XBase\TableReader
        $dbf = new TableReader(
            $dbfPath,
            [
                'encoding' => 'TIS-620', // encoding tis-620 => utf8
                'columns' => $fillableFields
            ]
        );

        if (class_exists($modelClass)) {

            $batchData = [];

            try {
                $counter = 0;
                while ($record = $dbf->nextRecord()) {
                    // echo '<br>Model ******** '.$modelClassName.'************<br>';
                    $convertedData = [];
                    foreach ($fillableFields as $field) {

                        $value = $record->$field;

                        // if($field == 'SUB_NAME'){
                        //     echo '<br>*******************************************'.$value.'********************************<br> ';
                        // }
                        if ($field == 'fin_date' || $field == 'trscp_date' || $field == 'fin_date2' || $field == 'trn_date2' || $field == 'v_recvdate'|| $field == 'v_repdate' || $field == 'v_reqdate' || $field == 'v_repdate' || $field == 'v_retdate' || $field ==  'v_senddate') {
                            if($this->convertDate($value) == null){
                                $convertedData[$field] = null;
                                //echo 'date null'.$field.'<br>';
                                continue;
                            }else{
                                $convertedData[$field] = $value;
                                //echo 'date value'.$field.'<br>';
                                continue;
                            }
                        }
                        if ($value == null || $value == " " || $value == '') {
                            //echo 'null Field'.$field.' Value = '.$value.'<br>';
                            $convertedData[$field] = null;
                            continue;
                        } else {
                            //echo 'Have Field = '.$field.' Value = '.$value. '<br>';
                            // echo '<br><br>iconv = '.mb_convert_encoding($value, 'UTF-8', 'auto').'<br><br>';
                            $convertedData[$field] = $value;
                        }

                    }

                    if (!empty($convertedData)) {
                        $batchData[] = $convertedData;
                    }

                    // Batch insert to avoid memory exhaustion
                    if (count($batchData) >= 100) {
                        try {                   
                            $unique_key = [];
                            if($modelClassName == 'Student1'||$modelClassName == 'Student2'||$modelClassName == 'Student3' ){
                                $unique_key = ['STD_CODE'];
                                // echo 'uni key';
                            }
                            if($modelClassName == 'Subject1'||$modelClassName == 'Subject2'||$modelClassName == 'Subject3' ){
                                $unique_key = ['SUB_CODE'];
                                // echo 'uni key';
                            }
                            if($modelClassName == 'GROUP'){
                                $unique_key = ['GRP_CODE'];
                               // echo 'uni key';
                            }
                            $modelClass::upsert($batchData, $unique_key, array_keys($convertedData));
                            // echo ('Model : '.$modelClassName.' Batch insert to avoid memory exhaustion... '.'<br>');
                        } catch (\Exception $e) {
                            // echo ('Model : '.$modelClassName.' Batch insert error: ' . $e->getMessage()).'<br>';
                        }
                        $batchData = [];
                    }
                    $counter++;
                }
            } catch (\Exception $e) {

                //echo ('Model : '.$modelClassName.' Error processing records: ' . $e->getMessage()).'<br>';
            }

            // Insert remaining batch data
            if (!empty($batchData)) {
                try {
                    $unique_key = [];
                    if($modelClassName == 'Student1'||$modelClassName == 'Student2'||$modelClassName == 'Student3' ){
                        $unique_key = ['STD_CODE'];
                        // echo 'uni key';
                    }
                    if($modelClassName == 'Subject1'||$modelClassName == 'Subject2'||$modelClassName == 'Subject3' ){
                        $unique_key = ['SUB_CODE'];
                        // echo 'uni key';
                    }
                    if($modelClassName == 'GROUP'){
                        $unique_key = ['GRP_CODE'];
                        // echo 'uni key';
                    }
                    //echo ('Model : '.$modelClassName.' : Final batch insert success <br>');
                    $upsert = $modelClass::upsert($batchData, $unique_key, array_keys($batchData[0]));
                    $lastModifiedtime = filemtime($dbfPath);

                    // บันทึกเวลา upload
                    if($upsert > 0){
                        $lastModified = [
                            'file_name' => $modelClassName,
                            'level' => 0,
                            'last_modified' => date("Y-m-d H:i:s", $lastModifiedtime),
                            'uploaded' => date("Y-m-d H:i:s")
                        ];
                        $save = DB::table('lastmodifiedfile')->updateOrInsert(['file_name' => $modelClassName], $lastModified);
                        // echo ('Model : '.$modelClassName.' บันทึกการอัพเดท '.date("Y-m-d H:i:s").' <br><br><br>');
                    } else {
                        $lastModified = [
                            'file_name' => $modelClassName,
                            'level' => 0,
                            'last_modified' => date("Y-m-d H:i:s", '0000-00-00 00:00:00'),
                            'uploaded' => date("Y-m-d H:i:s", '0000-00-00 00:00:00')
                        ];
                        $save = DB::table('lastmodifiedfile')->updateOrInsert(['file_name' => $modelClassName], $lastModified);
                        // echo ('Model : '.$modelClassName.' ไม่บันทึกการอัพเดท '.date("Y-m-d H:i:s").' <br><br><br>');
                    }
                } catch (\Exception $e) {
                    //echo ('Model : '.$modelClassName.'<br><br><br> Final batch insert error: ' . $e->getMessage()).'<br>';
                }
            }
        }
    }

    protected function convertDate($date)
    {
        if ($date instanceof \DateTime) {
            return $date->format('Y-m-d');
        }
    
        $date = trim($date);
        if (empty($date) || $date == '0000-00-00' || $date == '0000-00-00 00:00:00') {
            return null;
        }
        try {
            return date('Y-m-d', strtotime($date));
        } catch (\Exception $e) {
            return null;
        }
    }
    
    protected function clearTable() {

        $extractPath = storage_path('app/uploads/unzipped/');
        File::deleteDirectory($extractPath);

        Student1::truncate();
        Student2::truncate();
        Student3::truncate();
        Subject1::truncate();
        Subject2::truncate();
        Subject3::truncate();
        Group::truncate();
        DB::table('lastmodifiedfile')->truncate();

        $files = [
            'Student1', 'Student2', 'Student3',
            'Grade1', 'Grade2', 'Grade3',
            'Activity1', 'Activity2', 'Activity3',
            'Schedule1', 'Schedule2', 'Schedule3',
            'Subject1', 'Subject2', 'Subject3',
            'group'
        ];

        foreach ($files as $file) {
    
            $lastModified = [
                'FILE_NAME' => $file,
                'LEVEL' => 0,
                'LAST_MODIFIED' => null, // หรือค่าที่เหมาะสมสำหรับคุณ
                'UPLOADED' => null // วันที่และเวลาปัจจุบัน
            ];

            

            $save = DB::table('lastmodifiedfile')->updateOrInsert(
                ['FILE_NAME' => $file],
                $lastModified
            );

            // if ($save) {
            //     echo '************************************************** lastModifiedreset **************************<br>';
            // } else {
            //     echo '************************************************** NOT lastModifiedreset **************************<br>';
            // }
        }
        // Group
        $glastModified = [
            'FILE_NAME' => 'group',
            'LEVEL' => 0,
            'LAST_MODIFIED' => null, // หรือค่าที่เหมาะสมสำหรับคุณ
            'UPLOADED' => null // วันที่และเวลาปัจจุบัน
        ];

        DB::table('lastmodifiedfile')->updateOrInsert(
            ['FILE_NAME' => 'group'],
            $glastModified
        );

        //return view('admin.upload', compact('$lastmodified'));
        return response()->json(['success' => true, 'message' => 'ล้างข้อมูลสำเร็จ กรุณา reload หน้าเว็บใหม่.'])
        ->header('Content-Type', 'application/json')
        ->header('X-Trigger', 'ajaxComplete');
    }
}
