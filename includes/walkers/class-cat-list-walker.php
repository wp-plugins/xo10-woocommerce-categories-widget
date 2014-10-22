<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

include_once( XO10_WC_CATS_PLUGIN_DIR . '../woocommerce/includes/walkers/class-product-cat-list-walker.php' );

class XO10_WC_Cat_List_Walker extends WC_Product_Cat_List_Walker {

  public $imgtxt = 'mixed';
  public $imgw = 42;
  public $imgh = 42;
  public $forcecss = 'no';
  
	/**
   * Adds thumbnails if required.
   * 
	 * @see WC_Product_Cat_List_Walker::start_el()
	 */
	public function start_el( &$output, $cat, $depth = 0, $args = array(), $current_object_id = 0 ) {
				
    $thumbnail_id = get_woocommerce_term_meta( $cat->term_id, 'thumbnail_id', true );
		$image = wp_get_attachment_image_src( $thumbnail_id, 'detail', false );
    
    $imgsrc = '';
    if( false === $image ) {
      $imgsrc = wc_placeholder_img_src(); 
    } else {
      $imgsrc = $image[0];
    }
    
    $img_constraint = 'width="' . absint( $this->imgw ) . '" height="' . absint( $this->imgh ) . '"';
    
    $li_styles = '';
    switch( $this->forcecss ) {
      case 'vsp10':
        $li_styles = ' style="margin-top: 10px;" ';
      break;

      default:
        break;
    }
    
    $output .= '<li ' . $li_styles . ' class="cat-item cat-item-' . $cat->term_id;

		if ( $args['current_category'] == $cat->term_id ) {
			$output .= ' current-cat';
		}

		if ( $args['has_children'] && $args['hierarchical'] ) {
			$output .= ' cat-parent';
		}

		if ( $args['current_category_ancestors'] && $args['current_category'] && in_array( $cat->term_id, $args['current_category_ancestors'] ) ) {
			$output .= ' current-cat-parent';
		}

    $output .=  '"><a href="' . get_term_link( (int) $cat->term_id, 'product_cat' ) . '">';
            
    if( $this->imgtxt == 'text' ) {
      $output .= '<span class="cat-name">' . __( $cat->name, 'woocommerce' ) . '</span></a>';
    } elseif ( $this->imgtxt == 'image' ) {
      $output .= '<img src="' . $imgsrc . '" title="' . __( $cat->name, 'woocommerce' ) . '" alt="' . __( $cat->name, 'woocommerce' ) . '" ' . $img_constraint . ' /></a>';
    } else {
      $output .= '<img src="' . $imgsrc . '" title="' . __( $cat->name, 'woocommerce' ) . '" alt="' . __( $cat->name, 'woocommerce' ) . '" ' . $img_constraint . ' /> <span class="cat-name">' .  __( $cat->name, 'woocommerce' ) . '</span></a>';
    }
    
    if ( $args['show_count'] ) {
			$output .= ' <span class="count">(' . $cat->count . ')</span>';
		}

	}

}
