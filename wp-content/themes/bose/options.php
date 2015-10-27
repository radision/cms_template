<?php

/**
  ReduxFramework Sample Config File
  For full documentation, please visit: https://docs.reduxframework.com
 * */

if (!class_exists('Redux_Framework_sample_config')) {

    class Redux_Framework_sample_config {

        public $args        = array();
        public $sections    = array();
        public $theme;
        public $ReduxFramework;

        public function __construct() {

            if (!class_exists('ReduxFramework')) {
                return;
            }

            // This is needed. Bah WordPress bugs.  ;)
            if (  true == Redux_Helpers::isTheme(__FILE__) ) {
                $this->initSettings();
            } else {
                add_action('plugins_loaded', array($this, 'initSettings'), 10);
            }

        }

        public function initSettings() {

            // Just for demo purposes. Not needed per say.
            $this->theme = wp_get_theme();

            // Set the default arguments
            $this->setArguments();

            // Set a few help tabs so you can see how it's done
            $this->setHelpTabs();

            // Create the sections and fields
            $this->setSections();

            if (!isset($this->args['opt_name'])) { // No errors please
                return;
            }

            // If Redux is running as a plugin, this will remove the demo notice and links
            //add_action( 'redux/loaded', array( $this, 'remove_demo' ) );
            
            // Function to test the compiler hook and demo CSS output.
            // Above 10 is a priority, but 2 in necessary to include the dynamically generated CSS to be sent to the function.
            //add_filter('redux/options/'.$this->args['opt_name'].'/compiler', array( $this, 'compiler_action' ), 10, 2);
            
            // Change the arguments after they've been declared, but before the panel is created
            //add_filter('redux/options/'.$this->args['opt_name'].'/args', array( $this, 'change_arguments' ) );
            
            // Change the default value of a field after it's been set, but before it's been useds
            //add_filter('redux/options/'.$this->args['opt_name'].'/defaults', array( $this,'change_defaults' ) );
            
            // Dynamically add a section. Can be also used to modify sections/fields
            //add_filter('redux/options/' . $this->args['opt_name'] . '/sections', array($this, 'dynamic_section'));

            $this->ReduxFramework = new ReduxFramework($this->sections, $this->args);
        }

        /**

          This is a test function that will let you see when the compiler hook occurs.
          It only runs if a field	set with compiler=>true is changed.

         * */
        function compiler_action($options, $css) {
            //echo '<h1>The compiler hook has run!';
            //print_r($options); //Option values
            //print_r($css); // Compiler selector CSS values  compiler => array( CSS SELECTORS )

            /*
              // Demo of how to use the dynamic CSS and write your own static CSS file
              $filename = dirname(__FILE__) . '/style' . '.css';
              global $wp_filesystem;
              if( empty( $wp_filesystem ) ) {
                require_once( ABSPATH .'/wp-admin/includes/file.php' );
              WP_Filesystem();
              }

              if( $wp_filesystem ) {
                $wp_filesystem->put_contents(
                    $filename,
                    $css,
                    FS_CHMOD_FILE // predefined mode settings for WP files
                );
              }
             */
        }

        /**

          Custom function for filtering the sections array. Good for child themes to override or add to the sections.
          Simply include this function in the child themes functions.php file.

          NOTE: the defined constants for URLs, and directories will NOT be available at this point in a child theme,
          so you must use get_template_directory_uri() if you want to use any of the built in icons

         * */
        function dynamic_section($sections) {
            //$sections = array();
            $sections[] = array(
                'title' => __('Section via hook', 'bose'),
                'desc' => __('<p class="description">This is a section created by adding a filter to the sections array. Can be used by child themes to add/remove sections from the options.</p>', 'bose'),
                'icon' => 'el-icon-paper-clip',
                // Leave this as a blank section, no options just some intro text set above.
                'fields' => array()
            );

            return $sections;
        }

        /**

          Filter hook for filtering the args. Good for child themes to override or add to the args array. Can also be used in other functions.

         * */
        function change_arguments($args) {
            //$args['dev_mode'] = true;

            return $args;
        }

        /**

          Filter hook for filtering the default value of any given field. Very useful in development mode.

         * */
        function change_defaults($defaults) {
            $defaults['str_replace'] = 'Testing filter hook!';

            return $defaults;
        }

        // Remove the demo link and the notice of integrated demo from the redux-framework plugin
        function remove_demo() {

            // Used to hide the demo mode link from the plugin page. Only used when Redux is a plugin.
            if (class_exists('ReduxFrameworkPlugin')) {
                remove_filter('plugin_row_meta', array(ReduxFrameworkPlugin::instance(), 'plugin_metalinks'), null, 2);

                // Used to hide the activation notice informing users of the demo panel. Only used when Redux is a plugin.
                remove_action('admin_notices', array(ReduxFrameworkPlugin::instance(), 'admin_notices'));
            }
        }

        public function setSections() {

            /**
              Used within different fields. Simply examples. Search for ACTUAL DECLARATION for field examples
             * */
            // Background Patterns Reader
            $sample_patterns_path   = ReduxFramework::$_dir . '../config/patterns/';
            $sample_patterns_url    = ReduxFramework::$_url . '../config/patterns/';
            $sample_patterns        = array();

            if (is_dir($sample_patterns_path)) :

                if ($sample_patterns_dir = opendir($sample_patterns_path)) :
                    $sample_patterns = array();

                    while (( $sample_patterns_file = readdir($sample_patterns_dir) ) !== false) {

                        if (stristr($sample_patterns_file, '.png') !== false || stristr($sample_patterns_file, '.jpg') !== false) {
                            $name = explode('.', $sample_patterns_file);
                            $name = str_replace('.' . end($name), '', $sample_patterns_file);
                            $sample_patterns[]  = array('alt' => $name, 'img' => $sample_patterns_url . $sample_patterns_file);
                        }
                    }
                endif;
            endif;

            ob_start();

            $ct             = wp_get_theme();
            $this->theme    = $ct;
            $item_name      = $this->theme->get('Name');
            $tags           = $this->theme->Tags;
            $screenshot     = $this->theme->get_screenshot();
            $class          = $screenshot ? 'has-screenshot' : '';

            $customize_title = sprintf(__('Customize &#8220;%s&#8221;', 'bose'), $this->theme->display('Name'));
            
            ?>
            <div id="current-theme" class="<?php echo esc_attr($class); ?>">
            <?php if ($screenshot) : ?>
                <?php if (current_user_can('edit_theme_options')) : ?>
                        <a href="<?php echo wp_customize_url(); ?>" class="load-customize hide-if-no-customize" title="<?php echo esc_attr($customize_title); ?>">
                            <img src="<?php echo esc_url($screenshot); ?>" alt="<?php esc_attr_e('Current theme preview'); ?>" />
                        </a>
                <?php endif; ?>
                    <img class="hide-if-customize" src="<?php echo esc_url($screenshot); ?>" alt="<?php esc_attr_e('Current theme preview'); ?>" />
                <?php endif; ?>

                <h4><?php echo $this->theme->display('Name'); ?></h4>

                <div>
                    <ul class="theme-info">
                        <li><?php printf(__('By %s', 'bose'), $this->theme->display('Author')); ?></li>
                        <li><?php printf(__('Version %s', 'bose'), $this->theme->display('Version')); ?></li>
                        <li><?php echo '<strong>' . __('Tags', 'bose') . ':</strong> '; ?><?php printf($this->theme->display('Tags')); ?></li>
                    </ul>
                    <p class="theme-description"><?php echo $this->theme->display('Description'); ?></p>
            <?php
            if ($this->theme->parent()) {
                printf(' <p class="howto">' . __('This <a href="%1$s">child theme</a> requires its parent theme, %2$s.') . '</p>', __('http://codex.wordpress.org/Child_Themes', 'bose'), $this->theme->parent()->display('Name'));
            }
            ?>

                </div>
            </div>

            <?php
            $item_info = ob_get_contents();

            ob_end_clean();

            $sampleHTML = '';
            if (file_exists(dirname(__FILE__) . '/info-html.html')) {
                /** @global WP_Filesystem_Direct $wp_filesystem  */
                global $wp_filesystem;
                if (empty($wp_filesystem)) {
                    require_once(ABSPATH . '/wp-admin/includes/file.php');
                    WP_Filesystem();
                }
                $sampleHTML = $wp_filesystem->get_contents(dirname(__FILE__) . '/info-html.html');
            }

            	$this->sections[] = array(
                'title'     => __('Basic', 'bose'),
                'icon'      => 'el-icon-puzzle',
                // 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                'fields'    => array(
                	array(
                        'id'        => 'logo',
                        'type'      => 'media',
                        'url'       => true,
                        'title'     => __('Logo', 'bose'),
                        'compiler'  => 'true',
                        //'mode'      => false, // Can be set to false to allow any media type, or can also be set to any mime type.
                        'desc'      => __('Enabling Logo will Disable the default title and description.', 'bose'),
                        'subtitle'  => __('Upload any Image. It will not be resized Automatically.', 'bose'),
                        'default'   => '',
                    ),
                    array(
                        'id'        => 'blog-layout',
                        'type'      => 'select',
                        'title'     => __('Blog Layout', 'bose'),
                        'compiler'  => 'true',
                        'desc'      => __('Select the Default Blog Layout', 'bose'),
                        'subtitle'  => __('You Can Choose from Classic Layout, or 4 Column Grid Layout.', 'bose'),
                        'options'   => array(
                        					'grid2' => '2 Column Classic',
                        					'grid3'	=> '4 Column Grid' //grid3 is a 4 column layout
                        				),
                        'default'   => 'grid3',
                    ),
                    
                   array(
	                        'id'    => 'pro-layouts',
	                        'type'  => 'info',
	                        'title' => __('<a href="http://inkhive.com/product/bose-plus/" target="_blank">Upgrade to Bose Pro</a>', 'seller'),
	                        'desc'  => __('Pro Version Comes with more layouts, with optional sidebars and many other settings.',  'bose')
	                    ),
      
                    ),
                    
                  );
                  

            // ACTUAL DECLARATION OF SECTIONS
            $this->sections[] = array(
                'title'     => __('Slider', 'bose'),
                'icon'      => 'el-icon-picture',
                // 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                'fields'    => array(
                		array(
	                        'id'        => 'slider-enable-on-home',
	                        'type'      => 'checkbox',
	                        'title'     => __('Enable Slider on Home', 'bose'),
	                        'subtitle'  => __('This will enable slider on both the front page, and homepage.', 'bose'),
	                        //'options' => array('on', 'off'),
	                        'default'   => false,
                        ),
                       
	                	array(
	                        'id'        => 'slider-main',
	                        'type'      => 'slides',
	                        'title'     => __('Slides Options', 'bose'),
	                        'subtitle'  => __('Maximum 5 slides with drag and drop sortings.', 'bose'),
	                        'desc'      => __('', 'bose'),
	                        'placeholder'   => array(
	                            'title'         => __('Slide Title', 'bose'),
	                            'url'           => __('URL you want to Link.', 'bose'),
	                        ),
	                    ),
	                    
	                    array(
	                        'id'    => 'pro-slider',
	                        'type'  => 'info',
	                        'title' => __('<a href="http://inkhive.com/product/bose-plus/" target="_blank">Upgrade to Bose Plus</a>', 'seller'),
	                        'desc'  => __('The Slider in Pro Version has option to create unlimited slides, with options to configure Animations, Speed, etc. You can also add Slider on Individual Pages.',  'bose')
	                    ),
      

                    ), 
             );
             
            $this->sections[] = array(
                'title'     => __('Features Zone', 'bose'),
                'icon'      => 'el-icon-website',
                // 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                'fields'    => array(
                		array(
	                        'id'        => 'features-enable-on-home',
	                        'type'      => 'checkbox',
	                        'title'     => __('Enable Features Zone on Home', 'bose'),
	                        'subtitle'  => __('This will enable Features on both the static front page, and homepage(blog).', 'bose'),
	                        //'options' => array('on', 'off'),
	                        'default'   => false,
                        ),
	                	array(
	                        'id'        => 'features-main',
	                        'type'      => 'slides',
	                        'title'     => __('Featured Items', 'bose'),
	                        'subtitle'  => __('Add Exactly 3 items.', 'bose'),
	                        'desc'      => __('If you add less than 3 items, the orientation will be distorted.', 'bose'),
	                        'placeholder'   => array(
	                            'title'         => __('Features Title', 'bose'),
	                            'url'           => __('URL you want to Link.', 'bose'),
	                        ),
	                    ),

                    ), 
             );
             
             // ACTUAL DECLARATION OF SECTIONS
            $this->sections[] = array(
                'title'     => __('Showcase', 'bose'),
                'icon'      => 'el-icon-th',
                // 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                'fields'    => array(
                		array(
	                        'id'        => 'showcase-enable-on-home',
	                        'type'      => 'checkbox',
	                        'title'     => __('Enable Showcase on Home', 'bose'),
	                        'subtitle'  => __('This will enable showcase on both the static front page, and homepage(blog).', 'bose'),
	                        //'options' => array('on', 'off'),
	                        'default'   => false,
                        ),
                        
	                	array(
	                        'id'        => 'showcase-main',
	                        'type'      => 'slides',
	                        'title'     => __('Showcase Items', 'bose'),
	                        'subtitle'  => __('Add Exactly 3 items.', 'bose'),
	                        'desc'      => __('If you add less than 3 items, the orientation will be distorted.', 'bose'),
	                        'placeholder'   => array(
	                            'title'         => __('Showcase Title', 'bose'),
	                            'url'           => __('URL you want to Link.', 'bose'),
	                        ),
	                    ),

                    ), 
             );

             $this->sections[] = array(
                'title'     => __('Featured Posts', 'bose'),
                'icon'      => 'el-icon-youtube',
                // 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                'fields'    => array(
                		array(
	                        'id'        => 'featured-enable-on-home',
	                        'type'      => 'checkbox',
	                        'title'     => __('Enable Showcase on Home', 'bose'),
	                        'subtitle'  => __('This will enable showcase on both the static front page, and homepage(blog).', 'bose'),
	                        //'options' => array('on', 'off'),
	                        'default'   => false,
                        ),
	                	array(
	                        'id'        => 'featured-cats',
	                        'type'      => 'select',
	                        'data'      => 'categories',
	                        'multi'     => true,
	                        'title'     => __('Categories', 'verge'),
	                        'subtitle'  => __('Select all the Categories from which the Posts should be Fetched.', 'verge'),
	                    ),

                    ), 
             );

             $this->sections[] = array(
                'title'     => __('Social Icons', 'bose'),
                'icon'      => 'el-icon-facebook',
                // 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                'fields'    => array(
                		array(
	                        'id'        => 'enable-social-icons',
	                        'type'      => 'checkbox',
	                        'title'     => __('Enable Social Icons', 'bose'),
	                        'subtitle'  => __('This will enable social icons in the top menu bar. If you want a full width menu, then you need to disable the social icons.', 'bose'),
	                        //'options' => array('on', 'off'),
	                        'default'   => false,
                        ),
                        
	                	 array(
	                        'id'        => 'facebook',
	                        'type'      => 'text',
	                        'title'     => __('Facebook', 'bose'),
	                        'subtitle'  => __('Enter Complete URL including http://', 'bose'),
	                        'validate'  => 'url',
	                        'default'   => 'http://facebook.com/#/',
                        ),
                        array(
	                        'id'        => 'twitter',
	                        'type'      => 'text',
	                        'title'     => __('Twitter', 'bose'),
	                        'subtitle'  => __('Enter Complete URL including http://', 'bose'),
	                        'validate'  => 'url',
	                        'default'   => 'http://twitter.com/#/',
                        ),
                        array(
	                        'id'        => 'google',
	                        'type'      => 'text',
	                        'title'     => __('Google+', 'bose'),
	                        'subtitle'  => __('Enter Complete URL including http://', 'bose'),
	                        'validate'  => 'url',
	                        'default'   => 'http://plus.google.com/#/',
                        ),
                        array(
	                        'id'        => 'rss-feed',
	                        'type'      => 'text',
	                        'title'     => __('RSS Feed', 'bose'),
	                        'subtitle'  => __('Enter Complete URL including http://', 'bose'),
	                        'validate'  => 'url',
	                        'default'   => 'http://feeds.feedburner.com/#/',
                        ),
                        array(
	                        'id'        => 'instagram',
	                        'type'      => 'text',
	                        'title'     => __('Instagram', 'bose'),
	                        'subtitle'  => __('Enter Complete URL including http://', 'bose'),
	                        'validate'  => 'url',
	                        'default'   => 'http://instagram.com/#/',
                        ),
                        array(
	                        'id'        => 'flickr',
	                        'type'      => 'text',
	                        'title'     => __('Flickr', 'bose'),
	                        'subtitle'  => __('Enter Complete URL including http://', 'bose'),
	                        'validate'  => 'url',
	                        'default'   => 'http://flickr.com/#/',
                        ),
                        
                        array(
	                        'id'    => 'pro-layouts',
	                        'type'  => 'info',
	                        'title' => __('<a href="http://inkhive.com/product/bose-plus/" target="_blank">Upgrade to Seller Pro</a>', 'seller'),
	                        'desc'  => __('Pro Version Comes with over 15 Social Icons, and you can request any as well.',  'bose')
	                    ),
      

                    ), 
             );

             $this->sections[] = array(
                'title'     => __('Custom CSS', 'bose'),
                'icon'      => 'el-icon-broom',
                // 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                'fields'    => array(
                		array(
                        'id'        => 'custom-css',
                        'type'      => 'ace_editor',
                        'title'     => __('CSS Code', 'bose'),
                        'subtitle'  => __('Paste your CSS code here.', 'bose'),
                        'mode'      => 'css',
                        'theme'     => 'monokai',
                        'default'   => ""
                    ),
                    

                    ),
                    
             );

            $this->sections[] = array(
                'title'     => __('Import / Export', 'bose'),
                'desc'      => __('Import and Export your Redux Framework settings from file, text or URL.', 'bose'),
                'icon'      => 'el-icon-refresh',
                'fields'    => array(
                    array(
                        'id'            => 'opt-import-export',
                        'type'          => 'import_export',
                        'title'         => 'Import Export',
                        'subtitle'      => 'Save and restore your Redux options',
                        'full_width'    => false,
                    ),
                ),
            );                     
                    
            $this->sections[] = array(
                'type' => 'divide',
            );

            $this->sections[] = array(
                'icon'      => 'el-icon-info-sign',
                'title'     => __('Theme Information', 'bose'),
                'desc'      => __('This is how your site will look once ready.', 'bose'),
                'fields'    => array(
                    array(
                        'id'        => 'opt-raw-info',
                        'type'      => 'raw',
                        'content'   => $item_info,
                    )
                ),
            );

        }

        public function setHelpTabs() {

            // Custom page help tabs, displayed using the help API. Tabs are shown in order of definition.
            $this->args['help_tabs'][] = array(
                'id'        => 'redux-help-tab-1',
                'title'     => __('Theme Information 1', 'bose'),
                'content'   => __('<p>This is the tab content, HTML is allowed.</p>', 'bose')
            );

            $this->args['help_tabs'][] = array(
                'id'        => 'redux-help-tab-2',
                'title'     => __('Theme Information 2', 'bose'),
                'content'   => __('<p>This is the tab content, HTML is allowed.</p>', 'bose')
            );

            // Set the help sidebar
            $this->args['help_sidebar'] = __('<p>This is the sidebar content, HTML is allowed.</p>', 'bose');
        }

        /**

          All the possible arguments for Redux.
          For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments

         * */
        public function setArguments() {

            $theme = wp_get_theme(); // For use with some settings. Not necessary.

            $this->args = array(
                // TYPICAL -> Change these values as you need/desire
                'opt_name'          => 'bose_setting',            // This is where your data is stored in the database and also becomes your global variable name.
                'display_name'      => 'Bose Theme Options',     // Name that appears at the top of your panel
                'display_version'   => 'by InkHive.com',  // Version that appears at the top of your panel
                'menu_type'         => 'submenu',                  //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
                'allow_sub_menu'    => true,                    // Show the sections below the admin menu item or not
                'menu_title'        => __('Theme Options', 'bose'),
                'page_title'        => __('Bose Theme Options', 'bose'),
                
                // You will need to generate a Google API key to use this feature.
                // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
                'google_api_key' => '', // Must be defined to add google fonts to the typography module
                
                'async_typography'  => false,                   // Use a asynchronous font on the front end or font string
                'admin_bar'         => true,                    // Show the panel pages on the admin bar
                'global_variable'   => 'option_setting',        // Set a different name for your global variable other than the opt_name
                'dev_mode'          => false,                   // Show the time the page took to load, etc
                'customizer'        => true,                    // Enable basic customizer support
                
                // OPTIONAL -> Give you extra features
                'page_priority'     => null,                    // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
                'page_parent'       => 'themes.php',            // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
                'page_permissions'  => 'manage_options',        // Permissions needed to access the options panel.
                'menu_icon'         => '',                      // Specify a custom URL to an icon
                'last_tab'          => '',                      // Force your panel to always open to a specific tab (by id)
                'page_icon'         => 'icon-themes',           // Icon displayed in the admin panel next to your menu_title
                'page_slug'         => 'theme_options',         // Page slug used to denote the panel
                'save_defaults'     => true,                    // On load save the defaults to DB before user clicks save or not
                'default_show'      => false,                   // If true, shows the default value next to each field that is not the default value.
                'default_mark'      => '',                      // What to print by the field's title if the value shown is default. Suggested: *
                'show_import_export' => false,                   // Shows the Import/Export panel when not used as a field.
                
                // CAREFUL -> These options are for advanced use only
                'transient_time'    => 60 * MINUTE_IN_SECONDS,
                'output'            => true,                    // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
                'output_tag'        => true,                    // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
                 'footer_credit'     => '',                   // Disable the footer credit of Redux. Please leave if you can help it.
                
                // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
                'database'              => '', // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
                'system_info'           => false, // REMOVE

                // HINTS
                'hints' => array(
                    'icon'          => 'icon-question-sign',
                    'icon_position' => 'right',
                    'icon_color'    => 'lightgray',
                    'icon_size'     => 'normal',
                    'tip_style'     => array(
                        'color'         => 'light',
                        'shadow'        => true,
                        'rounded'       => false,
                        'style'         => '',
                    ),
                    'tip_position'  => array(
                        'my' => 'top left',
                        'at' => 'bottom right',
                    ),
                    'tip_effect'    => array(
                        'show'          => array(
                            'effect'        => 'slide',
                            'duration'      => '500',
                            'event'         => 'mouseover',
                        ),
                        'hide'      => array(
                            'effect'    => 'slide',
                            'duration'  => '500',
                            'event'     => 'click mouseleave',
                        ),
                    ),
                )
            );


            // SOCIAL ICONS -> Setup custom links in the footer for quick links in your panel footer icons.
            $this->args['share_icons'][] = array(
                'url'   => 'https://github.com/rohitink/',
                'title' => 'Visit us on GitHub',
                'icon'  => 'el-icon-github'
                //'img'   => '', // You can use icon OR img. IMG needs to be a full URL.
            );
            $this->args['share_icons'][] = array(
                'url'   => 'https://www.facebook.com/inkhivethemes/',
                'title' => 'Like us on Facebook',
                'icon'  => 'el-icon-facebook'
            );
            $this->args['share_icons'][] = array(
                'url'   => 'http://twitter.com/rohitinked',
                'title' => 'Follow us on Twitter',
                'icon'  => 'el-icon-twitter'
            );

            // Panel Intro text -> before the form
            if (!isset($this->args['global_variable']) || $this->args['global_variable'] !== false) {
                if (!empty($this->args['global_variable'])) {
                    $v = $this->args['global_variable'];
                } else {
                    $v = str_replace('-', '_', $this->args['opt_name']);
                }
                $this->args['intro_text'] = sprintf(__('<p>Welcome to Bose Theme Options. <a href="http://demo.inkhive.com/bose/" target="_blank">View Theme Demo</a> | <a href="http://wordpress.org/support/theme/bose/" target="_blank">Free Support Forums</a> | <a href="http://demo.inkhive.com/bose-plus/" target="_blank">Bose Plus Demo</a> | <a href="http://inkhive.com/product/bose-plus/" target="_blank">Buy Pro Version</a>','bose'));
            } else {
                $this->args['intro_text'] = __('<p>Upgrade to pro</p>', 'bose');
            }

            // Add content after the form.
            $this->args['footer_text'] = __('<p>To report bugs, send us an email.</p>', 'bose');
        }
    }
    
    global $reduxConfig;
    $reduxConfig = new Redux_Framework_sample_config();
}

/**
  Custom function for the callback referenced above
 */
if (!function_exists('redux_my_custom_field')):
    function redux_my_custom_field($field, $value) {
        print_r($field);
        echo '<br/>';
        print_r($value);
    }
endif;

/**
  Custom function for the callback validation referenced above
 * */
if (!function_exists('redux_validate_callback_function')):
    function redux_validate_callback_function($field, $value, $existing_value) {
        $error = false;
        $value = 'just testing';

        /*
          do your validation

          if(something) {
            $value = $value;
          } elseif(something else) {
            $error = true;
            $value = $existing_value;
            $field['msg'] = 'your custom error message';
          }
         */

        $return['value'] = $value;
        if ($error == true) {
            $return['error'] = $field;
        }
        return $return;
    }
endif;
