<!DOCTYPE html>
<html lang="en">

<head>

	<meta name="mobile-web-app-capable" content="yes">
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">

	<meta name="google-signin-scope" content="profile email">
	<meta name="google-signin-client_id" content="975215138073-q01j0lkacgag3t9sfmb91qltkjk6efne.apps.googleusercontent.com">
	<!--<meta name="google-signin-client_id" content="146152365447-7qrk3j0bap218qgj39gpa3taqrmct7ek.apps.googleusercontent.com">-->


	<script src="https://apis.google.com/js/platform.js" async defer></script>
	<script type="module" src="src/Views/Login/login.js"></script>


	<title><?php //echo $servername;
			?></title>
	<link rel="icon" type="image/png" href="">

	<link rel="stylesheet" href="Assets/plugins/fontawesome-free/css/all.min.css">
	<!-- icheck bootstrap -->
	<link rel="stylesheet" href="Assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
	<!-- Theme style -->
	<link rel="stylesheet" href="Assets/dist/css/adminlte.min.css">

	<style type="text/css">
		.errormessage {
			color: #F00;
		}

		.bg-image {
			/* Add the blur effect */
			filter: blur(8px);
			-webkit-filter: blur(8px);

			/* Full height */
			height: 100%;

			/* Center and scale the image nicely */
			background-position: center;
			background-repeat: no-repeat;
			background-size: cover;
		}

		div.blur {

			background: rgba(255, 255, 255, 0.1);
			-webkit-backdrop-filter: blur(24px);
			backdrop-filter: blur(24px);
			border: 1px solid rgba(255, 255, 255, 0.05);

		}


		.dt-buttons button {
			border-radius: 4px;
			background-color: #18a4bc;
			color: white;
			padding: 7px 15px 7px 15px;
			cursor: pointer;
			border: 2px solid #f8f4fc;


		}

		.dt-buttons button:hover {
			background-color: #0f8fa5;
			border: 2px solid #f8f4fc;
		}

		.dt-buttons button:active {
			background-color: #028aa2;
			border: 2px solid #f8f4fc;
		}
	</style>

</head>

<body class="bg-gradient-primarys" background="" style="background-position:center; background-repeat:no-repeat; background-size: cover;">

	<div class="container">

		<!-- Outer Row -->
		<div class="row justify-content-center">

			<div class="col-xl-6 col-lg-12 col-md-9">

				<div class="card o-hidden border-0 shadow-lg my-5 blur" style="min-height:80vh;">
					<div class="card-body p-0">
						<!-- Nested Row within Card Body -->
						<div class="row">
							<div class="col-lg-12" align="center">
								<div class="pl-5 pr-5 pt-3">
									<div class="text-center">
										<img src="Assets\assets\images\uratex-logo.png" width="50%" align="middle">
									</div>
									<hr>
									<div class="text-center">
										<h1 class="h4 mb-4"><strong>Stock Alignment</strong></h1>
									</div>

									<div class="form-group" id="profilepicdiv" style="display:none">
										<img src="" width="40%" align="middle" id="profilepic" style="border-radius: 50%;">
									</div>

									<div class="alert alert-danger" role="alert">
										A simple danger alert—check it out!
									</div>

									<form class="user" id="loginFrm" method="POST">
										<div class="form-group" id="gsigninbtn">
											<div class="input-group mb-3">
												<input type="text" class="form-control" placeholder="User ID" id="InputEmail" name="InputEmail" required>
												<div class="input-group-append">
													<div class="input-group-text">
														<span class="fas fa-user"></span>
													</div>
												</div>
											</div>
											<div class="input-group mb-3">
												<input type="password" class="form-control" placeholder="Password" id="InputPassword" name="InputPassword" autocomplete="new-password" required>
												<div class="input-group-append">
													<div class="input-group-text">
														<span class="fas fa-lock"></span>
													</div>
												</div>
											</div>

											<div class="errormessage" id="errormessage">
												<strong> </strong>
											</div>

											<input type="submit" id="loginBtn" value="Login" class="btn btn-primary btn-user btn-block" style="font-size:15px; padding:5px; font-weight:bold" />

											<hr>


										</div>

									</form>

									<hr>
									<div class="text-center">

										<a href="#"><button class="btn btn-info buttons-print" tabindex="0" aria-controls="example1" type="button"><span>Forgot Password?</span></button></a>
										<br>
										<a href="tel:(02)88886800"><i class="fa fa-phone"> Contact Us </i></a>
									</div>
								</div>

								<div class="errormessage" id="errormessage">
									<strong> </strong>
								</div>
								<input type="submit" value="Login" class="btn btn-primary btn-user btn-block" style="font-size:15px; padding:5px; font-weight:bold" />
								<hr>
							</div>

							</form>

							<hr>
							<div class="text-center">

							</div>

						</div>

					</div>

					<!-- jQuery -->
					<script src="Assets/plugins/jquery/jquery.min.js"></script>
					<!-- Bootstrap 4 -->
					<script src="Assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
					<!-- AdminLTE App -->
					<script src="Assets/dist/js/adminlte.min.js"></script>

</body>

</html>