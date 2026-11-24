<?php
/**
 * Helper functions used by the templates.
 *
 * @package Vara_Alert_Hub
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ==========================================================================
   Settings
   ========================================================================== */

/**
 * Default value of every Customizer setting.
 *
 * @return array
 */
function vara_alert_hub_defaults() {
	return array(
		'primary'             => '#0d6efd',
		'sidebar'             => 'right',
		'show_tabs'           => true,
		'show_featured'       => true,
		'show_latest'         => true,
		'featured_cat'        => 0,
		'featured_count'      => 3,
		'featured_title'      => __( 'Featured', 'vara-alert-hub' ),
		'latest_title'        => __( 'Latest updates', 'vara-alert-hub' ),
		'images'              => 'off',
		'show_author'         => true,
		'new_days'            => 3,
		'new_label'           => __( 'NEW', 'vara-alert-hub' ),
		'ticker_text'         => '',
		'ticker_label'        => __( 'Updates', 'vara-alert-hub' ),
		'footer_about_title'  => __( 'About', 'vara-alert-hub' ),
		'footer_about'        => '',
		'footer_links_title'  => __( 'Quick links', 'vara-alert-hub' ),
		'footer_latest_title' => __( 'Latest updates', 'vara-alert-hub' ),
		'copyright'           => '',
		'show_credit'         => true,
		'e404_title'          => __( "This update isn't on the board", 'vara-alert-hub' ),
		'e404_text'           => __( "The page may have moved, expired, or the link has a typo. Search for it, or head back to what's new.", 'vara-alert-hub' ),
		'e404_board'          => true,
	);
}

/**
 * Read a theme setting.
 *
 * @param string $key Setting key without the prefix.
 * @return mixed
 */
function vara_alert_hub_get( $key ) {
	$defaults = vara_alert_hub_defaults();
	$default  = isset( $defaults[ $key ] ) ? $defaults[ $key ] : '';
	return get_theme_mod( 'vara_alert_hub_' . $key, $default );
}

/**
 * Make a hex colour darker.
 *
 * @param string $hex    Hex colour.
 * @param float  $amount 0 to 1.
 * @return string
 */
function vara_alert_hub_darken( $hex, $amount ) {
	$hex = ltrim( $hex, '#' );
	if ( 3 === strlen( $hex ) ) {
		$hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
	}
	$rgb = array( hexdec( substr( $hex, 0, 2 ) ), hexdec( substr( $hex, 2, 2 ) ), hexdec( substr( $hex, 4, 2 ) ) );
	foreach ( $rgb as $i => $value ) {
		$rgb[ $i ] = max( 0, (int) round( $value * ( 1 - $amount ) ) );
	}
	return sprintf( '#%02x%02x%02x', $rgb[0], $rgb[1], $rgb[2] );
}

/**
 * CSS for the primary colour chosen in the Customizer.
 *
 * @return string
 */
function vara_alert_hub_custom_css() {
	$primary = sanitize_hex_color( vara_alert_hub_get( 'primary' ) );
	if ( ! $primary || '#0d6efd' === strtolower( $primary ) ) {
		return '';
	}
	return ':root{--ah-primary:' . $primary . ';--ah-primary-dark:' . vara_alert_hub_darken( $primary, 0.18 ) . ';}';
}

/* ==========================================================================
   Query values (read only, whitelisted)
   ========================================================================== */

/**
 * Sort choice from the URL.
 *
 * @return string updated, published or an empty string.
 */
function vara_alert_hub_sort_param() {
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$sort = isset( $_GET['ah_sort'] ) ? sanitize_key( wp_unslash( $_GET['ah_sort'] ) ) : '';
	return in_array( $sort, array( 'updated', 'published' ), true ) ? $sort : '';
}

/**
 * View choice from the URL.
 *
 * @return string list or grid.
 */
function vara_alert_hub_view_param() {
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$view = isset( $_GET['ah_view'] ) ? sanitize_key( wp_unslash( $_GET['ah_view'] ) ) : '';
	return ( 'grid' === $view ) ? 'grid' : 'list';
}

/**
 * Page number for lists built from our own queries.
 *
 * @return int
 */
function vara_alert_hub_page_param() {
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended
	return isset( $_GET['ah_page'] ) ? max( 1, absint( $_GET['ah_page'] ) ) : 1;
}

/**
 * Current address as a relative URL without paging values.
 *
 * @param array $strip Query keys to remove.
 * @return string
 */
function vara_alert_hub_clean_url( $strip = array( 'ah_page', 'paged' ) ) {
	$uri = isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '/';
	$uri = remove_query_arg( $strip, $uri );
	return preg_replace( '#/page/\d+/?#', '/', $uri );
}

/* ==========================================================================
   Layout checks
   ========================================================================== */

/**
 * Page templates that have no sidebar.
 *
 * @return bool
 */
function vara_alert_hub_is_full_width_template() {
	if ( ! is_page() ) {
		return false;
	}
	return in_array(
		get_page_template_slug(),
		array(
			'page-templates/full-width.php',
			'page-templates/full-width-title.php',
			'page-templates/homepage.php',
			'page-templates/archive.php',
		),
		true
	);
}

/**
 * Whether the sidebar is shown on this screen.
 *
 * @return bool
 */
function vara_alert_hub_has_sidebar() {
	if ( 'off' === vara_alert_hub_get( 'sidebar' ) || ! is_active_sidebar( 'sidebar-1' ) ) {
		return false;
	}
	if ( is_singular( 'post' ) ) {
		return true;
	}
	return is_page() && ! vara_alert_hub_is_full_width_template();
}

/* ==========================================================================
   Categories, colours and post details
   ========================================================================== */

/**
 * Six category colours: bar, soft background, dark text.
 *
 * @return array
 */
function vara_alert_hub_palette() {
	return array(
		array( '#0d6efd', '#e6f1fb', '#0a58ca' ),
		array( '#3b6d11', '#eaf3de', '#27500a' ),
		array( '#ba7517', '#faeeda', '#633806' ),
		array( '#993556', '#fbeaf0', '#72243e' ),
		array( '#0f6e56', '#e1f5ee', '#085041' ),
		array( '#534ab7', '#eeedfe', '#3c3489' ),
	);
}

/**
 * Inline CSS variables for a category colour.
 *
 * @param int $term_id Category ID.
 * @return string
 */
function vara_alert_hub_cat_style( $term_id ) {
	$palette = vara_alert_hub_palette();
	$colour  = $palette[ absint( $term_id ) % count( $palette ) ];
	return '--ah-cat:' . $colour[0] . ';--ah-cat-bg:' . $colour[1] . ';--ah-cat-text:' . $colour[2] . ';';
}

/**
 * First category of a post.
 *
 * @param int $post_id Post ID.
 * @return WP_Term|null
 */
function vara_alert_hub_primary_category( $post_id = 0 ) {
	$cats = get_the_category( $post_id ? $post_id : get_the_ID() );
	return ( $cats && ! is_wp_error( $cats ) ) ? $cats[0] : null;
}

/**
 * Inline CSS variables for the colour of a post.
 *
 * @param int $post_id Post ID.
 * @return string
 */
function vara_alert_hub_item_style( $post_id = 0 ) {
	$cat = vara_alert_hub_primary_category( $post_id );
	return $cat ? vara_alert_hub_cat_style( $cat->term_id ) : '';
}

/**
 * First letter of a text, for placeholders and avatars.
 *
 * @param string $text Text.
 * @return string
 */
function vara_alert_hub_initial( $text ) {
	$text = trim( wp_strip_all_tags( (string) $text ) );
	if ( '' === $text ) {
		return '?';
	}
	return function_exists( 'mb_substr' ) ? mb_strtoupper( mb_substr( $text, 0, 1 ) ) : strtoupper( substr( $text, 0, 1 ) );
}

/**
 * Was the post published or updated recently?
 *
 * @param int $post_id Post ID.
 * @return bool
 */
function vara_alert_hub_is_new( $post_id = 0 ) {
	$days = max( 1, absint( vara_alert_hub_get( 'new_days' ) ) );
	$time = (int) get_post_modified_time( 'U', true, $post_id ? $post_id : null );
	return $time && ( time() - $time ) < $days * DAY_IN_SECONDS;
}

/**
 * NEW badge markup (empty when the post is not new).
 *
 * @param int $post_id Post ID.
 * @return string
 */
function vara_alert_hub_new_badge( $post_id = 0 ) {
	if ( ! vara_alert_hub_is_new( $post_id ) ) {
		return '';
	}
	return '<span class="ah-new">' . esc_html( vara_alert_hub_get( 'new_label' ) ) . '</span>';
}

/**
 * Short update note written in the post editor.
 *
 * @param int $post_id Post ID.
 * @return string
 */
function vara_alert_hub_update_note( $post_id = 0 ) {
	return (string) get_post_meta( $post_id ? $post_id : get_the_ID(), '_vara_alert_hub_update_note', true );
}

/**
 * "Last date" chip markup (empty when no date is set).
 *
 * @param int $post_id Post ID.
 * @return string
 */
function vara_alert_hub_last_date_chip( $post_id = 0 ) {
	$date = (string) get_post_meta( $post_id ? $post_id : get_the_ID(), '_vara_alert_hub_last_date', true );
	if ( ! preg_match( '/^\d{4}-\d{2}-\d{2}$/', $date ) ) {
		return '';
	}
	$dt = date_create_immutable( $date, wp_timezone() );
	if ( ! $dt ) {
		return '';
	}
	$past = $date < wp_date( 'Y-m-d' );
	/* translators: %s: date such as 30 Nov */
	$text = sprintf( __( 'Last date %s', 'vara-alert-hub' ), wp_date( 'j M', $dt->getTimestamp() ) );
	return '<span class="ah-chip-date' . ( $past ? ' is-past' : '' ) . '">' . esc_html( $text ) . '</span>';
}

/**
 * Short excerpt for cards and rows.
 *
 * @param int $words Word count.
 * @return string
 */
function vara_alert_hub_excerpt( $words = 18 ) {
	return wp_trim_words( get_the_excerpt(), $words, '&hellip;' );
}

/**
 * Plain text with search words wrapped in <mark>. The text is escaped first.
 *
 * @param string $text Text.
 * @return string
 */
function vara_alert_hub_highlight( $text ) {
	$plain = html_entity_decode( wp_strip_all_tags( (string) $text ), ENT_QUOTES, get_bloginfo( 'charset' ) );
	$safe  = esc_html( $plain );
	if ( ! is_search() ) {
		return $safe;
	}
	$words = preg_split( '/\s+/', trim( get_search_query( false ) ), -1, PREG_SPLIT_NO_EMPTY );
	$words = array_filter(
		$words,
		function ( $word ) {
			return strlen( $word ) > 1 && ! in_array( strtolower( $word ), array( 'amp', 'lt', 'gt', 'quot', '039' ), true );
		}
	);
	if ( ! $words ) {
		return $safe;
	}
	$parts = array();
	foreach ( $words as $word ) {
		$parts[] = preg_quote( esc_html( $word ), '/' );
	}
	$result = preg_replace( '/(' . implode( '|', $parts ) . ')/iu', '<mark>$1</mark>', $safe );
	return null === $result ? $safe : $result;
}

/* ==========================================================================
   Icons
   ========================================================================== */

/**
 * Inline icon. The markup is fixed text written in this theme.
 *
 * @param string $name Icon name.
 * @return string
 */
function vara_alert_hub_icon( $name ) {
	$paths = array(
		'search'   => '<circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/>',
		'menu'     => '<path d="M4 6h16M4 12h16M4 18h16"/>',
		'close'    => '<path d="M6 6l12 12M18 6L6 18"/>',
		'chevron'  => '<path d="m6 9 6 6 6-6"/>',
		'calendar' => '<rect x="4" y="5" width="16" height="15" rx="2"/><path d="M8 3v4M16 3v4M4 10h16"/>',
		'refresh'  => '<path d="M20 11a8 8 0 1 0-2.3 5.7"/><path d="M20 5v6h-6"/>',
		'user'     => '<circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 4-6 8-6s8 2 8 6"/>',
	);
	if ( ! isset( $paths[ $name ] ) ) {
		return '';
	}
	return '<svg class="ah-icon-' . esc_attr( $name ) . '" viewBox="0 0 24 24" aria-hidden="true" focusable="false">' . $paths[ $name ] . '</svg>';
}

/* ==========================================================================
   Lists: tabs, chips, sorting, pagination
   ========================================================================== */

/**
 * Category tabs shown above the home page lists.
 */
function vara_alert_hub_tabs() {
	$cats = get_categories( array(
		'orderby'    => 'count',
		'order'      => 'DESC',
		'number'     => 6,
		'hide_empty' => true,
	) );
	if ( count( $cats ) < 2 ) {
		return;
	}
	echo '<ul class="ah-tabs">';
	echo '<li><a class="ah-chip is-active" href="' . esc_url( home_url( '/' ) ) . '" aria-current="page">' . esc_html__( 'All', 'vara-alert-hub' ) . '</a></li>';
	foreach ( $cats as $cat ) {
		echo '<li><a class="ah-chip" href="' . esc_url( get_category_link( $cat->term_id ) ) . '">' . esc_html( $cat->name ) . '</a></li>';
	}
	echo '</ul>';
}

/**
 * Categories found in the current archive or search results, with counts.
 *
 * @return array total and items (term and count).
 */
function vara_alert_hub_result_categories() {
	global $wp_query;

	$args                   = $wp_query->query_vars;
	$args['cat']            = '';
	$args['category_name']  = '';
	$args['category__in']   = array();
	$args['category__and']  = array();
	$args['posts_per_page'] = 200;
	$args['paged']          = 1;
	$args['offset']         = 0;
	$args['fields']         = 'ids';
	$args['no_found_rows']  = true;

	$query = new WP_Query( $args );
	$ids   = $query->posts;
	if ( ! $ids ) {
		return array(
			'total' => 0,
			'items' => array(),
		);
	}

	$rows   = wp_get_object_terms( $ids, 'category', array( 'fields' => 'all_with_object_id' ) );
	$counts = array();
	$terms  = array();
	if ( ! is_wp_error( $rows ) ) {
		foreach ( $rows as $row ) {
			$counts[ $row->term_id ] = isset( $counts[ $row->term_id ] ) ? $counts[ $row->term_id ] + 1 : 1;
			$terms[ $row->term_id ]  = $row;
		}
	}
	arsort( $counts );

	$items = array();
	foreach ( $counts as $term_id => $count ) {
		$items[] = array(
			'term'  => $terms[ $term_id ],
			'count' => $count,
		);
	}
	return array(
		'total' => count( $ids ),
		'items' => $items,
	);
}

/**
 * Chips that filter the current archive or search by category.
 */
function vara_alert_hub_category_chips() {
	$found = vara_alert_hub_result_categories();
	if ( count( $found['items'] ) < 2 ) {
		return;
	}
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$active = isset( $_GET['cat'] ) ? absint( $_GET['cat'] ) : 0;
	$base   = vara_alert_hub_clean_url( array( 'ah_page', 'paged', 'cat' ) );

	echo '<ul class="ah-chips ah-chips--scroll">';
	printf(
		'<li><a class="ah-chip%1$s" href="%2$s">%3$s <span class="ah-chip-count">%4$s</span></a></li>',
		$active ? '' : ' is-active',
		esc_url( $base ),
		esc_html__( 'All', 'vara-alert-hub' ),
		esc_html( number_format_i18n( $found['total'] ) )
	);
	foreach ( $found['items'] as $item ) {
		printf(
			'<li><a class="ah-chip%1$s" href="%2$s">%3$s <span class="ah-chip-count">%4$s</span></a></li>',
			( $active === (int) $item['term']->term_id ) ? ' is-active' : '',
			esc_url( add_query_arg( 'cat', $item['term']->term_id, $base ) ),
			esc_html( $item['term']->name ),
			esc_html( number_format_i18n( $item['count'] ) )
		);
	}
	echo '</ul>';
}

/**
 * Date of the most recently updated item in the current archive.
 *
 * @return string
 */
function vara_alert_hub_last_updated_label() {
	global $wp_query;

	$args                   = $wp_query->query_vars;
	$args['posts_per_page'] = 1;
	$args['paged']          = 1;
	$args['offset']         = 0;
	$args['orderby']        = 'modified';
	$args['order']          = 'DESC';
	$args['fields']         = 'ids';
	$args['no_found_rows']  = true;

	$query = new WP_Query( $args );
	if ( empty( $query->posts ) ) {
		return '';
	}
	return get_the_modified_date( 'j M Y', $query->posts[0] );
}

/**
 * Two links that choose the sort order.
 *
 * @param array  $options Value => label.
 * @param string $default Value used when the URL has none.
 */
function vara_alert_hub_sort_links( $options, $default ) {
	$current = vara_alert_hub_sort_param();
	if ( '' === $current ) {
		$current = $default;
	}
	$base = vara_alert_hub_clean_url( array( 'ah_page', 'paged', 'ah_sort' ) );

	echo '<div class="ah-seg" role="group" aria-label="' . esc_attr__( 'Sort by', 'vara-alert-hub' ) . '">';
	foreach ( $options as $value => $label ) {
		printf(
			'<a href="%1$s"%2$s>%3$s</a>',
			esc_url( add_query_arg( 'ah_sort', $value, $base ) ),
			( $current === $value ) ? ' class="is-active" aria-current="true"' : '',
			esc_html( $label )
		);
	}
	echo '</div>';
}

/**
 * Two links that switch between list and grid.
 */
function vara_alert_hub_view_links() {
	$current = vara_alert_hub_view_param();
	$base    = vara_alert_hub_clean_url( array( 'ah_page', 'paged', 'ah_view' ) );
	$options = array(
		'list' => __( 'List', 'vara-alert-hub' ),
		'grid' => __( 'Grid', 'vara-alert-hub' ),
	);

	echo '<div class="ah-seg ah-view-switch" role="group" aria-label="' . esc_attr__( 'View', 'vara-alert-hub' ) . '">';
	foreach ( $options as $value => $label ) {
		printf(
			'<a href="%1$s"%2$s>%3$s</a>',
			esc_url( add_query_arg( 'ah_view', $value, $base ) ),
			( $current === $value ) ? ' class="is-active" aria-current="true"' : '',
			esc_html( $label )
		);
	}
	echo '</div>';
}

/**
 * Pagination: Newer, page numbers, Older. On phones only "Page 1 of 3" shows.
 *
 * @param int  $current Current page.
 * @param int  $total   Number of pages.
 * @param bool $custom  True for lists built from our own query (uses ah_page).
 */
function vara_alert_hub_pagination( $current, $total, $custom = false ) {
	$current = max( 1, (int) $current );
	$total   = (int) $total;
	if ( $total < 2 ) {
		return;
	}

	$url = function ( $number ) use ( $custom ) {
		if ( $custom ) {
			return add_query_arg( 'ah_page', $number, vara_alert_hub_clean_url() );
		}
		return get_pagenum_link( $number );
	};

	$pages = array_unique( array_filter(
		array( 1, $current - 1, $current, $current + 1, $total ),
		function ( $number ) use ( $total ) {
			return $number >= 1 && $number <= $total;
		}
	) );
	sort( $pages );

	echo '<nav class="ah-pagination" aria-label="' . esc_attr__( 'Pages', 'vara-alert-hub' ) . '">';

	if ( $current > 1 ) {
		echo '<a class="ah-page" rel="prev" href="' . esc_url( $url( $current - 1 ) ) . '">' . esc_html__( 'Newer', 'vara-alert-hub' ) . '</a>';
	}

	echo '<span class="ah-pagination__nums">';
	$previous = 0;
	foreach ( $pages as $number ) {
		if ( $previous && $number - $previous > 1 ) {
			echo '<span class="ah-page is-gap">&hellip;</span>';
		}
		if ( $number === $current ) {
			echo '<span class="ah-page is-current" aria-current="page">' . esc_html( number_format_i18n( $number ) ) . '</span>';
		} else {
			echo '<a class="ah-page" href="' . esc_url( $url( $number ) ) . '">' . esc_html( number_format_i18n( $number ) ) . '</a>';
		}
		$previous = $number;
	}
	echo '</span>';

	/* translators: 1: current page, 2: total pages */
	echo '<span class="ah-pagination__status">' . esc_html( sprintf( __( 'Page %1$s of %2$s', 'vara-alert-hub' ), number_format_i18n( $current ), number_format_i18n( $total ) ) ) . '</span>';

	if ( $current < $total ) {
		echo '<a class="ah-page" rel="next" href="' . esc_url( $url( $current + 1 ) ) . '">' . esc_html__( 'Older', 'vara-alert-hub' ) . '</a>';
	}

	echo '</nav>';
}

/* ==========================================================================
   Footer
   ========================================================================== */

/**
 * Default content of a footer column, used while its widget area is empty.
 *
 * @param int $column Column number, 1 to 3.
 */
function vara_alert_hub_footer_column( $column ) {
	$column = (int) $column;

	if ( 1 === $column ) {
		$text = trim( (string) vara_alert_hub_get( 'footer_about' ) );
		if ( '' === $text ) {
			$text = get_bloginfo( 'description', 'display' );
		}
		if ( '' === trim( $text ) ) {
			$text = get_bloginfo( 'name' );
		}
		echo '<section class="widget"><h2 class="widget-title">' . esc_html( vara_alert_hub_get( 'footer_about_title' ) ) . '</h2>';
		echo '<p>' . esc_html( $text ) . '</p></section>';
		return;
	}

	if ( 2 === $column ) {
		echo '<section class="widget"><h2 class="widget-title">' . esc_html( vara_alert_hub_get( 'footer_links_title' ) ) . '</h2>';
		if ( has_nav_menu( 'primary' ) ) {
			wp_nav_menu( array(
				'theme_location' => 'primary',
				'menu_id'        => 'footer-menu',
				'container'      => false,
				'depth'          => 1,
				'fallback_cb'    => false,
			) );
		} else {
			echo '<ul>';
			wp_list_pages( array(
				'title_li' => '',
				'depth'    => 1,
				'number'   => 6,
			) );
			echo '</ul>';
		}
		echo '</section>';
		return;
	}

	$posts = get_posts( array(
		'numberposts'      => 5,
		'orderby'          => 'modified',
		'order'            => 'DESC',
		'suppress_filters' => true,
	) );
	echo '<section class="widget"><h2 class="widget-title">' . esc_html( vara_alert_hub_get( 'footer_latest_title' ) ) . '</h2>';
	if ( $posts ) {
		echo '<ul>';
		foreach ( $posts as $item ) {
			echo '<li><a href="' . esc_url( get_permalink( $item ) ) . '">' . esc_html( get_the_title( $item ) ) . '</a></li>';
		}
		echo '</ul>';
	}
	echo '</section>';
}

/**
 * Footer copyright line.
 *
 * @return string
 */
function vara_alert_hub_copyright_text() {
	$custom = trim( (string) vara_alert_hub_get( 'copyright' ) );
	if ( '' !== $custom ) {
		return $custom;
	}
	/* translators: 1: year, 2: site name */
	return sprintf( __( '&copy; %1$s %2$s. All rights reserved.', 'vara-alert-hub' ), gmdate( 'Y' ), get_bloginfo( 'name' ) );
}

/**
 * The single theme credit link (escaped). Empty when switched off.
 *
 * @return string
 */
function vara_alert_hub_credit_html() {
	if ( ! vara_alert_hub_get( 'show_credit' ) ) {
		return '';
	}
	$theme = wp_get_theme( get_template() );
	$name  = $theme->get( 'Name' );
	$uri   = $theme->get( 'AuthorURI' );
	$link  = $uri ? '<a href="' . esc_url( $uri ) . '">' . esc_html( $name ) . '</a>' : esc_html( $name );
	/* translators: %s: theme name, linked to the theme author's website */
	return sprintf( esc_html__( 'Theme: %s', 'vara-alert-hub' ), $link );
}

/**
 * Menu shown when no menu is assigned: the site's pages.
 */
function vara_alert_hub_menu_fallback() {
	echo '<ul id="primary-menu" class="menu">';
	echo '<li class="menu-item"><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Home', 'vara-alert-hub' ) . '</a></li>';
	wp_list_pages( array(
		'title_li' => '',
		'depth'    => 1,
		'number'   => 8,
	) );
	echo '</ul>';
}

/**
 * Upper case and cut a text to fit a board column.
 *
 * @param string $text  Text.
 * @param int    $width Number of characters.
 * @return string
 */
function vara_alert_hub_board_text( $text, $width ) {
	$text = wp_strip_all_tags( html_entity_decode( (string) $text, ENT_QUOTES, get_bloginfo( 'charset' ) ) );
	if ( function_exists( 'mb_strtoupper' ) && function_exists( 'mb_substr' ) ) {
		return mb_substr( mb_strtoupper( $text ), 0, $width );
	}
	return substr( strtoupper( $text ), 0, $width );
}
