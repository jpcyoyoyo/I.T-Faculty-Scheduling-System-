<!DOCTYPE html>
<html lang="en">
<?php 
session_start();
include('admin/db_connect.php');
ob_start();
ob_end_flush();
?>
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>I.T Faculty Scheduling System</title>

  <?php include('./header.php'); ?>
  <?php 
  if(isset($_SESSION['login_id']))
  header("location:index.php");
  ?>
  
  <!-- Added Google Fonts for better typography -->
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">

  <style>
    body {
      margin: 0;
      font-family: 'Roboto', sans-serif;
      background: #f4f4f4;
    }
    
    #main {
      width: 100%;
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: flex-start; /* Align to the left */
      background: url('assets/Images/Home.png') no-repeat center center;
      background-size: cover;
      padding-left: 200px; /* Adds space on the left */
    }
    
    #login {
      width: 100%;
      max-width: 400px;
    }

    .card {
      border: none;
      box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.1);
    }

    .card-body {
      padding: 40px;
      background-color: #fff;
      border-radius: 8px;
    }

    h4 {
      font-weight: 700;
      margin-bottom: 20px;
      color: #333;
      text-align: center;
    }

    /* Tagline styling */
    .tagline {
      font-size: 1.2em;
      text-align: center;
      margin-bottom: 30px;
      color: #555;
      font-style: italic;
    }

    .form-group {
      margin-bottom: 20px;
    }

    .form-group label {
      font-weight: 500;
      color: #333;
    }

    .form-control {
      border-radius: 5px;
      border: 1px solid #ccc;
      padding: 10px;
    }

    .btn-primary {
      background-color: #007bff;
      border-color: #007bff;
      padding: 10px;
      font-size: 16px;
      font-weight: 600;
      border-radius: 5px;
      width: 100%;
    }

    .btn-primary:hover {
      background-color: #0056b3;
    }

    /* Back to top icon */
    .back-to-top {
      position: fixed;
      bottom: 20px;
      right: 20px;
      display: none;
      background: #007bff;
      color: #fff;
      padding: 10px;
      border-radius: 50%;
      text-align: center;
    }

    .back-to-top i {
      font-size: 20px;
    }
  </style>
</head>

<body>
  <main id="main">
    <div id="login" class="col-md-4">
      <div class="card">
        <div class="card-body">
          <h4><b>Welcome To I.T Faculty Scheduling System</b></h4>
          <p class="tagline">Seamlessly Manage Your Academic Schedule</p> <!-- Tagline added -->
          <form id="login-form">
            <div class="form-group">
              <label for="id_no" class="control-label">Please enter your Faculty ID No.</label>
              <input type="text" id="id_no" name="id_no" class="form-control" required>
            </div>
            <center>
              <button class="btn-sm btn-block btn-wave col-md-4 btn-primary">Login</button>
            </center>
          </form>
        </div>
      </div>
    </div>
  </main>

  <a href="#" class="back-to-top"><i class="icofont-simple-up"></i></a>

  <script>
    $('#login-form').submit(function(e) {
      e.preventDefault();
      $('#login-form button').attr('disabled', true).html('Logging in...');
      if ($(this).find('.alert-danger').length > 0)
        $(this).find('.alert-danger').remove();
      $.ajax({
        url: 'admin/ajax.php?action=login_faculty',
        method: 'POST',
        data: $(this).serialize(),
        error: err => {
          console.log(err);
          $('#login-form button').removeAttr('disabled').html('Login');
        },
        success: function(resp) {
          if (resp == 1) {
            location.href = 'index.php';
          } else {
            $('#login-form').prepend('<div class="alert alert-danger">ID Number is incorrect.</div>');
            $('#login-form button').removeAttr('disabled').html('Login');
          }
        }
      });
    });
  </script>
</body>
</html>
