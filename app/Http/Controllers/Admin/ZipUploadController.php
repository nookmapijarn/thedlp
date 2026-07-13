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

        // Upload ZIP file to temporary folder inside non-public storage
        $file = $request->file('zip_file');
        $path = storage_path('app/uploads');

        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }
        
        // เก็บไฟล์ในโฟลเดอร์ที่กำหนด
        $file->move($path, 'uploaded.zip');

        // เส้นทางไฟล์ที่เก็บไว้
        $filePath = $path . '/uploaded.zip';
        $extractPath = storage_path('app/uploads/unzipped');
        
        // ตรวจสอบว่ามีโฟลเดอร์นี้อยู่หรือไม่
        if (!File::exists($extractPath)) {
            File::makeDirectory($extractPath, 0755, true);
        } else {
            // ลบไปใน Folder
            File::deleteDirectory($extractPath);
            File::makeDirectory($extractPath, 0755, true);
        }

        try {
            // Extract ZIP file
            $zip = new ZipArchive;
            if ($zip->open($filePath) === TRUE) {

                // ป้องกัน Zip Slip Vulnerability
                for ($i = 0; $i < $zip->numFiles; $i++) {
                    $filename = $zip->getNameIndex($i);
                    if (str_contains($filename, '..') || str_starts_with($filename, '/') || preg_match('/^[a-zA-Z]:/', $filename)) {
                        $zip->close();
                        return response()->json(['success' => false, 'message' => 'ไฟล์ Zip ไม่ปลอดภัย (พบความเสี่ยง Zip Slip)'])
                            ->header('Content-Type', 'application/json')
                            ->header('X-Trigger', 'ajaxComplete');
                    }
                }

                // แตกไฟล์
                $zip->extractTo($extractPath);
                $zip->close();
                
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
                // เก็บใน array Store folder names in an array
                $folderNames = array_map('basename', $directories);

                // loop array และ SET this->sc_code folder app/uploads/unzipped/{{รหัสสถานศึกษา}}/
                foreach ($folderNames as $folderName) {
                    $this->sc_code = $folderName;
                }

                Log::info($directories);
                Log::info('****************'.$this->sc_code.'********************');
                
                // หาระดับชั้น
                $lavellist = [];
                $lavelpath = storage_path("app/uploads/unzipped/{$this->sc_code}");
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

                $reportDetails = [];
                log::info('lavellist : '.json_encode($lavellist));
                foreach($lavellist as $lv){
                    if($lv != 0){
                        $levelResults = $this->processDbfFiles($lv);
                        if (is_array($levelResults)) {
                            $reportDetails = array_merge($reportDetails, $levelResults);
                        }
                    }
                }

                // Process the GROUP.dbf file
                $groupDbfPath = storage_path("app/uploads/unzipped/{$this->sc_code}/group.dbf");
                $groupResult = null;

                // ตรวจสอบว่าไฟล์มีอยู่หรือไม่
                if (File::exists($groupDbfPath)) {
                    $log_lastModified = DB::table('lastmodifiedfile')->where('FILE_NAME', "Group")->first(['LAST_MODIFIED']);
                    $log_lastModified = $log_lastModified ? $log_lastModified->LAST_MODIFIED : null;
                    $lastModifiedTimestamp = File::lastModified($groupDbfPath);
                    $lastModifiedDatetime = Carbon::createFromTimestamp($lastModifiedTimestamp)->format('Y-m-d H:i:s');

                    log::info( 'LAST% FILE TIME : '.$lastModifiedDatetime.' :   LOG TIME :'.$log_lastModified);

                    if($lastModifiedDatetime === $log_lastModified){
                        log::info( 'Model : Group'.' :  Nothing Modified Change !!! :'.$groupDbfPath);
                        $groupResult = [
                            'file' => 'Group',
                            'total' => 0,
                            'processed' => 0,
                            'inserted' => 0,
                            'status' => 'unchanged',
                            'errors' => []
                        ];
                    } else {
                        $res = $this->importDbfData($groupDbfPath, Group::class, null);
                        if ($res) {
                            $res['status'] = 'imported';
                            $groupResult = $res;
                        }
                    }
                } else {
                    // upper case
                    $groupDbfPath = storage_path("app/uploads/unzipped/{$this->sc_code}/GROUP.DBF");
                    
                    if (File::exists($groupDbfPath)){
                        $log_lastModified = DB::table('lastmodifiedfile')->where('FILE_NAME', "Group")->first(['LAST_MODIFIED']);
                        $log_lastModified = $log_lastModified ? $log_lastModified->LAST_MODIFIED : null;
                        $lastModifiedTimestamp = File::lastModified($groupDbfPath);
                        $lastModifiedDatetime = Carbon::createFromTimestamp($lastModifiedTimestamp)->format('Y-m-d H:i:s');
        
                        log::info( 'LAST% FILE TIME : '.$lastModifiedDatetime.' :   LOG TIME :'.$log_lastModified);
        
                        if($lastModifiedDatetime === $log_lastModified){
                            log::info( 'Model : Group'.' :  Nothing Modified Change !!! :'.$groupDbfPath);
                            $groupResult = [
                                'file' => 'Group',
                                'total' => 0,
                                'processed' => 0,
                                'inserted' => 0,
                                'status' => 'unchanged',
                                'errors' => []
                            ];
                        } else {
                            $res = $this->importDbfData($groupDbfPath, Group::class, null);
                            if ($res) {
                                $res['status'] = 'imported';
                                $groupResult = $res;
                            }
                        }
                    } else {
                        log::error( 'File Not Found ? '.$groupDbfPath);
                        $groupResult = [
                            'file' => 'Group',
                            'total' => 0,
                            'processed' => 0,
                            'inserted' => 0,
                            'status' => 'missing',
                            'errors' => ['ไม่พบไฟล์กลุ่มห้องเรียน GROUP.dbf ในระบบ']
                        ];
                    }
                }

                if ($groupResult) {
                    $reportDetails[] = $groupResult;
                }

                return response()->json([
                    'success' => true, 
                    'message' => 'อัพโหลดและนำเข้าข้อมูลเสร็จสมบูรณ์แล้ว!', 
                    'report' => $reportDetails
                ])
                ->header('Content-Type', 'application/json')
                ->header('X-Trigger', 'ajaxComplete');

            } else {
                return response()->json(['success' => false, 'message' => 'ไม่สามารถเปิด Flie Zip ได้กรุณาลองใหม่อีกครั้ง.'])
                ->header('Content-Type', 'application/json')
                ->header('X-Trigger', 'ajaxComplete');
            }
        } catch (\Exception $e) {
            Log::error("Zip upload processing failed: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'เกิดข้อผิดพลาดในการประมวลผลไฟล์: ' . $e->getMessage()])
                ->header('Content-Type', 'application/json')
                ->header('X-Trigger', 'ajaxComplete');
        } finally {
            // ลบโฟลเดอร์ชั่วคราวและไฟล์ ZIP ทันทีหลังจบการทำงาน เพื่อป้องกันความเสี่ยงด้านความปลอดภัย
            if (File::exists($filePath)) {
                File::delete($filePath);
            }
            if (File::exists($extractPath)) {
                File::deleteDirectory($extractPath);
            }
        }
    }

    protected function processDbfFiles($level)
    {
        $files = ['student', 'grade', 'activity', 'schedule', 'subject'];
        $results = [];

        foreach ($files as $file) {

            $dbfPath = storage_path("app/uploads/unzipped/{$this->sc_code}/{$level}/{$file}.dbf");

            // ตรวจสอบว่าไฟล์มีอยู่หรือไม่ก่อนทำการเปลี่ยนแปลงสิทธิ์
            try {
                if(File::exists($dbfPath)){
                    $log_lastModified = DB::table('lastmodifiedfile')->where('FILE_NAME', $file.$level)->first(['LAST_MODIFIED']);
                    $log_lastModified = $log_lastModified ? $log_lastModified->LAST_MODIFIED : null;

                    // Get the last modified time of the file using File facade
                    $lastModifiedTimestamp = File::lastModified($dbfPath);
                    $lastModifiedDatetime = Carbon::createFromTimestamp($lastModifiedTimestamp)->format('Y-m-d H:i:s');

                    log::info( 'LAST% FILE TIME : '.$lastModifiedDatetime.' :   LOG TIME :'.$log_lastModified);

                    if($lastModifiedDatetime === $log_lastModified){
                        log::info( 'Model : '.$file.$level.' :  Nothing Modified Change !!! :'.$dbfPath);
                        $results[] = [
                            'file' => $file . $level,
                            'total' => 0,
                            'processed' => 0,
                            'inserted' => 0,
                            'status' => 'unchanged',
                            'errors' => []
                        ];
                        continue;
                    } else {
                        // get model จากฟังชั้นนี้
                        $model = $this->getModelClass($file, $level);
                        // ใช้ฟังชั่นนี้อ่านข้อมูลและบันทึก
                        $res = $this->importDbfData($dbfPath, $model);
                        if ($res) {
                            $res['status'] = 'imported';
                            $results[] = $res;
                        }
                    }

                } else {
                    // **** กรณีที่ชื่อไฟล์ .dbf เป็นพิมพ์ใหญ่ ****
                    $fileUpper = strtoupper($file);
                    $dbfPath = storage_path("app/uploads/unzipped/{$this->sc_code}/{$level}/{$fileUpper}.DBF");
                    log::info('**** Upper File Name Case *****'. $dbfPath);
                    if(File::exists($dbfPath)){

                        $log_lastModified = DB::table('lastmodifiedfile')->where('FILE_NAME', $file.$level)->first(['LAST_MODIFIED']);
                        $log_lastModified = $log_lastModified ? $log_lastModified->LAST_MODIFIED : null;
    
                        // Get the last modified time of the file using File facade
                        $lastModifiedTimestamp = File::lastModified($dbfPath);
                        $lastModifiedDatetime = Carbon::createFromTimestamp($lastModifiedTimestamp)->format('Y-m-d H:i:s');

                        log::info( 'LAST% FILE TIME : '.$lastModifiedDatetime.' :   LOG TIME :'.$log_lastModified);

                        if($lastModifiedDatetime === $log_lastModified){
                            log::info( 'Model : '.$file.$level.' :  Nothing Modified  Change !!! :'.$dbfPath);
                            $results[] = [
                                'file' => $file . $level,
                                'total' => 0,
                                'processed' => 0,
                                'inserted' => 0,
                                'status' => 'unchanged',
                                'errors' => []
                            ];
                            continue;
                        } else {
                            // get model จากฟังชั้นนี้
                            $model = $this->getModelClass($file, $level);
                            // ใช้ฟังชั่นนี้อ่านข้อมูลและบันทึก
                            $res = $this->importDbfData($dbfPath, $model);
                            if ($res) {
                                $res['status'] = 'imported';
                                $results[] = $res;
                            }
                        }

                    } else {
                        log::error( 'Model : '.$file.$level.' :  DBF does not exist !!! :'.$dbfPath);
                        $results[] = [
                            'file' => $file . $level,
                            'total' => 0,
                            'processed' => 0,
                            'inserted' => 0,
                            'status' => 'missing',
                            'errors' => ["ไม่พบไฟล์ข้อมูล " . $file . ".dbf ของชั้นปีที่ " . $level]
                        ];
                    }
                }

            } catch (\Exception $e) {
                Log::error('path not found: ' . $dbfPath . "errorMessage : ".$e->getMessage());
                $results[] = [
                    'file' => $file . $level,
                    'total' => 0,
                    'processed' => 0,
                    'inserted' => 0,
                    'status' => 'error',
                    'errors' => ["เกิดความล้มเหลวระหว่างการนำเข้าไฟล์: " . $e->getMessage()]
                ];
            }
        }

        return $results;
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
        
        $errorRows = [];
        $totalRecords = 0;
        $processedRecords = 0;
        $insertedRecords = 0;

        // ล้างข้อมูลตารางที่ไม่มี kay ป้องกันค่าซ้ำ
        if(in_array($modelClassName, [
            'Grade1', 'Grade2', 'Grade3', 
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
            $errorRows[] = "ไม่สามารถเปิดไฟล์ตาราง DBF ได้: " . $e->getMessage();
            return [
                'file' => $modelClassName,
                'total' => 0,
                'processed' => 0,
                'inserted' => 0,
                'errors' => $errorRows
            ];
        }
    
        if (class_exists($modelClass)) {
            $tableColumns = [];
            try {
                $tableName = (new $modelClass())->getTable();
                $columnsInfo = DB::select("SHOW COLUMNS FROM `{$tableName}`");
                foreach ($columnsInfo as $col) {
                    $maxLength = null;
                    if (preg_match('/\((\d+)\)/', $col->Type, $matches)) {
                        $maxLength = (int)$matches[1];
                    }
                    $tableColumns[$col->Field] = [
                        'nullable' => ($col->Null === 'YES'),
                        'max_length' => $maxLength,
                        'type' => $col->Type
                    ];
                }
            } catch (\Exception $schemaEx) {
                log::warning("Failed to load schema info for {$modelClassName}: " . $schemaEx->getMessage());
            }

            $batchData = [];
            
            try {
                $counter = 0;
                // เริ่มต้นการอ่าน record จากตำแหน่งแรกของตาราง
                while ($record = $table->nextRecord()) {

                    if ($record === false) {
                        log::error('Error reading record at position ' . $processedRecords . 'record : ' . json_encode($record));
                        $errorRows[] = "แถวที่ " . ($processedRecords + 1) . ": ไม่สามารถอ่านเรคคอร์ดข้อมูลแถวนี้ได้";
                        continue;
                    }

                    $processedRecords++;
                    $convertedData = [];
                    $isRowValid = true;
                    $rowValidationErrors = [];

                    foreach ($fillableFields as $field) {
                        try {
                            $value = $record->$field;
                            
                            $val = null;
                            if (is_string($value)) {
                                if (trim($value) === '') {
                                    $val = null;
                                } elseif (in_array($field, ['fin_date', 'trscp_date', 'fin_date2', 'trn_date2', 'v_recvdate', 'v_repdate', 'v_reqdate', 'v_retdate', 'v_senddate'])) {
                                    $val = $this->convertDate($value) ?? null;
                                } else {
                                    $val = $value;
                                }
                            } elseif (is_null($value)) {
                                $val = null;
                            } else {
                                $val = $value;
                            }

                            // Validate constraints in PHP
                            if (isset($tableColumns[$field])) {
                                $colMeta = $tableColumns[$field];
                                
                                // 1. Null validation
                                if ($val === null && !$colMeta['nullable']) {
                                    $isRowValid = false;
                                    $rowValidationErrors[] = "ฟิลด์ '{$field}' ห้ามเป็นค่าว่าง";
                                }
                                
                                // 2. Max length validation for string types
                                if ($val !== null && is_string($val) && $colMeta['max_length'] !== null) {
                                    if (mb_strlen($val, 'UTF-8') > $colMeta['max_length']) {
                                        $isRowValid = false;
                                        $rowValidationErrors[] = "ฟิลด์ '{$field}' (ข้อมูล: '{$val}') ยาวเกินกำหนด (สูงสุด {$colMeta['max_length']} ตัวอักษร)";
                                    }
                                }
                            }

                            $convertedData[$field] = $val;
                        
                        } catch (\Exception $e) {
                            $isRowValid = false;
                            $rowValidationErrors[] = "ฟิลด์ {$field} เกิดข้อผิดพลาด - " . $e->getMessage();
                        }
                    }

                    if (!$isRowValid) {
                        log::error("Validation failed for {$modelClassName} record {$processedRecords}: " . implode(', ', $rowValidationErrors));
                        $errorRows[] = "แถวที่ {$processedRecords}: ไม่สามารถนำเข้าได้ - " . implode(', ', $rowValidationErrors);
                        continue;
                    }

                    if (!empty($convertedData)) {
                        $batchData[] = $convertedData;
                    } else {
                        log::error('ConvertData is Empty');
                        $errorRows[] = "แถวที่ {$processedRecords}: ไม่มีข้อมูลใดๆ ในฟิลด์ที่กำหนด";
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
                            if (strtoupper($modelClassName) == 'GROUP') {
                                $unique_key = ['GRP_CODE'];
                            }

                            if (empty($unique_key)) {
                                $upsert = $modelClass::insert($batchData) ? 1 : 0;
                            } else {
                                $upsert = $modelClass::upsert($batchData, $unique_key, array_keys($convertedData));
                            }
                            
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
                        } catch (\Exception $e) {
                            log::warning('Model : ' . $modelClassName . ' Batch insert failed, falling back to individual inserts for detailed error capture: ' . $e->getMessage());
                            
                            $unique_key = [];
                            if (in_array($modelClassName, ['Student1', 'Student2', 'Student3'])) {
                                $unique_key = ['STD_CODE'];
                            }
                            if (in_array($modelClassName, ['Subject1', 'Subject2', 'Subject3'])) {
                                $unique_key = ['SUB_CODE'];
                            }
                            if (strtoupper($modelClassName) == 'GROUP') {
                                $unique_key = ['GRP_CODE'];
                            }

                            $startRow = $processedRecords - count($batchData) + 1;
                            foreach ($batchData as $offset => $singleRow) {
                                $currentRowNum = $startRow + $offset;
                                try {
                                    if (empty($unique_key)) {
                                        $modelClass::insert([$singleRow]);
                                    } else {
                                        $modelClass::upsert([$singleRow], $unique_key, array_keys($singleRow));
                                    }
                                    $insertedRecords++;
                                } catch (\Exception $singleEx) {
                                    $rawError = $singleEx->getMessage();
                                    $cleanError = $rawError;
                                    if (str_contains($rawError, 'Column cannot be null') || str_contains($rawError, "cannot be null")) {
                                        $cleanError = "ข้อมูลไม่ครบถ้วน (ฟิลด์ห้ามเป็นค่าว่าง)";
                                    } elseif (str_contains($rawError, 'Data too long') || str_contains($rawError, 'too long')) {
                                        $cleanError = "ข้อมูลมีความยาวเกินขนาดที่กำหนด";
                                    } elseif (str_contains($rawError, 'Duplicate entry') || str_contains($rawError, 'Duplicate key')) {
                                        $cleanError = "มีคีย์ข้อมูลซ้ำซ้อนในระบบ";
                                    } elseif (str_contains($rawError, 'a foreign key constraint fails')) {
                                        $cleanError = "เชื่อมโยงรหัสอ้างอิงไม่ถูกต้อง (Foreign Key Error)";
                                    } else {
                                        $cleanError = preg_replace('/\(SQL:.*\)/Uis', '', $rawError);
                                    }
                                    $errorRows[] = "แถวที่ {$currentRowNum}: ไม่สามารถนำเข้าได้ - " . trim($cleanError);
                                }
                            }

                            // บันทึกเวลา upload สำหรับแถวที่ผ่าน
                            if ($insertedRecords > 0) {
                                $lastModifiedtime = filemtime($dbfPath);
                                $lastModified = [
                                    'file_name' => $modelClassName,
                                    'level' => 0,
                                    'last_modified' => date("Y-m-d H:i:s", $lastModifiedtime),
                                    'uploaded' => date("Y-m-d H:i:s")
                                ];
                                DB::table('lastmodifiedfile')->updateOrInsert(['file_name' => $modelClassName], $lastModified);
                            }
                        }
                        $batchData = [];
                    }
                    $counter++;
                }
            } catch (\Exception $e) {
                log::error('Model : ' . $modelClassName . ' Error processing records: ' . $e->getMessage());
                $errorRows[] = "เกิดความล้มเหลวระหว่างการอ่านข้อมูลแถว: " . $e->getMessage();
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
                    if (strtoupper($modelClassName) == 'GROUP') {
                        $unique_key = ['GRP_CODE'];
                    }

                    log::info('Model : ' . $modelClassName . ' : Final batch insert success');
                    if (empty($unique_key)) {
                        $upsert = $modelClass::insert($batchData) ? 1 : 0;
                    } else {
                        $upsert = $modelClass::upsert($batchData, $unique_key, array_keys($batchData[0]));
                    }
                    
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
                    log::warning('Model : ' . $modelClassName . ' Final batch insert failed, falling back to individual inserts for detailed error capture: ' . $e->getMessage());
                    
                    $unique_key = [];
                    if (in_array($modelClassName, ['Student1', 'Student2', 'Student3'])) {
                        $unique_key = ['STD_CODE'];
                    }
                    if (in_array($modelClassName, ['Subject1', 'Subject2', 'Subject3'])) {
                        $unique_key = ['SUB_CODE'];
                    }
                    if (strtoupper($modelClassName) == 'GROUP') {
                        $unique_key = ['GRP_CODE'];
                    }

                    $startRow = $processedRecords - count($batchData) + 1;
                    foreach ($batchData as $offset => $singleRow) {
                        $currentRowNum = $startRow + $offset;
                        try {
                            if (empty($unique_key)) {
                                $modelClass::insert([$singleRow]);
                            } else {
                                $modelClass::upsert([$singleRow], $unique_key, array_keys($singleRow));
                            }
                            $insertedRecords++;
                        } catch (\Exception $singleEx) {
                            $rawError = $singleEx->getMessage();
                            $cleanError = $rawError;
                            if (str_contains($rawError, 'Column cannot be null') || str_contains($rawError, "cannot be null")) {
                                $cleanError = "ข้อมูลไม่ครบถ้วน (ฟิลด์ห้ามเป็นค่าว่าง)";
                            } elseif (str_contains($rawError, 'Data too long') || str_contains($rawError, 'too long')) {
                                $cleanError = "ข้อมูลมีความยาวเกินขนาดที่กำหนด";
                            } elseif (str_contains($rawError, 'Duplicate entry') || str_contains($rawError, 'Duplicate key')) {
                                $cleanError = "มีคีย์ข้อมูลซ้ำซ้อนในระบบ";
                            } elseif (str_contains($rawError, 'a foreign key constraint fails')) {
                                $cleanError = "เชื่อมโยงรหัสอ้างอิงไม่ถูกต้อง (Foreign Key Error)";
                            } else {
                                $cleanError = preg_replace('/\(SQL:.*\)/Uis', '', $rawError);
                            }
                            $errorRows[] = "แถวที่ {$currentRowNum}: ไม่สามารถนำเข้าได้ - " . trim($cleanError);
                        }
                    }

                    // บันทึกเวลา upload สำหรับแถวที่ผ่าน
                    if ($insertedRecords > 0) {
                        $lastModifiedtime = filemtime($dbfPath);
                        $lastModified = [
                            'file_name' => $modelClassName,
                            'level' => 0,
                            'last_modified' => date("Y-m-d H:i:s", $lastModifiedtime),
                            'uploaded' => date("Y-m-d H:i:s")
                        ];
                        DB::table('lastmodifiedfile')->updateOrInsert(['file_name' => $modelClassName], $lastModified);
                    }
                }
            }
    
            // บันทึกจำนวน record ที่ประมวลผลได้และนำเข้ามาได้สำเร็จ
            log::info('Model'.$modelClass.' Total processed records: ' . $processedRecords);
            log::info('Model'.$modelClass.' Total inserted records: ' . $insertedRecords);

            if ($insertedRecords > 0) {
                // AUDIT LOG
                try {
                    if (\Illuminate\Support\Facades\Schema::hasTable('audit_logs')) {
                        DB::table('audit_logs')->insert([
                            'user_id' => auth()->check() ? auth()->id() : null,
                            'user_name' => auth()->check() ? auth()->user()->name : 'System/Console',
                            'action' => 'import_database',
                            'target_id' => $modelClassName,
                            'target_code' => $modelClassName,
                            'target_name' => 'นำเข้าข้อมูลตาราง ' . $modelClassName . ' สำเร็จ จำนวน ' . $insertedRecords . ' รายการ',
                            'ip_address' => request() ? request()->ip() : '127.0.0.1',
                            'user_agent' => request() ? substr(request()->userAgent(), 0, 255) : 'Console',
                            'created_at' => now(),
                        ]);
                    }
                } catch (\Exception $auditEx) {
                    Log::error("Failed to insert import audit log: " . $auditEx->getMessage());
                }
            }
    
        } else {
            log::error('Cannot use TableReader or ModelClass does not exist!');
            $errorRows[] = "ไม่พบคลาสสำหรับโมเดลนำเข้า: " . $modelClassName;
        }

        if ($totalRecords > $processedRecords) {
            $skipped = $totalRecords - $processedRecords;
            $errorRows[] = "มีแถวข้อมูลจำนวน " . number_format($skipped) . " แถว ถูกละเว้นเนื่องจากเป็นเรคคอร์ดที่ระบุเครื่องหมายลบ (Deleted marker) หรือเป็นแถวว่างตามโครงสร้างดั้งเดิมของไฟล์ DBF";
        }

        return [
            'file' => $modelClassName,
            'total' => $totalRecords,
            'processed' => $processedRecords,
            'inserted' => $insertedRecords,
            'errors' => $errorRows
        ];
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
        $logMessage = sprintf(
            "AUDIT: Admin %s (ID: %d) CLEARED LastModified upload stamps from IP %s, UserAgent: %s",
            auth()->user()->name,
            auth()->id(),
            request()->ip(),
            request()->userAgent()
        );
        Log::info($logMessage);

        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('audit_logs')) {
                DB::table('audit_logs')->insert([
                    'user_id' => auth()->id(),
                    'user_name' => auth()->user()->name,
                    'action' => 'clear_last_modified',
                    'target_id' => 'lastmodifiedfile',
                    'target_code' => 'lastmodifiedfile',
                    'target_name' => 'ล้างประวัติเวลาอัพโหลดไฟล์ทะเบียนล่าาสุด',
                    'ip_address' => request()->ip(),
                    'user_agent' => substr(request()->userAgent(), 0, 255),
                    'created_at' => now(),
                ]);
            }
        } catch (\Exception $e) {
            Log::error("Failed to write clearDateModified audit log: " . $e->getMessage());
        }

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
        $logMessage = sprintf(
            "AUDIT: Admin %s (ID: %d) TRUNCATED ALL DATABASE TABLES (Student, Subject, Grade, Activity, Schedule, Group) from IP %s, UserAgent: %s",
            auth()->user()->name,
            auth()->id(),
            request()->ip(),
            request()->userAgent()
        );
        Log::warning($logMessage);

        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('audit_logs')) {
                DB::table('audit_logs')->insert([
                    'user_id' => auth()->id(),
                    'user_name' => auth()->user()->name,
                    'action' => 'clear_database',
                    'target_id' => 'all',
                    'target_code' => 'ALL_TABLES',
                    'target_name' => 'ล้างข้อมูลตารางทะเบียนและผลการเรียนทั้งหมดของทุกระดับชั้น',
                    'ip_address' => request()->ip(),
                    'user_agent' => substr(request()->userAgent(), 0, 255),
                    'created_at' => now(),
                ]);
            }
        } catch (\Exception $e) {
            Log::error("Failed to write clearTable audit log: " . $e->getMessage());
        }

        $extractPath = storage_path('app/uploads');
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

    /**
     * Re-create the public storage symlink to fix broken paths on production hosts.
     */
    public function fixStorageLink()
    {
        try {
            $link = public_path('storage');
            
            // Log audit log
            try {
                DB::table('audit_logs')->insert([
                    'user_id' => auth()->id(),
                    'action' => 'fix_storage_link',
                    'target_type' => 'system',
                    'target_name' => 'เชื่อมโยงระบบจัดเก็บไฟล์ใหม่ (Fix Storage Symlink)',
                    'ip_address' => request()->ip(),
                    'user_agent' => substr(request()->userAgent(), 0, 255),
                    'created_at' => now(),
                ]);
            } catch (\Exception $e) {
                Log::error("Failed to write fixStorageLink audit log: " . $e->getMessage());
            }

            if (file_exists($link) || is_link($link)) {
                if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                    if (is_dir($link)) {
                        rmdir($link);
                    } else {
                        unlink($link);
                    }
                } else {
                    unlink($link);
                }
            }
            
            \Illuminate\Support\Facades\Artisan::call('storage:link');
            
            return response()->json(['success' => true, 'message' => 'เชื่อมโยงระบบจัดเก็บไฟล์ใหม่ (Fix Storage Symlink) เรียบร้อยแล้ว!'])
                ->header('Content-Type', 'application/json')
                ->header('X-Trigger', 'ajaxComplete');
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'เกิดข้อผิดพลาดในการเชื่อมโยงระบบจัดเก็บไฟล์: ' . $e->getMessage()])
                ->header('Content-Type', 'application/json')
                ->header('X-Trigger', 'ajaxComplete');
        }
    }
}
