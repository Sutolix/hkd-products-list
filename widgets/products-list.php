<?php
class Elementor_HPL_Products_List extends \Elementor\Widget_Base
{

    public function get_name(): string
    {
        return 'hpl_products_list';
    }

    public function get_title(): string
    {
        return 'Lista de produtos';
    }

    public function get_icon(): string
    {
        return 'eicon-code';
    }

    public function get_categories(): array
    {
        return ['basic'];
    }

    public function get_keywords(): array
    {
        return ['list', 'products', 'woocommerce', 'tabs'];
    }

    public function get_style_depends(): array
    {
        return ['hpl-products-list-style'];
    }

    public function get_script_depends(): array
    {
        return ['hpl-products-list-script'];
    }

    protected function register_controls(): void
    {
        $product_categories = get_terms([
            'taxonomy'   => 'product_cat',
            'hide_empty' => false,
        ]);

        $categories_array = [];
        if (!is_wp_error($product_categories)) {
            foreach ($product_categories as $category) {
                $categories_array[$category->slug] = $category->name;
            }
        }

        $this->start_controls_section(
            'content_section',
            [
                'label' => 'Abas',
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'categories',
            [
                'label' => 'Categorias',
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => [
                    [
                        'name' => 'woo_category',
                        'label' => 'Aba da categoria',
                        'type' => \Elementor\Controls_Manager::SELECT,
                        'label_block' => true,
                        'default' => 'Item',
                        'options' => $categories_array,
                        'selectors' => [
                            '{{WRAPPER}} .your-class' => 'border-style: {{VALUE}};',
                        ],
                    ],
                ],
                'title_field' => '{{{ woo_category }}}',
            ]
        );

        $this->end_controls_section();
    }

    protected function render(): void
    {
        $settings = $this->get_settings_for_display();

        var_dump($settings['categories']);
    }
}
