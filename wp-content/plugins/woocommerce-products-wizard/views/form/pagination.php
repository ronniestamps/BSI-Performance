<?php
if (!defined('ABSPATH')) {
    exit();
}

$id = isset($id) ? $id : false;

if (!$id) {
    throw new Exception('Empty wizard id');
}

$defaults = [
    'settings' => []
];

$arguments = isset($arguments) ? array_replace_recursive($defaults, $arguments) : $defaults;

$items = WCProductsWizard\Core::getPaginationItems($arguments);
$singleMode = isset($arguments['settings']['enable_single_step_mode'])
    && filter_var($arguments['settings']['enable_single_step_mode'], FILTER_VALIDATE_BOOLEAN);

if ($singleMode || empty($items)) {
    return;
}
?>
<ul class="pagination" data-component="wcpw-form-pagination">
    <?php foreach ($items as $item) { ?>
        <li class="<?php echo esc_attr($item['class']); ?>">
            <?php echo $item['innerHtml']; ?>
        </li>
    <?php } ?>
</ul>
