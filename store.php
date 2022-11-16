<?php
/**
 * The Template for displaying all single posts.
 *
 * @package dokan
 * @package dokan - 2014 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

$store_user   = dokan()->vendor->get( get_query_var( 'author' ) );
$store_info   = $store_user->get_shop_info();
$map_location = $store_user->get_location();
$layout       = get_theme_mod( 'store_layout', 'left' );

get_header( 'shop' );

if ( function_exists( 'yoast_breadcrumb' ) ) {
    yoast_breadcrumb( '<p id="breadcrumbs">', '</p>' );
}
?>
<?php do_action( 'woocommerce_before_main_content' ); ?>

<div class="dokan-store-wrap layout-<?php echo esc_attr( $layout ); ?>">

    <?php if ( 'left' === $layout ) { ?>
        <?php
        dokan_get_template_part(
            'store', 'sidebar', [
                'store_user'   => $store_user,
                'store_info'   => $store_info,
                'map_location' => $map_location,
            ]
        );
        ?>
    <?php } ?>

    <div id="dokan-primary" class="dokan-single-store">
        <div id="dokan-content" class="store-page-wrap woocommerce" role="main">

            <?php dokan_get_template_part( 'store-header' ); ?>

            <?php do_action( 'dokan_store_profile_frame_after', $store_user->data, $store_info ); ?>

            <?php if ( have_posts() ) { ?>

                <div class="seller-items">

                    <?php woocommerce_product_loop_start(); ?>
                    <?php 
                        $vendor_id = get_post_field( 'post_author', get_the_id() );
                        $vendor = dokan()->vendor->get( $vendor_id );
                        $phone = $vendor->get_phone();
                    ?>
                    <?php
                    while ( have_posts() ) :
                        the_post();
						?>

                        <?php wc_get_template_part( 'content', 'product' ); ?>

                    <?php endwhile; // end of the loop. ?>

                    <?php woocommerce_product_loop_end(); ?>

                </div>

                <?php dokan_content_nav( 'nav-below' ); ?>

            <?php } else { ?>

                <p class="dokan-info"><?php esc_html_e( 'No products were found of this vendor!', 'dokan-lite' ); ?></p>

            <?php } ?>

            <div>
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
                <a href="https://api.whatsapp.com/send?phone=<?php echo $phone ?>" class="float" target="_blank">
                <i class="fa fa-whatsapp my-float"></i>
            </a>
            </div>
        </div>

    </div><!-- .dokan-single-store -->

    <style>
            .__talkjs_launcher{
                margin-right: 80px !important;
            }


            .float{
                position:fixed;
                width:60px;
                height:60px;
                bottom:100px;
                right:10px;
                background-color:#25d366;
                color:#FFF;
                border-radius:50px;
                text-align:center;
            font-size:30px;
                box-shadow: 2px 2px 3px #999;
            z-index:100;
            }

            .my-float{
                margin-top:16px;
            }    
    </style>

    <?php if ( 'right' === $layout ) { ?>
        <?php
        dokan_get_template_part(
            'store', 'sidebar', [
                'store_user'   => $store_user,
                'store_info'   => $store_info,
                'map_location' => $map_location,
            ]
        );
        ?>
    <?php } ?>

</div><!-- .dokan-store-wrap -->

<?php do_action( 'woocommerce_after_main_content' ); ?>

<?php get_footer( 'shop' ); ?>
