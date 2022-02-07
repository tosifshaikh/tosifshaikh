var amt;

var rsArray=[];
rsArray[1]=500;
rsArray[2]=100;
rsArray[3]=20;
rsArray[4]=10;
rsArray[5]=5;
rsArray[6]=1;


function submitData()
{
	 amt=document.getElementById("amt").value;
	 loadGrid();
}


function loadGrid()
{
	
	var str="";
	var r_500="",r_100="",r_20="",r_10="",r_5="",r_1="";
	
	str+="<table border=1 cellpadding=5>";	
	
		var rs_500=amt/rsArray[1];		
		if(parseInt(rs_500)==0)
		{		
			rs_500="";	
		}
		
		else
		{
			rs_500=parseInt(rs_500);
			r_500+=parseInt(rs_500)*rsArray[1];
		}
	
	str+="<tr><td align=center>500</td><td>X</td><td>"+rs_500+"</td><td>=</td><td>"+r_500+"</td></tr>";
	amt=amt-r_500;
	
	
		var rs_100=amt/rsArray[2];	
		if(parseInt(rs_100)==0)
		{
			rs_100=""; 
		}
		
		else
		{
			rs_100=parseInt(rs_100);
			r_100+=parseInt(rs_100)*rsArray[2];
		}
	
	str+="<tr><td align=center>100</td><td>X</td><td>"+rs_100+"</td><td>=</td><td>"+r_100+"</td></tr>";
	amt=amt-r_100;
	
		var rs_20=amt/rsArray[3];
		if(parseInt(rs_20)==0)
		{	
			rs_20=""; 
		}
		
		else
		{
			rs_20=parseInt(rs_20);
			r_20+=parseInt(rs_20)*rsArray[3];
		}
	
	str+="<tr><td align=center>20</td><td>X</td><td>"+rs_20+"</td><td>=</td><td>"+r_20+"</td></tr>";
	amt=amt-r_20;
	
		var rs_10=amt/rsArray[4];
		if(parseInt(rs_10)==0)
		{
			rs_10=""; 
		}
		 
		else
		{
			rs_10=parseInt(rs_10);
			r_10+=parseInt(rs_10)*rsArray[4];
		}
	
	str+="<tr><td align=center>10</td><td>X</td><td>"+rs_10+"</td><td>=</td><td>"+r_10+"</td></tr>";
	amt=amt-r_10;
	
		var rs_5=amt/rsArray[5];
		if(parseInt(rs_5)==0)
		{	
		   rs_5=""; 
		}
		
		else
		{
			rs_5=parseInt(rs_5);
			r_5+=parseInt(rs_5)*rsArray[5];
		}
	
	str+="<tr><td align=center>5</td><td>X</td><td>"+rs_5+"</td><td>=</td><td>"+r_5+"</td></tr>";
	amt=amt-r_5;
	
		var rs_1=amt/rsArray[6];
		if(parseInt(rs_1)==0)
		{
			rs_1="";
		}
		
		else
		{
			rs_1=parseInt(rs_1);
			r_1+=parseInt(rs_1)*rsArray[6];
		}
	
	str+="<tr><td align=center>1</td><td>X</td><td>"+rs_1+"</td><td>=</td><td>"+r_1+"</td></tr>";
	amt=amt-r_1;
	
	str+="<tr><td colspan=4><b>Total Amt:</b></td><td><b>"+document.getElementById("amt").value+"</b></td></tr>";
	str+="</table>";
	
	document.getElementById("display_amt").innerHTML=str;
	document.getElementById("amt").value="";
}