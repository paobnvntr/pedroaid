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

fetch(documentTypeCountRoute)
  .then(response => response.json())
  .then(data => {
    let affidavitOfLossData = [];
    let affidavitOfGuardianshipData = [];
    let affidavitOfNoIncomeData = [];
    let affidavitOfNoFixIncomeData = [];
    let extraJudicialData = [];
    let deedOfSaleData = [];
    let deedOfDonationData = [];
    let otherDocumentData = [];

    // Process data for Affidavit of Loss
    for (let month = 1; month <= 12; month++) {
      affidavitOfLossData.push(data.affidavitOfLossData[month] || 0);
    }

    // Process data for Affidavit of Guardianship
    for (let month = 1; month <= 12; month++) {
      affidavitOfGuardianshipData.push(data.affidavitOfGuardianshipData[month] || 0);
    }

    // Process data for Affidavit of No Income
    for (let month = 1; month <= 12; month++) {
      affidavitOfNoIncomeData.push(data.affidavitOfNoIncomeData[month] || 0);
    }

    // Process data for Affidavit of No Fixed Income
    for (let month = 1; month <= 12; month++) {
      affidavitOfNoFixIncomeData.push(data.affidavitOfNoFixIncomeData[month] || 0);
    }

    // Process data for Extra Judicial
    for (let month = 1; month <= 12; month++) {
      extraJudicialData.push(data.extraJudicialData[month] || 0);
    }

    // Process data for Deed of Sale
    for (let month = 1; month <= 12; month++) {
      deedOfSaleData.push(data.deedOfSaleData[month] || 0);
    }

    // Process data for Deed of Donation
    for (let month = 1; month <= 12; month++) {
      deedOfDonationData.push(data.deedOfDonationData[month] || 0);
    }

    // Process data for Other Document
    for (let month = 1; month <= 12; month++) {
      otherDocumentData.push(data.otherDocumentData[month] || 0);
    }
    
    // Once data is fetched, initialize the chart
    initializeDocumentTypeChart(
      affidavitOfLossData,
      affidavitOfGuardianshipData,
      affidavitOfNoIncomeData,
      affidavitOfNoFixIncomeData,
      extraJudicialData,
      deedOfSaleData,
      deedOfDonationData,
      otherDocumentData
    );
  })
  .catch(error => {
    console.error('Error fetching data:', error);
  });

  function initializeDocumentTypeChart(
    affidavitOfLossData,
    affidavitOfGuardianshipData,
    affidavitOfNoIncomeData,
    affidavitOfNoFixIncomeData,
    extraJudicialData,
    deedOfSaleData,
    deedOfDonationData,
    otherDocumentData
  ) {
    // Area Chart Example
    var ctx = document.getElementById("documentRequestGraph");
  
    var myLineChart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
        datasets: [{
          label: "Affidavit of Loss",
          lineTension: 0.3,
          backgroundColor: "rgba(255, 99, 132, 0.2)",
          borderColor: "rgba(255, 99, 132, 1)",
          pointRadius: 3,
          pointBackgroundColor: "rgba(255, 99, 132, 1)",
          pointBorderColor: "rgba(255, 99, 132, 1)",
          pointHoverRadius: 3,
          pointHoverBackgroundColor: "rgba(255, 99, 132, 1)",
          pointHoverBorderColor: "rgba(255, 99, 132, 1)",
          pointHitRadius: 10,
          pointBorderWidth: 2,
          data: affidavitOfLossData,
        },
        {
          label: "Affidavit of Guardianship",
          lineTension: 0.3,
          backgroundColor: "rgba(54, 162, 235, 0.2)",
          borderColor: "rgba(54, 162, 235, 1)",
          pointRadius: 3,
          pointBackgroundColor: "rgba(54, 162, 235, 1)",
          pointBorderColor: "rgba(54, 162, 235, 1)",
          pointHoverRadius: 3,
          pointHoverBackgroundColor: "rgba(54, 162, 235, 1)",
          pointHoverBorderColor: "rgba(54, 162, 235, 1)",
          pointHitRadius: 10,
          pointBorderWidth: 2,
          data: affidavitOfGuardianshipData,
        },
        {
          label: "Affidavit of No Income",
          lineTension: 0.3,
          backgroundColor: "rgba(255, 206, 86, 0.2)",
          borderColor: "rgba(255, 206, 86, 1)",
          pointRadius: 3,
          pointBackgroundColor: "rgba(255, 206, 86, 1)",
          pointBorderColor: "rgba(255, 206, 86, 1)",
          pointHoverRadius: 3,
          pointHoverBackgroundColor: "rgba(255, 206, 86, 1)",
          pointHoverBorderColor: "rgba(255, 206, 86, 1)",
          pointHitRadius: 10,
          pointBorderWidth: 2,
          data: affidavitOfNoIncomeData,
        },
        {
          label: "Affidavit of No Fixed Income",
          lineTension: 0.3,
          backgroundColor: "rgba(75, 192, 192, 0.2)",
          borderColor: "rgba(75, 192, 192, 1)",
          pointRadius: 3,
          pointBackgroundColor: "rgba(75, 192, 192, 1)",
          pointBorderColor: "rgba(75, 192, 192, 1)",
          pointHoverRadius: 3,
          pointHoverBackgroundColor: "rgba(75, 192, 192, 1)",
          pointHoverBorderColor: "rgba(75, 192, 192, 1)",
          pointHitRadius: 10,
          pointBorderWidth: 2,
          data: affidavitOfNoFixIncomeData,
        },
        {
          label: "Extra Judicial",
          lineTension: 0.3,
          backgroundColor: "rgba(153, 102, 255, 0.2)",
          borderColor: "rgba(153, 102, 255, 1)",
          pointRadius: 3,
          pointBackgroundColor: "rgba(153, 102, 255, 1)",
          pointBorderColor: "rgba(153, 102, 255, 1)",
          pointHoverRadius: 3,
          pointHoverBackgroundColor: "rgba(153, 102, 255, 1)",
          pointHoverBorderColor: "rgba(153, 102, 255, 1)",
          pointHitRadius: 10,
          pointBorderWidth: 2,
          data: extraJudicialData,
        },
        {
          label: "Deed of Sale",
          lineTension: 0.3,
          backgroundColor: "rgba(255, 159, 64, 0.2)",
          borderColor: "rgba(255, 159, 64, 1)",
          pointRadius: 3,
          pointBackgroundColor: "rgba(255, 159, 64, 1)",
          pointBorderColor: "rgba(255, 159, 64, 1)",
          pointHoverRadius: 3,
          pointHoverBackgroundColor: "rgba(255, 159, 64, 1)",
          pointHoverBorderColor: "rgba(255, 159, 64, 1)",
          pointHitRadius: 10,
          pointBorderWidth: 2,
          data: deedOfSaleData,
        },
        {
          label: "Deed of Donation",
          lineTension: 0.3,
          backgroundColor: "rgba(75, 192, 192, 0.2)",
          borderColor: "rgba(75, 192, 192, 1)",
          pointRadius: 3,
          pointBackgroundColor: "rgba(75, 192, 192, 1)",
          pointBorderColor: "rgba(75, 192, 192, 1)",
          pointHoverRadius: 3,
          pointHoverBackgroundColor: "rgba(75, 192, 192, 1)",
          pointHoverBorderColor: "rgba(75, 192, 192, 1)",
          pointHitRadius: 10,
          pointBorderWidth: 2,
          data: deedOfDonationData,
        },
        {
          label: "Other Document",
          lineTension: 0.3,
          backgroundColor: "rgba(255, 99, 132, 0.2)",
          borderColor: "rgba(255, 99, 132, 1)",
          pointRadius: 3,
          pointBackgroundColor: "rgba(255, 99, 132, 1)",
          pointBorderColor: "rgba(255, 99, 132, 1)",
          pointHoverRadius: 3,
          pointHoverBackgroundColor: "rgba(255, 99, 132, 1)",
          pointHoverBorderColor: "rgba(255, 99, 132, 1)",
          pointHitRadius: 10,
          pointBorderWidth: 2,
          data: otherDocumentData,
        },
        // Repeat the same for other datasets...
        ],
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
  