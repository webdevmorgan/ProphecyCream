<?php

if ( fusion_is_element_enabled( 'fusion_image_compare_SM' ) ) {

	if ( ! class_exists( 'FusionSC_ImageCompareframe' ) ) {
		/**
		 * Shortcode class.
		 *
		 * @package fusion-builder
		 * @since 1.0
		 */
		class FusionSC_ImageCompareframe extends Fusion_Element {

			/**
			 * The image-frame counter.
			 *
			 * @access private
			 * @since 1.0
			 * @var int
			 */
			private $imageframe_counter = 1;

			/**
			 * The image data.
			 *
			 * @access private
			 * @since 1.0
			 * @var false|array
			 */
			private $image_data = false;

			/**
			 * An array of the shortcode arguments.
			 *
			 * @access protected
			 * @since 1.0
			 * @var array
			 */
			protected $args;

			/**
			 * Constructor.
			 *
			 * @access public
			 * @since 1.0
			 */
			public function __construct() {
				parent::__construct();
				add_filter( 'fusion_attr_image-shortcode', array( $this, 'attr' ) );
				add_filter( 'fusion_attr_image-shortcode-link', array( $this, 'link_attr' ) );

                add_shortcode( 'fusion_image_compare_SM', array( $this, 'render' ) );
                
                add_shortcode( 'fusion_image_compare_SM', array( $this, 'render_parent' ) );
                add_shortcode( 'fusion_compare_image', array( $this, 'render_child' ) );
                

            }
            

            public function render_parent( $args, $content = '' ) {
                $defaults = FusionBuilder::set_shortcode_defaults(
					array(
						'hide_on_mobile' => fusion_builder_default_visibility( 'string' ),
						'class'          => '',
						'id'             => '',
						'autoplay'       => 'no',
						'border'         => 'yes',
						'columns'        => '5',
						'column_spacing' => '13',
						'image_id'       => '',
						'lightbox'       => 'no',
						'mouse_scroll'   => 'no',
						'picture_size'   => 'fixed',
						'scroll_items'   => '',
						'show_nav'       => 'yes',
						'hover_type'     => 'none',
					),
					$args
				);

				$defaults['column_spacing'] = FusionBuilder::validate_shortcode_attr_value( $defaults['column_spacing'], '' );

				extract( $defaults );

                $this->parent_args = $defaults;

                //split image shortcodes and only use the first 2 as before and after.
                preg_match_all('~\[([^\[\]]+)/\]~is', $content, $matches);
                
                //$html = '<link rel="stylesheet" href="https://codyhouse.co/demo/image-comparison-slider/css/style.css">';
                $html = '';
                $html .= '<figure class="cd-image-container">';
                $html .= do_shortcode( $matches[0][0] );
                $html .= '    <span class="cd-image-label" data-type="original"></span>';
                    
                $html .= '    <div class="cd-resize-img">';
                $html .= do_shortcode( $matches[0][1] );
                $html .= '        <span class="cd-image-label" data-type="modified"></span>';
                $html .= '    </div>';
                    
                $html .= '    <span class="cd-handle"></span>';
                $html .= '</figure>';
                $html .= '<script src="'.get_template_directory_uri().'/assets/min/js/general/image-compare.js"></script>';
                //$html .= '<script src="https://codyhouse.co/demo/image-comparison-slider/js/main.js"></script>';

				$this->imageframe_counter++;

				return $html;
            }

            public function render_child( $args, $content = '' ) {
                global $fusion_library;

				$defaults = FusionBuilder::set_shortcode_defaults(
					array(
						'alt'        => '',
						'image'      => '',
						'link'       => '',
						'linktarget' => '_self',
					), $args
				);

				extract( $defaults );

				$this->child_args = $defaults;

				$width = $height = '';

				$this->image_data = $fusion_library->images->get_attachment_data_from_url( $image );
				if ( $this->image_data ) {
					$image_id = $this->image_data['id'];
				}

				$image_size = 'full';
				if ( 'fixed' === $this->parent_args['picture_size'] ) {
					$image_size = 'portfolio-two';
					if ( '6' === $this->parent_args['columns'] || '5' === $this->parent_args['columns'] || '4' === $this->parent_args['columns'] ) {
						$image_size = 'blog-medium';
					}
				}

				$output = '';
				if ( isset( $image_id ) ) {
					if ( $alt ) {
						$output = wp_get_attachment_image( $image_id, $image_size, false, array( 'alt' => $alt ) );
					} else {
						$output = wp_get_attachment_image( $image_id, $image_size );
					}
				} else {
					$output = '<img src="' . $image . '" alt="' . $alt . '"/>';
				}

				if ( 'no' === $this->parent_args['mouse_scroll'] && ( $link || 'yes' === $this->parent_args['lightbox'] ) ) {
					$output = '<a ' . FusionBuilder::attributes( 'image-carousel-shortcode-slide-link' ) . '>' . $output . '</a>';
				}

				return  $output;
            }

			/**
			 * Render the shortcode
			 *
			 * @access public
			 * @since 1.0
			 * @param  array  $args    Shortcode paramters.
			 * @param  string $content Content between shortcode.
			 * @return string          HTML output.
			 */
			public function render( $args, $content = '' ) {

				global $fusion_library, $fusion_settings;

				$defaults = FusionBuilder::set_shortcode_defaults(
					array(
						'align'               => '',
						'alt'                 => '',
						'animation_direction' => 'left',
						'animation_offset'    => $fusion_settings->get( 'animation_offset' ),
						'animation_speed'     => '',
						'animation_type'      => '',						
						'class'               => '',
						'id'                  => '',
						'style'               => '',
                        'image_id'           => '',
                        'after_image_id'     => '',
						'style_type'          => 'none',  // Deprecated.
					), $args
				);

				if ( ! $defaults['style'] ) {
					$defaults['style'] = $defaults['style_type'];
				}

				extract( $defaults );

				$this->args = $defaults;

				// Add the needed styles to the img tag.

				
				$img_styles = '';


				// Alt tag.
				$title = $alt_tag = $image_url = $image_id = $after_image_id = $image_width = $image_height = '';

				preg_match( '/(src=["\'](.*?)["\'])/', $content, $src );

				if ( array_key_exists( '2', $src ) ) {
					$src = $src[2];
				} elseif ( false === strpos( $content, '<img' ) && $content ) {
					$src = $content;
                }

				if ( $src ) {

					$src = str_replace( '&#215;', 'x', $src );

					$image_url = $this->args['pic_link'] = $src;

					$this->image_data = $fusion_library->images->get_attachment_data_from_url( $this->args['pic_link'] );

					if ( $this->image_data ) {
						$image_width  = ( $this->image_data['width'] ) ? $this->image_data['width'] : '';
						$image_height = ( $this->image_data['height'] ) ? $this->image_data['height'] : '';
                        $image_id       = $this->image_data['id'];
                        $after_image_id = $this->image_data['after'];
						$alt_tag      = ( $this->image_data['alt'] ) ? $this->image_data['alt'] : '';
						$title        = ( $this->image_data['title'] ) ? $this->image_data['title'] : '';
                    }
                    
                    var_dump($this->image_data);

					// For pre 5.0 shortcodes extract the alt tag.
					preg_match( '/(alt=["\'](.*?)["\'])/', $content, $legacy_alt );
					if ( array_key_exists( '2', $legacy_alt ) && '' !== $legacy_alt[2] ) {
						$alt_tag = $legacy_alt[2];
					} elseif ( $alt ) {
						$alt_tag = $alt;
					}

					if ( false !== strpos( $content, 'alt=""' ) && $alt_tag ) {
						$content = str_replace( 'alt=""', $alt_tag, $content );
					} elseif ( false === strpos( $content, 'alt' ) && $alt_tag ) {
						$content = str_replace( '/> ', $alt_tag . ' />', $content );
					}

					if ( 'no' === $lightbox && ! $link ) {
						$title = ' title="' . $title . '"';
					} else {
						$title = '';
					}

					$content = '<img src="' . $image_url . '" width="' . $image_width . '" height="' . $image_height . '" alt="' . $alt_tag . '"' . $title . ' />';
				}

				$img_classes = 'img-responsive';

				if ( ! empty( $image_id ) ) {
					$img_classes .= ' wp-image-' . $image_id;
				}

				// Get custom classes from the img tag.
				preg_match( '/(class=["\'](.*?)["\'])/', $content, $classes );

				if ( ! empty( $classes ) ) {
					$img_classes .= ' ' . $classes[2];
				}

				$img_classes = 'class="' . $img_classes . '"';

				// Add custom and responsive class and the needed styles to the img tag.
				if ( ! empty( $classes ) ) {
					$content = str_replace( $classes[0], $img_classes . $img_styles, $content );
				} else {
					$content = str_replace( '/>', $img_classes . $img_styles . '/>', $content );
				}

				if ( class_exists( 'Avada' ) && property_exists( Avada(), 'images' ) ) {
					Avada()->images->set_grid_image_meta(
						array(
							'layout' => 'large',
							'columns' => '1',
						)
					);
				}

				if ( function_exists( 'wp_make_content_images_responsive' ) ) {
					$content = wp_make_content_images_responsive( $content );
				}

				if ( class_exists( 'Avada' ) && property_exists( Avada(), 'images' ) ) {
					Avada()->images->set_grid_image_meta( array() );
				}

				

				$output = do_shortcode( $content );

				$html = '<span ' . FusionBuilder::attributes( 'image-shortcode' ) . '>' . $output . '</span>';

				if ( 'center' === $align ) {
					$html = '<div ' . FusionBuilder::attributes( 'imageframe-align-center' ) . '>' . $html . '</div>';
				}

				$this->imageframe_counter++;

				return $html;

			}

			/**
			 * Builds the attributes array.
			 *
			 * @access public
			 * @since 1.0
			 * @return array
			 */
			public function attr() {

				global $fusion_settings;

				$attr = array(
					'style' => '',
				);

				$style        = $this->args['style'];

				// Add the needed styles to the img tag.
				$img_styles = '';

				if ( $img_styles ) {
					$attr['style'] .= $img_styles;
				}

				$attr['class'] = 'fusion-imageframe imageframe-' . $this->args['style'] . ' imageframe-' . $this->imageframe_counter;

				if ( $this->args['class'] ) {
					$attr['class'] .= ' ' . $this->args['class'];
				}

				if ( $this->args['id'] ) {
					$attr['id'] = $this->args['id'];
				}

				if ( $this->args['animation_type'] ) {
					$animations = FusionBuilder::animations(
						array(
							'type'      => $this->args['animation_type'],
							'direction' => $this->args['animation_direction'],
							'speed'     => $this->args['animation_speed'],
							'offset'    => $this->args['animation_offset'],
						)
					);

					$attr = array_merge( $attr, $animations );

					$attr['class'] .= ' ' . $attr['animation_class'];
					unset( $attr['animation_class'] );
				}

				return fusion_builder_visibility_atts( $this->args['hide_on_mobile'], $attr );

			}

			/**
			 * Builds the link attributes array.
			 *
			 * @access public
			 * @since 1.0
			 * @return array
			 */
			public function link_attr() {

				$attr = array();

				

				return $attr;

			}

			/**
			 * Adds settings to element options panel.
			 *
			 * @access public
			 * @since 1.1
			 * @return array $sections Image Frame settings.
			 */
			public function add_options() {

				return array(
					'image_shortcode_section' => array(
						'label'       => esc_html__( 'Image Element', 'fusion-builder' ),
						'description' => '',
						'id'          => 'image_shortcode_section',
						'type'        => 'accordion',
						'fields'      => array(
							
						),
					),
				);
			}

			/**
			 * Sets the necessary scripts.
			 *
			 * @access public
			 * @since 1.1
			 * @return void
			 */
			public function add_scripts() {
                Fusion_Dynamic_JS::enqueue_script( 'fusion-animations' );
            }
            
		}
    }
    
    

	new FusionSC_ImageCompareframe();

}

/**
 * Map shortcode to Fusion Builder.
 *
 * @since 1.0
 */
function fusion_element_image_compare() {

	global $fusion_settings;

	fusion_builder_map(
		array(
            'name'          => esc_attr__( 'Image Compare', 'fusion-builder' ),
			'shortcode'     => 'fusion_image_compare_SM',
			'multi'         => 'multi_element_parent',
			'element_child' => 'fusion_compare_image',
			'icon'          => 'fusiona-images',
			'preview'       => FUSION_BUILDER_PLUGIN_DIR . 'inc/templates/previews/fusion-image-compare-preview.php',
			'preview_id'    => 'fusion-builder-block-module-image-compare-preview-template',
			'params'        => array(
                array(
					'type'        => 'tinymce',
					'heading'     => esc_attr__( 'Content', 'fusion-builder' ),
					'description' => esc_attr__( 'Enter some content for this contentbox.', 'fusion-builder' ),
					'param_name'  => 'element_content',
					'value'       => '[fusion_image link="" linktarget="_self" alt="" /]',
				),
				array(
					'type'             => 'multiple_upload',
					'heading'          => esc_attr__( 'Bulk Image Upload', 'fusion-builder' ),
					'description'      => __( 'This option allows you to select multiple images at once and they will populate into individual items. It saves time instead of adding one image at a time.', 'fusion-builder' ),
					'param_name'       => 'multiple_upload',
					'element_target'   => 'fusion_compare_image',
					'param_target'     => 'image',
					'remove_from_atts' => true,
				),
				
				
				array(
					'type'        => 'radio_button_set',
					'heading'     => esc_attr__( 'Align', 'fusion-builder' ),
					'description' => esc_attr__( 'Choose how to align the image.', 'fusion-builder' ),
					'param_name'  => 'align',
					'value'       => array(
						'none'   => esc_attr__( 'Text Flow', 'fusion-builder' ),
						'left'   => esc_attr__( 'Left', 'fusion-builder' ),
						'right'  => esc_attr__( 'Right', 'fusion-builder' ),
						'center' => esc_attr__( 'Center', 'fusion-builder' ),
					),
					'default'     => 'none',
				),
				
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Image Alt Text', 'fusion-builder' ),
					'description' => esc_attr__( 'The alt attribute provides alternative information if an image cannot be viewed.', 'fusion-builder' ),
					'param_name'  => 'alt',
					'value'       => '',
				),
				
				array(
					'type'        => 'select',
					'heading'     => esc_attr__( 'Animation Type', 'fusion-builder' ),
					'description' => esc_attr__( 'Select the type of animation to use on the element.', 'fusion-builder' ),
					'param_name'  => 'animation_type',
					'value'       => fusion_builder_available_animations(),
					'default'     => '',
					'group'       => esc_attr__( 'Animation', 'fusion-builder' ),
				),
				array(
					'type'        => 'radio_button_set',
					'heading'     => esc_attr__( 'Direction of Animation', 'fusion-builder' ),
					'description' => esc_attr__( 'Select the incoming direction for the animation.', 'fusion-builder' ),
					'param_name'  => 'animation_direction',
					'value'       => array(
						'down'   => esc_attr__( 'Top', 'fusion-builder' ),
						'right'  => esc_attr__( 'Right', 'fusion-builder' ),
						'up'     => esc_attr__( 'Bottom', 'fusion-builder' ),
						'left'   => esc_attr__( 'Left', 'fusion-builder' ),
						'static' => esc_attr__( 'Static', 'fusion-builder' ),
					),
					'default'     => 'left',
					'group'       => esc_attr__( 'Animation', 'fusion-builder' ),
					'dependency'  => array(
						array(
							'element'  => 'animation_type',
							'value'    => '',
							'operator' => '!=',
						),
					),
				),
				array(
					'type'        => 'range',
					'heading'     => esc_attr__( 'Speed of Animation', 'fusion-builder' ),
					'description' => esc_attr__( 'Type in speed of animation in seconds (0.1 - 1).', 'fusion-builder' ),
					'param_name'  => 'animation_speed',
					'min'         => '0.1',
					'max'         => '1',
					'step'        => '0.1',
					'value'       => '0.3',
					'group'       => esc_attr__( 'Animation', 'fusion-builder' ),
					'dependency'  => array(
						array(
							'element'  => 'animation_type',
							'value'    => '',
							'operator' => '!=',
						),
					),
				),
				array(
					'type'        => 'select',
					'heading'     => esc_attr__( 'Offset of Animation', 'fusion-builder' ),
					'description' => esc_attr__( 'Controls when the animation should start.', 'fusion-builder' ),
					'param_name'  => 'animation_offset',
					'value'       => array(
						''                => esc_attr__( 'Default', 'fusion-builder' ),
						'top-into-view'   => esc_attr__( 'Top of element hits bottom of viewport', 'fusion-builder' ),
						'top-mid-of-view' => esc_attr__( 'Top of element hits middle of viewport', 'fusion-builder' ),
						'bottom-in-view'  => esc_attr__( 'Bottom of element enters viewport', 'fusion-builder' ),
					),
					'default'     => '',
					'group'       => esc_attr__( 'Animation', 'fusion-builder' ),
					'dependency'  => array(
						array(
							'element'  => 'animation_type',
							'value'    => '',
							'operator' => '!=',
						),
					),
				),
				array(
					'type'        => 'checkbox_button_set',
					'heading'     => esc_attr__( 'Element Visibility', 'fusion-builder' ),
					'param_name'  => 'hide_on_mobile',
					'value'       => fusion_builder_visibility_options( 'full' ),
					'default'     => fusion_builder_default_visibility( 'array' ),
					'description' => esc_attr__( 'Choose to show or hide the element on small, medium or large screens. You can choose more than one at a time.', 'fusion-builder' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'CSS Class', 'fusion-builder' ),
					'description' => esc_attr__( 'Add a class to the wrapping HTML element.', 'fusion-builder' ),
					'param_name'  => 'class',
					'value'       => '',
					'group'       => esc_attr__( 'General', 'fusion-builder' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'CSS ID', 'fusion-builder' ),
					'description' => esc_attr__( 'Add an ID to the wrapping HTML element.', 'fusion-builder' ),
					'param_name'  => 'id',
					'value'       => '',
					'group'       => esc_attr__( 'General', 'fusion-builder' ),
				),
			),
		)
	);
}
add_action( 'fusion_builder_before_init', 'fusion_element_image_compare' );

/**
 * Map shortcode to Fusion Builder.
 */
function fusion_element_fusion_compare_image() {
	fusion_builder_map(
		array(
			'name'              => esc_attr__( 'Image', 'fusion-builder' ),
			'description'       => esc_attr__( 'Enter some content for this textblock.', 'fusion-builder' ),
			'shortcode'         => 'fusion_compare_image',
			'hide_from_builder' => true,
			'params'            => array(
				array(
					'type'        => 'upload',
					'heading'     => esc_attr__( 'Image', 'fusion-builder' ),
					'description' => esc_attr__( 'Upload an image to display.', 'fusion-builder' ),
					'param_name'  => 'image',
					'value'       => '',
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Image ID', 'fusion-builder' ),
					'description' => esc_attr__( 'Image ID from Media Library.', 'fusion-builder' ),
					'param_name'  => 'image_id',
					'value'       => '',
					'hidden'      => true,
				),
				array(
					'type'        => 'link_selector',
					'heading'     => esc_attr__( 'Image Website Link', 'fusion-builder' ),
					'description' => esc_attr__( "Add the url to image's website. If lightbox option is enabled, you have to add the full image link to show it in the lightbox.", 'fusion-builder' ),
					'param_name'  => 'link',
					'value'       => '',
				),
				array(
					'type'        => 'radio_button_set',
					'heading'     => esc_attr__( 'Link Target', 'fusion-builder' ),
					'description' => __( '_self = open in same window <br />_blank = open in new window.', 'fusion-builder' ),
					'param_name'  => 'linktarget',
					'value'       => array(
						'_self'  => esc_attr__( '_self', 'fusion-builder' ),
						'_blank' => esc_attr__( '_blank', 'fusion-builder' ),
					),
					'default'     => '_self',
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Image Alt Text', 'fusion-builder' ),
					'description' => esc_attr__( 'The alt attribute provides alternative information if an image cannot be viewed.', 'fusion-builder' ),
					'param_name'  => 'alt',
					'value'       => '',
				),
			),
		)
	);
}
add_action( 'fusion_builder_before_init', 'fusion_element_fusion_compare_image' );
