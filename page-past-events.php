<?php get_header(); ?>
<?php pageBanner(array(
    "title" => "Past Events",
    "subtitle" => "A recap of our past events"
)); ?>

<div class="container container--narrow page-section">
    <?php
    $today = date("Ymd");
    $pastEvents = new WP_Query(array(
        "paged" => get_query_var("paged", 1), // which page
        // "posts_per_page" => 1,
        "post_type" => "event",
        "meta_key" => "event_date", // name of the custom field
        "orderby" => "meta_value_num", // post_date is the default, rand for random, meta_value alongside meta_key to use custom fields
        // meta_value for strings
        "order" => "DESC", // default is DESC
        "meta_query" => array(
            array( // bring only upcoming events
                "key" => "event_date",
                "compare" => "<",
                "value" => $today,
                "type" => "numeric"
            )
        )
    ));
    ?>
    <?php while ($pastEvents->have_posts()) : ?>
        <?php $pastEvents->the_post(); ?>
        <?php get_template_part("template-parts/content", "event"); ?>
    <?php endwhile; ?>
    <?php
    echo paginate_links(array(
        "total" => $pastEvents->max_num_pages
    ));
    ?>
</div>
<?php get_footer(); ?>