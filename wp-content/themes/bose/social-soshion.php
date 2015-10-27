<?php global $option_setting; ?>
<?php if ($option_setting['facebook']) : ?>
<a title='Facebook' href="<?php echo esc_url( $option_setting['facebook'] ) ?>"><img src="<?php echo get_template_directory_uri()."/assets/images/social/facebook.png"; ?>"></a>
<?php endif; ?>
<?php if ($option_setting['google']) : ?>
<a title='Google Plus' href="<?php echo esc_url( $option_setting['google'] ) ?>"><img src="<?php echo get_template_directory_uri()."/assets/images/social/google.png"; ?>"></a>
<?php endif; ?>
<?php if ($option_setting['twitter']) : ?>
<a title='Twitter' href="<?php echo esc_url( $option_setting['twitter'] ) ?>"><img src="<?php echo get_template_directory_uri()."/assets/images/social/twitter.png"; ?>"></a>
<?php endif; ?>
<?php if ($option_setting['rss-feed']) : ?>
<a title='Subscribe to RSS Feeds' href="<?php echo esc_url( $option_setting['rss-feed'] ) ?>"><img src="<?php echo get_template_directory_uri()."/assets/images/social/rss.png"; ?>"></a>
<?php endif; ?>
<?php if ($option_setting['flickr']) : ?>
<a title='Flickr' href="<?php echo esc_url( $option_setting['flickr'] ) ?>"><img src="<?php echo get_template_directory_uri()."/assets/images/social/flickr.png"; ?>"></a>
<?php endif; ?>
<?php if ($option_setting['instagram']) : ?>
<a title='Instagram' href="<?php echo esc_url( $option_setting['instagram'] ) ?>"><img src="<?php echo get_template_directory_uri()."/assets/images/social/instagram.png"; ?>"></a>
<?php endif; ?>