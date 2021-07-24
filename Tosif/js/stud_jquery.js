  var txt='';
  var val,flg=0,th_id;
  var data=new Object();
  var temp=[],s=[],b;
  var x=new Object();
	var url="XML/student.xml";
$(document).ready(function(){
	$.ajax({
		
		  type:"GET",
		  url:url,
		  dataType:"xml",
		  success: function(dt){
		  data=dt;
		  getData();
			     		
		 }//end success
		
		});//ajax end
		
	
	});//end of ready function
function  getData(id)
{ 
	
	 x=$(data).find('student');
	 
	 
	 temp=new Array();
	$(x).each(function(key,value) 
	{   
		console.log(this.nodeName);
		var name=$(this).attr('name');
		var phy=$(this).find('physics').text();
		var maths=$(this).find('maths').text();
		var english=$(this).find('english').text();
		var total=parseInt(phy)+parseInt(maths)+parseInt(english);
		var avg=total/x.length;
		
		if(id && id!="")
		{
			temp.push(eval(id)+"#"+key);
		}
		else
		{
		   temp.push("#"+key);	
		}
		
	});
		
	    if(flg==0)
		{
			flg=1;
		}
		
		if(id!=th_id)
		{
			temp.sort();
			flg=2;
			th_id=id;
		}
		else
		{
			if(flg==1)
			{
				
				temp.sort();
				flg=2;
				th_id=id;
			}
			else if(flg==2)
			{
				temp.sort();
				temp.reverse();
				flg=1;
				th_id=id;
			}
		}
	tempSplit();
}
function tempSplit()
{	
				
		var txt="<table align='center' cellspacing='5' border='1'><tr><th id='name' onclick='getData(this.id)'> Name</th><th  id='phy' onclick='getData(this.id)'>Physics </th><th id='maths' onclick='getData(this.id)'>Maths </th> <th id='english' onclick='getData(this.id)'>English </th><th id='total' onclick='getData(this.id)'>Total </th><th id='avg' onclick='getData(this.id)'>avg</th> </tr>";	
	
		$(temp).each(function(key,value)
		{
			var s=temp[key].split("#");
			var b=s[1];
			var name=$(x[b]).attr('name');
			var phy=$(x[b]).find('physics').text();
			var maths=$(x[b]).find('maths').text();
			var english=$(x[b]).find('english').text();
			var total=parseInt(phy)+parseInt(maths)+parseInt(english);
			var avg=total/x.length;
			txt=txt+"<tr><td>"+name+"</td><td>"+phy+"</td><td>"+maths+"</td><td>"+english+"</td><td>"+total+"</td><td>"+avg.toPrecision(5)+"</td></tr>";
		
		});//end of ech function
		txt=txt+"</table>";
		$("#myDiv").html(txt);			
								
}