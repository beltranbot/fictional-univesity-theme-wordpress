<?php get_header(); ?>

<?php while (have_posts()) : ?>
    <?php the_post(); ?>
    <?php pageBanner(); ?>

    <div class="container container--narrow page-section">
        <div class="metabox metabox--position-up metabox--with-home-link">
            <p>
                <a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link("program"); ?>">
                    <i class="fa fa-home" aria-hidden="true"></i> All Programs
                </a>
                <span class="metabox__main"><?php the_title(); ?></span>
            </p>
        </div>
        <div class="generic-content">
            <?php the_content(); ?>
        </div>

        <?php
        $relatedProfessors = new WP_Query(array(
            "posts_per_page" => -1, // -1 get all posts that meet the condition
            "post_type" => "professor",
            "orderby" => "title", // post_date is the default, rand for random, meta_value alongside meta_key to use custom fields
            // meta_value for strings
            "order" => "ASC", // default is DESC
            "meta_query" => array(
                array(
                    "key" => "related_programs",
                    "compare" => "LIKE",
                    "value" => '"' . get_the_ID() . '"'
                )
            )
        ));
        ?>

        <?php if ($relatedProfessors->have_posts()) : ?>
            <hr class="section-break">
            <h2 class="headline headline--medium"><?php echo get_the_title($program); ?> Professors</h2>
        <?php endif; ?>

        <ul class="professor-cards">
            <?php while ($relatedProfessors->have_posts()) : ?>
                <?php $relatedProfessors->the_post(); ?>
                <li class="professor-card__list-item">
                    <a class="professor-card" href="<?php the_permalink(); ?>">
                        <img class="professor-card__image" src="<?php the_post_thumbnail_url("professorLandscape"); ?>">
                        <span class="professor-card__name"><?php the_title(); ?></span>
                    </a>
                </li>
            <?php endwhile; ?>
        </ul>

        <?php wp_reset_postdata(); ?>

        <?php
        $today = date("Ymd");
        $homepageEvents = new WP_Query(array(
            "posts_per_page" => 2, // -1 get all posts that meet the condition
            "post_type" => "event",
            "meta_key" => "event_date", // name of the custom field
            "orderby" => "meta_value_num", // post_date is the default, rand for random, meta_value alongside meta_key to use custom fields
            // meta_value for strings
            "order" => "ASC", // default is DESC
            "meta_query" => array(
                array( // bring only upcoming events
                    "key" => "event_date",
                    "compare" => ">=",
                    "value" => $today,
                    "type" => "numeric"
                ),
                array(
                    "key" => "related_programs",
                    "compare" => "LIKE",
                    "value" => '"' . get_the_ID() . '"'
                )
            )
        ));
        ?>

        <?php if ($homepageEvents->have_posts()) : ?>
            <hr class="section-break">
            <h2 class="headline headline--medium">Upcoming <?php echo get_the_title($program); ?> Events</h2>
        <?php endif; ?>

        <?php while ($homepageEvents->have_posts()) : ?>
            <?php $homepageEvents->the_post(); ?>
            <div class="event-summary">
                <a class="event-summary__date t-center" href="<?php the_permalink(); ?>">
                    <?php $event_date = new DateTime(get_field("event_date")); ?>
                    <span class="event-summary__month"><?php echo $event_date->format("M"); ?></span>
                    <span class="event-summary__day"><?php echo $event_date->format("d"); ?></span>
                </a>
                <div class="event-summary__content">
                    <h5 class="event-summary__title headline headline--tiny"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
                    <p>
                        <?php if (has_excerpt()) : ?>
                            <?php echo get_the_excerpt(); ?>
                        <?php else : ?>
                            <?php echo wp_trim_words(get_the_content(), 18); ?>
                        <?php endif; ?>
                        <a href="<?php the_permalink();  ?>" class="nu gray">Learn more</a>
                    </p>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

<?php endwhile; ?>

<?php get_footer(); ?>