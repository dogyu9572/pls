@extends('layouts.app_eng')

@section('content')
<div id="mainContent" class="container g{{ $gNum }} s{{ $sNum }}">
	
	<div class="sub_visual ethical_top">
		<div class="inner">
			<div class="pagename">{{ $sName }}</div>
			<div class="tt">PLS Co., Ltd. practices <strong>ethical management <br class="mo_vw"/>that adheres to the principles of integrity and fairness</strong> in all aspects of its business operations.</div>
			<p>By establishing an Ethics Charter, we have set clear standards for ethical decision-making. <br/>All executives and employees are committed to conducting business in a transparent, fair, and reasonable manner, continuously striving to uphold a sound and responsible corporate culture.</p>
		</div>
	</div>
	
	<div class="ethical01">
		<div class="inner">
			<div class="stit mb"><p>PLS Code of Ethics</p><strong>PLS Ethics Charter</strong></div>
			<dl>
				<dt><i></i>Ethical Management<em class="tl"></em><em class="tr"></em><em class="bl"></em><em class="br"></em></dt>
				<dd class="i1"><div class="tt">1. Fair Management</div><p>We comply with all relevant laws and respect social values, practicing honesty and fairness in every aspect of our business operations.</p></dd>
				<dd class="i2 tar"><div class="tt">2. Customer Satisfaction</div><p>We enhance customer rights and trust by delivering the highest quality services and maintaining a sincere commitment to customer satisfaction.</p></dd>
				<dd class="i3"><div class="tt">3. Harmony and Cooperation</div><p>We establish transparent business practices and pursue mutual trust and cooperation with our partners, striving for long-term coexistence and shared growth.</p></dd>
				<dd class="i4 tar"><div class="tt">4. Contributing to Society</div><p>Through sound and responsible business activities, we actively contribute to creating social value and building a better community.</p></dd>
			</dl>
		</div>
	</div>
	
	<div class="ethical02 gbox sub_padding">
		<div class="inner">
			<div class="stit mb"><p>Pledge of Practice</p><strong>Ethical Management Commitment</strong></div>
			<div class="wbox pd68">
				<div class="tit">Oath</div>
				<p class="tb">We, the employees of PLS, hereby pledge to uphold the following principles in order to lead the creation of a clean and transparent organization.</p>
				<ol class="num_list">
					<li><strong>1</strong>We will endeavor to realize PLS’s ethical management objectives by faithfully adhering to the company’s code of ethics.</li>
					<li><strong>2</strong>We will neither engage in unfair practices nor in any form of corruption or misconduct, and will promptly report to the audit officer upon recognizing any such wrongdoing by other employees.</li>
					<li><strong>3</strong>We will fully cooperate with all company requests, including the submission of relevant materials, during any regular or ad-hoc investigations concerning violations of ethical standards, unfair practices, or acts of corruption.</li>
					<li><strong>4</strong>In the performance of our duties, we will act with integrity and strive to ensure that every decision and action taken as members of PLS remains beyond reproach.</li>
					<li><strong>5</strong>We will faithfully comply with and actively uphold all the above principles, setting an example for all members of PLS.</li>
				</ol>
				<div class="date">2023.12.08<strong>All Employees</strong><i></i></div>
			</div>
		</div>
	</div>
	
	<div class="ethical03">
		<div class="inner">
			<div class="btns flex_center">
				<a href="{{ route('eng.terms.ethic') }}" class="btn_line flex_center">PLS Code of Ethics</a>
				<a href="{{ route('eng.terms.internal-reporting') }}" class="btn_line flex_center">Internal Reporting System Regulations</a>
				<!-- <a href="mailto:{{ $reportingEmail }}" class="btn_mail flex_center">Report to PLS</a> -->
			</div>
		</div>
	</div>

</div> <!-- //container -->
@endsection
