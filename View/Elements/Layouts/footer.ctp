<div class="footer">
	<div class="container">

		<div class="navbar-brand">
			<?php echo $this->Html->link('&copy;',
				array(
					'controller' => 'pages',
					'action' => 'display',
					'dashboard',
					'admin' => true,
				),
				array(
					'escape' => false,
					'style' => 'color: black; text-decoration: none; cursor: default;',
					'rel' => 'nofollow',
				)
			); ?>
			<?php echo __('Education Unlimited'); ?>
		</div>

		<ul class="nav nav-pills">
			<li>
				<?php echo $this->Html->link(
					__('Terms of Use'),
					UrlRef::get('ContentPage', 0) //@TODO
				); ?>
			</li>
			<li>
				<?php echo $this->Html->link(
					__('Privacy Policy'),
					UrlRef::get('ContentPage', 0) //@TODO
				); ?>
			</li>
		</ul>

	</div>
</div>
