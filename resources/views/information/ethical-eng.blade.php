@extends('layouts.app_eng')

@section('content')
<div id="mainContent" class="container g{{ $gNum }} s{{ $sNum }}">
	
	<div class="sub_visual ethical_top">
		<div class="inner">
			<div class="pagename">{{ $sName }}</div>
			<div class="tt">(주)피엘에스 practices <strong>'Ethical Management <br class="mo_vw"/>Walking the Right Path'</strong> in all management activities.</div>
			<p>We have established an ethics charter to clarify ethical judgment standards, and continuously strive to ensure that all employees can perform their duties transparently, fairly and rationally.</p>
		</div>
	</div>
	
	<div class="ethical01">
		<div class="inner">
			<div class="stit mb"><p>PLS Code of Ethics</p><strong>PLS Ethics Charter</strong></div>
			<dl>
				<dt><i></i>Ethical Management<em class="tl"></em><em class="tr"></em><em class="bl"></em><em class="br"></em></dt>
				<dd class="i1"><div class="tt">One, Fair Management</div><p>We will practice fair management with an honest and fair work attitude, complying with all laws and regulations and respecting social values.</p></dd>
				<dd class="i2 tar"><div class="tt">Two, Customer Satisfaction</div><p>We will become a company trusted by customers by promoting customer rights and interests with the best service and maintaining an attitude of customer satisfaction.</p></dd>
				<dd class="i3"><div class="tt">Three, Harmony and Cooperation</div><p>We will establish transparent transaction order and pursue long-term coexistence and joint development based on mutual trust and cooperation with partner companies.</p></dd>
				<dd class="i4 tar"><div class="tt">Four, Society Together</div><p>We will contribute to creating social value through sound corporate activities.</p></dd>
			</dl>
		</div>
	</div>
	
	<div class="ethical02 gbox sub_padding">
		<div class="inner">
			<div class="stit mb"><p>Pledge of Practice</p><strong>Ethical Management Practice Pledge</strong></div>
			<div class="wbox pd68">
				<div class="tit">Oath</div>
				<p class="tb">PLS employees pledge to comply with the following matters to take the lead in creating a clean and transparent company.</p>
				<ol class="num_list">
					<li><strong>1</strong>We will strive to realize PLS's ethical management goals by complying with ethical standards.</li>
					<li><strong>2</strong>We will not engage in any unfair transactions or misconduct, and will immediately report to the audit officer if we become aware of misconduct or corruption by other employees.</li>
					<li><strong>3</strong>We will fully cooperate with all requests for submission of related materials requested by the company when regular and occasional investigations are conducted regarding unfair transactions and misconduct/corruption that violate ethical standards.</li>
					<li><strong>4</strong>In performing our duties, we will strive to ensure that all decisions or actions made as PLS members are not ashamed of our conscience.</li>
					<li><strong>5</strong>We will comply with and actively practice all the above matters to become a model for PLS.</li>
				</ol>
				<div class="date">2023.12.08<strong>All Employees</strong><i></i></div>
			</div>
		</div>
	</div>
	
	<div class="ethical03">
		<div class="inner">
			<div class="btns flex_center">
				<a href="{{ route('eng.terms.ethic') }}" class="btn_line flex_center">PLS Code of Ethics</a>
				<a href="{{ route('eng.terms.internal-reporting') }}" class="btn_line flex_center">Internal Reporting System Operation Regulations</a>
				<a href="mailto:{{ $reportingEmail }}" class="btn_mail flex_center">Report to PLS</a>
			</div>
		</div>
	</div>

</div> <!-- //container -->
@endsection
