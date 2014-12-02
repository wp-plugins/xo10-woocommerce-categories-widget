<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

include_once( XO10_WC_CATS_PLUGIN_DIR . '../woocommerce/includes/abstracts/abstract-wc-widget.php' );

class XO10_WC_Categories_Widget extends WC_Widget {

  // widget-specific
  const WIDGET_SLUG = 'xo10_wc_cats_widget';
  const WIDGET_CSS_CLASS = 'woocommerce-product-categories';
  const WIDGET_DISPLAY_NAME = 'XO10 - WooCommerce Categories';
  
  // default image dimensions and constraints
  const IMG_W_MIN = 24;
  const IMG_W_MAX = 200;
  const IMG_W_DEFAULT = 42;
  const IMG_H_MIN = 24;
  const IMG_H_MAX = 200;
  const IMG_H_DEFAULT = 42; 
 
  
	public $cat_ancestors;
	public $current_cat;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->widget_cssclass    =  XO10_WC_Categories_Widget::WIDGET_CSS_CLASS; // css class
		$this->widget_description = __( 'A list or dropdown of product categories.', 'xo10-woocommerce-categories-widget' );
		$this->widget_id          = XO10_WC_Categories_Widget::WIDGET_SLUG; // option_id in database =  prefixed with "widget_"
		$this->widget_name        = __( XO10_WC_Categories_Widget::WIDGET_DISPLAY_NAME, 'xo10-woocommerce-categories-widget' );
		$this->settings           = array(
			'title'  => array(
				'type'  => 'text',
				'std'   => __( 'Product Categories', 'woocommerce' ),
				'label' => __( 'Title', 'woocommerce' )
			),
			'orderby' => array(
				'type'  => 'select',
				'std'   => 'name',
				'label' => __( 'Order by', 'woocommerce' ),
				'options' => array(
					'order' => __( 'Category Order', 'woocommerce' ),
					'name'  => __( 'Name', 'woocommerce' )
				)
			),
			'dropdown' => array(
				'type'  => 'checkbox',
				'std'   => 0,
				'label' => __( 'Show as dropdown', 'woocommerce' )
			),
			'count' => array(
				'type'  => 'checkbox',
				'std'   => 0,
				'label' => __( 'Show post counts', 'woocommerce' )
			),
			'hierarchical' => array(
				'type'  => 'checkbox',
				'std'   => 1,
				'label' => __( 'Show hierarchy', 'woocommerce' )
			),
			'show_children_only' => array(
				'type'  => 'checkbox',
				'std'   => 0,
				'label' => __( 'Only show children of the current category', 'woocommerce' )
			),
			'img_text_display' => array(
				'type'  => 'select',
				'std'   => 'iltr',
				'label' => __( 'Text/Image display', 'xo10-woocommerce-categories-widget' ),
				'options' => array(
					'image' => __( 'Image only', 'xo10-woocommerce-categories-widget' ),
					'text'  => __( 'Text only', 'xo10-woocommerce-categories-widget' ),
					'iltr' => __( 'Image left, Text right', 'xo10-woocommerce-categories-widget' ),
					'tlir' => __( 'Text left, Image right', 'xo10-woocommerce-categories-widget' )
				)
			),
			'img_width' => array(
				'type'  => 'number',
				'step'  => 10,
				'min'   => XO10_WC_Categories_Widget::IMG_W_MIN,
				'max'   => XO10_WC_Categories_Widget::IMG_W_MAX,
				'std'   => XO10_WC_Categories_Widget::IMG_W_DEFAULT,
				'label' => __( 'Image Width in px (Min = ' . XO10_WC_Categories_Widget::IMG_W_MIN . ', Max = ' . XO10_WC_Categories_Widget::IMG_W_MAX . ')', 'xo10-woocommerce-categories-widget' )
			),
			'img_height' => array(
				'type'  => 'number',
				'step'  => 1,
				'min'   => XO10_WC_Categories_Widget::IMG_H_MIN,
				'max'   => XO10_WC_Categories_Widget::IMG_H_MAX,
				'std'   => XO10_WC_Categories_Widget::IMG_H_DEFAULT,
				'label' => __( 'Image Height in px (Min = ' . XO10_WC_Categories_Widget::IMG_H_MIN . ', Max = ' . XO10_WC_Categories_Widget::IMG_H_MAX . ')', 'xo10-woocommerce-categories-widget' )
			),
			'count_pos' => array(
				'type'  => 'select',
				'std'   => 'extright',
				'label' => __( 'Post Counts display', 'xo10-woocommerce-categories-widget' ),
				'options' => array(
					'extright' => __( 'Extreme Right', 'xo10-woocommerce-categories-widget' ),
					'extleft'  => __( 'Extreme Left', 'xo10-woocommerce-categories-widget' ),
				)
			),
			'list_css_class'  => array(
				'type'  => 'text',
				'std'   => __( 'product-categories', 'xo10-woocommerce-categories-widget' ),
				'label' => __( 'List CSS class (for styling purposes)', 'xo10-woocommerce-categories-widget' )
			),
			'list_css_id'  => array(
				'type'  => 'text',
				'std'   => '',
				'label' => __( 'List CSS ID (for styling purposes)', 'xo10-woocommerce-categories-widget' )
			),
      // walter
			'force_css' => array(
				'type'  => 'select',
				'std'   => 'no',
				'label' => __( 'Force Display (Testing only. May not work.)', 'xo10-woocommerce-categories-widget' ),
				'options' => array(
					'no' => __( 'No', 'xo10-woocommerce-categories-widget' ),
					'vsp10'  => __( 'Vertical space between categories: 10px', 'xo10-woocommerce-categories-widget' ),
				)
			),
		);
    
    parent::__construct();
	}

  /**
   * @see WC_Widget::update()
   */
  public function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		if ( ! $this->settings ) {
			return $instance;
		}

		foreach ( $this->settings as $key => $setting ) {

			if ( isset( $new_instance[ $key ] ) ) {
				$instance[ $key ] = sanitize_text_field( $new_instance[ $key ] );
			} elseif ( 'checkbox' === $setting['type'] ) {
				$instance[ $key ] = 0;
			}
      
      if( 'list_css_class' === $key ) {
        if( empty( $new_instance[$key] ) ) {
          $instance[$key] = $setting['std'];
        }
      }

      if( 'number' === $setting['type'] ) {
        if( (int)$new_instance[$key] < (int)$setting['min'] || (int)$new_instance[$key] > (int)$setting['max'] ) {
          $instance[$key] = (int)$setting['std'];
        }
      }
      
		}

		$this->flush_widget_cache();

		return $instance;
	}
  
	/**
	 * @see WP_Widget
	 */
	public function widget( $args, $instance ) {
		extract( $args );

		global $wp_query, $post;

		$title         = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );
		$c             = ! empty( $instance['count'] );

		$h             = ! empty( $instance['hierarchical'] );
		$s             = ! empty( $instance['show_children_only'] );
		$d             = ! empty( $instance['dropdown'] );
		$o             = $instance['orderby'] ? $instance['orderby'] : 'order';
		$dropdown_args = array( 'hide_empty' => false );
		$list_args     = array( 'show_count' => $c, 'hierarchical' => $h, 'taxonomy' => 'product_cat', 'hide_empty' => false );
		$imgtxt        = $instance['img_text_display'] ? $instance['img_text_display'] : $this->settings['img_text_display']['std'];
		$imgw          = $instance['img_width'] ? $instance['img_width'] : $this->settings['img_width']['std'];
		$imgh          = $instance['img_height'] ? $instance['img_height'] : $this->settings['img_height']['std'];
		$cpos          = $instance['count_pos'] ? $instance['count_pos'] : $this->settings['count_pos']['std'];
		$css_class     = $instance['list_css_class'] ? $instance['list_css_class'] : $this->settings['list_css_class']['std'];
		$css_id        = $instance['list_css_id'] ? $instance['list_css_id'] : $this->settings['list_css_id']['std'];
		$forcecss      = $instance['force_css'] ? $instance['force_css'] : $this->settings['force_css']['std'];

    
		// Menu Order
		$list_args['menu_order'] = false;
		if ( $o == 'order' ) {
			$list_args['menu_order'] = 'asc';
		} else {
			$list_args['orderby']    = 'title';
		}

		// Setup Current Category
		$this->current_cat   = false;
		$this->cat_ancestors = array();

		if ( is_tax('product_cat') ) {

			$this->current_cat   = $wp_query->queried_object;
			$this->cat_ancestors = get_ancestors( $this->current_cat->term_id, 'product_cat' );

		} elseif ( is_singular('product') ) {

			$product_category = wc_get_product_terms( $post->ID, 'product_cat', array( 'orderby' => 'parent' ) );

			if ( $product_category ) {
				$this->current_cat   = end( $product_category );
				$this->cat_ancestors = get_ancestors( $this->current_cat->term_id, 'product_cat' );
			}

		}

		// Show Siblings and Children Only
		if ( $s && $this->current_cat ) {

			// Top level is needed
			$top_level = get_terms(
				'product_cat',
				array(
					'fields'       => 'ids',
					'parent'       => 0,
					'hierarchical' => true,
					'hide_empty'   => false
				)
			);

			// Direct children are wanted
			$direct_children = get_terms(
				'product_cat',
				array(
					'fields'       => 'ids',
					'parent'       => $this->current_cat->term_id,
					'hierarchical' => true,
					'hide_empty'   => false
				)
			);

			// Gather siblings of ancestors
			$siblings  = array();
			if ( $this->cat_ancestors ) {
				foreach ( $this->cat_ancestors as $ancestor ) {
					$ancestor_siblings = get_terms(
						'product_cat',
						array(
							'fields'       => 'ids',
							'parent'       => $ancestor,
							'hierarchical' => false,
							'hide_empty'   => false
						)
					);
					$siblings = array_merge( $siblings, $ancestor_siblings );
				}
			}

			if ( $h ) {
				$include = array_merge( $top_level, $this->cat_ancestors, $siblings, $direct_children, array( $this->current_cat->term_id ) );
			} else {
				$include = array_merge( $direct_children );
			}

			$dropdown_args['include'] = implode( ',', $include );
			$list_args['include']     = implode( ',', $include );

			if ( empty( $include ) ) {
				return;
			}

		} elseif ( $s ) {
			$dropdown_args['depth']        = 1;
			$dropdown_args['child_of']     = 0;
			$dropdown_args['hierarchical'] = 1;
			$list_args['depth']            = 1;
			$list_args['child_of']         = 0;
			$list_args['hierarchical']     = 1;
		}

		echo $before_widget;

		if ( $title ) {
			echo $before_title . $title . $after_title;
		}

		// Dropdown
		if ( $d ) {

			$dropdown_defaults = array(
				'show_counts'        => $c,
				'hierarchical'       => $h,
				'show_uncategorized' => 0,
				'orderby'            => $o,
				'selected'           => $this->current_cat ? $this->current_cat->slug : ''
			);
			$dropdown_args = wp_parse_args( $dropdown_args, $dropdown_defaults );

			// Stuck with this until a fix for http://core.trac.wordpress.org/ticket/13258
			wc_product_dropdown_categories( apply_filters( 'woocommerce_product_categories_widget_dropdown_args', $dropdown_args ) );

			wc_enqueue_js("
				jQuery('.dropdown_product_cat').change(function(){
					if(jQuery(this).val() != '') {
						location.href = '" . home_url() . "/?product_cat=' + jQuery(this).val();
					}
				});
			");

		// List
		} else {
			include_once( XO10_WC_CATS_PLUGIN_INCLUDES . 'walkers/class-cat-list-walker.php' );
      $listWalker = new XO10_WC_Cat_List_Walker();
      $listWalker->countpos = $cpos;
      $listWalker->imgtxt = $imgtxt;
      $listWalker->imgw = $imgw;
      $listWalker->imgh = $imgh;
      $listWalker->forcecss = $forcecss;

			$list_args['walker']                     = $listWalker; 
			$list_args['title_li']                   = '';
			$list_args['pad_counts']                 = 1;
			$list_args['show_option_none']           = __('No product categories exist.', 'woocommerce' );
			$list_args['current_category']           = ( $this->current_cat ) ? $this->current_cat->term_id : '';
			$list_args['current_category_ancestors'] = $this->cat_ancestors;

      $css_id = $css_id ? 'id="' . esc_attr( $css_id ) . '"' : '';
      $css_class = ' class="' . esc_attr( $css_class ) . '"';
      
			echo '<ul ' . $css_id . $css_class . '>';

			wp_list_categories( apply_filters( 'woocommerce_product_categories_widget_args', $list_args ) );

			echo '</ul>';
		}

		echo $after_widget;
	}
}
