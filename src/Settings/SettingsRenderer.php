<?php
namespace MHACC\Settings;
if ( ! defined( "ABSPATH" ) ) exit;

class SettingsRenderer
{

    private array $groups;
    private array $values;

    public function __construct(array $groups, array $values) {
        $this->groups = $groups;
        $this->values = $values;
    }

    


    public function render() {
        foreach ($this->groups as $group) {
            $this->render_group($group);
        }
    }

    private function render_group(array $group) {
        ?>
        <div class="mhacc-group boxed" id="<?php echo esc_attr($group['id']); ?>">
            <h2>
                <?php echo esc_html($group['title']); ?>
            </h2>

            <?php if (!empty($group['description'])): ?>
                <p class="description"><?php echo esc_html($group['description']); ?></p>
            <?php endif; ?>


            <table class="form-table">
                <tbody>
                <?php foreach ($group['fields'] as $field): ?>
                    <?php $this->render_field($field); ?>
                <?php endforeach; ?>
                </tbody>
            </table>

            <?php submit_button(); ?>
        </div>
        <?php
    }

    private function render_field(array $field) {

        $value = $this->values[$field['name']] ?? ($field['default'] ?? '');

        ?>
        <tr>
            <th scope="row">
                <div class="mhacc-be-option">
					<div class="mhacc-icon">
						<?php
						echo wp_kses(
							$field['icon'] ?? '',
                            array(
                                'svg' => array(
                                    'xmlns' => true,
                                    'width' => true,
                                    'height' => true,
                                    'viewbox' => true,
                                    'fill' => true,
                                    'aria-hidden' => true,
                                    'focusable' => true,
                                    'role' => true,
                                    'class' => true,
                                ),
                                'path' => array(
                                    'd' => true,
                                    'fill' => true,
                                    'stroke' => true,
                                    'stroke-width' => true,
                                ),
                                'circle' => array(
                                    'cx' => true,
                                    'cy' => true,
                                    'r' => true,
                                    'fill' => true,
                                ),
                                'rect' => array(
                                    'x' => true,
                                    'y' => true,
                                    'width' => true,
                                    'height' => true,
                                    'rx' => true,
                                    'fill' => true,
                                    'stroke' => true,
                                ),
                                'g' => array(
                                    'fill' => true,
                                    'stroke' => true,
                                    'stroke-width' => true,
                                    'stroke-linecap' => true,
                                    'stroke-linejoin' => true,
                                ),
                            )
						);
						?>
					</div>

                    <div>
                        <p class="option_label">
                            <?php echo esc_html($field['label']); ?>
                        </p>
                        <?php if (!empty($field['description'])): ?>
                            <p class="description"><?php echo esc_html($field['description']); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </th>
            <td>
                <?php
                switch ($field['type']) {

                    case 'checkbox':
                        ?>
                        <label class="mhacc-checkbox-wrapper">
                            <input type="checkbox"
                                   class="mhacc-checkbox-input"
                                   name="mhacc_settings[<?php echo esc_attr($field['name']); ?>]"
                                   value="1"
                                   <?php checked($value, 1); ?>>
                            <
                        </label>
                        <?php
                        break;

                    case 'toggle':
                        $value = $this->values[$field['name']] ?? ($field['isactive'] ?? '');
                        ?>
                        <label class="mhacc-toggle-wrapper">
                            <input type="checkbox"
                                   class="mhacc-toggle-input"
                                   name="mhacc_settings[<?php echo esc_attr($field['name']); ?>]"
                                   value="1"
                                   <?php checked($value, 1); ?>>
                            <span class="mhacc-toggle-slider"></span>
                        </label>
                        <?php
                        break;

                    case 'iconradios':
                        ?>
                        <div class="mhacc-icon-radios">
                            <?php foreach ($field['options'] as $key => $option): ?>

                                <?php
                                $is_active = ((string)$value === (string)$key);
                                ?>

                                <label class="mhacc-icon-radio <?php echo $is_active ? 'is-active' : ''; ?> ">

                                    <input type="radio"
                                        name="mhacc_settings[<?php echo esc_attr($field['name']); ?>]"
                                        value="<?php echo esc_attr($key); ?>"
                                        <?php checked($is_active); ?>
                                    >

									<span class="mhacc-icon-radio-icon">
										<?php
										echo wp_kses(
											$option['icon'] ?? '',
                                            array(
                                                'svg' => array(
                                                    'xmlns' => true,
                                                    'width' => true,
                                                    'height' => true,
                                                    'viewbox' => true,
                                                    'fill' => true,
                                                    'aria-hidden' => true,
                                                    'focusable' => true,
                                                    'role' => true,
                                                    'class' => true,
                                                ),
                                                'path' => array(
                                                    'd' => true,
                                                    'fill' => true,
                                                    'stroke' => true,
                                                    'stroke-width' => true,
                                                ),
                                                'circle' => array(
                                                    'cx' => true,
                                                    'cy' => true,
                                                    'r' => true,
                                                    'fill' => true,
                                                ),
                                                'rect' => array(
                                                    'x' => true,
                                                    'y' => true,
                                                    'width' => true,
                                                    'height' => true,
                                                    'rx' => true,
                                                    'fill' => true,
                                                    'stroke' => true,
                                                ),
                                                'g' => array(
                                                    'fill' => true,
                                                    'stroke' => true,
                                                    'stroke-width' => true,
                                                    'stroke-linecap' => true,
                                                    'stroke-linejoin' => true,
                                                ),
                                            )
										);
										?>
									</span>

                                </label>

                            <?php endforeach; ?>
                        </div>
                        <?php
                        break;



                    case 'position':

                        // Default-Werte für Unterfelder setzen
                        $subValue = $this->values[$field['name']] ?? [];
                        $number    = $subValue['value'] ?? ($field['options']['value']['default'] ?? '');
                        $unit      = $subValue['unit'] ?? ($field['options']['unit']['default'] ?? 'px');
                        $pos       = $subValue['position'] ?? ($field['options']['position']['default'] ?? 'right');

                        ?>
                        <div class="mhacc-position-field">

                            <!-- Number Input -->
                            <input type="number"
                                class="mhacc-position-number"
                                name="mhacc_settings[<?php echo esc_attr($field['name']); ?>][value]"
                                value="<?php echo esc_attr($number); ?>"
                                step="0.1"
                                style="width: 60px;"
                            >

                            <!-- Unit Select -->
                            <select name="mhacc_settings[<?php echo esc_attr($field['name']); ?>][unit]"
                                    class="mhacc-position-unit">
                                <?php foreach ($field['options']['unit']['options'] as $u): ?>
                                    <option value="<?php echo esc_attr($u); ?>" <?php selected($unit, $u); ?>>
                                        <?php echo esc_html($u); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>

                            <!-- Position Select -->
                            <select name="mhacc_settings[<?php echo esc_attr($field['name']); ?>][position]"
                                    class="mhacc-position-position">
                                <?php foreach ($field['options']['position']['options'] as $p): ?>
                                    <option value="<?php echo esc_attr($p); ?>" <?php selected($pos, $p); ?>>
                                        <?php echo esc_html(ucfirst($p)); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>


                        </div>
                        <?php
                    break;


                    case 'number':
                        // Wert laden, Default fallback
                        $value = $this->values[$field['name']] ?? ($field['default'] ?? '');

                        ?>
                        <input type="number"
                            class="regular-text mhacc-number-input"
                            name="mhacc_settings[<?php echo esc_attr($field['name']); ?>]"
                            value="<?php echo esc_attr($value); ?>"
                        >
                        <?php
                    break;


                    case 'color':

                        $current = (string) ($value ?: ($field['default'] ?? '#000000'));
                        ?>
                        <div class="mhacc-color-field" id="color_<?php echo esc_attr($field['name']); ?>">


                            <!-- HEX Input -->
                            <input type="text"
                                class="mhacc-color-input"
                                name="mhacc_settings[<?php echo esc_attr($field['name']); ?>]"
                                value="<?php echo esc_attr($current); ?>"
                                placeholder="#000000">

                            <!-- Native Color Picker -->
                            <input type="color"
                                class="mhacc-color-picker"
                                value="<?php echo esc_attr($current); ?>">

                            <?php if (!empty($field['options']) && is_array($field['options'])): ?>
                                <div class="mhacc-color-options">
                                    <?php foreach ($field['options'] as $color): ?>
                                        <label class="mhacc-color-swatch">
                                            <input type="radio"
                                                name="mhacc_settings[<?php echo esc_attr($field['name']); ?>]"
                                                value="<?php echo esc_attr($color); ?>"
                                                <?php checked($current, $color); ?>>

                                            <span class="mhacc-color-circle"
                                                style="background-color: <?php echo esc_attr($color); ?>"
                                                title="<?php echo esc_attr($color); ?>">
                                            </span>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>

                        </div>
                        <?php
                        break;




                    

                    case 'select':
                        ?>
                        <select name="mhacc_settings[<?php echo esc_attr($field['name']); ?>]">

                            <?php foreach ($field['options'] as $k => $opt): 
                                $opt_value = is_array($opt) ? ($opt['value'] ?? '') : $opt;
                            ?>
                                <option value="<?php echo esc_attr($k); ?>" <?php selected($value, $k); ?>>
                                    <?php echo esc_html($opt_value); ?>
                                </option>
                            <?php endforeach; ?>

                        </select>
                        <?php
                        break;

                    case 'text':
                        ?>
                        <input type="text"
                               class="regular-text"
                               name="mhacc_settings[<?php echo esc_attr($field['name']); ?>]"
                               value="<?php echo esc_attr($value); ?>"
                               placeholder="<?php echo esc_attr($field['placeholder'] ?? ''); ?>">
                        <?php
                        break;
                }
                ?>
            </td>
        </tr>
        <?php
    }
}
