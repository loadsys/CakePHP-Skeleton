<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<?php echo $this->Html->link('Dashboard',
				array(
					'controller' => 'pages',
					'action' => 'display', 'dashboard',
					'admin' => true,
				),
				array('class' => 'navbar-brand')
			); ?>
		</div>

		<div class="collapse navbar-collapse navbar-ex1-collapse">
			<ul class="nav navbar-nav">
				<li>
					<?php echo $this->Html->link(
						'<span class="glyphicon glyphicon-arrow-left"></span> Site',
						Router::url('/'),
						array('escape' => false)
					); ?>
				</li>

				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">Users <b class="caret"></b></a>
					<ul class="dropdown-menu">
						<li>
							<?php echo $this->Html->link('Users', array(
								'controller' => 'users',
								'action' => 'index',
								'admin' => true
							)); ?>
						</li>
					</ul>
				</li>
			</ul>

			<ul class="nav navbar-nav navbar-right">
				<li>
					<?php echo $this->Html->link('Help', array(
						'controller' => 'pages',
						'action' => 'display',
						'help',
						'index',
						'admin' => true,
					)); ?>
				</li>
				<li>
					<?php echo $this->Html->link('Logout', array(
						'controller' => 'users',
						'action' => 'logout'
					)); ?>
				</li>
			</ul>
		</div>
	</div>
</nav>
