	/* global $  URL  csrf_token */
    
    var files;
    var seconds = 15; //seconds to refresh

    $(document).ready(function () {
		
		$.ajaxSetup({
        	headers: {
            	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        	}
		});
    	showHideImagesPanel();	
    	launchRefreshInterval(seconds);
    
		// Add events
		$('input[type=file]').on('change', function(event) {
		    // Grab the files and set them to our variable
		    	
		    files = event.target.files;
            if (files.length == 0)  return;
            else uploadFiles(event);
		});
		
		// $('form').on('submit', uploadFiles);
		
    	$('#fileBrowseBtn').click(function(){
            event.stopPropagation(); // Stop stuff happening
    		event.preventDefault(); // Totally stop stuff happening
    		var $title = $("#title").val();
    		var goOn = true;
    		/*if ($title.trim().length == 0) {
    		    goOn = confirm("Are you sure you dont wanna add a title?");
    		}*/
    		if (goOn){
    		    $('#image').click();
    		}
    	});
    	
    	$("#csvExportBtn").click(function() {
    	    window.location = URL.exportCsv;
    	});
    	
    	$("#excelExportBtn").click(function() {
            window.location = URL.exportExcel;
    	});

    	$("#zipExportBtn").click(function() {
    		$('#zipping').show();
    		playWithProgressBar();
    		setTimeout(function() {
    			$('#zipping').fadeOut();
    		}, 30000); //30 secs fadeout
        	window.location = URL.exportZip;
    	});
    	

    });
    
    function playWithProgressBar(timeInterval, minChunk, maxChunk) {
    	timeInterval = timeInterval || 500;
    	minChunk = minChunk || 5;
    	maxChunk = maxChunk || 18;
    	
    	$('#progressbar').show();

    	var itvl = setInterval(function(){
    			var $bar = $($('#progressbar .progress-bar').get(0));
    			var w = parseInt($bar.attr('aria-valuenow'), 10);
    			var increase = Math.floor(Math.random() * maxChunk) + minChunk;
    				w = w+increase;
	    		$bar.attr('aria-valuenow', w);
    			$bar.width(w + "%");
 
    			if (w > 100) {
    				clearInterval(itvl);
    				setTimeout(function(){
    					$('#progressbar').fadeOut();
    					$bar.attr('aria-valuenow', 0);
    					$bar.width("0%");

    				}, 1500);
    			}
    		}, timeInterval);
    }
    
    function launchRefreshInterval(seconds) {
        /* refresh count every N seconds */
        setInterval(function(){
            $.getJSON(URL.feedCountViews, function( data ) {
    		    $('#viewCount').html(data.count);
        	});
        	$.getJSON(URL.feedCountPosts, function( data ) {
    		    var pc = getCurrentPostCount();
    			if (data.count !== pc) {
    			    //fetch posts
    				$('#images').html("");
    				fetchPosts();
    			}
        	 });
        },seconds * 1000);
    }
    
        
    function getCurrentPostCount() {
    	var pc = parseInt($('#postCount').html(), 10);
		return pc;
    }
    
     function fetchPosts() {
    	$.getJSON(URL.getPosts, function( posts ) {
    		for (var i in posts) {
    		    prependResult(posts[i]);
    		}
		    $('#postCount').html(posts.length);
        });
    }
    	
    function prependResult(data) {
    	var tpl = `
    	<div class="post_element row" style="margin-bottom: 20px;">
			<p> Title: ##title## <span class="pull-right"> uploaded @ ##uploadedTime## </span></p>
			<hr>
		    <img src="##url##" width="100%"></img>
		</div>
    	`;
    	var title = data.title ? data.title : "";
    	var rendered = tpl.replace("##title##", title)
    				.replace("##url##", data.image_url)
    				.replace("##uploadedTime##", data.created_at);
    	
		 $('#images').prepend(rendered);
    }
   
	function uploadFiles(event) {
	    var files = event.target.files;
	    if(!files || (files && files.length == 0)) return;
	    
		showUploading();
		hideErrors();
		
		playWithProgressBar(350, 12, 20);

    	// Create a formdata object and add the files
    	var data = new FormData();
    	data.append('_token', csrf_token);
    	data.append('image', files[0]);
    	data.append('title', $('#title').val().trim());
		doPost(data);
	}
	
	function doPost(data) {
		$.ajax({
			url:  URL.storePost,
		    type: 'POST',
		    data: data,
		    cache: false,
		    timeout: 30000,
		    processData: false, // Don't process the files
		    contentType: false, // Set content type to false as jQuery will tell the server its a query string request
		    success: successFn,
		    error: errorFn
		});
    }
	
    function successFn(data, textStatus, jqXHR) {
		prependResult({
			image_url: data.image_url,
			created_at: getCurrentFormattedTime(),
			title: $('#title').val()
		});
		$('#title').val("");
		$('#image').val("");
		$('#postCount').html(getCurrentPostCount()+1);
		hideUploading();
		showHideImagesPanel();
    }
    
    function getCurrentFormattedTime() {
		var d = new Date();
		var datestring = ("0" + d.getDate()).slice(-2) + "-" + ("0"+(d.getMonth()+1)).slice(-2) + "-" +
    	d.getFullYear() + ", " + ("0" + d.getHours()).slice(-2) + ":" + ("0" + d.getMinutes()).slice(-2);
		return datestring;
	}
    
    function errorFn(jqXHR, textStatus, errorThrown) {
		console.log(jqXHR.responseJSON);
		hideUploading();
		var errors = jqXHR.responseJSON || {};
		if(textStatus == "timeout") {
        	errors['timeout'] = "Timeout...";
        }
		showErrors(errors);
    }
    
    function showHideImagesPanel() {
	    if ($('.post_element').length > 0) {
	    	$('#imagesPanel').show();
	    } else {
	    	$('#imagesPanel').hide();
		}
    }
    	
    function showUploading() {
    	$('#uploading').show();
    	$('#uploadForm').hide();
    }

    function hideUploading() {
    	$('#uploading').fadeOut();
    	$('#progressbar').hide();
    	$('#uploadForm').show();
    }

	function showErrors(errors) {
	    errors = errors || {err: "Unkown Error, try later"};
	    var $err = $('#errors');
	    var $errList = $("#errorList");

	    for (var key in errors) {
	        var value = errors[key];
    	    $errList.append("<li>" + value + "</li>");
	    }
	    $err.show();
        $("#image").val("");
    }

    function hideErrors() {
    	$('#errors').hide();
        var $errList = $("#errorList");
        $errList.html("");

    }
    