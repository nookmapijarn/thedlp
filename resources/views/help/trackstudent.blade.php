<x-help-layout>
    <section class="bg-white grid justify-items-center">
        <form class="max-w-screen-md w-full p-10">
            <div class="text-center text-xl p-5  bg-yellow-200 mb-2 shadow-md">
                แบบติดตามผู้สำเร็จการศึกษา
                <div class="text-center text-xs">หลักสูตรการศึกษานอกระบบระดับการศึกษาขั้นพื้นฐาน พุทธศักราช 2551</div>
                <div class="text-center text-xs">ศูนย์ส่งเสริมการเรียนรู้อำเภอโพธิ์ทอง</div>
            </div>
            {{-- Part 1 --}}
            <div class="grid gap-6 mb-6 md:grid-cols-2 p-4 shadow-md">
                {{-- IMG --}}
                <div class="flex items-center justify-center w-full">
                    <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 ">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <svg aria-hidden="true" class="w-10 h-10 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                            <p class="mb-2 text-sm text-gray-500 dark:text-gray-400 text-center"><span class="font-semibold">ภาพถ่าย 1 (ชุดทำงาน/นักศึกษา)</span></p>
                        </div>
                        <input id="dropzone-file" type="file" class="hidden" required/>
                    </label>
                </div> 
                <div class="flex items-center justify-center w-full">
                    <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 ">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <svg aria-hidden="true" class="w-10 h-10 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                            <p class="mb-2 text-sm text-gray-500 dark:text-gray-400 text-center"><span class="font-semibold">ภาพถ่าย 2 (ถ้ามี) (สถานที่ทำงาน, ผลงาน, อื่นๆ)</span></p>
                        </div>
                        <input id="dropzone-file" type="file" class="hidden" />
                    </label>
                </div> 
                <div>
                    <label for="student_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">รหัสนักศึกษา</label>
                    <input type="search" id="student_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 placeholder="" required>
                </div>
                <div>
                    <label for="countries" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">คำนำหน้า</label>
                    <select id="countries" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  ">
                        <option selected>เลือก</option>
                        <option value="CA">นาย</option>
                        <option value="FR">นาง</option>
                        <option value="DE">นางสาว</option>
                    </select>
                </div>  
                <div>
                    <label for="first_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">ชื่อ (ไม่ใส่คำนำหน้า)</label>
                    <input type="text" id="first_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  " placeholder="" required>
                </div>
                <div>
                    <label for="last_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">นามสกุล</label>
                    <input type="text" id="last_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  " placeholder="" required>
                </div>
                <div>
                    <label for="countries" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">ระดับที่จบ</label>
                    <select id="countries" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  ">
                        <option selected>เลือก</option>
                        <option value="CA">ประถมศึกษา</option>
                        <option value="FR">มัธยมศึกษาตอนต้น</option>
                        <option value="DE">มัธยมศึกษาตอนปลาย</option>
                    </select>
                </div>  
                <div>
                    <label for="countries" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">ภาคเรียนที่จบ</label>
                    <select id="countries" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  ">
                        <option selected>เลือก</option>
                        <option value="CA">ภาคเรียนที่ 2/66</option>
                        <option value="CA">ภาคเรียนที่ 1/66</option>
                        <option value="CA">ภาคเรียนที่ 2/65</option>
                        <option value="CA">ภาคเรียนที่ 1/65</option>
                        <option value="CA">ภาคเรียนที่ 2/64</option>
                        <option value="CA">ภาคเรียนที่ 1/64</option>
                        <option value="CA">ภาคเรียนที่ 2/63</option>
                        <option value="CA">ภาคเรียนที่ 1/63</option>
                        <option value="CA">ภาคเรียนที่ 2/62</option>
                        <option value="CA">ภาคเรียนที่ 1/62</option>
                        <option value="CA">ภาคเรียนที่ 2/61</option>
                        <option value="CA">ภาคเรียนที่ 1/61</option>
                        <option value="CA">ภาคเรียนที่ 2/60</option>
                        <option value="CA">ภาคเรียนที่ 1/60</option>
                        <option value="CA">ภาคเรียนที่ 2/59</option>
                        <option value="CA">ภาคเรียนที่ 1/59</option>
                        <option value="CA">ภาคเรียนที่ 2/58</option>
                        <option value="CA">ภาคเรียนที่ 1/58</option>
                        <option value="CA">ภาคเรียนที่ 2/57</option>
                        <option value="CA">ภาคเรียนที่ 1/57</option>
                        <option value="CA">ไม่ทราบ</option>
                    </select>
                </div>  
                <div>
                    <label for="countries" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">กศน.ตำบล</label>
                    <select id="countries" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  ">
                        <option selected>เลือก</option>
                        <option value="400">อำเภอโพธิ์ทอง</option>
                        <option value="4011 บางพลับ">4011 บางพลับ</option>
                        <option value="4012 บางพลับ">4012 บางพลับ</option>
                        <option value="4021 อ่างแก้ว">4021 อ่างแก้ว</option>
                        <option value="4031 หนองแม่ไก่">4031 หนองแม่ไก่</option>
                        <option value="4041 ยางช้าย">4041 ยางช้าย</option>
                        <option value="4051 โพธิ์รังนก">4051 โพธิ์รังนก</option>
                        <option value="4061 รำมะสัก">4061 รำมะสัก</option>
                        <option value="4071 บางระกำ">4071 บางระกำ</option>
                        <option value="4081 บ่อแร่">4081 บ่อแร่</option>
                        <option value="4091 สามง่าม">4091 สามง่าม</option>
                        <option value="4101 ทางพระ">4101 ทางพระ</option>
                        <option value="4102 ทางพระ">4102 ทางพระ</option>
                        <option value="4111 อินทประมูล">4111 อินทประมูล</option>
                        <option value="4121 องครักษ์">4121 องครักษ์</option>
                        <option value="4131 โคกพุทรา">4131 โคกพุทรา</option>
                        <option value="4141 บางเจ้าฉ่า">4141 บางเจ้าฉ่า</option>
                        <option value="4151 คำหยาด">4151 คำหยาด</option>
                        <option value="4171 พิการ">4171 พิการ</option>
                    </select>
                </div>  
                <div>
                    <label for="phone" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">เบอร์โทร</label>
                    <input type="tel" id="phone" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  " placeholder="123-45-678" pattern="[0-9]{3}-[0-9]{2}-[0-9]{3}" required>
                </div>
                <div>
                    <label for="phone" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Line/fackbook/อื่นๆ</label>
                    <input type="tel" id="phone" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  " placeholder="" pattern="[0-9]{3}-[0-9]{2}-[0-9]{3}" required>
                </div>
            </div>

            {{-- Part 2 --}}
            <div class="text-center text-xl p-5  bg-yellow-200 mb-2 shadow-md">
                <div class="text-center text-sm">เมื่อท่านสำเร็จการศึกษาจาก กศน.แล้ว ท่านได้นำความรู้ / วุฒิการศึกษาไปใช้ในด้านใดบ้างโปรดกรอกข้อมูลตามความเป็นจริง</div>
            </div>
            <div class="grid gap-6 mb-6 md:grid-cols-2 p-4 shadow-md">
                <div>
                    <label for="countries" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">ใช้ศึกษาต่อในระดับที่สูงขึ้น</label>
                    <select id="countries" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  ">
                        <option selected>เลือก</option>
                        <option value="FR">มัธยมศึกษาตอนต้น</option>
                        <option value="DE">มัธยมศึกษาตอนปลาย</option>
                        <option value="DE">ปวช.</option>
                        <option value="DE">ปวส.</option>
                        <option value="DE">ปริญาตรี</option>
                    </select>
                </div> 
                <div>
                    <label for="school" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">ชื่อและสถานที่ ศึกษาต่อ</label>
                    <input type="text" id="school" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  " placeholder="" pattern="[0-9]{3}-[0-9]{2}-[0-9]{3}" required>
                </div>
                <div>
                    <label for="countries" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">ใช้ประกอบอาชีพ</label>
                    <select id="countries" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  ">
                        <option selected>เลือก</option>
                        <option value="FR">พนักงาน/ลูกจ้างโรงาน</option>
                        <option value="DE">รับจ้างทั่วไป</option>
                        <option value="DE">ลูกจ้างหน่วยงานรัฐ</option>
                        <option value="DE">ผู้นำท้องถื่น(กำนัน ผู้ใหญ่บ้าน อบต อบจ)</option>
                        <option value="DE">ผู้นำศาสนา</option>
                        <option value="DE">ข้าาราชการ/เจ้าหน้าที่ของรัฐ</option>
                        <option value="DE">พนักงานเอกชน</option>
                        <option value="DE">ธุรกิจส่วนตัว</option>
                    </select>
                </div> 
                <div>
                    <label for="school" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">ชื่อและสถานที่ ประกอบอาชีพ</label>
                    <input type="text" id="school" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  " placeholder="" pattern="[0-9]{3}-[0-9]{2}-[0-9]{3}" required>
                </div>
                <div>
                    <label for="countries" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">ใช้ปรับตำแหน่ง/เพิ่มเงินเดือน</label>
                    <select id="countries" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  ">
                        <option selected>เลือก</option>
                        <option value="FR">ปรับตำแหน่ง</option>
                        <option value="DE">เพิ่มเงินเดือน</option>
                    </select>
                </div> 
                <div>
                    <label for="school" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">ชื่อและสถานที่ ปรับตำแหน่ง/เพิ่มเงินเดือน</label>
                    <input type="text" id="school" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  " placeholder="" pattern="[0-9]{3}-[0-9]{2}-[0-9]{3}" required>
                </div>
            </div>

            {{-- Part 3 --}}
            <div class="text-center text-xl p-5  bg-yellow-200 mb-2 shadow-md">
                <div class="text-center text-sm">ประโยชน์ที่ได้รับจากการเรียน กศน.</div>
            </div>
            <div class="grid gap-6 mb-6 md:grid-cols-2 p-4 shadow-md">
                <div class="text-center text-xs">ท่านสามารถนำความรู้ที่ได้รับจากการศึกษาของ กศน.ไปใช้ในการทำงานได้ในระดับใด</div>
                <div class="flex justify-between">
                    <div class="flex items-center mr-4">
                        <input id="inline-radio" type="radio" value="" name="inline-radio-group" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
                        <label for="inline-radio" class="ml-2 text-sm font-medium text-gray-900">1</label>
                    </div>
                    <div class="flex items-center mr-4">
                        <input id="inline-2-radio" type="radio" value="" name="inline-radio-group" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500  ">
                        <label for="inline-2-radio" class="ml-2 text-sm font-medium text-gray-900 ">2</label>
                    </div>
                    <div class="flex items-center mr-4">
                        <input checked id="inline-checked-radio" type="radio" value="" name="inline-radio-group" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500  ">
                        <label for="inline-checked-radio" class="ml-2 text-sm font-medium text-gray-900 ">3</label>
                    </div>
                    <div class="flex items-center">
                        <input id="inline-disabled-radio" type="radio" value="" name="inline-radio-group" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500  ">
                        <label for="inline-disabled-radio" class="ml-2 text-sm font-medium text-gray-900">4</label>
                    </div>
                    <div class="flex items-center">
                        <input id="inline-disabled-radio" type="radio" value="" name="inline-radio-group" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500  ">
                        <label for="inline-disabled-radio" class="ml-2 text-sm font-medium text-gray-900">5</label>
                    </div>
                </div>       
                <div class="text-center text-xs">ความพึงพอใจโดยรวมในการจัดการศึกษาระดับการศึกษาขั้นพื้นฐานของ กศน.</div>
                <div class="flex justify-between">
                    <div class="flex items-center mr-4">
                        <input id="inline-radio" type="radio" value="" name="inline-radio-group" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
                        <label for="inline-radio" class="ml-2 text-sm font-medium text-gray-900">1</label>
                    </div>
                    <div class="flex items-center mr-4">
                        <input id="inline-2-radio" type="radio" value="" name="inline-radio-group" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500  ">
                        <label for="inline-2-radio" class="ml-2 text-sm font-medium text-gray-900 ">2</label>
                    </div>
                    <div class="flex items-center mr-4">
                        <input checked id="inline-checked-radio" type="radio" value="" name="inline-radio-group" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500  ">
                        <label for="inline-checked-radio" class="ml-2 text-sm font-medium text-gray-900 ">3</label>
                    </div>
                    <div class="flex items-center">
                        <input id="inline-disabled-radio" type="radio" value="" name="inline-radio-group" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500  ">
                        <label for="inline-disabled-radio" class="ml-2 text-sm font-medium text-gray-900">4</label>
                    </div>
                    <div class="flex items-center">
                        <input id="inline-disabled-radio" type="radio" value="" name="inline-radio-group" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500  ">
                        <label for="inline-disabled-radio" class="ml-2 text-sm font-medium text-gray-900">5</label>
                    </div>
                </div>         
            </div>

            {{-- Part 4 --}}
            <div class="text-center text-xl p-5  bg-yellow-200 mb-2 shadow-md">
                <div class="text-center text-sm">ข้อมูลอื่นที่เป็นประโยชน์-ข้อเสนอแนะ</div>
            </div>
            <div class="grid gap-6 mb-6 md:grid-cols-2 p-4 shadow-md">
                <div>
                    <label for="school" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">ท่านมีความถนัด</label>
                    <input type="text" id="school" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  " placeholder="" pattern="[0-9]{3}-[0-9]{2}-[0-9]{3}" required>
                </div>      
                <div>
                    <label for="school" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">ท่านมีความต้องการทำงานเกี่ยวกับ</label>
                    <input type="text" id="school" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  " placeholder="" pattern="[0-9]{3}-[0-9]{2}-[0-9]{3}" required>
                </div> 
                <div>
                    <label for="school" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">ท่านมีความต้องการเข้ารับการพัฒนาตนเองเพื่อประกอบอาชีพในด้านใด</label>
                    <input type="text" id="school" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  " placeholder="" pattern="[0-9]{3}-[0-9]{2}-[0-9]{3}" required>
                </div> 
                <div>
                    <label for="school" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">ความคิดเห็น/ข้อเสนอแนะ</label>
                    <input type="text" id="school" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  " placeholder="" pattern="[0-9]{3}-[0-9]{2}-[0-9]{3}" required>
                </div>  
            </div>

            <div class="flex items-start mb-6">
                <div class="flex items-center h-5">
                <input id="remember" type="checkbox" value="" class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-600 dark:ring-offset-gray-800" required>
                </div>
                <label for="remember" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">ยินยอมให้ข้อมูล <a href="#" class="text-blue-600 hover:underline dark:text-blue-500">เงื่อนไขการนำข้อมูลไปใช้</a>.</label>
            </div>
            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">ส่งแบบฟอร์ม</button>
        </form>
    </section>
    
</x-help-layout>