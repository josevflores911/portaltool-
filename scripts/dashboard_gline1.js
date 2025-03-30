// Carregue os dados JSON do PHP
fetch('./jsondados/gerar_dados.php')
    .then(response => response.json())
    .then(data => {
        // Extraia as categorias e valores do JSON
        const categories = data.map(item => item.datetime);
        const series1 = data.map(item => item.series1);
        const series2 = data.map(item => item.series2);

        // Crie um objeto de configuração para o gráfico
        const options = {
            series: [{
                name: 'line1',
                data: series1
            }],
            chart: {
                height: '100%',
                width: '100%',
                type: 'area',
                sparkline: {
                  enabled: true
                },
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
                        reset: '<img src="/static/icons/reset.png" width="20">',
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
                  enabled: true,
                  top: 1,
                  left: 1,
                  blur: 2,
                  opacity: 0.2,
                }
              },
              stroke: {
                curve: 'smooth'
              },
              grid: {
                padding: {
                  top: 20,
                  //bottom: 3,
                  //left: 110
                }
              },
              markers: {
                size: 0
              },
              colors: ['#fff'],
              tooltip: {
                x: {
                  show: false
                },
                y: {
                  title: {
                    formatter: function formatter(val) {
                      return '';
                    }
                  }
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth'
            },
            xaxis: {
                type: 'datetime',
                categories: categories
            },
            tooltip: {
                x: {
                    format: 'dd/MM/yy HH:mm'
                },
            },
        };

        // Crie o gráfico usando ApexCharts
        const chart = new ApexCharts(document.querySelector('#chart1'), options);

        // Renderize o gráfico com os valores
        chart.render();
    })
    .catch(error => {
        console.error('Erro ao carregar os dados:', error);
    });