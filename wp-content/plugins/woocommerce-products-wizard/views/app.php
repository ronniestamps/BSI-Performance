<?php
if (!defined('ABSPATH')) {
    exit();
}

$id = isset($id) ? $id : false;

if (!$id) {
    throw new Exception('Empty wizard id');
}
$stepId = isset($stepId) ? $stepId : WCProductsWizard\Instance()->getActiveStepId($id);
$defaults = [
    'id' => $id,
    'stepId' => $stepId
];

$arguments = isset($arguments) ? array_replace_recursive($defaults, $arguments) : $defaults;
?>
<div class="woocommerce-products-wizard builder-sidebar"
    data-component="wcpw"
    data-id="<?php echo esc_attr($arguments['id']); ?>">
    <?php WCProductsWizard\Instance()->getTemplatePart('router', $arguments); ?>
</div>
<?php
    // get the thumbnail id using the queried category term_id
    $thumbnail_id = get_woocommerce_term_meta( $arguments['stepId'], 'thumbnail_id', true ); 

    // get the image URL
    $image = wp_get_attachment_url( $thumbnail_id ); 
?>
<div class="builder-bg" id="builder-bg" style="background: url(<?php echo $image; ?>) no-repeat center center; background-size: cover; width: 100%; height: 100%;"></div>
<script type="text/javascript">
    jQuery(document).ready(function (){
        jQuery(document).on('ajaxCompleted.wcProductsWizard', function () {
            var image_src = jQuery(this).find('span').data('image');
            jQuery('#builder-bg').css('background', 'url('+image_src+')');
        });
    });
</script>