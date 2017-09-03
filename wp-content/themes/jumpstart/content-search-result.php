<?php
/**
 * The default template for displaying content in search results
 */
$meta = array('include' => array('time', 'comments'), 'time' => 'ago');
?>
<div class="search-result">

	<span class="search-result-icon bg-primary">
		<i class="fa fa-<?php echo themeblvd_get_format_icon( get_post_format(), true ); ?>"></i>
	</span>

	<h3 class="entry-title"><?php themeblvd_the_title(); ?></h3>

	<div class="meta-wrapper">
		<?php echo themeblvd_search_meta(); ?>
	</div><!-- .meta-wrapper (end) -->

	<div class="entry-content">
		<?php the_excerpt(); ?>
	</div>

</div><!-- .search-result (end) -->