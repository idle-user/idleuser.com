<?php require_once getenv('APP_PATH') . '/src/session.php'; set_last_page(); requires_admin(); ?>
<?php $domain_traffic = $db->traffic_daily(); ?>
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

        <?php $cnt=1;  foreach($domain_traffic as $key=>$value){ ?>

        <div class="my-3 p-3 bg-white rounded shadow-sm">
            <h6 class="border-bottom border-gray pb-2 mb-0"><?=$key?> Traffic</h6>
            <div class="media text-muted pt-3">
                <p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
                    <canvas id="chart-traffic-<?=$cnt++?>" class="chartjs-render-monitor"></canvas>
                </p>
            </div>
        </div>

        <?php } ?>

    </main>

	<?php include getenv('APP_PATH') . '/public/includes/footer.php'; ?>
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

            <?php $cnt=1;  foreach($domain_traffic as $key=>$data) { ?>

            var traffic<?=$cnt?>_data = <?php echo json_encode($data); ?>;
            var traffic<?=$cnt?>_data_k = [];
            var traffic<?=$cnt?>_data_v = [];
            for (var key in traffic<?=$cnt?>_data) {
                traffic<?=$cnt?>_data_k.push(key);
                traffic<?=$cnt?>_data_v.push(traffic<?=$cnt?>_data[key]['total_traffic']);
            }
            var traffic<?=$cnt?>_ctx = $("#chart-traffic-<?=$cnt?>");
            var traffic<?=$cnt?>Chart = new Chart(traffic<?=$cnt?>_ctx, {
                type: 'bar',
                data: {
                    labels: traffic<?=$cnt?>_data_k.reverse(),
                    datasets: [{
                        data: traffic<?=$cnt?>_data_v.reverse(),
                        label: "<?=$key?> Traffic",
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

            <?php $cnt++; } ?>

        });
    </script>
</body>



</html>
