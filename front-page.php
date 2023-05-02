<?php get_header();?>
<?php
?>
    <section class="ec-section">
        <div class="container">
            <div class="_off-gutter">
                <?php front_page_slider();?>
            </div>
        </div>
    </section>
    <section class="ec-section">
        <div class="container">
            <div class="h2">Популярное</div>
            <div class="_off-gutter">
                <?php echo do_shortcode("[products category='popular']")?>
            </div>
        </div>
    </section>
    <section class="ec-section">
        <div class="container">
            <div class="h2">Услуги</div>
            <div class="_off-gutter">
                <?php echo do_shortcode("[product_categories parent='43' columns='4']")?>
            </div>
        </div>
    </section>
<?php get_footer();?>

