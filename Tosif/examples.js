// JavaScript DocumentThe following is the complete listing of the client-side JavaScript app.
var obj,tree,txt;
function myFun()
{


if(window.XMLHttpRequest)
{
	obj = new XMLHttpRequest;
}else
{
	obj = new ActiveXObject('Microsoft.XMLHTTP');
}


obj.onreadystatechange=function()
{
	if (obj.readyState==4 && obj.status==200)
	{
		//alert(0)
		tree=obj.responseXML.getElementsByTagName('tree')[0];
		txt="<table>";
		traverse(tree);
		txt+="</table>";
		document.getElementById('tree').innerHTML=txt;
		/*if(tree.hasChildNodes()) 
		{
			//console.log(tree.tagName)
			 var nodes=tree.children.length;
		     console.log(nodes)
		}
		*/
		/*for(var i=0;i<tree.length;i++)
		{
			var yy=tree[i].children
			 //console.log(yy)
			for(var j=0;j<yy.length;j++)
			{
				 var zz=yy[j].children;
			     // console.log(zz)
			}
		}*/
       

		//traverse(tree);
	}
}
obj.open("GET","example.xml",true);
obj.send();
}

function traverse(tree) 
{
   
    
        if(tree.hasChildNodes()) 
		{
             //   document.write('<ul><li>');
               txt+="<tr><td>"+tree.tagName+"</td></tr>";
               
				

				for(var j=0;j<tree.children.length;j++)
				{
					//console.log(tree.childNodes(j))
					traverse(tree[j].children);
               // document.write('</li></ul>');
				}
        }
      
}


