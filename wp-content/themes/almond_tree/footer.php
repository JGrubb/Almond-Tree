<?php
/**
 * @package WordPress
 * @subpackage Starkers
 */
?>
		<div id="footer">
			<div class="ad-list">
				<ul>
					<li><a target="_blank" href="http://www.ce.org/"><img src="<?php echo get_option('home'); ?>/images/cea-logo.jpg" alt="CEA" width="105" height="58" /></a></li> 
					<li><a target="_blank" href="http://www.cedia.net/"><img src="<?php echo get_option('home'); ?>/images/cedia-logo.png" alt="CEDIA" width="94" height="58" /></a></li> 
				    <li><a target="_blank" href="http://www.infocomm.org/"><img src="<?php echo get_option('home'); ?>/images/infocomm-logo.gif" alt="InfoComm" width="117" height="58" /></a></li> 
					<li><a target="_blank" href="http://www.nsca.org/"><img src="<?php echo get_option('home'); ?>/images/nsca-logo.png" alt="NSCA" width="100" height="58" /></a></li> 
				</ul> 
			</div>
							
			<div class="footer-nav">
				<ul>
					<?php wp_list_pages ("title_li=&sort_column=menu_order&depth=1"); ?>
				</ul>
			</div>
			
				<p>Copyright &copy; 2009 Almond Tree Marketing LLC. All rights reserved. All trademarks and names mentioned herein are the property of their respective owners.<br /> 
							*Limited quantities available. Almond Tree Marketing LLC maintains the right to modify this promotion at any time. Additional restrictions may apply.</p>
		<?php wp_footer(); ?>
		</div>
	</div>

	</body>

</html>