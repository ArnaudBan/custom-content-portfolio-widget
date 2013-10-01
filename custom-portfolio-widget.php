<?php
/**
 * Plugin Name: Custom Content Portfolio Widget
 * Plugin URI: http://arnaudBan.me/portfolio/wordpress-plugin/custom-content-portfolio-widget
 * Description: Add Widget to display the last items of your Portfolio
 * Version: 0.1
 * Author: ArnaudBan
 * Author URI: http://arnaudban.me
 *
 * Required The Custom Content Portfolio plugin by Justin Tadlock
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU
 * General Public License version 2, as published by the Free Software Foundation.  You may NOT assume
 * that you can use any other version of the GPL.
 *
 * @package   CustomContentPortfolioWidget
 * @version   0.1.0
 * @since     0.1.0
 * @author    ArnaudBan
 * @link      http://arnaudBan.me/portfolio/wordpress-plugins/custom-content-portfolio-widget
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * The Custom Content Portfolio Widget Class
 */
class Ab_Custom_Portfolio_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'ab_custom_portfolio_widget',
			__('Portfolio Widget', 'ab_custom_portfolio_widget'),
			array(
				'description' => __( 'Display the last items of your portfolio', 'ab_custom_portfolio_widget' )
				)
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );
		$nb_items = isset( $instance['nb_items'] ) ? $instance['nb_items'] : 3;

		echo $args['before_widget'];
		if ( ! empty( $title ) )
			echo $args['before_title'] . $title . $args['after_title'];

		$last_portfolio_items_args = array(
				'post_type' => 'portfolio_item',
				'posts_per_page' => $nb_items
			);
		$last_portfolio_items = new WP_Query( $last_portfolio_items_args );

		while ( $last_portfolio_items->have_posts() ) {
			$last_portfolio_items->the_post();
			?>
			<article <?php post_class() ?>>
				<a href="<?php the_permalink() ?>">
					<?php
					the_title();
					the_post_thumbnail();
					?>
				</a>
				<div class="entry-meta">
					<?php the_terms( get_the_ID(), 'portfolio', __('Portfolio : ', 'ab_custom_portfolio_widget'), $sep = ' - ', $after = '' ) ?>
				</div>
			</article>
			<?php
		}
		wp_reset_postdata();

		echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$title = isset( $instance[ 'title' ] ) ?$instance[ 'title' ] : __( 'Portfolio', 'ab_custom_portfolio_widget' );
		$nb_items = isset( $instance[ 'nb_items' ] ) ?$instance[ 'nb_items' ] : 3;

		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
		<label for="<?php echo $this->get_field_id( 'nb_items' ); ?>"><?php _e( 'Number of items to dislay' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'nb_items' ); ?>" name="<?php echo $this->get_field_name( 'nb_items' ); ?>" type="number" value="<?php echo esc_attr( $nb_items ); ?>" />
		</p>
		<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['nb_items'] = ( ! empty( $new_instance['nb_items'] ) ) ? absint( $new_instance['nb_items'] ) : 3;

		return $instance;
	}

}

function ab_register_cutom_portfolio_widget() {
    register_widget( 'Ab_Custom_Portfolio_Widget' );
}
add_action( 'widgets_init', 'ab_register_cutom_portfolio_widget' );