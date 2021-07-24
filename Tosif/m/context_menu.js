/**
*
*  Simple Context Menu
*  http://www.webtoolkit.info/
*
**/
var nAgt = navigator.userAgent;
var browserName  = navigator.appName;
if ((verOffset=nAgt.indexOf("Opera"))!=-1) {
	browserName = "Opera";
}
else if ((verOffset=nAgt.indexOf("MSIE"))!=-1) {
 browserName = "Microsoft Internet Explorer"; 
}
else if ((verOffset=nAgt.indexOf("Firefox"))!=-1) {
 browserName = "Firefox"; 
}

var SimpleContextMenu = {
 
	// private attributes
	_menus : new Array,
	_attachedElement : null,
	_menuElement : null,
	_preventDefault : true,
	_preventForms : true,
 
 
	// public method. Sets up whole context menu stuff..
	setup : function (conf) {
 
		if ( document.all && document.getElementById && !window.opera ) {
			SimpleContextMenu.IE = true;
		}
 
		if ( !document.all && document.getElementById && !window.opera ) {
			SimpleContextMenu.FF = true;
		}
 
		if ( document.all && document.getElementById && window.opera ) {
			SimpleContextMenu.OP = true;
		}
 
		if ( SimpleContextMenu.IE || SimpleContextMenu.FF ) {
 
			document.oncontextmenu = SimpleContextMenu._show;
			document.onclick = SimpleContextMenu._hide;
 
			if (conf && typeof(conf.preventDefault) != "undefined") {
				SimpleContextMenu._preventDefault = conf.preventDefault;
			}
 
			if (conf && typeof(conf.preventForms) != "undefined") {
				SimpleContextMenu._preventForms = conf.preventForms;
			}
 
		}
 
	},
 
 
	// public method. Attaches context menus to specific class names
	attach : function (classNames, menuId) {
 
		if (typeof(classNames) == "string") {
			SimpleContextMenu._menus[classNames] = menuId;
		}
 
		if (typeof(classNames) == "object") {
			for (x = 0; x < classNames.length; x++) {
				SimpleContextMenu._menus[classNames[x]] = menuId;
			}
		}
 
	},
 
 
	// private method. Get which context menu to show
	_getMenuElementId : function (e) {
 
		if (SimpleContextMenu.IE) {
			SimpleContextMenu._attachedElement = event.srcElement;
		} else {
			SimpleContextMenu._attachedElement = e.target;
		}
 
		while(SimpleContextMenu._attachedElement != null) {
			var className = SimpleContextMenu._attachedElement.className;
 
			if (typeof(className) != "undefined") {
				className = className.replace(/^\s+/g, "").replace(/\s+$/g, "")
				var classArray = className.split(/[ ]+/g);
 
				for (i = 0; i < classArray.length; i++) {
					if (SimpleContextMenu._menus[classArray[i]]) {
						return SimpleContextMenu._menus[classArray[i]];
					}
				}
			}
 
			if (SimpleContextMenu.IE) {
				SimpleContextMenu._attachedElement = SimpleContextMenu._attachedElement.parentElement;
			} else {
				SimpleContextMenu._attachedElement = SimpleContextMenu._attachedElement.parentNode;
			}
		}
 
		return null;
 
	},
 
 
	// private method. Shows context menu
	_getReturnValue : function (e) {
 
		var returnValue = true;
		var evt = SimpleContextMenu.IE ? window.event : e;
 
		if (evt.button != 1) {
			if (evt.target) {
				var el = evt.target;
			} else if (evt.srcElement) {
				var el = evt.srcElement;
			}
 
			var tname = el.tagName.toLowerCase();
 
			if ((tname == "input" || tname == "textarea")) {
				if (!SimpleContextMenu._preventForms) {
					returnValue = true;
				} else {
					returnValue = false;
				}
			} else {
				if (!SimpleContextMenu._preventDefault) {
					returnValue = true;
				} else {
					returnValue = false;
				}
			}
		}
 
		return returnValue;
 
	},
 
 
	// private method. Shows context menu
	_show : function (e) {
 
		SimpleContextMenu._hide();
		var menuElementId = SimpleContextMenu._getMenuElementId(e);
 		
		if (menuElementId) {
			var m = SimpleContextMenu._getMousePosition(e);
			var s = SimpleContextMenu._getScrollPosition(e);
			
			var total_width=$(window).width();
			var total_height=$(window).height();
				
			var final_x=m.x;
			var final_y=s.y;
			SimpleContextMenu._menuElement = document.getElementById(menuElementId);			

			var fx=total_width-m.x;
			var fy=total_height-m.y;
				
			if(fx<140)
			{
				final_x=total_width-170;
			}
			if(fx<450)
			{	
				if(document.getElementById("Mouse_xy"))
				document.getElementById("Mouse_xy").value=total_width;
			}
			else
			{	
				if(document.getElementById("Mouse_xy"))		
				document.getElementById("Mouse_xy").value=0;
			}	
			SimpleContextMenu._menuElement.style.left = final_x + s.x + 'px';
			if(document.getElementById("total_sub"))
			{
				if(fy<33)
					final_y=s.y-30;
				document.getElementById("total_sub").value=fy;
			}
			else if(fy<119)
			{
				if(document.getElementById("Compo_Type"))
					final_y=s.y-110;
			}
										
			SimpleContextMenu._menuElement.style.top = m.y + final_y +'px';
			
			SimpleContextMenu._menuElement.style.display = 'block';
			return false;
		}
 
		return SimpleContextMenu._getReturnValue(e);
 
	},
 
 
	// private method. Hides context menu
	_hide : function () {
 
		if (SimpleContextMenu._menuElement) {
			SimpleContextMenu._menuElement.style.display = 'none';
		}
 
	},
 
 
	// private method. Returns mouse position
	_getMousePosition : function (e) {
 
		e = e ? e : window.event;
		var position = {
			'x' : e.clientX,
			'y' : e.clientY
		}
 
		return position;
 
	},
 
 
	// private method. Get document scroll position
	_getScrollPosition : function () {
 
		var x = 0;
		var y = 0;
 
		if( typeof( window.pageYOffset ) == 'number' ) {
			x = window.pageXOffset;
			y = window.pageYOffset;
		} else if( document.documentElement && ( document.documentElement.scrollLeft || document.documentElement.scrollTop ) ) {
			x = document.documentElement.scrollLeft;
			y = document.documentElement.scrollTop;
		} else if( document.body && ( document.body.scrollLeft || document.body.scrollTop ) ) {
			x = document.body.scrollLeft;
			y = document.body.scrollTop;		
		}

		var position = {
			'x' : x,
			'y' : y
		}
 
		return position;
 
	}
 
}
function set_submenu(item)
{	
	if(browserName == "Microsoft Internet Explorer")
	{
		if(document.getElementById("total_sub").value<item)
		{
			document.getElementById("updown").style.top="-"+(item-30)+"px";	
		}
		if(document.getElementById("Compo_Type"))
		{
			if(document.getElementById("total_sub").value<55)
			{	
				document.getElementById("updown").style.top="-"+(item-39)+"px";					
			}
			else
			{
				document.getElementById("updown").style.top=""+(20)+"px";									
			}
		}		
	}
	else
	{
		if(document.getElementById("total_sub").value<item)
		{
			document.getElementById("updown").style.top="-"+(item-13)+"px";	
		}
		if(document.getElementById("Compo_Type"))
		{
			if(document.getElementById("total_sub").value<55)
			{
				document.getElementById("updown").style.top="-"+(item-38)+"px";						
			}
		}
	}
}