<?php get_header(); ?>
<div class="page-banner">
    <div class="page-banner__bg-image" style="background-image: url(<?php echo get_theme_file_uri('/images/ocean.jpg') ?>);"></div>
    <div class="page-banner__content container container--narrow">
        <h1 class="page-banner__title">All Events</h1>
        <div class="page-banner__intro">
            <p>See what's going on in our world!</p>
        </div>
    </div>
</div>

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
        <div class="event-summary">
            <a class="event-summary__date t-center" href="<?php the_permalink(); ?>">
                <?php $event_date = new DateTime(get_field("event_date")); ?>
                <span class="event-summary__month"><?php echo $event_date->format("M"); ?></span>
                <span class="event-summary__day"><?php echo $event_date->format("d"); ?></span>
            </a>
            <div class="event-summary__content">
                <h5 class="event-summary__title headline headline--tiny"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
                <p><?php echo wp_trim_words(get_the_content(), 18); ?> <a href="<?php the_permalink();  ?>" class="nu gray">Learn more</a></p>
            </div>
        </div>
    <?php endwhile; ?>
    <?php
    echo paginate_links(array(
        "total" => $pastEvents->max_num_pages
    ));
    ?>
</div>
<?php get_footer(); ?>