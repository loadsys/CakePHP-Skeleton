<ul class="social-networks">
	<li>
		<?php
			echo $this->Html->link(
				$this->Html->image('social-icons/ico-youtube.png', array(
					'width' => 48,
					'height' => 20,
					'alt' => 'YouTube',
				)),
				Configure::read('SocialNetworks.YouTube.link'),
				array(
					'target' => '_blank',
					'escape' => false,
				)
			);
		?>
	</li>
	<li>
		<?php
			echo $this->Html->link(
				$this->Html->image('social-icons/ico-facebook.png', array(
					'width' => 19,
					'height' => 20,
					'alt' => 'Facebook',
				)),
				Configure::read('SocialNetworks.Facebook.link'),
				array(
					'target' => '_blank',
					'escape' => false,
				)
			);
		?>
	</li>
	<li>
		<?php
			echo $this->Html->link(
				$this->Html->image('social-icons/ico-twitter.png', array(
					'width' => 24,
					'height' => 20,
					'alt' => 'Twitter',
				)),
				Configure::read('SocialNetworks.Twitter.link'),
				array(
					'target' => '_blank',
					'escape' => false,
				)
			);
		?>
	</li>
</ul>
