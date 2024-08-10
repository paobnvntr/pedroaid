function number_format(number, decimals, dec_point, thousands_sep) {
  // *     example: number_format(1234.56, 2, ',', ' ');
  // *     return: '1 234,56'
  number = (number + '').replace(',', '').replace(' ', '');
  var n = !isFinite(+number) ? 0 : +number,
    prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
    sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
    dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
    s = '',
    toFixedFix = function(n, prec) {
      var k = Math.pow(10, prec);
      return '' + Math.round(n * k) / k;
    };
  // Fix for IE parseFloat(0.55).toFixed(0) = 0;
  s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
  if (s[0].length > 3) {
    s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
  }
  if ((s[1] || '').length < prec) {
    s[1] = s[1] || '';
    s[1] += new Array(prec - s[1].length + 1).join('0');
  }
  return s.join(dec);
}

fetch(servicesCountRoute)
.then(response => response.json())
.then(data => {
  // Transfer data directly to variables
  console.log(data.appointmentsData);

  appointmentsData = [];
  for (let month = 1; month <= 12; month++) {
    appointmentsData.push(data.appointmentsData[month] || 0);
  }
  
  // Convert inquiries data object to array
  inquiriesData = [];
  for (let month = 1; month <= 12; month++) {
    inquiriesData.push(data.inquiriesData[month] || 0);
  }
  
  // Convert document requests data object to array
  documentRequestsData = [];
  for (let month = 1; month <= 12; month++) {
    documentRequestsData.push(data.documentRequestsData[month] || 0);
  }
  
  // Once data is fetched, initialize the chart
  initializeChart();
})
.catch(error => {
  console.error('Error fetching data:', error);
});

function initializeChart() {
// Area Chart Example
var ctx = document.getElementById("myAreaChart");

console.log(appointmentsData);

var myLineChart = new Chart(ctx, {
  type: 'line',
  data: {
    labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
    datasets: [{
      label: "Appointments",
      lineTension: 0.3,
      backgroundColor: "rgba(78, 115, 223, 0.05)",
      borderColor: "rgba(78, 115, 223, 1)",
      pointRadius: 3,
      pointBackgroundColor: "rgba(78, 115, 223, 1)",
      pointBorderColor: "rgba(78, 115, 223, 1)",
      pointHoverRadius: 3,
      pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
      pointHoverBorderColor: "rgba(78, 115, 223, 1)",
      pointHitRadius: 10,
      pointBorderWidth: 2,
      data: appointmentsData,
    }, {
      label: "Inquiries",
      lineTension: 0.3,
      backgroundColor: "rgba(115, 78, 223, 0.05)",
      borderColor: "rgba(115, 78, 223, 1)",
      pointRadius: 3,
      pointBackgroundColor: "rgba(115, 78, 223, 1)",
      pointBorderColor: "rgba(115, 78, 223, 1)",
      pointHoverRadius: 3,
      pointHoverBackgroundColor: "rgba(115, 78, 223, 1)",
      pointHoverBorderColor: "rgba(115, 78, 223, 1)",
      pointHitRadius: 10,
      pointBorderWidth: 2,
      data: inquiriesData,
    }, {
      label: "Document Requests",
      lineTension: 0.3,
      backgroundColor: "rgba(223, 78, 115, 0.05)",
      borderColor: "rgba(223, 78, 115, 1)",
      pointRadius: 3,
      pointBackgroundColor: "rgba(223, 78, 115, 1)",
      pointBorderColor: "rgba(223, 78, 115, 1)",
      pointHoverRadius: 3,
      pointHoverBackgroundColor: "rgba(223, 78, 115, 1)",
      pointHoverBorderColor: "rgba(223, 78, 115, 1)",
      pointHitRadius: 10,
      pointBorderWidth: 2,
      data: documentRequestsData,
    }],
  },
  options: {
    maintainAspectRatio: false,
    layout: {
      padding: {
        left: 10,
        right: 25,
        top: 25,
        bottom: 0
      }
    },
    scales: {
      xAxes: [{
        time: {
          unit: 'date'
        },
        gridLines: {
          display: false,
          drawBorder: false
        },
        ticks: {
          maxTicksLimit: 7
        }
      }],
      yAxes: [{
        ticks: {
          maxTicksLimit: 5,
          padding: 10,
          // Include a dollar sign in the ticks
          callback: function(value, index, values) {
            return number_format(value);
          }
        },
        gridLines: {
          color: "rgb(234, 236, 244)",
          zeroLineColor: "rgb(234, 236, 244)",
          drawBorder: false,
          borderDash: [2],
          zeroLineBorderDash: [2]
        }
      }],
    },
    legend: {
      display: true,
    },
    tooltips: {
      backgroundColor: "rgb(255,255,255)",
      bodyFontColor: "#858796",
      titleMarginBottom: 10,
      titleFontColor: '#6e707e',
      titleFontSize: 14,
      borderColor: '#dddfeb',
      borderWidth: 1,
      xPadding: 15,
      yPadding: 15,
      displayColors: false,
      intersect: false,
      mode: 'index',
      caretPadding: 10,
      callbacks: {
        label: function(tooltipItem, chart) {
          var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
          return datasetLabel + ': ' + number_format(tooltipItem.yLabel);
        }
      }
    }
  }
});
}
