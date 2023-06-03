<x-boss-layout>
    {{-- <x-slot name="header">
        <h6 class="font-semibold text-md text-gray-800 dark:text-gray-200 leading-tight pl-4">
            {{ __('ผู้บริหาร') }}
        </h6>
    </x-slot> --}}

    {{-- Chart --}}
    <div class="h-full p-8 bg-gray-100">
      <h1>สถิตินักศึกษา</h1>
      <canvas id="myChart" height="100px"></canvas>
    </div> 

    {{-- Chart 1 --}}
    <div class="h-full p-8 bg-gray-100">
      <h1>สถิตินักศึกษา เข้าใหม่ - จบหลักสูตร</h1>
      <canvas id="myChart_B" height="100px"></canvas>
    </div> 

    {{-- Chart 2 --}}
    <div class="h-full p-8 bg-gray-100">
      <h1>ร้อยละผู้เข้าสอบ</h1>
      <canvas id="myChart_C" height="100px"></canvas>
    </div> 

    {{-- Card --}}
    <div class="h-full p-8 bg-gray-100">
        <div class="grid grid-cols-1 gap-5 mt-5 md:grid-cols-3">
          <div class="overflow-hidden bg-violet-100 rounded-lg shadow">
            <div class="px-4 py-5 lg:p-6">
                <dl>
                    <dt class="text-sm font-medium leading-5 text-gray-500 truncate flex flex-row content-center place-content-between">
                        <div>
                            คาดว่าจะจบ <a href="{{ url('teachers') }}" class="underline text-indigo-400"> ดูรายตำบล </a>
                        </div>  
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M17.593 3.322c1.1.128 1.907 1.077 1.907 2.185V21L12 17.25 4.5 21V5.507c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0111.186 0z" />
                        </svg>                                                                           
                    </dt>
                    <dd class="mt-1 text-3xl font-semibold leading-9 text-gray-900">
                    {{$expectfin_student}} คน
                    </dd>
                </dl>
            </div>
          </div>
          <div class="overflow-hidden bg-red-100 rounded-lg shadow">
            <div class="px-4 py-5 lg:p-6">
                <dl>
                    <dt class="text-sm font-medium leading-5 text-gray-500 truncate flex flex-row content-center place-content-between">
                        <div>
                            ไม่จบตกค้าง (ไม่ได้ลงทะเบียนแล้ว) <a href="{{ url('teachers') }}"> รายตำบล </a>
                        </div>  
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        </svg>                                                      
                    </dt>
                    <dd class="mt-1 text-3xl font-semibold leading-9 text-gray-900">
                    {{$nofinish_student}} คน
                    </dd>
                </dl>
            </div>
          </div>
          <div class="overflow-hidden bg-blue-100 rounded-lg shadow">
            <div class="px-4 py-5 lg:p-6">
                <dl>
                    <dt class="text-sm font-medium leading-5 text-gray-500 truncate flex flex-row content-center place-content-between">
                        <div>
                            มีสิทธิสอบ N-NET <a href="{{ url('teachers') }}"> รายตำบล </a>
                        </div>  
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5" />
                        </svg>                                                        
                    </dt>
                    <dd class="mt-1 text-3xl font-semibold leading-9 text-gray-900">
                    81 คน
                    </dd>
                </dl>
            </div>
          </div>
          <div class="overflow-hidden bg-yellow-100 rounded-lg shadow">
            <div class="px-4 py-5 lg:p-6">
                <dl>
                    <dt class="text-sm font-medium leading-5 text-gray-500 truncate flex flex-row content-center place-content-between">
                        <div>
                            มีสิทธิสอบ E-Exam <a href="{{ url('teachers') }}"> รายตำบล </a>
                        </div>  
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5" />
                        </svg>                                                        
                    </dt>
                    <dd class="mt-1 text-3xl font-semibold leading-9 text-gray-900">
                        11 คน
                    </dd>
                </dl>
            </div>
          </div>
      </div>   
  </div>

</x-boss-layout>
  {{-- Script --}}
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" ></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script type="text/javascript">
        
    var labels =  {{ Js::from($labels) }};
    var data_student =  {{ Js::from($data_student) }};
    var data_studentPrimary =  {{ Js::from($data_studentPrimary) }};
    var data_studentJunior =  {{ Js::from($data_studentJunior) }};
    var data_studentSenior =  {{ Js::from($data_studentSenior) }};

    const data = {
      labels: labels,
      datasets: [
        {
          label: 'นักศึกษาทั้งหมด',
          backgroundColor: '#6996F6',
          borderColor: '#6996F6',
          data: data_student,
        },
        {
          label: 'ประถม',
          backgroundColor: '#F598AA',
          borderColor: '#F598AA',
          data: data_studentPrimary,
        },
        {
          label: 'มัธยมต้น',
          backgroundColor: '#7DDAD9',
          borderColor: '#7DDAD9',
          data: data_studentJunior,
        },
        {
          label: 'มัธยมปลาย',
          backgroundColor: '#FCEAAF',
          borderColor: '#FCEAAF',
          data: data_studentSenior,
        }
      ]
    };

    const config = {
      type: 'line',
      data: data,
      options: {}
    };

    const myChart = new Chart(
      document.getElementById('myChart'),
      config
    );
  </script>

  {{-- Chart 1 --}}
  <script>

  var labels =  {{ Js::from($labels) }};
  var data_new_student =  {{ Js::from($data_new_student) }};
  var data_finish_student =  {{ Js::from($data_finish_student) }};

  const data1 = {
    labels: labels,
    datasets: [
      {
        label: 'นักศึกษาใหม่',
        backgroundColor: '#6996F6',
        borderColor: '#6996F6',
        data: data_new_student,
      },
      {
        label: 'นักศึกษาจบหลักสูตร',
        backgroundColor: '#F598AA',
        borderColor: '#F598AA',
        data: data_finish_student,
      }
    ]
  };

  const config1 = {
    type: 'line',
    data: data1,
    options: {}
  };

  const myChart_B = new Chart(
    document.getElementById('myChart_B'),
    config1
  );
</script>

  {{-- Chart 2 --}}
  <script>

    var labels =  {{ Js::from($labels) }};
    var data_exam_avg =  {{ Js::from($data_exam_avg) }};
  
    const data2 = {
      labels: labels,
      datasets: [
        {
          label: 'ร้อยละผู้เข้าสอบ',
          backgroundColor: '#7289da',
          borderColor: '#7289da',
          data: data_exam_avg,
        }
      ]
    };
  
    const config2 = {
      type: 'bar',
      data: data2,
      options: {}
    };
  
    const myChart_C = new Chart(
      document.getElementById('myChart_C'),
      config2
    );
  </script>