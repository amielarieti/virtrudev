<?php
/**
 * Recursively get taxonomy and its children.
 *
 * @param array $args - arguments.
 * @param int   $parent - parent term id.
 * @return array
 */
function alm_get_taxonomy_hierarchy( $args = array(), $parent = 0 ) {

	if ( ! $args || ! isset( $args['taxonomy'] ) || empty( $args['taxonomy'] ) ) {
		return false;
	}

	// Add parent relationship.
	$args['parent'] = $parent;

	// Get the terms.
	$terms = get_terms( $args );

	// Prepare new array.
	$children = array();

	// Loop direct decendants of $parent, and get children.
	foreach ( $terms as $term ) {
		// Get direct decendants of "this" term.
		$term->children = alm_get_taxonomy_hierarchy( $args, $term->term_id );
		// Add term to new array.
		$children[] = $term;
	}

	return $children;
}

/**
 * Recursively get taxonomy children.
 *
 * @param array $items
 * @param object $term
 * @param array $exclude
 * @return array
 */
function alm_loop_term_children( $items, $term, $exclude ) {

	if ( ! $items || !$term ) {
		return false;
	}

	// Confirm child term exists.
	if ( isset( $term->children ) ) {
		// Loop child terms.
		foreach ( $term->children as $child ) {
			if ( ! in_array( $child->term_id, $exclude ) ) {
				$items[] = $child;
				$items   = alm_loop_term_children( $items, $child, $exclude );
			}
		}
	}

	return $items;
}
