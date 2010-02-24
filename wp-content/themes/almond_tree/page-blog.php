<?php
/*
Template Name: Blog
*/
?>

<?php get_header(); ?>

<div id="main">
				<div class="content">
					<div class="visual">
						
				<img src="<?php echo get_option('home'); ?>/images/main-image03.jpg" alt="image description" width="720" height="100" />
				
					</div>
			
					<div class="holder"> 
						
						<div class="aside-r"> 

							<div class="green-box"> 
							<h3>News and Events</h3>

						    	<div class="green-box-in"> 
						        <p><strong>Almond Tree Marketing</strong> is pleased to announce our new strategic partnership with <strong>Consumer Technology Publishing Group, </strong>a division of North American Publishing Company. <a href="/email-campaign/index.html" target="_blank">More info...</a></p> 
						  		<p><a href="/PDF/Almond-Tree-Marketing_CTPG-Partnership.pdf" target="_blank">Click here</a> for the Press Release.</p> 
						        </div> 
						    	<span class="img-holder"><img src="<?php echo get_option('home'); ?>/images/news-image01.png" alt="Consumer Technology Publishing Group publications" width="235" height="67" />
										<br /> 
										<br /> 
								<img src="<?php echo get_option('home'); ?>/images/news-image02.jpg" alt="International CES | CEA" width="241" height="191" /></span>
							</div> 

						</div>
				<div class="text-block">
				
				<?php if (have_posts()) : ?>
				
					<?php while (have_posts()) : the_post(); ?>
				
						<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
							<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
							<p><?php the_time('F jS, Y') ?> <!-- by <?php the_author() ?> --></p>
							<?php the_content('Read the rest of this entry &raquo;'); ?>
							<p><?php the_tags('Tags: ', ', ', '<br />'); ?> Posted in <?php the_category(', ') ?> | <?php edit_post_link('Edit', '', ' | '); ?>  <?php comments_popup_link('No Comments &#187;', '1 Comment &#187;', '% Comments &#187;'); ?></p>
						</div>
				
					<?php endwhile; ?>
				
					<?php next_posts_link('&laquo; Older Entries') ?> | <?php previous_posts_link('Newer Entries &raquo;') ?>
				
				<?php else : ?>
				
					<h2>Not Found</h2>
					<p>Sorry, but you are looking for something that isn't here.</p>
					<?php get_search_form(); ?>
					
					<?php endif; ?>
					
						</div>
									
					</div>
					
				</div>



		<div class="sidebar">
		<?php get_sidebar(); ?>
		</div>

<?php get_footer(); ?>