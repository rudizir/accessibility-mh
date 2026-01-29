<?php
namespace MHACC\Settings;

class SettingsRenderer
{

    private array $groups;
    private array $values;
    private bool $is_pro;

    public function __construct(array $groups, array $values) {
        $this->groups = $groups;
        $this->values = $values;
        $this->is_pro = defined('MHACC_WIDGET_PRO_ACTIVE') && MHACC_WIDGET_PRO_ACTIVE;
    }

    


    public function render() {
        foreach ($this->groups as $group) {
            $this->render_group($group);
        }
    }

    private function render_group(array $group) {
        $is_pro_group = isset($group['pro']) && $group['pro'] === true;
        $group_disabled = $is_pro_group && !$this->is_pro;
        ?>
        <div class="mhacc-group boxed" id="<?php echo esc_attr($group['id']); ?>">
            <h2>
                <?php echo esc_html($group['title']); ?>
                <?php if ($is_pro_group): ?>
                    <span class="pro-badge">PRO</span>
                <?php endif; ?>
            </h2>

            <?php if (!empty($group['description'])): ?>
                <p class="description"><?php echo esc_html($group['description']); ?></p>
            <?php endif; ?>

            <?php if ($group_disabled): ?>
                <p class="description">
                    🔒 <?php echo esc_html__('These features are only available in the Pro version.', 'mhacc'); ?>
                </p>
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
        $is_pro = isset($field['pro']) && $field['pro'] === true;
        $disabled = $is_pro && !$this->is_pro;

        $value = $this->values[$field['name']] ?? ($field['default'] ?? '');

        ?>
        <tr>
            <th scope="row">
                <div class="mhacc-be-option">
                    <div class="mhacc-icon">
                        <?php echo $field['icon']; ?>
                    </div>
                    <div>
                        <p class="option_label">
                            <?php echo esc_html($field['label']); ?>
                            <?php if ($is_pro): ?><span class="pro-badge">PRO</span><?php endif; ?>
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
                                   <?php checked($value, 1); ?>
                                   <?php disabled($disabled); ?>>
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
                                   <?php checked($value, 1); ?>
                                   <?php disabled($disabled); ?>>
                            <span class="mhacc-toggle-slider"></span>
                        </label>
                        <?php
                        break;

                    case 'iconradios':
                        ?>
                        <div class="mhacc-icon-radios">
                            <?php foreach ($field['options'] as $key => $option): ?>

                                <?php
                                $option_is_pro = !empty($option['pro']);
                                $option_disabled = $option_is_pro && !$this->is_pro;
                                $is_active = ((string)$value === (string)$key);
                                ?>

                                <label class="mhacc-icon-radio <?php echo $is_active ? 'is-active' : ''; ?> <?php echo $option_disabled ? 'is-disabled' : ''; ?>">

                                    <input type="radio"
                                        name="mhacc_settings[<?php echo esc_attr($field['name']); ?>]"
                                        value="<?php echo esc_attr($key); ?>"
                                        <?php checked($is_active); ?>
                                        <?php disabled($option_disabled); ?>
                                    >

                                    <span class="mhacc-icon-radio-icon">
                                        <?php echo $option['icon']; ?>
                                    </span>

                                    <?php if ($option_is_pro): ?>
                                        <span class="pro-badge">PRO</span>
                                    <?php endif; ?>

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
                            <?php if ($field['pro'] && !$this->is_pro) echo 'disabled'; ?>
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
                                placeholder="#000000"
                                <?php disabled($disabled); ?>>

                            <!-- Native Color Picker -->
                            <input type="color"
                                class="mhacc-color-picker"
                                value="<?php echo esc_attr($current); ?>"
                                <?php disabled($disabled); ?>>

                            <?php if (!empty($field['options']) && is_array($field['options'])): ?>
                                <div class="mhacc-color-options">
                                    <?php foreach ($field['options'] as $color): ?>
                                        <label class="mhacc-color-swatch">
                                            <input type="radio"
                                                name="mhacc_settings[<?php echo esc_attr($field['name']); ?>]"
                                                value="<?php echo esc_attr($color); ?>"
                                                <?php checked($current, $color); ?>
                                                <?php disabled($disabled); ?>>

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
                        <select name="mhacc_settings[<?php echo esc_attr($field['name']); ?>]" <?php disabled($disabled); ?>>

                            <?php foreach ($field['options'] as $k => $opt): 
                                // Prüfen, ob Option ein Array (mit 'value' und evtl. 'pro') oder einfach ein String ist
                                $opt_value = is_array($opt) ? ($opt['value'] ?? '') : $opt;
                                $opt_pro   = is_array($opt) && !empty($opt['pro']);
                                $disabled_option = $opt_pro && !$this->is_pro;
                            ?>
                                <option value="<?php echo esc_attr($k); ?>"
                                    <?php selected($value, $k); ?>
                                    <?php disabled($disabled_option); ?>>
                                    <?php echo esc_html($opt_value); ?>
                                    <?php if ($disabled_option) echo ' (PRO)'; ?>
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
                               placeholder="<?php echo esc_attr($field['placeholder'] ?? ''); ?>"
                               <?php disabled($disabled); ?>>
                        <?php
                        break;
                }
                ?>
            </td>
        </tr>
        <?php
    }
}
