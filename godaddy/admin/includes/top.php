<?php //ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
if(!isset($_SESSION)) 
{ 
	session_start(); 
}   
if(empty($_SESSION["validadmin"]) || $_SESSION["validadmin"]!=1)
{
	header('location:index.php');
}
require("../includes/connection.php") 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ZipCroc - Admin Area</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="Content-Language" content="en-us" />
<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
<link rel="stylesheet" type="text/css" href="css/base.css"></link>
<link rel="stylesheet" type="text/css" href="css/jquery-ui.css"></link>
        
<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript" src="../js/jquery.validate.js"></script>
<script type="text/javascript" src="../js/jquery-ui.js"></script>
<script type="text/javascript" language="javascript">
	
	$(document).ready(function(){
		doStartPaging();
		$(".trd").hide();
		
		dialog = $( ".dialog-order-status" ).dialog({
		  autoOpen: false,
		  height: 200,
		  width: 350,
		  modal: true
		});
		dialog = $( ".dialog-zip-update" ).dialog({
		  autoOpen: false,
		  height: 200,
		  width: 350,
		  modal: true
		});
		dialog = $( ".dialog-store-update" ).dialog({
		  autoOpen: false,
		  height: 200,
		  width: 350,
		  modal: true
		});
	});
	function showDet(tdid)
	{
		var xid = "#"+tdid;
		$(xid).show();
	}
	function closedet(tdid)
	{
		var xid = "#"+tdid;
		$(xid).hide();
	}	
    function checkFileType(src, arg) {

        var fileName = arg.Value;
        var ext;
        ext = fileName.substr(fileName.lastIndexOf('.')).toLowerCase();
        if (fileName == '') {
            arg.IsValid = false;
        }
        if ('.jpg, .gif, .png, .jpeg, .jpe,.JPG,.GIF,.PNG,.JPEG,.JPE'.indexOf(ext + ',') < 0) {
            arg.IsValid = false;
        }
        else
            arg.IsValid = true;
    }
    function documentType(src, arg) {

        var fileName = arg.Value;
        var ext;
        ext = fileName.substr(fileName.lastIndexOf('.')).toLowerCase();
        if (fileName == '') {
            arg.IsValid = false;
        }
        if ('.pdf, .ppt,.txt, .docx, .doc,.xls,.xlsx'.indexOf(ext + ',') < 0) {
            arg.IsValid = false;
        }
        else
            arg.IsValid = true;
    }
    function CheckKeyCode(e) {
        if (navigator.appName == "Microsoft Internet Explorer") {
            if ((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode == 8) || (e.keyCode == 46)) {
                return true;
            }
            else {
                return false;
            }
        }
        else {
            if ((e.charCode >= 48 && e.charCode <= 57) || (e.charCode == 0) || (e.keyCode == 46)) {
                return true;
            }
            else {
                return false;
            }
        }
    }
	function doStartPaging()
	{
			var pathname = window.location.pathname;
			
			var show_per_page = 3  //Rows Per Page
			if(pathname.indexOf('zipcodes') > 0)
				show_per_page = 15;
            var number_of_items = $('#content tr.paging').size();
            var number_of_pages = Math.ceil(number_of_items / show_per_page);  
            $('#current_page').val(0);
            $('#show_per_page').val(show_per_page);
            var navigation_html = '<a class="previous_link toplinks" href="javascript:previous();">Prev</a>';
            var current_link = 0;
            while (number_of_pages > current_link) {
                navigation_html += '<a class="page_link toplinks" href="javascript:go_to_page(' + current_link + ')" longdesc="' + current_link + '">' + (current_link + 1) + '</a>';
                current_link++;
            }
            navigation_html += '<a class="next_link toplinks" href="javascript:next();">Next</a>';
            $('#page_navigation').html(navigation_html);
            $('#page_navigation .page_link:first').addClass('active_page toplinks');
            $('#content tr.paging').css('display', 'none');
            $('#content tr.paging').slice(0, show_per_page).css('display', '');	
	}
	function previous() {
		new_page = parseInt($('#current_page').val()) - 1;
		if ($('.active_page').prev('.page_link').length == true) {
			go_to_page(new_page);
		}
		$(".trd").hide();
	}

	function next() {
		new_page = parseInt($('#current_page').val()) + 1;
		if ($('.active_page').next('.page_link').length == true) {
			go_to_page(new_page);
		}
		$(".trd").hide();
	}
	function go_to_page(page_num) {
		var show_per_page = parseInt($('#show_per_page').val());
		start_from = page_num * show_per_page;
		end_on = start_from + show_per_page;
		$('#content tr.paging').css('display', 'none').slice(start_from, end_on).css('display', '');
		$('.page_link[longdesc=' + page_num + ']').addClass('active_page').siblings('.active_page').removeClass('active_page');
		$('#current_page').val(page_num);
		$(".trd").hide();
	}	
	function ShowOrderUpdateDialog(orderid)
	{
		var ord = "#order-status-"+orderid;
		dialog = $( ord ).dialog({autoOpen: false,
                    maxHeight: 150,
                    height: 150,
                    modal: true});

		dialog.dialog( "open" );
	}	
	function ShowZipUpdate(orderid)
	{
		var ord = "#zip-update-"+orderid;
		dialog = $( ord ).dialog({autoOpen: false,
                    maxHeight: 150,
                    height: 150,
                    modal: true});

		dialog.dialog( "open" );
	}
	function ShowStoreUpdate(orderid)
	{
		var ord = "#zip-update-"+orderid;
		dialog = $( ord ).dialog({autoOpen: false,
                    maxHeight: 150,
                    height: 150,
                    modal: true});

		dialog.dialog( "open" );
	}
	
	
</script>	
<style>
	#page_navigation a {
		padding: 3px;
		margin: 2px;
		color: black;
		text-decoration: none;
	}

	.active_page {
		background: #49c5ac;
		color: white !important;
	}
</style>
</head>
<body>
	<table border="0" cellspacing="0" cellpadding="0" width="1000" summary="Administrative Area" align="center">
			<tr>
				<td height="77px"><img src="images/head_admin.jpg" /> </td>
			</tr>
			<tr>
				<td>