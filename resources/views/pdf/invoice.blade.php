<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.svg" />
    <style>
        :root {
            font-size: 14px;
            font-family: Arial, Helvetica, sans-serif;
        }

        body {
            margin: 0px;
            padding: 0px;
        }

        .container {
            width: 100%;
            padding: 0 15px;
            margin: 0 auto;
        }

        .text-center {
            text-align: center;
        }

        .text-left {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .fw-semibold {
            font-family: Arial, Helvetica, sans-serif !important;
        }

        .img-fluid {
            max-width: 100%;
            height: auto;
        }

        .py-3 {
            padding-top: 1rem;
            padding-bottom: 1rem;
        }

        .mt-3 {
            margin-top: 1rem;
        }

        .mt-4 {
            margin-top: 1.5rem;
        }

        .mt-5 {
            margin-top: 3rem;
        }

        .fs-14 {
            font-size: 14px;
        }

        .border-csi {
            border: 1px solid #04B4B9 !important;
            border-collapse: collapse;
        }

        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
        }

        .table th,
        .table td {
            padding: 0.75rem;
            vertical-align: top;
            border-top: 1px solid #04B4B9 !important;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #04B4B9 !important;
        }

        .thead-light th {
            background-color: #F2FEFF;
            color: #666666;
        }

        .align-middle {
            vertical-align: middle !important;
        }

        .d-block {
            display: block;
        }

        .fst-italic {
            font-style: italic;
        }

        ul {
            margin: 0;
            padding-left: 20px;
        }

        .position-relative {
            position: relative;
        }

        .stamp-img {
            position: absolute;
            bottom: 200vh;
            right: 80px;
            width: 200px;
            opacity: 0.5;
            transform: rotate(0deg);
        }
    </style>
</head>

<body>
	<div style="position: relative; min-height: 100vh;">
			<div style="position: absolute; top: 0; left: 0; width: 100%; text-align: center;">
					@php
							$imagePath = public_path('assets/img/atas_pdf.png');
							$imageData = base64_encode(file_get_contents($imagePath));
					@endphp
					<img src="data:image/png;base64,{{ $imageData }}" style="max-width: 100%;">
			</div>
	</div>
    <div class="py-3" style="padding-top: 170px">
        <div class="container">
            <div class="mt-3">
                <div class="text-left">
                    <h3 class="fw-semibold" style="color: #007A7D;">INVOICE</h3>
                </div>
                <div class="text-left invoice-details">
                    <span class="d-block fs-14 ">Invoice Number: {{ $invoiceNumber }}</span>
                    <span class="d-block fs-14 ">Date: {{ $date }}</span>
                </div>
            </div>
            <div class="mt-3">
                <div>
                    <span class="d-block fs-14 ">Invoice to:</span>
                    <span class="d-block fs-14 fw-semibold" style="color: #66666;">{{ $username }}</span>
                    <span class="d-block fs-14 ">{{ $phoneNumber . ', '. $address }}</span>
                </div>
            </div>
            <div class="mt-5">
                <table class="table table-bordered border-csi">
                    <thead class="thead-light">
                        <tr>
                            <th class="align-middle text-center" style="width: 10%;">CPT Code</th>
                            <th class="align-middle text-center" style="width: 45%">Item Description</th>
                            <th class="align-middle text-center" style="width: 10%">ICD10 Code</th>
                            <th class="align-middle text-center" style="width: 8%">Pax</th>
                            <th class="align-middle text-center" style="width: 25%">Total</th>
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
                                <td class="align-middle">Rp
                                    {{ number_format($item->cpt_price * $item->cpt_pax, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="4" class="text-right"><span class="fw-semibold"><b>Complimentary disc</b></span></td>
                            <td><span>Rp {{ number_format($complimentaryDiscount, 0, ',', '.') }}</span></td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-right"><span class="fw-semibold"><b>Med. team transport cost</b></span></td>
                            <td><span>Rp {{ number_format($medicalTeamTransportCost, 0, ',', '.') }}</span></td>
                        </tr>
												<tr>
													<td colspan="4" class="text-right"><span class="fw-semibold"><b>Night service cost</b></span></td>
													<td><span>Rp {{ number_format($nightServiceCost, 0, ',', '.') }}</span></td>
											</tr>
                        <tr>
                            <td colspan="4" class="text-right"><span class="fw-semibold"><b>Total</b></span></td>
                            <td><span class="fw-semibold"><b>Rp
                                        {{ number_format($totalPrice - $complimentaryDiscount + $medicalTeamTransportCost, 0, ',', '.') }}</b></span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-right"><span class="fw-semibold"><b>Payment Method</b></span></td>
                            <td><span class="fw-semibold">
                                    @foreach ($paymentMethod as $index => $payment)
                                        <b>{{ $payment }}@if (!$loop->last)
                                                ,
                                            @endif
                                        </b>
                                    @endforeach
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="mt-4">
                    @php
                        $price = $totalPrice - $complimentaryDiscount + $medicalTeamTransportCost;
                    @endphp
                    <span>Said: <span class="fw-semibold fst-italic">{{ numberToWords($price) }} Rupiahs</span></span>
                </div>
                <div class="mt-5" style="margin-top: 7rem;">
                    <div class="position-relative" style="width: 35%; float: left;">
                        <div>
                            <span class="fw-semibold">Clinic Cepat Sehat</span>
														@php
															$imagePath = public_path('assets/img/Stempel-Dokter-Irvan-pake-ttd-Rev.png');
															$imageData = base64_encode(file_get_contents($imagePath));
													@endphp
													<img src="data:image/png;base64,{{ $imageData }}" alt="Stamp" class="stamp-img" style="z-index: 100000;">
                        </div>
                        <div class="mt-5" style="margin-top: 4.5rem;">
                            <span class="fw-semibold"><b>dr. Irvan Rizki Fikri</b></span><br>
                            <span class="fw-semibold">General Practitioner</span>
                        </div>
                    </div>
                    <div style="width: 10%"></div>
                    <div style="width: 35%; float: right;">
                        <div>
                            <span>For payment please wired to :</span>
                        </div>
                        <div class="mt-3">
                            <b>Bank Negara Indonesia</b><br><span>
                                BNI Acct No: 1370398396<br>
                                Swift code: BNINIDJA<br>
                                PT. Cepat Sehat Indonesia<br>
                                Branch: Tebet - Jakarta</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
		<div style="position: relative; min-height: 100vh;">
			<div style="position: absolute; bottom: 120px; left: 0; width: 100%; text-align: center;">
					@php
							$imagePath = public_path('assets/img/bawah_pdf.png');
							$imageData = base64_encode(file_get_contents($imagePath));
					@endphp
					<img src="data:image/png;base64,{{ $imageData }}" style="max-width: 100%;">
			</div>
	</div>
</body>

</html>
