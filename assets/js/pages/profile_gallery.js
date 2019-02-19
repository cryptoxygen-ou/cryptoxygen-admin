var gallery_val = true;
$(document).ready(function() {
  // Tabs to Accordion in Responsive view

    $('.responsive-tabs').responsiveTabs({
      accordionOn: ['xs', 'sm']
    });
    //take tab up
      $('.accordion-link').on('click', function(e) { 
        $('html,body').animate({scrollTop: $(this).offset().top - 100}, 800);
      }); 

   // Gallery isotop and load on click
  
   //$('.galleryTab').click(function (e) { //alert();
//       $.ajax({
//            type: "POST",
//            data: { },
//            url: "user/profile/gallery_load",
//            success: function(result) { 
//                //console.log(result);
//                if(result != '') {
//                    $('.img_gallery .photos').html(result);
//                }
//                $('.photos').gallerify.render();
//            }
//        });
  //$(document).onload(function() {
      //e.preventDefault()
      //$(this).tab('show')
      //alert();
      
      //gallery.gallerify();
      //console.log(gallery);
    //}); 
    var gallery = $('.photos').gallerify({
        margin:1,
        mode:'default',
        lastRow:'adjust'
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

    function loadGallery(setIDs, setClickAttr){ //alert();
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
            var $sel = selector; //console.log($sel);
            current_image = $sel.data('image-id');
            $('#image-gallery-caption').text($sel.data('caption'));
            $('#image-gallery-title').text($sel.data('title'));
            $('#image-gallery-image').attr('src', $sel.data('image'));
            $('#deleteGallery').attr('data-id', $sel.data('file'));
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
    $('#image-gallery').on('show.bs.modal', function() { //alert();
      setTimeout(function() {
        $('.modal-backdrop').addClass('bg-black');
      }, 10);
    });
    
    $('.gallery-files').fileinput({
        language: 'es',
        uploadUrl: 'user/profile/add_gallery',
        uploadExtraData: {},
        allowedFileExtensions : ['jpg', 'png','gif', 'jpeg'],        
        showCancel : false,
        showClose  : false,
        showRemove: false,
        showUploadedThumbs: false,
        removeFromPreviewOnError: true,
        fileActionSettings : {            
            showZoom : false,
        },
        ajaxSettings : { global: false },
        maxFileCount: 10,
        msgFilesTooMany: "Number of files selected for upload ({n}) exceeds maximum allowed limit of 10"
    });
    
    $('.gallery-files').on('fileuploaded', function(event, data, previewId, index) {  //alert();
        //console.log(event);
        //console.log(data.height);
        
        var image = '<div class="photo"><a href="#" class="thumbnail" data-image-id="" data-toggle="modal" data-title="This is my title" data-caption="Some lovely red flowers" data-image="'+data.response+'" data-target="#image-gallery"><img src="'+data.response+'" alt="pic"></a></div>';

        $('.photos.xgallerify').append(image);
        
        var form = "#gallery_form";
        
        //show dropzone
        if(jQuery.trim($(form).find(".file-preview-thumbnails .file-live-thumbs").html()) == "" && $(form).find(".file-drop-zone .file-drop-zone-title").length == 0){
            $(form).find(".file-drop-zone").prepend('<div class="file-drop-zone-title">Drag & drop files here …</div>');
        }
        
        //hide progress bar after upload
        if($(form).find(".file-preview-frame").length == 0){
            $(form).find(".kv-upload-progress").addClass("hide");
            $(form).find(".file-caption-name").html('');
        }
        
        $('.gallery-files').fileinput('destroy');
        $('.gallery-files').fileinput({
            language: 'es',
            uploadUrl: 'user/profile/add_gallery',
            uploadExtraData: {},
            allowedFileExtensions : ['jpg', 'png','gif', 'jpeg'],        
            showCancel : false,
            showClose  : false,
            showRemove: false,
            showUploadedThumbs: false,
            removeFromPreviewOnError: true,
            fileActionSettings : {            
                showZoom : false
            },
            ajaxSettings : { global: false},
            maxFileCount: 10,
            msgFilesTooMany: "Number of files selected for upload ({n}) exceeds maximum allowed limit of 10"
        });
        $('.gallery-files').fileinput('enable');
        
        // toggle upload image form
        $('.upload_gallery').css('display', 'none');
        
        //Gallery reload
        //$('.photos').gallerify.render();
        //$( ".galleryTab" ).trigger( "click" ); 
        load_gallery_all();
//        $('.photos').gallerify.render();
//        loadGallery(true, 'a.thumbnail');
 
    });
    
    $(document).on('click', '.kv-error-close', function() {
        var form = "#gallery_form";
        //show dropzone
        if($(form).find(".file-preview-thumbnails").is(':empty') && $(form).find(".file-drop-zone .file-drop-zone-title").length == 0){
            $(form).find(".file-drop-zone").prepend('<div class="file-drop-zone-title">Drag & drop files here …</div>');
        }
    });
    
    $('.galleryTab').click(function (e) {
        if(gallery_val) {
            load_gallery_all();
            gallery_val = false;
        }
    }); 
        
    $(document).on('click', '.deleteGallery', function (e) {
       $.ajax({
            type: "POST",
            data: { id: $(this).attr('data-id') },
            url: "user/profile/gallery_delete",
            success: function(result) {
                if(result != '') {
                    $('.img_gallery .photos').html(result);
                }
                $('#image-gallery').modal('hide');
                $('.photos').gallerify.render();
                loadGallery(true, 'a.thumbnail');
            }
        });
        $('#image-gallery').modal('hide');
    }); 
    
    function load_gallery_all() {
        var loc = window.location.pathname;
        $.ajax({
            type: "POST",
            data: { 'loc': loc },
            url: "user/profile/gallery_load",
            success: function(result) {
                if(result != '') {
                    $('.img_gallery .photos').html(result);
                }
                $('.photos').gallerify.render();
                loadGallery(true, 'a.thumbnail');
            }
        });
    }
    
});