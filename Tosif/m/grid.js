// JavaScript Document
var flg_browser_name=0;
navigator.sayswho= (function(){
    var ua= navigator.userAgent, 
    N= navigator.appName, tem, 
    M= ua.match(/(opera|chrome|safari|firefox|msie|trident)\/?\s*([\d\.]+)/i) || [];
    M= M[2]? [M[1], M[2]]:[N, navigator.appVersion, '-?'];
    if(M && (tem= ua.match(/version\/([\.\d]+)/i))!= null) M[2]= tem[1];
	var bname=M.join(' ');
	if(bname.indexOf('Trident') != -1)
	{
		flg_browser_name=1;
	}
})();

function filter_grid(e)
{
	if(e.keyCode == 13)
	{
		//	loadGrid();
		set_filter();
		return false;
	}
}

function set_filter()
{
	var arrFilterVal = new Array();
	var filter = "";
	for(var i=0;i<arrFilter.length;i++)
	{
		filter += "&"+arrFilter[i]+"="+((document.getElementById('fd_filter_'+arrFilter[i])) ? document.getElementById('fd_filter_'+arrFilter[i]).value : "");
		arrFilterVal[i] = ((document.getElementById('fd_filter_'+arrFilter[i])) ? document.getElementById('fd_filter_'+arrFilter[i]).value : "");
	}
	//alert(filter)
	renderGrid(arrHeader,arrFilter,xmlDoc,"divGrid",arrFilterVal);	
	if(current_filter != "")
	{
		var ctrl = document.getElementById(current_filter);
		var pos = ctrl.value.length;		
		if(flg_browser_name==1)
		{
			if(ctrl.setSelectionRange(pos,pos))
			{			
				ctrl.focus();
				ctrl.select(); 			
			}
		}
		else
		{
			if(ctrl.setSelectionRange)
			{
				ctrl.focus();
				ctrl.select(); 				
			}
		}
		if (ctrl.createTextRange) 
		{
			var range = ctrl.createTextRange();
			range.collapse(true);
			range.moveEnd('character', pos);
			range.moveStart('character', pos);
			range.select();
		}
	}	
}
function ShowTypeChild(typeRow)
{
	
	var num_rows = document.getElementById("typeTR"+typeRow).value;

	for(var i=0;i<num_rows;i++)
	{
		if(document.getElementById("child_"+typeRow+"_"+i).style.display=='none')
		{
			document.getElementById("child_"+typeRow+"_"+i).style.display='';
			document.getElementById("ImgTypeId"+typeRow).src="images/minus_active1.jpg";
		}
		else
		{
			document.getElementById("child_"+typeRow+"_"+i).style.display='none';
			document.getElementById("ImgTypeId"+typeRow).src="images/plus_inactive.jpg";
			 
		}
	}
}

function getNodeValue(parent,tagName) 
{ 
	var node = parent.getElementsByTagName(tagName)[0];
	//alert(node); 
	return (node && node.firstChild) ? node.firstChild.nodeValue : ""; 
}

function MouseOver(elmId)
{ 
     elm = document.getElementById(elmId);
	 if(elm.id != "") 
	 {
		elm.style.backgroundColor = "#CCFFCC";
	    elm.style.textAlign = "left";
	    elm.style.cursor = "pointer";
	 }
}

function MouseOut(elmId,rid)
{	 
 	 elm = document.getElementById(elmId);
	 fid = document.getElementById("id").value;
	 if(elm.id != "") 
	 {	
	   elm.style.backgroundColor = (fid==rid)? "#FFCC99" :"";
	   elm.style.textAlign = "left";
	   elm.style.cursor = "pointer";
	 }	 
}