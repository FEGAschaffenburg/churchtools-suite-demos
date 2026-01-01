	</main>
	
	<footer class="site-footer">
		<div class="container">
			<div class="footer-content">
				<div class="footer-section">
					<h3>ChurchTools Suite</h3>
					<p>Professionelle ChurchTools-Integration für WordPress</p>
					<ul>
						<li><a href="<?php echo home_url( '/demos/' ); ?>">Live Demos</a></li>
						<li><a href="<?php echo home_url( '/documentation/' ); ?>">Dokumentation</a></li>
						<li><a href="https://github.com/FEGAschaffenburg/churchtools-suite" target="_blank">GitHub Repository</a></li>
					</ul>
				</div>
				
				<div class="footer-section">
					<h3>Demos</h3>
					<ul>
						<li><a href="<?php echo home_url( '/demos/calendar/' ); ?>">Calendar Demos</a></li>
						<li><a href="<?php echo home_url( '/demos/list/' ); ?>">List Demos</a></li>
						<li><a href="<?php echo home_url( '/demos/grid/' ); ?>">Grid Demos</a></li>
						<li><a href="<?php echo home_url( '/demos/slider/' ); ?>">Slider Demos</a></li>
					</ul>
				</div>
				
				<div class="footer-section">
					<h3>Dokumentation</h3>
					<ul>
						<li><a href="<?php echo home_url( '/documentation/installation/' ); ?>">Installation</a></li>
						<li><a href="<?php echo home_url( '/documentation/configuration/' ); ?>">Konfiguration</a></li>
						<li><a href="<?php echo home_url( '/documentation/shortcodes/' ); ?>">Shortcode-Referenz</a></li>
						<li><a href="<?php echo home_url( '/documentation/templates/' ); ?>">Templates</a></li>
					</ul>
				</div>
				
				<div class="footer-section">
					<h3>Support</h3>
					<ul>
						<li><a href="https://github.com/FEGAschaffenburg/churchtools-suite/issues" target="_blank">Issues melden</a></li>
						<li><a href="https://github.com/FEGAschaffenburg/churchtools-suite/discussions" target="_blank">Diskussionen</a></li>
						<li><a href="<?php echo home_url( '/download/' ); ?>">Download</a></li>
					</ul>
				</div>
			</div>
			
			<div class="footer-bottom">
				<p>&copy; <?php echo date( 'Y' ); ?> FEG Aschaffenburg | <a href="https://github.com/FEGAschaffenburg" target="_blank">GitHub</a> | <a href="<?php echo home_url( '/privacy/' ); ?>">Datenschutz</a></p>
			</div>
		</div>
	</footer>
</div>

<?php wp_footer(); ?>
</body>
</html>
