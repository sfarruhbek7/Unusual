let weatherData;
API_KEY= "9f2e1f455e003246d00a9c41b725bbc8";

function createWeatherCard(wi){
    console.log(wi.dt_txt.split(" ")[0] + " - " + (wi.main.temp - 273.15).toFixed(0)+"°C");
}
function getWeatherDetails(city, lat, lon){
    const weatherAPI=`http://api.openweathermap.org/data/2.5/forecast?lat=${lat}&lon=${lon}&appid=${API_KEY}`;

    fetch(weatherAPI).then(res => res.json()).then(data => {



        const fiveDaysF = data.list;
        // .filter(forecast => {
        //     const forecastDate = new Date(forecast.dt_txt).getDate();
        //     if (!uniqueForecastDays.includes(forecastDate)){
        //         return uniqueForecastDays.push(forecastDate);
        //     }
        // });









        weatherData = fiveDaysF;

        console.log(weatherData);
        TodayWeather();


















    });
}
function getWeather(city){
    const GECODING_API_URL = `http://api.openweathermap.org/geo/1.0/direct?q=${city}&limit=1&appid=${API_KEY}`;
    fetch(GECODING_API_URL).then(
        res => res.json())
        .then(data => {
            const {name, lat, lon} = data[0];
            getWeatherDetails(name, lat, lon);
        }).catch(() => {
            alert("ERROR");
        });
}

function TodayWeather(){
    console.log(weatherData[weatherData.length-1]);
    let weatherItem=weatherData[weatherData.length-1];
    document.getElementById('tdHumidity').innerHTML=weatherItem.main.humidity+" %";
    document.getElementById('tdTemp').innerHTML=(weatherItem.main.temp - 273.15).toFixed(0) + " °C";
    document.getElementById('tdWS').innerHTML=weatherItem.wind.speed+" m/s";
    document.getElementById('tdPR').innerHTML=0+" %";



    let dataH = [];
    let dataT = [];
    let dataWS = [];



    const uniqueForecastDays =[];
    const fiveDaysF = weatherData.filter(forecast => {
            const forecastDate = new Date(forecast.dt_txt).getDate();
            if (!uniqueForecastDays.includes(forecastDate)){
                return uniqueForecastDays.push(forecastDate);
            }
        });
    fiveDaysF.forEach(wItem => {
        dataH.push(wItem.main.humidity);
        dataT.push((wItem.main.temp - 273.15).toFixed(0));
        dataWS.push((weatherItem.wind.speed).toFixed(0));
    });

    var ctx1 = document.getElementById("chart-line").getContext("2d");

    var gradientStroke1 = ctx1.createLinearGradient(0, 230, 0, 50);

    gradientStroke1.addColorStop(1, 'rgba(94, 114, 228, 0.2)');
    gradientStroke1.addColorStop(0.2, 'rgba(94, 114, 228, 0.0)');
    gradientStroke1.addColorStop(0, 'rgba(94, 114, 228, 0)');
    new Chart(ctx1, {
        type: "bar",
        data: {
            labels: ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Sunday"],
            datasets: [{
                label: "Humidity(%)",
                tension: 0.4,
                borderWidth: 0,
                pointRadius: 0,
                borderColor: "#2dce89",
                backgroundColor: gradientStroke1,
                borderWidth: 3,
                fill: true,
                data: dataH,
                maxBarThickness: 6
            }, {
                label: "Temperature(°C)",
                tension: 0.4,
                borderWidth: 0,
                pointRadius: 0,
                borderColor: "#f5365c",
                backgroundColor: gradientStroke1,
                borderWidth: 3,
                fill: true,
                data: dataT,
                maxBarThickness: 6
            }, {
                label: "Wind Speed(m/s)",
                tension: 0.4,
                borderWidth: 0,
                pointRadius: 0,
                borderColor: "#495057",
                backgroundColor: gradientStroke1,
                borderWidth: 3,
                fill: true,
                data: dataWS,
                maxBarThickness: 6
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false,
                }
            },
            interaction: {
                intersect: false,
                mode: 'index',
            },
            scales: {
                y: {
                    grid: {
                        drawBorder: false,
                        display: true,
                        drawOnChartArea: true,
                        drawTicks: false,
                        borderDash: [5, 5]
                    },
                    ticks: {
                        display: true,
                        padding: 10,
                        color: '#fbfbfb',
                        font: {
                            size: 11,
                            family: "Open Sans",
                            style: 'normal',
                            lineHeight: 2
                        },
                    }
                },
                x: {
                    grid: {
                        drawBorder: false,
                        display: false,
                        drawOnChartArea: false,
                        drawTicks: false,
                        borderDash: [5, 5]
                    },
                    ticks: {
                        display: true,
                        color: '#ccc',
                        padding: 20,
                        font: {
                            size: 11,
                            family: "Open Sans",
                            style: 'normal',
                            lineHeight: 2
                        },
                    }
                },
            },
        },
    });













}

function ReadMore(){
    let addHTMLWeather ="";
    weatherData.forEach(weatherItem => {

        addHTMLWeather+=`<tr>
                      <td>
                        <div class="d-flex px-2">
                          <div class="my-auto">
                            <h6 class="mb-0 text-sm">${weatherItem.dt_txt.split(" ")[0]}</h6>
                          </div>
                        </div>
                      </td>
                      <td>
                        <p class="text-sm font-weight-bold mb-0">${(weatherItem.main.humidity).toFixed(0)} %</p>
                      </td>
                      <td>
                        <span class="text-xs font-weight-bold">${(weatherItem.main.temp - 273.15).toFixed(0)} °C</span>
                      </td>
                      <td class="align-middle text-center">
                        <div class="d-flex align-items-center justify-content-center">
                          <span class="me-2 text-xs font-weight-bold">${(weatherItem.wind.speed).toFixed(0)} m/s</span>
                        </div>
                      </td>
                      <td class="align-middle text-center">
                        <div class="d-flex align-items-center justify-content-center">
                          <span class="me-2 text-xs font-weight-bold">${weatherItem.weather[0].description}</span>
                        </div>
                      </td>
                    </tr>`

    })




    Swal.fire({
        title: "<strong></strong>",
        html: `
                  <div class="container-fluid py-4">
      <div class="row">
        <div class="col-12">
          <div class="card mb-4">
            <div class="card-header pb-0">
              <h6>Last 1 month</h6>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
              <div class="table-responsive p-0">
                <table class="table align-items-center justify-content-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Humidity(%)</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Temperature(°C)</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Wind Speed(m/s)</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2">Description</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody> `+
            addHTMLWeather
            +`</tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      </div>
                  `,
        width: 900,
        showCloseButton: true,
    });
}
