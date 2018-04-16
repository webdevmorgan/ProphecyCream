<?php
/**
 * WooCommere Top User Container.
 *
 * @author     ThemeFusion
 * @copyright  (c) Copyright by ThemeFusion
 * @link       http://theme-fusion.com
 * @package    Avada
 * @subpackage Core
 * @since      5.1
 */

global $woocommerce, $current_user;
?>
<div class="avada-myaccount-user">
	<?php if ( isset($current_user->display_name) ) { ?>
	<div class="avada-myaccount-user-column username">
		
			<span class="hello">
				<?php
				printf(
					/* translators: %1$s: Username. %2$s: Username (same as %1$s). %3$s: "Sign Out" link. */
					esc_attr__( 'Hello %1$s (not %2$s? %3$s)', 'Avada' ),
					'<strong>' . esc_html( $current_user->display_name ) . '</strong></span><span class="not-user">',
					esc_html( $current_user->display_name ),
					'<a href="' . esc_url( wc_get_endpoint_url( 'customer-logout', '', wc_get_page_permalink( 'myaccount' ) ) ) . '">' . esc_attr__( 'Sign Out', 'Avada' ) . '</a>'
				);
				?>
			</span>
		
			

	</div>
	<?php } ?>

	<?php if ( Avada()->settings->get( 'woo_acc_msg_1' ) ) : ?>
		<div class="avada-myaccount-user-column message">
			<span class="msg"><?php echo Avada()->settings->get( 'woo_acc_msg_1' ); // WPCS: XSS ok. ?></span>
		</div>
	<?php endif; ?>

	<?php if ( Avada()->settings->get( 'woo_acc_msg_2' ) ) : ?>
		<div class="avada-myaccount-user-column message">
			<span class="msg"><?php echo Avada()->settings->get( 'woo_acc_msg_2' ); // WPCS: XSS ok. ?></span>
		</div>
	<?php endif; ?>
</div>
