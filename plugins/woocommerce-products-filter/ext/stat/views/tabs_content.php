<?php
if (!defined('ABSPATH'))
    die('No direct access allowed');
?>

<section id="tabs-stat">
    <div class="woof-tabs woof-tabs-style-line">

        <nav>
            <ul>
                <li>
                    <a href="#woof-stat-1">
                        <span><?php _e("Statistic", 'woocommerce-products-filter') ?></span>
                    </a>
                </li>
                <li>
                    <a href="#woof-stat-2">
                        <span><?php _e("Options", 'woocommerce-products-filter') ?></span>
                    </a>
                </li>
            </ul>
        </nav>

        <?php global $wp_locale; ?>

        <div class="content-wrap">
            <section id="woof-stat-1">



                <div class="woof-control-section">

                    <h4 style="margin-bottom: 3px;"><?php _e('Select period:', 'woocommerce-products-filter') ?></h4>
                    <?php if (!empty($stat_min_date)): ?>
                        <div style="font-size: 12px; font-style: italic;"><?php printf(__('(Statistic collected from: %s %d)', 'woocommerce-products-filter'), $wp_locale->get_month($stat_min_date[1]), $stat_min_date[0]) ?></div>
                    <?php endif; ?>
                    <br />

                    <div class="woof-control-container">

                        <div class="woof-control woof-upload-style-wrap">

                            <input type="hidden" id="woof_stat_calendar_from" value="0" />
                            <input type="text" readonly="readonly" class="woof_stat_calendar woof_stat_calendar_from" placeholder="<?php _e('From', 'woocommerce-products-filter') ?>" />
                            &nbsp;
                            <input type="hidden" id="woof_stat_calendar_to" value="0" />
                            <input type="text" readonly="readonly" class="woof_stat_calendar woof_stat_calendar_to" placeholder="<?php _e('To', 'woocommerce-products-filter') ?>" /><br />

                            <br />

                        </div>
                        <div class="woof-description">
                            <p class="description">
                                <?php _e('Select the time period for which you want to see statistical data', 'woocommerce-products-filter') ?>
                            </p>
                        </div>
                    </div>
                </div><!--/ .woof-control-section-->




                <div class="woof-control-section">

                    <h4 style="margin-bottom: 7px;"><?php _e('Statistical parameters:', 'woocommerce-products-filter') ?></h4>

                    <div class="woof-control-container">

                        <div class="woof-control woof-upload-style-wrap">


                            <?php
                            //prices will be reviwed in the next versions of stat
                            $all_items = array(
                                    //'min_price' => __('Min price', 'woocommerce-products-filter'),
                                    //'max_price' => __('Max price', 'woocommerce-products-filter')
                            );
                            //***
                            $taxonomies = $this->get_taxonomies();
                            if (!empty($taxonomies))
                            {
                                foreach ($taxonomies as $slug => $t)
                                {
                                    $all_items[urldecode($slug)] = $t->labels->name;
                                }
                            }

                            asort($all_items);
                            //***

                            if (!isset($woof_settings['woof_stat']['items_for_stat']) OR empty($woof_settings['woof_stat']['items_for_stat']))
                            {
                                $woof_settings['woof_stat']['items_for_stat'] = array();
                            }
                            $items_for_stat = (array) $woof_settings['woof_stat']['items_for_stat'];
                            ?>


                            <?php if (!empty($items_for_stat)): ?>

                                <div class="select-wrap">
                                    <select id="woof_stat_snippet" multiple="" class="chosen_select">
                                        <?php foreach ($all_items as $key => $value) : ?>
                                            <?php
                                            if (!in_array($key, $items_for_stat))
                                            {
                                                continue;
                                            }
                                            ?>
                                            <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div style="display:none;">
                                    <ul id="woof_stat_snippets_tags"></ul>
                                </div>
                            <?php else: ?>
                                <p class="description" style="color: red;">
                                    <?php _e('Select taxonomies in tab Options and press "Save changes"', 'woocommerce-products-filter') ?>
                                </p>
                            <?php endif; ?>

                            <br />

                            <a href="javascript: woof_stat_calculate();" class="button button-primary button-large"><?php _e('Calculate Statistics', 'woocommerce-products-filter') ?></a><br />


                        </div>
                        <div class="woof-description">
                            <p class="description">
                                <?php _e('Select taxonomy, taxonomies combinations OR leave this field empty to see general data for all the most requested taxonomies', 'woocommerce-products-filter') ?>
                            </p>
                        </div>
                    </div>
                </div><!--/ .woof-control-section-->


                <div class="woof-control-section">

                    <h4><?php _e('Graphics', 'woocommerce-products-filter') ?></h4>

                    <div class="woof-control-container">

                        <div class="woof-control woof-upload-style-wrap" style="width: 100%;">

                            <ul id="woof_stat_get_monitor"></ul>

                            <div id="woof_stat_charts_list">
                                <!-- <a href="javascript: window.print();" id="woof_stat_print_btn" class="button button-primary"><?php _e('Print Graphs', 'woocommerce-products-filter') ?></a> -->
                                <div id="chart_div_1" style="width: 100%; height: 600px;"></div>
                                <div id="chart_div_1_set" style="width: 100%; height: auto;"></div>
                                <!-- <div id="chart_div_2" style="width: 100%; height: 600px;"></div> -->
                            </div>



                        </div>
                        <!-- <div class="woof-description" style="width: 30%;">
                            <p class="description">
                        <?php _e('xxx', 'woocommerce-products-filter') ?>
                            </p>
                        </div> -->
                    </div>
                </div><!--/ .woof-control-section-->


            </section>

            <section id="woof-stat-2">

                <div class="woof-control-section">

                    <h4 style="color: orange;"><?php _e('Notice:', 'woocommerce-products-filter') ?></h4>

                    <div class="woof-control-container">

                        <p class="description">
                            <?php _e('This extension is experimental but with finished arhitecture of collection and keeping the data! If you have an idea about how to improve representation of the statistic data in your shop, I mean how do you want to see processed data on the graphs, types of graphs, etc. - create topic <a href="https://wordpress.org/support/plugin/woocommerce-products-filter" target="_blank">here</a> to discuss it! Be sure that your PHP version is not lower than 5.4.', 'woocommerce-products-filter') ?>
                        </p>
                    </div>
                </div><!--/ .woof-control-section-->

                <div class="woof-control-section">

                    <h4><?php _e('Statistics collection:', 'woocommerce-products-filter') ?></h4>

                    <div class="woof-control-container">

                        <div class="woof-control woof-upload-style-wrap">

                            <?php
                            $stat_activated_mode = array(
                                0 => __('Disabled', 'woocommerce-products-filter'),
                                1 => __('Enabled', 'woocommerce-products-filter')
                            );

                            if (!isset($woof_settings['woof_stat']['is_enabled']))
                            {
                                $woof_settings['woof_stat']['is_enabled'] = 0;
                            }
                            $is_enabled = $woof_settings['woof_stat']['is_enabled'];
                            if (!$is_enabled)
                            {
                                echo '<div class="error"><p class="description">' . sprintf(__('Statistic extension is activated but statistics collection is not enabled. Enable it on: tab Statistic -> tab Options -> "Statistics collection enabled"', 'woocommerce-products-filter')) . '</p></div>';
                            }
                            ?>

                            <div class="select-wrap">
                                <select name="woof_settings[woof_stat][is_enabled]" class="chosen_select">
                                    <?php foreach ($stat_activated_mode as $key => $value) : ?>
                                        <option value="<?php echo $key; ?>" <?php if ($is_enabled == $key): ?>selected="selected"<?php endif; ?>><?php echo $value; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>


                        </div>
                        <div class="woof-description">
                            <p class="description">
                                <?php _e('After installing all settings for statistics assembling - enable it here', 'woocommerce-products-filter') ?>
                            </p>
                        </div>
                    </div>
                </div><!--/ .woof-control-section-->

                <div class="woof-control-section">

                    <h4><?php _e('Server options for statistic stock', 'woocommerce-products-filter') ?>:</h4>
                    <?php
                    if (!isset($woof_settings['woof_stat']['server_options']) OR empty($woof_settings['woof_stat']['server_options']))
                    {
                        $woof_settings['woof_stat']['server_options'] = array(
                            'host' => '',
                            'host_db_name' => '',
                            'host_user' => '',
                            'host_pass' => '',
                        );
                    }

                    $server_options = $woof_settings['woof_stat']['server_options'];

                    if (empty($server_options['host']) OR empty($server_options['host_user']) OR empty($server_options['host_db_name']) OR empty($server_options['host_pass']))
                    {
                        echo '<div class="error"><p class="description">' . sprintf(__('Statistic -> tab Options -> "Stat server options" inputs should be filled in by right data, another way not possible to collect statistical data!', 'woocommerce-products-filter')) . '</p></div>';
                    }
                    ?>
                    <div class="woof-control-container">
                        <div class="woof-control">
                            <label style="margin-bottom: 5px; display: inline-block;"><?php _e('Host', 'woocommerce-products-filter') ?></label>:
                            <input type="text" name="woof_settings[woof_stat][server_options][host]" value="<?php echo $server_options['host'] ?>" /><br />
                            <br />
                            <label style="margin-bottom: 5px; display: inline-block;"><?php _e('User', 'woocommerce-products-filter') ?></label>:
                            <input type="text" name="woof_settings[woof_stat][server_options][host_user]" value="<?php echo $server_options['host_user'] ?>" /><br />
                            <br />
                            <label style="margin-bottom: 5px; display: inline-block;"><?php _e('DB Name', 'woocommerce-products-filter') ?></label>:
                            <input type="text" name="woof_settings[woof_stat][server_options][host_db_name]" value="<?php echo $server_options['host_db_name'] ?>" /><br />
                            <br />
                            <label style="margin-bottom: 5px; display: inline-block;"><?php _e('Password', 'woocommerce-products-filter') ?></label>:
                            <input type="text" name="woof_settings[woof_stat][server_options][host_pass]" value="<?php echo $server_options['host_pass'] ?>" /><br />


                        </div>
                        <div class="woof-description">
                            <p class="description">
                                <?php _e('This data is very important for assembling statistics data, so please fill fields very responsibly. To collect statistical data uses a separate MySQL table.', 'woocommerce-products-filter') ?><br />
                                <br />
                                <a href="http://www.woocommerce-filter.com/extencion/statistic/" target="_blank" class="button"><?php _e('Read about the Statistic extension here', 'woocommerce-products-filter') ?></a>
                            </p>
                        </div>
                    </div>

                </div><!--/ .woof-control-section-->



                <div class="woof-control-section">

                    <h4><?php _e('Statistic for:', 'woocommerce-products-filter') ?></h4>

                    <div class="woof-control-container">

                        <div class="woof-control woof-upload-style-wrap">

                            <?php
                            $all_items = array(
                                    //'min_price' => __('Min price', 'woocommerce-products-filter'),
                                    //'max_price' => __('Max price', 'woocommerce-products-filter')
                            );
                            //***
                            $taxonomies = $this->get_taxonomies();
                            if (!empty($taxonomies))
                            {
                                foreach ($taxonomies as $slug => $t)
                                {
                                    $all_items[urldecode($slug)] = $t->labels->name;
                                }
                            }

                            asort($all_items);
                            //***

                            if (!isset($woof_settings['woof_stat']['items_for_stat']) OR empty($woof_settings['woof_stat']['items_for_stat']))
                            {
                                $woof_settings['woof_stat']['items_for_stat'] = array();
                            }
                            $items_for_stat = (array) $woof_settings['woof_stat']['items_for_stat'];
                            ?>

                            <div class="select-wrap">
                                <select multiple="" name="woof_settings[woof_stat][items_for_stat][]" class="chosen_select">
                                    <?php foreach ($all_items as $key => $value) : ?>
                                        <option value="<?php echo $key; ?>" <?php if (in_array($key, $items_for_stat)): ?>selected="selected"<?php endif; ?>><?php echo $value; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>


                        </div>
                        <div class="woof-description">
                            <p class="description">
                                <?php _e('Select taxonomies which you want to track', 'woocommerce-products-filter') ?>
                            </p>
                        </div>
                    </div>
                </div><!--/ .woof-control-section-->





                <div class="woof-control-section">

                    <h4><?php _e('Max requests per unique user', 'woocommerce-products-filter') ?></h4>
                    <?php
                    if (!isset($woof_settings['woof_stat']['user_max_requests']) OR empty($woof_settings['woof_stat']['user_max_requests']))
                    {
                        $woof_settings['woof_stat']['user_max_requests'] = 10;
                    }
                    $user_max_requests = intval($woof_settings['woof_stat']['user_max_requests']);
                    if ($user_max_requests <= 0)
                    {
                        $user_max_requests = 10;
                    }
                    ?>
                    <div class="woof-control-container">
                        <div class="woof-control">
                            <input type="text" name="woof_settings[woof_stat][user_max_requests]" value="<?php echo $user_max_requests ?>" />
                        </div>
                        <div class="woof-description">
                            <p class="description"><?php _e('How many search requests will be catched and written down into the statistical mySQL table per 1 unique user before cron will assemble the data', 'woocommerce-products-filter') ?></p>
                        </div>
                    </div>

                </div><!--/ .woof-control-section-->



                <div class="woof-control-section">

                    <h4><?php _e('Max deep of the search request', 'woocommerce-products-filter') ?></h4>
                    <?php
                    if (!isset($woof_settings['woof_stat']['request_max_deep']) OR empty($woof_settings['woof_stat']['request_max_deep']))
                    {
                        $woof_settings['woof_stat']['request_max_deep'] = 5;
                    }
                    $request_max_deep = intval($woof_settings['woof_stat']['request_max_deep']);
                    if ($request_max_deep <= 0)
                    {
                        $request_max_deep = 5;
                    }
                    ?>
                    <div class="woof-control-container">
                        <div class="woof-control">
                            <input type="text" name="woof_settings[woof_stat][request_max_deep]" value="<?php echo $request_max_deep ?>" />
                        </div>
                        <div class="woof-description">
                            <p class="description"><?php _e('How many taxonomies per one search request will be written down into the statistical mySQL table for 1 unique user. The excess data will be truncated! Number 5 is recommended. More depth - more space in the DataBase will be occupied by the data', 'woocommerce-products-filter') ?></p>
                        </div>
                    </div>

                </div><!--/ .woof-control-section-->







                <div class="woof-control-section" style="display: none;">

                    <h4><?php _e('Cache folder', 'woocommerce-products-filter') ?></h4>
                    <?php
                    if (!isset($woof_settings['woof_stat']['cache_folder']) OR empty($woof_settings['woof_stat']['cache_folder']))
                    {
                        $woof_settings['woof_stat']['cache_folder'] = '_woof_stat_cache';
                    }
                    $cache_folder = sanitize_title($woof_settings['woof_stat']['cache_folder']);
                    /*
                      $path = realpath(WP_CONTENT_DIR . '/' . $cache_folder);
                      if ($path === false AND ! is_dir($path))
                      {

                      if (!mkdir(WP_CONTENT_DIR . '/' . $cache_folder))
                      {
                      echo '<div class="error"><p class="description">' . sprintf(__('Not possible to create folder %s automatically, create it please manually!', 'woocommerce-products-filter'), WP_CONTENT_DIR . '/' . $cache_folder) . '</p></div>';
                      }

                      }
                     *
                     */
                    ?>
                    <div class="woof-control-container">
                        <div class="woof-control">
                            <input type="text" name="woof_settings[woof_stat][cache_folder]" value="<?php echo $cache_folder ?>" />
                        </div>
                        <div class="woof-description">
                            <p class="description">
                                <?php echo WP_CONTENT_DIR . '/' . $cache_folder ?>/<br />
                                <?php _e('Select cron which you want to use for the statistic assembling. Better use WordPress cron, but on the server create external cron and set there period of site visiting.', 'woocommerce-products-filter') ?></p>
                        </div>
                    </div>

                </div><!--/ .woof-control-section-->


                <div class="woof-control-section">

                    <h4><?php _e('How to assemble statistic', 'woocommerce-products-filter') ?></h4>

                    <div class="woof-control-container">

                        <div class="woof-control woof-upload-style-wrap">

                            <?php
                            $cron_systems = array(
                                0 => __('WordPress Cron', 'woocommerce-products-filter'),
                                    //1 => __('External Cron', 'woocommerce-products-filter')
                            );

                            if (!isset($woof_settings['woof_stat']['cron_system']))
                            {
                                $woof_settings['woof_stat']['cron_system'] = 0;
                            }
                            $cron_system = $woof_settings['woof_stat']['cron_system'];
                            ?>

                            <div class="select-wrap">
                                <select name="woof_settings[woof_stat][cron_system]" class="chosen_select woof_cron_system">
                                    <?php foreach ($cron_systems as $key => $value) : ?>
                                        <option value="<?php echo $key; ?>" <?php if ($cron_system == $key): ?>selected="selected"<?php endif; ?>><?php echo $value; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>


                        </div>
                        <div class="woof-description">
                            <p class="description">
                                <?php _e('Use WordPress Cron if your site has a lot of traffic, and external cron if the site traffic is not big. External cron is more predictable with time of execution, but additional knowledge how to set it correctly is required (<i style="color: orange;">External cron will be ready in the next version of the extension</i>)', 'woocommerce-products-filter') ?>
                            </p>
                        </div>
                    </div>
                </div><!--/ .woof-control-section-->


                <div class="woof-control-section woof_external_cron_option" style="display: <?php echo($cron_system == 1 ? 'block' : 'none') ?>;">

                    <h4><?php _e('Secret key for external cron', 'woocommerce-products-filter') ?></h4>
                    <?php
                    if (!isset($woof_settings['woof_stat']['cron_secret_key']) OR empty($woof_settings['woof_stat']['cron_secret_key']))
                    {
                        $woof_settings['woof_stat']['cron_secret_key'] = 'woof_stat_updating';
                    }
                    $cron_secret_key = sanitize_title($woof_settings['woof_stat']['cron_secret_key']);
                    ?>
                    <div class="woof-control-container">
                        <div class="woof-control">
                            <input type="text" name="woof_settings[woof_stat][cron_secret_key]" value="<?php echo $cron_secret_key ?>" />
                        </div>
                        <div class="woof-description">
                            <p class="description"><?php _e('Enter any random text in the field and use it in the external cron with link like: http://mysite.com/?woof_stat_collection=__YOUR_SECRET_KEY_HERE__', 'woocommerce-products-filter') ?></p>
                        </div>
                    </div>

                </div><!--/ .woof-control-section-->


                <div class="woof-control-section woof_wp_cron_option" style="display: <?php echo($cron_system == 0 ? 'block' : 'none') ?>;">

                    <h4><?php _e('WordPress Cron period', 'woocommerce-products-filter') ?></h4>

                    <div class="woof-control-container">

                        <div class="woof-control woof-upload-style-wrap">

                            <?php
                            $wp_cron_periods = array(
                                'hourly' => __('hourly', 'woocommerce-products-filter'),
                                'twicedaily' => __('twicedaily', 'woocommerce-products-filter'),
                                'daily' => __('daily', 'woocommerce-products-filter'),
                                'week' => __('weekly', 'woocommerce-products-filter'),
                                'month' => __('monthly', 'woocommerce-products-filter'),
                                    //'min1' => __('min1', 'woocommerce-products-filter')
                            );

                            if (!isset($woof_settings['woof_stat']['wp_cron_period']))
                            {
                                $woof_settings['woof_stat']['wp_cron_period'] = 'daily';
                            }
                            $wp_cron_period = $woof_settings['woof_stat']['wp_cron_period'];
                            ?>

                            <div class="select-wrap">
                                <select name="woof_settings[woof_stat][wp_cron_period]" class="chosen_select">
                                    <?php foreach ($wp_cron_periods as $key => $value) : ?>
                                        <option value="<?php echo $key; ?>" <?php if ($wp_cron_period == $key): ?>selected="selected"<?php endif; ?>><?php echo $value; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>


                        </div>
                        <div class="woof-description">
                            <p class="description">
                                <?php _e('12 hours recommended', 'woocommerce-products-filter') ?>
                            </p>
                        </div>
                    </div>
                </div><!--/ .woof-control-section-->



                <div class="woof-control-section">

                    <h4><?php _e('Max terms or taxonomies per graph', 'woocommerce-products-filter') ?></h4>
                    <?php
                    if (!isset($woof_settings['woof_stat']['max_items_per_graph']) OR empty($woof_settings['woof_stat']['max_items_per_graph']))
                    {
                        $woof_settings['woof_stat']['max_items_per_graph'] = 10;
                    }
                    $max_items_per_graph = intval($woof_settings['woof_stat']['max_items_per_graph']);
                    if ($max_items_per_graph <= 0)
                    {
                        $max_items_per_graph = 10;
                    }
                    ?>
                    <div class="woof-control-container">
                        <div class="woof-control">
                            <input type="text" name="woof_settings[woof_stat][max_items_per_graph]" value="<?php echo $max_items_per_graph ?>" />
                        </div>
                        <div class="woof-description">
                            <p class="description"><?php _e('How many taxonomies and terms to show on the graphs. Use no more than 10 to understand situation with statistical data', 'woocommerce-products-filter') ?></p>
                        </div>
                    </div>

                </div><!--/ .woof-control-section-->





                <?php
                global $wpdb;

                $charset_collate = '';
                if (method_exists($wpdb, 'has_cap') AND $wpdb->has_cap('collation'))
                {
                    if (!empty($wpdb->charset))
                    {
                        $charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
                    }
                    if (!empty($wpdb->collate))
                    {
                        $charset_collate .= " COLLATE $wpdb->collate";
                    }
                }
                //***
                $sql = "CREATE TABLE IF NOT EXISTS `{$table_stat_buffer}` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        `hash` text COLLATE utf8_unicode_ci NOT NULL,
                        `user_ip` text COLLATE utf8_unicode_ci NOT NULL,
                        `taxonomy` text COLLATE utf8_unicode_ci NOT NULL,
                        `value` int(11) NOT NULL,
                        `page` text COLLATE utf8_unicode_ci NOT NULL,
                        `tax_page_term_id` int(11) NOT NULL DEFAULT '0',
                        `time` int(11) NOT NULL,
                        PRIMARY KEY (`id`)
                      ) ENGINE=MyISAM {$charset_collate};";

                if ($wpdb->query($sql) === false)
                {
                    ?>
                    <p class="description"><?php _e("WOOF cannot create database table for statistic! Make sure that your mysql user has the CREATE privilege! Do it manually using your host panel&amp;phpmyadmin!", 'woocommerce-products-filter') ?></p>
                    <code><?php echo $sql; ?></code>
                    <?php
                    echo $wpdb->last_error;
                }

                //***
                $sql = "CREATE TABLE IF NOT EXISTS `{$table_stat_tmp}` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        `user_ip` text COLLATE utf8_unicode_ci NOT NULL,
                        `page` varchar(16) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'shop',
                        `request` text COLLATE utf8_unicode_ci NOT NULL,
                        `hash` text COLLATE utf8_unicode_ci NOT NULL,
                        `tax_page` text COLLATE utf8_unicode_ci NOT NULL,
                        `tax_page_term_id` int(11) NOT NULL,
                        `time` int(11) NOT NULL,
                        `is_collected` int(1) NOT NULL DEFAULT '0',
                        PRIMARY KEY (`id`)
                      ) ENGINE=MyISAM  {$charset_collate};";

                if ($wpdb->query($sql) === false)
                {
                    ?>
                    <p class="description"><?php _e("WOOF cannot create database table for statistic! Make sure that your mysql user has the CREATE privilege! Do it manually using your host panel&amp;phpmyadmin!", 'woocommerce-products-filter') ?></p>
                    <code><?php echo $sql; ?></code>
                    <?php
                    echo $wpdb->last_error;
                }
                ?>



            </section>

        </div>

    </div>
</section>

<style type="text/css">
    #woof_stat_print_btn{
        display: none;
    }

    .woof_stat_calendar{
        width: 40% !important;
    }

    #woof_stat_get_monitor{
        font-size: 10px;
        height: auto;
        max-height: 75px;
        overflow: auto;
    }

    #woof_stat_get_monitor li{
        padding: 0;
        margin: 0 0 3px 0;
        line-height: normal;
    }

    .woof_stat_one_graph .woof_stat_graph_title{
        display: block;
        font-weight: bold;
        font-size: 16px;
        padding-top: 50px;
    }

    @media print{
        #adminmenumain,
        .woo-nav-tab-wrapper{
            display: none;
        }

        #wpcontent{
            margin: 0;
            padding: 0;
        }

        #chart_div_1{
            padding: 50px;
            page-break-before:always;
            page-break-after:always;
        }

        .woof_stat_one_graph{
            padding: 50px;
            page-break-before:auto;
            page-break-after:always;
        }
    }
    /*
        @media print
        {
            body * { visibility: hidden; padding:0 !important;margin: 0 !important; }
            #woof_stat_charts_list * { visibility: visible;padding:0 !important;margin: 0 !important;  }

        }


    */

</style>

<script type="text/javascript">
    jQuery(function ($) {
        //reset cache of "Statistical parameters" drop-down
        jQuery("#woof_stat_snippet option[selected]").removeAttr("selected");

        //+++
        //*** Load the Visualization API and the corechart package.
        try {
            google.charts.load('current', {'packages': ['corechart', 'bar']});
        } catch (e) {
            console.log('<?php _e('Google charts library not loaded! If site is on localhost just disable statistic extension in tab Extensions!', 'woocommerce-products-filter') ?>');
        }
        //+++
        jQuery('.woof_cron_system').change(function () {
            var state = parseInt(jQuery(this).val(), 10);
            if (state === 1) {
                //external
                jQuery('.woof_external_cron_option').show(200);
                jQuery('.woof_wp_cron_option').hide(200);
            } else {
                jQuery('.woof_external_cron_option').hide(200);
                jQuery('.woof_wp_cron_option').show(200);
            }
        });
    });

    //+++

    function woof_stat_draw_graphs() {
        woof_stat_process_monitor('<?php _e('drawing graphs ...', 'woocommerce-products-filter') ?>');

        try {
            if (woof_stat_data.length) {
                var graph1 = {};
                //***
                var counter = 1;
                if (Object.keys(woof_stat_get_request_snippets()).length === 0) {
                    var data1 = woof_stat_data[0];
                    counter = 1;
                    for (tn in data1) {
                        if (counter > parseInt(woof_stat_vars.max_items_per_graph, 10)) {
                            break;
                        }
                        graph1[tn] = data1[tn];
                        counter++;
                    }

                    //+++
                    var data2 = woof_stat_data[1];
                    counter = 1;
                    var graph_count = 0;
                    for (i in data2) {

                        var graph = {};
                        var html = "";
                        var id = 'chart_div_1_set_' + graph_count;
                        html = '<div class="woof_stat_one_graph"><span class="woof_stat_graph_title">' + data2[i]['tax_name'] + '</span>';
                        html += "<div id='" + id + "' style='width: 100%; height: 500px;'></div></div>";
                        jQuery('#chart_div_1_set').append(html);
                        counter = 1;

                        for (term_name in data2[i]['terms']) {
                            if (counter > parseInt(woof_stat_vars.max_items_per_graph, 10)) {
                                break;
                            }
                            //+++
                            graph[term_name] = parseInt(data2[i]['terms'][term_name], 10);
                            counter++;
                        }
                        //console.log(id);
                        //console.log(graph);
                        drawChart1(graph, id);
                        graph_count++;
                    }

                } else {
                    var counter = 1;
                    jQuery(woof_stat_data).each(function (i, request_block) {
                        //counter = 0;
                        jQuery(request_block).each(function (ii, item) {
                            if (counter > parseInt(woof_stat_vars.max_items_per_graph, 10)) {
                                return;
                            }
                            //+++
                            if (graph1[item.vname] !== undefined) {
                                graph1[item.vname] = graph1[item.vname] + parseInt(item.val, 10);
                            } else {
                                graph1[item.vname] = parseInt(item.val, 10);
                            }

                            counter++;
                        });
                    });
                }
                drawChart1(graph1, 'chart_div_1');
                //***

                /*
                 var graph2 = [['Name', 'Value', {role: 'style'}]];
                 //console.log(woof_stat_data);
                 jQuery(woof_stat_data).each(function (i, request_block) {
                 jQuery(request_block).each(function (ii, item) {
                 graph2[graph2.length] = [item.vname, item.val, 'opacity: 0.2'];
                 });
                 });
                 drawChart2(graph2);
                 */
            }

            woof_stat_process_monitor('<?php _e('finished!', 'woocommerce-products-filter') ?>');
            jQuery('#woof_stat_print_btn').show(200);
        } catch (e) {
            console.log('<?php _e('Looks like troubles with JavaScript!', 'woocommerce-products-filter') ?>');
        }

        return false;
    }


    //+++


    function drawChart1(graph1, id) {

        var data = new google.visualization.DataTable();
        data.addColumn('string', 'X');
        data.addColumn('number', 'Y');
        var rows_data = [];

        jQuery.each(graph1, function (index, value) {
            rows_data.push([index + " (" + value + ")", value]);
        });
        data.addRows(rows_data);
        /*
         data.addRows([
         ['Mushrooms', 3],
         ['Onions', 1],
         ['Olives', 2]
         ]);
         */

        // Set chart options
        var options = {
            'title': 'Graph 1',
            //'width': 800,
            //'height': 600,
            chartArea: {left: 0, top: 0, width: "100%", height: "100%"}
        };

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.PieChart(document.getElementById(id));
        chart.draw(data, options);
    }


    function drawChart2(graph2) {
        var data = google.visualization.arrayToDataTable(graph2);
        /*
         var data = google.visualization.arrayToDataTable([
         ['Name', 'Value', {role: 'style'}],
         ['2010', 10, 'color: gray'],
         ['2020', 14, 'color: #76A7FA'],
         ['2030', 16, 'opacity: 0.2'],
         ['2040', 22, 'stroke-color: #703593; stroke-width: 4; fill-color: #C5A5CF'],
         ['2050', 28, 'stroke-color: #871B47; stroke-opacity: 0.6; stroke-width: 8; fill-color: #BC5679; fill-opacity: 0.2']
         ]);
         */
        // Set chart options
        var options = {
            'title': 'Graph 2',
            //'width': 800,
            //'height': 600,
            chartArea: {left: 0, top: 0, width: "100%", height: "100%"}
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('chart_div_2'));
        chart.draw(data, options);

    }
</script>

