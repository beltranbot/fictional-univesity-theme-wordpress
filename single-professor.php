<?php get_header(); ?>

<?php while (have_posts()) : ?>
    <?php the_post(); ?>
    <?php pageBanner(); ?>

    <div class="container container--narrow page-section">
        <div class="generic-content">
            <div class="row group">
                <div class="one-third">
                    <?php the_post_thumbnail("professorPortrait"); ?>
                </div>
                <div class="two-thirds">
                    <?php  the_content(); ?>
                </div>
            </div>
            
        </div>

        <?php $relatedPrograms = get_field("related_programs"); ?>
        <?php if (is_array($relatedPrograms) && count($relatedPrograms) > 0) : ?>
            <hr class="section-break">
            <h2 class="headline headline--medium">Subject(s) Taught</h2>

            <ul class="link-list min-list">
                <?php foreach ($relatedPrograms as $program) : ?>
                    <li>
                        <a href="<?php echo get_the_permalink($program); ?>">
                            <?php echo get_the_title($program); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>

<?php endwhile; ?>

<?php get_footer(); ?>