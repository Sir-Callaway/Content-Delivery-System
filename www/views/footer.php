<footer>
	<div class="section">
		<ul class="footlist">
			<h2>Follow Us</h2>
			<li><a href="https://www.facebook.com/pages/The-Kenya-Conservatoire-of-Music/131066340666?ref=ts&fref=ts" target="_blank"><img src="/images/system/icons/facebook-48x48.png" />Facebook</a></li>
			<li><a href="http://twitter.com/KConservatoire" target="_blank"><img src="/images/system/icons/twitter-48x48.png" />Twitter</a></li>
			<li><a href="http://flickr.com/KConservatoire" target="_blank"><img src="/images/system/icons/flickr-48x48.png" />Flickr</a></li>
		</ul>
		<ul class="footlist">
			<h2>Other Pages</h2>
			<li><a href="/term_dates">Term Dates</a></li>
			<li><a href="/acknowledgements">Acknowledgements</a></li>
			<li><a href="/terms_and_conditions">Terms & Conditions</a></li>
			<li><a href="/sitemap">Sitemap</a></li>
			<li><a href="/contact_us">Contact Us</a></li>
		</ul> 
		<div class="form">
			<h2>Contact Us</h2>	
			<form id="contactform" action="" method="post" enctype="multipart/form-data" class="footform">
				<ul>						
					<li>
						<label for="name">Your Name</label>
						<input id="name" name="name" type="text" required>
					</li>					
					<li>
						<label for="mail">Your Email</label>
						<input id="mail" name="mail" type="text" required>
					</li>
					<li class="clear">
						<label for="text">Message</label>
						<textarea id="text" name="text" required></textarea>
					</li>
					<li>						
						<button id="footsend" class="btns black" type="" style="display: none!important;">Quick Send</button>
					</li>	
					<li>
						<div id="response" style="margin-top: 1em;"></div>
					</li>
				</ul>
			</form>
		</div>		
	</div>	
	<div class="thin-footer">
		<div class="section">
			<h3>Kenya Conservatoire of Music<sup>&copy;</sup> <?php if((int)date('Y') != 2012)echo '2012 - '.date('Y');else echo date('Y')?></h3>
		</div>
	</div>
</footer>