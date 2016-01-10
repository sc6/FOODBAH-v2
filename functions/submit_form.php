<?
	
	require_once 'private/connect.php';
	require_once 'private/queries.php';
	
	$action 	= $_POST['action'];			
	$id			= $_POST['id'];				
	$content 	= $_POST['content'];

	if($action == "submit_vendorReview") addVendorReview($mysqli, $id, $content);
