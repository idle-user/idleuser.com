<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/../src/session.php'; set_last_page(); requires_admin(); ?>
<!doctype html>
<html lang="en">
<head>
    <?php
        $title = 'Admin - Analytics';
        include 'includes/head.php';
    ?>
</head>
<body>

    <?php include 'includes/nav.php'; ?>

    <main role="main" class="container">

        <?php include 'includes/banner.php'; ?>

        <div class="my-3 p-3 bg-white rounded shadow-sm">
            <h6 class="border-bottom border-gray pb-2 mb-0">User Registers</h6>
            <div class="media text-muted pt-3">
                <p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
                    <canvas id="chart-user" class="chartjs-render-monitor"></canvas>
                </p>
            </div>
        </div>

        <div class="my-3 p-3 bg-white rounded shadow-sm">
            <h6 class="border-bottom border-gray pb-2 mb-0">Web Traffic</h6>
            <div class="media text-muted pt-3">
                <p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
                    <canvas id="chart-web-traffic" class="chartjs-render-monitor"></canvas>
                </p>
            </div>
        </div>

        <div class="my-3 p-3 bg-white rounded shadow-sm">
            <h6 class="border-bottom border-gray pb-2 mb-0">API Traffic</h6>
            <div class="media text-muted pt-3">
                <p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
                    <canvas id="chart-api-traffic" class="chartjs-render-monitor"></canvas>
                </p>
            </div>
        </div>

    </main>

	<?php include '../includes/footer.php'; ?>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.4/Chart.bundle.min.js'></script>
    <script type="text/javascript">
        $(document).ready(function() {
            var user_data = <?php echo json_encode($db->registered_user_dates()); ?>;
            var user_k = [];
            var user_v = [];
            for (var key in user_data) {
                user_k.push(key);
                user_v.push(user_data[key]['subtotal']);
            }
            var user_ctx = $("#chart-user");
            var userChart = new Chart(user_ctx, {
                type: 'line',
                data: {
                    labels: user_k,
                    datasets: [{
                        data: user_v,
                        label: "User Registrations",
                        borderColor: "#43bac7",
                        backgroundColor: '#43bac7',
                        fill: false
                    }]
                },
                options: {
                    title: {
                        display: false,
                        text: ''
                    },
                    legend: {
                    display: false
                    },
                    layout: {
                    padding: {
                        left: 0
                    }
                    },
                    scales: {
                        xAxes: [{
                            ticks: {
                                beginAtZero: true,
                                fixedStepSize: 1
                            }
                        }]
                    }
                }
            });

            var web_traffic_data = <?php echo json_encode($db->web_traffic_daily()); ?>;
            var web_traffic_data_k = [];
            var web_traffic_data_v = [];
            for (var key in web_traffic_data) {
                web_traffic_data_k.push(key);
                web_traffic_data_v.push(web_traffic_data[key]['total_traffic']);
            }
            var web_traffic_ctx = $("#chart-web-traffic");
            var webTrafficChart = new Chart(web_traffic_ctx, {
                type: 'bar',
                data: {
                    labels: web_traffic_data_k.reverse(),
                    datasets: [{
                        data: web_traffic_data_v.reverse(),
                        label: "Web Traffic",
                        borderColor: "#43bac7",
                        backgroundColor: '#43bac7',
                        fill: false
                    }]
                },
                options: {
                    title: {
                        display: false,
                        text: ''
                    },
                    legend: {
                    display: false
                    },
                    layout: {
                    padding: {
                        left: 0
                    }
                    },
                    scales: {
                    xAxes: [{
                        ticks: {
                            beginAtZero: true,
                            fixedStepSize: 1
                        }
                    }]
                }
                }
            });

        var api_traffic_data = <?php echo json_encode($db->api_traffic_daily()); ?>;
            var api_traffic_data_k = [];
            var api_traffic_data_v = [];
            for (var key in api_traffic_data) {
                api_traffic_data_k.push(key);
                api_traffic_data_v.push(api_traffic_data[key]['total_traffic']);
            }
            var api_traffic_ctx = $("#chart-api-traffic");
            var apiTrafficChart = new Chart(api_traffic_ctx, {
                type: 'bar',
                data: {
                    labels: api_traffic_data_k.reverse(),
                    datasets: [{
                        data: api_traffic_data_v.reverse(),
                        label: "API Traffic",
                        borderColor: "#43bac7",
                        backgroundColor: '#43bac7',
                        fill: false
                    }]
                },
                options: {
                    title: {
                        display: false,
                        text: ''
                    },
                    legend: {
                    display: false
                    },
                    layout: {
                    padding: {
                        left: 0
                    }
                    },
                    scales: {
                    xAxes: [{
                        ticks: {
                            beginAtZero: true,
                            fixedStepSize: 1
                        }
                    }]
                }
                }
            });

        });
    </script>
</body>



</html>
