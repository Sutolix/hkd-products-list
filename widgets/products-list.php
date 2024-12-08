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
        return 'eicon-product-tabs';
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

        $this->add_control(
            'show_badge',
            [
                'label' => 'Mostrar emblema',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'elementor'),
                'label_off' => esc_html__('Hide', 'elementor'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        $this->add_control(
            'badge',
            [
                'label' => 'Escolher emblema',
                'description' =>  'Ficará ao lado do produto na listagem.',
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'show_badge' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'tabs_style_section',
            [
                'label' => 'Abas',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs(
            'tabs_box_shadow_tabs'
        );

        $this->start_controls_tab(
            'tabs_box_shadow_normal',
            [
                'label' => 'Normal',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'tabs_box_shadow',
                'selector' => '{{WRAPPER}} .hpl-tabs-header .hpl-tab-item',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tabs_box_shadow_active',
            [
                'label' => 'Selecionado',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'tabs_box_shadow_hover',
                'selector' => '{{WRAPPER}} .hpl-tabs-header .hpl-tab-item.hpl-active',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'tabs_border',
                'selector' => '{{WRAPPER}} .hpl-tabs-header .hpl-tab-item',
            ]
        );

        $this->add_control(
            'tabs_padding',
            [
                'label' => esc_html__('Padding', 'elementor'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .hpl-tabs-header .hpl-tab-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'tabs_gap',
            [
                'type' => \Elementor\Controls_Manager::SLIDER,
                'label' => 'Espaço entre abas',
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'devices' => ['desktop', 'tablet', 'mobile'],
                'selectors' => [
                    '{{WRAPPER}} .hpl-tabs-header' => '--gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'tabs_typography',
                'selector' => '{{WRAPPER}} .hpl-tabs-header .hpl-tab-item span',
            ]
        );

        $this->add_control(
            'tabs_image_width',
            [
                'label' => 'Largura das imagens',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .hpl-tabs-header .hpl-tab-item img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'tabs_image_height',
            [
                'label' => 'Altura das imagens',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .hpl-tabs-header .hpl-tab-item img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'tabs_image_object',
            [
                'label' => 'Ajuste do objeto',
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => 'Padrão',
                    'fill' => 'Preencher',
                    'cover'  => 'Cobertura',
                    'contain' => 'Conter'
                ],
                'selectors' => [
                    '{{WRAPPER}} .hpl-tabs-header .hpl-tab-item img' => 'object-fit: {{VALUE}};',
                ],
                'condition' => [
                    'tabs_image_height[size]!' => '',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'loop_style_section',
            [
                'label' => 'Item do loop',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'loop_border',
                'selector' => '{{WRAPPER}} .hpl-tabs-content .hpl-variation-item',
            ]
        );

        $this->add_control(
            'loop_padding',
            [
                'label' => esc_html__('Padding', 'elementor'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .hpl-tabs-content .hpl-variation-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'variation_header',
            [
                'label' => 'Título e emblema',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'variation_title_text',
                'selector' => '{{WRAPPER}} .hpl-tabs-content .hpl-variation-item .hpl-variation-header span',
            ]
        );

        $this->add_control(
            'variation_badge_width',
            [
                'label' => 'Largura do emblema',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .hpl-variation-header .hpl-variation-badge img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'variation_description',
            [
                'label' => 'Descrição da variação',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'variation_description_text',
                'selector' => '{{WRAPPER}} .hpl-tabs-content .hpl-variation-item .hpl-variation-info .hpl-variation-text',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'variation_price',
            [
                'label' => 'Preço da variação',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'variation_price_text',
                'selector' => '{{WRAPPER}} .hpl-tabs-content .hpl-variation-item .hpl-variation-info .woocommerce-Price-amount',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'variation_action',
            [
                'label' => 'Botão',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'variation_button_text',
                'selector' => '{{WRAPPER}} .hpl-tabs-content .hpl-variation-item .hpl-variation-action button',
            ]
        );

        $this->start_controls_tabs(
            'variation_button_colors'
        );

        $this->start_controls_tab(
            'variation_button_colors_normal',
            [
                'label' => 'Normal',
            ]
        );

        $this->add_control(
			'variation_button_color',
			[
				'label' => 'Cor do texto',
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .hpl-tabs-content .hpl-variation-item .hpl-variation-action button' => 'color: {{VALUE}}',
				],
			]
		);

        $this->add_control(
			'variation_button_background_color',
			[
				'label' => 'Cor do texto',
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .hpl-tabs-content .hpl-variation-item .hpl-variation-action button' => 'background-color: {{VALUE}}',
				],
			]
		);

        $this->end_controls_tab();

        $this->start_controls_tab(
            'variation_button_colors_hover',
            [
                'label' => 'Ao passar o mouse',
            ]
        );

        $this->add_control(
			'variation_button_color_hover',
			[
				'label' => 'Cor do texto',
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .hpl-tabs-content .hpl-variation-item .hpl-variation-action button:hover' => 'color: {{VALUE}}',
				],
			]
		);

        $this->add_control(
			'variation_button_background_color_hover',
			[
				'label' => 'Cor do texto',
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .hpl-tabs-content .hpl-variation-item .hpl-variation-action button:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'variation_button_padding',
            [
                'label' => esc_html__('Padding', 'elementor'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .hpl-tabs-content .hpl-variation-item .hpl-variation-action button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'modal',
            [
                'label' => 'Modal',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
			'heading_modal_title',
			[
				'label' => 'Título',
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'modal_title_text',
                'selector' => '.hpl-modal .hpl-modal-content .hpl-modal-header',
            ]
        );

        $this->add_control(
			'heading_modal_subtitle',
			[
				'label' => 'Subtitulo',
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'modal_subtitle_text',
                'selector' => '.hpl-modal .hpl-modal-content .hpl-modal-subtitle',
            ]
        );

        $this->add_control(
			'heading_modal_product_name',
			[
				'label' => 'Nome do produto',
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'modal_product_name_text',
                'selector' => '.hpl-modal .hpl-modal-content .hpl-modal-product-name',
            ]
        );

        $this->add_control(
			'heading_modal_quantity',
			[
				'label' => 'Quantidade',
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'modal_quantity_text',
                'selector' => '.hpl-modal .hpl-modal-content .hpl-modal-quantity',
            ]
        );

        $this->add_control(
			'heading_modal_footer',
			[
				'label' => 'Rodapé',
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'modal_footer_text',
                'selector' => '.hpl-modal .hpl-modal-content .hpl-modal-footer span',
            ]
        );

        $this->add_control(
			'heading_modal_close_button',
			[
				'label' => 'Botão voltar',
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'modal_close_button_text',
                'selector' => '.hpl-modal .hpl-modal-content .hpl-modal-close',
            ]
        );

        $this->start_controls_tabs(
            'modal_close_button_colors'
        );

        $this->start_controls_tab(
            'modal_close_button_colors_normal',
            [
                'label' => 'Normal',
            ]
        );

        $this->add_control(
			'modal_close_button_color',
			[
				'label' => 'Cor do texto',
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'.hpl-modal .hpl-modal-content .hpl-modal-close' => 'color: {{VALUE}}',
				],
			]
		);

        $this->add_control(
			'modal_close_button_background_color',
			[
				'label' => 'Cor do texto',
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'.hpl-modal .hpl-modal-content .hpl-modal-close' => 'background-color: {{VALUE}}',
				],
			]
		);

        $this->end_controls_tab();

        $this->start_controls_tab(
            'modal_close_button_colors_hover',
            [
                'label' => 'Ao passar o mouse',
            ]
        );

        $this->add_control(
			'modal_close_button_color_hover',
			[
				'label' => 'Cor do texto',
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'.hpl-modal .hpl-modal-content .hpl-modal-close:hover' => 'color: {{VALUE}}',
				],
			]
		);

        $this->add_control(
			'modal_close_button_background_color_hover',
			[
				'label' => 'Cor do texto',
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'.hpl-modal .hpl-modal-content .hpl-modal-close:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
			'heading_modal_confirm_button',
			[
				'label' => 'Botão confirmar',
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'modal_confirm_button_text',
                'selector' => '.hpl-modal .hpl-modal-content .hpl-modal-confirm',
            ]
        );

        $this->start_controls_tabs(
            'modal_confirm_button_colors'
        );

        $this->start_controls_tab(
            'modal_confirm_button_colors_normal',
            [
                'label' => 'Normal',
            ]
        );

        $this->add_control(
			'modal_confirm_button_color',
			[
				'label' => 'Cor do texto',
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'.hpl-modal .hpl-modal-content .hpl-modal-confirm' => 'color: {{VALUE}}',
				],
			]
		);

        $this->add_control(
			'modal_confirm_button_background_color',
			[
				'label' => 'Cor do texto',
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'.hpl-modal .hpl-modal-content .hpl-modal-confirm' => 'background-color: {{VALUE}}',
				],
			]
		);

        $this->end_controls_tab();

        $this->start_controls_tab(
            'modal_confirm_button_colors_hover',
            [
                'label' => 'Ao passar o mouse',
            ]
        );

        $this->add_control(
			'modal_confirm_button_color_hover',
			[
				'label' => 'Cor do texto',
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'.hpl-modal .hpl-modal-content .hpl-modal-confirm:hover' => 'color: {{VALUE}}',
				],
			]
		);

        $this->add_control(
			'modal_confirm_button_background_color_hover',
			[
				'label' => 'Cor do texto',
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'.hpl-modal .hpl-modal-content .hpl-modal-confirm:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

        $this->end_controls_tab();

        $this->end_controls_tabs();

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
            $tabHeaders .= "<li class='hpl-tab-item' data-tab='hpl-tab-{$product_slug}'>";
            if ($product_image) {
                $tabHeaders .= "<img src='{$product_image}' alt='{$product_name}' class='hpl-product-thumbnail' />";
            }
            $tabHeaders .= "<span>{$product_name}</span>";
            $tabHeaders .= "</li>";

            // Variações do produto
            $tab_contents .= "<div class='hpl-tab-content' id='hpl-tab-{$product_slug}'>";

            $variation_ids = $product->get_children();
            foreach ($variation_ids as $variation_id) {
                $variation = wc_get_product($variation_id);

                $attributes = $variation->get_variation_attributes();
                $name = is_array($attributes) ? array_pop(array_reverse($attributes)) : $product_name;
                $price = $variation->get_price_html();
                $description = $variation->get_description();
                $add_to_cart_url = wc_get_cart_url() . "?add-to-cart={$product->get_id()}&variation_id={$variation->get_id()}";

                ob_start();
?>
                <div class='hpl-variation-item'>
                    <div class="hpl-variation-header">
                        <?php
                        if (
                            isset($settings['show_badge']) &&
                            $settings['show_badge'] === 'yes' &&
                            isset($settings['badge']) &&
                            isset($settings['badge']['url']) &&
                            !empty($settings['badge']['url'])
                        ) {
                            echo "<div class='hpl-variation-badge'><img src='{$settings['badge']['url']}'/></div>";
                        }
                        ?>
                        <span><?php echo $name ?></span>
                    </div>
                    <div class="hpl-variation-info">
                        <div class="hpl-variation-text">
                            <span class="hpl-variation-description"><?php echo $description ?></span>
                            <span class="hpl-label">por</span>
                            <?php echo $price ?>
                        </div>
                    </div>
                    <div class="hpl-variation-action">
                        <button class='hpl-buy-button'
                            data-product-name='<?php echo $product_name ?>'
                            data-product-price='<?php echo $price ?>'
                            data-product-image='<?php echo $product_image ?>'
                            data-variation-name='<?php echo $name ?>'
                            data-add-to-cart='<?php echo $add_to_cart_url ?>'>
                            Comprar
                        </button>
                    </div>
                </div>
        <?php
                $tab_contents .= ob_get_clean();
            }
            $tab_contents .= "</div>";
        }
        
        echo "<ul class='hpl-tabs-header'>{$tabHeaders}</ul>";
        echo "<div class='hpl-tabs-content'>{$tab_contents}</div>";
        echo "<div class='hpl-tabs-fallback'><span>Escolha uma plataforma antes</span></div>";
        ?>

        <div class='hpl-modal' style='display:none;'>
            <div class='hpl-modal-content'>
                <div class="hpl-modal-header">
                    <span>Confirme a sua compra</span>
                </div>
                <div class="hpl-modal-body">
                    <p class="hpl-modal-subtitle">Você está comprando COINS para a plataforma</p>
                    <img class='hpl-modal-product-thumbnail' src='' alt=''>
                    <div class='hpl-modal-product-name'></div>
                    <p class='hpl-modal-quantity'>Quantidade: <span class='hpl-modal-variation-name'></span> | Valor total: <span class='hpl-modal-product-price'></span></p>
                </div>
                <div class="hpl-modal-footer">
                    <span><b>Possui cupom?</b> Você vai poder usar na etapa de pagamento.</span>
                    <div class="hpl-modal-buttons">
                        <button type="button" class='hpl-modal-btn hpl-modal-close'>Voltar</button>
                        <a href='#' class='hpl-modal-btn hpl-modal-confirm'>Confirmar</a>
                    </div>
                </div>
            </div>
        </div>
<?php
    }
}
