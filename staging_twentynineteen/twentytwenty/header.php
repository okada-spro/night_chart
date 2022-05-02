
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/single_style.css?<?php echo date('Ymd-His'); ?>" type="text/css" />
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/header_style.css?<?php echo date('Ymd-His'); ?>" type="text/css" />
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/footer_style.css?<?php echo date('Ymd-His'); ?>" type="text/css" />
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/page_style.css?<?php echo date('Ymd-His'); ?>" type="text/css" />
<!DOCTYPE html>

<html>

	<head>
		<?php wp_head(); ?>
	</head>

	<body <?php body_class(); ?>>
		<header class="site-header">
			<div class="sb-site">
				<div class="overlay" id="js__overlay"></div>
					
				<header id="mainHeader" class="wrapper logo-left">
					<div id="shopHeader" class="clearfix">
						<div class="site-logo">
							<img src="<?php echo get_template_directory_uri(); ?>\assets\images\ヘッダーロゴ.png" alt="">
						</div>
						<nav class="crim">
							<ul>
								<li class="header-item">
									<a class="mainHeaderNavColor" href="<?php echo home_url()?>">HOME</a>												
								</li>
								<li class="header-item">
									<a class="mainHeaderNavColor" href="<?php echo home_url('contact')?>">CONTACT</a>
								</li>
								<li class="cart">
									<a href="<?php echo home_url('cart')?>">
										<img src="<?php echo get_template_directory_uri(); ?>\assets\images\outline_shopping_cart_black_24dp.png" alt="shopping cart">
									</a>
								</li>
							</ul>
						</nav>

						<div class="hamburger-menu">
							<input type="checkbox" id="menu-btn-check">
							<label for="menu-btn-check" class="menu-btn"><span></span></label>
							<div class="menu-content">
								<ul>
									<li>
										<a href="#">HOME</a>
									</li>
									<li>
										<a href="#">CONTACT</a>
									</li>
									<li>
										<a href="#">サイト利用規約　</a>
									</li>
									<li>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</header>
			</div>

		</header>