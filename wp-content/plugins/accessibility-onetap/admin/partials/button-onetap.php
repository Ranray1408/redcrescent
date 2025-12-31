<?php
/**
 * Admin Button Template for Onetap plugin.
 *
 * This template is responsible for rendering the button section
 * of the plugin's admin pages, including logo, documentation links,
 * support links, and navigation menu.
 *
 * @package    Accessibility_Onetap
 * @subpackage Accessibility_Onetap/admin/partials
 */

$onetap_settings = get_option( 'onetap_settings' );
if ( ! is_array( $onetap_settings ) ) {
	$onetap_settings = array();
}

$onetap_toggle_classes = array_filter(
	array(
		! empty( $onetap_settings['border'] ) ? $onetap_settings['border'] : '',
		isset( $onetap_settings['toggle-device-position-desktop'] ) && 'on' === $onetap_settings['toggle-device-position-desktop'] ? 'hide-on-desktop' : '',
		isset( $onetap_settings['toggle-device-position-tablet'] ) && 'on' === $onetap_settings['toggle-device-position-tablet'] ? 'hide-on-tablet' : '',
		isset( $onetap_settings['toggle-device-position-mobile'] ) && 'on' === $onetap_settings['toggle-device-position-mobile'] ? 'hide-on-mobile' : '',
	)
);
?>

<button type="button" aria-label="Toggle Accessibility Toolbar" class="onetap-toggle <?php echo esc_attr( implode( ' ', $onetap_toggle_classes ) ); ?>">
	<?php
	// Define SVG paths for each icon type.
	$onetap_icon_paths = array(
		'design1' => 'assets/images/admin/Original_Logo_Icon.svg',
		'design2' => 'assets/images/admin/Hand_Icon.svg',
		'design3' => 'assets/images/admin/Accessibility-Man-Icon.svg',
		'design4' => 'assets/images/admin/Settings-Filter-Icon.svg',
		'design5' => 'assets/images/admin/Switcher-Icon.svg',
		'design6' => 'assets/images/admin/Eye-Show-Icon.svg',
	);

	// Check if the selected icon exists in the array.
	$onetap_settings = get_option( 'onetap_settings' );
	if ( isset( $onetap_settings['icons'], $onetap_icon_paths[ $onetap_settings['icons'] ] ) ) {
		$onetap_icons = array(
			'design1' => 'Original_Logo_Icon.svg',
			'design2' => 'Hand_Icon.svg',
			'design3' => 'Accessibility-Man-Icon.svg',
			'design4' => 'Settings-Filter-Icon.svg',
			'design5' => 'Switcher-Icon.svg',
			'design6' => 'Eye-Show-Icon.svg',
		);
		foreach ( $onetap_icons as $onetap_icon_value => $onetap_icon_image ) {
			if ( $onetap_icon_value === $onetap_settings['icons'] ) {
				$onetap_class_size   = isset( $onetap_settings['size'] ) ? $onetap_settings['size'] : '';
				$onetap_class_border = isset( $onetap_settings['border'] ) ? $onetap_settings['border'] : '';
				echo '<img class="' . esc_attr( $onetap_class_size ) . ' ' . esc_attr( $onetap_class_border ) . '" src="' . esc_url( ACCESSIBILITY_ONETAP_PLUGINS_URL . 'assets/images/admin/' . $onetap_icon_image ) . '" alt="toggle icon" />';
			}
		}
	} else {
		echo '<img class="design-size2 design-border2" src="' . esc_url( ACCESSIBILITY_ONETAP_PLUGINS_URL . 'assets/images/admin/Original_Logo_Icon.svg' ) . '" alt="toggle icon" />';
	}
	?>
</button>