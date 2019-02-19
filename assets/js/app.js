$(document).ready(function() {

         $('.btn-show-nav').click(function(){    
            $('.right_main').toggleClass('show');
        });
	    $('.input-append.date').datepicker({
	      format:'dd/mm/yyyy'
	    });

		// Modal date picker
		$('#addfamily_model').on('modal-body', function (e) {
		     $('.input-append.date').datepicker({
		      format:'dd/mm/yyyy'
		    });
		});


		
		    $("input[name$='cars']").click(function() {
		        var test = $(this).val();

		        $("div.desc").hide();
		        $("#Cars" + test).show();
		    });
		

		// Tabs to Accordion in Responsive view

		$('.responsive-tabs').responsiveTabs({
		  accordionOn: ['xs', 'sm']
		});
		//take tab up
	    $('.accordion-link').on('click', function(e) { 
	      $('html,body').animate({scrollTop: $(this).offset().top - 100}, 800);
	    }); 

	 // Gallery isotop and load on click
	
	$('.galleryTab').click(function (e) { //alert();
      //e.preventDefault()
      //$(this).tab('show')
      var gallery = $('.photos').gallerify({
      margin:1,
      mode:'default',
      lastRow:'hidden'
    });
    });			

   loadGallery(true, 'a.thumbnail');

    //This function disables buttons when needed
    function disableButtons(counter_max, counter_current){
        $('#show-previous-image, #show-next-image').show();
        if(counter_max == counter_current){
            $('#show-next-image').hide();
        } else if (counter_current == 1){
            $('#show-previous-image').hide();
        }
    }

    /**
     *
     * @param setIDs        Sets IDs when DOM is loaded. If using a PHP counter, set to false.
     * @param setClickAttr  Sets the attribute for the click handler.
     */

    function loadGallery(setIDs, setClickAttr){
        var current_image,
            selector,
            counter = 0;

        $('#show-next-image, #show-previous-image').click(function(){
            if($(this).attr('id') == 'show-previous-image'){
                current_image--;
            } else {
                current_image++;
            }

            selector = $('[data-image-id="' + current_image + '"]');
            updateGallery(selector);
        });

        function updateGallery(selector) {
            var $sel = selector;
            current_image = $sel.data('image-id');
            $('#image-gallery-caption').text($sel.data('caption'));
            $('#image-gallery-title').text($sel.data('title'));
            $('#image-gallery-image').attr('src', $sel.data('image'));
            disableButtons(counter, $sel.data('image-id'));
        }

        if(setIDs == true){
            $('[data-image-id]').each(function(){
                counter++;
                $(this).attr('data-image-id',counter);
            });
        }
        $(setClickAttr).on('click',function(){
            updateGallery($(this));
        });
    }

    //  Backdrop color Gallery
    $('#image-gallery').on('show.bs.modal', function() {
		  setTimeout(function() {
		    $('.modal-backdrop').addClass('bg-black');
		  }, 10);
		});

    // Show hide Sidebar 
});
