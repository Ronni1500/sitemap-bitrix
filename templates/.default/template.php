<div class="content">
	<ul class="site-map">
		<?php $depth = 1 ?>
		<?php foreach ($arResult['ITEMS'] as $item) { ?>
			<li class="depth-<?= $item['DEPTH'] ?>">
				<a href="<?= $item['LINK'] ?>"><?= $item['NAME'] ?></a>
			</li>
		<?php } ?>
	</ul>
</div>
