Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#858796';

var ctx = document.getElementById("myPieChart");

fetch(feedbackAppointmentRoute)
    .then(response => response.json())
    .then(data => {
        var categories = ['Poor', 'Fair', 'Good', 'Very Good', 'Excellent'];

        var backgroundColors = categories.map(category => {
            var colorMap = {
                'Poor': '#e74a3b',
                'Fair': '#f6c23e',
                'Good': '#35784f',
                'Very Good': '#36b9cc',
                'Excellent': '#1cc88a',
            };

            return colorMap[category];
        });

        var hoverBackgroundColors = backgroundColors.map(color => lightenDarkenColor(color, 20));

        var myPieChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: categories,
                datasets: [{
                    data: categories.map(category => {
                        var categoryData = data.find(item => item.rating === category);
                        return categoryData ? categoryData.count : 0;
                    }),
                    backgroundColor: backgroundColors,
                    hoverBackgroundColor: hoverBackgroundColors,
                    hoverBorderColor: "rgba(234, 236, 244, 1)",
                }],
            },
            options: {
                maintainAspectRatio: false,
                tooltips: {
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    caretPadding: 10,
                },
                legend: {
                    display: false
                },
                cutoutPercentage: 80,
            },
        });
    })
    .catch(error => console.error('Error fetching data:', error));

document.addEventListener('DOMContentLoaded', function () {

    fetch(feedbackDocumentRequestRoute)
        .then(response => response.json())
        .then(data => {
            var categories = ['Poor', 'Fair', 'Good', 'Very Good', 'Excellent'];

            categories.forEach((category, index) => {
                var categoryData = data.find(item => item.rating === category);
                var percentage = categoryData ? (categoryData.count / data.reduce((acc, item) => acc + item.count, 0)) * 100 : 0;

                document.getElementById(`${category.toLowerCase()}Progress`).style.width = `${percentage}%`;
                document.getElementById(`${category.toLowerCase()}Percentage`).textContent = `${percentage.toFixed(2)}%`;
            });

        }).catch(error => console.error('Error fetching data:', error));
});

function lightenDarkenColor(col, amt) {
    var usePound = false;

    if (col[0] === "#") {
        col = col.slice(1);
        usePound = true;
    }

    var num = parseInt(col, 16);

    var r = (num >> 16) + amt;

    if (r > 255) r = 255;
    else if (r < 0) r = 0;

    var b = ((num >> 8) & 0x00FF) + amt;

    if (b > 255) b = 255;
    else if (b < 0) b = 0;

    var g = (num & 0x0000FF) + amt;

    if (g > 255) g = 255;
    else if (g < 0) g = 0;

    return (usePound ? "#" : "") + (g | (b << 8) | (r << 16)).toString(16);
}