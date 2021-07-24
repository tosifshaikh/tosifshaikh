<script type="text/javascript">
function ShowTypeChild(typeRow)
{
	var num_rows = document.getElementById("trTypeEnd"+typeRow).value;
	for(var i=0;i<num_rows;i++)
	{
		if(document.getElementById("TrId_"+typeRow+"_"+i).style.display=='none')
		{
			document.getElementById("TrId_"+typeRow+"_"+i).style.display='';
		}
		else
		{
			document.getElementById("TrId_"+typeRow+"_"+i).style.display='none';
		}
	}
}
</script>
<?php 
   error_reporting(0);
   $linkid=mysql_connect("localhost","root","");
   mysql_select_db("virgin20jan");
   global $db,$mdb;
   function showgridheader($class="",$size="10%",$align="left",$value=""){
     $header="<td width='$size' align='$align' class='$class'>".$value."</td>"; 
     return $header;
   }
   function showgridfilter($class="",$size="15",$fieldname="",$events=""){
     $filter="<td class='$class'><input type='text' name='$fieldname' id='$fieldname' size='$size' ".$events." //></td>";
	 return $filter;
   }
   function showgridprent($class="",$size="10%",$align="left",$value="",$t=""){
     $parent	="<td width='$size' align='$align' class='$class'><a href='javascript: ShowTypeChild(".$t.");'><img id='ImgTypeId".$t."' src='../../images/plus_inactive.jpg' border='0' width='9' height='9'></a>".$value."</td>"; 
     return $parent;
   }
   function showgridform_begin($formname="frm",$method="post",$action="",$target=""){
    return $form="<form name='$formname' id='$formname' method='$method' action='$action' target='$target' enctype='multipart/form-data'>";   
   }
   function showgridform_end(){	    
	return $form="</form>";
   
   }
   function showgrid($header="",$filter="",$formaction="",$parent="",$child=""){
	   $grid.="<table width='100%' border='1' cellspacing='1' cellpadding='3' class='tableContentBG'>
       <tr>"; 
       for($i=0;$i<sizeof($header);$i++){		  
		$grid.=showgridheader($header[$i][0],$header[$i][1],$header[$i][2],$header[$i][3]);
	   }
	   $grid.="</tr>
	           <tr>";
       for($j=0;$j<sizeof($filter);$j++){  	     
	   $grid.=showgridfilter($filter[$j][0],$filter[$j][1],$filter[$j][2],$filter[$j][3]);
	   }
	   $grid.="</tr>";
	   $farray=$parent[0];
	   $tablename=$parent[1];//tablename
	   $whereclause=($parent[2]!="")?" where ".$parent[1]:"";//condition
	   $fieldname=($parent[3]!="")?$parent[2]." ":"";//fieldname
	   $Sql_qry = "Select * from $tablename $whereclause  $fieldname ";
	   $Sql=mysql_query($Sql_qry);
	   $grid.="<tr><td colspan='3' id='searchDiv'>";
	   $grid.=showgridform_begin("frm","",$formaction,"");
	   $grid.="<table width='100%' border='1' cellspacing='1' cellpadding='3' class='tableContentBG'>";
	     $s=0;
		 while($rec=mysql_fetch_array($Sql)){
         $grid.="<tr id='typeTR$s' style='background-color:#FFFFFF;'>";
		  for($t=0;$t<sizeof($farray);$t++){
		   $grid.=showgridprent("tableCotentTopBackground1","10%","left",$rec[$farray[$t]],$s);	   	   
		  }
		$grid.="</tr>"; $s++;
	   }
	   $grid.="</table>";
	   $grid.=showgridform_end();
	   $grid.="</td></tr>";
	   $grid.="</table>"; 
	   echo $grid;	    
   }
   $header = array(0=>array("tableCotentTopBackground", "50%","center","Aircraft Type"),1=>array("tableCotentTopBackground", "10%" ,"","Client"),2=>array("tableCotentTopBackground", "10%" ,"","Tail"));
   $filter=array(0=>array("tableclass","25","Aircraft","onkeyup=\"xajax_getSearchContent('aircraftlist.php',xajax.getFormValues('aircraftlist'),'searchDiv',this.id)\""),1=>array("tableclass","20","Aircraft1","onkeyup=\"xajax_getSearchContent('aircraftlist.php',xajax.getFormValues('aircraftlist'),'searchDiv',this.id)\""),2=>array("tableclass","20","Aircraft2","onkeyup=\"xajax_getSearchContent('aircraftlist.php',xajax.getFormValues('aircraftlist'),'searchDiv',this.id)\"")); 
   $parray=array("ICAO","test");
   $parent=array($parray,"aircrafttype","","group by ICAO",);
    $carray=array("ICAO","WAKE","ID");
    $child=array($carray,"aircrafttype","","group by ICAO",);
   showgrid($header,$filter,"",$parent); 
?>