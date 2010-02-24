<?php
/**
 * @package WordPress
 * @subpackage Starkers
 */

get_header(); ?>
			<div id="main">
				<div class="content">
					<div class="visual">
						
				<img src="<?php echo get_option('home'); ?>/images/main-image03.jpg" alt="image description" width="720" height="100" />
				
					</div>
			
					<div class="holder"> 
						
						<div class="aside-r"> 
						<div id="social-holder">
<a target="_blank" href="http://www.facebook.com/pages/Almond-Tree-Marketing/285935016263?ref=ts"><img src="<?php echo get_option('home'); ?>/images/facebook.png" alt="Almond Tree Marketing on Facebook" /></a>
<a target="_blank" href="http://twitter.com/almondtreemktg"><img src="<?php echo get_option('home'); ?>/images/twitter.png" alt="Follow Almond Tree Marketing on Twitter" /></a>
<a target="_blank" href="http://www.linkedin.com/companies/almond-tree-marketing"><img src="<?php echo get_option('home'); ?>/images/linkedin.png" alt="Almond Tree Marketing on LinkedIn" /></a>
</div>

							<div class="green-box"> 
							<h3>News and Events</h3>

						    	<div class="green-box-in"> 
						        <p><strong>Almond Tree Marketing</strong> is pleased to announce our new strategic partnership with <a target="_blank" href="http://www.napco.com/">Consumer Technology Publishing Group</a>, a division of North American Publishing Company. <a href="email-campaign/index.html" target="_blank">More info...</a></p> 
						  		<p><a href="PDF/Almond-Tree-Marketing_CTPG-Partnership.pdf" target="_blank">Click here</a> for the Press Release.</p> 
						        </div> 
						    	<span class="img-holder"><img src="<?php echo get_option('home'); ?>/images/news-image01.png" alt="Consumer Technology Publishing Group publications" width="235" height="67" />
										<br /> 
										<br /> 
								<img src="<?php echo bloginfo('home'); ?>/images/ATMBanner100217.gif" alt="All Access Marketing Banner" width="241" height="191" /></span>
							</div> 

						</div>
			
					<div class="text-block">
						<?php the_content(); ?>
					</div>
	
				</div>
					
			</div>
			
		<div class="sidebar">
		<?php get_sidebar(); ?>
		</div>
			

<?php get_footer(); ?>