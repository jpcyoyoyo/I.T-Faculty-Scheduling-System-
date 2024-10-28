<!DOCTYPE html>
<html lang="en">
<?php 
session_start();
include('./db_connect.php');
ob_start();
ob_end_flush();
?>
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>I.T Faculty Scheduling System</title>

  <!-- Include Font Awesome for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<?php include('./header.php'); ?>
<?php 
if(isset($_SESSION['login_id']))
header("location:index.php?page=home");

?>

</head>
<style>
	body{
		width: 100%;
	    height: calc(100%);
	}
	main#main{
		width:100%;
		height: calc(100%);
		background:white;
	}
	#login-right{
		position: absolute;
		right:0;
		width:40%;
		height: calc(100%);
		background: url('assets/img/goo.png'); /* Updated image path */
		display: flex;
		align-items: center;
	}
	#login-left{
		position: absolute;
		left:0;
		width:60%;
		height: calc(100%);
		background:#59b6ec61;
		display: flex;
		align-items: center;
		background: url('assets/img/go.png'); /* Updated image path */
	    background-repeat: no-repeat;
	    background-size: cover;
	}
	#login-right .card{
		margin: auto;
		z-index: 1;
		background: rgba(255, 255, 255, 0.9); /* Slight white transparency for card background */
	}
	.logo {
    	margin: auto;
    	font-size: 8rem;
    	background: white;
    	padding: .5em 0.7em;
    	border-radius: 50% 50%;
    	color: #000000b3;
    	z-index: 10;
	}
	div#login-right::before {
	    content: "";
	    position: absolute;
	    top: 0;
	    left: 0;
	    width: calc(100%);
	    height: calc(100%);
	    background: ;
	}
	.form-group {
		position: relative;
	}
	.form-group i {
		position: absolute;
		top: 80%;
		left: 10px;
		transform: translateY(-50%);
		color: #aaa;
	}
	.form-control {
		padding-left: 30px; /* Space for the icons */
	}
</style>

<body>

  <main id="main" class=" bg-dark">
  		<div id="login-left">
  		</div>

  		<div id="login-right">
  			<div class="card col-md-8">
  				<div class="card-body">
  					<form id="login-form" >
  						<div class="form-group">
  							<label for="username" class="control-label">Username</label>
  							<i class="fas fa-user"></i> <!-- Username Icon -->
  							<input type="text" id="username" name="username" class="form-control">
  						</div>
  						<div class="form-group">
  							<label for="password" class="control-label">Password</label>
  							<i class="fas fa-lock"></i> <!-- Password Icon -->
  							<input type="password" id="password" name="password" class="form-control">
  						</div>
  						<center><button class="btn-sm btn-block btn-wave col-md-4 btn-primary">Login</button></center>
  					</form>
  				</div>
  			</div>
  		</div>
  </main>

  <a href="#" class="back-to-top"><i class="icofont-simple-up"></i></a>

</body>
<script>
	$('#login-form').submit(function(e){
		e.preventDefault()
		$('#login-form button[type="button"]').attr('disabled',true).html('Logging in...');
		if($(this).find('.alert-danger').length > 0 )
			$(this).find('.alert-danger').remove();
		$.ajax({
			url:'ajax.php?action=login',
			method:'POST',
			data:$(this).serialize(),
			error:err=>{
				console.log(err)
		$('#login-form button[type="button"]').removeAttr('disabled').html('Login');

			},
			success:function(resp){
				if(resp == 1){
					location.href ='index.php?page=home';
				}else{
					$('#login-form').prepend('<div class="alert alert-danger">Username or password is incorrect.</div>')
					$('#login-form button[type="button"]').removeAttr('disabled').html('Login');
				}
			}
		})
	})
</script>	
</html>
