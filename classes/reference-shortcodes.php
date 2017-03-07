<?php
/**
 * This class is executes the reference shortcode.
 *
 * (c) Joseph Gabito <joseph@useissuestabinstead.com>
 * (c) Jasper jardin <jasper@useissuestabinstead.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * PHP Version 5.4
 *
 * @category Reference\Reference\KnowledgebaseShortcodes
 * @package  Reference WordPress Knowledgebase
 * @author   Dunhakdis Software Creatives <emailnotdisplayed@domain.tld>
 * @author   Jasper J. <emailnotdisplayed@domain.tld>
 * @license  http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @version  GIT:github.com/codehaiku/reference-wordpress-knowledgebase
 * @link     github.com/codehaiku/reference-wordpress-knowledgebase  The Plugin Repository
 */

namespace DSC\Reference;

 if ( ! defined( 'ABSPATH' ) ) {
     return;
 }

/**
 * This class handles the frontend funtionality.
 *
 * @category Reference\Public\KnowledgebaseShortcodes
 * @package  Reference
 * @author   Dunhakdis Software Creatives <emailnotdisplayed@domain.tld>
 * @author   Jasper J. <emailnotdisplayed@domain.tld>
 * @license  http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @link     github.com/codehaiku/reference-wordpress-knowledgebase  The Plugin Repository
 * @since    1.0
 */
class KnowledgebaseShortcodes
{
    public function __construct()
    {

        add_shortcode( 'dsc_knb', array( $this, 'register_shortcode' ) );

        return $this;
    }

    public function register_shortcode( $atts )
    {

		$atts = shortcode_atts(
			array(
                'title' => '',
				'columns' => 3, // Select: 1,2,3,4,5: The number of columns(items) to show.
				'categories' => '', // Select: masonry, grid, wide, masonry-grid, masonry-wide, classic, modern, minimalist, masonry-classic, masonry-modern, masonry-minimalist.
				'tags' => '', // Any numbers: Default '0' to display number of page base on the user reading settings.
				'enable_search' => 'yes',  // Select: default(''), alphabetical, random.
				'posts_per_page' => 10,  // Select: default(''), alphabetical, random.
			), $atts, 'dsc_knb'
		);

		return $this->display( $atts );

	}

    public function display($atts)
    {

		ob_start();

		$template = REFERENCE_DIR_PATH . 'shortcodes/reference.php';

		if ( $theme_template = locate_template( array( 'knowledgebase/shortcodes/reference.php' ) ) ) {

			   $template = $theme_template;

		}

		include $template;

		return ob_get_clean();

    }

    public static function reference_shortcode_display_knowledgebase_category_list($categories)
    {
        global $post;

        $terms = '';
        $term = '';
        $image_id = '';
        $thumbnail = '';
        $thumbnail_letter = '';
        $displayed_thumbnail = '';
        $helper_class = new \DSC\Reference\Helper;

        $categories_list = array();

        $get_term_categories = get_terms( 'categories', array(
            'orderby'    => 'count',
            'hide_empty' => 0,
            'name' => $categories
        ) );

        $args = array( 'hide_empty' => 0 );

        $terms = get_terms( 'categories', $args );

        $child_categories = array();

        foreach ( $get_term_categories as $get_term_category ) {
            foreach ( $terms as $term ) {
                if($get_term_category->term_id === $term->parent) {
                    $child_categories[] = $term->name;
                }
            }
        }

        $get_child_term_categories = get_terms( 'categories', array(
            'hide_empty' => 0,
            'name' => $child_categories
        ) );

        // echo '<pre>';
        //     var_dump($get_child_term_categories);
        // echo '</pre>';

        foreach ( $get_child_term_categories as $term ) {


            if( $term->parent) {
                $image_id = get_term_meta( $term->term_id, 'categories-image-id', true );
                $thumbnail = wp_get_attachment_image ( $image_id, 'reference-knowledgebase-thumbnail' );
                $thumbnail_letter = $helper_class->reference_thumbnail_letter($term->name);
                $displayed_thumbnail = $thumbnail;

                if ( empty($thumbnail)) {
                    $displayed_thumbnail = $thumbnail_letter;
                }

    			$categories_list[] = sprintf(
    				'<article class="hentry categoriy-listing %1$s"><div class="reference-cat-image">%2$s</div><a href="%3$s">%4$s</a><div class="description">%5$s</div></article>',
                    esc_attr( strtolower( $term->name ) ),
                    $displayed_thumbnail,
    				// esc_url( get_term_link( $term->slug, $term->slug->taxonomy ) ),
                    'a',
    				esc_html( $term->name . $term->parent ),
    				esc_html( $helper_class->reference_excerpt_description($term->description, 55) )
    			);

            }
        }

		return implode( '', $categories_list );
	}
}
