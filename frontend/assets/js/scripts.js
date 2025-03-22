/*!
    * Start Bootstrap - SB Admin v7.0.7 (https://startbootstrap.com/template/sb-admin)
    * Copyright 2013-2023 Start Bootstrap
    * Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-sb-admin/blob/master/LICENSE)
    */
    // 
// Scripts
// 



            var app = $.spapp({
                defaultView: "#charts2",
                templateDir: "./views/"
    
            });

            app.route({
                view : "dashboard",

               

                onCreate: function() {  

                    console.log("tu sam u charts dashboard");
                                            // Set new default font family and font color to mimic Bootstrap's default styling
                            Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
                            Chart.defaults.global.defaultFontColor = '#292b2c';
                            initializeDashboardCharts();

                },

              });
              
            // Route for the Profile view
            app.route({
                view: "profile",
                onCreate: function() {
                    console.log("Profile view loaded");
                    initializeProfileForm(); // Initialize profile editing functionality
                }
            }); 
                
            app.run();
       
            function initializeDashboardCharts() {
                

                            // Area Chart Example
                            var ctx = document.getElementById("myAreaChart");
                            var myLineChart = new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: ["Mar 1", "Mar 2", "Mar 3", "Mar 4", "Mar 5", "Mar 6", "Mar 7", "Mar 8", "Mar 9", "Mar 10", "Mar 11", "Mar 12", "Mar 13"],
                                datasets: [{
                                label: "Sessions",
                                lineTension: 0.3,
                                backgroundColor: "rgba(2,117,216,0.2)",
                                borderColor: "rgba(2,117,216,1)",
                                pointRadius: 5,
                                pointBackgroundColor: "rgba(2,117,216,1)",
                                pointBorderColor: "rgba(255,255,255,0.8)",
                                pointHoverRadius: 5,
                                pointHoverBackgroundColor: "rgba(2,117,216,1)",
                                pointHitRadius: 50,
                                pointBorderWidth: 2,
                                data: [10000, 30162, 26263, 18394, 18287, 28682, 31274, 33259, 25849, 24159, 32651, 31984, 38451],
                                }],
                            },
                            options: {
                                scales: {
                                xAxes: [{
                                    time: {
                                    unit: 'date'
                                    },
                                    gridLines: {
                                    display: false
                                    },
                                    ticks: {
                                    maxTicksLimit: 7
                                    }
                                }],
                                yAxes: [{
                                    ticks: {
                                    min: 0,
                                    max: 40000,
                                    maxTicksLimit: 5
                                    },
                                    gridLines: {
                                    color: "rgba(0, 0, 0, .125)",
                                    }
                                }],
                                },
                                legend: {
                                display: false
                                }
                            }
                            }); 
                            


                            // Bar Chart Example
                            var ctx = document.getElementById("myBarChart");
                            var myLineChart = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: ["January", "February", "March", "April", "May", "June"],
                                datasets: [{
                                label: "Revenue",
                                backgroundColor: "rgba(2,117,216,1)",
                                borderColor: "rgba(2,117,216,1)",
                                data: [4215, 5312, 6251, 7841, 9821, 14984],
                                }],
                            },
                            options: {
                                scales: {
                                xAxes: [{
                                    time: {
                                    unit: 'month'
                                    },
                                    gridLines: {
                                    display: false
                                    },
                                    ticks: {
                                    maxTicksLimit: 6
                                    }
                                }],
                                yAxes: [{
                                    ticks: {
                                    min: 0,
                                    max: 15000,
                                    maxTicksLimit: 5
                                    },
                                    gridLines: {
                                    display: true
                                    }
                                }],
                                },
                                legend: {
                                display: false
                                }
                            }

                            });




                            const datatablesSimple = document.getElementById("datatablesSimple");
                            if (datatablesSimple) {
                                new simpleDatatables.DataTable(datatablesSimple);
                            }
                    


            }
        
            function initializeProfileForm() {
                document.getElementById("edit-profile-btn").addEventListener("click", function () {
                    document.getElementById("profile-view").style.display = "none";
                    document.getElementById("edit-profile-form").style.display = "block";
                });
            
                document.getElementById("cancel-profile-btn").addEventListener("click", function () {
                    document.getElementById("profile-view").style.display = "block";
                    document.getElementById("edit-profile-form").style.display = "none";
                });
            
                document.getElementById("save-profile-btn").addEventListener("click", function () {
                    document.getElementById("profile-name").innerText = document.getElementById("edit-name").value;
                    document.getElementById("profile-email").innerText = document.getElementById("edit-email").value;
                    document.getElementById("profile-phone").innerText = document.getElementById("edit-phone").value;
                    document.getElementById("profile-mobile").innerText = document.getElementById("edit-mobile").value;
                    document.getElementById("profile-address").innerText = document.getElementById("edit-address").value;
            
                    document.getElementById("profile-view").style.display = "block";
                    document.getElementById("edit-profile-form").style.display = "none";
                });
            }
window.addEventListener('DOMContentLoaded', event => {

    // Toggle the side navigation
    const sidebarToggle = document.body.querySelector('#sidebarToggle');
    if (sidebarToggle) {
        
      

        // Uncomment Below to persist sidebar toggle between refreshes


        if (localStorage.getItem('sb|sidebar-toggle') === 'true') {
            document.body.classList.toggle('sb-sidenav-toggled');
         }
        sidebarToggle.addEventListener('click', event => {
            event.preventDefault();
            document.body.classList.toggle('sb-sidenav-toggled');
            localStorage.setItem('sb|sidebar-toggle', document.body.classList.contains('sb-sidenav-toggled'));
        });
    }

}); 
