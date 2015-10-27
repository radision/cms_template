<?php global $option_setting;
$count = 1;
if (isset($option_setting['showcase-enable-on-home'])) :
	if( $option_setting['showcase-enable-on-home'] && (is_front_page() || is_home() )) : 
		if ( count($option_setting['showcase-main']) > 0 ) : ?>
	
	    <div id="showcase">
	    <div class="container">
	    	<?php
			  		foreach ( $option_setting['showcase-main'] as $showcase ) {
			  				if ($count > 3) { break; }
							echo "<div class='col-md-4 col-sm-4 showcase'><figure><div><a href='".esc_url($showcase['url'])."'><img src='".$showcase['image']."'><div class='showcase-caption'><div class='showcase-caption-title'>".$showcase['title']."</div><div class='showcase-caption-desc'>".$showcase['description']."</div></div></a></div></figure></div>";  
							$count++;  
					}
	           ?>
	     </div>   
		</div><!--.showcase-->
	    
	<?php endif;
	endif;
endif; ?>