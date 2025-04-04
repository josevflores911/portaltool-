// Carregue os dados JSON do PHP
fetch('./jsondados/dado1.php')
    .then(response => response.json())
    .then(data => {
        // Extraia as categorias e valores do JSON
        const categories = data.map(item => item.month_name);
        const series1 = data.map(item => item.series1);
        const series2 = data.map(item => item.series2);

        // Crie um objeto de configuração para o gráfico
        var optionspie = {
            series: series1,
              chart: {
              width: 380,
              type: 'pie',
            },
            labels: categories,
            responsive: [{
              breakpoint: 480,
              options: {
                chart: {
                  width: '100%'
                },
                legend: {
                  position: 'bottom'
                }
              }
            }],
            chart: {
                //height: 380,
                type: 'pie',
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
                foreColor: '#fff',
            },

        }

        var chartBar = new ApexCharts(
          document.querySelector("#piechart"),
          optionspie
        );

        chartBar.render();
    })
    .catch(error => {
        console.error('Erro ao carregar os dados:', error);
    });