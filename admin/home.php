<?php include 'db_connect.php' ?>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css' rel='stylesheet' />
<script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js'></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f4f4f9;
    }

    .summary_icon {
        font-size: 3rem;
        position: absolute;
        right: 1rem;
        color: #ffffff96;
    }

    .welcome-card {
        background: linear-gradient(135deg, #6b73ff, #000dff);
        color: white;
        border-radius: 12px;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        padding: 2rem;
        position: relative;
        overflow: hidden;
        transition: transform 0.3s ease-in-out;
    }

    .welcome-card:hover {
        transform: scale(1.05);
    }

    .welcome-card h1 {
        font-size: 2.5rem;
        font-weight: 700;
        margin: 0;
    }

    .welcome-card .icon {
        position: absolute;
        top: 1rem;
        right: 1rem;
        font-size: 5rem;
        color: rgba(255, 255, 255, 0.3);
    }

    .welcome-card p {
        font-size: 1.2rem;
        font-weight: 300;
        margin-top: 0.5rem;
    }

    .card-body {
        position: relative;
        z-index: 2;
    }

    /* Button styling */
    .btn-check-status {
        background-color: #4caf50;
        color: white;
        padding: 0.8rem 1.5rem;
        border: none;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .btn-check-status:hover {
        background-color: #45a049;
    }

    .card {
        border-radius: 12px;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        margin-bottom: 1rem;
    }

    #calendar {
        padding: 20px;
    }

    canvas {
        max-height: 300px;
    }

    .imgs {
        margin: 0.5em;
        max-width: calc(100%);
        max-height: calc(100%);
    }

    .imgs img {
        max-width: calc(100%);
        max-height: calc(100%);
        cursor: pointer;
    }

    #imagesCarousel,
    #imagesCarousel .carousel-inner,
    #imagesCarousel .carousel-item {
        height: 60vh !important;
        background: black;
    }

    #imagesCarousel .carousel-item.active {
        display: flex !important;
    }

    #imagesCarousel .carousel-item-next {
        display: flex !important;
    }

    #imagesCarousel .carousel-item img {
        margin: auto;
    }

    #imagesCarousel img {
        width: auto !important;
        height: auto !important;
        max-height: calc(100%) !important;
        max-width: calc(100%) !important;
    }

    /* Floating icon for more style */
    .float-right-icon {
        position: absolute;
        top: 10px;
        right: 10px;
        font-size: 3rem;
        color: rgba(255, 255, 255, 0.3);
    }
</style>

<div class="container-fluid">
    <div class="row mt-3 ml-3 mr-3">
        <div class="col-lg-12">
            <div class="card welcome-card">
                <i class="fas fa-smile-beam float-right-icon"></i>
                <div class="card-body">
                    <h1>Welcome back, <?php echo $_SESSION['login_name']; ?>!</h1>
                    <p>We are happy to see you again. Hope you have a productive day!</p>
                    <button class="btn-check-status">Check Status</button>
                    <hr>
                </div>
            </div>
        </div>
    </div>

    <!-- Calendar and Chart Section -->
    <div class="row mt-3 ml-3 mr-3">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h3>Event Calendar</h3>
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h3>User Activity Chart</h3>
                    <canvas id="userActivityChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        // Initialize FullCalendar
        $('#calendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            events: [
                // Sample events; replace with dynamic events from your database if needed
                {
                    title: 'Meeting',
                    start: '2024-10-20'
                },
                {
                    title: 'Conference',
                    start: '2024-10-22',
                    end: '2024-10-24'
                }
            ]
        });

        // Initialize Chart.js
        var ctx = document.getElementById('userActivityChart').getContext('2d');
        var userActivityChart = new Chart(ctx, {
            type: 'bar', // Change to 'line' for a line chart
            data: {
                labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
                datasets: [{
                    label: 'User Activities',
                    data: [12, 19, 3, 5, 2, 3, 10],
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1,
                    fill: true // Set to false for line chart
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });

    $('#manage-records').submit(function (e) {
        e.preventDefault();
        start_load();
        $.ajax({
            url: 'ajax.php?action=save_track',
            data: new FormData($(this)[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST',
            success: function (resp) {
                resp = JSON.parse(resp);
                if (resp.status == 1) {
                    alert_toast("Data successfully saved", 'success');
                    setTimeout(function () {
                        location.reload();
                    }, 800);
                }
            }
        });
    });

    $('#tracking_id').on('keypress', function (e) {
        if (e.which == 13) {
            get_person();
        }
    });

    $('#check').on('click', function (e) {
        get_person();
    });

    function get_person() {
        start_load();
        $.ajax({
            url: 'ajax.php?action=get_pdetails',
            method: "POST",
            data: { tracking_id: $('#tracking_id').val() },
            success: function (resp) {
                if (resp) {
                    resp = JSON.parse(resp);
                    if (resp.status == 1) {
                        $('#name').html(resp.name);
                        $('#address').html(resp.address);
                        $('[name="person_id"]').val(resp.id);
                        $('#details').show();
                        end_load();
                    } else if (resp.status == 2) {
                        alert_toast("Unknown tracking id.", 'danger');
                        end_load();
                    }
                }
            }
        });
    }
</script>
