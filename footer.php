<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package electrocommerce
 */
wp_footer();
?>
<footer class="ec-footer bg-deep-blue">
    <div class="container">
        <?php if ($logo = get_theme_mod('ec_footer_logo')) : ?>
        <div class="ec-footer__logo _mb-xxxl mb-5">
            <img alt="" src="<?php echo $logo ?>" />
        </div>
        <?php endif;?>
        <div class="row">
            <?php echo get_footer_menu('footer_menu_1')?>
            <?php echo get_footer_menu('footer_menu_2')?>
            <?php if($menu_title = get_theme_mod('ec_footer_contacts_enabled')) : ?>
                <div class="col-md-4">
                    <h2 class="ec-footer__title h2">
                        <?php echo get_theme_mod('ec_footer_contacts_title');?>
                    </h2>
                    <?php if($phone = get_theme_mod('ec_contact_phone')): ?>
                        <div class="ec-footer__column-item">
                            <a class="text-light" href="tel:<?php echo $phone;?>">
                                <i class="bi bi-telephone"></i> <?php echo $phone?>
                            </a>
                        </div>
                    <?php endif; ?>
                    <?php if($address = get_theme_mod('ec_contact_address')): ?>
                        <div class="ec-footer__column-item">
                            <a class="text-light" href="https://yandex.ru/maps/?text=<?php echo $address;?>">
                                <i class="bi bi-geo-alt"></i>
                                <?php echo $address;?>
                            </a>
                        </div>
                    <?php endif; ?>
                    <?php if(get_theme_mod('ec_footer_social_enabled')): ?>
                        <div class="ec-footer__column-item _social">
                            <div class="d-flex">
                                <?php if($social = get_theme_mod('ec_contact_social_instagram')) : ?>
                                    <a class="ec-button _social _instagram _w-text" href="<?php echo $social;?>">
                                        <i class="bi bi-instagram"></i>
                                    </a>
                                <?php endif;?>
                                <?php if($social = get_theme_mod('ec_contact_social_whatsapp')) : ?>
                                    <a class="ec-button _social _whatsapp _w-text" href="whatsapp://send?phone=<?php echo $social;?>">
                                        <i class="bi bi-whatsapp"></i>
                                    </a>
                                <?php endif;?>
                                <?php if($social = get_theme_mod('ec_contact_social_vk')) : ?>
                                    <a class="ec-button _social _vk _w-text" href="<?php echo $social;?>">
                                        <i class="bi bi-vk"></i>
                                    </a>
                                <?php endif;?>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="ec-footer__column-item">
                        <p><?php echo date("Y");?>© <?php echo __('All rights reserved', "electrocommerce");?></p>
                    </div>
                </div>
            <?php endif;?>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>

</body>
</html>
