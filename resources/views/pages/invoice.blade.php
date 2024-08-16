<x-master-layout>
	<div class="py-4">
		<div class="container">
			<div class="text-center mb-4">
				<img
					src="assets/img/logo-invoice.svg"
					class="logo-invoice"
					alt="" />
			</div>

			<div class="input-group form-search">
				<input
					type="text"
					class="form-control"
					placeholder="Search ..."
					aria-label="Search ..."
					aria-describedby="basic-addon2" />
				<span class="input-group-text" id="basic-addon2">
					<i class="fi fi-rr-search"></i>
				</span>
			</div>

			<div class="float-button-add" style="z-index: 1000">
				<a href="{{ route('view_new_invoice') }}" class="btn btn-primary">
					Add New
					<i class="fi fi-rr-plus ms-2"></i>
				</a>
			</div>
    </div>

		<div class="list-invoice">
			<div class="table-responsive mt-4">
				<table id="invoices" class="table nowrap" style="width:100%">
						<thead>
								<tr>
									<th>Name</th>
									<th class="text-center">Status</th>
								</tr>
						</thead>
						<tbody>
						</tbody>
				</table>
			</div>
		</div>

		<div
      class="offcanvas custom offcanvas-bottom"
      tabindex="-1"
      id="offcanvasDetail"
      aria-labelledby="offcanvasDetailLabel">
      <div class="offcanvas-header align-items-start pb-0">
        <h5 class="offcanvas-title fs-14" id="offcanvasDetailLabel">
          No Invoice: 0001/CSI/VII/2024
          <p class="fs-12 text-muted mb-0">21 Juni 2024</p>
        </h5>
        <button
          type="button"
          class="btn-close"
          data-bs-dismiss="offcanvas"
          aria-label="Close"></button>
      </div>
      <div class="offcanvas-body pb-3">
        <div class="list-data-invoice">
          <div class="items-data mb-3">
            <span class="fs-12 fw-semibold text-muted">Name</span>
            <p class="fs-14">Anissa Amalia Nurrahmah</p>
          </div>
          <div class="items-data mb-3">
            <span class="fs-12 fw-semibold text-muted">Address</span>
            <p class="fs-14">
              Jln. Batukaurung, Kel. Kendawangan Bali, Kec. Ubud - GIANYAR 80571
            </p>
          </div>
          <div class="items-data mb-3">
            <span class="fs-12 fw-semibold text-muted">Phone</span>
            <p class="fs-14">081287177984</p>
          </div>
          <div class="items-data mb-3">
            <span class="fs-12 fw-semibold text-muted">Item desc</span>
            <ul class="ps-3 fs-14">
              <li>Doctor consultation</li>
              <li>Gerd</li>
            </ul>
          </div>
        </div>
        <hr />
        <div class="d-flex justify-content-between mb-3 total-pax">
          <span class="fs-12 fw-semibold text-muted">Pax</span>
          <p class="fs-14 mb-0"></p>
        </div>
				<div class="d-flex justify-content-between mb-3 compdisc">
          <span class="fs-12 fw-semibold text-muted">Complimentary Discount</span>
          <p class="fs-14 mb-0"></p>
        </div>
				<div class="d-flex justify-content-between mb-3 cost">
          <span class="fs-12 fw-semibold text-muted">Medical Team Transport Cost</span>
          <p class="fs-14 mb-0"></p>
        </div>
        <div class="d-flex justify-content-between total-price">
          <span class="fs-12 fw-semibold text-muted">Total</span>
          <p class="fs-16 mb-0 fw-semibold"></p>
        </div>
				<div id="is_draft">
				</div>
      </div>
		</div>
	</div>
</x-master-layout>
<script>
	'use strict';

	const formatingDate = (dateTime) => {
		let date = new Date(dateTime);
    const year = date.getFullYear();
    const monthNames = ["January", "February", "March", "April", "May", "June",
                        "July", "August", "September", "October", "November", "December"];
    const month = monthNames[date.getMonth()];
    const day = String(date.getDate()).padStart(2, '0');

    return `${month} ${day}, ${year}`;
	}

	const statusInvoice = (status) => {
		var classNamebadge;
		var name;
		switch (status) {
			case 1:
				name = 'Draft';
				classNamebadge = 'badge badge-draft';
				break;
			case 2:
				name = 'On Progress';
				classNamebadge = 'badge badge-progress';
				break;
			case 3:
				name = 'Done';
				classNamebadge = 'badge badge-success';
				break;
			case 4:
				name = 'On Progress';
				classNamebadge = 'badge badge-progress';
				break;
			default:
				name = 'Unknown';
				classNamebadge = 'badge badge-info';
				break;
		}
		return `<span class="${classNamebadge} fs-14" style="font-weight: normal;">${name}</span>`;
	}

	const directToNewInvoice = (invoiceId) => {
		const newUrl = `/draft-invoice/${encodeURIComponent(invoiceId)}`;
		window.location.href = newUrl;
	}

	const formatter = new Intl.NumberFormat('id-ID', {
		style: 'currency',
		currency: 'IDR',
		minimumFractionDigits: 0,
  	maximumFractionDigits: 0
	});

	function updateTable() {
    setInterval(function() {
      $('#invoices').DataTable().ajax.reload(null, true);
    }, 3000);
	}

	$(document).ready(function () {
		updateTable();
		const invoiceTable = $('#invoices').DataTable({
			processing: false,
			serverSide: true,
			responsive: true,
			order: [[0, 'desc']],
			dom: 'rtp',
			ajax: '{{ route('invoices') }}',
			columns: [
				{
					data: 'updated_at',
					name: 'updated_at'
				},
				{
					data: 'status',
					name: 'status'
				}
			],columnDefs: [
				{
					targets: 0,
					className: `align-middle`,
					render: function(data, type, full, row) {
						const html = `
							<div class="col-12">
								<div class="row mb-1">
									<span class="fs-12 text-muted">${formatingDate(full['created_at'])}</span> <span class='fs-12 text-muted' style='color: #494949;'>${full['invoice_number']}</span>
								</div>
								<h5 class='fs-14' style='color: #494949; font-weight: 600;'>${full['username']}</h5>
							</div>
						`;
						return html;
					}
				},
				{
					targets: 1,
					className: `align-middle text-center`,
					render: function(data, type, full, row) {
						return statusInvoice(parseInt(data));
					}
				}
			],
			language: {
				paginate: {
					previous: '<',
					next: '>'
				}
      },
			initComplete: function () {
				$('.form-search input').on('keyup', function () {
					invoiceTable.search(this.value).draw();
				});
			}
		});

		$('.dataTable').on('click', 'tbody td', function() {
			let rowData = invoiceTable.row($(this).closest('tr')).data();
			let offcanvasElement = document.getElementById('offcanvasDetail');
			let offcanvas = new bootstrap.Offcanvas(offcanvasElement);

			let invoiceNumber = rowData?.invoice_number;
			let date = rowData?.created_at;
			let name = rowData?.username;
			let address = rowData?.address;
			let phone = rowData?.phone;
			let status = rowData?.status;
			let invoiceId = rowData?.id;
			let complimentaryDiscount = rowData?.complimentary_discount;
			let medicalTeamTransportCost = rowData?.medical_team_transport_cost;

			var fixJson = rowData?.diagnosis.replace(/&quot;/g, '"');

			$('#offcanvasDetailLabel').html(`
				No Invoice: <a href="/invoice/${invoiceId}?view=8FxU0" target="_blank">${invoiceNumber}</a> ${statusInvoice(parseInt(status))}
				<p class="fs-12 text-muted mb-0">${formatingDate(date)}</p>
			`);

			$('#offcanvasDetail .list-data-invoice .items-data:nth-child(1) p').text(name);
			$('#offcanvasDetail .list-data-invoice .items-data:nth-child(2) p').text(address);
			$('#offcanvasDetail .list-data-invoice .items-data:nth-child(3) p').text(phone);

			$('#offcanvasDetail .list-data-invoice .items-data:nth-child(4) ul').html('');
			var decodedJsonData = JSON.parse(fixJson);
			try {
				if (Array.isArray(decodedJsonData)) {
					decodedJsonData.map(function(item) {
						let infusionName = item.cpt_infusion ?  `<b>- ${item.cpt_infusion}</b>` : '';
						let additionalReceipt = item?.cpt_additional ? `- ${item.cpt_additional}` : '';
						let html = `<div class="row">
                <div class="col-6">
                    ${item.cpt_code} <span class="font-weight-bold">${item.cpt_desc}</span> <br>
                    <span class="font-weight-bold">${infusionName}</span>
										<span>${additionalReceipt}</span>
                </div>
								<div class="col-2 text-right">
                  ${item.cpt_pax} Pax
                </div>
                <div class="col-3 text-right">
                  ${formatter.format(item.cpt_price)}<br>
                </div>
            </div>`;
            var newItem = $('<li></li>').html(html);
            $('#offcanvasDetail .list-data-invoice .items-data:nth-child(4) ul').append(newItem);
            if (item.cpt_icd && item.cpt_icd.length > 0) {
							item.cpt_icd.map(function(icd) {
								var icdItem = $('<li></li>').html(`${icd.icdx_code}, ${icd.icdx_desc}`).css("margin-left", "20px");
								$('#offcanvasDetail .list-data-invoice .items-data:nth-child(4) ul').append(icdItem);
							});
            }
        });
				} else {
					console.error("Parsed JSON data is not an array:", decodedJsonData);
				}
			} catch (error) {
				console.error("Error parsing JSON:", error);
			}

			const totalPrice = decodedJsonData
				.map(item => parseInt(item.cpt_price) * parseInt(item.cpt_pax))
				.reduce((acc, price) => acc + price, 0);

			const totalPax = decodedJsonData
				.map(item => parseInt(item.cpt_pax))
				.reduce((acc, pax) => acc + pax, 0);


			let finalPrice = (totalPrice - complimentaryDiscount) + medicalTeamTransportCost;

			$('#offcanvasDetail .total-pax p').text(`${totalPax}x`);
			$('#offcanvasDetail .compdisc p').text(formatter.format(complimentaryDiscount));
			$('#offcanvasDetail .cost p').text(formatter.format(medicalTeamTransportCost));
			$('#offcanvasDetail .total-price p').text(formatter.format(finalPrice));

			if (status === 1) {
				$('#is_draft').html(`
					<div class="col-12 mt-3">
						<a onclick='directToNewInvoice("${invoiceId}")' type="submit" class="btn btn-primary w-100">
							Edit
						</a>
					</div>
				`);
			} else {
				$('#is_draft').empty();
			}

			offcanvas.show();
		});

	});
</script>
