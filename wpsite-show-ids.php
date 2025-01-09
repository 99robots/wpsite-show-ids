<?php
/**
 * Plugin Name: Show IDs
 * Plugin URI: https://draftpress.com/products
 * Description: Show IDs on all post, page, and taxonomy pages.
 * Version: 1.1.11
 * Author: DraftPress
 * Author URI: https://draftpress.com/
 * License: GPL2
 * php version: 7.0
 *
 * @category Plugin
 * @package  ShowIDs
 * @author   DraftPress <support@draftpress.com>
 * @license  GNU General Public License 2
 *           (https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html)
 * @link     https://draftpress.com/products
 */

/**
 * Class WPSite_Show_IDs
 *
 * @category Class
 * @package  ShowIDs
 * @author   DraftPress <support@draftpress.com>
 * @license  GNU General Public License 2
 *          (https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html)
 * @link     https://draftpress.com/products
 */
class WPSite_Show_IDs {


	/**
	 * Class constructor. Initializes actions and filters.
	 */
	public function __construct() {

		add_action( 'admin_init', array( $this, 'custom_objects' ) );
		add_action( 'admin_head', array( $this, 'add_css' ) );

		// For Post Management.
		add_filter( 'manage_posts_columns', array( $this, 'add_column' ) );
		add_action( 'manage_posts_custom_column', array( $this, 'add_value' ), 10, 2 );

		// For Page Management.
		add_filter( 'manage_pages_columns', array( $this, 'add_column' ) );
		add_action( 'manage_pages_custom_column', array( $this, 'add_value' ), 10, 2 );

		// For Media Management.
		add_filter( 'manage_media_columns', array( $this, 'add_column' ) );
		add_action( 'manage_media_custom_column', array( $this, 'add_value' ), 10, 2 );

		// For Link Management.
		add_filter( 'manage_link-manager_columns', array( $this, 'add_column' ) );
		add_action( 'manage_link_custom_column', array( $this, 'add_value' ), 10, 2 );

		// For Category Management.
		add_action(
			'manage_edit-link-categories_columns',
			array( $this, 'add_column' )
		);
		add_filter(
			'manage_link_categories_custom_column',
			array( $this, 'add_return_value' ),
			10,
			3
		);

		// For User Management.
		add_action( 'manage_users_columns', array( $this, 'add_column' ) );
		add_filter(
			'manage_users_custom_column',
			array( $this, 'add_return_value' ),
			10,
			3
		);

		// For Comment Management.
		add_action(
			'manage_edit-comments_columns',
			array( $this, 'add_column' )
		);
		add_action(
			'manage_comments_custom_column',
			array( $this, 'add_value' ),
			10,
			2
		);
	}

	/**
	 * Hooks to the 'admin_init'
	 *
	 * @return void
	 */
	public function custom_objects() {

		// For Custom Taxonomies.
		$taxonomies = get_taxonomies( array( 'public' => true ), 'names' );
		foreach ( $taxonomies as $custom_taxonomy ) {
			if ( isset( $custom_taxonomy ) ) {
				add_action(
					'manage_edit-' . $custom_taxonomy . '_columns',
					array( $this, 'add_column' )
				);
				add_filter(
					'manage_' . $custom_taxonomy . '_custom_column',
					array( $this, 'add_return_value' ),
					10,
					3
				);
			}
		}

		// For Custom Post Types.
		$post_types = get_post_types( array( 'public' => true ), 'names' );
		foreach ( $post_types as $post_type ) {
			if ( isset( $post_type ) ) {
				add_action(
					'manage_edit-' . $post_type . '_columns',
					array( $this, 'add_column' )
				);
				add_filter(
					'manage_' . $post_type . '_custom_column',
					array( $this, 'add_return_value' ),
					10,
					3
				);
			}
		}
	}

	/**
	 * Hooks to 'admin_head'
	 *
	 * @return void
	 */
	public function add_css() {
		?>
		<style type="text/css">
			#wpsite-show-ids {
				width: 50px;
			}
		</style>
		<?php
	}

	/**
	 * Adds a column to the edit screen.
	 *
	 * @param mixed $cols The existing columns.
	 *
	 * @return string
	 */
	public function add_column( $cols ) {
		$cols['wpsite-show-ids'] = 'ID';
		return $cols;
	}


	/**
	 * Adds id value to a column.
	 *
	 * @param mixed $column_name The name of the column.
	 * @param mixed $id The ID value to add.
	 *
	 * @return void
	 */
	public function add_value( $column_name, $id ) {
		if ( 'wpsite-show-ids' === $column_name ) {
			echo esc_html( $id );
		}
	}


	/**
	 * Adds an ID value as a return value for a column.
	 *
	 * @param mixed $value       The current column value.
	 * @param mixed $column_name The name of the column.
	 * @param mixed $id          The ID value.
	 *
	 * @return string
	 */
	public function add_return_value( $value, $column_name, $id ) {
		if ( 'wpsite-show-ids' === $column_name ) {
			$value = $id;
		}

		return $value;
	}
}


/**
 * Start
 *
 * @var WPSite_Show_IDs
 */
new WPSite_Show_IDs();

?>
