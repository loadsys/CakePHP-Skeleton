<?php
/**
 * Default navigation element, used for all users.
 */
use Cake\Core\Configure;
use Cake\Utility\Inflector;

// Set navigation defaults.
$loginLogoutLink = $this->Html->link(__('Login'), [
	'controller' => 'Users',
	'action' => 'login',
]);

// Override defaults based on User presence.
if (!empty($u)) {
	$loginLogoutLink = $this->Html->link(__('Logout'), [
		'controller' => 'Users',
		'action' => 'logout',
	]);
}

?>

<div class="contain-to-grid">
	<nav class="top-bar" data-topbar role="navigation">
		<ul class="title-area">
			<li class="name">
				<h1>
					<?= $this->Html->link(
						Configure::read('Defaults.long_name'),
						'/'
					); ?>
				</h1>
			</li>
			<li class="toggle-topbar menu-icon">
				<a href="#"><span>Menu</span></a>
			</li>
		</ul>

		<section class="top-bar-section">
			<!-- Right Nav Section (Role-based) -->
			<ul class="right">
				<li><?= $loginLogoutLink ?></li>
			</ul>

			<!-- Left Nav Section (Public / all Users) -->
			<ul class="left">

				<li class="has-dropdown">
					<?= $this->Html->link(__('About {0}', \Cake\Core\Configure::read('Defaults.short_name')), [
						'controller' => 'Pages',
						'action' => 'display',
						'about',
					]) ?>

					<ul class="dropdown">
						<li>
							<?php echo $this->Html->link(__('Frequently Asked Questions'), [
								'controller' => 'Pages',
								'action' => 'display',
								'faq',
							]) ?>
						</li>
					</ul>
				</li>

			</ul>
		</section>
	</nav>
</div>
