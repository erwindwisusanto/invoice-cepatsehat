<x-master-layout>
	<div class="py-4">
		<div class="container">
			<div class="text-center mb-4">
				<img
					src="assets/img/logo-invoice.svg"
					class="logo-invoice"
					alt="" />
			</div>
			<form action="#">
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
			</form>

			<div class="list-invoice">
				<a
					class="items-invoice"
					data-bs-toggle="offcanvas"
					href="#offcanvasDetail"
					role="button"
					aria-controls="offcanvasDetail">
					<div class="d-flex justify-content-between">
						<div class="detail">
							<span>01 Juli 2024</span>
							<h5>Andriatim Marie</h5>
						</div>
						<div class="status">
							<span class="badge badge-success">Done</span>
						</div>
					</div>
				</a>
				<a
					class="items-invoice"
					data-bs-toggle="offcanvas"
					href="#offcanvasDetail"
					role="button"
					aria-controls="offcanvasDetail">
					<div class="d-flex justify-content-between">
						<div class="detail">
							<span>01 Juli 2024</span>
							<h5>Anissa Amalia Nurrahmah</h5>
						</div>
						<div class="status">
							<span class="badge badge-success">Done</span>
						</div>
					</div>
				</a>
				<a
					class="items-invoice"
					data-bs-toggle="offcanvas"
					href="#offcanvasDetail"
					role="button"
					aria-controls="offcanvasDetail">
					<div class="d-flex justify-content-between">
						<div class="detail">
							<span>01 Juli 2024</span>
							<h5>Reza Akbar</h5>
						</div>
						<div class="status">
							<span class="badge badge-success">Done</span>
						</div>
					</div>
				</a>
				<a
					class="items-invoice"
					data-bs-toggle="offcanvas"
					href="#offcanvasDetail"
					role="button"
					aria-controls="offcanvasDetail">
					<div class="d-flex justify-content-between">
						<div class="detail">
							<span>01 Juli 2024</span>
							<h5>Ellie Whiston</h5>
						</div>
						<div class="status">
							<span class="badge badge-draft">Draft</span>
						</div>
					</div>
				</a>
				<a
					class="items-invoice"
					data-bs-toggle="offcanvas"
					href="#offcanvasDetail"
					role="button"
					aria-controls="offcanvasDetail">
					<div class="d-flex justify-content-between">
						<div class="detail">
							<span>01 Juli 2024</span>
							<h5>Aprylia Widya Ningsih</h5>
						</div>
						<div class="status">
							<span class="badge badge-progress">On Progress</span>
						</div>
					</div>
				</a>
				<a
					class="items-invoice"
					data-bs-toggle="offcanvas"
					href="#offcanvasDetail"
					role="button"
					aria-controls="offcanvasDetail">
					<div class="d-flex justify-content-between">
						<div class="detail">
							<span>01 Juli 2024</span>
							<h5>Andriatim Marie</h5>
						</div>
						<div class="status">
							<span class="badge badge-success">Done</span>
						</div>
					</div>
				</a>
				<a
					class="items-invoice"
					data-bs-toggle="offcanvas"
					href="#offcanvasDetail"
					role="button"
					aria-controls="offcanvasDetail">
					<div class="d-flex justify-content-between">
						<div class="detail">
							<span>01 Juli 2024</span>
							<h5>Anissa Amalia Nurrahmah</h5>
						</div>
						<div class="status">
							<span class="badge badge-success">Done</span>
						</div>
					</div>
				</a>
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

			<div class="float-button-add">
				<a href="{{ route('view_new_invoice') }}" class="btn btn-primary">
					Add New
					<i class="fi fi-rr-plus ms-2"></i>
				</a>
			</div>
		</div>
	</div>
</x-master-layout>
