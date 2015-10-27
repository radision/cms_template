<?php global $option_setting;
$count = 1;
if (isset($option_setting['features-enable-on-home'])) :
	if( $option_setting['features-enable-on-home'] && (is_front_page() || is_home() )) : 
		if ( count($option_setting['features-main']) > 0 ) : ?>
	
	    <div id="features-area">
	    <div class="container">
	    	<?php
			  		foreach ( $option_setting['features-main'] as $features ) {
			  				if ($count > 3) { break; }
							echo "<div class='col-md-4 col-sm-4 feature'><figure><div><a href='".esc_url($features['url'])."'><img src='".$features['image']."'><div class='features-caption'><div class='features-caption-title'>".$features['title']."</div><div class='features-caption-desc'>".$features['description']."</div></div></a></div></figure></div>";  
							$count++;  
					}
	           ?>
	     </div>   
		</div><!--.features-->
	    
	<?php endif;
	endif;
endif; ?>