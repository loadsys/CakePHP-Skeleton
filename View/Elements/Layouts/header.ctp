<!-- Static navbar -->
<div class="navbar navbar-default" role="navigation">
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="#"><?php echo Configure::read('Defaults.long_name'); ?></a>
		</div>
		<div class="navbar-collapse collapse">
			<ul class="nav navbar-nav">
				<li class="active">
					<?php echo $this->Html->link(
						__('Home'),
						Router::url('/')
					); ?>
				</li>
				<li>
					<?php echo $this->Html->link(
						__('About'),
						array(
							'plugin' => false,
							'controller' => 'pages',
							'action' => 'display',
							'about',
							'admin' => false,
						)
					); ?>
				</li>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo __('Products'); ?> <span class="caret"></span></a>
					<ul class="dropdown-menu" role="menu">
						<li><a href="#">@TODO</a></li>
						<li class="divider"></li>
						<li class="dropdown-header">Nav header</li>
						<li><a href="#">Separated link</a></li>
					</ul>
				</li>
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<?php if ($u && in_array($u['role'], array('user'))) { ?>
					<li>
						<?php echo $this->Html->link(__('Portal'), array(
							'controller' => 'users',
							'action' => 'portal',
							'user' => true,
						));?>
					</li>
					<li>
						<?php echo $this->Html->link(__('Logout'), array(
							'controller' => 'users',
							'action' => 'logout',
							'user' => false,
						));?>
					</li>
				<?php } else { ?>
					<li>
						<?php echo $this->Html->link(__('Login'), array(
							'controller' => 'users',
							'action' => 'portal',
							'user' => true,
						));?>
					</li>
				<?php } ?>
			</ul>
		</div><!--/.nav-collapse -->
	</div><!--/.container-fluid -->
</div>

<?php /*
	<?php echo $this->element('Layouts/social_networks'); ?>
	<?php echo $this->element('Layouts/search'); ?>
*/ ?>