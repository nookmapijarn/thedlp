<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use PHPUnit\TextUI\XmlConfiguration\Group;
use XBase\TableReader;
use ZipArchive;

class ZipUploadController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'zip_file' => 'required|mimes:zip',
        ]);

        // อัปโหลดไฟล์ไปยังโฟลเดอร์ temporary
        $file = $request->file('zip_file');
        $path = $file->storeAs('uploads', 'uploaded.zip');

        if (!Storage::exists($path)) {
            return redirect()->route('upload.form')->with('error', 'Failed to upload file.');
        }

        // แตกไฟล์ ZIP
        $zip = new ZipArchive;
        if ($zip->open(Storage::path($path)) === TRUE) {
            $extractPath = storage_path('app/uploads/unzipped');
            $zip->extractTo($extractPath);
            $zip->close();
            Storage::delete($path);

            // ประมวลผลไฟล์ .dbf ในแต่ละระดับ
            for ($level = 1; $level <= 3; $level++) {
                $this->processDbfFiles($extractPath, $level);
            }

            // ลบโฟลเดอร์ที่แตกไฟล์ออก
            File::deleteDirectory($extractPath);

            return redirect()->route('upload.form')->with('success', 'Files uploaded and migration/model created successfully.');
        } else {
            return redirect()->route('upload.form')->with('error', 'Failed to extract ZIP file.');
        }
    }

    protected function processDbfFiles($extractPath, $level)
    {
        $files = ['student', 'activity', 'grade', 'subject', 'schedule'];
        foreach ($files as $file) {
            $dbfPath = "$extractPath/1215040001/$level/$file.dbf";
            if (File::exists($dbfPath)) {
                $this->createMigrationAndModel($dbfPath, $file, $level);
            }
        }
        $dbfGroupPath = "$extractPath/1215040001/GROUP.dbf";
        $this->createMigrationAndModel($dbfGroupPath, 'group', '');
    }

    protected function createMigrationAndModel($dbfPath, $fileName, $level)
    {
        $table = new TableReader($dbfPath);
        $columns = [];
    
        foreach ($table->getColumns() as $column) {
            $columns[] = [
                'name' => strtoupper($column->getName()), // ใช้ getName() เพื่อเข้าถึงชื่อคอลัมน์
                'type' => $column->getType(),
                'length' => $column->getLength(),
            ];
        }
    
        $tableName = "{$fileName}{$level}";
        $this->createMigration($columns, $tableName);
        $this->createModel($columns, $tableName);
    }
    

    protected function createMigration($columns, $tableName)
    {
        $migrationName = 'create_' . Str::snake($tableName) . '_table';
        $className = 'Create' . Str::studly($tableName) . 'Table';
        $fileName = date('Y_m_d_His') . '_' . $migrationName . '.php';

        $migrationPath = database_path('migrations/' . $fileName);
        $fields = '';

        foreach ($columns as $column) {
            $fieldType = $this->mapFieldType($column);
            $fields .= "\$table->$fieldType('{$column['name']}');\n";
        }

        $migrationTemplate = <<<EOT
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class $className extends Migration
{
    public function up()
    {
        Schema::create('$tableName', function (Blueprint \$table) {
            \$table->id();
            $fields
            \$table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('$tableName');
    }
}
EOT;

        File::put($migrationPath, $migrationTemplate);
    }

    protected function createModel($columns, $tableName)
    {
        $modelName = Str::studly($tableName);
        $modelPath = app_path("Models/$modelName.php");

        $modelTemplate = <<<EOT
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class $modelName extends Model
{
    use HasFactory;

    protected \$table = '$tableName';
    protected \$fillable = [\n";

EOT;

        foreach ($columns as $column) {
            $modelTemplate .= "        '{$column['name']}',\n";
        }

        $modelTemplate .= <<<EOT
    ];
}
EOT;

        File::put($modelPath, $modelTemplate);
    }

    protected function mapFieldType($column)
    {
        // Mapping ของประเภทข้อมูลจาก .dbf เป็นประเภทข้อมูลใน Laravel Migration
        switch ($column['type']) {
            case 'C':
                return "string('{$column['name']}', {$column['length']})";
            case 'N':
                return "integer('{$column['name']}')";
            case 'L':
                return "boolean('{$column['name']}')";
            case 'D':
                return "date('{$column['name']}')";
            case 'M':
                return "text('{$column['name']}')";
            default:
                return "string('{$column['name']}')";
        }
    }
}

