
		<!-- フッター -->
		<footer class="mainFooter">
			<div class="footer-logo">
				<img src="http://localhost/wordpress\wp-content\themes\twentytwenty\assets\images\フッターロゴ.png" alt="">
			</div>

			<nav class="footer-content">
				<ul>
					<li class="footer-item">
						<a class="mainHeaderNavColor" href="<?php echo home_url()?>">HOME　|</a>												
					</li>
					<li class="footer-item">
						<a class="mainHeaderNavColor" href="<?php echo home_url('conditions')?>">　サイト利用規約　|</a>
					</li>
					<li class="footer-item">
						<a class="mainHeaderNavColor" href="<?php echo home_url('policy')?>">　プライバシーポリシー</a>
					</li>

				</ul>
			</nav>
			<div class="footer-border"></div>
			<div class="copyright" ><small>Copyright©︎2022 PEACEFUL LIFE</small></div>
		</footer>

		<?php wp_footer(); ?>

	</body>
</html>
