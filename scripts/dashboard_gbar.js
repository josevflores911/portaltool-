// Carregue os dados JSON do PHP

fetch('modules/dashboard_gbar.php')
    .then(response => response.json())
    .then(data => {
        // Extraia as categorias e valores do JSON
        const categories = data.map(item => item.month_name);
        const series1 = data.map(item => item.series1);
        const series2 = data.map(item => item.series2);

        // Crie um objeto de configuração para o gráfico
        var optionsBar = {
            series: [{
                name: 'WI',
                data: series1
              }],
              xaxis: {
                type: 'month_name',
                categories: categories
              },
            chart: {
                //height: 380,
                type: 'bar',
                stacked: true,

                // Configuração da barra de ferramentas
                toolbar: {
                    show: true,
                    offsetX: 0,
                    offsetY: 0,
                    tools: {
                        download: true,
                        selection: false,
                        zoom: false,
                        zoomin: false,
                        zoomout: false,
                        pan: false,
                        //reset: '<img src="/static/icons/reset.png" width="20">',
                        customIcons: []
                    },
                    export: {
                        csv: {
                            filename: undefined,
                            columnDelimiter: ',',
                            headerCategory: 'category',
                            headerValue: 'value',
                            dateFormatter(timestamp) {
                                return new Date(timestamp).toDateString();
                            }
                        },
                        svg: {
                            filename: undefined,
                        },
                        png: {
                            filename: undefined,
                        }
                    },
                    autoSelected: 'zoom'
                },
                dropShadow: {
                    enabled: false,
                    enabledOnSeries: undefined,
                    top: 0,
                    left: 0,
                    blur: 3,
                    color: '#fff',
                    opacity: 0.35
                },
                foreColor: '#ccc',
            },
            plotOptions: {
                bar: {
                  //columnWidth: '30%',
                  horizontal: false,
                },
            },
            subtitle: {
                  text: undefined,
                  align: 'left',
                  margin: 10,
                  offsetX: 0,
                  offsetY: 0,
                  floating: false,
                  style: {
                    fontSize:  '12px',
                    fontWeight:  'normal',
                    fontFamily:  undefined,
                    color:  '#9699a2'
                  },
            },
            yaxis: [
            {
              axisTicks: {
                show: true,
                color: "#ccc"
              },
              axisBorder: {
                show: true,
                color: "#ccc"
              },
              labels: {
                style: {
                  colors: "#ccc"
                }
              },
            },
          ],
            grid: {
                show: false,
                borderColor: '#90A4AE',
                strokeDashArray: 0,
                position: 'back',
                xaxis: {
                    lines: {
                        show: true
                    }
                },   
                yaxis: {
                    lines: {
                        show: true
                    }
                },  
                row: {
                    colors: undefined,
                    opacity: 0.5
                },  
                column: {
                    colors: undefined,
                    opacity: 0.5
                },  
                padding: {
                    top: 0,
                    right: 0,
                    bottom: 0,
                    left: 0
                },  
            },
            //colors: ['#0AA5B5'],
            colors: [function({ value, seriesIndex, w }) {
                if (value < 35) {
                      return '#0AA5B5'
                } else {
                      return '#D9534F'
                }
            }, function({ value, seriesIndex, w }) {
              if (value < 70) {
                  return '#0AA5B5'
              } else {
                  return '#D9534F'
              }
            }],
            dataLabels: {
              style: {
                colors: ['#fff']
              }
            },
          fill: {
            //opacity: 1,
            type: "gradient",
            gradient: {
              shade: "light",
              type: "vertical",
              shadeIntensity: 0.5,
              gradientToColors: ["#2b2b2b"],
              inverseColors: false,
              opacityFrom: 1,
              opacityTo: 1,
              stops: [0, 100]
            }
          },

        }

        var chartBar = new ApexCharts(
          document.querySelector("#barchart"),
          optionsBar
        );

        chartBar.render();
    })
    .catch(error => {
        console.error('Erro ao carregar os dados:', error);
    });