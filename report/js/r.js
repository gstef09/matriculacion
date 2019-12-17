new Vue({
    el: '#app',
    data:{
        periodo:'',
        registrados:0,
        legalizados:0,
        registrados_carrera:{},
        legalizados_carrera:{}

    },
    created: function () {
        this.getData();
       
    }
    ,


    methods: {
        getData: function(){
            axios('report/report.php').then(
                response=>{
                   
                    this.periodo = response.data.periodo_academico;
                    this.registrados = response.data.total_registrados;
                    this.legalizados = response.data.total_legalizados;
                    this.registrados_carrera =  response.data.registrados_x_carrera;
                    this.legalizados_carrera = response.data.legalizados_x_carrera;
                    this.report1();
                    this.report2();
                }
            ).catch(e=>{
                console.error(e);
                
            })
        },
        report1: function(){
            let  labels1 = [];
            let dataset1 = [];
            let label = "# de Estudiantes";
            for (let  key in this.registrados_carrera) {
                labels1.push(this.registrados_carrera[key].Carrera);
              
                dataset1.push(this.registrados_carrera[key].matriculados);
                
                
            }
            
            var ctx = document.getElementById('myChart').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels1,
                    datasets: [{
                        label: label,
                        data: dataset1,
                        backgroundColor: [
                            "#34",
                            "#3A9",
                            "#4B8",
                            "#5C9",
                            "#59F",
                            "#e7F",
                            "#f4F",
                            "#94F",
                            "#10Ae",
                            "#256e",
                            "#56e",
                            "#800e",
                            "#d00",
                            "#d00",
                            "#b35",
                            "#c85",
                            "#e49",
                            "#649",
                            "#745",
                            "#230c",
                            "#b2a",
                            "#2eb",
                            "#d63"
                            
                        ]
                       
                    }]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });
        },
        report2: function(){
            let  labels1 = [];
            let dataset1 = [];
            let label = "# de Estudiantes";
            for (let  key in this.legalizados_carrera) {
                labels1.push(this.legalizados_carrera[key].Carrera);
              
                dataset1.push(this.legalizados_carrera[key].legalizados);
                
                
            }
            
            var ctx = document.getElementById('myChart1').getContext('2d');
            var myChart1 = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels1,
                    datasets: [{
                        label: label,
                        data: dataset1,
                        backgroundColor: [
                            "#34",
                            "#3A9",
                            "#4B8",
                            "#5C9",
                            "#59F",
                            "#e7F",
                            "#f4F",
                            "#94F",
                            "#10Ae",
                            "#256e",
                            "#56e",
                            "#800e",
                            "#d00",
                            "#d00",
                            "#b35",
                            "#c85",
                            "#e49",
                            "#649",
                            "#745",
                            "#230c",
                            "#b2a",
                            "#2eb",
                            "#d63",
                            
                        ],
                       
                    }]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });
        }
    },
})