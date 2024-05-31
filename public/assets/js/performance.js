

$( document ).ready(function() {

//Sales Chart
var options = {
  chart: {
    height: 450,
    type: "line",
    stacked: false,
    toolbar: {
        show: false,
        
    },
  },
  dataLabels: {
    enabled: false
  },


  colors: ['#b357c3','#4556a6'],
  series: [{
    name: 'Total Course Earning',
    type: 'column',
    data: [104, 102, 117, 146, 118, 115, 220, 103, 83, 114, 265, 174],
  }, {
    name: "Total Product Earning",
    type: "column",
    data: [92, 75, 123, 111, 196, 122, 159, 102, 138, 136, 62, 240]
  }],
  stroke: {
    width: [0, 0, 2]
  },
  plotOptions: {
    bar: {
      columnWidth: '30%',
    }
  },
  markers: {
    size: [0, 0, 3],
    colors: undefined,
    strokeColors: "#fff",
    strokeOpacity: 0.6,
    strokeDashArray: 0,
    fillOpacity: 1,
    discrete: [],
    shape: "circle",
    radius: [0, 0, 2],
    offsetX: 0,
    offsetY: 0,
    showNullDataPoints: true,
    hover: {
      sizeOffset: 3
    }
  },
  fill: {
    opacity: [1, 1, 1]
  },
  grid: {
    borderColor: '#fff',
  },
  legend: {
    show: true,
    position: 'bottom',
    color: '#fff',
    markers: {
      width: 7,
      height: 7,
      shape: 'square',
      radius: 0,
    }
  },
  yaxis: {
    min: 0,
    forceNiceScale: true,
    title: {
      style: {
        color: '#fff',
        fontSize: '14px',
        fontWeight: 600,
        cssClass: 'apexcharts-yaxis-label',
      },
    },
    
  },
  yaxis: {
        tickAmount: 4,
        floating: false,
        labels: {
          style: {
            colors: '#fff',
          },
          offsetY: -7,
          offsetX: 0,

        },
        axisBorder: {
          show: false,
        },
        axisTicks: {
          show: false,
        }
      },

  xaxis: {
    type: 'month',
    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
    labels: {
      style: {
        colors: '#fff',
      },

    },
    axisBorder: {
      show: true,
      color: '#fff',
      offsetX: 0,
      offsetY: 0,
    },

    
    axisTicks: {
      show: true,
      borderType: 'solid',
      color: '#fff',
      width: 6,
      offsetX: 0,
      offsetY: 0
    },
    labels: {
      style: {
        colors: '#fff',
      },
    },
  },
  tooltip: {
    enabled: true,
    theme: 'dark',
    shared: false,
    intersect: true,
    x: {
      show: false
    }
  },
};
var chart1 = new ApexCharts(document.querySelector("#earnings-chart"), options);
chart1.render();
      
});