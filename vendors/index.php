<!DOCTYPE HTML>

<? 
	require_once '../functions/private/connect.php';
	require_once '../functions/private/queries.php';


	$id		= $_GET['id'];		//the unique identifier of the vendor

	
	class VendorResult {
		public $name = "unknown";
		public $address = "123";
		public $city = "unknown";
		public $zip = 11111;
		public $distance = -1.1;
		
		function __construct($id, $facility, $address, $city, $zip, $distance) {
			$this->id = $id;
			$this->facility = $facility;
			$this->address = $address;
			$this->city = $city;
			$this->zip = $zip;
			$this->distance = $distance;
		}
	}
	
	$vendor = getVendorById($mysqli, $id);
	
?>




<HTML>

<head>
<title><?=$vendor->facility?> - FOODBAH</title>
<link rel="stylesheet" type="text/css" href="/assets/header.css" />
<link rel="stylesheet" type="text/css" href="/assets/vendors.css" />
<link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
<link href='https://fonts.googleapis.com/css?family=Lato' rel='stylesheet' type='text/css'>
<link href='https://fonts.googleapis.com/css?family=Montserrat:700' rel='stylesheet' type='text/css'>
</head>

<body>
	<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>

<!--Header-->
<header>
	<div class="header_content_area">
		<a class="logo" href="/">FOODBAH</a>
		<a class="register" href="#">Register</a>
		<a class="signIn" href="#">Sign in</a>
	</div>
</header>


<div class="spacer_100"></div>

<h1><?=$vendor->facility?></h1>

<div class="spacer_100"></div>

<div class="result_container">

	<div class="restaurant_result_large">
		<img src="http://placehold.it/200x150" />
		<div class="details">
			<?=$vendor->facility?><br>
			<?=$vendor->address?><br>
			<?=$vendor->city?>, CA <?=$vendor->zip?><br><br>
			There are 0 posts about this place.
		</div>
	</div>
	
	<span class="showSubmissionForm" id="showVendorReview">Write a vendor review</span> | Write an item review | View items by rating
	
	<div class="spacer_100"></div>
	
	<form id="vendorReviewForm">
		<!--PRE format; do not change indentation-->
		<div class="submission_vendorReviewContent" contenteditable onkeyup="formatEntry()"><strong>[Add a title]</strong>
<span class="date">January 4th 2015</span>

Describe your experience here.</div>
		<div class="submission_vendorReviewFormat">
			<div class="submission_instructions">
				Press "Finalize" in order to unlock the submit button.
			</div>
			<input id="submission_vendorReviewSubmit" type="submit" disabled></input>
			<input id="submission_vendorReviewFix" type="button" value="Finalize"></input>
		</div>
		
		<div class="spacer_100"></div>
		
		<input type='hidden' name='newVendorReview' />
	</form>
	
	
	
	<div id="vendor_content">
	<? foreach(getAllVendorReviews($mysqli, $id) as $review) { ?>
		<p>
			<?=$review->content?>
		</p>
		<div class="spacer_100"></div>
	<? } ?>
	</div>
</div>


<script>
	
	$('#showVendorReview').on("click", function() {
		$('#vendorReviewForm').slideDown();
	});
	
	$('#submission_vendorReviewFix').on("click", function() {
		$(this).attr('disabled', 'disabled');
		$(".submission_instructions").html("Press the submit button to publish your entry.");
		$(".submission_vendorReviewContent").removeAttr('contentEditable');
		$(".submission_vendorReviewContent").css("background-color", "#DDDDDD");
		$('#submission_vendorReviewSubmit').removeAttr('disabled');
	});
	
	//submit form
	$(document).ready(function () {
    $('#vendorReviewForm').on('submit', function(e) {
        e.preventDefault();
		var content = $(".submission_vendorReviewContent").html();
        $.ajax({
            url : "/functions/submit_form.php",
            type: "POST",
            data: { action:"submit_vendorReview", id:"<?=$id?>", content:""+content },
            error: function (jXHR, textStatus, errorThrown) {
                alert(errorThrown);
            }
			});
		$("#vendorReviewForm").slideUp();
		$("#vendor_content").prepend("<div class='message'>Your review has been submitted successfully; refresh the page to view.</div><div class='spacer_100'></div>");
		$("#mynewpost").slideDown();
		});
	});
	
	// Source: [1] http://stackoverflow.com/questions/6023307/dealing-with-line-breaks-on-contenteditable-div
	// - Deals with Safari, Chrome browsers for submission_vendorReviewContent
	$(function(){

	  $(".submission_vendorReviewContent")

	  // make sure br is always the lastChild of contenteditable
	  .on("keyup mouseup", function(){
		if (!this.lastChild || this.lastChild.nodeName.toLowerCase() != "br") {
		  this.appendChild(document.createElement("br"));
		 }
	  })

	  // use br instead of div div
	  .on("keypress", function(e){
		if (e.which == 13) {
		  if (window.getSelection) {
			var selection = window.getSelection(),
			  range = selection.getRangeAt(0),
			  br = document.createElement("br");
			range.deleteContents();
			range.insertNode(br);
			range.setStartAfter(br);
			range.setEndAfter(br);
			range.collapse(false);
			selection.removeAllRanges();
			selection.addRange(range);
			return false;
		  }
		}
	  });
	});
	
		
		
		/*function formatEntry() {
			alert("Working...");
			$('.submission_vendorReviewContent').blur(function() {
				var content = $(this).html();
				var lines = $('.submission_vendorReview').val().split('\n');
				for(var i = 0;i < lines.length;i++){
					if(i == 0) lines.val("<strong>[Key pressed]</strong>")
				}

			});
		}*/
		
		//$('#vendorReviewForm').submit(function()
		//{
		//  $('#vendorReviewSubmit').val($('.submission_vendorReviewContent').html());
		//});
</script>


</body>

</HTML>

