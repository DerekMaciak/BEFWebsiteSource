$(document).ready(function(){ 
	
	// ACCORDION
	
	var accordion_active_class = 'accordion-active';
	var sections = $('.accordion > section');	
	var section_headings = $('.accordion > section h1');	
	
	function scrollToSection(section) {
	
		$('html, body').animate({
			scrollTop: parseInt($(section).offset().top) - 10
		});
	
	}
	
	function openSection(section) {
	
		// if not already open
		if (!$(section).hasClass(accordion_active_class)) {

			// hide all section content
			$('.section-content', sections).hide();
			
			// show current section content
			$('.section-content', section).hide().fadeTo(500, 1); // fadeTo looks better than fadeIn in IE7

			// move active class to new active section
			sections.removeClass(accordion_active_class);
			$(section).addClass(accordion_active_class);

			// scroll there, because if a really big section was closed, things are still off
			scrollToSection(section);
			
		}
	
	}	

	section_headings.click(function() {

		var section = $(this).parent();
	
		// if clicked section is not active
		if (!$(section).hasClass(accordion_active_class)) {
			openSection(section);
		}
		
		// clicked section is active, collapse it
		else {
		
			// hide section content
			$('.section-content', sections).hide();
			
			// remove active class
			sections.removeClass(accordion_active_class);

		}
			
	});

		// CSS fixes for IE7/8 which doesn't support :not or :last-child
		$('.accordion section  .section-content > p:last-child').css('margin-bottom', '0');
		$('.accordion section:not(.' + accordion_active_class + ') .section-content').hide(); 

		/* Scroll to and open section */
		$("a[data-rel^='openSection']").click(function(event) {

			// stop click action 
			event.preventDefault();

			/* which section? */
			var section = $($(this).attr('href'));

			/* open section */
			openSection(section);
			
			/* scroll to it */
			scrollToSection(section);

		});
		
	// Scroll to section via hash tag
	if(window.location.hash) {
		openSection(window.location.hash);
	}

});