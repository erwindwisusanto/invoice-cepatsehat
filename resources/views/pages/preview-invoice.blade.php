<x-master-layout>
	<div class="py-3">
		<div class="container">
			<div class="row">
				<div class="col-12 text-center">
						<img src="{{ asset('assets/img/banner-preview.svg') }}" class="img-fluid w-100" alt="Sample Image">
				</div>
			</div>
			<div class="row mt-3">
				<div class="col-12 text-left">
						<h6 class="fw-semibold" style="color: #007A7D;">NEW INVOICE</h6>
				</div>
				<div class="col-12 text-right invoice-details">
						<span class="d-block fs-14 text-muted">Invoice Number: 0001/CSI/VII/2024</span>
						<span class="d-block fs-14 text-muted">Date: July 8, 2024</span>
				</div>
			</div>
			<div class="row mt-3">
				<div class="col-9">
					<span class="d-block fs-14 text-muted">Invoice to:</span>
					<span class="d-block fs-14 fw-semibold" style="color: #66666;">{{ $username }}</span>
					<span class="d-block fs-14 text-muted">{{ $address }}</span>
				</div>
			</div>
			<div class="row gx-0 mt-4">
				<table class="table table-bordered border-csi">
					<thead class="thead-light">
            <tr>
                <th class="align-middle text-center" style="background-color: #F2FEFF; color: #666666;">CPT Code</th>
                <th class="align-middle text-center" style="background-color: #F2FEFF; color: #666666; width: 40%">Item Description</th>
                <th class="align-middle text-center" style="background-color: #F2FEFF; color: #666666; width: 10%">ICD10 Code</th>
                <th class="align-middle text-center" style="background-color: #F2FEFF; color: #666666;">Pax</th>
                <th class="align-middle text-center" style="background-color: #F2FEFF; color: #666666;">Total</th>
            </tr>
       	 	</thead>
					<tbody>
						@php $totalPrice = 0; @endphp
						@foreach ($diagnosis as $item)
							@php
								$itemTotalPrice = $item->cpt_price * $item->cpt_pax;
								$totalPrice += $itemTotalPrice;
							@endphp
							<tr>
								<td class="align-middle text-center">{{ $item->cpt_code }}</td>
								<td>
									{{ $item->cpt_desc }}
									@if (!empty($item->cpt_additional))
											<br>
											<b>Additional:</b> {{ $item->cpt_additional }}
									@endif
									@if (!empty($item->cpt_infusion))
										<br>

										<span><b>Infusion: </b> {{ $item->cpt_infusion }}</span>
									@endif
									@if (!empty($item->cpt_icd))
										<br>
										<span><b>Symptoms: </b></span>
										<ul>
											@foreach ($item->cpt_icd as $icd)
												<li>{{ $icd->icdx_desc }}</li>
											@endforeach
										</ul>
									@endif
								</td>
								<td class="align-middle text-center">
									@if (!empty($item->cpt_icd))
										@foreach ($item->cpt_icd as $icd)
											{{ $icd->icdx_code }}<br>
										@endforeach
									@endif
								</td>
								<td class="align-middle text-center">{{ $item->cpt_pax }}</td>
								<td class="align-middle">Rp {{ number_format($item->cpt_price * $item->cpt_pax, 0, ',', '.') }}</td>
							</tr>
						@endforeach
						<tr>
							<td></td>
							<td><span class="fw-semibold">Complimentary disc</span></td>
							<td></td>
							<td></td>
							<td><span>Rp {{ number_format($complimentaryDiscount) }}</span></td>
						</tr>
						<tr>
							<td></td>
							<td><span class="fw-semibold">Med. team transport cost</span></td>
							<td></td>
							<td></td>
							<td><span>Rp {{ number_format($medicalTeamTransportCost) }}</span></td>
						</tr>
						<tr>
							<td></td>
							<td><span class="fw-semibold">Total</span></td>
							<td></td>
							<td></td>
							<td><span class="fw-semibold">Rp {{ number_format(($totalPrice - $complimentaryDiscount) + $medicalTeamTransportCost) }}</span></td>
						</tr>
					</tbody>
				</table>
				<div class="row gx-0">
					@php
						$price = ($totalPrice - $complimentaryDiscount) + $medicalTeamTransportCost;
					@endphp
					<span>Said: <span class="fw-semibold fst-italic">{{ numberToWords($price)}} Rupiahs</span></span>
				</div>
				<div class="row mt-5 gx-0">
					<div class="col-6">
						<div class="row">
							<span class="fw-semibold">Clinic Cepat Sehat</span>
						</div>
						<div class="row mt-5">
							<span class="fw-semibold">dr. Irvan Rizki Fikri</span>
							<span class="fw-semibold">General Practitioner</span>
						</div>
					</div>
					<div class="col-6">
						<div class="row">
							<span>For payment please wired to :</span>
						</div>
						<div class="row mt-3">
							<b>Bank Negara Indonesia</b><span>
								BNI Acct No: 1370398396
								Swift code: BNINIDJA
								PT. Cepat Sehat Indonesia
								Branch: Tebet - Jakarta</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</x-master-layout>
