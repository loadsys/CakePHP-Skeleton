<ul class="social-networks">
	<?php
		$networks = Configure::read('SocialNetworks');
		if (is_array($networks)) {
			foreach ($networks as $network => $options) {
				if (!empty($options['link'])) {
					echo '<li>';
					echo $this->Html->link(
						$this->Html->image(
							(!empty($options['image']) ? $options['image'] : "social-icons/{$network}.png"),
							array(
								'width' => (!empty($options['width']) ? $options['width'] : 48),
								'height' => (!empty($options['height']) ? $options['height'] : 20),
								'alt' => $network,
							)
						),
						$options['link'],
						array(
							'target' => '_blank',
							'escape' => false,
						)
					);
					echo '</li>';
				}
			}
		}
	?>
</ul>
