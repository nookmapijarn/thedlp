<x-teachers-layout>
  <div class="p-4 sm:ml-64">
    <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700 mt-14">
      <canvas id="myChart" height="100px"></canvas>     
    </div>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <caption class="p-5 text-lg font-semibold text-left rtl:text-right text-gray-900 bg-white dark:text-white dark:bg-gray-800">
                จำนวนนักศึกษา
                <p class="mt-1 text-sm font-normal text-gray-500 dark:text-gray-400">หลักสูตรการศึกษานอกระบบระดับการศึกษาขั้นพื้นฐาน พุทธศักราช 2551</p>
            </caption>
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3 text-sm">
                        ภาคเรียน
                    </th>
                    @foreach($labels as $sem)
                    <th scope="col" class="px-6 py-3 text-sm">
                        {{ $sem }}
                    </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                <tr class="bg-green-100 border-b dark:bg-gray-800 dark:border-gray-700 hover:underline">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        นักศึกษาใหม่
                    </th>
                    @foreach($data_new_student as $ns)
                    <td class="px-6 py-4">
                        {{ $ns }}  
                    </td>
                    @endforeach
                </tr>
                <tr class="bg-pink-100 border-b dark:bg-gray-800 dark:border-gray-700 hover:underline">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        นักศึกษาเก่า
                    </th>
                    @foreach($data_old_student as $os)
                    <td class="px-6 py-4">
                        {{ $os }}
                    </td>
                    @endforeach
                </tr>
                <tr class="bg-indigo-100 dark:bg-gray-800 hover:underline">
                    <th scope="row" class="px-6 py-4 font-bold text-gray-900 whitespace-nowrap dark:text-white">
                        นักศึกษาทั้งหมด
                    </th>
                    @foreach($data_student as $os)
                    <td class="px-6 py-4 font-bold">
                        {{ $os }}
                    </td>
                    @endforeach
                </tr>
            </tbody>
        </table>
    </div>
    {{-- รายตำบล --}}
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <caption class="p-5 text-lg font-semibold text-left rtl:text-right text-gray-900 bg-white dark:text-white dark:bg-gray-800">
                จำนวนนักศึกษา (รายตำบล) ภาคเรียนที่ {{$current_semestry}}
                <p class="mt-1 text-sm font-normal text-gray-500 dark:text-gray-400">หลักสูตรการศึกษานอกระบบระดับการศึกษาขั้นพื้นฐาน พุทธศักราช 2551</p>
            </caption>
            <thead class="text-sm text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        ลำดับ
                    </th>
                    <th scope="col" class="px-6 py-3">
                        รหัสตำบล
                    </th>
                    <th scope="col" class="px-6 py-3">
                        ศกร.ระดับตำบล
                    </th>
                    <th scope="col" class="px-6 py-3">
                        ครู
                    </th>
                    <th scope="col" class="px-6 py-3 text-center">
                        ประถม
                    </th>
                    <th scope="col" class="px-6 py-3 text-center">
                        มัธยมต้น
                    </th>
                    <th scope="col" class="px-6 py-3 text-center">
                        มัธยมปลาย
                    </th>
                    <th scope="col" class="px-6 py-3 text-center">
                        ทั้งหมด
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach($student_tumbon as $sttm)
                <tr class="border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100 hover:border-fuchsia-600">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ $loop->iteration }}
                    </th>
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ $sttm->GRP->GRP_CODE }}
                    </th>
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ $sttm->GRP->GRP_NAME }}
                    </th>
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ $sttm->GRP->GRP_ADVIS }}
                    </th>
                    <td class="bg-pink-100 text-center px-6 py-4">
                        {{-- ประถม --}}
                        {{ $sttm->STUDENT->ST1 }}
                    </td>
                    <td class="bg-green-100 text-center px-6 py-4">
                        {{-- ต้น --}}
                        {{ $sttm->STUDENT->ST2 }} 
                    </td>
                    <td class="bg-yellow-100 text-center px-6 py-4">
                        {{-- ปลาย --}}
                        {{ $sttm->STUDENT->ST3 }}
                    </td>
                    <td class="bg-blue-100 font-bold text-center px-6 py-4">
                        {{-- รวม --}}
                        {{ $sttm->STUDENT->ST1 + $sttm->STUDENT->ST2 + $sttm->STUDENT->ST3 }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{-- ************** MAP ************** --}}
    <div class=" border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700">
        <br><br>
      <div id="container" style="width: 100%; height: 100%;"></div>
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
var data_old_student =  {{ Js::from($data_old_student) }};

// จำนวนนักศึกษา
   const student_data = {
      labels: labels,
      datasets: [
        {
          label: 'นักศึกษาใหม่',
          backgroundColor: '#ff901f',
          borderColor: '#ff901f',
          data: data_new_student,
        },
        {
          label: 'นักศึกษาเก่า',
          backgroundColor: '#c621b6',
          borderColor: '#c621b6',
          data: data_old_student,
        },
        {
          label: 'นักศึกษาทั้งหมด',
          backgroundColor: '#2d60dd',
          borderColor: '#2d60dd',
          data: data_student,
        }
      ]
    };
    const config = {
      type: 'bar',
      data: student_data,
      options: {
        indexAxis: 'x',
        // scales: {
        //     x: {
        //         stacked: true,
        //     },
        //     y: {
        //         stacked: true
        //     }
        // }
      }
    };
    const myChart = new Chart(
      document.getElementById('myChart'),
      config
    );
</script>

{{-- **************** แผนที่ ******************** --}}
<script src="https://code.highcharts.com/maps/highmaps.js"></script>
<script src="https://code.highcharts.com/maps/modules/exporting.js"></script>
<script src="https://code.highcharts.com/maps/modules/offline-exporting.js"></script>
<script src="https://code.highcharts.com/maps/modules/accessibility.js"></script>
<script>
const createChart = async () => {

/* MAIN CHART */

// Import maps
const world = await fetch(
    'https://code.highcharts.com/mapdata/custom/world.topo.json'
).then(response => response.json());

const topology = await fetch(
    'https://code.highcharts.com/mapdata/countries/th/th-all.topo.json'
).then(response => response.json());

var data = {{ Js::from($province) }};

Highcharts.mapChart('container', {

    chart: {
        margin: 0,
        // height: '50%',
        // width: '50%'
    },

    title: {
        text: 'ภูมิลำเนาผู้เรียน (ตามหลักฐานทะเบียนบ้าน)'
    },

    mapView: {
        // // Make room for the title
        padding: [30, 0, 0, 0],
        center: [99.9899, 14.8], // Set the center to Thailand
        projection: {
            name: 'Orthographic',
            rotation: [-99.9899, -14.8] // Rotate the map to center Thailand 16.325164712139028, 101.53898776611497
        },
        zoom: 8 // Zoom level
    }, 

    mapNavigation: {
        enabled: true,
        buttonOptions: {
            align: 'right',
            alignTo: 'spacingBox'
        }
    },

    navigation: {
        buttonOptions: {
            theme: {
                stroke: '#e6e6e6'
            }
        }
    },

    legend: {
        layout: 'vertical',
        align: 'right'
    },

    colorAxis: {
        minColor: '#d9ead3',
        maxColor: '#308000'
    },

    series: [{
        states: {
            inactive: {
                enabled: false
            }
        },
        name: 'Background map',
        mapData: world,
        affectsMapView: false,
        borderColor: 'rgba(0, 0, 0, 0)',
        nullColor: 'rgba(196, 196, 196, 0.2)'
    },
    {
        name: topology.title || 'Map',
        mapData: topology,
        data,
        dataLabels: {
                    enabled: true,
                    format: '{point.name}: {point.value}'
                }
    }]
});
};


/* Start of Locator map plugin */
Highcharts.addEvent(Highcharts.Chart, 'afterInit', async function () {

if (
    (
        this.renderTo &&
        this.renderTo.classList &&
        this.renderTo.classList.contains('highcharts-locator')
    ) ||
    !this.mapView ||
    this.options.locator === false
) {
    return;
}

// Get the main chart
const mainChart = this;

const locatorMap = await fetch(
    'https://code.highcharts.com/mapdata/custom/world.topo.json'
).then(response => response.json());

// Generate and place the locator div
mainChart.renderTo.style.position = 'relative';
const locatorContainer = Highcharts.createElement('div', {
    className: 'highcharts-locator'
}, {
    position: 'absolute',
    height: '30%',
    width: '25%',
    bottom: 0,
    left: 0
}, mainChart.renderTo);


// Orthographic projection grid
const getGraticule = () => {
    const data = [];
    // Meridians
    for (let x = -180; x <= 180; x += 15) {
        data.push({
            geometry: {
                type: 'LineString',
                coordinates: x % 90 === 0 ? [
                    [x, -90],
                    [x, 0],
                    [x, 90]
                ] : [
                    [x, -80],
                    [x, 80]
                ]
            }
        });
    }
    // Latitudes
    for (let y = -90; y <= 90; y += 10) {
        const coordinates = [];
        for (let x = -180; x <= 180; x += 5) {
            coordinates.push([x, y]);
        }
        data.push({
            geometry: {
                type: 'LineString',
                coordinates
            },
            lineWidth: y === 0 ? 2 : undefined
        });
    }
    return data;
};

// Render a circle filled with a radial gradient behind the globe to
// make it appear as the sea around the continents
const renderGlobe = e => {
    const chart = e.target;
    if (chart.mapView.projection.options.name === 'Orthographic') {
        let verb = 'animate';
        if (!chart.globe) {
            chart.globe = chart.renderer
                .circle()
                .attr({
                    fill: {
                        radialGradient: {
                            cx: 0.4,
                            cy: 0.4,
                            r: 1
                        },
                        stops: [
                            [
                                0,
                                Highcharts.color(
                                    mainChart.options.plotOptions.map
                                        .nullColor
                                ).brighten(0.3).get()
                            ],
                            [
                                1,
                                mainChart.options.plotOptions.map
                                    .nullColor
                            ]
                        ]
                    },
                    zIndex: -1
                })
                .add(chart.get('graticule').group)
                .shadow({});
            verb = 'attr';
        }

        const bounds = chart.get('graticule').bounds,
            p1 = chart.mapView.projectedUnitsToPixels({
                x: bounds.x1,
                y: bounds.y1
            }),
            p2 = chart.mapView.projectedUnitsToPixels({
                x: bounds.x2,
                y: bounds.y2
            });
        chart.globe.show()[verb]({
            cx: (p1.x + p2.x) / 2,
            cy: (p1.y + p2.y) / 2,
            r: Math.min(p2.x - p1.x, p1.y - p2.y) / 2
        });

    } else if (chart.globe) {
        chart.globe.hide();
    }
};

// Locator chart frame logic
function getMapFrame(chart, plotHeight, plotWidth) {
    const steps = 20;

    function calculateEdge(xFunc, yFunc) {
        const edgePoints = [];
        for (let i = 0; i <= steps; i++) {
            const x = xFunc(i),
                y = yFunc(i),
                lonLat = chart.mapView.pixelsToLonLat({
                    x,
                    y
                });
            if (!isNaN(lonLat.lon) && !isNaN(lonLat.lat)) {
                edgePoints.push([lonLat.lon, lonLat.lat]);
            }
        }
        return edgePoints;
    }

    const topEdge = calculateEdge(
            i => (i / steps) * plotWidth,
            () => 0
        ),

        bottomEdge = calculateEdge(
            i => (i / steps) * plotWidth,
            () => plotHeight
        ),

        leftEdge = calculateEdge(
            () => 0,
            i => (i / steps) * plotHeight
        ),

        rightEdge = calculateEdge(
            () => plotWidth,
            i => (i / steps) * plotHeight
        ),

        rect = [
            ...leftEdge,
            ...bottomEdge,
            ...rightEdge.reverse(),
            ...topEdge.reverse()
        ];

    return rect;
}

// Get the main chart center
const [lon, lat] = mainChart.mapView.center;

// Locator map rotation
function rotation(lonCenter, latCenter) {
    return [-lonCenter, -latCenter];
}

// Locator chart
const locatorChart = Highcharts.mapChart(locatorContainer, {
    chart: {
        backgroundColor: 'transparent',
        margin: 15,
        events: {
            render: renderGlobe
        }
    },

    credits: {
        enabled: false
    },

    mapView: {
        projection: {
            name: 'Orthographic',
            rotation: rotation(lon, lat)
        }
    },

    title: null,

    colorAxis: {
        visible: false
    },

    legend: {
        enabled: false
    },

    mapNavigation: {
        enabled: false
    },

    exporting: {
        enabled: false
    },

    plotOptions: {
        series: {
            accessibility: {
                enabled: false
            },
            enableMouseTracking: false,
            animationLimit: 500,
            borderColor: '#fff',
            borderWidth: 0.25,
            clip: false,
            nullColor: '#e0e0e0',
            states: {
                inactive: {
                    enabled: false
                }
            }
        }
    },
    series: [{
        name: 'Map',
        mapData: locatorMap
    }, {
        id: 'graticule',
        type: 'mapline',
        data: getGraticule(),
        color: 'rgba(128,128,128,0.1)',
        zIndex: -1
    }, {
        name: 'Frame',
        type: 'mapline',
        color: mainChart.options.legend.navigation.activeColor,
        data: [{
            geometry: {
                type: 'LineString',
                coordinates: getMapFrame(
                    mainChart,
                    mainChart.plotHeight,
                    mainChart.plotWidth
                )
            }
        }]
    }]
});

// Adjust the locator frame size when zooming or panning
Highcharts.addEvent(mainChart, 'render', function () {
    locatorChart.series[2].setData([{
        geometry: {
            type: 'LineString',
            coordinates: getMapFrame(
                mainChart,
                mainChart.plotHeight,
                mainChart.plotWidth
            )
        }
    }]);
});
}, { order: 1 });
/* End of Locator map plugin */
createChart();
</script>