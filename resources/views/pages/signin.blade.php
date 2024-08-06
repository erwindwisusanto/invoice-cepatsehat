<x-master-layout>
	<div class="pages-bg">
		<div class="container">
			<div class="text-center">
				<img src="{{ asset('assets/img/img-invoice.svg') }}" class="img-content" alt="" />
				<h4 class="title-pages">Online Invoice</h4>
			</div>
			<form class="mt-5" id="form-signin" novalidate>
				@method('POST')
				@csrf
				<div class="mb-4">
					<label for="" class="form-label">Email Address</label>
					<input
						type="email"
						class="form-control"
						id="email"
						name="email"
						placeholder="Enter email address"
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
						placeholder="Enter your password"
						required
						/>
				</div>
				<a href="#" class="text-primary fs-14 text-end">
					<p>Forgot Password?</p>
				</a>
				<button type="submit" class="btn btn-primary w-100" id="submit-button">
					Sign In &nbsp;
					<span id="loading-spinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
				</button>
			</form>
			<div class="text-center mt-3">
				<a href="{{ route('signup') }}" class="fs-14 fw-semibold text-dark">
					Create new account
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

	const submitSignIn = (form) => {
		const submitButton = $('#submit-button');
    const loadingSpinner = $('#loading-spinner');
		const disabledClass = 'btn-disabled';

		submitButton.prop('disabled', true);
		submitButton.addClass(disabledClass);
    loadingSpinner.removeClass('d-none');

		setTimeout(() => {
			$.ajax({
				type: 'POST',
				url: '{{ route("post_signin") }}',
				data: $(form).serialize(),
				success: function(response) {
					window.location.href = "/";
					history.replaceState(null, null, "/");
				},
				error: function(xhr, status, error) {
					Swal.fire({
						icon: 'error',
						text: xhr.responseJSON?.message,
					});
				},
				complete: function() {
					submitButton.prop('disabled', false);
					submitButton.removeClass(disabledClass);
					loadingSpinner.addClass('d-none');
				}
			});
		}, 1000);
	}

	$(document).ready(function () {
		$('#form-signin').validate({
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
				email: {
					required: true,
					email: true
				},
				password: {
					required: true,
					minlength: 6
				}
			},
			messages: {
				email: {
					required: "Please enter your email address",
					email: "Please enter a valid email address"
				},
				password: {
					required: "Please provide a password",
					minlength: "Your password must be at least 6 characters long"
				}
			},
			submitHandler: submitSignIn
		});
	});
</script>
