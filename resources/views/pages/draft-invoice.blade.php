<x-master-layout>
	<x-breadcrumbs route="{{ route('view_invoice') }}" title="Edit Invoice" />
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
				<input type="hidden" name="invoice_id" id="invoice_id" value="{{ $invoiceId }}">
				<div class="mb-4">
					<label for="" class="form-label">Name<small style="color: red;">*</small></label>
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
					<label for="" class="form-label">Address<small style="color: red;">*</small></label>
					<textarea
						name="address"
						class="form-control bg-white"
						rows="3"
						placeholder="Address ..."
						id="address">{{ $address ?? '' }}</textarea>
				</div>
				<div class="mb-4">
					<label for="" class="form-label">Phone<small style="color: red;">* 62821107XXX</small></label>
					<input
						type="number"
						class="form-control bg-white"
						id="phone_number"
						name="phone_number"
						aria-describedby=""
						placeholder="Enter phone number 62821107XXX"
						value="{{ $phone ?? '-' }}"
						pattern="62[0-9]{9,14}"
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
					<div class="items-diagnosis" style="padding: 8px 0px 0px 0px;">
					</div>
				</div>
				<hr />
				<div class="mb-4">
					<label for="" class="form-label">Complimentary Discount</label>
					<input
						type="text"
						class="form-control bg-white"
						id="complimentary_discount"
						name="complimentary_discount"
						aria-describedby=""
						placeholder="eg. 240.000"
						value="{{ formatCurrency($complimentaryDiscount) ?? 0 }}"
						oninput="formatCurrency(this)"
						/>
				</div>
				<div class="mb-4">
					<label for="" class="form-label">
						Medical team transport cost
					</label>
					<input
						type="text"
						class="form-control bg-white"
						id="medical_team_transport_cost"
						name="medical_team_transport_cost"
						aria-describedby=""
						placeholder="eg. 450.000"
						value="{{ formatCurrency($medicalTeamTransportCost) ?? 0 }}"
						oninput="formatCurrency(this)"
						/>
				</div>
				<div class="mb-4">
					<label for="cost_night_service" class="form-label">
						Night Service
					</label>
					<input
						type="text"
						class="form-control bg-white"
						id="cost_night_service"
						name="cost_night_service"
						aria-describedby=""
						placeholder="eg. 20.000"
						value="{{ formatCurrency($costNightService) ?? 0 }}"
						oninput="formatCurrency(this)"
						/>
				</div>
				<div class="mb-4">
					<label for="payment_method" class="form-label">Payment Method<small style="color: red;">*</small></label>
					<select class="form-select" id="payment_method" name="payment_method[]" data-placeholder="Choose Payment Method" multiple required>
						@foreach($paymentMethods as $method)
							<option value="{{ $method->id }}">{{ $method->name }}</option>
						@endforeach
					</select>
				</div>
				<div class="mb-4">
					<label for="service" class="form-label">Service</label>
					<select class="form-select" id="service" name="service" data-placeholder="Choose Service">
						@foreach($services as $service)
							<option value="{{ $service->id }}" {{ (int) $service->id === $service_selected ? 'selected' : '' }}>{{ $service->name }}</option>
						@endforeach
					</select>
				</div>
				<div class="row gx-2">
					<div class="col-6">
						<button type="submit" class="btn btn-outline-primary w-100" id="submit-draft-invoice" data-is-draft="true">
							Save as Draft &nbsp;
							<span id="loading-spinner-invoice-draft" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
						</button>
					</div>
					<div class="col-6">
						<button type="submit" class="btn btn-primary w-100" id="submit-new-invoice" data-is-draft="false">
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
			<form id="formpopup" style="overflow-y: visible; height: 80vh;">
				<div class="mb-4">
					<label for="cpt" class="form-label">CPT Code<small style="color: red;">*</small></label>
					<div id="cpt_cube">
						<div class="cpt-row d-flex align-items-center" style="margin-bottom: 10px;">
							<select class="form-select bg-white selecttwo" aria-label="Default select example" id="cpt" name="cpt_1" required>
								@foreach ($cpts as $cpt)
									<option value="{{ $cpt->id }}" data-desc="{{ $cpt->description }}" data-code="{{ $cpt->code }}">{{ $cpt->code .' - '. $cpt->description }}</option>
								@endforeach
							</select>
						</div>
					</div>
				</div>
				<div class="mb-4 d-none" id="cube_infusion">
					<label for="infusion" class="form-label">Infusions<small style="color: red;">*</small></label>
					<div id="infusion_cube">
						<div class="d-flex align-items-center" style="margin-bottom: 10px;">
							<select class="form-select bg-white selecttwo" aria-label="Default select example" id="infusion" name="infusion">
								<option value="" selected disabled>Choose Infusion</option>
								@foreach ($infusions as $infusion)
									<option value="{{ $infusion->price }}" data-desc="{{ $infusion->infusion }}">{{ $infusion->infusion }}</option>
								@endforeach
							</select>
						</div>
					</div>
				</div>
				<div class="mb-4 mt-3 d-none infusion_custom_price">
					<label for="infusion_custom_price" class="form-label">Infusion Price</label>
					<input
						type="text"
						class="form-control bg-white"
						id="infusion_custom_price"
						name="infusion_custom_price"
						oninput="formatCurrency(this)"/>
				</div>
				<div class="mb-4 mt-3">
					<label for="pax" class="form-label">Pax<small style="color: red;">*</small></label>
					<input
						type="number"
						class="form-control bg-white"
						id="pax"
						name="pax"
						aria-describedby=""
						placeholder="eg. 1"
						required/>
				</div>
				<div class="mb-4 mt-3 d-none custom_additional_cube">
					<label for="custom_price" class="form-label">Price<small style="color: red;">*</small></label>
					<input
						type="text"
						class="form-control bg-white"
						id="custom_price"
						name="custom_price"
						aria-describedby=""
						placeholder="eg. 10.000"
						oninput="formatCurrency(this)"
						/>
				</div>
				<div class="mb-4 mt-3 d-none custom_additional_cube">
					<label for="custom_additional" class="form-label">Additional<small style="color: red;">*</small></label>
					<textarea
						name="custom_additional"
						class="form-control bg-white"
						rows="3"
						placeholder="Paracetamol etc..."
						id="custom_additional"></textarea>
				</div>
				<div class="mb-4 d-none icdx_code">
					<label for="icdx" class="form-label">ICD10 Code<small style="color: red;">*</small></label>
					<div id="icdx_cube">
						<div class="icdx-row d-flex align-items-center" style="margin-bottom: 10px;">
							<select class="form-select bg-white selecttwo-icdx" aria-label="Default select example" id="icdx" name="icdx_1">
								<option value="" data-desc="" data-code=""></option>
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
				<button type="submit" class="btn btn-primary w-100 mb-3">
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

	let isDraftButtonClicked;
	$('#submit-new-invoice').on('click', function(event) {
		isDraftButtonClicked = "NEW";
	});

	$('#submit-draft-invoice').on('click', function(event) {
		isDraftButtonClicked = "DRAFT";
	});

	const submitNewInvoice = (form) => {
		const submitButton = $('#submit-new-invoice');
		const submitButtonDraft = $('#submit-draft-invoice');

    const loadingSpinner = $('#loading-spinner-invoice');
    const loadingSpinnerDraft = $('#loading-spinner-invoice-draft');

		const disabledClass = 'btn-disabled';

		if (isDraftButtonClicked === "DRAFT") {
			submitButtonDraft.prop('disabled', true);
			submitButton.prop('disabled', true);
			submitButtonDraft.addClass(disabledClass);
			loadingSpinnerDraft.removeClass('d-none');
    } else {
			submitButton.prop('disabled', true);
			submitButtonDraft.prop('disabled', true);
			submitButton.addClass(disabledClass);
			loadingSpinner.removeClass('d-none');
    }

		let cptDataxLocalStorage = JSON.parse(localStorage.getItem('dianosis')) || [];

		setTimeout(() => {
			$.ajax({
				type: 'POST',
				url: '{{ route("post_new_invoice") }}',
				data: {
					form: $(form).serialize(),
					form2: JSON.stringify(cptDataxLocalStorage),
					formType: "DRAFT INVOICE",
					buttonType: isDraftButtonClicked,
					_token: $('meta[name="csrf-token"]').attr('content')
				},
				success: function(response) {
					if (response.status === 'success' && parseInt(response.isDraft) === 2) {
       				window.location.href = "{{ route('success') }}";
					}

					if (response.status === 'success' && parseInt(response.isDraft) === 1) {
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
					if (isDraftButtonClicked) {
						submitButtonDraft.prop('disabled', false);
						submitButton.prop('disabled', false);
						submitButtonDraft.removeClass(disabledClass);
						loadingSpinnerDraft.addClass('d-none');
					} else {
						submitButton.prop('disabled', false);
						submitButtonDraft.prop('disabled', false);
						submitButton.removeClass(disabledClass);
						loadingSpinner.addClass('d-none');
					}
				}
			});
		}, 1000);
	}

	var getNewIcdxRow = (icdxCounter) => {
		return `
			<div class="icdx-row d-flex align-items-center" style="margin-bottom: 10px;">
				<select class="form-select bg-white selecttwo-icdx" aria-label="Default select example" name="icdx_${icdxCounter}">
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

		infusionValue = '500000';

		if (cpt === 3 || cpt === 4 || cpt === 5) {
			infusionValue = $('#custom_price').val();
			customAdditional = $('#custom_additional').val() || '';
		} else if (cpt === 2) {
			let infusionElement = $('#infusion');
			let selectedOptionInf = infusionElement.find('option:selected');

			infusionValue = $('#infusion_custom_price').val();
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
				let selectedOptionData = selectElement.select2('data')[0];

				if (selectedOptionData && selectedOptionData.id) {
					let icdxId = selectedOptionData?.id || '';
					let descIcdx = selectedOptionData?.desc || '';
					let icdxCode = selectedOptionData?.code || '';

					icdxData.push({
						icdx_id: icdxId,
						icdx_desc: descIcdx,
						icdx_code: icdxCode
					});
				}
			});

			let dataDiagnosis = {
				cpt_id: cptId,
				cpt_code: diagnosisCode,
				cpt_pax: pax,
				cpt_desc: nameDiagnosis || '',
				cpt_price: infusionValue,
				cpt_infusion: infusionName,
				cpt_additional: customAdditional,
				cpt_icd: icdxData.length > 0 ? icdxData : []
			};

			let localStorageData = JSON.parse(localStorage.getItem('dianosis')) || [];

			let existingIndex = localStorageData.findIndex(item => item.cpt_code === diagnosisCode);
			if (existingIndex !== -1) {
				localStorageData[existingIndex] = dataDiagnosis;
			} else {
				localStorageData.push(dataDiagnosis);
			}

			localStorage.setItem('dianosis', JSON.stringify(localStorageData));

			listItemsSelected({ cptData: localStorageData });
    }
	};

	const listItemsSelected = (data) => {
    $('.items-diagnosis').empty();

    data?.cptData?.forEach((cpt, cptIndex) => {
			let infusionName = cpt?.cpt_infusion ? `- ${cpt?.cpt_infusion}` : '';
			let additionalReceipt = cpt?.cpt_additional ? `- ${cpt?.cpt_additional}` : '';
			let cptHtml = `
				<span class="fs-12 fw-semibold"></span>
				<div class="d-flex align-items-center justify-content-between mb-2">
						<div class="detail" style="width: 350px;" onclick="openCanvas(this)" data-cpt-id="${cpt.cpt_id}">
							<h5>(${cpt.cpt_code}) ${cpt.cpt_desc}</h5>
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
						<div class="d-flex align-items-center justify-content-between mb-1" style="margin-left: 1rem;">
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

		$('.btn-remove').on('click', function (e) {
			e.preventDefault();

			const cptIndex = $(this).data('cpt-index');
			const icdIndex = $(this).data('icd-index');

			let localStorageData = JSON.parse(localStorage.getItem('dianosis')) || [];

			if (cptIndex === 0) {
				return;
			}

			if (cptIndex !== undefined) {
        if (icdIndex !== undefined) {
					localStorageData[cptIndex].cpt_icd.splice(icdIndex, 1);
					localStorage.setItem('dianosis', JSON.stringify(localStorageData));

					$(this).closest('.icd-item').remove();
        } else {
					localStorageData.splice(cptIndex, 1);
					localStorage.setItem('dianosis', JSON.stringify(localStorageData));

					$(this).closest('.cpt-item').remove();
        }
    	}

			listItemsSelected({ cptData: localStorageData });
    });
	};

	function formatCurrency(input) {
    let value = input.value.replace(/[^0-9]/g, '');
    value = new Intl.NumberFormat('id-ID').format(value);
    input.value = value;
	}

	const openCanvas = (element) => {
		const offcanvasElement = $('#offcanvasDiagnosis');
		const bsOffcanvas = new bootstrap.Offcanvas(offcanvasElement[0]);
		const cptId = element.getAttribute('data-cpt-id');
		resetFormPopup(cptId);
		bsOffcanvas.show();
	}

	$('#formpopup').on('submit', function(e) {
		e.preventDefault();

		$('#offcanvasDiagnosis').offcanvas('hide');
		saveFormData();

		$(".selecttwo-icdx").html('');
		resetFormPopup();
	});

	$('#add-new-icdx').click(function(e) {
		e.preventDefault();

		const newIcdxRow = getNewIcdxRow(icdxCounter);
		$('#icdx_cube').append(newIcdxRow);

		$("#icdx_cube .selecttwo-icdx").last().select2({
			dropdownParent: $("#offcanvasDiagnosis"),
			width: '78%',
			ajax: {
				url: "{{ route('list_icdx') }}",
				dataType: 'json',
				delay: 250,
				data: function (params) {
					return {
						search: params.term,
						page: params.page || 1
					};
				},
				processResults: function (data, params) {
					params.page = params.page || 1;
					return {
						results: data.items.map(function (item) {
							return { id: item.id, desc: item.name, code: item.code };
						}),
						pagination: {
							more: (params.page * data.pageSize) < data.total
						}
					};
				},
				cache: true
			},
			placeholder: 'Select an ICDX',
			templateResult: formatICDX,
			templateSelection: formatICDXSelection
		});

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
	});

	var resetFormPopup = (cptId = '') => {
		let firstRowFound = false;
		$('#formpopup')[0].reset();

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

		$('#cpt').val(cptId).trigger('change');
	}

	$('[href="#offcanvasDiagnosis"]').on('click', function() {
		resetFormPopup(1);
	});

	function formatICDX(icdx) {
		if (icdx.loading) {
			return icdx.desc;
		}

		var $container = $(
			`<div>
				<span data-code="${icdx?.code || ``}" data-desc="${icdx?.desc || ``}">
					${icdx?.code || ``} - ${icdx?.desc || ``}
				</span>
			</div>`
		);

		return $container;
	}

	function formatICDXSelection(icdx) {
		var $container = $(
			`<div>
				<span data-code="${icdx?.code || ``}" data-desc="${icdx?.desc || ``}" style="width: 90%;">
					${icdx?.code || ``} ${icdx?.desc || ``}
				</span>
			</div>`
		);

		return $container;
	}

	$(document).ready(function () {
		localStorage.removeItem('dianosis');

		let localStorageData = JSON.parse(localStorage.getItem('dianosis')) || [];

		if (localStorageData.length === 0) {
    	localStorage.setItem('dianosis', JSON.stringify(cptDatax));
			localStorageData = cptDatax;
		}

		listItemsSelected({ cptData: localStorageData });

		$('.selecttwo').select2({
			dropdownParent: $("#offcanvasDiagnosis"),
			width: '100%'
		});

		$(".selecttwo-icdx").select2({
			dropdownParent: $("#offcanvasDiagnosis"),
			width: '78%',
			ajax: {
				url: "{{ route('list_icdx') }}",
				dataType: 'json',
				delay: 250,
				data: function (params) {
					return {
						search: params.term,
						page: params.page || 1
					};
				},
				processResults: function (data, params) {
					params.page = params.page || 1;
					return {
						results: data.items.map(function (item) {
							return { id: item.id, desc: item.name, code: item.code };
						}),
						pagination: {
							more: (params.page * data.pageSize) < data.total
						}
					};
				},
				cache: true
			},
			placeholder: 'Select an ICDX',
			templateResult: formatICDX,
			templateSelection: formatICDXSelection
		})

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
				"payment_method[]": { required: true },
			},
			messages: {
				username: { required: "Username required"},
				address: { required: "Address required" },
				phone_number: { required: "Phone number required" },
				"payment_method[]": { required: "Payment method required" },
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
				$('.infusion_custom_price').removeClass('d-none');
				$('.icdx_code').removeClass('d-none');
				$('.custom_additional_cube').addClass('d-none');
			} else if (parseInt(selectedValue) === defaultOption) {
				$('.icdx_code').addClass('d-none');
				$('#cube_infusion').addClass('d-none');
				$('.custom_additional_cube').addClass('d-none');
				$('.infusion_custom_price').addClass('d-none');
			} else if (parseInt(selectedValue) === additional1 || parseInt(selectedValue) === additional2 || parseInt(selectedValue) === additional3) {
				$('.custom_additional_cube').removeClass('d-none');
				$('#cube_infusion').addClass('d-none');
				$('.icdx_code').removeClass('d-none');
				$('.infusion_custom_price').addClass('d-none');
			} else {
				$('#cube_infusion').addClass('d-none');
				$('.icdx_code').removeClass('d-none');
				$('.custom_additional_cube').addClass('d-none');
				$('.infusion_custom_price').addClass('d-none');
			}
    });

		$('#infusion').on('change', function() {
			let input = this;
			let value = input.value.replace(/[^0-9]/g, '');
			value = new Intl.NumberFormat('id-ID').format(value);
			$('#infusion_custom_price').val(value);
		});

		$('#payment_method').select2( {
			width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
			placeholder: $( this ).data( 'placeholder' ),
			closeOnSelect: true,
		});

		$('#phone_number').on('input', function() {
			let phoneNumber = $(this).val();
			if (!phoneNumber.startsWith('62')) {
				phoneNumber = '62' + phoneNumber.replace(/^0+/, '');
				$(this).val(phoneNumber);
			}
		});

		$('#service').select2();

		var selectedMethods = {{ $paymentMethodSelected ?? [] }};
		$('#payment_method').val(selectedMethods).trigger('change');
	});
</script>
