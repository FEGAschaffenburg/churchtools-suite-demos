<?php
/**
 * Demo Users Admin View
 *
 * @package ChurchTools_Suite_Demo
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="wrap">
	<h1><?php esc_html_e( 'Demo-Registrierungen', 'churchtools-suite-demo' ); ?></h1>
	
	<!-- Statistics -->
	<div class="cts-stats" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin: 20px 0;">
		<div class="cts-stat-card" style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
			<h3 style="margin: 0 0 10px; font-size: 14px; color: #666;"><?php esc_html_e( 'Gesamt', 'churchtools-suite-demo' ); ?></h3>
			<p style="margin: 0; font-size: 32px; font-weight: bold; color: #2563eb;"><?php echo esc_html( $stats['total'] ); ?></p>
		</div>
		<div class="cts-stat-card" style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
			<h3 style="margin: 0 0 10px; font-size: 14px; color: #666;"><?php esc_html_e( 'Verifiziert', 'churchtools-suite-demo' ); ?></h3>
			<p style="margin: 0; font-size: 32px; font-weight: bold; color: #16a34a;"><?php echo esc_html( $stats['verified'] ); ?></p>
		</div>
		<div class="cts-stat-card" style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
			<h3 style="margin: 0 0 10px; font-size: 14px; color: #666;"><?php esc_html_e( 'Unverifiziert', 'churchtools-suite-demo' ); ?></h3>
			<p style="margin: 0; font-size: 32px; font-weight: bold; color: #eab308;"><?php echo esc_html( $stats['unverified'] ); ?></p>
		</div>
		<div class="cts-stat-card" style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
			<h3 style="margin: 0 0 10px; font-size: 14px; color: #666;"><?php esc_html_e( 'Letzte 7 Tage', 'churchtools-suite-demo' ); ?></h3>
			<p style="margin: 0; font-size: 32px; font-weight: bold; color: #9333ea;"><?php echo esc_html( $stats['last_7_days'] ); ?></p>
		</div>
	</div>
	
	<!-- Actions -->
	<div class="tablenav top">
		<div class="alignleft actions">
			<a href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin-ajax.php?action=cts_demo_export_users' ), 'cts_demo_admin', 'nonce' ) ); ?>" class="button">
				<?php esc_html_e( 'Als CSV exportieren', 'churchtools-suite-demo' ); ?>
			</a>
		</div>
	</div>
	
	<!-- Users Table -->
	<table class="wp-list-table widefat fixed striped">
		<thead>
			<tr>
				<th><?php esc_html_e( 'E-Mail', 'churchtools-suite-demo' ); ?></th>
				<th><?php esc_html_e( 'Name', 'churchtools-suite-demo' ); ?></th>
				<th><?php esc_html_e( 'Firma/Gemeinde', 'churchtools-suite-demo' ); ?></th>
				<th><?php esc_html_e( 'Status', 'churchtools-suite-demo' ); ?></th>
				<th><?php esc_html_e( 'Letzter Login', 'churchtools-suite-demo' ); ?></th>
				<th><?php esc_html_e( 'Registriert', 'churchtools-suite-demo' ); ?></th>
				<th><?php esc_html_e( 'Aktionen', 'churchtools-suite-demo' ); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php if ( empty( $users ) ) : ?>
				<tr>
					<td colspan="7" style="text-align: center; padding: 40px 20px; color: #666;">
						<?php esc_html_e( 'Noch keine Registrierungen vorhanden', 'churchtools-suite-demo' ); ?>
					</td>
				</tr>
			<?php else : ?>
				<?php foreach ( $users as $user ) : ?>
					<tr data-user-id="<?php echo esc_attr( $user->id ); ?>">
						<td><strong><?php echo esc_html( $user->email ); ?></strong></td>
						<td><?php echo esc_html( $user->name ?: '-' ); ?></td>
						<td><?php echo esc_html( $user->company ?: '-' ); ?></td>
						<td>
							<?php if ( $user->verified_at ) : ?>
								<span style="color: #16a34a;">✓ <?php esc_html_e( 'Verifiziert', 'churchtools-suite-demo' ); ?></span>
							<?php else : ?>
								<span style="color: #eab308;">⏳ <?php esc_html_e( 'Ausstehend', 'churchtools-suite-demo' ); ?></span>
							<?php endif; ?>
						</td>
						<td><?php echo esc_html( $user->last_login_at ? date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $user->last_login_at ) ) : '-' ); ?></td>
						<td><?php echo esc_html( date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $user->created_at ) ) ); ?></td>
						<td>
							<button type="button" class="button button-small cts-delete-user" data-id="<?php echo esc_attr( $user->id ); ?>">
								<?php esc_html_e( 'Löschen', 'churchtools-suite-demo' ); ?>
							</button>
						</td>
					</tr>
				<?php endforeach; ?>
			<?php endif; ?>
		</tbody>
	</table>
</div>

<script>
jQuery(document).ready(function($) {
	$('.cts-delete-user').on('click', function() {
		if (!confirm('<?php esc_html_e( 'Wirklich löschen?', 'churchtools-suite-demo' ); ?>')) {
			return;
		}
		
		const userId = $(this).data('id');
		const $row = $('tr[data-user-id="' + userId + '"]');
		
		$.ajax({
			url: ajaxurl,
			method: 'POST',
			data: {
				action: 'cts_demo_delete_user',
				nonce: '<?php echo wp_create_nonce( 'cts_demo_admin' ); ?>',
				id: userId
			},
			success: function(response) {
				if (response.success) {
					$row.fadeOut(300, function() { $(this).remove(); });
				} else {
					alert(response.data.message);
				}
			}
		});
	});
});
</script>
