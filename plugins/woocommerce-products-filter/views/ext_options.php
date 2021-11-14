<?php
if (!defined('ABSPATH'))
    die('No direct access allowed');
//***
extract($options);
?>
<div class="woof-control-section-2">

    <h5><?php echo $title ?></h5>

    <div class="woof-control-container">
        <div class="woof-control">
            <?php
            if (!isset($woof_settings[$key]))
            {
                $woof_settings[$key] = $default;
            }
            //***
            switch ($type)
            {
                case 'textinput':
                    ?>
                    <input type="text" placeholder="<?php echo $placeholder ?>" name="woof_settings[<?php echo $key ?>]" value="<?php echo $woof_settings[$key] ?>" id="<?php echo $key ?>" />
                    <?php
                    break;
                case 'color':
                    ?>
                    <input type="text" placeholder="<?php echo $placeholder ?>" class="woof-color-picker" name="woof_settings[<?php echo $key ?>]" value="<?php echo $woof_settings[$key] ?>" id="<?php echo $key ?>" />
                    <?php
                    break;
                case 'select':
                    ?>
                    <select name="woof_settings[<?php echo $key ?>]" id="<?php echo $key ?>">
                        <?php
                        if (!empty($select_options))
                        {
                            foreach ($select_options as $opt_key => $opt_title)
                            {
                                ?>
                                <option <?php echo selected($woof_settings[$key], $opt_key) ?> value="<?php echo $opt_key ?>"><?php echo $opt_title ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                    <?php
                    break;

                default:
                    break;
            }
            ?>


        </div>
        <div class="woof-description">
            <p class="description"><?php echo $description ?></p>
        </div>
    </div>

</div><!--/ .woof-control-section-->
