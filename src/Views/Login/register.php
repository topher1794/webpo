<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<meta name="description" content="This is a login page template based on Bootstrap 5">
	<title>Stock Alignment - Registration</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
	<link rel="stylesheet" href="Assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">

	<script type="module" src="src/Views/Login/register.js"></script>
</head>

<body>
	<section class="h-100">
		<div class="container h-100 pt-5">
			<div class="row justify-content-sm-center h-100">
				<div class="col-xxl-4 col-xl-5 col-lg-5 col-md-7 col-sm-9">

					<div class="card shadow-lg">

						<div class="card-body p-5">
							<div class="text-center my-2">
								<figure>
									<img src="Assets/Logo/uratex_only.png" alt="logo" width="250" height="130">
								</figure>
							</div>
							<div class="alert alert-danger" id="alert" role="alert">
								This is a danger alert—check it out!
							</div>
							<h1 class="fs-4 card-title fw-bold mb-4">Register</h1>
							<form id="registerFrm" class="needs-validation" novalidate autocomplete="off">
								<div class="mb-3">
									<!-- <label class="mb-2 text-muted" for="name">Name</label> -->
									<input id="username" type="text" class="form-control" name="username" value="" placeholder="Username" required>
									<div class="invalid-feedback">
										Name is required
									</div>
								</div>

								<div class="mb-3">
									<input id="firstname" type="text" class="form-control" name="firstname" value="" placeholder="First Name" required>
									<div class="invalid-feedback">
										First Name is required
									</div>
								</div>

								<div class="mb-3">
									<input id="lastname" type="text" class="form-control" name="lastname" value="" placeholder="Last Name" required>
									<div class="invalid-feedback">
										Last Name is required
									</div>
								</div>

								<div class="mb-3">
									<select class="form-control" name="company" id="company">
										<option value="ROBERTS">ROBERTS</option>
										<option value="URATEX">URATEX</option>
									</select>
								</div>

								<div class="mb-3">
									<!-- <label class="mb-2 text-muted" for="email">E-Mail Address</label> -->
									<input id="email" type="email" class="form-control" name="email" value="" placeholder="Email" required>
									<div class="invalid-feedback">
										Email is invalid
									</div>
								</div>

								<div class="mb-3">
									<input id="password" type="password" class="form-control" name="password" placeholder="Password" required>
									<p class="mt-2" id="passwordFeedBack" hidden>SAMPLE TEXT</p>
									<div class="invalid-feedback">
										Password is required
									</div>
								</div>

								<div class="mb-3">
									<input id="confirm-password" type="password" class="form-control" name="confirm-password" placeholder="Confirm Password" required>
									<div class="invalid-feedback">
										Password is required
									</div>
								</div>

								<p class="form-text text-muted mb-3">
									By registering you agree with our terms and condition.
								</p>

								<div class="align-items-center d-flex">
									<button type="submit" id="registerBtn" class="btn btn-primary ms-auto">
										Register
									</button>
								</div>
							</form>
						</div>
						<div class="card-footer py-3 border-0">
							<div class="text-center">
								Already have an account? <a href="login" class="text-dark">Login</a>
							</div>
						</div>
					</div>
					<div class="text-center mt-5 text-muted">
						Copyright &copy; 2025 &mdash; RGC Philippines
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- jQuery -->
	<script src="Assets/plugins/jquery/jquery.min.js"></script>
	<!-- Bootstrap 4 -->
	<script src="Assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
	<!-- SweetAlert2 -->
	<script src="Assets/plugins/sweetalert2/sweetalert2.all.min.js"></script>
	<script src="Assets/plugins/sweetalert2/sweetalert2.min.js"></script>
	<!-- AdminLTE App -->
	<script src="Assets/dist/js/adminlte.min.js"></script>
</body>

</html>