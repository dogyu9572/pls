<div class="sub_tit <?php if($gNum=="00"){?>bdb<?}?>">
	<div class="inner">
		<div class="title"><?=$gName?></div>
		<p>고객과 함께 내일을 꿈꾸며, 새로운 삶의 가치를 창조한다.</p>
		<div class="aside <?php if($gNum=="00"){?>hide<?}?>">
			<dl>
				<dt><button type="button"><?=$sName?></button></dt>
				<dd>
				<?php if($gNum=="01"){?>
					<a href="/information/ceo_message.php" class="<?if($gNum=="01"&&$sNum=="01"){?>on<?}?>">CEO 인사말</a>
					<a href="/information/about_company.php" class="<?if($gNum=="01"&&$sNum=="02"){?>on<?}?>">회사소개</a>
					<a href="/information/history.php" class="<?if($gNum=="01"&&$sNum=="03"){?>on<?}?>">회사연혁</a>
					<a href="/information/quality_environmental.php" class="<?if($gNum=="01"&&$sNum=="04"){?>on<?}?>">품질/환경경영</a>
					<a href="/information/safety_health.php" class="<?if($gNum=="01"&&$sNum=="05"){?>on<?}?>">안전/보건경영</a>
					<a href="/information/ethical.php" class="<?if($gNum=="01"&&$sNum=="06"){?>on<?}?>">윤리경영</a>
				<?php }elseif($gNum=="02"){?>
					<a href="/business/imported_automobiles.php" class="<?if($gNum=="02"&&$sNum=="01"){?>on<?}?>">수입자동차 PDI사업</a>
					<a href="/business/port_logistics.php" class="<?if($gNum=="02"&&$sNum=="02"){?>on<?}?>">항만물류사업</a>
					<a href="/business/special_vehicle.php" class="<?if($gNum=="02"&&$sNum=="03"){?>on<?}?>">특장차 제조사업</a>
				<?php }elseif($gNum=="03"){?>
					<a href="/recruitment/ideal_employee.php" class="<?if($gNum=="03"&&$sNum=="01"){?>on<?}?>">인재상</a>
					<a href="/recruitment/personnel.php" class="<?if($gNum=="03"&&$sNum=="02"){?>on<?}?>">인사제도</a>
					<a href="/recruitment/welfare.php" class="<?if($gNum=="03"&&$sNum=="03"){?>on<?}?>">복지제도</a>
					<a href="/recruitment/information.php" class="<?if($gNum=="03"&&$sNum=="04"){?>on<?}?>">채용안내</a>
				<?php }elseif($gNum=="04"){?>
					<a href="/pr_center/announcements.php" class="<?if($gNum=="04"&&$sNum=="01"){?>on<?}?>">PLS 공지</a>
					<a href="/pr_center/news.php" class="<?if($gNum=="04"&&$sNum=="02"){?>on<?}?>">PLS 소식</a>
					<a href="/pr_center/location.php" class="<?if($gNum=="04"&&$sNum=="03"){?>on<?}?>">오시는 길</a>
				<?php }?>
				</dd>
			</dl>
		</div>
	</div>
</div>