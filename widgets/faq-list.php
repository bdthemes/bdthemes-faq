<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

class BdThemes_FAQ_List_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'bdthemes-faq-list';
	}

	public function get_title() {
		return __('FAQ List', 'bdthemes-faq');
	}

	public function get_icon() {
		return 'eicon-post-list';
	}

	public function get_categories() {
		return ['general'];
	}

	public function get_keywords() {
		return ['faq', 'list', 'questions', 'answers'];
	}

	public function get_style_depends() {
		return ['bdthemes-faq-list'];
	}

	protected function register_controls() {
		
		// Content Tab
		$this->start_controls_section(
			'section_content',
			[
				'label' => __('Content', 'bdthemes-faq'),
			]
		);

		$this->add_control(
			'faq_count',
			[
				'label' => __('FAQ Count', 'bdthemes-faq'),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => 6,
				'min' => -1,
				'max' => 100,
				'description' => __('Set -1 to show all FAQs', 'bdthemes-faq'),
			]
		);

		$this->add_control(
			'faq_order',
			[
				'label' => __('Order', 'bdthemes-faq'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'DESC',
				'options' => [
					'ASC' => __('Ascending', 'bdthemes-faq'),
					'DESC' => __('Descending', 'bdthemes-faq'),
				],
			]
		);

		$this->add_control(
			'faq_orderby',
			[
				'label' => __('Order By', 'bdthemes-faq'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'date',
				'options' => [
					'date' => __('Date', 'bdthemes-faq'),
					'title' => __('Title', 'bdthemes-faq'),
					'menu_order' => __('Menu Order', 'bdthemes-faq'),
					'rand' => __('Random', 'bdthemes-faq'),
				],
			]
		);

		// Get FAQ filters/categories
		$faq_filters = get_terms([
			'taxonomy' => 'faq_filter',
			'hide_empty' => false,
		]);

		$filter_options = ['' => __('All', 'bdthemes-faq')];
		if (!is_wp_error($faq_filters) && !empty($faq_filters)) {
			foreach ($faq_filters as $filter) {
				$filter_options[$filter->slug] = $filter->name;
			}
		}

		$this->add_control(
			'faq_filter',
			[
				'label' => __('Filter', 'bdthemes-faq'),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => true,
				'options' => $filter_options,
				'default' => '',
			]
		);

		$this->add_control(
			'show_image',
			[
				'label' => __('Show Image', 'bdthemes-faq'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __('Yes', 'bdthemes-faq'),
				'label_off' => __('No', 'bdthemes-faq'),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'image_source',
			[
				'label' => __('Image Source', 'bdthemes-faq'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'featured',
				'options' => [
					'featured' => __('Featured Image', 'bdthemes-faq'),
					'custom' => __('Custom Icon', 'bdthemes-faq'),
				],
				'condition' => [
					'show_image' => 'yes',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Image_Size::get_type(),
			[
				'name' => 'featured_image',
				'default' => 'thumbnail',
				'separator' => 'none',
				'condition' => [
					'show_image' => 'yes',
					'image_source' => 'featured',
				],
			]
		);

		$this->add_control(
			'show_excerpt',
			[
				'label' => __('Show Text', 'bdthemes-faq'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __('Yes', 'bdthemes-faq'),
				'label_off' => __('No', 'bdthemes-faq'),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'excerpt_length',
			[
				'label' => __('Excerpt Length', 'bdthemes-faq'),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => 20,
				'min' => 0,
				'max' => 100,
				'condition' => [
					'show_excerpt' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_category',
			[
				'label' => __('Show Category', 'bdthemes-faq'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __('Yes', 'bdthemes-faq'),
				'label_off' => __('No', 'bdthemes-faq'),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->end_controls_section();

		// Style Tab - Items
		$this->start_controls_section(
			'section_style_items',
			[
				'label' => __('Items', 'bdthemes-faq'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'item_spacing',
			[
				'label' => __('Item Spacing', 'bdthemes-faq'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdtfq-faq-list' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'item_padding',
			[
				'label' => __('Padding', 'bdthemes-faq'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} .bdtfq-faq-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'item_border_radius',
			[
				'label' => __('Border Radius', 'bdthemes-faq'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .bdtfq-faq-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs('item_tabs');

		$this->start_controls_tab(
			'item_tab_normal',
			[
				'label' => __('Normal', 'bdthemes-faq'),
			]
		);

		$this->add_control(
			'item_bg_color',
			[
				'label' => __('Background Color', 'bdthemes-faq'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdtfq-faq-item' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'item_border',
				'selector' => '{{WRAPPER}} .bdtfq-faq-item',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'item_box_shadow',
				'selector' => '{{WRAPPER}} .bdtfq-faq-item',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'item_tab_hover',
			[
				'label' => __('Hover', 'bdthemes-faq'),
			]
		);

		$this->add_control(
			'item_bg_color_hover',
			[
				'label' => __('Background Color', 'bdthemes-faq'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdtfq-faq-item:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'item_border_color_hover',
			[
				'label' => __('Border Color', 'bdthemes-faq'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdtfq-faq-item:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'item_box_shadow_hover',
				'selector' => '{{WRAPPER}} .bdtfq-faq-item:hover',
			]
		);

		$this->add_control(
			'item_hover_transform',
			[
				'label' => __('Transform Y', 'bdthemes-faq'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => -20,
						'max' => 0,
					],
				],
				'default' => [
					'size' => -2,
				],
				'selectors' => [
					'{{WRAPPER}} .bdtfq-faq-item:hover' => 'transform: translateY({{SIZE}}{{UNIT}});',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		// Style Tab - Icon
		$this->start_controls_section(
			'section_style_icon',
			[
				'label' => __('Image', 'bdthemes-faq'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_image' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'icon_image_size',
			[
				'label' => __('Image Size', 'bdthemes-faq'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 32,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdtfq-faq-icon img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'icon_bg_color',
			[
				'label' => __('Background Color', 'bdthemes-faq'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdtfq-faq-icon' => 'background-color: {{VALUE}};',
				],
			]
		);

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'icon_border',
                'selector' => '{{WRAPPER}} .bdtfq-faq-icon',
            ]
        );

		$this->add_responsive_control(
			'icon_border_radius',
			[
				'label' => __('Border Radius', 'bdthemes-faq'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .bdtfq-faq-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_responsive_control(
			'icon_padding',
			[
				'label' => __('Padding', 'bdthemes-faq'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .bdtfq-faq-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_spacing',
			[
				'label' => __('Spacing', 'bdthemes-faq'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default' => [
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .bdtfq-faq-item-inner' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Style Tab - Title
		$this->start_controls_section(
			'section_style_title',
			[
				'label' => __('Title', 'bdthemes-faq'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'selector' => '{{WRAPPER}} .bdtfq-faq-title',
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => __('Color', 'bdthemes-faq'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdtfq-faq-title' => 'color: {{VALUE}};',
					'{{WRAPPER}} .bdtfq-faq-title a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'title_hover_color',
			[
				'label' => __('Hover Color', 'bdthemes-faq'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdtfq-faq-title a:hover' => 'color: {{VALUE}};',
				],
			]
		);

        $this->add_responsive_control(
            'title_margin',
            [
                'label' => __('Margin', 'bdthemes-faq'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .bdtfq-faq-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

		$this->end_controls_section();

		// Style Tab - Excerpt
		$this->start_controls_section(
			'section_style_excerpt',
			[
				'label' => __('Text', 'bdthemes-faq'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_excerpt' => 'yes',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'excerpt_typography',
				'selector' => '{{WRAPPER}} .bdtfq-faq-excerpt',
			]
		);

		$this->add_control(
			'excerpt_color',
			[
				'label' => __('Color', 'bdthemes-faq'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdtfq-faq-excerpt' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		// Style Tab - Category
		$this->start_controls_section(
			'section_style_category',
			[
				'label' => __('Category', 'bdthemes-faq'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_category' => 'yes',
				],
			]
		);

        $this->start_controls_tabs( 'category_tabs_style' );
		$this->start_controls_tab(
			'category_normal',
			[ 
				'label' => esc_html__( 'Normal', 'bdthemes-faq' ),
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'category_typography',
				'selector' => '{{WRAPPER}} .bdtfq-faq-category',
			]
		);

		$this->add_control(
			'category_color',
			[
				'label' => __('Text Color', 'bdthemes-faq'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdtfq-faq-category' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'category_bg_color',
			[
				'label' => __('Background Color', 'bdthemes-faq'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdtfq-faq-category' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'category_padding',
			[
				'label' => __('Padding', 'bdthemes-faq'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em'],
				'selectors' => [
					'{{WRAPPER}} .bdtfq-faq-category' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'category_border_radius',
			[
				'label' => __('Border Radius', 'bdthemes-faq'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .bdtfq-faq-category' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'category_spacing',
			[
				'label' => __('Bottom Spacing', 'bdthemes-faq'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default' => [
					'size' => 8,
				],
				'selectors' => [
					'{{WRAPPER}} .bdtfq-faq-category-wrapper' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'category_gap',
			[
				'label' => __('Gap Between Items', 'bdthemes-faq'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 30,
					],
				],
				'default' => [
					'size' => 6,
				],
				'selectors' => [
					'{{WRAPPER}} .bdtfq-faq-category-wrapper' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);
        $this->end_controls_tab();
        $this->start_controls_tab(
            'category_hover',
            [
                'label' => esc_html__( 'Hover', 'bdthemes-faq' ),
            ]
        );
        $this->add_control(
            'category_text_hover_color',
            [
                'label' => __('Text Color', 'bdthemes-faq'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdtfq-faq-category:hover' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'category_bg_hover_color',
            [
                'label' => __('Background Color', 'bdthemes-faq'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdtfq-faq-category:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'category_border_hover_color',
            [
                'label' => __('Border Color', 'bdthemes-faq'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdtfq-faq-category:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$args = [
			'post_type' => 'faq',
			'posts_per_page' => $settings['faq_count'],
			'order' => $settings['faq_order'],
			'orderby' => $settings['faq_orderby'],
		];

		if (!empty($settings['faq_filter'])) {
			// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query -- Required for filtering FAQs by taxonomy
			$args['tax_query'] = [
				[
					'taxonomy' => 'faq_filter',
					'field' => 'slug',
					'terms' => $settings['faq_filter'],
				],
			];
		}

		$faq_query = new WP_Query($args);

		if (!$faq_query->have_posts()) {
			echo '<p class="bdtfq-faq-no-items">' . esc_html__('No FAQ items found.', 'bdthemes-faq') . '</p>';
			return;
		}

		?>
		<div class="bdtfq-faq-list-wrapper">
			<div class="bdtfq-faq-list">
				<?php while ($faq_query->have_posts()) : $faq_query->the_post(); 
					$show_image = false;
					$image_html = '';
					
					if ($settings['show_image'] === 'yes') {
						if ($settings['image_source'] === 'featured' && has_post_thumbnail()) {
							$show_image = true;
							$image_html = get_the_post_thumbnail(get_the_ID(), $settings['featured_image_size']);
						} elseif ($settings['image_source'] === 'custom') {
							$faq_icon = get_post_meta(get_the_ID(), 'bdt_faq_image_url', true);
							if ($faq_icon) {
								$show_image = true;
								$image_html = '<img src="' . esc_url($faq_icon) . '" alt="' . esc_attr(get_the_title()) . '">';
							}
						}
					}
				?>
					<div class="bdtfq-faq-item">
						<div class="bdtfq-faq-item-inner">
							<?php if ($show_image) : ?>
								<div class="bdtfq-faq-icon">
								<?php echo wp_kses_post($image_html); ?>
								</div>
							<?php endif; ?>
							<div class="bdtfq-faq-content">
								<?php if ($settings['show_category'] === 'yes') : 
									$categories = get_the_terms(get_the_ID(), 'faq_filter');
									if ($categories && !is_wp_error($categories)) :
								?>
									<div class="bdtfq-faq-category-wrapper">
										<?php foreach ($categories as $category) : ?>
											<span class="bdtfq-faq-category"><?php echo esc_html($category->name); ?></span>
										<?php endforeach; ?>
									</div>
								<?php endif; endif; ?>
								<h3 class="bdtfq-faq-title">
									<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
								</h3>
								<?php if ($settings['show_excerpt'] === 'yes') : ?>
									<div class="bdtfq-faq-excerpt">
										<?php 
										if (has_excerpt()) {
											the_excerpt();
										} else {
										echo wp_kses_post(wp_trim_words(get_the_content(), $settings['excerpt_length'], '...'));
										}
										?>
									</div>
								<?php endif; ?>
							</div>
						</div>
					</div>
				<?php endwhile; wp_reset_postdata(); ?>
			</div>
		</div>
		<?php
	}
}
