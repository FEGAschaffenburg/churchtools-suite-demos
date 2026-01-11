<?php
/**
 * Demo Registration Form Template
 *
 * @package ChurchTools_Suite_Demo
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="cts-demo-registration">
	<div class="cts-demo-registration-header">
		<h2><?php esc_html_e( 'Backend-Demo anfordern', 'churchtools-suite-demo' ); ?></h2>
		<p><?php esc_html_e( 'Erhalten Sie kostenlosen Zugang zum Plugin-Backend und testen Sie alle Funktionen.', 'churchtools-suite-demo' ); ?></p>
	</div>
	
	<form id="cts-demo-registration-form" class="cts-demo-form">
		<div class="cts-form-group">
			<label for="cts-demo-email">
				<?php esc_html_e( 'E-Mail-Adresse', 'churchtools-suite-demo' ); ?>
				<span class="required">*</span>
			</label>
			<input type="email" id="cts-demo-email" name="email" required placeholder="ihre@email.de">
		</div>
		
		<div class="cts-form-group">
			<label for="cts-demo-name">
				<?php esc_html_e( 'Name', 'churchtools-suite-demo' ); ?>
				<span class="required">*</span>
			</label>
			<input type="text" id="cts-demo-name" name="name" required placeholder="Max Mustermann">
		</div>
		
		<div class="cts-form-group">
			<label for="cts-demo-company">
				<?php esc_html_e( 'Firma / Gemeinde', 'churchtools-suite-demo' ); ?>
			</label>
			<input type="text" id="cts-demo-company" name="company" placeholder="Musterkirche e.V.">
		</div>
		
		<div class="cts-form-group">
			<label for="cts-demo-purpose">
				<?php esc_html_e( 'Verwendungszweck', 'churchtools-suite-demo' ); ?>
			</label>
			<textarea id="cts-demo-purpose" name="purpose" rows="3" placeholder="Ich möchte das Plugin für..."></textarea>
		</div>
		
		<div class="cts-form-group cts-checkbox-group">
			<label class="cts-checkbox-label">
				<input type="checkbox" id="cts-demo-privacy" name="privacy_accepted" required>
				<span>
					<?php
					printf(
						/* translators: %s: Privacy policy link */
						esc_html__( 'Ich akzeptiere die %s und stimme der Speicherung meiner Daten zu.', 'churchtools-suite-demo' ),
						'<a href="' . esc_url( get_privacy_policy_url() ) . '" target="_blank">' . esc_html__( 'Datenschutzerklärung', 'churchtools-suite-demo' ) . '</a>'
					);
					?>
					<span class="required">*</span>
				</span>
			</label>
			<p class="cts-privacy-note">
				<?php esc_html_e( 'Ihre Daten werden nur für die Demo-Registrierung verwendet und nach 30 Tagen automatisch gelöscht.', 'churchtools-suite-demo' ); ?>
			</p>
		</div>
		
		<div class="cts-form-actions">
			<button type="submit" class="cts-submit-btn">
				<span class="btn-text"><?php esc_html_e( 'Demo-Zugang anfordern', 'churchtools-suite-demo' ); ?></span>
				<span class="btn-spinner" style="display: none;">
					<span class="spinner"></span>
					<?php esc_html_e( 'Wird gesendet...', 'churchtools-suite-demo' ); ?>
				</span>
			</button>
		</div>
		
		<div class="cts-form-message" style="display: none;"></div>
	</form>
	
	<div class="cts-demo-info">
		<h3><?php esc_html_e( 'Was passiert nach der Registrierung?', 'churchtools-suite-demo' ); ?></h3>
		<ol>
			<li><?php esc_html_e( 'Sie erhalten eine Bestätigungs-E-Mail mit einem Verifizierungslink', 'churchtools-suite-demo' ); ?></li>
			<li><?php esc_html_e( 'Nach dem Klick auf den Link werden Sie automatisch eingeloggt', 'churchtools-suite-demo' ); ?></li>
			<li><?php esc_html_e( 'Sie können direkt das Plugin-Backend erkunden', 'churchtools-suite-demo' ); ?></li>
			<li><?php esc_html_e( 'Ihr Demo-Zugang ist 30 Tage gültig und wird dann automatisch gelöscht', 'churchtools-suite-demo' ); ?></li>
		</ol>
	</div>
</div>
