<script src="js/tooltip/wz_tooltip.js" type="text/javascript"></script>
<link href="<?php echo CSS_PATH;?>style.php" rel="stylesheet" type="text/css">
<script src="<?php echo JS_PATH;?>common.js"></script>
<script type="text/javascript">
function isMouseLeaveOrEnter(e, handler) 
{ 
	if (e.type != 'mouseout' && e.type != 'mouseover') 
		return false; 
	var reltg = e.relatedTarget ? e.relatedTarget : e.type == 'mouseout' ? e.toElement : e.fromElement; 
	while (reltg && reltg != handler) 
		reltg = reltg.parentNode; 
		return (reltg != handler); 
}

function show_top_header_submenu(id) //onmouseover
{
	if(document.getElementById('thsmdiv_'+id))
	  {
	   var thsW = document.getElementById('thsmdiv_'+id).offsetWidth;
	   thsW = thsW+"px";	  	  
	  	document.getElementById('thsmdiv_'+id).style.visibility='visible' ;
		document.getElementById('thsmdiv_'+id).style.margin = '-24px -'+thsW;
	  }
}
function hide_top_header_submenu(id) //onmouseout
{
	 if(document.getElementById('thsmdiv_'+id))
	 {
	 	document.getElementById('thsmdiv_'+id).style.visibility='hidden' ;
	 }
}
</script>
<table  border="0" cellspacing="0" cellpadding="0" class="outer_tbl" >
  <tr>
    <td  class="logoheading">
    <div class="topheaderclass" id="main_header">
<div class="logoclass" style="height:64px;"></div>
<div class="powerby"  style="float:right">powered by FLYdocs</div>
<div align="right" style="float:right; width:100%; margin:0px; padding:0px;">
<?php
$db  = new DB_Sql($_CONFIG);
$mdb = new DB_Sql($_CONFIG);
$tdb=clone $db;
$jdb=clone $db;
if($_SESSION['UserLevel'] == "0")
{
	$menuClass = "TabbedPanelsMainMenu";
}
else
{
	$menuClass = "TabbedPanelsMainMenu_new";
}
?>
<ul class="<?php echo $menuClass;?>">
<?php
if(strtolower($checkAccessObj->curPageName()) == "welcomemain.php")
{
?>
	<li class="TabbedPanelsTabHover" ><a href="welcomemain.php"><span>Home</span></a></li>
<?php
}
else
{
?>
<li class="TabbedPanelsTab"  onMouseOver="this.className='TabbedPanelsTabHover';" onMouseOut="this.className='TabbedPanelsTab';" ><a href="welcomemain.php"><span>Home</span></a></li>
<?php
}
if($_SESSION['UserLevel'] != 0)
{	
	$LinkIds = implode(",",array_keys($_SESSION['linkpriv']));
	if(trim($LinkIds) == '')
	{
		$LinkIds = "''";
	}
	if(!$db->select("*","tbl_adminlinks","id in (".$LinkIds.") AND id not in (14,15,21) and parentId =0","linkorder"))
	{
		 ExceptionMsg('section:header.inc,Page:header.inc, Message:error in tbl_adminlinks query');
	}
}
else
{
	$db->select("*","tbl_adminlinks"," id not in (14,15,21) and parentId =0","linkorder");	
}
$i=1;
while($db->next_record())
{
	  $class = "TabbedPanelsTab";
	  $Overclass = "TabbedPanelsTabHover";
	  $mouseOver = "";
	  $linkLen = strlen($db->f("linkname"));
	  if($linkLen > 10)
	  {
		  $class = "TabbedPanelsTab";
		  $Overclass = "TabbedPanelsTabHover";
		  $mouseOver = "this.className = 'TabbedPanelsTabHover'";
	  }
	  else
	  {
		  $class = "TabbedPanelsTab";
		  $mouseOver = "this.className = 'TabbedPanelsTabHover'";
	  }
	  $Lid = $db->f("id");
	$LinkId = $_REQUEST['linkId'];
	if($LinkId == "")
		$LinkId = $_REQUEST['linkid'];
	if($db->f("linkname") != "Retrieve")
	{
		 if($LinkId == $Lid)
			 {
			  $className = $Overclass;
			  $mouseOut="";
			  $submenu_client=1;
			 }
			else
			 {
				$className = $class;
				 if($linkLen > 10)
				  {
				  $mouseOut="this.className = 'TabbedPanelsTab'";
				  }
				  else
				  {
				   $mouseOut="this.className = 'TabbedPanelsTab'";
				 }
				 $submenu_client=0;
			 } 
		  $aircraftMainDiv = "MainDiv".$db->f("id");
		 ?>
	  <li class="<?php echo $className?>" onMouseOver="if (isMouseLeaveOrEnter(event, this)){<?php echo $mouseOver ?>; setMenuScript_Header(document.getElementById('SubMenu_header<?php echo $i;?>'),'<?php echo $aircraftMainDiv;?>',1);}" onMouseOut="if (isMouseLeaveOrEnter(event, this)){<?php echo $mouseOut?>; setMenuScript_Header(document.getElementById('SubMenu_header<?php echo $i;?>'),'<?php echo $aircraftMainDiv;?>',0);}" >
		<div id="mainnav_txt">
			  <div id="<?php echo $aircraftMainDiv;?>">
				  <ul onmouseover="setMenuScript_Header(document.getElementById('SubMenu_header<?php echo $i;?>'),'<?php echo $aircraftMainDiv;?>',1);" style="margin:0px; padding:0px; z-index:10000000; list-style:none;">
				  
                   <?php
					if($_SESSION['UserLevel'] != 0)
					{
						
						$SubLinkIds = $_SESSION['linkpriv'][$db->f("id")];						
						if($SubLinkIds == '')
						{
							$SubLinkIds = "''";
						}						
						if(!$mdb->select("*","tbl_adminsublinks","id in (".$SubLinkIds.")  and id NOT IN (71,84,85,86,87)  AND linktype = 0 $strWher","linkorder"))
						{
							 ExceptionMsg('section:header.inc,Page:header.inc, Message:error in tbl_adminsublinks query');
						}
					}
					else
					{
						if(!$mdb->select("*","tbl_adminsublinks","linkid='".$db->f("id")."' and id NOT IN (71,84,85,86,87)  order by linkorder"))
						{
							 ExceptionMsg('section:header.inc,Page:header.inc, Message:error in tbl_adminsublinks query');
						}
					}
				   ?>
                   
				  <?php 
				  if($db->f("linkname") != "Help")
				  { 
				  		if($_SESSION['UserLevel'] != 0)
						  {
							 
							  if(!$jdb->select("*","tbl_adminsublinks","id in (".$SubLinkIds.")  and id NOT IN (71,84,85,86,87)  AND linktype = 0  order by linkorder"))
							  {
								   ExceptionMsg('section:header.inc,Page:header.inc, Message:error in tbl_adminsublinks query');
							  }
							  if($jdb->num_rows() > 0)
							  {
								    $jdb->next_record();
									$pagelnk = $jdb->f("pagename");
									if(!$jdb->select("*","tbl_adminsublinks","id in (".$SubLinkIds.")  and id NOT IN (71,84,85,86,87)  AND linktype = ".$jdb->f('id')."  order by linkorder"))
									{
										 ExceptionMsg('section:header.inc,Page:header.inc, Message:error in tbl_adminsublinks query');
									}
									if($jdb->num_rows() > 0)
									{
										 $jdb->next_record();
										 $pagelnk = $jdb->f("pagename");
									}
							  }
							  else
							  {
								  $pagelnk = $db->f("pagename");
							  }
						  }
						  else
						  {
							  if($Lid == '6')
							  {
							  	$pagelnk = $db->f("pagename")."&linkId=".$Lid;
							  }
							  else if($Lid == '3')
							  {
							  	$pagelnk = $db->f("pagename")."?linkId=3&view=boxsearch";
							  }
							  else
							  {
							  	$pagelnk = $db->f("pagename")."?linkId=".$Lid;
							  }
						  }
						  if($db->f("id")==20)
						  {
							  ?>
							 <li style="font: bold 11px Tahoma;text-align:center;"><a href="javascript: void(0);" onclick="javascript: window.open('http://gen2.kayako.com/visitor/index.php?/Default/LiveChat/Chat/Request/_sessionID=/_promptType=chat/_proactive=0/_filterDepartmentID=2/_randomNumber=ivklpmo3gucsnngnt2al7oa3dls8rp1x/_fullName=/_email=/', 'livechatwin', 'toolbar=0,location=0,directories=0,status=1,menubar=0,scrollbars=0,resizable=1,width=600,height=680');" class="livechatlink"><span>Support</span></a></li>
                             <?php
						  }else{
				  ?>
					<li style="font: bold 11px Tahoma;text-align:center;"><a href="<?php echo $pagelnk;?>" > <span><?php echo $db->f("linkname");?></span></a></li>
					<?php }
					}
					else
					{
					?>
					<li style="font: bold 11px Tahoma;text-align:center;"><a href="#" onclick="javascript:window.open('<?php echo  $pagelnk ?>','','height=550,width=1024,scrollbars=1')" ><span><?php echo $db->f("linkname")?></span></a></li>
					<?php } ?>
				   
					<div id="SubMenu_header<?php echo $i;?>" style="display:none; text-align:left; background-color:none; z-index:1000; width:100" onmouseover="if (isMouseLeaveOrEnter(event, this)) {setMenuScript_Header(this,'<?php echo $aircraftMainDiv;?>',1);}" onmouseout="if (isMouseLeaveOrEnter(event, this)) {setMenuScript_Header(this,'<?php echo $aircraftMainDiv;?>',0);}" >
							
								<?php 								  
									
									$cphp = $mdb->num_rows();
									if($cphp>0)
									{
									?>
									<div id="worknew"><div id="subdivc" style="z-index:10000000000">
								<ul>
									<?php
									}
									while($mdb->next_record())
									 {
									  $pagenameArray=explode("/",$_SERVER['PHP_SELF']);  // set this code on date : 09_11_2011
									  $pagename=$pagenameArray[count($pagenameArray)-1];	 						
									  if($_REQUEST['linkId']!="" || $pagename=='welcomemain.php')
									  {
										 $TXTsublink="";
									  }
									  else
									  {
										  
									  }
									  if($_SESSION['UserLevel'] != 0)
									  {
										  if(!$jdb->select("*","tbl_adminsublinks","linkid='".$mdb->f("id")."' and id IN (".$SubLinkIds.")  order by linkorder"))
										  {
											   ExceptionMsg('section:header.inc,Page:header.inc, Message:error in tbl_adminsublinks query');
										  }
										  $jdb->next_record();
										  if($jdb->num_rows() > 0)
										  {
										  		$pagelnk = $jdb->f("pagename").$TXTsublink;
										  }
										  else
										  {
											  $pagelnk = $mdb->f("pagename").$TXTsublink;
										  }
									  }
									  else
									  {
										   $pagelnk = $mdb->f("pagename").$TXTsublink;
									  }
									  if($mdb->f("id") == '99')
									 {
										 $pagelnk = 'maintenance_control_centre.php?section=MCC&linkid=1';
									 }
									 ?>
									<li class="darkpurpleFont" id="showTSM_<?php echo $mdb->f("id"); ?>" onmouseover=show_top_header_submenu('<?php echo $mdb->f("id"); ?>') onmouseout=hide_top_header_submenu('<?php echo  $mdb->f("id"); ?>')>
                                    
                                    <?php
									if($mdb->f("id") == '151')
									{
										include_once(INCLUDE_PATH."flyChatFixScriptForMenu.php");
									}
									else if($mdb->f("id") == '152' || $mdb->f("id") == '153' || $mdb->f("id") == '154' || $mdb->f("id") == '156')
									{
										?>
                                        <a href="#" onclick="window.open('<?php echo  $pagelnk; ?>','flychat','toolbar=no,fullscreen=yes,location=no,scrollbars=yes,resizable=yes,width='+screen.width+',height='+screen.height+',left=0,top=0')" >&raquo; <?php echo str_replace('Delivery Bible',BIBLE_TEMPLATE,$mdb->f("linkname"));?></a>
                                        <?php
									}
									else
									{
									?>
                                    
                                    <a href="<?php echo  $pagelnk; ?>">&raquo; <?php echo str_replace('Delivery Bible',BIBLE_TEMPLATE,$mdb->f("linkname"));?></a>
                                    
                                    <?php
									}
									?>
									 <?php if($mdb->f("id")==23)
									 {
									 ?>
										 <ul class="ssdiv" id="thsmdiv_<?php echo $mdb->f("id");?>" style="visibility:hidden;">
										  <?php 
												$strPermission = "";
												if($_SESSION['UserLevel'] != 0)
												{
													$idStrDMC = '';
													$idStrDMC = $_SESSION['linkpriv'][$db->f("id")];
													if($idStrDMC != '')
													{
														$strPermission = " AND id IN (".$idStrDMC.") ";
													}
												}
												$wh = " linkid='".$mdb->f("id")."' ";
												
												if($strPermission != '')
												{
													$wh .= $strPermission;
												}
												//$wh .= " and id  order by linkorder";
												$wh .= " order by linkorder";
												if(!$tdb->select("*","tbl_adminsublinks",$wh))
												{
													 ExceptionMsg('section:header.inc,Page:header.inc, Message:error in tbl_adminsublinks query');
												}
												while($tdb->next_record())
												{
									   			?>
										<li class="darkpurpleFont">
											<?php if ($tdb->f('pagename') =='fscc_workflow_mng.php')
													{ ?>
														<a href="fscc_workflow_mng.php?subtabname=fscc">&raquo; <?php echo $tdb->f('linkname'); ?></a></li>
													<?php
													}
													else
													{
													?>
													<a href="<?php echo $tdb->f('pagename');?>">&raquo; <?php echo $tdb->f('linkname'); ?></a></li>
													<?php
													}
											   ?>
										<?php  }?>
									   </ul>
									 <?php  }
									 		else if($mdb->f("id")==32 || $mdb->f("id")==109 || $mdb->f("id")==113 || $mdb->f("id")==56 || $mdb->f("id")==117 || $mdb->f("id")==125 || $mdb->f("id")==114)
											{
												?>
                                                 <ul class="ssdiv" id="thsmdiv_<?php echo $mdb->f("id");?>" style="visibility:hidden;">
										  <?php 
												$strPermission = "";
												if($_SESSION['UserLevel'] != 0)
												{
													 $ismod_notin = "";
												
													$idStrDMC = '';
													$idStrDMC = $_SESSION['linkpriv'][$db->f("id")];													
													if($idStrDMC != '')
													{
														$strPermission = " AND id IN (".$idStrDMC.") $ismod_notin ";
													}
												}
										 		
												$wh = " linkid='".$mdb->f("id")."' ";
												if($strPermission != '')
												{
													$wh .= $strPermission;
												}
												$wh .=$strWher;
												
												//$wh .=  " AND id NOT IN (94) ";
												
												if(!$tdb->select("*","tbl_adminsublinks",$wh,"linkorder"))
												{
													 ExceptionMsg('section:header.inc,Page:header.inc, Message:error in tbl_adminsublinks query');
												}
												while($tdb->next_record())
												{
									   			?>
										<li class="darkpurpleFont">
											
													<a href="<?php echo $tdb->f('pagename');?>">&raquo; <?php echo $tdb->f('linkname'); ?></a></li>
												
											  
										<?php  }?>
									   </ul>
                                                <?php
											}
									?>
									 </li>
									<?php							  
								 }
								 if($cphp>0)
								 {
								  ?>
								</ul></div></div>
								 <?php
								 }
								  ?>
							
						  
						  
						</div>
				  </ul>
				</div>
		</div>
	  </li>
	  <?php
				}
				
				 $i++; 
				}
 ?>
  <li class="TabbedPanelsTab" ><a href="logout.php"><span>Logoff</span></a></li>
</ul>

  <?php 
    $searchsubtabs='<ul class="SubTabbedPanels">';
	if($_SESSION['UserLevel'] != 0)
	{
		$SubLinkIds = implode(",",array_keys($_SESSION['sublinkpriv']));
		if($SubLinkIds == "")
		{
			$SubLinkIds = "''";
		}
		if(!$mdb->select("*","tbl_adminsublinks","id in(".$SubLinkIds.") order by linkorder"))
		{
			 ExceptionMsg('section:header.inc,Page:header.inc, Message:error in tbl_adminsublinks query');
		}
	}
	else
	{
		 $mdb->select("*","tbl_adminsublinks","linkid='3'");	
		 $addrightche=1;
		 $editrightche=1;
		 $deleterightche=1;		 
	}
	$submenucp = 0;	
	$submenucp1 = 1;							
	while($mdb->next_record())
	{						
	    $arrLiIdClass = array("class1","class2","class3","class4","class5","class6","class7","class8","class9","class10","class11","class12");
		$hover = "Hover";
		if($_SERVER['QUERY_STRING']!='')
		{
		 $mystring1=$_SERVER['QUERY_STRING'];
		 $findme="linkId";
		 
		 $pos1 = strpos($mystring1, $findme);
         $pos2 = strpos($mystring1, $findme,$pos1+6);		 
		 $findrel = "?linkId=3";
		 if($pos2>$pos1+7)
		 	$pagename = $_SERVER['PHP_SELF']."?".str_replace($findrel,'',$_SERVER['QUERY_STRING']);
		 else
		    $pagename = $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'];
		}
		else
		{
		 	$pagename = $_SERVER['PHP_SELF'];
		}
	
		$pagename = str_replace("/fly_docs/","",$pagename);
		$pagename = str_replace("fly_docs/","",$pagename);
		$pagename = str_replace("//","",$pagename);
		$pagename = str_replace("/","",$pagename);	
				
		if($pagename == $mdb->f("pagename"))
		{
			  $addrightche=$_SESSION['sublinkpriv'][$mdb->f("id")]['ADD'];
			  $editrightche=$_SESSION['sublinkpriv'][$mdb->f("id")]['EDIT'];
			  $deleterightche=$_SESSION['sublinkpriv'][$mdb->f("id")]['DELETE'];
			  $class1 = $arrLiIdClass[$submenucp].$hover;
			  $flagClass1 = 0;
		}
		else
		{
			$class1 = $arrLiIdClass[$submenucp];
			$flagClass1 = 1;
		}
	
		  $searchsubtabs.='<li id="'.$class1.'" class="" onMouseOver="setClassId(this,';
		  $searchsubtabs.="'".$arrLiIdClass[$submenucp]."',".$flagClass1.")";
		  $searchsubtabs.='" onMouseOut="setClassId(this,';
		  $searchsubtabs.="'".$arrLiIdClass[$submenucp]."',".$flagClass1;
		  $searchsubtabs.=')" onClick="location.href';
		  $searchsubtabs.="='".$mdb->f("pagename")."'";
		  $searchsubtabs.='">';
		  $searchsubtabs.='<table cellpadding="0" cellspacing="0" border="0">
			  <tr>
				<td class="SubTabbedPanelsTabLeft" ></td>
				<td class="SubTabbedPanelsTabCenter">'.$mdb->f("linkname").'</td>
				<td class="SubTabbedPanelsTabRight"></td>
			  </tr>
			</table>
		  </li>';
       $submenucp++;
	   $submenucp1++;
    } 
	 $searchsubtabs.='</ul>';

?></div>
</div>
<?php 
	include_once(INCLUDE_PATH."flychatfixscript.php");
?>
</td></tr>
 <tr>
    <td height="100%" class="mainwhitebackgroundtable" align="center" valign="top" id="PageContent" >
  