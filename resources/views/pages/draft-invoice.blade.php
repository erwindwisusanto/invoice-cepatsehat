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
					<span class="text-muted fw-normal">{{ $formattedDate }}</span>
				</p>
			</div>
			<form class="mt-2" id="form-new-invoice" novalidate>
				@method('POST')
				@csrf
				<input type="hidden" name="invoice_number" id="invoice_number" value="{{ $invoiceNumber }}">
				<div class="mb-4">
					<label for="" class="form-label">Username*</label>
					<input
						type="text"
						class="form-control bg-white"
						id="username"
						name="username"
						aria-describedby="username"
						placeholder="Complete name"
						value="{{ $username ?? '-' }}"
						/>
				</div>
				<div class="mb-4">
					<label for="" class="form-label">Address*</label>
					<textarea
						name="address"
						class="form-control bg-white"
						rows="3"
						placeholder="Address ..."
						id="address">{{ $address ?? '' }}</textarea>
				</div>
				<div class="mb-4">
					<label for="" class="form-label">Phone*</label>
					<input
						type="number"
						class="form-control bg-white"
						id="phone_number"
						name="phone_number"
						aria-describedby=""
						placeholder="Enter phone number"
						value="{{ $phone ?? '-' }}"
						/>
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
						placeholder="eg. 500.000"
						value="{{ $complimentaryDiscount ?? 0 }}"
						/>
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
						placeholder="eg. 500.000"
						value="{{ $medicalTeamTransportCost ?? 0 }}"
						/>
				</div>
				<div class="mb-4">
					<label for="payment_method" class="form-label">Payment Method</label>
					<select class="form-select" id="payment_method" name="payment_method[]" data-placeholder="Choose Payment Method" multiple>
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
						<button type="submit" class="btn btn-primary w-100" id="submit-new-invoice">
							Submit &nbsp;
							<span id="loading-spinner-invoice" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
						</button>
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
		<div class="offcanvas-body pb-3" style="overflow-y: auto;">
			<form id="formpopup">
				<div class="mb-4">
					<label for="cpt" class="form-label">CPT Code*</label>
					<div id="cpt_cube">
						<div class="cpt-row d-flex align-items-center" style="margin-bottom: 10px;">
							<select class="form-select bg-white selecttwo" aria-label="Default select example" id="cpt" name="cpt_1">
								@foreach ($cpts as $cpt)
									<option value="{{ $cpt->id }}" data-desc="{{ $cpt->description }}" data-code="{{ $cpt->code }}">{{ $cpt->code .' - '. $cpt->description }}</option>
								@endforeach
							</select>
						</div>
					</div>
				</div>
				<div class="mb-4 d-none" id="cube_infusion">
					<label for="infusion" class="form-label">Infusions*</label>
					<div id="infusion_cube">
						<div class="d-flex align-items-center" style="margin-bottom: 10px;">
							<select class="form-select bg-white selecttwo" aria-label="Default select example" id="infusion" name="infusion">
								@foreach ($infusions as $infusion)
									<option value="{{ $infusion->price }}" data-desc="{{ $infusion->infusion }}">{{ $infusion->infusion }}</option>
								@endforeach
							</select>
						</div>
					</div>
				</div>
				<div class="mb-4 mt-3">
					<label for="pax" class="form-label">Pax*</label>
					<input
						type="number"
						class="form-control bg-white"
						id="pax"
						name="pax"
						aria-describedby=""
						placeholder="eg. 1" required/>
				</div>
				<div class="mb-4 mt-3 d-none custom_additional_cube">
					<label for="custom_price" class="form-label">Price*</label>
					<input
						type="number"
						class="form-control bg-white"
						id="custom_price"
						name="custom_price"
						aria-describedby=""
						placeholder="eg. 10.000"/>
				</div>
				<div class="mb-4 mt-3 d-none custom_additional_cube">
					<label for="custom_additional" class="form-label">Additional*</label>
					<textarea
						name="custom_additional"
						class="form-control bg-white"
						rows="3"
						placeholder="Paracetamol etc..."
						id="custom_additional"></textarea>
				</div>
				<div class="mb-4 d-none icdx_code">
					<label for="icdx" class="form-label">ICD10 Code*</label>
					<div id="icdx_cube">
						<div class="icdx-row d-flex align-items-center" style="margin-bottom: 10px;">
							<select class="form-select bg-white selecttwo" aria-label="Default select example" id="icdx" name="icdx_1">
								<option value="" data-desc="" data-code=""></option>
								@foreach ($icdxs as $icdx)
									<option value="{{ $icdx->id }}" data-desc="{{ $icdx->name }}" data-code="{{ $icdx->code }}">{{ $icdx->code .' - '. $icdx->name }}</option>
								@endforeach
							</select>
							<button class="btn btn btn-remove remove-btn-icdx" type="button" style="margin-left: 10px;">
								<i class="mdi mdi-close"></i>
							</button>
						</div>
					</div>
				</div>
				<div class="mb-4 d-none icdx_code">
					<span>
						<button class="btn btn-outline-primary btn-small" type="button" id="add-new-icdx">
							<i class="mdi mdi-plus-circle me-2"></i>
							Add more
						</button>
					</span>
				</div>
				<button type="submit" class="btn btn-primary w-100" id="">
					Submit &nbsp;
					<span id="loading-spinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
				</button>
			</form>
		</div>
	</div>
</x-master-layout>
<script>
	"use strict";

	let cptCounter = 1;
	let icdxCounter = 1;
	let cptDatax = @json($diagnosis);

	const Toast = Swal.mixin({
		toast: true,
		position: "top-end",
		showConfirmButton: false,
		timer: 2000,
		timerProgressBar: true,
		didOpen: (toast) => {
			toast.onmouseenter = Swal.stopTimer;
			toast.onmouseleave = Swal.resumeTimer;
		}
	});

	const submitNewInvoice = (form) => {
		const submitButton = $('#submit-new-invoice');
    const loadingSpinner = $('#loading-spinner-invoice');
		const disabledClass = 'btn-disabled';

		submitButton.prop('disabled', true);
		submitButton.addClass(disabledClass);
    loadingSpinner.removeClass('d-none');

		setTimeout(() => {
			$.ajax({
				type: 'POST',
				url: '{{ route("post_new_invoice") }}',
				data: {
					form: $(form).serialize(),
					form2: JSON.stringify(cptDatax),
					_token: $('meta[name="csrf-token"]').attr('content')
				},
				success: function(response) {
					if (response.status == 'success') {
						window.location.href = "/";
						history.replaceState(null, null, "/");
					}
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

	var getNewIcdxRow = (icdxCounter) => {
		return `
			<div class="icdx-row d-flex align-items-center" style="margin-bottom: 10px;">
				<select class="form-select bg-white icdxselect2" aria-label="Default select example" name="icdx_${icdxCounter}">
					@foreach ($icdxs as $icdx)
						<option value="{{ $icdx->id }}" data-desc="{{ $icdx->name }}" data-code="{{ $icdx->code }}">{{ $icdx->code .' - '. $icdx->name }}</option>
					@endforeach
				</select>
				<button class="btn btn btn-remove remove-btn-icdx" type="button" style="margin-left: 10px;">
					<i class="mdi mdi-close"></i>
				</button>
			</div>
		`;
	}

	const saveFormData = () => {
    let cpt = parseInt($('#cpt').val());
    let pax = $('#pax').val();

		let customAdditional;
		let infusionValue;
		let infusionName;

		if (cpt === 3 || cpt === 4 || cpt === 5) {
			infusionValue = $('#custom_price').val();
			customAdditional = $('#custom_additional').val() || '';
		} else if (cpt === 2) {
			let infusionElement = $('#infusion');
			let selectedOptionInf = infusionElement.find('option:selected');
			infusionValue = infusionElement.val() || 0;
			infusionName = selectedOptionInf.data('desc');
		}

    let selectElement = $('#cpt_cube .cpt-row').find('select');
    let cptId = selectElement.val();
    let selectedOption = selectElement.find('option:selected');
    let nameDiagnosis = selectedOption.data('desc');
    let diagnosisCode = selectedOption.data('code');

    if (cptId) {
			let icdxData = [];
			$('#icdx_cube .icdx-row').each(function() {
					let selectElement = $(this).find('select');
					let icdxId = selectElement.val();
					let selectedOption = selectElement.find('option:selected');
					let descIcdx = selectedOption.data('desc');
					let icdxCode = selectedOption.data('code');

					if (icdxId) {
						icdxData.push({
							icdx_id: icdxId,
							icdx_desc: descIcdx || '',
							icdx_code: icdxCode
					});
				}
			});

			cptDatax.push({
				cpt_id: cptId,
				cpt_code: diagnosisCode,
				cpt_pax: pax,
				cpt_desc: nameDiagnosis || '',
				cpt_price: infusionValue,
				cpt_infusion: infusionName,
				cpt_additional: customAdditional,
				cpt_icd: icdxData
			});
    }

    let formData = {
      cptData: cptDatax
    };

    return JSON.stringify(formData);
	};


	const listItemsSelected = (data) => {
    $('.items-diagnosis').empty();

    data?.cptData?.forEach((cpt, cptIndex) => {
			let infusionName = cpt?.cpt_infusion ? `- ${cpt?.cpt_infusion}` : '';
			let additionalReceipt = cpt?.cpt_additional ? `- ${cpt?.cpt_additional}` : '';
			let cptHtml = `
				<span class="fs-12">${cpt.cpt_code}</span>
				<div class="d-flex align-items-center justify-content-between mb-2">
						<div class="detail">
							<h5>${cpt.cpt_desc}</h5>
							<span>${infusionName}</span>
							<span>${additionalReceipt}</span>
						</div>
						<div class="status">
							<span class="fs-14">${cpt.cpt_pax}pax</span>
							<button class="btn btn-remove" data-cpt-index="${cptIndex}">
								<i class="mdi mdi-close"></i>
							</button>
						</div>
				</div>
			`;

			$('.items-diagnosis').append(cptHtml);
				cpt?.cpt_icd?.forEach((icd, icdIndex) => {
					$('.items-diagnosis').append(`
						<div class="d-flex align-items-center justify-content-between mb-2">
								<div class="detail">
									<h5>(${icd.icdx_code}) ${icd.icdx_desc}</h5>
								</div>
								<div class="status">
									<button class="btn btn-remove" data-cpt-index="${cptIndex}" data-icd-index="${icdIndex}">
										<i class="mdi mdi-close"></i>
									</button>
								</div>
						</div>
					`);
			});
    });

		$('.btn-remove').on('click', function () {
			const cptIndex = $(this).data('cpt-index');
			const icdIndex = $(this).data('icd-index');

			if (icdIndex !== undefined) {
				cptDatax[cptIndex].cpt_icd.splice(icdIndex, 1);
			} else {
				cptDatax.splice(cptIndex, 1);
			}

			listItemsSelected({ cptData: cptDatax });
    });
	};

	$('#formpopup').on('submit', function(e) {
		e.preventDefault();
		$('#offcanvasDiagnosis').offcanvas('hide');
		let data = saveFormData();
		let dataParse = JSON.parse(data);
		listItemsSelected(dataParse);
		resetFormPopup();
	});

	$('#add-new-icdx').click(function(e) {
		e.preventDefault();
		const newIcdxRow = getNewIcdxRow(icdxCounter);
		$('#icdx_cube').append(newIcdxRow);
		$('.icdxselect2').select2();
		icdxCounter++;
	});

	$('#icdx_cube').on('click', '.remove-btn-icdx', function(e) {
		e.preventDefault();
		const $cptRow = $(this).closest('.icdx-row');
		if ($('#icdx_cube .icdx-row').length > 1) {
			$cptRow.remove();
		} else {
			$(this).prop('disabled', true);
		}
		saveFormData();
	});

	var resetFormPopup = () => {
		let firstRowFound = false;
		$('#icdx_cube .icdx-row').each(function() {
			let selectName = $(this).find('select').attr('name');
			if (selectName === 'icdx_1') {
				if (!firstRowFound) {
					firstRowFound = true;
				} else {
					$(this).remove();
				}
			} else {
				$(this).remove();
			}
		});
	}

	$('[href="#offcanvasDiagnosis"]').on('click', function() {
		resetFormPopup();
	});

	const defaultData = {
		"cpt_id": "1",
		"cpt_code": 99451,
		"cpt_pax": "1",
		"cpt_desc": "Konsultasi telehealth, evaluasi dan manajemen data medis jarak jauh. (konsultasi dokter)",
		"cpt_price": "500000",
		"cpt_infusion": "",
		"cpt_additional": "",
		"cpt_icd": []
	};

	$(document).ready(function () {
		listItemsSelected({ cptData: cptDatax });

		$('.selecttwo').select2({
			dropdownParent: $("#offcanvasDiagnosis"),
			width: '100%'
		});

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
				username: { required: true },
				address: { required: true },
				phone_number: { required: true },
				complimentary_discount: { required: false },
				medical_team_transport_cost: { required: false },
				payment_method: { required: true },
			},
			messages: {
				username: { required: "Username required"},
				address: { required: "Address required" },
				phone_number: { required: "Phone number required" },
				payment_method: { required: "Payment method required" },
			},
			submitHandler: submitNewInvoice
		});

		$('#cpt').on('change', function() {
			const defaultOption = 1;
			const infusion = 2;
			const additional1 = 3;
			const additional2 = 4;
			const additional3 = 5;
			let selectedValue = $(this).val();
			if (parseInt(selectedValue) === infusion) {
				$('#cube_infusion').removeClass('d-none');
				$('.icdx_code').removeClass('d-none');
				$('.custom_additional_cube').addClass('d-none');
			} else if (parseInt(selectedValue) === defaultOption) {
				$('.icdx_code').addClass('d-none');
				$('#cube_infusion').addClass('d-none');
				$('.custom_additional_cube').addClass('d-none');
			} else if (parseInt(selectedValue) === additional1 || parseInt(selectedValue) === additional2 || parseInt(selectedValue) === additional3) {
				$('.custom_additional_cube').removeClass('d-none');
				$('#cube_infusion').addClass('d-none');
				$('.icdx_code').removeClass('d-none');
			} else {
				$('#cube_infusion').addClass('d-none');
				$('.icdx_code').removeClass('d-none');
				$('.custom_additional_cube').addClass('d-none');
			}
    });

		$('#payment_method').select2( {
			width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
			placeholder: $( this ).data( 'placeholder' ),
			closeOnSelect: true,
		});

		var selectedMethods = {{ $paymentMethodSelected ?? [] }};
		$('#payment_method').val(selectedMethods).trigger('change');
	});

	const defaultCpt = () => {
		$.ajax({
			type: 'GET',
			url: '{{ route("get_default_cpt") }}',
			success: function(response){
				console.log(response);
			},
		});
	}
</script>
