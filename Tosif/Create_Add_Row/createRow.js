var data,z,l=0,num,n,flag,val,indx,space,pos,promBox,newNum;
var url='treeJson1.php';
var rowId=0,pid,cid;
var arr=[],temp=[];
 $(document).ready(function() {
	 
 $.ajax({
		  type:'GET',
		  url:url,
		  dataType:"json",
		  success: function(dt)
		  { 
			data=dt;
			callAjax();
		  }//success end
	  });   //ajax end 
});//ready function end
function callAjax()
{  
    var addRoot=$("<td>").append($('<input>').attr({"type":"button","id":'rootBtn',"onclick":"addNodes("+0+","+0+","+0+","+0+")","value":"Add Root"}));
    $('#mytable').append($("<tr>").append(addRoot).attr("id","tr"+rowId));
    dataPrint(0,1);
}
function dataPrint(id,z)
{
	var p=1;
	for(var k in data[id])
	{
	    n=numGenerator(id,z,p)//number generator 
		for(var j=0;j<=l;j++)
		{
			if(j==l)
			{
				createRow(k,id,n,rowId,j);
			}
		}
		rowId=k;
		l++;
		p++;
		dataPrint(k,n);//recursion function
		l--; 	 
		if(id==0)
		{    
			l=0;
			z++;
		}   	
	}//});//each loop of data
}
function createRow(childId,parenId,index,tr_id,blnk)
{
	var tx='';
	for(var i=0;i<blnk;i++)
	{
		tx+="<td></td>";
	}
	arr.push(data[parenId][childId]);
	var indxSpan=$("<span>").attr({"id":"span"+childId}).text(index);
	var nam=$("<span>").attr({"id":"nmspan"+childId}).text(data[parenId][childId]);
	var add=$("<button>").attr({"id":"btn"+childId,"onclick":"addNodes("+childId+","+parenId+","+(i+1)+")"}).text("Add");
	var edit=$("<button>").attr({"id":"edit"+childId,"onclick":"editNodes("+childId+","+parenId+")"}).text("Edit");
	var del=$("<button>").attr({"id":"del"+childId,"onclick":"confirmDel("+childId+","+parenId+")"}).text("Delete");	
	var nm=$("<td>").append(indxSpan,"   ",nam,add,edit,del).attr("id","id"+childId);			
    $("<tr>").append(tx,nm).insertAfter("#tr"+tr_id).attr("id","tr"+childId);			
}
function addNodes(childId,parenId,spac)
{
	
	pid=parenId;
	cid=childId;
	indx=$("#span"+cid).text();
    space=spac;
	promBox=prompt("ENTER NAME");
	if(promBox!=null)
	{
		flag=1;
		save();	
	}
}
function editNodes(childId,parenId)
{
	pid=parenId;
	cid=childId;
	promBox=prompt("ENTER NAME",data[pid][cid]);
	if(promBox!=null)
	{
		flag=2;
		save();	
	}
}
function confirmDel(childId,parenId)
{
	var delName=confirm("Are You Sure You Want to Delete : "+$("#nmspan"+childId).text());
	if(delName==true)
	{
		delNodes(childId,parenId);
	}
	
}
function delNodes(childId,parenId)
{
	for(var k in data[childId])
	{
		delNodes(k,childId);
	}
	delete data[parenId][childId];
	$("#tr"+childId).remove();	
	del(parenId);
}
function del(parenId)
{
	var p=1;
	for(var k in data[parenId])
	{
		 if(parenId==0)
		{
		   newNum=numGenerator(parenId,p,p);	
		}
		else
		{
			newNum=numGenerator(parenId,$("#span"+parenId).text(),p);
		}
		p++;
		//$("#btn"+k).attr({"onclick":"addNodes("+k+","+parenId+","+(spc+1)+",\""+newNum+"\")"});
		$("#span"+k).text(newNum);
		del(k);	
	}
}
function validate(val,cid)//validation function for edit and add
{	
  try{	
		if(val=="")
		{
		   throw "blank not allowed";
		}
		else if( /[^a-zA-Z0-9\-\_\/]/.test(val))
		   {
			  throw "special characters are not allowed";
		   }
		else 
		{
			for(var k in data[cid])
			{
				if(data[cid][k].toLowerCase()==val.toLowerCase())
				{
					throw "same values are not allowed";
				}
			}
		}
  }
  catch(error)
  {
	  alert("ERROR: "+error);
	  return false;
  }
}
function save()
{
    
	val=promBox.trim();
	var l=arr.length+1;
	if(flag==1)
	{
		var valid_rtn=validate(val,cid);
		if(valid_rtn==false)
		{
			promBox=prompt("enter name","");
		}
		else
		{
			var last_rtn=lastValue(pid,cid);
		     if(last_rtn[1]==0)//  0 child return
			{
				data[cid]={};
				
				var rowid=cid;	
			}
			else
			{
				var rowid=last_rtn[0];
			}
		
		    if(cid==0)
			{
				space=0;
				var n1=numGenerator(0,last_rtn[1]+1,indx);	
			}
			else
			{  
				var n1=numGenerator(l,indx,last_rtn[1]+1);
			}	
			data[cid][l]=val;
		    var rowid=last_rtn[2];
		   alert("New Row "+val+" Added");
		   createRow(l,cid,n1,rowid,space);
	   }
	  
	}
	else if(flag==2)
	{	
		
		var valid_rtn=validate(val,pid);
		if(valid_rtn==false)
		{   
			promBox=prompt("enter name","");
		}
		else
		{    
			alert("value is changed : "+val);
			data[pid][cid]=val;
			$('#nmspan'+cid).html(data[pid][cid]);
		}
		
	}
}
function lastValue(pid,cid)
{
	var cnt=0;
	var temp=[];
	pos=cid;
	for(var i in data[cid])
	{
		 cnt++;
		 pos=i;
		 lastValue(cid,i);
	}
	temp.push(i,cnt,pos);
	return temp;
}
function numGenerator(id,z,p)
{ 
	if(id==0)
	{ 
		num=z;
	}
	else
	{
		num=z+"."+p; 
	}		
	return num;		
}