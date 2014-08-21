<div class="nav-panel">
	<div class="pagination">
		<?php echo $this->StockPaginator->prev(
			'« ' . __('Prev'),
			array(
				'class' => 'btn-prev',
				'tag' => false,
			),
			'« ' . __('Prev'),
			array(
				'class' => 'btn-prev disabled',
				'tag' => false,
				'disabledTag' => 'a',
			)
		); ?>
		<?php echo $this->StockPaginator->next(
			__('Next') . ' »',
			array(
				'class' => 'btn-next',
				'tag' => false,
			),
			__('Next') . ' »',
			array(
				'class' => 'btn-next disabled',
				'tag' => false,
				'disabledTag' => 'a',
			)
		); ?>
		<div class="paging-area">
			<ul>
				<?php echo $this->Paginator->numbers(array(
					'modulus' => '7',
					'tag' => 'li',
					'separator' => null,
				)); ?>
			</ul>
		</div>
	</div>
</div>
