<x-teachers-layout>
  <div class="p-4 sm:ml-64">
    <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700 mt-14">
      <canvas id="myChart" height="100px"></canvas>
   </div>
 </div>
</x-teachers-layout>

{{-- Script --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" ></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script type="text/javascript">

var labels =  {{ Js::from($labels) }};
var data_student =  {{ Js::from($data_student) }};
var data_new_student =  {{ Js::from($data_new_student) }};

   const data = {
      labels: labels,
      datasets: [
        {
          label: 'นักศึกษาทั้งหมด',
          backgroundColor: '#0f5e9c',
          borderColor: '#0f5e9c',
          data: data_student,
        },
        {
          label: 'นักศึกษาใหม่',
          backgroundColor: '#00a86b',
          borderColor: '#00a86b',
          data: data_new_student,
        },
      ]
    };
    const config = {
      type: 'bar',
      data: data,
      options: {}
    };

    const myChart = new Chart(
      document.getElementById('myChart'),
      config
    );
</script>
