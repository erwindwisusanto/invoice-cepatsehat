<x-master-layout>
	<x-breadcrumbs route="{{ route('view_invoice') }}" title="New Invoice" />
	<div class="content-body pb-5">
		<div class="container">
			<div class="mb-2">
				<p class="fs-14 fw-semibold mb-0">
					No.
					<span class="text-muted fw-normal">{{ $invoiceNumber }}</span>
				</p>
			</div>
			<div class="mb-3">
				<p class="fs-14 fw-semibold">
					Date:
					<span class="text-muted fw-normal">{{ $date }}</span>
				</p>
			</div>
			<form class="mt-2" id="form-new-invoice" novalidate>
				<div class="mb-4">
					<label for="" class="form-label">Username*</label>
					<input
						type="text"
						class="form-control bg-white"
						id="username"
						name="username"
						aria-describedby="username"
						placeholder="Complete name" />
				</div>
				<div class="mb-4">
					<label for="" class="form-label">Address*</label>
					<textarea
						name="address"
						class="form-control bg-white"
						rows="3"
						placeholder="Address ..."
						id="address"></textarea>
				</div>
				<div class="mb-4">
					<label for="" class="form-label">Phone*</label>
					<input
						type="number"
						class="form-control bg-white"
						id="phone_numner"
						name="phone_numner"
						aria-describedby=""
						placeholder="Enter phone number" />
				</div>
				<hr />
				<div class="d-flex align-items-center justify-content-between">
					<h6 class="fw-semibold fs-14 mb-0">Description/Diagnosis</h6>
					<a
						data-bs-toggle="offcanvas"
						href="#offcanvasDiagnosis"
						role="button"
						aria-controls="offcanvasDiagnosis"
						class="text-decoration-none text-primary fs-14">
						<i class="mdi mdi-plus-circle"></i>
						Add more
					</a>
				</div>

				<div class="list-diagnosis">
					<div class="items-diagnosis">

					</div>
					<div class="items-diagnosis">

					</div>
				</div>
				<hr />
				<div class="mb-4">
					<label for="" class="form-label">Complimentary Discount</label>
					<input
						type="number"
						class="form-control bg-white"
						id="complimentary_discount"
						name="complimentary_discount"
						aria-describedby=""
						placeholder="eg. 500.000" />
				</div>

				<div class="mb-4">
					<label for="" class="form-label">
						Medical team transport cost
					</label>
					<input
						type="number"
						class="form-control bg-white"
						id="medical_team_transport_cost"
						name="medical_team_transport_cost"
						aria-describedby=""
						placeholder="eg. 500.000" />
				</div>

				<div class="mb-4">
					<label for="" class="form-label">Payment Method</label>
					<select class="form-select" id="multiple-select-field" data-placeholder="Choose Payment Method" multiple>
						@foreach($paymentMethods as $method)
							<option value="{{ $method->id }}">{{ $method->name }}</option>
						@endforeach
					</select>
				</div>

				<div class="row gx-2">
					<div class="col-6">
						<a href="#" class="btn btn-outline-primary w-100">
							Save as Draft
						</a>
					</div>
					<div class="col-6">
						<a href="#" class="btn btn-primary w-100">Submit</a>
					</div>
				</div>
			</form>
		</div>
	</div>
	<div
		class="offcanvas custom offcanvas-bottom"
		tabindex="-1"
		id="offcanvasDiagnosis"
		aria-labelledby="offcanvasDiagnosisLabel">
		<div class="offcanvas-header align-items-start pb-0">
			<h5 class="offcanvas-title fs-14" id="offcanvasDiagnosisLabel">
				Diagnosis
			</h5>
			<button
				type="button"
				class="btn-close"
				data-bs-dismiss="offcanvas"
				aria-label="Close"></button>
		</div>
		<div class="offcanvas-body pb-3">
			<form id="formpopup">
				<div class="mb-4">
					<label for="cpt" class="form-label">CPT Code*</label>
					<div id="cpt_cube">
						<div class="cpt-row d-flex align-items-center" style="margin-bottom: 10px;">
							<select class="form-select bg-white selecttwo" aria-label="Default select example" id="cpt" name="cpt_1">
								@foreach ($cpts as $cpt)
									<option value="{{ $cpt->id }}">{{ $cpt->code .' - '. $cpt->description }}</option>
								@endforeach
							</select>
							<button class="btn btn btn-remove remove-btn" type="button" style="margin-left: 10px;">
								<i class="mdi mdi-close"></i>
							</button>
						</div>
					</div>
				</div>
				<span>
					<button class="btn btn-outline-primary btn-small" type="button" id="add-new-cpt">
						<i class="mdi mdi-plus-circle me-2"></i>
						Add more
					</button>
				</span>
				<div class="mb-4 mt-3">
					<label for="" class="form-label">Pax*</label>
					<input
						type="number"
						class="form-control bg-white"
						id="pax"
						name="pax"
						aria-describedby=""
						placeholder="eg. 1" />
				</div>
				<div class="mb-4">
					<label for="icdx" class="form-label">ICD10 Code*</label>
					<div id="icdx_cube">
						<div class="icdx-row d-flex align-items-center" style="margin-bottom: 10px;">
							<select class="form-select bg-white selecttwo" aria-label="Default select example" id="icdx" name="icdx_1">
								@foreach ($icdxs as $icdx)
									<option value="{{ $icdx->id }}">{{ $icdx->code .' - '. $icdx->name }}</option>
								@endforeach
							</select>
							<button class="btn btn btn-remove remove-btn-icdx" type="button" style="margin-left: 10px;">
								<i class="mdi mdi-close"></i>
							</button>
						</div>
					</div>
				</div>
				<div class="mb-4">
					<span>
						<button class="btn btn-outline-primary btn-small" type="button" id="add-new-icdx">
							<i class="mdi mdi-plus-circle me-2"></i>
							Add more
						</button>
					</span>
				</div>
				<button type="submit" class="btn btn-primary w-100">Submit</button>
			</form>
		</div>
	</div>
</x-master-layout>
<script>
	"use strict";

	const validateForm = () => {
		$('#form-new-invoice').validate({
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
	}

	let cptCounter = 1;
	let icdxCounter = 1;

	var getNewCptRow = (cptCounter) => {
		return `
			<div class="cpt-row d-flex align-items-center" style="margin-bottom: 10px;">
				<select class="form-select bg-white selecttwo" aria-label="Default select example" name="cpt_${cptCounter}">
					@foreach ($cpts as $cpt)
						<option value="{{ $cpt->id }}">{{ $cpt->code .' - '. $cpt->description }}</option>
					@endforeach
				</select>
				<button class="btn btn btn-remove remove-btn" type="button" style="margin-left: 10px;">
					<i class="mdi mdi-close"></i>
				</button>
			</div>
		`;
	}

	var getNewIcdxRow = (icdxCounter) => {
		return `
			<div class="icdx-row d-flex align-items-center" style="margin-bottom: 10px;">
				<select class="form-select bg-white icdxselect2" aria-label="Default select example" name="icdx_${icdxCounter}">
					@foreach ($icdxs as $icdx)
						<option value="{{ $icdx->id }}">{{ $icdx->code .' - '. $icdx->name }}</option>
					@endforeach
				</select>
				<button class="btn btn btn-remove remove-btn" type="button" style="margin-left: 10px;">
					<i class="mdi mdi-close"></i>
				</button>
			</div>
		`;
	}

	const saveFormData = () => {
    let cptData = [];
    let icdxData = [];

    $('#cpt_cube .cpt-row').each(function() {
      let cptId = $(this).find('select').val();
      cptData.push({ cpt_id: cptId });
    });

		$('#icdx_cube .icdx-row').each(function() {
      let icdxId = $(this).find('select').val();
      icdxData.push({ icdx_id: icdxId });
    });

    let formData = {
      cptData: cptData,
      icdxData: icdxData,
    };

		console.log(formData);
    return formData;
	};

	$('#add-new-cpt').click(function(e) {
		e.preventDefault();
		const newCptRow = getNewCptRow(cptCounter);
		$('#cpt_cube').append(newCptRow);
		$('.selecttwo').select2();
		cptCounter++;
	});

	$('#add-new-icdx').click(function(e) {
		e.preventDefault();
		const newIcdxRow = getNewIcdxRow(icdxCounter);
		$('#icdx_cube').append(newIcdxRow);
		$('.icdxselect2').select2();
		icdxCounter++;
	});

	$('#cpt_cube').on('click', '.remove-btn', function(e) {
		e.preventDefault();
		const $cptRow = $(this).closest('.cpt-row');
		if ($('#cpt_cube .cpt-row').length > 1) {
			$cptRow.remove();
		} else {
			$(this).prop('disabled', true);
		}
		saveFormData();
	});

	$('#formpopup').on('submit', function(e) {
    e.preventDefault();
    let formData = saveFormData();
    console.log(formData);
	});

	$(document).ready(function () {
		$('.selecttwo').select2({
			dropdownParent: $("#offcanvasDiagnosis")
		});

		$('#multiple-select-field').select2( {
			width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
			placeholder: $( this ).data( 'placeholder' ),
			closeOnSelect: true,
		});
	});
</script>
