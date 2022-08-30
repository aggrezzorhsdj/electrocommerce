<?php get_header();?>
<?php
?>
    <section class="ec-section">
        <div class="container">
            <div class="_off-gutter">
                <div class="ec-carousel glide" aria-label="Splide Basic HTML Example">
                    <div class="ec-carousel__track glide__track" data-glide-el="track">
                        <ul class="ec-carousel__list glide__slides">
                            <?php for($i = 1; $i < 3; $i++) :?>
                                <li class="ec-carousel__slide glide__slide">
                                    <a class="ec-carousel__link" href="<?php echo get_theme_mod('ec_front_page_'.$i.'_link')?>">
                                        <img class="ec-carousel__image" src="<?php echo get_theme_mod('ec_front_page_'.$i.'_image')?>"/>
                                    </a>
                                </li>
                            <?php endfor;?>
                        </ul>
                    </div>
                    <div class="ec-carousel__arrows glide__arrows" data-glide-el="controls">
                        <button class="ec-carousel__arrow glide__arrow glide__arrow--left btn btn-light" data-glide-dir="<">
                            <i class="bi bi-arrow-left"></i>
                        </button>
                        <button class="ec-carousel__arrow glide__arrow glide__arrow--right btn btn-light" data-glide-dir=">">
                            <i class="bi bi-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php get_footer();?>

