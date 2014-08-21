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
			<a class="navbar-brand" href="#"><?php echo __('Education Unlimited'); ?></a>
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
						UrlRef::get('ContentPage', 0)  //@TODO
					); ?>
				</li>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo __('Camps'); ?> <span class="caret"></span></a>
					<ul class="dropdown-menu" role="menu">
						<li><a href="#">@TODO</a></li>
						<li class="divider"></li>
						<li class="dropdown-header">Nav header</li>
						<li><a href="#">Separated link</a></li>
					</ul>
				</li>
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<?php if ($u && in_array($u['role'], array('parent', 'student'))) { ?>
					<li>
						<?php echo $this->Html->link(__('My Portal'), array(
							'controller' => 'parents',
							'action' => 'portal',
							'parent' => true,
						));?>
					</li>
					<li>
						<?php echo $this->Html->link(__('Logout'), array(
							'controller' => 'users',
							'action' => 'logout',
							'parent' => false,
						));?>
					</li>
				<?php } else { ?>
					<li>
						<?php echo $this->Html->link(__('Login'), array(
							'controller' => 'parents',
							'action' => 'portal',
							'parent' => true,
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