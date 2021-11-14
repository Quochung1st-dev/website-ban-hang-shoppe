<?php if (!defined('ABSPATH')) die('No direct access allowed'); ?>
<div class="woof-admin-preloader"></div>

<div class="subsubsub_section">

    <?php if (isset($_GET['settings_saved'])): ?>
        <div id="message" class="updated"><p><strong><?php _e("Your settings have been saved.", 'woocommerce-products-filter') ?></strong></p></div>
    <?php endif; ?>

    <?php
    if (!empty(WOOF_HELPER::$notices)) {
	foreach (WOOF_HELPER::$notices as $key) {
	    WOOF_HELPER::show_admin_notice($key);
	}
    }
    ?>


    <?php if (isset($_GET['woof_hide_notice'])): ?>
        <script type="text/javascript">
    	window.location = "<?php echo admin_url('admin.php?page=wc-settings&tab=woof'); ?>";
        </script>
    <?php endif; ?>

    <section class="woof-section">
        <h3><?php printf(__('WOOF - Products Filter Options v.%s', 'woocommerce-products-filter'), WOOF_VERSION) ?></h3>
        <input type="hidden" name="woof_settings" value="" />
        <input type="hidden" name="woof_settings[items_order]" value="<?php echo(isset($woof_settings['items_order']) ? $woof_settings['items_order'] : '') ?>" />

	<?php if (version_compare(WOOCOMMERCE_VERSION, WOOF_MIN_WOOCOMMERCE_VERSION, '<')): ?>

    	<div id="message" class="error fade"><p><strong><?php _e("ATTENTION! Your version of the woocommerce plugin is too obsolete. There is no warranty for working with WOOF!!", 'woocommerce-products-filter') ?></strong></p></div>

	<?php endif; ?>

        <svg class="hidden">
        <defs>
        <path id="tabshape" d="M80,60C34,53.5,64.417,0,0,0v60H80z"/>
        </defs>
        </svg>

        <div id="tabs" class="woof-tabs woof-tabs-style-shape">

            <nav>
                <ul>
                    <li class="tab-current">
                        <a href="#tabs-1">
                            <svg viewBox="0 0 80 60" preserveAspectRatio="none"><use xlink:href="#tabshape"></use></svg>
                            <span><?php _e("Structure", 'woocommerce-products-filter') ?></span>
                        </a>
                    </li>
                    <li>
                        <a href="#tabs-2">
                            <svg viewBox="0 0 80 60" preserveAspectRatio="none"><use xlink:href="#tabshape"></use></svg>
                            <svg viewBox="0 0 80 60" preserveAspectRatio="none"><use xlink:href="#tabshape"></use></svg>
                            <span><?php _e("Options", 'woocommerce-products-filter') ?></span>
                        </a>
                    </li>
                    <li>
                        <a href="#tabs-3">
                            <svg viewBox="0 0 80 60" preserveAspectRatio="none"><use xlink:href="#tabshape"></use></svg>
                            <svg viewBox="0 0 80 60" preserveAspectRatio="none"><use xlink:href="#tabshape"></use></svg>
                            <span><?php _e("Design", 'woocommerce-products-filter') ?></span>
                        </a>
                    </li>
                    <li>
                        <a href="#tabs-4">
                            <svg viewBox="0 0 80 60" preserveAspectRatio="none"><use xlink:href="#tabshape"></use></svg>
                            <svg viewBox="0 0 80 60" preserveAspectRatio="none"><use xlink:href="#tabshape"></use></svg>
                            <span><?php _e("Advanced", 'woocommerce-products-filter') ?></span>
                        </a>
                    </li>


		    <?php
		    if (!empty(WOOF_EXT::$includes['applications'])) {
			foreach (WOOF_EXT::$includes['applications'] as $obj) {
			    $dir1 = $this->get_custom_ext_path() . $obj->folder_name;
			    $dir2 = WOOF_EXT_PATH . $obj->folder_name;
			    $checked1 = WOOF_EXT::is_ext_activated($dir1);
			    $checked2 = WOOF_EXT::is_ext_activated($dir2);
			    if ($checked1 OR $checked2) {
				do_action('woof_print_applications_tabs_' . $obj->folder_name);
			    }
			}
		    }
		    ?>

                    <li>
                        <a href="#tabs-6">
                            <svg viewBox="0 0 80 60" preserveAspectRatio="none"><use xlink:href="#tabshape"></use></svg>
                            <svg viewBox="0 0 80 60" preserveAspectRatio="none"><use xlink:href="#tabshape"></use></svg>
                            <span><?php _e("Extensions", 'woocommerce-products-filter') ?></span>
                        </a>
                    </li>
                    <li>
                        <a href="#tabs-7">
                            <svg viewBox="0 0 80 60" preserveAspectRatio="none"><use xlink:href="#tabshape"></use></svg>
                            <span><?php _e("Info", 'woocommerce-products-filter') ?></span>
                        </a>
                    </li>
                </ul>
            </nav>

            <div class="content-wrap">

                <section id="tabs-1" class="content-current">

                    <ul id="woof_options">

			<?php
			$items_order = array();
			$taxonomies = $this->get_taxonomies();
			$taxonomies_keys = array_keys($taxonomies);
			if (isset($woof_settings['items_order']) AND ! empty($woof_settings['items_order'])) {
			    $items_order = explode(',', $woof_settings['items_order']);
			} else {
			    $items_order = array_merge($this->items_keys, $taxonomies_keys);
			}

//*** lets check if we have new taxonomies added in woocommerce or new item
			foreach (array_merge($this->items_keys, $taxonomies_keys) as $key) {
			    if (!in_array($key, $items_order)) {
				$items_order[] = $key;
			    }
			}

//lets print our items and taxonomies
			foreach ($items_order as $key) {
			    if (in_array($key, $this->items_keys)) {
				woof_print_item_by_key($key, $woof_settings);
			    } else {
				if (isset($taxonomies[$key])) {
				    woof_print_tax($key, $taxonomies[$key], $woof_settings);
				}
			    }
			}
			?>
                    </ul>

                    <input type="button" class="woof_reset_order" style="float: right;" value="<?php _e('Reset items order', 'woocommerce-products-filter') ?>" />

                    <div class="clear"></div>

                </section>

                <section id="tabs-2">

		    <?php woocommerce_admin_fields($this->get_options()); ?>

                </section>

                <section id="tabs-3">

		    <?php
		    $skins = array(
			'none' => array('none'),
			'flat' => array(
			    'flat_aero',
			    'flat_blue',
			    'flat_flat',
			    'flat_green',
			    'flat_grey',
			    'flat_orange',
			    'flat_pink',
			    'flat_purple',
			    'flat_red',
			    'flat_yellow'
			),
			'minimal' => array(
			    'minimal_aero',
			    'minimal_blue',
			    'minimal_green',
			    'minimal_grey',
			    'minimal_minimal',
			    'minimal_orange',
			    'minimal_pink',
			    'minimal_purple',
			    'minimal_red',
			    'minimal_yellow'
			),
			'square' => array(
			    'square_aero',
			    'square_blue',
			    'square_green',
			    'square_grey',
			    'square_orange',
			    'square_pink',
			    'square_purple',
			    'square_red',
			    'square_yellow',
			    'square_square'
			)
		    );
		    $skin = 'none';
		    if (isset($woof_settings['icheck_skin'])) {
			$skin = $woof_settings['icheck_skin'];
		    }
		    ?>

                    <div class="woof-control-section">

                        <h4><?php _e('Radio and checkboxes skin', 'woocommerce-products-filter') ?></h4>

                        <div class="woof-control-container">
                            <div class="woof-control">

                                <select name="woof_settings[icheck_skin]" class="chosen_select">
				    <?php foreach ($skins as $key => $schemes) : ?>
    				    <optgroup label="<?php echo $key ?>">
					    <?php foreach ($schemes as $scheme) : ?>
						<option value="<?php echo $scheme; ?>" <?php if ($skin == $scheme): ?>selected="selected"<?php endif; ?>><?php echo $scheme; ?></option>
					    <?php endforeach; ?>
    				    </optgroup>
				    <?php endforeach; ?>
                                </select>

                            </div>
                            <div class="woof-description"></div>
                        </div>

                    </div><!--/ .woof-control-section-->

		    <?php
		    $skins = array(
			'default' => __('Default', 'woocommerce-products-filter'),
			'plainoverlay' => __('Plainoverlay - CSS', 'woocommerce-products-filter'),
			'loading-balls' => __('Loading balls - SVG', 'woocommerce-products-filter'),
			'loading-bars' => __('Loading bars - SVG', 'woocommerce-products-filter'),
			'loading-bubbles' => __('Loading bubbles - SVG', 'woocommerce-products-filter'),
			'loading-cubes' => __('Loading cubes - SVG', 'woocommerce-products-filter'),
			'loading-cylon' => __('Loading cyclone - SVG', 'woocommerce-products-filter'),
			'loading-spin' => __('Loading spin - SVG', 'woocommerce-products-filter'),
			'loading-spinning-bubbles' => __('Loading spinning bubbles - SVG', 'woocommerce-products-filter'),
			'loading-spokes' => __('Loading spokes - SVG', 'woocommerce-products-filter'),
		    );
		    if (!isset($woof_settings['overlay_skin'])) {
			$woof_settings['overlay_skin'] = 'default';
		    }
		    $skin = $woof_settings['overlay_skin'];
		    ?>


                    <div class="woof-control-section">

                        <h4><?php _e('Overlay skins', 'woocommerce-products-filter') ?></h4>

                        <div class="woof-control-container">
                            <div class="woof-control">

                                <select name="woof_settings[overlay_skin]" class="chosen_select">
				    <?php foreach ($skins as $scheme => $title) : ?>
    				    <option value="<?php echo $scheme; ?>" <?php if ($skin == $scheme): ?>selected="selected"<?php endif; ?>><?php echo $title; ?></option>
				    <?php endforeach; ?>
                                </select>

                            </div>
                            <div class="woof-description">

                            </div>
                        </div>

                    </div><!--/ .woof-control-section-->

		    <?php
		    if (!isset($woof_settings['overlay_skin_bg_img'])) {
			$woof_settings['overlay_skin_bg_img'] = '';
		    }
		    $overlay_skin_bg_img = $woof_settings['overlay_skin_bg_img'];
		    ?>


                    <div class="woof-control-section" <?php if ($skin == 'default'): ?>style="display: none;"<?php endif; ?>>

                        <h4><?php _e('Overlay image background', 'woocommerce-products-filter') ?></h4>

                        <div class="woof-control-container">
                            <div class="woof-control woof-upload-style-wrap">

                                <input type="text" name="woof_settings[overlay_skin_bg_img]" value="<?php echo $overlay_skin_bg_img ?>" />

                                <a href="#" class="woof-button woof_select_image"><?php _e('Select Image', 'woocommerce-products-filter') ?></a><br />

                                <div <?php if ($skin != 'plainoverlay'): ?>style="display: none;"<?php endif; ?>>
                                    <br />
				    <?php
				    if (!isset($woof_settings['plainoverlay_color'])) {
					$woof_settings['plainoverlay_color'] = '';
				    }
				    $plainoverlay_color = $woof_settings['plainoverlay_color'];
				    ?>

                                    <h4<?php _e('Plainoverlay color', 'woocommerce-products-filter') ?></h4>
                                    <input type="text" name="woof_settings[plainoverlay_color]" value="<?php echo $plainoverlay_color ?>" id="woof_color_picker_plainoverlay_color" class="woof-color-picker" />

                                </div>

                            </div>
                            <div class="woof-description">
                                <p class="description">
				    <?php _e('Example', 'woocommerce-products-filter') ?>: <?php echo WOOF_LINK ?>img/overlay_bg.png
                                </p>
                            </div>
                        </div>

                    </div><!--/ .woof-control-section-->


                    <div class="woof-control-section" <?php if ($skin != 'default'): ?>style="display: none;"<?php endif; ?>>

                        <h4><?php _e('Loading word', 'woocommerce-products-filter') ?></h4>

                        <div class="woof-control-container">

                            <div class="woof-control woof-upload-style-wrap">

				<?php
				if (!isset($woof_settings['default_overlay_skin_word'])) {
				    $woof_settings['default_overlay_skin_word'] = '';
				}
				$default_overlay_skin_word = $woof_settings['default_overlay_skin_word'];
				?>



                                <input type="text" name="woof_settings[default_overlay_skin_word]" value="<?php echo $default_overlay_skin_word ?>" />


                            </div>
                            <div class="woof-description">
                                <p class="description">
				    <?php _e('Word while searching is going on front when "Overlay skins" is default.', 'woocommerce-products-filter') ?>
                                </p>
                            </div>
                        </div>
                    </div><!--/ .woof-control-section-->



                    <div class="woof-control-section">

                        <h4><?php _e('Use chosen', 'woocommerce-products-filter') ?></h4>

                        <div class="woof-control-container">

                            <div class="woof-control woof-upload-style-wrap">

				<?php
				$chosen_selects = array(
				    0 => __('No', 'woocommerce-products-filter'),
				    1 => __('Yes', 'woocommerce-products-filter')
				);

				if (!isset($woof_settings['use_chosen'])) {
				    $woof_settings['use_chosen'] = 1;
				}
				$chosen_select = $woof_settings['use_chosen'];
				?>

                                <div class="select-wrap">
                                    <select name="woof_settings[use_chosen]" class="chosen_select">
					<?php foreach ($chosen_selects as $key => $value) : ?>
    					<option value="<?php echo $key; ?>" <?php if ($chosen_select == $key): ?>selected="selected"<?php endif; ?>><?php echo $value; ?></option>
					<?php endforeach; ?>
                                    </select>
                                </div>


                            </div>
                            <div class="woof-description">
                                <p class="description">
				    <?php _e('Use chosen javascript library on the front of your site for drop-downs.', 'woocommerce-products-filter') ?>
                                </p>
                            </div>
                        </div>
                    </div><!--/ .woof-control-section-->


                    <div class="woof-control-section">

                        <h4><?php _e('Use beauty scroll', 'woocommerce-products-filter') ?></h4>

                        <div class="woof-control-container">

                            <div class="woof-control woof-upload-style-wrap">

				<?php
				$use_beauty_scroll = array(
				    0 => __('No', 'woocommerce-products-filter'),
				    1 => __('Yes', 'woocommerce-products-filter')
				);

				if (!isset($woof_settings['use_beauty_scroll'])) {
				    $woof_settings['use_beauty_scroll'] = 0;
				}
				$use_scroll = $woof_settings['use_beauty_scroll'];
				?>

                                <div class="select-wrap">
                                    <select name="woof_settings[use_beauty_scroll]" class="chosen_select">
					<?php foreach ($use_beauty_scroll as $key => $value) : ?>
    					<option value="<?php echo $key; ?>" <?php if ($use_scroll == $key): ?>selected="selected"<?php endif; ?>><?php echo $value; ?></option>
					<?php endforeach; ?>
                                    </select>
                                </div>


                            </div>
                            <div class="woof-description">
                                <p class="description">
				    <?php _e('Use beauty scroll when you apply max height for taxonomy block on the front', 'woocommerce-products-filter') ?>
                                </p>
                            </div>
                        </div>
                    </div><!--/ .woof-control-section-->


                    <div class="woof-control-section">

                        <h4><?php _e('Range-slider skin', 'woocommerce-products-filter') ?></h4>

                        <div class="woof-control-container">

                            <div class="woof-control woof-upload-style-wrap">

				<?php
				$skins = array(
				    'skinNice' => 'skinNice',
				    'skinFlat' => 'skinFlat',
				    'skinHTML5' => 'skinHTML5',
				    'skinModern' => 'skinModern',
				    'skinSimple' => 'skinSimple'
				);

				if (!isset($woof_settings['ion_slider_skin'])) {
				    $woof_settings['ion_slider_skin'] = 'skinNice';
				}
				$skin = $woof_settings['ion_slider_skin'];
				?>

                                <div class="select-wrap">
                                    <select name="woof_settings[ion_slider_skin]" class="chosen_select">
					<?php foreach ($skins as $key => $value) : ?>
    					<option value="<?php echo $key; ?>" <?php if ($skin == $key): ?>selected="selected"<?php endif; ?>><?php echo $value; ?></option>
					<?php endforeach; ?>
                                    </select>
                                </div>


                            </div>
                            <div class="woof-description">
                                <p class="description">
				    <?php _e('Ion-Range slider js lib skin for range-sliders of the plugin', 'woocommerce-products-filter') ?>
                                </p>
                            </div>
                        </div>
                    </div><!--/ .woof-control-section-->



		    <?php if (get_option('woof_set_automatically')): ?>
    		    <div class="woof-control-section">

    			<h4><?php _e('Hide auto filter by default', 'woocommerce-products-filter') ?></h4>

    			<div class="woof-control-container">
    			    <div class="woof-control">

				    <?php
				    $woof_auto_hide_button = array(
					0 => __('No', 'woocommerce-products-filter'),
					1 => __('Yes', 'woocommerce-products-filter')
				    );
				    if (!isset($woof_settings['woof_auto_hide_button'])) {
					$woof_settings['woof_auto_hide_button'] = 0;
				    }
				    $woof_auto_hide_button_val = $woof_settings['woof_auto_hide_button'];
				    ?>

    				<select name="woof_settings[woof_auto_hide_button]" class="chosen_select">
					<?php foreach ($woof_auto_hide_button as $v => $n) : ?>
					    <option value="<?php echo $v; ?>" <?php if ($woof_auto_hide_button_val == $v): ?>selected="selected"<?php endif; ?>><?php echo $n; ?></option>
					<?php endforeach; ?>
    				</select>

    			    </div>
    			    <div class="woof-description">
    				<p class="description"><?php _e('If in options tab option "Set filter automatically" is "Yes" you can hide filter and show hide/show button instead of it.', 'woocommerce-products-filter') ?></p>
    			    </div>
    			</div>

    		    </div><!--/ .woof-control-section-->

		    <?php endif; ?>

		    <?php
		    if (!isset($woof_settings['woof_auto_hide_button_img'])) {
			$woof_settings['woof_auto_hide_button_img'] = '';
		    }

		    if (!isset($woof_settings['woof_auto_hide_button_txt'])) {
			$woof_settings['woof_auto_hide_button_txt'] = '';
		    }
		    ?>

                    <div class="woof-control-section">

                        <h4><?php _e('Auto filter close/open image', 'woocommerce-products-filter') ?></h4>

                        <div class="woof-control-container">
                            <div class="woof-control woof-upload-style-wrap">
                                <input type="text" name="woof_settings[woof_auto_hide_button_img]" value="<?php echo $woof_settings['woof_auto_hide_button_img'] ?>" />
                                <a href="#" class="woof-button woof_select_image"><?php _e('Select Image', 'woocommerce-products-filter') ?></a>
                            </div>
                            <div class="woof-description">
                                <p class="description"><?php _e('Image which displayed instead filter while it is closed if selected. Write "none" here if you want to use text only!', 'woocommerce-products-filter') ?></p>
                            </div>
                        </div>

                    </div><!--/ .woof-control-section-->


                    <div class="woof-control-section">

                        <h4><?php _e('Auto filter close/open text', 'woocommerce-products-filter') ?></h4>

                        <div class="woof-control-container">
                            <div class="woof-control">
                                <input type="text" name="woof_settings[woof_auto_hide_button_txt]" value="<?php echo $woof_settings['woof_auto_hide_button_txt'] ?>" />
                            </div>
                            <div class="woof-description">
                                <p class="description"><?php _e('Text which displayed instead filter while it is closed if selected.', 'woocommerce-products-filter') ?></p>
                            </div>
                        </div>

                    </div><!--/ .woof-control-section-->

                    <div class="woof-control-section">

                        <h4><?php _e('Image for subcategories [<i>open</i>]', 'woocommerce-products-filter') ?></h4>

                        <div class="woof-control-container">
                            <div class="woof-control woof-upload-style-wrap">
                                <input type="text" name="woof_settings[woof_auto_subcats_plus_img]" value="<?php echo(isset($woof_settings['woof_auto_subcats_plus_img']) ? $woof_settings['woof_auto_subcats_plus_img'] : '') ?>" />
                                <a href="#" class="woof-button woof_select_image"><?php _e('Select Image', 'woocommerce-products-filter') ?></a>
                            </div>
                            <div class="woof-description">
                                <p class="description"><?php _e('Image when you select in tab Options "Hide childs in checkboxes and radio". By default it is green cross.', 'woocommerce-products-filter') ?></p>
                            </div>
                        </div>

                        <h4><?php _e('Image for subcategories [<i>close</i>]', 'woocommerce-products-filter') ?></h4>

                        <div class="woof-control-container">
                            <div class="woof-control woof-upload-style-wrap">
                                <input type="text" name="woof_settings[woof_auto_subcats_minus_img]" value="<?php echo(isset($woof_settings['woof_auto_subcats_minus_img']) ? $woof_settings['woof_auto_subcats_minus_img'] : '') ?>" />
                                <a href="#" class="woof-button woof_select_image"><?php _e('Select Image', 'woocommerce-products-filter') ?></a>
                            </div>
                            <div class="woof-description">
                                <p class="description"><?php _e('Image when you select in tab Options "Hide childs in checkboxes and radio". By default it is green minus.', 'woocommerce-products-filter') ?></p>
                            </div>
                        </div>

                    </div><!--/ .woof-control-section-->


                    <div class="woof-control-section">

                        <h4><?php _e('Toggle block type', 'woocommerce-products-filter') ?></h4>

                        <div class="woof-control-container">

                            <div class="woof-control woof-upload-style-wrap">

				<?php
				$toggle_types = array(
				    'text' => __('Text', 'woocommerce-products-filter'),
				    'image' => __('Images', 'woocommerce-products-filter')
				);

				if (!isset($woof_settings['toggle_type'])) {
				    $woof_settings['toggle_type'] = 'text';
				}
				$toggle_type = $woof_settings['toggle_type'];
				?>

                                <div class="select-wrap">
                                    <select name="woof_settings[toggle_type]" class="chosen_select" id="toggle_type">
					<?php foreach ($toggle_types as $key => $value) : ?>
    					<option value="<?php echo $key; ?>" <?php if ($toggle_type == $key): ?>selected="selected"<?php endif; ?>><?php echo $value; ?></option>
					<?php endforeach; ?>
                                    </select>
                                </div>


                            </div>
                            <div class="woof-description">
                                <p class="description">
				    <?php _e('Type of the toogle on the front for block of html-items as: radio, checkbox .... Works only if the block title is not hidden!', 'woocommerce-products-filter') ?>
                                </p>
                            </div>
                        </div>

                        <div class="toggle_type_text" <?php if ($toggle_type == 'image'): ?>style="display: none;"<?php endif; ?>>

                            <h4><?php _e('Text for block toggle [<i>opened</i>]', 'woocommerce-products-filter') ?></h4>

                            <div class="woof-control-container">
                                <div class="woof-control woof-upload-style-wrap">
				    <?php
				    if (!isset($woof_settings['toggle_opened_text'])) {
					$woof_settings['toggle_opened_text'] = '';
				    }
				    ?>
                                    <input type="text" name="woof_settings[toggle_opened_text]" value="<?php echo $woof_settings['toggle_opened_text'] ?>" />
                                </div>
                                <div class="woof-description">
                                    <p class="description"><?php _e('Toggle text for opened html-items block. Example: close. By default applied sign minus "-"', 'woocommerce-products-filter') ?></p>
                                </div>
                            </div>

                            <h4><?php _e('Text for block toggle [<i>closed</i>]', 'woocommerce-products-filter') ?></h4>

                            <div class="woof-control-container">
                                <div class="woof-control woof-upload-style-wrap">
				    <?php
				    if (!isset($woof_settings['toggle_closed_text'])) {
					$woof_settings['toggle_closed_text'] = '';
				    }
				    ?>
                                    <input type="text" name="woof_settings[toggle_closed_text]" value="<?php echo $woof_settings['toggle_closed_text'] ?>" />
                                </div>
                                <div class="woof-description">
                                    <p class="description"><?php _e('Toggle text for closed html-items block. Example: open. By default applied sign plus "+"', 'woocommerce-products-filter') ?></p>
                                </div>
                            </div>

                        </div>


                        <div class="toggle_type_image" <?php if ($toggle_type == 'text'): ?>style="display: none;"<?php endif; ?>>
                            <h4><?php _e('Image for block toggle [<i>opened</i>]', 'woocommerce-products-filter') ?></h4>

                            <div class="woof-control-container">
                                <div class="woof-control woof-upload-style-wrap">
				    <?php
				    if (!isset($woof_settings['toggle_opened_image'])) {
					$woof_settings['toggle_opened_image'] = '';
				    }
				    ?>
                                    <input type="text" name="woof_settings[toggle_opened_image]" value="<?php echo(isset($woof_settings['toggle_opened_image']) ? $woof_settings['toggle_opened_image'] : '') ?>" />
                                    <a href="#" class="woof-button woof_select_image"><?php _e('Select Image', 'woocommerce-products-filter') ?></a>
                                </div>
                                <div class="woof-description">
                                    <p class="description"><?php _e('Any image for opened html-items block 20x20', 'woocommerce-products-filter') ?></p>
                                </div>
                            </div>


                            <h4><?php _e('Image for block toggle [<i>closed</i>]', 'woocommerce-products-filter') ?></h4>

                            <div class="woof-control-container">
                                <div class="woof-control woof-upload-style-wrap">
				    <?php
				    if (!isset($woof_settings['toggle_closed_image'])) {
					$woof_settings['toggle_closed_image'] = '';
				    }
				    ?>
                                    <input type="text" name="woof_settings[toggle_closed_image]" value="<?php echo(isset($woof_settings['toggle_closed_image']) ? $woof_settings['toggle_closed_image'] : '') ?>" />
                                    <a href="#" class="woof-button woof_select_image"><?php _e('Select Image', 'woocommerce-products-filter') ?></a>
                                </div>
                                <div class="woof-description">
                                    <p class="description"><?php _e('Any image for closed html-items block 20x20', 'woocommerce-products-filter') ?></p>
                                </div>
                            </div>
                        </div>


                    </div><!--/ .woof-control-section-->

		    <?php
		    if (!isset($woof_settings['custom_front_css'])) {
			$woof_settings['custom_front_css'] = '';
		    }
		    ?>

                    <div class="woof-control-section">

                        <h4><?php _e('Custom front css styles file link', 'woocommerce-products-filter') ?></h4>

                        <div class="woof-control-container">
                            <div class="woof-control">
                                <input type="text" name="woof_settings[custom_front_css]" value="<?php echo $woof_settings['custom_front_css'] ?>" />
                            </div>
                            <div class="woof-description">
                                <p class="description"><?php _e('For developers who want to rewrite front css of the plugin front side. You are need to know CSS for this!', 'woocommerce-products-filter') ?></p>
                            </div>
                        </div>

                    </div><!--/ .woof-control-section-->

		    <?php do_action('woof_print_design_additional_options'); ?>

                </section>

                <section id="tabs-4">

                    <div class="woof-tabs woof-tabs-style-line">

                        <nav>
                            <ul>
                                <li>
                                    <a href="#tabs-41">
                                        <span><?php _e("Code", 'woocommerce-products-filter') ?></span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#tabs-42">
                                        <span><?php _e("Options", 'woocommerce-products-filter') ?></span>
                                    </a>
                                </li>
                            </ul>
                        </nav>

                        <div class="content-wrap">

                            <section id="tabs-41">

                                <table class="form-table">

                                    <tr>
                                        <th scope="row"><label for="custom_css_code"><?php _e('Custom CSS code', 'woocommerce-products-filter') ?></label></th>

                                        <td>
                                            <textarea class="wide woof_custom_css" id="custom_css_code" style="height: 300px; width: 100%;" name="woof_settings[custom_css_code]"><?php echo(isset($this->settings['custom_css_code']) ? stripcslashes($this->settings['custom_css_code']) : '') ?></textarea>
                                            <p class="description"><?php _e("If you are need to customize something and you don't want to lose your changes after update", 'woocommerce-products-filter') ?></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row"><label for="js_after_ajax_done"><?php _e('JavaScript code after AJAX is done', 'woocommerce-products-filter') ?></label></th>
                                        <td>
                                            <textarea class="wide woof_custom_css" id="js_after_ajax_done" style="height: 300px; width: 100%;" name="woof_settings[js_after_ajax_done]"><?php echo(isset($this->settings['js_after_ajax_done']) ? stripcslashes($this->settings['js_after_ajax_done']) : '') ?></textarea>
                                            <p class="description"><?php _e('Use it when you are need additional action after AJAX redraw your products in shop page or in page with shortcode! For use when you need additional functionality after AJAX redraw of your products on the shop page or on pages with shortcodes.', 'woocommerce-products-filter') ?></p>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th scope="row"><label for="init_only_on"><?php _e('Init plugin on the next site pages only ', 'woocommerce-products-filter') ?></label></th>
                                        <td>
					    <?php
					    if (!isset($this->settings['init_only_on'])) {
						$this->settings['init_only_on'] = '';
					    }
					    ?>
                                            <textarea class="wide woof_custom_css" id="init_only_on" style="height: 300px; width: 100%;" name="woof_settings[init_only_on]"><?php echo stripcslashes(trim($this->settings['init_only_on'])) ?></textarea>
                                            <p class="description"><?php _e('This option excludes initialization of the plugin on all pages of the site except links and link-masks in the textarea. One row - one link (or link-mask)! Example of link: http://site.com/ajaxed-search-7. Example of link-mask: product-category! Leave it empty to allow the plugin initialization on all pages of the site!', 'woocommerce-products-filter') ?></p>
                                        </td>
                                    </tr>


				    <?php if (class_exists('SitePress')): ?>
    				    <tr>
    					<th scope="row"><label for="wpml_tax_labels">
						    <?php _e('WPML taxonomies labels translations', 'woocommerce-products-filter') ?> <img class="help_tip" data-tip="Syntax:
    						     es:Locations^Ubicaciones
    						     es:Size^Tamaño
    						     de:Locations^Lage
    						     de:Size^Größe" src="<?php echo WP_PLUGIN_URL ?>/woocommerce/assets/images/help.png" height="16" width="16" />
    					    </label></th>
    					<td>

						<?php
						$wpml_tax_labels = "";
						if (isset($woof_settings['wpml_tax_labels']) AND is_array($woof_settings['wpml_tax_labels'])) {
						    foreach ($woof_settings['wpml_tax_labels'] as $lang => $words) {
							if (!empty($words) AND is_array($words)) {
							    foreach ($words as $key_word => $translation) {
								$wpml_tax_labels .= $lang . ':' . $key_word . '^' . $translation . PHP_EOL;
							    }
							}
							//$first_value = reset($value); // First Element's Value
							//$first_key = key($value); // First Element's Key
						    }
						}
						?>

    					    <textarea class="wide woof_custom_css" id="wpml_tax_labels" style="height: 300px; width: 100%;" name="woof_settings[wpml_tax_labels]"><?php echo $wpml_tax_labels ?></textarea>
    					    <p class="description"><?php _e('Use it if you can not translate your custom taxonomies labels and attributes labels by another plugins.', 'woocommerce-products-filter') ?></p>

    					</td>
    				    </tr>
				    <?php endif; ?>

                                </table>

                            </section>

                            <section id="tabs-42">

                                <div class="woof-control-section">

                                    <h5><?php _e('Search slug', 'woocommerce-products-filter') ?></h5>

                                    <div class="woof-control-container">
                                        <div class="woof-control">

					    <?php
					    if (!isset($woof_settings['swoof_search_slug'])) {
						$woof_settings['swoof_search_slug'] = '';
					    }
					    ?>

                                            <input placeholder="swoof" type="text" name="woof_settings[swoof_search_slug]" value="<?php echo $woof_settings['swoof_search_slug'] ?>" id="swoof_search_slug" />

                                        </div>
                                        <div class="woof-description">
                                            <p class="description"><?php _e('If you do not like search key "swoof" in the search link you can replace it by your own word. But be care to avoid conflicts with any themes and plugins, + never define it as symbol "s".<br /> Not understood? Simply do not touch it!', 'woocommerce-products-filter') ?></p>
                                        </div>
                                    </div>

                                </div><!--/ .woof-control-section-->

                                <div class="woof-control-section">

                                    <h5><?php _e('Products per page', 'woocommerce-products-filter') ?></h5>

                                    <div class="woof-control-container">
                                        <div class="woof-control">
					    <?php
					    if (!isset($woof_settings['per_page'])) {
						$woof_settings['per_page'] = -1;
					    }
					    ?>

                                            <input type="text" name="woof_settings[per_page]" value="<?php echo $woof_settings['per_page'] ?>" id="per_page" />
                                        </div>
                                        <div class="woof-description">
                                            <p class="description"><?php _e('Products per page when searching is going only. Set here -1 to prevent pagination managing from here!', 'woocommerce-products-filter') ?></p>
                                        </div>
                                    </div>

                                </div><!--/ .woof-control-section-->

                                <div class="woof-control-section">

                                    <h5><?php _e("In the terms slugs uses non-latin characters", 'woocommerce-products-filter') ?></h5>

                                    <div class="woof-control-container">
                                        <div class="woof-control">

					    <?php
					    $non_latin_mode = array(
						0 => __("No", 'woocommerce-products-filter'),
						1 => __("Yes", 'woocommerce-products-filter')
					    );
					    ?>

					    <?php
					    if (!isset($woof_settings['non_latin_mode']) OR empty($woof_settings['non_latin_mode'])) {
						$woof_settings['non_latin_mode'] = 0;
					    }
					    ?>
                                            <div class="select-wrap">
                                                <select name="woof_settings[non_latin_mode]">
						    <?php foreach ($non_latin_mode as $key => $value) : ?>
    						    <option value="<?php echo $key; ?>" <?php if ($woof_settings['non_latin_mode'] == $key): ?>selected="selected"<?php endif; ?>><?php echo $value; ?></option>
						    <?php endforeach; ?>
                                                </select>
                                            </div>

                                        </div>
                                        <div class="woof-description">
                                            <p class="description"><?php _e("If your site taxonomies terms is in: russian, chinese, arabic, hebrew, persian, korean, japanese and any another non-latin characters language - set this option to Yes, better do it instantly after installation, because later if you will activate this option: color options for example - you will have to set them by hands again.", 'woocommerce-products-filter') ?></p>
                                        </div>
                                    </div>

                                </div><!--/ .woof-control-section-->


				<div class="woof-control-section">

                                    <h5><?php _e("Optimize loading of WOOF JavaScript files", 'woocommerce-products-filter') ?></h5>

                                    <div class="woof-control-container">
                                        <div class="woof-control">

					    <?php
					    $non_latin_mode = array(
						0 => __("No", 'woocommerce-products-filter'),
						1 => __("Yes", 'woocommerce-products-filter')
					    );
					    ?>

					    <?php
					    if (!isset($woof_settings['optimize_js_files']) OR empty($woof_settings['optimize_js_files'])) {
						$woof_settings['optimize_js_files'] = 0;
					    }
					    ?>
                                            <div class="select-wrap">
                                                <select name="woof_settings[optimize_js_files]">
						    <?php foreach ($non_latin_mode as $key => $value) : ?>
    						    <option value="<?php echo $key; ?>" <?php if ($woof_settings['optimize_js_files'] == $key): ?>selected="selected"<?php endif; ?>><?php echo $value; ?></option>
						    <?php endforeach; ?>
                                                </select>
                                            </div>

                                        </div>
                                        <div class="woof-description">
                                            <p class="description"><?php _e("This option place WOOF JavaScript files on the site footer. Use it for page loading optimization. Be care with this option, and always after enabling of it test your site frontend!", 'woocommerce-products-filter') ?></p>
                                        </div>
                                    </div>

                                </div><!--/ .woof-control-section-->


                                <!--
                                <div class="woof-control-section">

                                    <h5><?php _e("Storage type", 'woocommerce-products-filter') ?></h5>

                                    <div class="woof-control-container">
                                        <div class="woof-control">

				<?php
				$storage_types = array(
				    'session' => 'session',
				    'transient' => 'transient'
				);
				?>

				<?php
				if (!isset($woof_settings['storage_type']) OR empty($woof_settings['storage_type'])) {
				    $woof_settings['storage_type'] = 'transient';
				}
				?>
                                            <div class="select-wrap">
                                                <select name="woof_settings[storage_type]">
				<?php foreach ($storage_types as $key => $value) : ?>
                                                                                                                                                                                                        <option value="<?php echo $key; ?>" <?php if ($woof_settings['storage_type'] == $key): ?>selected="selected"<?php endif; ?>><?php echo $value; ?></option>
				<?php endforeach; ?>
                                                </select>
                                            </div>

                                        </div>
                                        <div class="woof-description">
                                            <p class="description"><?php _e("If you have troubles with relevant terms recount on categories pages with dynamic recount for not logged in users - select transient.", 'woocommerce-products-filter') ?></p>
                                        </div>
                                    </div>

                                </div> -->

                                <div class="woof-control-section woof_premium_only">

                                    <h5><?php _e("Hide terms count text", 'woocommerce-products-filter') ?></h5>

                                    <div class="woof-control-container">
                                        <div class="woof-control">

					    <?php
					    $hide_terms_count_txt = array(
						0 => __("No", 'woocommerce-products-filter'),
						1 => __("Yes", 'woocommerce-products-filter')
					    );
					    ?>

					    <?php
					    if (!isset($woof_settings['hide_terms_count_txt']) OR empty($woof_settings['hide_terms_count_txt'])) {
						$woof_settings['hide_terms_count_txt'] = 0;
					    }
					    ?>
                                            <div class="select-wrap">
                                                <select name="woof_settings[hide_terms_count_txt]">
						    <?php foreach ($hide_terms_count_txt as $key => $value) : ?>
    						    <option value="<?php echo $key; ?>" <?php if ($woof_settings['hide_terms_count_txt'] == $key): ?>selected="selected"<?php endif; ?>><?php echo $value; ?></option>
						    <?php endforeach; ?>
                                                </select>
                                            </div>

                                        </div>
                                        <div class="woof-description">
                                            <p class="description"><?php _e("If you want show relevant tags on the categories pages you should activate show count, dynamic recount and <b>hide empty terms</b> in the tab Options. But if you do not want show count (number) text near each term - set Yes here.", 'woocommerce-products-filter') ?></p>
                                        </div>
                                    </div>

                                </div><!--/ .woof-control-section-->

                                <div class="woof-control-section">

                                    <h5><?php _e("Listen catalog visibility", 'woocommerce-products-filter') ?></h5>

                                    <div class="woof-control-container">
                                        <div class="woof-control">

					    <?php
					    $listen_catalog_visibility = array(
						0 => __("No", 'woocommerce-products-filter'),
						1 => __("Yes", 'woocommerce-products-filter')
					    );
					    ?>

					    <?php
					    if (!isset($woof_settings['listen_catalog_visibility']) OR empty($woof_settings['listen_catalog_visibility'])) {
						$woof_settings['listen_catalog_visibility'] = 0;
					    }
					    ?>
                                            <div class="select-wrap">
                                                <select name="woof_settings[listen_catalog_visibility]">
						    <?php foreach ($listen_catalog_visibility as $key => $value) : ?>
    						    <option value="<?php echo $key; ?>" <?php if ($woof_settings['listen_catalog_visibility'] == $key): ?>selected="selected"<?php endif; ?>><?php echo $value; ?></option>
						    <?php endforeach; ?>
                                                </select>
                                            </div>

                                        </div>
                                        <div class="woof-description">
                                            <p class="description">
						<?php _e("Listen catalog visibility - options in each product backend page in 'Publish' sidebar widget.", 'woocommerce-products-filter') ?><br />
                                                <a href="<?php echo WOOF_LINK ?>img/plugin_options/listen_catalog_visibility.png" target="_blank"><img src="<?php echo WOOF_LINK ?>img/plugin_options/listen_catalog_visibility.png" width="150" alt="" /></a>
                                            </p>
                                        </div>
                                    </div>

                                </div><!--/ .woof-control-section-->


                                <div class="woof-control-section">

                                    <h5><?php _e("Disable swoof influence", 'woocommerce-products-filter') ?></h5>

                                    <div class="woof-control-container">
                                        <div class="woof-control">

					    <?php
					    $disable_swoof_influence = array(
						0 => __("No", 'woocommerce-products-filter'),
						1 => __("Yes", 'woocommerce-products-filter')
					    );
					    ?>

					    <?php
					    if (!isset($woof_settings['disable_swoof_influence']) OR empty($woof_settings['disable_swoof_influence'])) {
						$woof_settings['disable_swoof_influence'] = 0;
					    }
					    ?>
                                            <div class="select-wrap">
                                                <select name="woof_settings[disable_swoof_influence]">
						    <?php foreach ($disable_swoof_influence as $key => $value) : ?>
    						    <option value="<?php echo $key; ?>" <?php if ($woof_settings['disable_swoof_influence'] == $key): ?>selected="selected"<?php endif; ?>><?php echo $value; ?></option>
						    <?php endforeach; ?>
                                                </select>
                                            </div>

                                        </div>
                                        <div class="woof-description">
                                            <p class="description"><?php _e("Sometimes code '<code>wp_query->is_post_type_archive = true</code>' does not necessary. Try to disable this and try woof-search on your site. If all is ok - leave its disabled. Disabled code by this option you can find in index.php by mark disable_swoof_influence.", 'woocommerce-products-filter') ?></p>
                                        </div>
                                    </div>

                                </div><!--/ .woof-control-section-->

                                <div class="woof-control-section">

                                    <h5><?php _e("Cache dynamic recount number for each item in filter", 'woocommerce-products-filter') ?></h5>

                                    <div class="woof-control-container">
                                        <div class="woof-control">

					    <?php
					    $cache_count_data = array(
						0 => __("No", 'woocommerce-products-filter'),
						1 => __("Yes", 'woocommerce-products-filter')
					    );
					    ?>

					    <?php
					    if (!isset($woof_settings['cache_count_data']) OR empty($woof_settings['cache_count_data'])) {
						$woof_settings['cache_count_data'] = 0;
					    }
					    ?>
                                            <div class="select-wrap">
                                                <select name="woof_settings[cache_count_data]">
						    <?php foreach ($cache_count_data as $key => $value) : ?>
    						    <option value="<?php echo $key; ?>" <?php if ($woof_settings['cache_count_data'] == $key): ?>selected="selected"<?php endif; ?>><?php echo $value; ?></option>
						    <?php endforeach; ?>
                                                </select>
                                            </div>

					    <?php if ($woof_settings['cache_count_data']): ?>
    					    <br />
    					    <br /><a href="#" class="button js_cache_count_data_clear"><?php _e("clear cache", 'woocommerce-products-filter') ?></a>&nbsp;<span style="color: green"></span><br />
    					    <br />
						<?php
						$clean_period = 0;
						if (isset($this->settings['cache_count_data_auto_clean'])) {
						    $clean_period = $this->settings['cache_count_data_auto_clean'];
						}
						$periods = array(
						    0 => __("do not clean cache automatically", 'woocommerce-products-filter'),
						    'hourly' => __("clean cache automatically hourly", 'woocommerce-products-filter'),
						    'twicedaily' => __("clean cache automatically twicedaily", 'woocommerce-products-filter'),
						    'daily' => __("clean cache automatically daily", 'woocommerce-products-filter'),
						    'days2' => __("clean cache automatically each 2 days", 'woocommerce-products-filter'),
						    'days3' => __("clean cache automatically each 3 days", 'woocommerce-products-filter'),
						    'days4' => __("clean cache automatically each 4 days", 'woocommerce-products-filter'),
						    'days5' => __("clean cache automatically each 5 days", 'woocommerce-products-filter'),
						    'days6' => __("clean cache automatically each 6 days", 'woocommerce-products-filter'),
						    'days7' => __("clean cache automatically each 7 days", 'woocommerce-products-filter')
						);
						?>
    					    <div class="select-wrap">
    						<select name="woof_settings[cache_count_data_auto_clean]">
							<?php foreach ($periods as $key => $txt): ?>
							    <option <?php selected($clean_period, $key) ?> value="<?php echo $key ?>"><?php echo $txt; ?></option>
							<?php endforeach; ?>
    						</select>
    					    </div>

					    <?php endif; ?>

                                        </div>
                                        <div class="woof-description">

					    <?php
					    global $wpdb;

					    $charset_collate = '';
					    if (method_exists($wpdb, 'has_cap') AND $wpdb->has_cap('collation')) {
						if (!empty($wpdb->charset)) {
						    $charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
						}
						if (!empty($wpdb->collate)) {
						    $charset_collate .= " COLLATE $wpdb->collate";
						}
					    }
					    //***
					    $sql = "CREATE TABLE IF NOT EXISTS `" . WOOF::$query_cache_table . "` (
                                    `mkey` varchar(64) NOT NULL,
                                    `mvalue` text NOT NULL,
				    KEY `mkey` (`mkey`)
                                  ) {$charset_collate}";

					    if ($wpdb->query($sql) === false) {
						?>
    					    <p class="description"><?php _e("WOOF cannot create the database table! Make sure that your mysql user has the CREATE privilege! Do it manually using your host panel&phpmyadmin!", 'woocommerce-products-filter') ?></p>
    					    <code><?php echo $sql; ?></code>
    					    <input type="hidden" name="woof_settings[cache_count_data]" value="0" />
						<?php
						echo $wpdb->last_error;
					    }
					    ?>

                                            <p class="description"><?php _e("Useful thing when you already set your site IN THE PRODUCTION MODE and use dynamic recount -> it make recount very fast! Of course if you added new products which have to be in search results you have to clean this cache OR you can set time period for auto cleaning!", 'woocommerce-products-filter') ?></p>
                                        </div>
                                    </div>

                                </div><!--/ .woof-control-section-->



                                <div class="woof-control-section">

                                    <h5><?php _e("Cache terms", 'woocommerce-products-filter') ?></h5>

                                    <div class="woof-control-container">
                                        <div class="woof-control">

					    <?php
					    $cache_terms = array(
						0 => __("No", 'woocommerce-products-filter'),
						1 => __("Yes", 'woocommerce-products-filter')
					    );
					    ?>

					    <?php
					    if (!isset($woof_settings['cache_terms']) OR empty($woof_settings['cache_terms'])) {
						$woof_settings['cache_terms'] = 0;
					    }
					    ?>
                                            <div class="select-wrap">
                                                <select name="woof_settings[cache_terms]">
						    <?php foreach ($cache_terms as $key => $value) : ?>
    						    <option value="<?php echo $key; ?>" <?php if ($woof_settings['cache_terms'] == $key): ?>selected="selected"<?php endif; ?>><?php echo $value; ?></option>
						    <?php endforeach; ?>
                                                </select>
                                            </div>

					    <?php if ($woof_settings['cache_terms']): ?>
    					    <br />
    					    <br /><a href="#" class="button js_cache_terms_clear"><?php _e("clear terms cache", 'woocommerce-products-filter') ?></a>&nbsp;<span style="color: green"></span><br />
    					    <br />
						<?php
						$clean_period = 0;
						if (isset($this->settings['cache_terms_auto_clean'])) {
						    $clean_period = $this->settings['cache_terms_auto_clean'];
						}
						$periods = array(
						    0 => __("do not clean cache automatically", 'woocommerce-products-filter'),
						    'hourly' => __("clean cache automatically hourly", 'woocommerce-products-filter'),
						    'twicedaily' => __("clean cache automatically twicedaily", 'woocommerce-products-filter'),
						    'daily' => __("clean cache automatically daily", 'woocommerce-products-filter'),
						    'days2' => __("clean cache automatically each 2 days", 'woocommerce-products-filter'),
						    'days3' => __("clean cache automatically each 3 days", 'woocommerce-products-filter'),
						    'days4' => __("clean cache automatically each 4 days", 'woocommerce-products-filter'),
						    'days5' => __("clean cache automatically each 5 days", 'woocommerce-products-filter'),
						    'days6' => __("clean cache automatically each 6 days", 'woocommerce-products-filter'),
						    'days7' => __("clean cache automatically each 7 days", 'woocommerce-products-filter')
						);
						?>
    					    <div class="select-wrap">
    						<select name="woof_settings[cache_terms_auto_clean]">
							<?php foreach ($periods as $key => $txt): ?>
							    <option <?php selected($clean_period, $key) ?> value="<?php echo $key ?>"><?php echo $txt; ?></option>
							<?php endforeach; ?>
    						</select>
    					    </div>

					    <?php endif; ?>

                                        </div>
                                        <div class="woof-description">
                                            <p class="description"><?php _e("Useful thing when you already set your site IN THE PRODUCTION MODE - its getting terms for filter faster without big MySQL queries! If you actively adds new terms every day or week you can set cron period for cleaning. Another way set: '<b>not clean cache automatically</b>'!", 'woocommerce-products-filter') ?></p>
                                        </div>
                                    </div>

                                </div><!--/ .woof-control-section-->

                                <div class="woof-control-section">

                                    <h5><?php _e("Show blocks helper button", 'woocommerce-products-filter') ?></h5>

                                    <div class="woof-control-container">
                                        <div class="woof-control">

					    <?php
					    $show_woof_edit_view = array(
						0 => __("No", 'woocommerce-products-filter'),
						1 => __("Yes", 'woocommerce-products-filter')
					    );
					    ?>

					    <?php
					    if (!isset($woof_settings['show_woof_edit_view'])) {
						$woof_settings['show_woof_edit_view'] = 1;
					    }
					    ?>
                                            <div class="select-wrap">
                                                <select id="show_woof_edit_view" name="woof_settings[show_woof_edit_view]">
						    <?php foreach ($show_woof_edit_view as $key => $value) : ?>
    						    <option value="<?php echo $key; ?>" <?php if ($woof_settings['show_woof_edit_view'] == $key): ?>selected="selected"<?php endif; ?>><?php echo $value; ?></option>
						    <?php endforeach; ?>
                                                </select>
                                            </div>

                                        </div>
                                        <div class="woof-description">
                                            <p class="description"><?php _e("Show helper button for shortcode [woof] on the front when 'Set filter automatically' is Yes", 'woocommerce-products-filter') ?></p>
                                        </div>
                                    </div>

                                </div><!--/ .woof-control-section-->

                                <div class="woof-control-section">

                                    <h5><?php _e('Custom extensions folder', 'woocommerce-products-filter') ?></h5>

                                    <div class="woof-control-container">
                                        <div class="woof-control">
					    <?php
					    if (!isset($woof_settings['custom_extensions_path'])) {
						$woof_settings['custom_extensions_path'] = '';
					    }
					    ?>

                                            <input type="text" name="woof_settings[custom_extensions_path]" value="<?php echo $woof_settings['custom_extensions_path'] ?>" id="custom_extensions_path" placeholder="Example: my_woof_extensions" />
                                        </div>
                                        <div class="woof-description">
                                            <p class="description"><?php printf(__('Custom extensions folder path relative to: %s', 'woocommerce-products-filter'), WP_CONTENT_DIR . DIRECTORY_SEPARATOR) ?></p>
                                        </div>
                                    </div>

                                </div><!--/ .woof-control-section-->

                            </section>

                        </div>

                    </div>

                </section>



		<?php
		if (!empty(WOOF_EXT::$includes['applications'])) {
		    foreach (WOOF_EXT::$includes['applications'] as $obj) {
			$dir1 = $this->get_custom_ext_path() . $obj->folder_name;
			$dir2 = WOOF_EXT_PATH . $obj->folder_name;
			$checked1 = WOOF_EXT::is_ext_activated($dir1);
			$checked2 = WOOF_EXT::is_ext_activated($dir2);
			if ($checked1 OR $checked2) {
			    do_action('woof_print_applications_tabs_content_' . $obj->folder_name);
			}
		    }
		}
		?>



                <section id="tabs-6">

                    <div class="woof-tabs woof-tabs-style-line">

                        <nav>
                            <ul>
                                <li>
                                    <a href="#tabs-61">
                                        <span><?php _e("Extensions", 'woocommerce-products-filter') ?></span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#tabs-62">
                                        <span><?php _e("Ext-Applications options", 'woocommerce-products-filter') ?></span>
                                    </a>
                                </li>
                            </ul>
                        </nav>

                        <div class="content-wrap">


                            <section id="tabs-61">

                                <div class="select-wrap">
                                    <select id="woof_manipulate_with_ext">
                                        <option value="0"><?php _e('All', 'woocommerce-products-filter') ?></option>
                                        <option value="1"><?php _e('Enabled', 'woocommerce-products-filter') ?></option>
                                        <option value="2"><?php _e('Disabled', 'woocommerce-products-filter') ?></option>
                                    </select>
                                </div>

                                <input type="hidden" name="woof_settings[activated_extensions]" value="" />


				<?php if (true): ?>


    				<!-- ----------------------------------------- -->
				    <?php if (isset($this->settings['custom_extensions_path']) AND ! empty($this->settings['custom_extensions_path'])): ?>

					<br />
					<hr />
					<h3><?php _e('Custom extensions installation', 'woocommerce-products-filter') ?></h3>

					<?php
					$is_custom_extensions = false;
					if (is_dir($this->get_custom_ext_path())) {
					    //$dir_writable = substr(sprintf('%o', fileperms($this->get_custom_ext_path())), -4) == "0774" ? true : false;
					    $dir_writable = is_writable($this->get_custom_ext_path());
					    if ($dir_writable) {
						$is_custom_extensions = true;
					    }
					} else {
					    if (!empty($this->settings['custom_extensions_path'])) {
						//ext dir auto creation
						$dir = $this->get_custom_ext_path();
						try {
						    mkdir($dir, 0777);
						    $dir_writable = is_writable($this->get_custom_ext_path());
						    if ($dir_writable) {
							$is_custom_extensions = true;
						    }
						} catch (Exception $e) {
						    //***
						}
					    }
					}
					//***
					if ($is_custom_extensions):
					    ?>
	    				<input type="button" id="upload-btn" class="button" value="<?php _e('Choose an extension zip', 'woocommerce-products-filter') ?>">
	    				<span style="padding-left:5px;vertical-align:middle;"><i><?php _e('(zip)', 'woocommerce-products-filter') ?></i></span>

	    				<div id="errormsg" class="clearfix redtext" style="padding-top: 10px;"></div>

	    				<div id="pic-progress-wrap" class="progress-wrap" style="margin-top:10px;margin-bottom:10px;"></div>

	    				<div id="picbox" class="clear" style="padding-top:0px;padding-bottom:10px;"></div>

	    				<script>
	    				    jQuery(function ($) {
	    					woof_init_ext_uploader("<?php echo ABSPATH ?>", "<?php echo $this->get_custom_ext_path() ?>", "<?php echo WOOF_LINK ?>lib/simple-ajax-uploader/action.php");
	    				    });
	    				</script>

					<?php else: ?>
	    				<span style="color:orangered;"><?php printf(__('Note for admin: Folder %s for extensions is not writable OR doesn exists! Ignore this message if you not planning using WOOF custom extensions!', 'woocommerce-products-filter'), $this->get_custom_ext_path()) ?></span>
					<?php endif; ?>
				    <?php else: ?>
					<?php if (!empty($this->settings['custom_extensions_path'])): ?>
	    				<span style="color:orangered;"><?php _e('<b>Note for admin</b>: Create folder for custom extensions in wp-content folder: tab Advanced -> Options -> Custom extensions folder', 'woocommerce-products-filter') ?></span>
					<?php endif; ?>
				    <?php endif; ?>
    				<!-- ----------------------------------------- -->




				    <?php
				    if (!isset($woof_settings['activated_extensions']) OR ! is_array($woof_settings['activated_extensions'])) {
					$woof_settings['activated_extensions'] = array();
				    }
				    ?>
				    <?php if (!empty($extensions) AND is_array($extensions)): ?>


					<ul class="woof_extensions woof_custom_extensions">
					    <?php foreach ($extensions['custom'] as $dir): ?>
						<?php
						//$idx = md5($dir);
						//$checked = in_array($idx, $woof_settings['activated_extensions']);
						$checked = WOOF_EXT::is_ext_activated($dir);
						$idx = WOOF_EXT::get_ext_idx($dir);
						?>
	    				    <li class="woof_ext_li <?php echo($checked ? 'is_enabled' : 'is_disabled'); ?>">
						    <?php
						    $info = array();
						    if (file_exists($dir . DIRECTORY_SEPARATOR . 'info.dat')) {
							$info = WOOF_HELPER::parse_ext_data($dir . DIRECTORY_SEPARATOR . 'info.dat');
						    }
						    ?>
	    					<table style="width: 100%;">
	    					    <tr>
	    						<td style="vertical-align: top;">
	    						    <img style="width: 85px;" src="<?php echo WOOF_LINK ?>img/woof_ext_cover.png" alt="ext cover" /><br />
	    						    <br />
	    						    <span class="woof_ext_ver"><?php
								    if (isset($info['version'])) {
									printf(__('<i>ver.:</i> %s', 'woocommerce-products-filter'), $info['version']);
								    }
								    ?></span>
	    						</td>
	    						<td><div style="width:5px;"></div></td>
	    						<td style="width: 100%; vertical-align: top; position: relative;">
	    						    <a href="#" class="woof_ext_remove" data-title="" data-idx="<?php echo $idx ?>" title="<?php _e('remove extension', 'woocommerce-products-filter') ?>"><img src="<?php echo WOOF_LINK ?>img/delete2.png" alt="<?php _e('remove extension', 'woocommerce-products-filter') ?>" /></a>
								<?php
								if (!empty($info)) {
								    if (!empty($info) AND is_array($info)) {
									?>
		    						    <label for="<?php echo $idx ?>">
		    							<input type="checkbox" id="<?php echo $idx ?>" <?php if (isset($info['status']) AND $info['status'] == 'premium'): ?>disabled="disabled"<?php endif; ?> <?php if ($checked): ?>checked=""<?php endif; ?> value="<?php echo $idx ?>" name="woof_settings[activated_extensions][]" />
									    <?php
									    if (isset($info['link'])) {
										?>
										<a href="<?php echo $info['link'] ?>" class="woof_ext_title" target="_blank"><?php echo $info['title'] ?></a>
										<?php
									    } else {
										echo $info['title'];
									    }
									    ?>
		    						    </label><br />
									<?php
									if (isset($info['description'])) {
									    echo '<br />';
									    echo '<p class="description">' . $info['description'] . '</p>';
									}
								    } else {
									echo $dir;
									echo '<br />';
									_e('You should write extension info in info.dat file!', 'woocommerce-products-filter');
								    }
								} else {
								    printf(__('Looks like its not the WOOF extension here %s!', 'woocommerce-products-filter'), $dir);
								}
								?>
	    						</td>
	    					    </tr>
	    					</table>
	    				    </li>
					    <?php endforeach; ?>
					<?php endif; ?>
    				</ul>
    				<div style="clear: both;"></div>
    				<br />
    				<hr />

				    <?php if (!empty($extensions['default'])): ?>

					<h3><?php _e('Default extensions', 'woocommerce-products-filter') ?></h3>

					<ul class="woof_extensions">
					    <?php foreach ($extensions['default'] as $dir): ?>
						<?php
						//$idx = md5($dir);
						//$checked = in_array($idx, $woof_settings['activated_extensions']);
						$checked = WOOF_EXT::is_ext_activated($dir);
						$idx = WOOF_EXT::get_ext_idx($dir);
						?>
	    				    <li class="woof_ext_li <?php echo($checked ? 'is_enabled' : 'is_disabled'); ?>">
						    <?php
						    $info = array();
						    if (file_exists($dir . DIRECTORY_SEPARATOR . 'info.dat')) {
							$info = WOOF_HELPER::parse_ext_data($dir . DIRECTORY_SEPARATOR . 'info.dat');
						    }
						    ?>
	    					<table style="width: 100%;">
	    					    <tr>
	    						<td style="vertical-align: top;">
	    						    <img style="width: 85px;" src="<?php echo WOOF_LINK ?>img/woof_ext_cover.png" alt="ext cover" /><br />
	    						    <br />
	    						    <span class="woof_ext_ver"><?php
								    if (isset($info['version'])) {
									printf(__('<i>ver.:</i> %s', 'woocommerce-products-filter'), $info['version']);
								    }
								    ?></span>
	    						</td>
	    						<td><div style="width:5px;"></div></td>
	    						<td style="width: 100%;">
								<?php
								if (!empty($info)) {
								    $info = WOOF_HELPER::parse_ext_data($dir . DIRECTORY_SEPARATOR . 'info.dat');
								    if (!empty($info) AND is_array($info)) {
									?>
		    						    <label for="<?php echo $idx ?>">
		    							<input type="checkbox" id="<?php echo $idx ?>" <?php if (isset($info['status']) AND $info['status'] == 'premium'): ?>disabled="disabled"<?php endif; ?> <?php if ($checked): ?>checked=""<?php endif; ?> value="<?php echo $idx ?>" name="woof_settings[activated_extensions][]" />
									    <?php
									    if (isset($info['link'])) {
										?>
										<a href="<?php echo $info['link'] ?>" class="woof_ext_title" target="_blank"><?php echo $info['title'] ?></a>
										<?php
									    } else {
										echo $info['title'];
									    }
									    ?>
		    						    </label><br />
									<?php
									echo '<br />';
									echo '<p class="description">' . $info['description'] . '</p>';
								    } else {
									echo $dir;
									echo '<br />';
									_e('You should write extension info in info.dat file!', 'woocommerce-products-filter');
								    }
								} else {
								    echo $dir;
								}
								?>
	    						</td>
	    					    </tr>
	    					</table>

	    				    </li>
					    <?php endforeach; ?>
					</ul>
				    <?php endif; ?>

				<?php endif; ?>
                                <div class="clear"></div>


                            </section>


                            <section id="tabs-62">

                                <div class="woof-tabs woof-tabs-style-line">

                                    <nav class="woof_ext_nav">
                                        <ul>
					    <?php
					    $is_custom_extensions = false;
					    if (is_dir($this->get_custom_ext_path())) {
						//$dir_writable = substr(sprintf('%o', fileperms($this->get_custom_ext_path())), -4) == "0774" ? true : false;
						$dir_writable = is_writable($this->get_custom_ext_path());
						if ($dir_writable) {
						    $is_custom_extensions = true;
						}
					    }

					    if ($is_custom_extensions) {
						if (!empty(WOOF_EXT::$includes['applications'])) {
						    foreach (WOOF_EXT::$includes['applications'] as $obj) {

							$dir = $this->get_custom_ext_path() . $obj->folder_name;
							//$idx = md5($dir);
							//$checked = in_array($idx, $woof_settings['activated_extensions']);
							$checked = WOOF_EXT::is_ext_activated($dir);
							if (!$checked) {
							    continue;
							}
							?>
	    					    <li>

							    <?php
							    if (file_exists($dir . DIRECTORY_SEPARATOR . 'info.dat')) {
								$info = WOOF_HELPER::parse_ext_data($dir . DIRECTORY_SEPARATOR . 'info.dat');
								if (!empty($info) AND is_array($info)) {
								    $name = $info['title'];
								} else {
								    $name = $obj->folder_name;
								}
							    } else {
								$name = $obj->folder_name;
							    }
							    ?>
	    						<a href="#tabs-<?php echo sanitize_title($obj->folder_name) ?>" title="<?php printf(__("%s", 'woocommerce-products-filter'), $name) ?>">
	    						    <span style="font-size: 11px;"><?php printf(__("%s", 'woocommerce-products-filter'), $name) ?></span>
	    						</a>
	    					    </li>
							<?php
						    }
						}
					    }
					    ?>


                                        </ul>
                                    </nav>


                                    <div class="content-wrap woof_ext_opt">

					<?php
					if ($is_custom_extensions) {
					    if (!empty(WOOF_EXT::$includes['applications'])) {
						foreach (WOOF_EXT::$includes['applications'] as $obj) {

						    $dir = $this->get_custom_ext_path() . $obj->folder_name;
						    //$idx = md5($dir);
						    //$checked = in_array($idx, $woof_settings['activated_extensions']);
						    $checked = WOOF_EXT::is_ext_activated($dir);
						    if (!$checked) {
							continue;
						    }
						    do_action('woof_print_applications_options_' . $obj->folder_name);
						}
					    }
					}
					?>

                                    </div>


                                    <div class="clear"></div>

                                </div>




                            </section>

                        </div>

                    </div>

                </section>



                <section id="tabs-7">

                    <table class="form-table">
                        <tbody>
                            <tr valign="top">
                                <th scope="row"><label><?php _e("Docs", 'woocommerce-products-filter') ?></label></th>
                                <td>

                                    <ul>

                                        <li>
                                            <a class="button" href="http://woocommerce-filter.com/documentation/" target="_blank">WOOF documentation</a>
                                            <a class="button" href="http://www.woocommerce-filter.com/category/faq/" target="_blank">FAQ</a>
                                            <a class="button" href="http://www.woocommerce-filter.com/video-tutorials/" target="_blank" style="border: solid 1px greenyellow;">Video tutorials</a>
                                        </li>

                                    </ul>

                                </td>
                            </tr>

                            <tr valign="top">
                                <th scope="row"><label><?php _e("Demo site", 'woocommerce-products-filter') ?></label></th>
                                <td>

                                    <ul>

                                        <li>
                                            <a href="http://www.demo.woocommerce-filter.com/" target="_blank">WOOF - WooCommerce Products Filter</a>
                                        </li>
                                        <li>
                                            <a href="http://www.woocommerce-filter.com/styles-codes-applied-on-demo-site/" target="_blank"><?php _e("Styles and codes which are applied on the demo site", 'woocommerce-products-filter') ?></a>
                                        </li>

                                    </ul>

                                </td>
                            </tr>

			    <?php if (!$this->is_free_ver): ?>
                                <tr>
                                    <td colspan="2">
                                        <div id="plugin_warning" style="padding: 9px; border: solid red 3px; background: #eee; ">
                                            <div class="plugin_warning_head"><strong style="color: red;">ATTENTION MESSAGE FROM THE PLUGIN AUTHOR TO ALL USERS WHO USES PIRATE VERSION OF THE PLUGIN!</strong>!<br></div>
                                            <br />
                                            GET YOUR COPY OF THE PLUGIN <em> <span style="text-decoration: underline;"><span style="color: #ff0000;"><strong>ONLY</strong></span></span></em> FROM <a href="https://codecanyon.net/item/woof-woocommerce-products-filter/11498469?ref=realmag777" target="_blank"><span style="color: #008000;"><strong>CODECANYON.NET</strong></span></a> OR <span style="color: #008000;"><strong><a href="https://wordpress.org/plugins/woocommerce-products-filter/" target="_blank">WORDPRESS.ORG</a></strong></span> IF YOU DO NOT WANT TO BE AN AFFILIATE OF PORNO VIRUS SITE.<br>
                                            <br>
                                            <strong>DID YOU CATCH A VIRUS DOWNLOADING THE PLUGIN FROM ANOTHER (PIRATE) SITES<span style="color: #ff0000;">?</span></strong> THIS IS YOUR TROUBLES AND <em>DO NOT WRITE TO SUPPORT THAT GOOGLE DOWN YOUR SITE TO ZERO BECAUSE OF &nbsp;PORNO VIRUS</em>!!<br>
                                            <br>
                                            <strong><span style="color: #ff0000;">REMEMBER</span></strong> - if somebody suggesting YOU premium version of the plugin for free - think twenty times before installing it ON YOUR SITE, as it can be trap for it! <strong>DOWNLOAD THE PLUGIN ONLY FROM OFFICIAL SITES TO AVOID THE COLLAPSE OF YOUR BUSINESS</strong>.<br>
                                            <br>
                                            <strong style="color: #ff0000;">Miser pays twice</strong>!<br>
                                            <br>
                                            P.S. Reason of this warning text - emails from the users! Be care!!
                                        </div>
                                    </td>
                                </tr>
			    <?php endif; ?>

                            <tr valign="top">
                                <th scope="row"><label><?php _e("Quick video tutorial", 'woocommerce-products-filter') ?></label></th>
                                <td>

                                    <ul>

                                        <li>
                                            <iframe width="560" height="315" src="https://www.youtube.com/embed/jZPtdWgAxKk" frameborder="0" allowfullscreen></iframe>
                                        </li>

                                    </ul>

                                </td>
                            </tr>


                            <tr valign="top">
                                <th scope="row"><label><?php _e("More video", 'woocommerce-products-filter') ?></label></th>
                                <td>

                                    <ul>

                                        <li>
                                            <a href="http://www.woocommerce-filter.com/video-tutorials/" target="_blank"><?php _e("Video tutorials", 'woocommerce-products-filter') ?></a>
                                        </li>

                                    </ul>

                                </td>
                            </tr>




                            <tr valign="top">
                                <th scope="row"><label><?php _e("Recommended plugins for your site flexibility and features", 'woocommerce-products-filter') ?></label></th>
                                <td>

                                    <ul class="list_plugins">


                                        <li>
                                            <a href="https://wordpress.org/plugins/woocommerce-currency-switcher/" target="_blank"><img src="<?php echo WOOF_LINK ?>img/woocs_banner.jpg" /></a>
                                            <p class="description"><?php _e("WooCommerce Currency Switcher – is the plugin that allows you to switch to different currencies and get their rates converted in the real time!", 'woocommerce-products-filter') ?></p>
                                        </li>

                                        <li>
                                            <a href="https://wordpress.org/plugins/inpost-gallery/" target="_blank">InPost Gallery - flexible photo gallery</a>
                                            <p class="description"><?php _e("Insert Gallery in post, page and custom post types just in two clicks. You can create great galleries for your products.", 'woocommerce-products-filter') ?></p>
                                            <p class="description"><a href="http://www.demo.woocommerce-filter.com/shop/music/woo-single-2/" target="_blank" class="button"><?php _e("Example", 'woocommerce-products-filter') ?></a></p>
                                        </li>


                                        <li>
                                            <a href="https://wordpress.org/plugins/autoptimize/" target="_blank">Autoptimize</a>
                                            <p class="description"><?php _e("It concatenates all scripts and styles, minifies and compresses them, adds expires headers, caches them, and moves styles to the page head, and scripts to the footer", 'woocommerce-products-filter') ?></p>
                                        </li>


                                        <li>
                                            <a href="https://wordpress.org/plugins/pretty-link/" target="_blank">Pretty Link Lite</a>
                                            <p class="description"><?php _e("Shrink, beautify, track, manage and share any URL on or off of your WordPress website. Create links that look how you want using your own domain name!", 'woocommerce-products-filter') ?></p>
                                        </li>

                                        <li>
                                            <a href="https://wordpress.org/plugins/custom-post-type-ui/" target="_blank">Custom Post Type UI</a>
                                            <p class="description"><?php _e("This plugin provides an easy to use interface to create and administer custom post types and taxonomies in WordPress.", 'woocommerce-products-filter') ?></p>
                                        </li>

                                        <li>
                                            <a href="https://wordpress.org/plugins/widget-logic/other_notes/" target="_blank">Widget Logic</a>
                                            <p class="description"><?php _e("Widget Logic lets you control on which pages widgets appear using", 'woocommerce-products-filter') ?></p>
                                        </li>

                                        <li>
                                            <a href="https://wordpress.org/plugins/wp-super-cache/" target="_blank">WP Super Cache</a>
                                            <p class="description"><?php _e("Cache pages, allow to make a lot of search queries on your site without high load on your server!", 'woocommerce-products-filter') ?></p>
                                        </li>


                                        <li>
                                            <a href="https://wordpress.org/plugins/wp-migrate-db/" target="_blank">WP Migrate DB</a>
                                            <p class="description"><?php _e("Exports your database, does a find and replace on URLs and file paths, then allows you to save it to your computer.", 'woocommerce-products-filter') ?></p>
                                        </li>

                                        <li>
                                            <a href="https://wordpress.org/plugins/duplicator/" target="_blank">Duplicator</a>
                                            <p class="description"><?php _e("Duplicate, clone, backup, move and transfer an entire site from one location to another.", 'woocommerce-products-filter') ?></p>
                                        </li>

                                    </ul>

                                </td>
                            </tr>

                            <tr valign="top">
                                <th scope="row"><label><?php _e("Adv", 'woocommerce-products-filter') ?></label></th>
                                <td>

                                    <ul>

                                        <li>
                                            <a href="https://share.payoneer.com/nav/6I2wmtpBuitGE6ZnmaMXLYlP8iriJ-63OMLi3PT8SRGceUjGY1dvEhDyuAGBp91DEmf8ugfF3hkUU1XhP_C6Jg2" target="_blank"><img src="<?php echo WOOF_LINK ?>img/plugin_options/100125.png" alt="" /></a>
                                        </li>

                                    </ul>

                                </td>
                            </tr>

                        </tbody>
                    </table>

                </section>

            </div>

        </div>

        <style type="text/css">
            .form-table th {  width: 300px; }
        </style>

    </section><!--/ .woof-section-->

    <div id="woof-modal-content" style="display: none;">

        <div class="woof_option_container woof_option_all">

            <div class="woof-form-element-container">

                <div class="woof-name-description">
                    <strong><?php _e('Show title label', 'woocommerce-products-filter') ?></strong>
                    <span><?php _e('Show/Hide taxonomy block title on the front', 'woocommerce-products-filter') ?></span>
                </div>

                <div class="woof-form-element">

                    <div class="select-wrap">
                        <select class="woof_popup_option" data-option="show_title_label">
                            <option value="0"><?php _e('No', 'woocommerce-products-filter') ?></option>
                            <option value="1"><?php _e('Yes', 'woocommerce-products-filter') ?></option>
                        </select>
                    </div>

                </div>

            </div>

            <div class="woof-form-element-container">

                <div class="woof-name-description">
                    <strong><?php _e('Show toggle button', 'woocommerce-products-filter') ?></strong>
                    <span><?php _e('Show toggle button near the title on the front above the block of html-items', 'woocommerce-products-filter') ?></span>
                </div>

                <div class="woof-form-element">

                    <div class="select-wrap">
                        <select class="woof_popup_option" data-option="show_toggle_button">
                            <option value="0"><?php _e('No', 'woocommerce-products-filter') ?></option>
                            <option value="1"><?php _e('Yes, show as closed', 'woocommerce-products-filter') ?></option>
                            <option value="2"><?php _e('Yes, show as opened', 'woocommerce-products-filter') ?></option>
                        </select>
                    </div>

                </div>

            </div>

        </div>


        <div class="woof_option_container woof_option_all">

            <div class="woof-form-element-container">

                <div class="woof-name-description">
                    <strong><?php _e('Not toggled terms count', 'woocommerce-products-filter') ?></strong>
                    <span><?php _e('Enter count of terms which should be visible to make all other collapsible. "Show more" button will be appeared. This feature is works with: radio, checkboxes, labels, colors.', 'woocommerce-products-filter') ?></span>
                    <span><?php printf(__('Advanced info is <a href="%s" target="_blank">here</a>', 'woocommerce-products-filter'), 'http://www.woocommerce-filter.com/hook/woof_get_more_less_button_xxxx/') ?></span>
                </div>

                <div class="woof-form-element">
                    <input type="text" class="woof_popup_option regular-text code" data-option="not_toggled_terms_count" placeholder="<?php _e('leave it empty to show all terms', 'woocommerce-products-filter') ?>" value="0" />
                </div>

            </div>

        </div>

        <div class="woof_option_container woof_option_all">

            <div class="woof-form-element-container">

                <div class="woof-name-description">
                    <strong><?php _e('Taxonomy custom label', 'woocommerce-products-filter') ?></strong>
                    <span><?php _e('For example you want to show title of Product Categories as "My Products". Just for your convenience.', 'woocommerce-products-filter') ?></span>
                </div>

                <div class="woof-form-element">
                    <input type="text" class="woof_popup_option regular-text code" data-option="custom_tax_label" placeholder="<?php _e('leave it empty to use native taxonomy name', 'woocommerce-products-filter') ?>" value="0" />
                </div>

            </div>

        </div>

        <div class="woof_option_container woof_option_radio woof_option_checkbox woof_option_label">

            <div class="woof-form-element-container">

                <div class="woof-name-description">
                    <strong><?php _e('Max height of the block', 'woocommerce-products-filter') ?></strong>
                    <span><?php _e('Container max-height (px). 0 means no max-height.', 'woocommerce-products-filter') ?></span>
                </div>

                <div class="woof-form-element">
                    <input type="text" class="woof_popup_option regular-text code" data-option="tax_block_height" placeholder="<?php _e('Max height of  the block', 'woocommerce-products-filter') ?>" value="0" />
                </div>

            </div>

        </div>

        <div class="woof_option_container woof_option_radio woof_option_checkbox">

            <div class="woof-form-element-container">

                <div class="woof-name-description">
                    <strong><?php _e('Display items in a row', 'woocommerce-products-filter') ?></strong>
                    <span><?php _e('Works for radio and checkboxes only. Allows show radio/checkboxes in 1 row!', 'woocommerce-products-filter') ?></span>
                </div>

                <div class="woof-form-element">

                    <div class="select-wrap">
                        <select class="woof_popup_option" data-option="dispay_in_row">
                            <option value="0"><?php _e('No', 'woocommerce-products-filter') ?></option>
                            <option value="1"><?php _e('Yes', 'woocommerce-products-filter') ?></option>
                        </select>
                    </div>

                </div>

            </div>

        </div>

        <!------------- options for extensions ------------------------>

	<?php
	if (!empty(WOOF_EXT::$includes['taxonomy_type_objects'])) {
	    foreach (WOOF_EXT::$includes['taxonomy_type_objects'] as $obj) {
		if (!empty($obj->taxonomy_type_additional_options)) {
		    foreach ($obj->taxonomy_type_additional_options as $key => $option) {
			switch ($option['type']) {
			    case 'select':
				?>
				<div class="woof_option_container woof_option_<?php echo $obj->html_type ?>">

				    <div class="woof-form-element-container">

					<div class="woof-name-description">
					    <strong><?php echo $option['title'] ?></strong>
					    <span><?php echo $option['tip'] ?></span>
					</div>

					<div class="woof-form-element">

					    <div class="select-wrap">
						<select class="woof_popup_option" data-option="<?php echo $key ?>">
						    <?php foreach ($option['options'] as $val => $title): ?>
			    			    <option value="<?php echo $val ?>"><?php echo $title ?></option>
						    <?php endforeach; ?>
						</select>
					    </div>

					</div>

				    </div>

				</div>
				<?php
				break;

			    case 'text':
				?>
				<div class="woof_option_container woof_option_<?php echo $obj->html_type ?>">

				    <div class="woof-form-element-container">

					<div class="woof-name-description">
					    <strong><?php echo $option['title'] ?></strong>
					    <span><?php echo $option['tip'] ?></span>
					</div>

					<div class="woof-form-element">
					    <input type="text" class="woof_popup_option regular-text code" data-option="<?php echo $key ?>" placeholder="<?php echo $option['placeholder'] ?>" value="" />px
					</div>

				    </div>

				</div>
				<?php
				break;

			    case 'image':
				?>
				<div class="woof_option_container woof_option_<?php echo $obj->html_type ?>">

				    <div class="woof-form-element-container">

					<div class="woof-name-description">
					    <strong><?php echo $option['title'] ?></strong>
					    <span><?php echo $option['tip'] ?></span>
					</div>

					<div class="woof-form-element">
					    <input type="text" class="woof_popup_option regular-text code" data-option="<?php echo $key ?>" placeholder="<?php echo $option['placeholder'] ?>" value="" />
					    <a href="#" class="button woof_select_image"><?php _e('select image', 'woocommerce-products-filter') ?></a>
					</div>

				    </div>

				</div>
				<?php
				break;

			    default:
				break;
			}
		    }
		}
	    }
	}
	?>

    </div>

    <div id="woof_ext_tpl" style="display: none;">
        <li class="woof_ext_li is_disabled">

            <table style="width: 100%;">
                <tbody>
                    <tr>
                        <td style="vertical-align: top;">
                            <img alt="ext cover" src="<?php echo WOOF_LINK ?>img/woof_ext_cover.png" style="width: 85px;">
                        </td>
                        <td><div style="width:5px;"></div></td>
                        <td style="width: 100%; vertical-align: top; position: relative;">
                            <a href="#" class="woof_ext_remove" data-title="__TITLE__" data-idx="__IDX__" title="<?php _e('remove extension', 'woocommerce-products-filter') ?>"><img src="<?php echo WOOF_LINK ?>img/delete2.png" alt="<?php _e('remove extension', 'woocommerce-products-filter') ?>" /></a>
                            <label for="__IDX__">
                                <input type="checkbox" name="__NAME__" value="__IDX__" id="__IDX__">
                                __TITLE__
                            </label><br>
                            <i>ver.:</i> __VERSION__<br><p class="description">__DESCRIPTION__</p>
                        </td>
                    </tr>
                </tbody>
            </table>

        </li>
    </div>

    <div id="woof-modal-content-by_price" style="display: none;">

        <div class="woof-form-element-container">

            <div class="woof-name-description">
                <strong><?php _e('Show button', 'woocommerce-products-filter') ?></strong>
                <span><?php _e('Show button for woocommerce filter by price inside woof search form when it is dispayed as woo range-slider', 'woocommerce-products-filter') ?></span>
            </div>

            <div class="woof-form-element">

		<?php
		$show_button = array(
		    0 => __('No', 'woocommerce-products-filter'),
		    1 => __('Yes', 'woocommerce-products-filter')
		);
		?>

                <div class="select-wrap">
                    <select class="woof_popup_option" data-option="show_button">
			<?php foreach ($show_button as $key => $value) : ?>
    			<option value="<?php echo $key; ?>"><?php echo $value; ?></option>
			<?php endforeach; ?>
                    </select>
                </div>

            </div>

        </div>

        <div class="woof-form-element-container">

            <div class="woof-name-description">
                <strong><?php _e('Title text', 'woocommerce-products-filter') ?></strong>
                <span><?php _e('Text before the price filter range slider. Leave it empty if you not need it!', 'woocommerce-products-filter') ?></span>
            </div>

            <div class="woof-form-element">
                <input type="text" class="woof_popup_option" data-option="title_text" placeholder="" value="" />
            </div>

        </div>

        <div class="woof-form-element-container">

            <div class="woof-name-description">
                <h3><?php _e('Drop-down OR radio', 'woocommerce-products-filter') ?></h3>
                <strong><?php _e('Drop-down OR radio price filter ranges', 'woocommerce-products-filter') ?></strong>
                <span><?php _e('Ranges for price filter.', 'woocommerce-products-filter') ?></span>
                <span><?php printf(__('Example: 0-50,51-100,101-i. Where "i" is infinity. Max price is %s.', 'woocommerce-products-filter'), WOOF_HELPER::get_max_price()) ?></span>
            </div>

            <div class="woof-form-element">
                <input type="text" class="woof_popup_option" data-option="ranges" placeholder="" value="" />
            </div>

        </div>

        <div class="woof-form-element-container">

            <div class="woof-name-description">
                <strong><?php _e('Drop-down price filter text', 'woocommerce-products-filter') ?></strong>
                <span><?php _e('Drop-down price filter first option text', 'woocommerce-products-filter') ?></span>
            </div>

            <div class="woof-form-element">
                <input type="text" class="woof_popup_option" data-option="first_option_text" placeholder="" value="" />
            </div>

        </div>

        <div class="woof-form-element-container">

            <div class="woof-name-description">
                <h3><?php _e('Ion Range slider', 'woocommerce-products-filter') ?></h3>
                <strong><?php _e('Step', 'woocommerce-products-filter') ?></strong>
                <span><?php _e('predifined step', 'woocommerce-products-filter') ?></span>
            </div>

            <div class="woof-form-element">
                <input type="text" class="woof_popup_option" data-option="ion_slider_step" placeholder="" value="" />
            </div>

        </div>



    </div>



    <div id="woof_buffer" style="display: none;"></div>

    <div id="woof_html_buffer" class="woof_info_popup" style="display: none;"></div>


    <?php if ($this->is_free_ver): ?>
        <script>
    	jQuery(function () {
    	    //for premium only
    	    jQuery('#woof_filter_btn_txt').prop('disabled', true);
    	    jQuery('#woof_filter_btn_txt').val('In the premium version');
    	    jQuery('#woof_reset_btn_txt').prop('disabled', true);
    	    jQuery('#woof_reset_btn_txt').val('In the premium version');
    	    jQuery('#woof_hide_dynamic_empty_pos').prop('disabled', true);
    	    jQuery('select[name="woof_settings[hide_terms_count_txt]"]').prop('disabled', true);
    	    //***
    	    jQuery('#swoof_search_slug').prop('disabled', true);
    	    jQuery('#swoof_search_slug').val('In the premium version');
    	    jQuery('#swoof_search_slug').parents('.woof-control-section').addClass('woof_premium_only');
    	    jQuery('#hide_terms_count_txt').prop('disabled', true);
    	    jQuery('#hide_terms_count_txt').parents('.woof-control-section').addClass('woof_premium_only');
    	});
        </script>

        <style type="text/css">
    	.woof_premium_only{
    	    color:red !important;
    	}

    	label[for=woof_filter_btn_txt],
    	label[for=woof_reset_btn_txt],label[for=swoof_search_slug],
    	label[for=woof_hide_dynamic_empty_pos],label[for=hide_terms_count_txt]
    	{
    	    color:red;
    	}
        </style>
    <?php endif; ?>

</div>


<?php if ($this->is_free_ver): ?>
    <hr />


    <table style="width: 100%;">
        <tr>
    	<td style="width: 30%;">
    	    <h3><?php _e("Get the full version of the plugin from Codecanyon", 'woocommerce-products-filter-free') ?>:</h3>
    	    <a href="http://codecanyon.net/item/woof-woocommerce-products-filter/11498469?ref=realmag777" target="_blank"><img src="<?php echo WOOF_LINK ?>img/woof_banner.png" alt="<?php _e("full version of the plugin", 'woocommerce-currency-switcher'); ?>" /></a>
    	</td>
    	<td style="width: 10%;">

    	    <ul>

    		<li>
    		    <a href="https://share.payoneer.com/nav/6I2wmtpBuitGE6ZnmaMXLYlP8iriJ-63OMLi3PT8SRGceUjGY1dvEhDyuAGBp91DEmf8ugfF3hkUU1XhP_C6Jg2" target="_blank"><img src="<?php echo WOOF_LINK ?>img/100125.png" alt="" /></a>
    		</li>

    	    </ul>

    	</td>
    	<td style="width: 50%;">
    	    <h3><?php _e("Get WooCommerce Currency Swither for free", 'woocommerce-currency-switcher') ?>:</h3>
    	    <a href="http://codecanyon.net/item/woocommerce-currency-switcher/8085217?ref=realmag777" target="_blank"><img src="<?php echo WOOF_LINK ?>img/woocs_banner.jpg" alt="<?php _e("WOOCS", 'woocommerce-currency-switcher'); ?>" /></a>
    	</td>
        </tr>

    </table>

<?php endif; ?>

<?php

function woof_print_tax($key, $tax, $woof_settings) {
    global $WOOF;
    ?>
    <li data-key="<?php echo $key ?>" class="woof_options_li">

        <a href="#" class="help_tip woof_drag_and_drope" data-tip="<?php _e("drag and drope", 'woocommerce-products-filter'); ?>"><img src="<?php echo WOOF_LINK ?>img/move.png" alt="<?php _e("move", 'woocommerce-products-filter'); ?>" /></a>

        <div class="select-wrap">
    	<select name="woof_settings[tax_type][<?php echo $key ?>]" class="woof_select_tax_type">
		<?php foreach ($WOOF->html_types as $type => $type_text) : ?>
		    <option value="<?php echo $type ?>" <?php if (isset($woof_settings['tax_type'][$key])) echo selected($woof_settings['tax_type'][$key], $type) ?>><?php echo $type_text ?></option>
		<?php endforeach; ?>
    	</select>
        </div>

        <img class="help_tip" data-tip="<?php _e('View of the taxonomies terms on the front', 'woocommerce-products-filter') ?>" src="<?php echo WP_PLUGIN_URL ?>/woocommerce/assets/images/help.png" height="16" width="16" />

	<?php
	$excluded_terms = '';
	if (isset($woof_settings['excluded_terms'][$key])) {
	    $excluded_terms = $woof_settings['excluded_terms'][$key];
	}
	?>

        <input type="text" style="width: 420px;" name="woof_settings[excluded_terms][<?php echo $key ?>]" placeholder="<?php _e('excluded terms ids', 'woocommerce-products-filter') ?>" value="<?php echo $excluded_terms ?>" />
        <img class="help_tip" data-tip="<?php _e('If you want to exclude some current taxonomies terms from the seacrh form! Example: 11,23,77', 'woocommerce-products-filter') ?>" src="<?php echo WP_PLUGIN_URL ?>/woocommerce/assets/images/help.png" height="16" width="16" />
        <input type="button" value="<?php _e('additional options', 'woocommerce-products-filter') ?>" data-taxonomy="<?php echo $key ?>" data-taxonomy-name="<?php echo $tax->labels->name ?>" class="woof-button js_woof_add_options" />

        <div style="display: none;">
	    <?php
	    $max_height = 0;
	    if (isset($woof_settings['tax_block_height'][$key])) {
		$max_height = $woof_settings['tax_block_height'][$key];
	    }
	    ?>
    	<input type="text" name="woof_settings[tax_block_height][<?php echo $key ?>]" placeholder="" value="<?php echo $max_height ?>" />
	    <?php
	    $show_title_label = 0;
	    if (isset($woof_settings['show_title_label'][$key])) {
		$show_title_label = $woof_settings['show_title_label'][$key];
	    }
	    ?>
    	<input type="text" name="woof_settings[show_title_label][<?php echo $key ?>]" placeholder="" value="<?php echo $show_title_label ?>" />


	    <?php
	    $show_toggle_button = 0;
	    if (isset($woof_settings['show_toggle_button'][$key])) {
		$show_toggle_button = $woof_settings['show_toggle_button'][$key];
	    }
	    ?>
    	<input type="text" name="woof_settings[show_toggle_button][<?php echo $key ?>]" placeholder="" value="<?php echo $show_toggle_button ?>" />


	    <?php
	    $dispay_in_row = 0;
	    if (isset($woof_settings['dispay_in_row'][$key])) {
		$dispay_in_row = $woof_settings['dispay_in_row'][$key];
	    }
	    ?>
    	<input type="text" name="woof_settings[dispay_in_row][<?php echo $key ?>]" placeholder="" value="<?php echo $dispay_in_row ?>" />


	    <?php
	    $custom_tax_label = '';
	    if (isset($woof_settings['custom_tax_label'][$key])) {
		$custom_tax_label = $woof_settings['custom_tax_label'][$key];
	    }
	    ?>
    	<input type="text" name="woof_settings[custom_tax_label][<?php echo $key ?>]" placeholder="" value="<?php echo $custom_tax_label ?>" />


	    <?php
	    $not_toggled_terms_count = '';
	    if (isset($woof_settings['not_toggled_terms_count'][$key])) {
		$not_toggled_terms_count = $woof_settings['not_toggled_terms_count'][$key];
	    }
	    ?>
    	<input type="text" name="woof_settings[not_toggled_terms_count][<?php echo $key ?>]" placeholder="" value="<?php echo $not_toggled_terms_count ?>" />


    	<!------------- options for extensions ------------------------>
	    <?php
	    if (!empty(WOOF_EXT::$includes['taxonomy_type_objects'])) {
		foreach (WOOF_EXT::$includes['taxonomy_type_objects'] as $obj) {
		    if (!empty($obj->taxonomy_type_additional_options)) {
			foreach ($obj->taxonomy_type_additional_options as $option_key => $option) {
			    $option_val = 0;
			    if (isset($woof_settings[$option_key][$key])) {
				$option_val = $woof_settings[$option_key][$key];
			    }
			    ?>
		    	<input type="text" name="woof_settings[<?php echo $option_key ?>][<?php echo $key ?>]" value="<?php echo $option_val ?>" />
			    <?php
			}
		    }
		}
	    }
	    ?>




        </div>



        <input <?php echo(((isset($WOOF->settings['tax']) ? is_array($WOOF->settings['tax']) : FALSE) ? in_array($key, (array) array_keys($WOOF->settings['tax'])) : false) ? 'checked="checked"' : '') ?> type="checkbox" name="woof_settings[tax][<?php echo $key ?>]" id="tax_<?php echo md5($key) ?>" value="1" />
        <label for="tax_<?php echo md5($key) ?>" style="font-weight:bold;"><?php echo $tax->labels->name ?></label>
	<?php
	if (isset($woof_settings['tax_type'][$key])) {
	    do_action('woof_print_tax_additional_options_' . $woof_settings['tax_type'][$key], $key);
	}
	?>
    </li>
    <?php
}

//***

function woof_print_item_by_key($key, $woof_settings) {
    switch ($key) {
	case 'by_price':
	    ?>
	    <li data-key="<?php echo $key ?>" class="woof_options_li">

		<?php
		$show = 0;
		if (isset($woof_settings[$key]['show'])) {
		    $show = $woof_settings[$key]['show'];
		}
		?>

	        <a href="#" class="help_tip woof_drag_and_drope" data-tip="<?php _e("drag and drope", 'woocommerce-products-filter'); ?>"><img src="<?php echo WOOF_LINK ?>img/move.png" alt="<?php _e("move", 'woocommerce-products-filter'); ?>" /></a>

	        <strong style="display: inline-block; width: 176px;"><?php _e("Search by Price", 'woocommerce-products-filter'); ?>:</strong>

	        <img class="help_tip" data-tip="<?php _e('Show woocommerce filter by price inside woof search form', 'woocommerce-products-filter') ?>" src="<?php echo WP_PLUGIN_URL ?>/woocommerce/assets/images/help.png" height="16" width="16" />

	        <div class="select-wrap">
	    	<select name="woof_settings[<?php echo $key ?>][show]" class="woof_setting_select">
	    	    <option value="0" <?php echo selected($show, 0) ?>><?php _e('No', 'woocommerce-products-filter') ?></option>
	    	    <option value="1" <?php echo selected($show, 1) ?>><?php _e('As woo range-slider', 'woocommerce-products-filter') ?></option>
	    	    <option value="2" <?php echo selected($show, 2) ?>><?php _e('As drop-down', 'woocommerce-products-filter') ?></option>
	    	    <option value="5" <?php echo selected($show, 5) ?>><?php _e('As radio button', 'woocommerce-products-filter') ?></option>
	    	    <option value="4" <?php echo selected($show, 4) ?>><?php _e('As textinputs', 'woocommerce-products-filter') ?></option>
	    	    <option value="3" <?php echo selected($show, 3) ?>><?php _e('As ion range-slider', 'woocommerce-products-filter') ?></option>

	    	</select>
	        </div>

	        <input type="button" value="<?php _e('additional options', 'woocommerce-products-filter') ?>" data-key="<?php echo $key ?>" data-name="<?php _e("Search by Price", 'woocommerce-products-filter'); ?>" class="woof-button js_woof_options js_woof_options_<?php echo $key ?>" />

		<?php
		if (!isset($woof_settings[$key]['show_button'])) {
		    $woof_settings[$key]['show_button'] = 0;
		}

		if (!isset($woof_settings[$key]['title_text'])) {
		    $woof_settings[$key]['title_text'] = '';
		}


		if (!isset($woof_settings[$key]['ranges'])) {
		    $woof_settings[$key]['ranges'] = '';
		}

		if (!isset($woof_settings[$key]['first_option_text'])) {
		    $woof_settings[$key]['first_option_text'] = '';
		}

		if (!isset($woof_settings[$key]['ion_slider_step'])) {
		    $woof_settings[$key]['ion_slider_step'] = 1;
		}
		?>

	        <input type="hidden" name="woof_settings[<?php echo $key ?>][show_button]" value="<?php echo $woof_settings[$key]['show_button'] ?>" />
	        <input type="hidden" name="woof_settings[<?php echo $key ?>][title_text]" value="<?php echo $woof_settings[$key]['title_text'] ?>" />
	        <input type="hidden" name="woof_settings[<?php echo $key ?>][ranges]" value="<?php echo $woof_settings[$key]['ranges'] ?>" />
	        <input type="hidden" name="woof_settings[<?php echo $key ?>][first_option_text]" value="<?php echo $woof_settings[$key]['first_option_text'] ?>" />
	        <input type="hidden" name="woof_settings[<?php echo $key ?>][ion_slider_step]" value="<?php echo $woof_settings[$key]['ion_slider_step'] ?>" />

	    </li>
	    <?php
	    break;

	default:
	    //options for extensions
	    do_action('woof_print_html_type_options_' . $key);
	    break;
    }
}
