<x-master-layout>
	<div class="pages-bg d-flex align-items-center justify-content-center flex-column" style="min-height: 100vh;">
			<div class="container text-center">
					<img src="{{ asset('assets/img/success.png') }}" class="img-content" alt="" />
					<h5 class="title-pages">Invoice Has been sent</h5>

					<div class="copyright" style="margin-top: 120px;">
						<img src="{{ asset('assets/img/logo-cepatsehat.png') }}" class="logo-cepatsehat" alt="cepatsehat"/>
					</div>
			</div>
	</div>
</x-master-layout>
<script>
	'use strict';
	$(document).ready(function () {
		setTimeout(function() {
			window.location.href = "/";
			history.replaceState(null, null, "/");
    }, 2000);
	});
</script>
