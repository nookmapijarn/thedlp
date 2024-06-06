<?php

namespace App\Http\Controllers\Teachers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Jenssegers\Agent\Agent;
use App\Providers\CustomServiceProvider;


class TeachersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function __construct(CustomServiceProvider $customService)
    // {
    //     $customService->setSemestry(66/2);
    // }
    public function index(Request $request)
    {   
        // จำนวนนักศึกษา
        $labels = [];
        $data_student = [];
        $data_old_student = [];
        $data_new_student = [];
        //  เพศ
        $current_semestry = $this->get_semestry(1, 'DESC')[0];
        $count_student_male = $this->current_student($current_semestry)->where('GENDER', '=', 1)->count();
        $count_student_female = $this->current_student($current_semestry)->where('GENDER', '=', 2)->count();
        $count_student_monk = $this->current_student($current_semestry)->where('GENDER', '=', 3)->count();
        // จังหวัด
        $province = $this->province();
        // ตำบล
        $all_tumbon = DB::table('grade')
        ->join('group', 'grade.GRP_CODE', '=', 'group.GRP_CODE')
        ->where('grade.SEMESTRY', '=', $current_semestry)
        ->select('group.GRP_CODE', 'group.GRP_NAME', 'group.GRP_ADVIS')
        ->groupBy('group.GRP_CODE', 'group.GRP_NAME', 'group.GRP_ADVIS')
        ->get();
        $student_tumbon = [];
        //echo 'JSON'.$province;
        // $all_semestry = DB::table('grade')->select('SEMESTRY')->groupBy('SEMESTRY')->orderBy('SEMESTRY', 'DESC')->get();
        // $semestry = $all_semestry->first()->SEMESTRY;
        // echo "++++++++ ".$this->current_student($semestry)->Count();

       // SEMESTRY Labels
       $agent = new Agent();  
       if( $agent->isMobile()){
        $labels = $this->get_semestry(6, 'DESC');
       }else{
        $labels = $this->get_semestry(12, 'DESC'); 
       }   

       
       foreach($labels as $key=>$val) {
            $index = 0;
            // จำนวนนักศึกษาทั้งหมด
            $allstudent = $this->current_student($val)->Count();
            array_push($data_student, $allstudent);
            // จำนวนนักศึกษาใหม่
            $new_student = $this->new_student($val)->Count();
            array_push($data_new_student, $new_student);
            // จำนวนนักศึกษาเก่า
            $old_student = $allstudent - $new_student;
            array_push($data_old_student, $old_student);
            $index++;   
        }

        // จำนวน นศ. รายตำบล
        // foreach($all_tumbon as $tb) {
        //     $student_count = [];
        //     for ($i = 1; $i <= 3; $i++) {
        //         $count_s = $this->current_student($current_semestry, $i)->Count();
        //         array_push($student_count, [
        //             'ST'.$i => $count_s
        //         ]);
        //     }
        //     array_push($student_tumbon, [
        //         'GRP' => $tb,
        //         'STUDENT' => $student_count
        //     ]);
        // }
        foreach ($all_tumbon as $tb) {
            $student_count = [];
            for ($i = 1; $i <= 3; $i++) {
                $count_s = $this->current_student($current_semestry, $i, $tb->GRP_CODE)->count();
                $student_count['ST'.$i] = $count_s;
            }
            $student_tumbon[] = [
                'GRP' => $tb,
                'STUDENT' => $student_count
            ];
        }
        $student_tumbon = json_decode(json_encode($student_tumbon));

        return view('teachers.tdashboard', compact('labels', 'data_student', 'data_new_student', 'data_old_student', 'province', 'count_student_male', 'count_student_female', 'count_student_monk', 'all_tumbon', 'student_tumbon', 'current_semestry'));
    }

    public function current_student($semestry, $lv='', $grp_code=''){
        $grade_table = 'grade' . $lv;

        $query = DB::table($grade_table)
            ->join('student', $grade_table . '.STD_CODE', '=', 'student.STD_CODE')
            ->where($grade_table . '.SEMESTRY', '=', $semestry);
        
        if (!empty($grp_code)) {
            $query->where($grade_table . '.GRP_CODE', '=', $grp_code);
        }
        
        $g = $query
            ->select('student.STD_CODE', 'student.GENDER', $grade_table . '.GRP_CODE')
            ->groupBy('student.STD_CODE', 'student.GENDER', $grade_table . '.GRP_CODE')
            ->get();
        
        return $g;
        
    }
    
    public function new_student($semestry){
        $ID = str_replace('/','',$semestry);
        $s = DB::table('student')
        ->where('ID', 'regexp', $ID.'[0-9]')
        ->select('ID')
        ->orderBy('ID', 'ASC')
        ->groupBy('ID')
        ->get();
        return $s;
    }

    public function old_student($semestry){
        $ID = str_replace('/','',$semestry);
        $s = DB::table('student')
        ->where('ID', 'regexp', $ID.'[0-9]')
        ->select('ID')
        ->orderBy('ID', 'ASC')
        ->groupBy('ID')
        ->get();
        return $s;
    }

    public function get_semestry($limit, $sort){
        $sem = DB::table('grade')
        ->select('SEMESTRY')
        ->orderBy('SEMESTRY', $sort)
        ->groupBy('SEMESTRY')
        ->take($limit)
        ->get();

        $isem = [];

        foreach($sem as $s){
            array_push($isem, $s->SEMESTRY);
        }
        return  array_reverse($isem);
    }

    public function province(){
        $semestry = DB::table('grade')->select('SEMESTRY')->groupBy('SEMESTRY')->orderBy('SEMESTRY', 'DESC')->get()->first()->SEMESTRY;
        $student_adds = DB::table('grade')->join('student', 'grade'.'.STD_CODE', '=', 'student.STD_CODE')
                            ->where('grade'.'.SEMESTRY', '=', $semestry)
                            ->select('student.STD_CODE', 'student.ZIPCODE')
                            ->groupBy('student.STD_CODE', 'student.ZIPCODE')
                            ->get();
        // echo $student_adds;
        // $cm = $student_adds->where('ZIPCODE', '>=', 00000)->where('ZIPCODE', '<', 00000)->count();// เชียงใหม่
        // $pg = DB::table('student')->select('ZIPCODE')->where('ZIPCODE', 'regexp', '^101')->count();   // พังงา
        // $st = DB::table('student')->select('ZIPCODE')->where('ZIPCODE', 'regexp', '^101')->count();   // สุราชธานี
        // $kr = DB::table('student')->select('ZIPCODE')->where('ZIPCODE', 'regexp', '^101')->count();   // กระบี่
        // $sa = DB::table('student')->select('ZIPCODE')->where('ZIPCODE', 'regexp', '^101')->count();   // สตูน
        // $tg = DB::table('student')->select('ZIPCODE')->where('ZIPCODE', 'regexp', '^101')->count();   // ตรัง
        // $tt = DB::table('student')->select('ZIPCODE')->where('ZIPCODE', 'regexp', '^101')->count();   // ตราด
        // $pl = DB::table('student')->select('ZIPCODE')->where('ZIPCODE', 'regexp', '^101')->count();   // พัทลุง
        // $ps = DB::table('student')->select('ZIPCODE')->where('ZIPCODE', 'regexp', '^101')->count();   // พิษณูโลก
        // $kp = DB::table('student')->select('ZIPCODE')->where('ZIPCODE', 'regexp', '^101')->count();  // กำแพงเพชร
        // $pc = DB::table('student')->select('ZIPCODE')->where('ZIPCODE', 'regexp', '^101')->count();  // พิจิตร
        $sh = $student_adds->where('ZIPCODE', '>=', 72000)->where('ZIPCODE', '<', 73000)->count();  // สพรรณบุรี
        $at = $student_adds->where('ZIPCODE', '>=', 14000)->where('ZIPCODE', '<', 15000)->count();  // อ่างทอง
        $lb = $student_adds->where('ZIPCODE', '>=', 15000)->where('ZIPCODE', '<', 16000)->count();  // ลพบุรี
        $pa = $student_adds->where('ZIPCODE', '>=', 13000)->where('ZIPCODE', '<', 14000)->count();  // พระนครศรีอยุธยา
        // $np = DB::table('student')->select('ZIPCODE')->where('ZIPCODE', 'regexp', '^101')->count(); // นครปฐม
        $sb = $student_adds->where('ZIPCODE', '>=', 16000)->where('ZIPCODE', '<', 17000)->count();  // สิงห์บุรี
        $cn = $student_adds->where('ZIPCODE', '>=', 17000)->where('ZIPCODE', '<', 18000)->count();  // ชัยนาท
        $bm = $student_adds->where('ZIPCODE', '>=', 10000)->where('ZIPCODE', '<', 11000)->count();  // กรุงเทพ bangkok metropolis
        $pt = $student_adds->where('ZIPCODE', '>=', 12000)->where('ZIPCODE', '<', 13000)->count();  // ปทุมธานี
        // $no = DB::table('student')->select('ZIPCODE')->where('ZIPCODE', 'regexp', '^101')->count();  // นนทบุรี
        // $sp = DB::table('student')->select('ZIPCODE')->where('ZIPCODE', 'regexp', '^101')->count();  // สุมทรปราการ
        // $cr = DB::table('student')->select('ZIPCODE')->where('ZIPCODE', 'regexp', '^101')->count();  // เชียงงราย
        // $sm = DB::table('student')->select('ZIPCODE')->where('ZIPCODE', 'regexp', '^101')->count(); // สมุทรสงคราม
        // $pe = DB::table('student')->select('ZIPCODE')->where('ZIPCODE', 'regexp', '^101')->count();  // เพชรบุรี
        // $cc = DB::table('student')->select('ZIPCODE')->where('ZIPCODE', 'regexp', '^101')->count();  // ฉะเชิงเทรา
        // $nn = DB::table('student')->select('ZIPCODE')->where('ZIPCODE', 'regexp', '^101')->count();  // นครนายก
        $cb = $student_adds->where('ZIPCODE', '>=', 20000)->where('ZIPCODE', '<', 15000)->count();  // ชลบุรี
        // $br = DB::table('student')->select('ZIPCODE')->where('ZIPCODE', 'regexp', '^101')->count();  // บุรีรัมย์
        // ["hc-key" => "th-kk", "value" => 30],  // ขอนแก่น
        // ["hc-key" => "th-ph", "value" => 31],  // เพชรบูรณ์
        // ["hc-key" => "th-kl", "value" => 32],  // กาฬสินธุ์
        // ["hc-key" => "th-sr", "value" => 33],  // สระบุรี
        // ["hc-key" => "th-nr", "value" => 34],  // นครราชสีมา
        // ["hc-key" => "th-si", "value" => 35],  // ศรีสะเกษ
        // ["hc-key" => "th-re", "value" => 36],  // ร้อยเอ็ด
        // ["hc-key" => "th-le", "value" => 37],  // เลย
        // ["hc-key" => "th-nk", "value" => 38],  // หนองคาย
        // ["hc-key" => "th-ac", "value" => 39],  // อำนาจเจริญ
        // ["hc-key" => "th-md", "value" => 40],  // มุกดาหาร
        // ["hc-key" => "th-sn", "value" => 41],  // สกลนคร
        // ["hc-key" => "th-nw", "value" => 42],  // นาราธิวาส
        // ["hc-key" => "th-pi", "value" => 43],  // ปัตตานี
        // ["hc-key" => "th-rn", "value" => 44],  // ระนอง
        // ["hc-key" => "th-nt", "value" => 45],  // นครศรีธรรมราช
        // ["hc-key" => "th-sg", "value" => 46],  // สงขลา
        // ["hc-key" => "th-pr", "value" => 47],  // แพร่
        // ["hc-key" => "th-py", "value" => 48],  // พะเยา
        // ["hc-key" => "th-so", "value" => 49],  // สุโขทัย
        // ["hc-key" => "th-ud", "value" => 50],  // อุตรดิตถ์
        // ["hc-key" => "th-kn", "value" => 51],  // กาญจณบุรี
        // ["hc-key" => "th-tk", "value" => 52],  // ตาก
        // ["hc-key" => "th-ut", "value" => 53],   // อุทัยธานี
        // ["hc-key" => "th-ns", "value" => 54],   // นครสวรรค์
        // ["hc-key" => "th-pk", "value" => 55],   // ประจวบคีรีขันธ์
        // ["hc-key" => "th-ur", "value" => 56],   // อุบลราชธานี
        // ["hc-key" => "th-sk", "value" => 57],   // สระแก้ว
        // ["hc-key" => "th-ry", "value" => 58],   // ระยอง
        // ["hc-key" => "th-cy", "value" => 59],   // ชัยภูมิ
        // ["hc-key" => "th-su", "value" => 60],   // สุรินทร์
        // ["hc-key" => "th-nf", "value" => 61],   // นครพนม
        // ["hc-key" => "th-bk", "value" => 62],   // บึงกาฬ
        // ["hc-key" => "th-mh", "value" => 63],   // แม่ฮ่องสอน
        // ["hc-key" => "th-pu", "value" => 64],   // ภูเก็ต
        // ["hc-key" => "th-cp", "value" => 65],   // ชุมพร
        // ["hc-key" => "th-yl", "value" => 66],   // ยะลา
        // ["hc-key" => "th-ct", "value" => 67],   // จันทบุรี
        // ["hc-key" => "th-ln", "value" => 68],   // ลำพูน
        // ["hc-key" => "th-na", "value" => 69],   // น่าน
        // ["hc-key" => "th-lg", "value" => 70],   // ลำปาง
        // ["hc-key" => "th-pb", "value" => 71],   // ปราจีนบุรี
        // ["hc-key" => "th-rt", "value" => 72],   // ราชบุรี
        // ["hc-key" => "th-ys", "value" => 73],   // ยโสธร
        // ["hc-key" => "th-ms", "value" => 74],   // มหาสารคาม
        // ["hc-key" => "th-un", "value" => 75],   // อุบลราชธานี
        // ["hc-key" => "th-nb", "value" => 76],   // หนองบัวลำพู
        // ["hc-key" => "th-ss", "value" => 77]    // สมุทรสาคร
        
        $data = [
            ["hc-key" => "th-cm", "name" => 'เชียงใหม่', "value" => 0],   // เชียงใหม่
            ["hc-key" => "th-pg", "name" => 'พังงา', "value" => 0],   // พังงา
            ["hc-key" => "th-st", "name" => 'สุราชธานี', "value" => 0],   // สุราชธานี
            ["hc-key" => "th-kr", "name" => 'กระบี่', "value" => 0],   // กระบี่
            ["hc-key" => "th-sa", "name" => 'สตูน', "value" => 0],   // สตูน
            ["hc-key" => "th-tg", "name" => 'ตรัง', "value" => 0],   // ตรัง
            ["hc-key" => "th-tt", "name" => 'ตราด', "value" => 0],   // ตราด
            ["hc-key" => "th-pl", "name" => 'พัทลุง', "value" => 0],   // พัทลุง
            ["hc-key" => "th-ps", "name" => 'พิษณูโลก', "value" => 0],   // พิษณูโลก
            ["hc-key" => "th-kp", "name" => 'กำแพงเพชร', "value" => 0],  // กำแพงเพชร
            ["hc-key" => "th-pc", "name" => 'พิจิตร', "value" => 0],  // พิจิตร
            ["hc-key" => "th-sh", "name" => 'สพรรณบุรี', "value" => $sh],  // สพรรณบุรี
            ["hc-key" => "th-at", "name" => 'อ่างทอง', "value" => $at],  // อ่างทอง
            ["hc-key" => "th-lb", "name" => 'ลพบุรี', "value" => $lb],  // ลพบุรี
            ["hc-key" => "th-pa", "name" => 'พระนครศรีอยุธยา', "value" => $pa],  // พระนครศรีอยุธยา
            ["hc-key" => "th-np", "name" => 'นครปฐม', "value" => 0],  // นครปฐม
            ["hc-key" => "th-sb", "name" => 'สิงห์บุรี', "value" => $sb],  // สิงห์บุรี
            ["hc-key" => "th-cn", "name" => 'ชัยนาท', "value" => $cn],  // ชัยนาท
            ["hc-key" => "th-bm", "name" => 'กรุงเทพ', "value" => $bm],  // กรุงเทพ bangkok metropolis
            ["hc-key" => "th-pt", "name" => 'ปทุมธานี', "value" => $pt],  // ปทุมธานี
            ["hc-key" => "th-no", "name" => 'นนทบุรี', "value" => 0],  // นนทบุรี
            ["hc-key" => "th-sp", "name" => 'สุมทรปราการ', "value" => 0],  // สุมทรปราการ
            ["hc-key" => "th-cr", "name" => 'เชียงงราย', "value" => 0],  // เชียงงราย
            ["hc-key" => "th-sm", "name" => 'สมุทรสงคราม', "value" => 0],  // สมุทรสงคราม
            ["hc-key" => "th-pe", "name" => 'เพชรบุรี', "value" => 0],  // เพชรบุรี
            ["hc-key" => "th-cc", "name" => 'ฉะเชิงเทรา', "value" => 0],  // ฉะเชิงเทรา
            ["hc-key" => "th-nn", "name" => 'นครนายก', "value" => 0],  // นครนายก
            ["hc-key" => "th-cb", "name" => 'ชลบุรี', "value" => 0],  // ชลบุรี
            ["hc-key" => "th-br", "name" => 'บุรีรัมย์', "value" => 0],  // บุรีรัมย์
            ["hc-key" => "th-kk", "name" => 'ขอนแก่น', "value" => 0],  // ขอนแก่น
            ["hc-key" => "th-ph", "name" => 'เพชรบูรณ์', "value" => 0],  // เพชรบูรณ์
            ["hc-key" => "th-kl", "name" => 'กาฬสินธุ์', "value" => 0],  // กาฬสินธุ์
            ["hc-key" => "th-sr", "name" => 'สระบุรี', "value" => 0],  // สระบุรี
            ["hc-key" => "th-nr", "name" => 'นครราชสีมา', "value" => 0],  // นครราชสีมา
            ["hc-key" => "th-si", "name" => 'ศรีสะเกษ', "value" => 0],  // ศรีสะเกษ
            ["hc-key" => "th-re", "name" => 'ร้อยเอ็ด', "value" => 0],  // ร้อยเอ็ด
            ["hc-key" => "th-le", "name" => 'เลย', "value" => 0],  // เลย
            ["hc-key" => "th-nk", "name" => 'หนองคาย', "value" => 0],  // หนองคาย
            ["hc-key" => "th-ac", "name" => 'อำนาจเจริญ', "value" => 0],  // อำนาจเจริญ
            ["hc-key" => "th-md", "name" => 'มุกดาหาร', "value" => 0],  // มุกดาหาร
            ["hc-key" => "th-sn", "name" => 'สกลนคร', "value" => 0],  // สกลนคร
            ["hc-key" => "th-nw", "name" => 'นาราธิวาส', "value" => 0],  // นาราธิวาส
            ["hc-key" => "th-pi", "name" => 'ปัตตานี', "value" => 0],  // ปัตตานี
            ["hc-key" => "th-rn", "name" => 'ระนอง', "value" => 0],  // ระนอง
            ["hc-key" => "th-nt", "name" => 'นครศรีธรรมราช', "value" => 0],  // นครศรีธรรมราช
            ["hc-key" => "th-sg", "name" => 'สงขลา', "value" => 0],  // สงขลา
            ["hc-key" => "th-pr", "name" => 'แพร่', "value" => 0],  // แพร่
            ["hc-key" => "th-py", "name" => 'พะเยา', "value" => 0],  // พะเยา
            ["hc-key" => "th-so", "name" => 'สุโขทัย', "value" => 0],  // สุโขทัย
            ["hc-key" => "th-ud", "name" => 'อุตรดิตถ์', "value" => 0],  // อุตรดิตถ์
            ["hc-key" => "th-kn", "name" => 'กาญจณบุรี', "value" => 0],  // กาญจณบุรี
            ["hc-key" => "th-tk", "name" => 'ตาก', "value" => 0],  // ตาก
            ["hc-key" => "th-ut", "name" => 'อุทัยธานี', "value" => 0],   // อุทัยธานี
            ["hc-key" => "th-ns", "name" => 'นครสวรรค์', "value" => 0],   // นครสวรรค์
            ["hc-key" => "th-pk", "name" => 'ประจวบคีรีขันธ์', "value" => 0],   // ประจวบคีรีขันธ์
            ["hc-key" => "th-ur", "name" => 'อุบลราชธานี', "value" => 0],   // อุบลราชธานี
            ["hc-key" => "th-sk", "name" => 'สระแก้ว', "value" => 0],   // สระแก้ว
            ["hc-key" => "th-ry", "name" => 'ระยอง', "value" => 0],   // ระยอง
            ["hc-key" => "th-cy", "name" => 'ชัยภูมิ', "value" => 0],   // ชัยภูมิ
            ["hc-key" => "th-su", "name" => 'สุรินทร์', "value" => 0],   // สุรินทร์
            ["hc-key" => "th-nf", "name" => 'นครพนม', "value" => 0],   // นครพนม
            ["hc-key" => "th-bk", "name" => 'บึงกาฬ', "value" => 0],   // บึงกาฬ
            ["hc-key" => "th-mh", "name" => 'แม่ฮ่องสอน', "value" => 0],   // แม่ฮ่องสอน
            ["hc-key" => "th-pu", "name" => 'ภูเก็ต', "value" => 0],   // ภูเก็ต
            ["hc-key" => "th-cp", "name" => 'ชุมพร', "value" => 0],   // ชุมพร
            ["hc-key" => "th-yl", "name" => 'ยะลา', "value" => 0],   // ยะลา
            ["hc-key" => "th-ct", "name" => 'จันทบุรี', "value" => 0],   // จันทบุรี
            ["hc-key" => "th-ln", "name" => 'ลำพูน', "value" => 0],   // ลำพูน
            ["hc-key" => "th-na", "name" => 'น่าน', "value" => 0],   // น่าน
            ["hc-key" => "th-lg", "name" => 'ลำปาง', "value" => 0],   // ลำปาง
            ["hc-key" => "th-pb", "name" => 'ปราจีนบุรี', "value" => 0],   // ปราจีนบุรี
            ["hc-key" => "th-rt", "name" => 'ราชบุรี', "value" => 0],   // ราชบุรี
            ["hc-key" => "th-ys", "name" => 'ยโสธร', "value" => 0],   // ยโสธร
            ["hc-key" => "th-ms", "name" => 'มหาสารคาม', "value" => 0],   // มหาสารคาม
            ["hc-key" => "th-un", "name" => 'อุบลราชธานี', "value" => 0],   // อุบลราชธานี
            ["hc-key" => "th-nb", "name" => 'หนองบัวลำพู', "value" => 0],   // หนองบัวลำพู
            ["hc-key" => "th-ss", "name" => 'สมุทรสาคร', "value" => 0]    // สมุทรสาคร
        ];

        return $data;
    }
}
