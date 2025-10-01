@extends('layouts.app')

@section('content')
<div id="mainContent" class="container g{{ $gNum }} s{{ $sNum }}">
	
	<div class="inner">
		<div class="stit mb2"><p>Desired Talent</p><strong>인재상</strong></div>
		<dl class="ideal_employee php">
			<dt class="flex_center"><img src="{{ asset('images/img_ideal_employee_center.svg') }}" alt="logo"></dt>
			<dd class="i1 tar"><div class="tt">도전</div><p>실패를 두려워하지 않고, <br class="pc_vw">새로운 과제에 과감히 도전하며 <br class="pc_vw">적극적으로 업무를 추진하는 인재</p></dd>
			<dd class="i2"><div class="tt">열정</div><p>주인의식과 책임감을 바탕으로, <br class="pc_vw">회사와 고객을 위해 <br class="pc_vw">헌신적으로 몰입하는 인재</p></dd>
			<dd class="i3 tar"><div class="tt">전문성</div><p>자신의 분야에서 최고의 역량을 갖추고 <br class="pc_vw">지속적으로 성장하는 전문가형 인재</p></dd>
			<dd class="i4"><div class="tt">협력</div><p>긍정적인 태도로 업무방향을 공유하고, 원활한 <br class="pc_vw">소통과 협업을 통해 시너지를 만드는 인재</p></dd>
		</dl>
	</div>

</div> <!-- //container -->
@endsection
