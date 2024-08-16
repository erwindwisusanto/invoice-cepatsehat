<x-master-layout>
	<div class="pages-bg">
		<div class="container">
			<div class="text-center">
				<img src="{{ asset('assets/img/img-invoice.svg') }}" class="img-content" alt="" />
				<h4 class="title-pages">SignUp Online Invoice</h4>
			</div>
			<form class="mt-5" id="form-signup" novalidate>
				@method('POST')
				@csrf
				<div class="mb-4">
					<label for="" class="form-label">Name</label>
					<input
						type="text"
						class="form-control"
						id="name"
						name="name"
						placeholder="Dr. Budiono Siregar"
						required
						/>
				</div>
				<div class="mb-4">
					<label for="" class="form-label">Phone Number <small style="color: red;">*FORMAT NUMBER 62821107XXX</small></label>
					<input
						type="number"
						class="form-control"
						id="phone"
						name="phone"
						placeholder="6282111XXX"
						required
						/>
				</div>
				<div class="mb-4">
					<label for="" class="form-label">Email Address</label>
					<input
						type="email"
						class="form-control"
						id="email"
						name="email"
						placeholder="buidiono12@gcepatsehat.com"
						required
						/>
				</div>
				<div class="mb-4">
					<label for="" class="form-label">Password</label>
					<input
						type="password"
						class="form-control mb-2"
						id="password"
						name="password"
						placeholder="******"
						required
						/>
				</div>
				<button type="submit" class="btn btn-primary w-100">
					Submit &nbsp;
					<span id="loading-spinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
				</button>
			</form>
			<div class="text-center mt-3">
				<a href="{{ route('signin') }}" class="fs-14 fw-semibold text-dark">
					Already have an account?
				</a>
			</div>
			<div class="copyright">
				<img
					src="{{ asset('assets/img/logo-cepatsehat.png') }}"
					class="logo-cepatsehat"
					alt="cepatsehat"/>
			</div>
		</div>
	</div>
</x-master-layout>
<script>
	'use strict';

	const submitSignUp = (form) => {
		$.ajax({
			type: 'POST',
			url: '{{ route("post_signup") }}',
			data: $(form).serialize(),
			success: function(response) {
				if (response.success === false) {
					Swal.fire({
						icon: 'error',
						text: 'Please try again.',
						customClass: {
							confirmButton: 'btn btn-primary'
						}
					});
				} else {
					Swal.fire({
						icon: 'success',
						text: 'Signup successful!',
						timer: 1500,
    				showConfirmButton: false
					}).then(() => {
						window.location.href = '/signin';
					});
				}
			},
			error: function(xhr, status, error) {
				let errorMessage = '';
				if (xhr.responseJSON && xhr.responseJSON.message) {
					let hasRelevantErrors = false;
					for (let key in xhr.responseJSON.message) {
							if (xhr.responseJSON.message.hasOwnProperty(key)) {
								if (key === 'email' || key === 'phone') {
									errorMessage += `${xhr.responseJSON.message[key][0]}<br>`;
									hasRelevantErrors = true;
								}
							}
					}

					if (hasRelevantErrors) {
						Swal.fire({
							icon: 'warning',
							title: 'Warning',
							html: errorMessage,
							customClass: {
								confirmButton: 'btn btn-primary'
							}
						});
					} else {
						Swal.fire({
							icon: 'error',
							title: 'Internal Server Error',
							text: 'An error occurred. Please try again later.',
							customClass: {
								confirmButton: 'btn btn-primary'
							}
						});
					}
				} else {
					Swal.fire({
						icon: 'error',
						title: 'Error',
						text: 'An error occurred. Please try again.',
						customClass: {
							confirmButton: 'btn btn-primary'
						}
					});
				}
			}
		});
	}

	$(document).ready(function () {
		$('#form-signup').validate({
			errorClass: 'is-invalid',
			validClass: 'is-valid',
			errorElement: 'div',
			errorPlacement: function (error, element) {
				error.addClass('invalid-feedback');
				element.closest('.mb-4').append(error);
			},
			highlight: function (element, errorClass, validClass) {
				$(element).addClass(errorClass).removeClass(validClass);
				$(element).closest('.form-group').find('.form-control').addClass(errorClass).removeClass(validClass);
			},
			unhighlight: function (element, errorClass, validClass) {
				$(element).removeClass(errorClass).addClass(validClass);
				$(element).closest('.form-group').find('.form-control').removeClass(errorClass).addClass(validClass);
			},
			rules: {
				name: { required: true, minlength: 3 },
				phone: { required: true, minlength: 10, maxlength: 20 },
				email: { required: true, email: true },
				password: { required: true, minlength: 6 }
			},
			messages: {
				name: {
					required: "Please enter your full name",
					minlength: "Your password must be at least 3 characters long"
				},
				phone: {
					required: "Please enter your phone number",
					minlength: "Your password must be at least 10 characters long",
					maxlength: "Your password must be at least 20 characters long"
				},
				email: {
					required: "Please enter your email address",
					email: "Please enter a valid email address"
				},
				password: {
					required: "Please provide a password",
					minlength: "Your password must be at least 6 characters long"
				}
			},
			submitHandler: submitSignUp
		});
	});
</script>
