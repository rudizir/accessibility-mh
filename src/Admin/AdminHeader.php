<?php
namespace MHACC\Admin;
if ( ! defined( "ABSPATH" ) ) exit;

class AdminHeader
{

    public static function render( $active = '' ) {

        $tabs = [
            'welcome' => [
                'label' => __('Welcome', 'mh-accessibility'),
                'url'   => admin_url( 'admin.php?page=mhacc-widget' ),
            ],
            'modules' => [
                'label' => __('Modules', 'mh-accessibility'),
                'url'   => admin_url( 'admin.php?page=mhacc-widget-modules' ),
            ],
        ];


        // Erweiterungspunkt für Pro Tabs
        $tabs = apply_filters( 'mhacc_widget_admin_tabs', $tabs );
        ?>
        <div class="mhacc-header <?php echo esc_attr( \MHACC\Admin\AdminTheme::get_css_class() ); ?>">
            <div class="mhacc-header-inner">

                <h1 class="mhacc-logo">
                    <svg fill="none" viewBox="5 6 30 30" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="20" cy="14" r="6" fill="currentColor" opacity="1" transform-origin="20px 13px"></circle>
                        <path d="M9 25.5C9 25.5 14.5 21 20 21C25.5 21 31 25.5 31 25.5" stroke="currentColor" stroke-width="4" stroke-linecap="round"></path>
                        <path d="M13 31.5C13 31.5 16.5 29 20 29C23.5 29 27 31.5 27 31.5" stroke="currentColor" stroke-width="4" stroke-linecap="round"></path>
                    </svg>
                </h1>


                    <nav class="mhacc-nav">
                        <?php foreach ( $tabs as $key => $tab ) : ?>
                            <a href="<?php echo esc_url( $tab['url'] ); ?>"
                            class="mhacc-nav-item <?php echo $active === $key ? 'is-active' : ''; ?>">
                                <?php echo esc_html( $tab['label'] ); ?>
                            </a>
                        <?php endforeach; ?>
                    </nav>

                    <div class="mhacc-theme-switcher">

                        <?php 
                            if(\MHACC\Admin\AdminTheme::get_mode() === 'light') {
                        ?>
                            <button title="<?php esc_html_e('Dark', 'mh-accessibility'); ?>" data-theme="dark" class=" ">
                                <svg width="20" height="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="none" class=""><path d="M8.5 6.25C8.49985 7.29298 8.81035 8.31237 9.39192 9.17816C9.97348 10.0439 10.7997 10.7169 11.7653 11.1112C12.7309 11.5055 13.7921 11.6032 14.8134 11.3919C15.8348 11.1807 16.7701 10.67 17.5 9.925V10C17.5 14.1423 14.1423 17.5 10 17.5C5.85775 17.5 2.5 14.1423 2.5 10C2.5 5.85775 5.85775 2.5 10 2.5H10.075C9.57553 2.98834 9.17886 3.57172 8.90836 4.21576C8.63786 4.8598 8.49902 5.55146 8.5 6.25ZM4 10C3.99945 11.3387 4.44665 12.6392 5.27042 13.6945C6.09419 14.7497 7.24723 15.4992 8.54606 15.8236C9.84489 16.148 11.2149 16.0287 12.4381 15.4847C13.6614 14.9407 14.6675 14.0033 15.2965 12.8215C14.1771 13.0852 13.0088 13.0586 11.9026 12.744C10.7964 12.4295 9.78888 11.8376 8.97566 11.0243C8.16244 10.2111 7.57048 9.20361 7.25596 8.09738C6.94144 6.99116 6.91477 5.82292 7.1785 4.7035C6.21818 5.2151 5.41509 5.97825 4.85519 6.91123C4.2953 7.84422 3.99968 8.91191 4 10Z" fill="currentColor"></path></svg>
                            </button>
                        <?php
                        }
                        else {
                        ?>
                            <button title="<?php esc_html_e('Light', 'mh-accessibility'); ?>" data-theme="light" class=" ">
                                <svg width="20" height="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="none" class=""><path d="M10 14.5C8.80653 14.5 7.66193 14.0259 6.81802 13.182C5.97411 12.3381 5.5 11.1935 5.5 10C5.5 8.80653 5.97411 7.66193 6.81802 6.81802C7.66193 5.97411 8.80653 5.5 10 5.5C11.1935 5.5 12.3381 5.97411 13.182 6.81802C14.0259 7.66193 14.5 8.80653 14.5 10C14.5 11.1935 14.0259 12.3381 13.182 13.182C12.3381 14.0259 11.1935 14.5 10 14.5ZM10 13C10.7956 13 11.5587 12.6839 12.1213 12.1213C12.6839 11.5587 13 10.7956 13 10C13 9.20435 12.6839 8.44129 12.1213 7.87868C11.5587 7.31607 10.7956 7 10 7C9.20435 7 8.44129 7.31607 7.87868 7.87868C7.31607 8.44129 7 9.20435 7 10C7 10.7956 7.31607 11.5587 7.87868 12.1213C8.44129 12.6839 9.20435 13 10 13ZM9.25 1.75H10.75V4H9.25V1.75ZM9.25 16H10.75V18.25H9.25V16ZM3.63625 4.69675L4.69675 3.63625L6.2875 5.227L5.227 6.2875L3.63625 4.6975V4.69675ZM13.7125 14.773L14.773 13.7125L16.3638 15.3032L15.3032 16.3638L13.7125 14.773ZM15.3032 3.6355L16.3638 4.69675L14.773 6.2875L13.7125 5.227L15.3032 3.63625V3.6355ZM5.227 13.7125L6.2875 14.773L4.69675 16.3638L3.63625 15.3032L5.227 13.7125ZM18.25 9.25V10.75H16V9.25H18.25ZM4 9.25V10.75H1.75V9.25H4Z" fill="currentColor"></path></svg>
                            </button>
                        <?php
                            }
                        ?>

                        <?php if (apply_filters('mhacc_show_get_pro_link', true)) : ?>
                            <a id="mhacc_getpro" target="_blank" href="https://mh-accessibility.de/" title="Upgrade to Pro →">Upgrade to Pro →</a>
                        <?php endif; ?>

                    </div>
                    
                
            </div>
        </div>
        <?php
    }
}
