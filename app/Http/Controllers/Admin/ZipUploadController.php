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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;


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
        ini_set('max_execution_time', '40960M');

        $request->validate([
            'zip_file' => 'required|mimes:zip|max:60960',
        ], [
            'zip_file.required' => 'โปรดอัพโหลดไฟล์ Zip เท่านั้น!',
            'zip_file.mimes' => 'โปรดอัพโหลดไฟล์ Zip เท่านั้น!',
            'zip_file.max' => 'ขนาดไฟล์ต้องไม่เกิน 60 MB !! กรณีที่สถานศึกษาขนาดใหญ่ ให้นำเข้าทีละระดับแทน',
        ]);

        // Upload ZIP file to temporary folder
        $file = $request->file('zip_file');
        $path = public_path('storage/uploads');//$file->storeAs('uploads', 'uploaded.zip');

        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }
        
        // เก็บไฟล์ในโฟลเดอร์ที่กำหนด
        $file->move($path, 'uploaded.zip');

        // เส้นทางไฟล์ที่เก็บไว้
        $filePath = $path . '/uploaded.zip';

        // เส้นทางไฟล์ที่ต้องแตก
        $filePath = public_path('storage/uploads/uploaded.zip');
        $extractPath = public_path('storage/uploads/unzipped');
        
        // ตรวจสอบว่ามีโฟลเดอร์นี้อยู่หรือไม่
        if (!File::exists($extractPath)) {
            File::makeDirectory($extractPath, 0755, true);
        } else {
            // ลบไปใน Folder
            File::deleteDirectory($extractPath, 0755, true);
            //File::makeDirectory($extractPath, 0755, true);
        }

        // Extract ZIP file
        $zip = new ZipArchive;
        if ($zip->open($filePath) === TRUE) {

            //$extractPath =  storage_path('app/uploads/unzipped/');//Storage::url($path);


            // แตกไฟล์
            $zip->extractTo($extractPath);
            $zip->close();
            // ลบไฟล์ zip
            File::delete($filePath);
            
            Log::info('**********extractPath**********'.$extractPath);

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
            Log::info('****************'.$this->sc_code.'********************');
            
            // หาระดับชั้น
            $lavellist = [];
            $lavelpath = public_path("storage/uploads/unzipped/{$this->sc_code}");
            Log::info('**************** Lavel path : '."{$lavelpath}");
            $allLavel = glob($lavelpath. '/*', GLOB_ONLYDIR);
            
            if(count($allLavel) != 0){
                foreach ($allLavel as $lavel) {
                    $lavelName = basename($lavel);
                    if (is_dir($lavel) && preg_match('/^\d{1}$/', $lavelName)) {
                        $lavellist[] = $lavelName;
                    }
                }
            } else {
                return response()->json(['success' => false, 'message' => 'ไม่พบ Folder ระดับชั้น.'])
                ->header('Content-Type', 'application/json')
                ->header('X-Trigger', 'ajaxComplete');           
            }

            log::info('lavellist : '.json_encode($lavellist));
            foreach($lavellist as $lv){
                if($lv != 0){
                    $this->processDbfFiles($lv);
                }
            }

            // Process the GROUP.dbf file
            $groupDbfPath = public_path("storage/uploads/unzipped/{$this->sc_code}/group.DBF");

            // ตรวจสอบว่าไฟล์มีอยู่หรือไม่ก่อนทำการเปลี่ยนแปลงสิทธิ์
            if (File::exists($groupDbfPath)) {

                $log_lastModified = DB::table('lastmodifiedfile')->where('FILE_NAME', "Group")->first(['LAST_MODIFIED']);
                $log_lastModified = $log_lastModified->LAST_MODIFIED;
                $lastModifiedTimestamp = File::lastModified($groupDbfPath);
                $lastModifiedDatetime = Carbon::createFromTimestamp($lastModifiedTimestamp)->format('Y-m-d H:i:s');

                log::info( 'LAST% FILE TIME : '.$lastModifiedDatetime.' :   LOG TIME :'.$log_lastModified);

                if($lastModifiedDatetime === $log_lastModified){
                    log::info( 'Model : Group'.' :  Nothing Modified Change !!! :'.$groupDbfPath);
                } else {
                    $this->importDbfData($groupDbfPath, Group::class, null);
                }
            } else {
                // upper case
                $groupDbfPath = public_path("storage/uploads/unzipped/{$this->sc_code}/GROUP.DBF");
                
                if (!File::exists($groupDbfPath)){
                    log::info( 'File Not Found ? '.$groupDbfPath);
                }

                $log_lastModified = DB::table('lastmodifiedfile')->where('FILE_NAME', "Group")->first(['LAST_MODIFIED']);
                $log_lastModified = $log_lastModified->LAST_MODIFIED;
                $lastModifiedTimestamp = File::lastModified($groupDbfPath);
                $lastModifiedDatetime = Carbon::createFromTimestamp($lastModifiedTimestamp)->format('Y-m-d H:i:s');

                log::info( 'LAST% FILE TIME : '.$lastModifiedDatetime.' :   LOG TIME :'.$log_lastModified);

                if($lastModifiedDatetime === $log_lastModified){
                    log::info( 'Model : Group'.' :  Nothing Modified Change !!! :'.$groupDbfPath);
                } else {
                    $this->importDbfData($groupDbfPath, Group::class, null);
                }
            }
            
            // Delete extracted folder
            File::deleteDirectory($extractPath, 0755, true);

            return response()->json(['success' => true, 'message' => 'Upload สำเร็จ กรุณา reload '])
            ->header('Content-Type', 'application/json')
            ->header('X-Trigger', 'ajaxComplete');


        } else {

            // Delete extracted folder
            File::deleteDirectory($extractPath, 0755, true);

            return response()->json(['success' => false, 'message' => 'ไม่สามารถเปิด Flie Zip ได้กรุณาลองใหม่อีกครั้ง.'])
            ->header('Content-Type', 'application/json')
            ->header('X-Trigger', 'ajaxComplete');
        }
    }

    protected function processDbfFiles($level)
    {
        $files = ['student', 'grade', 'activity', 'schedule', 'subject'];

        foreach ($files as $file) {

            $dbfPath = public_path("storage/uploads/unzipped/{$this->sc_code}/{$level}/{$file}.dbf");//"{$extractPath}/{$this->sc_code}/{$level}/{$file}.dbf";//"{$extractPath}/{$sc_code}/$level/{$file}.dbf";

            // ตรวจสอบว่าไฟล์มีอยู่หรือไม่ก่อนทำการเปลี่ยนแปลงสิทธิ์
            try {
                // ใช้ File::chmod() เพื่อเปลี่ยนแปลงสิทธิ์ไฟล์
                if(File::exists($dbfPath)){
                    // File::chmod($dbfPath, 0777, true);
                    // $lastmodified = File::lastModified($dbfPath);
                    // Log::info('Unlock $dbfPath : ' . $dbfPath );
                    // Log::info('File Time  : ' . $lastmodified );
                    $log_lastModified = DB::table('lastmodifiedfile')->where('FILE_NAME', $file.$level)->first(['LAST_MODIFIED']);
                    $log_lastModified = $log_lastModified->LAST_MODIFIED;

                    // Get the last modified time of the file using File facade
                    $lastModifiedTimestamp = File::lastModified($dbfPath);
                    $lastModifiedDatetime = Carbon::createFromTimestamp($lastModifiedTimestamp)->format('Y-m-d H:i:s');

                    log::info( 'LAST% FILE TIME : '.$lastModifiedDatetime.' :   LOG TIME :'.$log_lastModified);

                    if($lastModifiedDatetime === $log_lastModified){
                        log::info( 'Model : '.$file.$level.' :  Nothing Modified Change !!! :'.$dbfPath);
                        continue;
                    } else {
                        // get model จากฟังชั้นนี้
                        $model = $this->getModelClass($file, $level);
                        // ใช้ฟังชั่นนี้อ่านข้อมูลและบันทึก
                        $this->importDbfData($dbfPath, $model);
                    }

                } else {
                    // **** กรณีที่ชื่อไฟล์ .dbf เป็นพิมพ์ใหญ่ ****
                    $file = strtoupper($file);
                    $dbfPath = public_path("storage/uploads/unzipped/{$this->sc_code}/{$level}/{$file}.DBF");
                    log::info('**** Upper File Name Case *****'. $dbfPath);
                    // File::chmod($dbfPath, 0777, true);
                    // Log::info('Unlock $dbfPath : ' . $dbfPath );
                    // Log::info('File Time  : ' . $lastmodified );
                    if(File::exists($dbfPath)){

                        $log_lastModified = DB::table('lastmodifiedfile')->where('FILE_NAME', $file.$level)->first(['LAST_MODIFIED']);
                        $log_lastModified = $log_lastModified->LAST_MODIFIED;
    
                        // Get the last modified time of the file using File facade
                        $lastModifiedTimestamp = File::lastModified($dbfPath);
                        $lastModifiedDatetime = Carbon::createFromTimestamp($lastModifiedTimestamp)->format('Y-m-d H:i:s');

                        log::info( 'LAST% FILE TIME : '.$lastModifiedDatetime.' :   LOG TIME :'.$log_lastModified);

                        if($lastModifiedDatetime === $log_lastModified){
                            log::info( 'Model : '.$file.$level.' :  Nothing Modified  Change !!! :'.$dbfPath);
                            continue;
                        } else {
                            // get model จากฟังชั้นนี้
                            $model = $this->getModelClass($file, $level);
                            // ใช้ฟังชั่นนี้อ่านข้อมูลและบันทึก
                            $this->importDbfData($dbfPath, $model);
                        }

                    } else {
                        log::error( 'Model : '.$file.$level.' :  DBF dose exist !!! :'.$dbfPath);
                    }
                }

            } catch (\Exception $e) {
                Log::error('path not found: ' . $dbfPath . "errorMessage : ".$e->getMessage());
            }
        }

    }

    protected function getModelClass($file, $level)
    {
        $file = strtolower($file);
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
        log::info('Model : ' . $modelClass . ' : Starting ImportData >>> ' . "Path : " . $dbfPath);
    
        $modelClassName = class_basename($modelClass);
        $fillableFields = (new $modelClass())->getFillable();

        // ล้างข้อมูลตารางที่ไม่มี kay ป้องกันค่าซ้ำ
        if(in_array($modelClassName, [
            'Student1', 'Student2', 'Student3', 
            'Activity1', 'Activity2', 'Activity3', 
            'Schedule1', 'Schedule2', 'Schedule3'])) {
                if($modelClass::truncate()){
                    log::info($modelClass." truncate success");
                }
            }
    
        try {
            // โหลดไฟล์ DBF โดยใช้ XBase\TableReader
            $table = new TableReader(
                $dbfPath,
                [
                    'encoding' => 'TIS-620', // encoding tis-620 => utf8
                    'columns' => $fillableFields
                ]
            );

            // นับจำนวน record ทั้งหมดในไฟล์ .dbf
            $totalRecords = $table->getRecordCount();
            log::info('Model'.$modelClass.' Total records in DBF file: ' . $totalRecords);
    
        } catch (\Exception $e) {
            // บันทึกข้อผิดพลาดลงใน log
            Log::error('Error loading DBF table: ' . $e->getMessage());
            return; // หยุดการทำงานถ้าเกิดข้อผิดพลาด
        }
    
        if (class_exists($modelClass)) {
            $batchData = [];
            $processedRecords = 0;
            $insertedRecords = 0;
            
            try {
                $counter = 0;
                // เริ่มต้นการอ่าน record จากตำแหน่งแรกของตาราง
                while ($record = $table->nextRecord()) {

                    if ($record === false) {
                        log::error('Error reading record at position ' . $processedRecords . 'record : ' . json_encode($record));
                        continue;
                    }

                    $processedRecords++;
                    $convertedData = [];

                    foreach ($fillableFields as $field) {
                        try {
                            $value = $record->$field;
                            
                            if (is_string($value)) {
                                if (trim($value) === '') {
                                    $convertedData[$field] = null;
                                    continue;
                                } elseif (in_array($field, ['fin_date', 'trscp_date', 'fin_date2', 'trn_date2', 'v_recvdate', 'v_repdate', 'v_reqdate', 'v_retdate', 'v_senddate'])) {
                                    $convertedData[$field] = $this->convertDate($value) ?? null;
                                    continue;
                                } else {
                                    $convertedData[$field] = $value;
                                    continue;
                                }
                            } elseif (is_null($value)) {
                                $convertedData[$field] = null;
                                continue;
                            } elseif (is_numeric($value)) {
                                $convertedData[$field] = $value;
                                continue;
                            }
                        
                        } catch (\Exception $e) {
                            log::error('Error processing field ' . $field . ': ' . $e->getMessage());
                        }
                    }

                    if (!empty($convertedData)) {
                        $batchData[] = $convertedData;
                    } else {
                        log::error('ConvertData is Empty');
                    }
    
                    if (count($batchData) >= 100) {
                        try {
                            $unique_key = [];
                            if (in_array($modelClassName, ['Student1', 'Student2', 'Student3'])) {
                                $unique_key = ['STD_CODE'];
                            }
                            if (in_array($modelClassName, ['Subject1', 'Subject2', 'Subject3'])) {
                                $unique_key = ['SUB_CODE'];
                            }
                            if ($modelClassName == 'GROUP') {
                                $unique_key = ['GRP_CODE'];
                            }

                            $upsert = $modelClass::upsert($batchData, $unique_key, array_keys($convertedData));
                            $insertedRecords += count($batchData);
                            $lastModifiedtime = filemtime($dbfPath);
                            
                            // บันทึกเวลา  upload
                            if ($upsert > 0) {
                                $lastModified = [
                                    'file_name' => $modelClassName,
                                    'level' => 0,
                                    'last_modified' => date("Y-m-d H:i:s", $lastModifiedtime),
                                    'uploaded' => date("Y-m-d H:i:s")
                                ];
                                $save = DB::table('lastmodifiedfile')->updateOrInsert(['file_name' => $modelClassName], $lastModified);
                                log::info('Model : ' . $modelClassName . ' บันทึกการอัพเดท ' . date("Y-m-d H:i:s"));
                            } else {
                                $lastModified = [
                                    'file_name' => $modelClassName,
                                    'level' => 0,
                                    'last_modified' => date("Y-m-d H:i:s", '0000-00-00 00:00:00'),
                                    'uploaded' => date("Y-m-d H:i:s", '0000-00-00 00:00:00')
                                ];
                                $save = DB::table('lastmodifiedfile')->updateOrInsert(['file_name' => $modelClassName], $lastModified);
                                log::error('Model : ' . $modelClassName . ' ไม่บันทึกการอัพเดท ' . date("Y-m-d H:i:s"));
                            }
                            //log::info('Model : ' . $modelClassName . ' Batch insert to avoid memory exhaustion...');
                        } catch (\Exception $e) {
                            log::error('Model : ' . $modelClassName . ' Batch insert error: ' . $e->getMessage());
                        }
                        $batchData = [];
                    }
                    $counter++;
                }
            } catch (\Exception $e) {
                log::error('Model : ' . $modelClassName . ' Error processing records: ' . $e->getMessage());
            }
    
            // Insert Final batch insert remaining batch data
            if (!empty($batchData)) {
                try {
                    $unique_key = [];
                    if (in_array($modelClassName, ['Student1', 'Student2', 'Student3'])) {
                        $unique_key = ['STD_CODE'];
                    }
                    if (in_array($modelClassName, ['Subject1', 'Subject2', 'Subject3'])) {
                        $unique_key = ['SUB_CODE'];
                    }
                    if ($modelClassName == 'GROUP') {
                        $unique_key = ['GRP_CODE'];
                    }

                    log::info('Model : ' . $modelClassName . ' : Final batch insert success');
                    $upsert = $modelClass::upsert($batchData, $unique_key, array_keys($batchData[0]));
                    $insertedRecords += count($batchData);
                    $lastModifiedtime = filemtime($dbfPath);
    
                    // บันทึกเวลา upload
                    if ($upsert > 0) {
                        $lastModified = [
                            'file_name' => $modelClassName,
                            'level' => 0,
                            'last_modified' => date("Y-m-d H:i:s", $lastModifiedtime),
                            'uploaded' => date("Y-m-d H:i:s")
                        ];
                        $save = DB::table('lastmodifiedfile')->updateOrInsert(['file_name' => $modelClassName], $lastModified);
                        log::info('Model : ' . $modelClassName . ' บันทึกการอัพเดท ' . date("Y-m-d H:i:s"));
                    } else {
                        $lastModified = [
                            'file_name' => $modelClassName,
                            'level' => 0,
                            'last_modified' => date("Y-m-d H:i:s", '0000-00-00 00:00:00'),
                            'uploaded' => date("Y-m-d H:i:s", '0000-00-00 00:00:00')
                        ];
                        $save = DB::table('lastmodifiedfile')->updateOrInsert(['file_name' => $modelClassName], $lastModified);
                        log::error('Model : ' . $modelClassName . ' ไม่บันทึกการอัพเดท ' . date("Y-m-d H:i:s"));
                    }
                } catch (\Exception $e) {
                    log::error('Model : ' . $modelClassName . ' Final batch insert error: ' . $e->getMessage());
                }
            }
    
            // บันทึกจำนวน record ที่ประมวลผลได้และนำเข้ามาได้สำเร็จ
            log::info('Model'.$modelClass.' Total processed records: ' . $processedRecords);
            log::info('Model'.$modelClass.' Total inserted records: ' . $insertedRecords);
    
        } else {
            log::error('Cannot use TableReader or ModelClass does not exist!');
        }
    }
    
    
    protected function convertDate($date)
    {
        if ($date instanceof \DateTime) {
            return $date->format('Y-m-d');
        }
    
        $date = trim($date);
        if (empty($date) || $date == '0000-00-00' || $date == '0000-00-00 00:00:00') {
            log::info("return date Null");
            return null;
        }
        try {
            return date('Y-m-d', strtotime($date));
        } catch (\Exception $e) {
            log::info("return date Null");
            return null;
        }
    }
    
    protected function clearDateModifiled(){

        DB::table('lastmodifiedfile')->truncate();

        $files = [
            'Student1', 'Student2', 'Student3',
            'Grade1', 'Grade2', 'Grade3',
            'Activity1', 'Activity2', 'Activity3',
            'Schedule1', 'Schedule2', 'Schedule3',
            'Subject1', 'Subject2', 'Subject3',
            'Group'
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
        }
        return response()->json(['success' => true, 'message' => 'ลบค่าในตาราง Lastmodifiled แล้ว.'])
        ->header('Content-Type', 'application/json')
        ->header('X-Trigger', 'ajaxComplete');
    }

    protected function clearTable() {

        $extractPath = public_path('storage/uploads');
        File::deleteDirectory($extractPath);

        Student1::truncate();
        Student2::truncate();
        Student3::truncate();

        Subject1::truncate();
        Subject2::truncate();
        Subject3::truncate();

        Grade1::truncate();
        Grade2::truncate();
        Grade3::truncate();

        Activity1::truncate();
        Activity2::truncate();
        Activity3::truncate();

        Schedule1::truncate();
        Schedule2::truncate();
        Schedule3::truncate();

        Group::truncate();
        DB::table('lastmodifiedfile')->truncate();

        $files = [
            'Student1', 'Student2', 'Student3',
            'Grade1', 'Grade2', 'Grade3',
            'Activity1', 'Activity2', 'Activity3',
            'Schedule1', 'Schedule2', 'Schedule3',
            'Subject1', 'Subject2', 'Subject3',
            'Group'
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
        }

        return response()->json(['success' => true, 'message' => 'ล้างข้อมูลสำเร็จ กรุณา reload หน้าเว็บใหม่.'])
        ->header('Content-Type', 'application/json')
        ->header('X-Trigger', 'ajaxComplete');
    }
}
