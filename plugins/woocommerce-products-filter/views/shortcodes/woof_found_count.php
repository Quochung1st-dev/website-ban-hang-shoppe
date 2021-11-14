<?php
if (!defined('ABSPATH'))
    die('No direct access allowed');

//***

global $wp_query;
?>

<?php if ($this->is_isset_in_request_data($this->get_swoof_search_slug())): ?>
    <span class="woof_found_count"><?php echo(isset($_REQUEST['woof_wp_query_found_posts']) ? $_REQUEST['woof_wp_query_found_posts'] : $wp_query->found_posts) ?></span>
<?php endif; ?>



