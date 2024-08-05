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
									<th>Date</th>
									<th>Name</th>
									<th>Status</th>
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
        <div class="d-flex justify-content-between mb-3">
          <span class="fs-12 fw-semibold text-muted">Pax</span>
          <p class="fs-14 mb-0">1x</p>
        </div>
        <div class="d-flex justify-content-between">
          <span class="fs-12 fw-semibold text-muted">Total</span>
          <p class="fs-16 mb-0 fw-semibold">Rp200.000</p>
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
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');

		return `${year}-${month}-${day}`;
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
			default:
				name = 'Unknown';
				classNamebadge = 'badge badge-info';
				break;
		}
		return `<span class="${classNamebadge} fs-14" style="font-weight: normal;">${name}</span>`;
	}

	$(document).ready(function () {
		const invoiceTable = $('#invoices').DataTable({
			processing: false,
			serverSide: true,
			responsive: true,
			dom: 'rtp',
			ajax: '{{ route('invoices') }}',
			columns: [
				{
					data: 'created_at',
					name: 'created_at'
				},
				{
					data: 'username',
					name: 'username'
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
						return formatingDate(data);
					}
				},
				{
					targets: 1,
					className: `align-middle`,
					render: function(data, type, full, row) {
						const html = `
							<div><span class='fs-14' style='color: #494949;'>${full['username']}</span></div>
						`;
						return html;
					}
				},
				{
					targets: 2,
					className: `align-middle`,
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
			console.log(rowData);

			let invoiceNumber = rowData?.invoice_numner;
			let date = rowData?.created_at;
			let name = rowData?.username;
			let address = rowData?.address;
			let phone = rowData?.phone;
			let status = rowData?.status;

			$('#offcanvasDetailLabel').html(`
				No Invoice: ${invoiceNumber} ${statusInvoice(parseInt(status))}
				<p class="fs-12 text-muted mb-0">${formatingDate(date)}</p>
			`);

			$('#offcanvasDetail .list-data-invoice .items-data:nth-child(1) p').text(name);
			$('#offcanvasDetail .list-data-invoice .items-data:nth-child(2) p').text(address);
			$('#offcanvasDetail .list-data-invoice .items-data:nth-child(3) p').text(phone);

			offcanvas.show();
		});

	});
</script>
