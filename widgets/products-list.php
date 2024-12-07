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

    private function get_variable_products()
    {
        $args = [
            'status'    => 'publish',
            'orderby' => 'name',
            'order'   => 'ASC',
            'limit' => -1,
        ];
        $all_products = wc_get_products($args);

        $products_array = [];
        foreach ($all_products as $product) {
            if ($product->get_type() == "variable") {
                $products_array[$product->get_id()] = $product->get_name();
            }
        }

        return $products_array;
    }

    protected function register_controls(): void
    {
        $products = $this->get_variable_products();

        $this->start_controls_section(
            'content_section',
            [
                'label' => 'Abas',
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'products',
            [
                'label' => 'Produtos',
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => [
                    [
                        'name' => 'product_id',
                        'label' => 'Aba do produto',
                        'type' => \Elementor\Controls_Manager::SELECT,
                        'label_block' => true,
                        'default' => 'Produto',
                        'options' => $products,
                        'selectors' => [
                            '{{WRAPPER}} .your-class' => 'border-style: {{VALUE}};',
                        ],
                    ],
                ],
                'title_field' => 'Produto #{{{ product_id }}}',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'style_section',
            [
                'label' => esc_html__('Style', 'elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->end_controls_section();
    }

    protected function render(): void
    {
        $settings = $this->get_settings_for_display();

        if (empty($settings['products'])) return;

        $tabHeaders = '';
        $tab_contents = '';

        foreach ($settings['products'] as $product) {
            $product = wc_get_product($product['product_id']);

            if (!$product || !$product->is_type('variable')) {
                continue;
            }

            $product_name = $product->get_name();
            $product_slug = $product->get_slug();
            $product_image = wp_get_attachment_url($product->get_image_id());

            // Criação da aba
            $tabHeaders .= "<li class='hpl_tab-item' data-tab='hpl_tab-{$product_slug}'>";
            if ($product_image) {
                $tabHeaders .= "<img src='{$product_image}' alt='{$product_name}' class='hpl_product-thumbnail' />";
            }
            $tabHeaders .= "<span>{$product_name}</span>";
            $tabHeaders .= "</li>";

            // Variações do produto
            $tab_contents .= "<div class='hpl_tab-content' id='hpl_tab-{$product_slug}'>";

            $variation_ids = $product->get_children();
            foreach ($variation_ids as $variation_id) {
                $variation = wc_get_product($variation_id);

                $attributes = $variation->get_variation_attributes();
                $variation_name = is_array($attributes) ? array_pop(array_reverse($attributes)) : $product_name;
                $variation_price = $variation->get_price_html();
                $add_to_cart_url = wc_get_cart_url() . "?add-to-cart={$product->get_id()}&variation_id={$variation->get_id()}";

                $tab_contents .= "<div class='hpl_variation-item'>";
                $tab_contents .= "<h4>{$variation_name}</h4>";
                $tab_contents .= "<button class='hpl_buy-button' 
                                        data-product-name='{$product_name}' 
                                        data-product-price='{$variation_price}' 
                                        data-product-image='{$product_image}' 
                                        data-variation-name='{$variation_name}' 
                                        data-add-to-cart='{$add_to_cart_url}'>
                                        Comprar
                                     </button>";
                $tab_contents .= "</div>";
            }
            $tab_contents .= "</div>";
        }

        echo "<div class='elementor-widget-hpl_products_list'>";
        echo "<ul class='hpl_tabs-header'>{$tabHeaders}</ul>";
        echo "<div class='hpl_tabs-content'>{$tab_contents}</div>";
        echo "</div>";

        // Modal
        echo "<div class='hpl_modal' style='display:none;'>
                    <div class='hpl_modal-content'>
                        <h2>Confirme a sua compra</h2>
                        <p>Você está comprando COINS para a plataforma</p>
                        <img class='hpl_modal-product-thumbnail' src='' alt=''>
                        <h3 class='hpl_modal-product-name'></h3>
                        <p>Quantidade: <span class='hpl_modal-variation-name'></span> | Valor total: <span class='hpl_modal-product-price'></span></p>
                        <p>Possui cupom? Você vai poder usar na etapa de pagamento.</p>
                        <button class='hpl_modal-close'>Voltar</button>
                        <a href='#' class='hpl_modal-confirm'>Confirmar</a>
                    </div>
                 </div>";
    }
}
