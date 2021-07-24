<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<style>
select{

 width:auto;
 min-width:auto;
 display: inline-block;
}
</style>
<script src="../jquery.js"></script>
<script>
var DateObj={};
DateObj[1]={"d|##|1":"D","dd|##|1":"DD"};
DateObj[2]={"m|##|2":"M","mm|##|2":"MM","mmm|##|2":"MMM"};
DateObj[3]={"yy|##|3":"YY","yyyy|##|3":"YYYY"};

var sepratorObj={};
sepratorObj={1:"/",2:".",3:"-"};

var dval=0,dval2=0;
function getcom(val,f)
{
	if(f==1){
		if(val.value!=0){
			dval=val.value.split("|##|")[1];
		}
	}
	if(f==2){
		if(val.value!=0){
			dval2=val.value.split("|##|")[1];
		}
	}
}
var i='';
function excludeArr(flg)
{
	var Obj={};
	for(d in DateObj){
		switch(flg){
			case 0: $.extend(Obj,DateObj[d]);
			break;
		    case 1: 
					if(d!=dval){
					  $.extend(Obj,DateObj[d]);
					} 
			break;
			case 2:
					if(d!==dval && d!=dval2){
					 $.extend(Obj,DateObj[d])
					}
			break; 
			default : 
			break;    		
		}
	}
	/*if(flg==0)
	{
		$.extend(Obj,DateObj[1],DateObj[2],DateObj[3]);
	}
	else if(flg==1)
	{
		 

		for(d in DateObj)
		{
			
			if(d!=dval)
			{
			 $.extend(Obj,DateObj[d]);
			}
			
		}
	}
	else if(flg==2)
	{
		
		for(d in DateObj)
		{
			if(d!==dval && d!=dval2)
			{
			 $.extend(Obj,DateObj[d])
			}
		}
	}*/
	return Obj;
}
function getCombo(val,v,flg)
{
	
	if(val==4){
		var filStr="";
		var temp={}
		var flgarr=[0,1,2,4];
		if(v!=''){
		 getcom(v,flg);
		}
		temp=excludeArr(flg);
	
		if($("#select_0").length!=1 || $("#select_1").length!=1 || $("#select_2").length!=1){
		  for(var i=0;i<flgarr.length;i++){
			  if(flgarr[i]==4){
				  temp=sepratorObj;
			  }
		      filStr +=getDrop(flgarr[i],temp);
		  }
		}else if($("#select_0").length>0 || $("#select_1").length>0 || $("#select_2").length>0){
				if(flg!=3){
				 $("#div_"+flg).replaceWith(getDrop(flg,temp));
				}
			}		
	}
	$("#tbl").append(filStr);
}
function getDrop(i,temp)
{
	var filStr="";	
	filStr +="<div style='float:left;margin-left:2px;' id=div_"+i+">"
	filStr +="<select id=select_"+i+" onChange=getCombo(4,this,"+(i+1)+")>";
	filStr +="<option value=0>select</option>"
	for(d in temp){
		filStr +="<option value="+d+" >"+temp[d]+"</option>"
	}
	filStr +="</select>";
	filStr +="</div>";
    return filStr;
}
</script>
</head>

<body>
<table><tr><td>
<select id="sel_1" onChange="getCombo(this.value,'',0)">
<option value="">SELECT</option>
<option value="4">DATE</option>
</select>
</td><td id="tbl"></td></tr></table>
</body>
</html>