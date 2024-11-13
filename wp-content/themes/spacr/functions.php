<?php

/**
 * Function describe for Spacr child
 * 
 * @package spacr
 */
function spacr_child_enqueue_styles() {

	$parent_style = 'entr-stylesheet';

	$dep = array( 'bootstrap' );
	if ( class_exists( 'WooCommerce' ) ) {
		$dep = array( 'bootstrap', 'entr-woo-stylesheet' );
	}

	wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css', array( 'bootstrap' ) );
	wp_enqueue_style( 'spacr-stylesheet', get_stylesheet_directory_uri() . '/style.css', $dep, wp_get_theme()->get( 'Version' ) );
}

add_action( 'wp_enqueue_scripts', 'spacr_child_enqueue_styles' );


add_action( 'after_setup_theme', 'spacr_setup' );

function spacr_setup() {
  // Remove parent theme header fields
	remove_action( 'entr_header', 'entr_title_logo', 10 );
	remove_action( 'entr_header', 'entr_menu', 20 );
	remove_action( 'entr_header', 'entr_head_start', 25 );
	remove_action( 'entr_header', 'entr_head_end', 80 );
  remove_action('entr_header', 'entr_menu_button', 28);
  // Create child theme header
	add_action( 'spacr_header', 'entr_head_start', 25 );
	add_action( 'spacr_header', 'entr_head_end', 80 );
  add_action( 'spacr_header', 'spacr_header_widget_area', 20 );
  add_action( 'spacr_header', 'spacr_title_logo', 10 );
  add_action( 'entr_header', 'spacr_main_menu', 20 );
  add_action( 'spacr_header', 'entr_menu_button', 28);
  
	if ( class_exists( 'WooCommerce' ) ) {
		// re-position WooCommerce icons
		remove_action( 'entr_header', 'entr_header_cart', 30 );
		remove_action( 'entr_header', 'entr_my_account', 40 );
		remove_action( 'entr_header', 'entr_head_wishlist', 50 );
		remove_action( 'entr_header', 'entr_head_compare', 60 );
		add_action( 'spacr_header', 'entr_header_cart', 30 );
		add_action( 'spacr_header', 'entr_my_account', 40 );
		add_action( 'spacr_header', 'entr_head_wishlist', 50 );
		add_action( 'spacr_header', 'entr_head_compare', 60 );
	}
  // Create new menu location
  register_nav_menus(
    array(
      'main_menu_right' => esc_html__('Menu Right', 'spacr'),
    )
  );
  if (function_exists('entr_customizer')) { add_action( 'init', 'entr_customizer' ); 	}
}


/**
 * Title, logo code
 */
function spacr_title_logo() {
	?>
	<div class="site-heading">    
		<div class="site-branding-logo">
			<?php the_custom_logo(); ?>
		</div>
		<div class="site-branding-text">
			<?php if ( is_front_page() ) : ?>
				<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
			<?php else : ?>
				<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
			<?php endif; ?>

			<?php
			$description = get_bloginfo( 'description', 'display' );
			if ( $description || is_customize_preview() ) :
				?>
				<p class="site-description">
					<?php echo esc_html( $description ); ?>
				</p>
			<?php endif; ?>
		</div><!-- .site-branding-text -->
	</div>
	<?php
}

/**
 * Menu position change
 */
function spacr_main_menu() {
	?>
	<div class="menu-heading">
		<nav id="site-navigation" class="navbar navbar-default">
			<?php
			wp_nav_menu( array(
				'theme_location'	 => 'main_menu',
				'depth'				 => 5,
				'container_id'		 => 'theme-menu',
				'container'			 => 'div',
				'container_class'	 => 'menu-container',
				'menu_class'		 => 'nav navbar-nav navbar-' . get_theme_mod( 'menu_position', 'left' ),
				'fallback_cb'		 => 'Entr_WP_Bootstrap_Navwalker::fallback',
				'walker'			 => new Entr_WP_Bootstrap_Navwalker(),
			) ); 
      wp_nav_menu(array(
                'theme_location' => 'main_menu_right',
                'depth' => 1,
                'container_id' => 'my-menu-right',
                'container' => 'div',
                'container_class' => 'menu-container',
                'menu_class' => 'nav navbar-nav navbar-right',
                'fallback_cb' => 'Entr_WP_Bootstrap_Navwalker::fallback',
                'walker' => new Entr_WP_Bootstrap_Navwalker(),
            ));      
			?>
		</nav>
	</div>
	<?php
}



/**
 * Add header widget area
 */
function spacr_header_widget_area() {
	?>
	<div class="header-widget-area">
    <?php if ( class_exists( 'WooCommerce' ) ) { ?>
      <div class="menu-search-widget">
    		<?php the_widget( 'WC_Widget_Product_Search', 'title=' ); ?>
    	</div>
    <?php } ?>
		<?php if ( is_active_sidebar( 'spacr-header-area' ) ) { ?>
			<div class="site-heading-sidebar" >
				<?php dynamic_sidebar( 'spacr-header-area' ); ?>
			</div>
		<?php } ?>
	</div>
	<?php
}

add_action( 'widgets_init', 'spacr_widgets_init' );

/**
 * Register the Sidebars
 */
function spacr_widgets_init() {
	register_sidebar(
  	array(
  		'name'			 => esc_html__( 'Header Section', 'spacr' ),
  		'id'			 => 'spacr-header-area',
  		'before_widget'	 => '<div id="%1$s" class="widget %2$s">',
  		'after_widget'	 => '</div>',
  		'before_title'	 => '<div class="widget-title"><h3>',
  		'after_title'	 => '</h3></div>',
  	) 
	);
  register_sidebar(
    array(
  		'name'			 => esc_html__( 'Top bar', 'spacr' ),
  		'id'			 => 'spacr-top-bar',
  		'before_widget'	 => '<div id="%1$s" class="widget %2$s col-sm-4">',
  		'after_widget'	 => '</div>',
  		'before_title'	 => '<div class="widget-title"><h3>',
  		'after_title'	 => '</h3></div>',
  	),
  );
}

if (!function_exists('spacr_top_bar_header_code')) :

    /**
     * Create top bar widget area
     */
    add_action('spacr_top_bar_header', 'spacr_top_bar_header_code');

    function spacr_top_bar_header_code() {
        if (is_active_sidebar('spacr-top-bar')) { ?>
            <div class="top-bar-section container-fluid">
                <div class="<?php echo esc_attr(get_theme_mod('top_bar_content_width', 'container')); ?>">
                    <div class="row">
                        <?php dynamic_sidebar('spacr-top-bar'); ?>
                    </div>
                </div>
            </div>
        <?php } ?>
        <div class="site-header title-header container-fluid">
  				<div class="container" >
  					<div class="heading-row row" >
  						<?php do_action( 'spacr_header' ); ?>
  					</div>
  				</div>
  			</div>
        <?php
    }

endif;

add_filter( 'body_class','spacr_body_classes' );
function spacr_body_classes( $classes ) {
	
	if ( !class_exists( 'WooCommerce' ) ) {
		$classes[] = 'woo-off';
	}
	if ( !is_active_sidebar( 'spacr-header-area' ) && !class_exists( 'WooCommerce' ) ) {
		$classes[] = 'header-widgets-off';	
	}
	    
  return $classes;
     
}
