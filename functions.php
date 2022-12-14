<?php function pageBanner($args = [])
{ ?>
  <?php // php logic will live here 
    $title = !$args["title"] ? get_the_title() : $args["title"];
    $subtitle = !$args["subtitle"] ? get_field("page_banner_subtitle") : $args["subtitle"];
    $pageBannerImage = !$args["photo"] ? get_field("page_banner_background_image") : $args["photo"];
    if (!$args["photo"]) {
      if (get_field("page_banner_background_image")) {
        $args["photo"] = get_field("page_banner_background_image")["sizes"]["pageBanner"];
      } else {
        $args["photo"] = get_theme_file_uri("/images/ocean.jpg");
      }
    }
  ?>
  <div class="page-banner">
    <div class="page-banner__bg-image" style="background-image: url(<?php echo $args["photo"]; ?>);"></div>
    <div class="page-banner__content container container--narrow">
      <h1 class="page-banner__title"><?php echo $title; ?></h1>
      <div class="page-banner__intro">
        <p><?php echo $subtitle; ?></p>
      </div>
    </div>
  </div>

<?php } ?>

<?php
function university_files()
{
  wp_enqueue_script("main-university-js", get_theme_file_uri("/build/index.js"), array("jquery"), "1.0", true);
  wp_enqueue_style("custom-google-fonts", "//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i");
  wp_enqueue_style("font-awesome", "//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css");
  wp_enqueue_style("university_main_styles", get_theme_file_uri("/build/style-index.css"));
  wp_enqueue_style("university_extra_styles", get_theme_file_uri("/build/index.css"));
}

function university_features()
{
  add_theme_support("title-tag");
  add_theme_support("post-thumbnails");
  add_image_size(
    "professorLandscape", // name
    400, // width
    260, // heigth
    true // crop, also accepts an array arra("left", "top") to specify the type of croppping
    // also use manual image crop (tomaz sita) plugin to more customized cropping
  );
  add_image_size("professorPortrait", 480, 650, true);
  add_image_size("pageBanner", 1500, 350, true);
}

add_action("wp_enqueue_scripts", "university_files");
add_action("after_setup_theme", "university_features");

function override_event_archive_query($query)
{
  $condition = (!is_admin() &&
    is_post_type_archive("event") &&
    $query->is_main_query() // not a custom query
  );
  if ($condition) {
    // first parameter, what we want to change
    // second parameter, value we want to give it.
    // $query->set("posts_per_page", '1'):
    $query->set("meta_key", "event_date");
    $query->set("orderby", "meta_value_num");
    $query->set("order", "ASC");
    $today = date("Ymd");
    $query->set(
      "meta_query",
      array( // bring only upcoming events
        "key" => "event_date",
        "compare" => ">=",
        "value" => $today,
        "type" => "numeric"
      )
    );
  }
}

function override_program_archive_query($query)
{
  $condition = (!is_admin() &&
    is_post_type_archive("program") &&
    $query->is_main_query() // not a custom query
  );
  if ($condition) {
    // first parameter, what we want to change
    // second parameter, value we want to give it.
    $query->set("posts_per_page", -1);
    $query->set("orderby", "title");
    $query->set("order", "ASC");
  }
}

function university_adjust_queries($query)
{
  override_event_archive_query($query);
  override_program_archive_query($query);
}

add_action("pre_get_posts", "university_adjust_queries");
