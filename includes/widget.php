<?php

/**
 * RII_Player_Widget
 */
class RII_Player_Widget extends WP_Widget {
	
	protected $widget_slug = 'rii';

	/**
	 * the constructor
	 */
	public function __construct() {
		parent::__construct(
			$this->rii_widget_slug(),
			__( 'Radio Islam Indonesia (RII)', $this->rii_widget_slug() ),
			array(
				'classname'		=> $this->rii_widget_slug() . '-player',
				'description'	=> __( 'Radio Islam Indonesia (RII) Player', $this->rii_widget_slug() )
			)
		);

		if ( is_active_widget( false, false, $this->id_base ) ) {
			add_action( 'init', array( $this, 'rii_data_class' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'rii_widget_assets' ) );
			add_action( 'wp_ajax_rii_data', array( $this, 'rii_data_json' ) );
			add_action( 'wp_ajax_nopriv_rii_data', array( $this, 'rii_data_json' ) );
		}
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		// instance
		$title 		= !empty( $instance['title'] ) ? $instance['title'] : '';
		$interval 	= $instance['interval'];
		$skin 		= $instance['skin'];
		$credits 	= $instance['credits'];

		// extract args
		extract( $args, EXTR_SKIP );

		echo $before_widget;

		if ( $title ) {
			echo $before_title . __( $title ) . $after_title;
		}

		include( plugin_dir_path( RII_PLUGIN_FILE ) . 'views/player.php' );

		echo $after_widget;
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {
		// defaults
		$instance = wp_parse_args( (array) $instance, 
			array( 
				'title' 	=> '',
				'interval' 	=> 30000, 
				'skin' 		=> 'dark',
				'credits'	=> false
			) 
		);
		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', $this->rii_widget_slug() ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>">
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'interval' ); ?>"><?php _e( 'Auto Update Interval:', $this->rii_widget_slug() ); ?></label><br>
			<select class="widefat" id="<?php echo $this->get_field_id( 'interval' ); ?>" name="<?php echo $this->get_field_name( 'interval' ); ?>">
				<?php
					$interval_options = array(
						0  => __( '0 (disable)', $this->rii_widget_slug() ),  
						15000 => __( '15 seconds', $this->rii_widget_slug() ), 
						30000 => __( '30 seconds', $this->rii_widget_slug() ),
						45000 => __( '45 seconds', $this->rii_widget_slug() ), 
						60000 => __( '60 seconds', $this->rii_widget_slug() )  
					);

					foreach ( $interval_options as $s => $n ) {
						printf(
							'<option value="%1$s"%3$s>%2$s</option>',
							esc_attr( $s ),
							esc_html( $n ),
							selected( $instance['interval'], $s, false )
						);
					}
				?>
			</select>
			<small><?php _e( 'Default is 30 seconds. Set 0 to disable auto update.', $this->rii_widget_slug() ); ?></small>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'skin' ); ?>"><?php _e( 'Player Skin:', $this->rii_widget_slug() ); ?></label><br>
			<select class="widefat" id="<?php echo $this->get_field_id( 'skin' ); ?>" name="<?php echo $this->get_field_name( 'skin' ); ?>">
				<?php
					$skin_options = array( 'dark' => __( 'Dark', $this->rii_widget_slug() ), 'light' => __( 'Light', $this->rii_widget_slug() ) );
					foreach ( $skin_options as $s => $n ) {
						printf(
							'<option value="%1$s"%3$s>%2$s</option>',
							esc_attr( $s ),
							esc_html( $n ),
							selected( $instance['skin'], $s, false )
						);
					}
				?>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'credits' ); ?>"><?php _e( 'Show Credits (Powered By):', $this->rii_widget_slug() ); ?> 
				<input class="checkbox" id="<?php echo $this->get_field_id( 'credits' ); ?>" name="<?php echo $this->get_field_name( 'credits' ); ?>" type="checkbox" <?php checked($instance['credits'], true) ?> />
			</label>
		</p>

		<?php
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 */
	public function update( $new_instance, $old_instance ) {
		// processes widget options to be saved
		$instance = array();
		$instance['title'] 		= ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['interval'] 	= $new_instance['interval'];
		$instance['skin'] 		= $new_instance['skin'];
		$instance['credits'] 	= isset( $new_instance['credits'] ) ? 1 : 0;

		return $instance;
	}

	/**
	 * additional function
	 *
	 * widget slug
	 */
	private function rii_widget_slug() {
		return $this->widget_slug;
	}

	/**
	 * RII data
	 */
	public function rii_data_class() {
		include_once( plugin_dir_path( RII_PLUGIN_FILE ) . 'class/rii.class.php' );
	}

	/**
	 * ajax request for channels
	 * be carefully don't edit this line
	 */
	public function rii_data_json() {
		check_ajax_referer( 'rii_data_nonce', 'security' );
		$channels = esc_url( 'http://hirsh.radioislam.or.id/radio/lrii.php?model=lima' );
		$data = new RII_Data( $channels, $is_xml = false, array( 'sslverify' => false ) );
		$data->generate_json();
		exit;
	}

	/**
	 * widget assets
	 */
	public function rii_widget_assets() {
		wp_register_style( 'rii-font-awesome', plugins_url( 'assets/css/vendor/simple-line-icons.css', RII_PLUGIN_FILE ), array(), '2.4.0', 'all' );
		wp_register_style( 'rii-google-fonts', '//fonts.googleapis.com/css?family=Roboto', array(), RII_PLUGIN_VERSION, 'all' );
		wp_register_style( 'rii-jquery-scrollbar', plugins_url( 'assets/css/vendor/jquery.scrollbar.min.css', RII_PLUGIN_FILE ),  array(), '3.0.8', 'all' );
		wp_register_style( $this->rii_widget_slug() . '-style', plugins_url( 'assets/css/rii.min.css', RII_PLUGIN_FILE ), array( 'rii-font-awesome', 'rii-google-fonts', 'rii-jquery-scrollbar' ), RII_PLUGIN_VERSION, 'all' );

		wp_register_script( 'rii-jquery-jplayer', plugins_url( 'assets/js/vendor/jquery.jplayer.min.js', RII_PLUGIN_FILE ), array(), '2.9.2', false );
		wp_register_script( 'rii-jquery-mousewheel', plugins_url( 'assets/js/vendor/jquery.mousewheel.min.js', RII_PLUGIN_FILE ), array(), '3.1.12', false );
		wp_register_script( 'rii-jquery-scrollbar', plugins_url( 'assets/js/vendor/jquery.scrollbar.min.js', RII_PLUGIN_FILE ), array(), '3.0.8', false );
		wp_register_script( 'rii-jquery-reverseorder', plugins_url( 'assets/js/vendor/jquery.reverseorder.min.js', RII_PLUGIN_FILE ), array(), RII_PLUGIN_VERSION, false );
		wp_register_script( $this->rii_widget_slug() . '-script', plugins_url( 'assets/js/rii.min.js', RII_PLUGIN_FILE ), array( 'jquery', 'rii-jquery-jplayer', 'rii-jquery-reverseorder', 'rii-jquery-mousewheel', 'rii-jquery-scrollbar' ), RII_PLUGIN_VERSION, false );
		// localize
		wp_localize_script( $this->rii_widget_slug() . '-script', 'rii', array(
				'ajaxurl' 	=> admin_url( 'admin-ajax.php' ),
				'nonce'	 	=> wp_create_nonce( 'rii_data_nonce' ),
				'logo_rii'	=> plugins_url( 'assets/images/rii.png', RII_PLUGIN_FILE ),
				'logo_quran'=> plugins_url( 'assets/images/radio-quran.png', RII_PLUGIN_FILE ),
				'logo_at'	=> plugins_url( 'assets/images/radio-at.png', RII_PLUGIN_FILE ),
			)
		);
		// enqueue
		wp_enqueue_style( $this->rii_widget_slug() . '-style' );
		wp_enqueue_script( $this->rii_widget_slug() . '-script' );
	}
}

/**
 * register widget action
 * PHP 5.2+: 
 */
add_action( 'widgets_init',
     create_function('', 'return register_widget("RII_Player_Widget");')
);