<div class="footer">
	<div class="container">

		<div class="navbar-brand">
			<?php echo $this->Html->link('&copy;',
				array(
					'plugin' => false,
					'controller' => 'pages',
					'action' => 'display',
					'home',
					'admin' => true,
				),
				array(
					'escape' => false,
					'style' => 'color: black; text-decoration: none; cursor: default;',
					'rel' => 'nofollow',
				)
			); ?>
			<?php echo Configure::read('Defaults.long_name'); ?>
		</div>

		<ul class="nav nav-pills">
			<li>
				<?php echo $this->Html->link(
					__('Terms of Use'),
					array(
						'plugin' => false,
						'controller' => 'pages',
						'action' => 'display',
						'terms',
						'admin' => false,
					)
				); ?>
			</li>
			<li>
				<?php echo $this->Html->link(
					__('Privacy Policy'),
					array(
						'plugin' => false,
						'controller' => 'pages',
						'action' => 'display',
						'privacy',
						'admin' => false,
					)
				); ?>
			</li>
		</ul>

	</div>
</div>
