 
// JavaScript Document
var target='divGrid';
var xmlDoc='';
var ACT='';
var CurrentID=0;
var ClientArr=new Array();
var jsonObj=new Object();
var jsonObjCopy=new Object();
var current_filter='';
var screenW = 640, screenH = 480;
 if (parseInt(navigator.appVersion) > 3) {
  screenW = screen.width;
  screenH = screen.height;
}
 function loadGrid()
{
               getLoadingCombo(1);
               var selClient=document.getElementById('selClient').value;
               var isArchive = document.getElementById("is_archive").value;
               var is_inbox = document.getElementById("is_inbox").value;
               var strUrl='staff_diary.php';
               var params="act=GRID&selClients="+selClient+"&isArchive="+isArchive+"&is_inbox="+is_inbox;
               getGrid(strUrl,params,target);
}
 function getGrid(strURL,params,target)
{
              
                try
               {
                               var req = getXMLHTTP();
                               if (req)
                               {
                                               req.onreadystatechange = function()
                                                {
                                                               if (req.readyState == 4)
                                                               {
                                                                               if (req.status == 200)
                                                                                {
                                                                                               xmlDoc = req.responseText;
                                                                                               renderGrid(xmlDoc);
                                                                                              
                                                                                                ACT="RESET";
                                                                                               enableField(ACT);
                                                                                               getLoadingCombo(0);
                                                                               }
                                                               }
                                               }
                                               req.open("POST", strURL, true);
                                               req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                                               req.setRequestHeader("Content-length", params.length);
                                               req.send(params);
                               }             
               }
               catch(err) {
                               alert(err+'GetGRID');
               }
}
function getLoadingCombo(flg)
{
var elm = document.getElementById("LoadingDivCombo");
                if(flg ==1)
               {
               elm.style.width='100%';
               elm.style.height='100%';
               elm.style.display="";
               }
               else
               {
               elm.style.width=0;
               elm.style.height=0;
               elm.style.display="none";
               }
}
var parent=new Object();
function renderGrid(xmlDoc)
{
               var new_area;
               var new_dept;
               var objClient=document.getElementById('selClient');
              
 
                for(var i =1; i <objClient.length;i++)
               {
                               ClientArr[objClient[i].value]=objClient[i].text;
               }
              
                jsonObj=eval("("+ xmlDoc +")");
               Obj=jsonObj;
                parent=Obj.parent;
               var table = '<table width="100%" cellspacing="1" cellpadding="3" border="0" class="tableContentBG" >';
                               table+='<tr>';
                               table+='<td class="tableCotentTopBackground" >Client</td>';
                               table+='<td class="tableCotentTopBackground" >Area</td>';
                               table+='<td class="tableCotentTopBackground">Department</td>';
                               table+='<td class="tableCotentTopBackground">Project Title</td>';
                                              
                                table+='<td class="tableCotentTopBackground">Late Update</td>';
                               table+='<td class="tableCotentTopBackground">Made By</td>';
                               table+='<td class="tableCotentTopBackground">Project Type</td>';
                                                                              
                                table+='<td class="tableCotentTopBackground" >View</td>';
                               table+='</tr>';
                              
                                table+='<tr>';
              
                                var filter_client_val= ($("#filter_client_id").val() && $("#filter_client_id").val()!='') ? $("#filter_client_id").val() :'';
                               
                               
                                table+='<td class="tableCotentTopBackground" ><input type="text" name="filter_client" id="filter_client_id" value="'+filter_client_val+'" onkeypress="return searchpage(this.value,event.keyCode,\'filter_client_id\');"></td>';
                              
                                var filter_area_val= ($("#filter_area_id").val() && $("#filter_area_id").val()!='') ? $("#filter_area_id").val() :'';
                               
                                table+='<td class="tableCotentTopBackground" ><input type="text" name="filter_area" id="filter_area_id" value="'+filter_area_val+'" onkeypress="return searchpage(this.value,event.keyCode,\'filter_area_id\');"></td>';
                              
                                var filter_department_val= ($("#filter_department_id").val() && $("#filter_department_id").val()!='') ? $("#filter_department_id").val() :'';
                               
                                table+='<td class="tableCotentTopBackground"><input type="text" name="filter_department" id="filter_department_id" value="'+filter_department_val+'" onkeypress="return searchpage(this.value,event.keyCode,\'filter_department_id\');"></td>';
                              
                                var filter_title_val= ($("#filter_title_id").val() && $("#filter_title_id").val()!='') ? $("#filter_title_id").val() :''; 
                               
                                table+='<td class="tableCotentTopBackground"><input type="text" name="filter_title" id="filter_title_id" value="'+filter_title_val+'" onkeypress="return searchpage(this.value,event.keyCode,\'filter_title_id\');"></td>';
                                              
                                table+='<td class="tableCotentTopBackground">&nbsp;</td>';
              
                                var filter_made_by_val= ($("#filter_made_by_id").val() && $("#filter_made_by_id").val()!='') ? $("#filter_made_by_id").val() :'';
                               
                                table+='<td class="tableCotentTopBackground"><input type="text" name="filter_made_by" id="filter_made_by_id" value="'+filter_made_by_val+'" onkeypress="return searchpage(this.value,event.keyCode,\'filter_made_by_id\');" ></td>';
                              
                                var filter_project_type_val= ($("#filter_project_type_id").val() && $("#filter_project_type_id").val()!='0') ? $("#filter_project_type_id").val() :'0';
                               
                                table+='<td class="tableCotentTopBackground"><select name="filter_project_type" id="filter_project_type_id" onchange="vv()" ><option value="0">Select </option>';
                               table+='<option value="1"';
                               if(filter_project_type_val==1)
                               {
                                               table+=' selected="selected" ';
                               }
                               table+='>Internal  Users Only</option><option value="2"';
                               if(filter_project_type_val==2)
                               {
                                               table+=' selected="selected" ';
                               }
                               table+='>             Internal and External Users </option></select></td>';
                                                                              
                                table+='<td class="tableCotentTopBackground" >&nbsp;</td>';
                               table+='</tr>';
                              
                                var cnt = 0;
                               for(k in parent)
                               {
                                               cnt++;
                               }
               if(cnt>0)
                {
                               table+=renderGridData();           
               }
               else
               {
                               table+='<tr>';
                                table+='<td colspan="5" class="" width="10%" align="center" >No Records Found</td>';               
                                table+='<td colspan="8" class=""  align="center" >No Records Found</td>';                         
                               table+='</tr>';
               }
               table+='<tr><td id ="gridData"></td></tr>';
               table+='</table>';
               document.getElementById(target).innerHTML=table;
               
                if(current_filter != "")
                {
                                var ctrl = document.getElementById(current_filter);
                                var pos = ctrl.value.length;
               
                                if(ctrl.setSelectionRange)
                                {
                                                //alert("a")
                                                ctrl.focus();
                               ctrl.select();
                                               
                                }
                                else if (ctrl.createTextRange)
                                {
                                                //alert("else");
                                                var range = ctrl.createTextRange();
                                                range.collapse(true);
                                                range.moveEnd('character', pos);
                                                range.moveStart('character', pos);
                                                range.select();
                                }
                }             
               
}
function vv()
{
                current_filter="filter_project_type_id";
               renderGrid(xmlDoc);
}
function renderGridData()
{
               var table='';
               var clientTable='';
               for(client in parent)
               {             
                                               var clientTemp=client.replace('_', '');
                                               var showClient=1;
                                              
                                                var filter_client=$("#filter_client_id").val();
                                              
                                               
                                                if(filter_client && filter_client!='' && (ClientArr[clientTemp].toLowerCase()).indexOf(filter_client.toLowerCase()) == '-1')
                                               {
                                                              
                                                                showClient=0;
                                                               //return false;
                                               }
                                              
                                if(showClient==1)
                               {
                                               clientTable='';
                                               jsonObjCopy[clientTemp]=new Object();  
                                                var rClass= "even" ;
                                               var clientName=document.getElementById('selClients');
                                               var projectArr=parent[client];
                                               clientTable+='<tr class="'+rClass+'" id="TR_B_'+clientTemp+'" >';
                                               clientTable+='<td>'+ClientArr[clientTemp]+'</td>';
                                               clientTable+='<td>&nbsp;</td>';
                                               clientTable+='<td>&nbsp;</td>';
                                               clientTable+='<td>&nbsp;</td>';
                                               clientTable+='<td>&nbsp;</td>';
                                               clientTable+='<td >&nbsp;</td>';
                                               clientTable+='<td >&nbsp;</td>';
                                               clientTable+='<td >&nbsp;</td>';
                                               clientTable+='</tr>';
                                              
                                               
                                                var table1='';
                                               var areaTable='';
                                   for(a in projectArr)
                                               {
                                                               areaTable='';
                                                               var showArea=1;
                                              
                                                                var filter_area=$("#filter_area_id").val();
                                                               if(filter_area && filter_area!='' && (a.toLowerCase()).indexOf(filter_area.toLowerCase()) == -1)
                                                               {
                                                                               showArea=0;
                                                               }
                                                               if(showArea==1)
                                                               {
                                                                               areaTable += '<tr style="background-color:#FFFFFF;">';
                                                                               areaTable+='<td></td>';
                                                                               areaTable += '<td valign="top" align="left" >';
                                                                               areaTable += '&nbsp;&nbsp'+a+'</td>';
                                                                               areaTable+='<td></td>';
                                                                               areaTable+='<td></td>';
                                                                               areaTable+='<td ></td>';
                                                                               areaTable+='<td ></td>';
                                                                               areaTable+='<td ></td>';
                                                                               areaTable+='<td ></td>';
                                                                               areaTable += '</tr>';
                                                                               var departmentTable='';
                                                                              
                                                                                var table2='';
                                                                                              
                                                                                for(d in projectArr[a])
                                                                               {
                                                                              
                                                                                                departmentTable='';
                                                                                               var showDepartment=1;
                                              
                                                                                                var filter_department=$("#filter_department_id").val();
                                                                                               if(filter_department && filter_department!='' && (d.toLowerCase()).indexOf(filter_department.toLowerCase()) == -1)
                                                                                               {
                                                                                              
                                                                                                                showDepartment=0;
                                                                                               //            return false;
                                                                                               }
                                                                                              
                                                                                if(showDepartment==1)
                                                                               {
                                                                                               departmentTable += '<tr style="background-color:#FFFFFF;">';
                                                                                               departmentTable+='<td></td>';
                                                                                               departmentTable+='<td></td>';
                                                                                               departmentTable += '<td valign="top" align="left" >';
                                                                                               departmentTable += '&nbsp;&nbsp'+d+'</td>';
                                                                                               departmentTable+='<td></td>';
                                                                                               departmentTable+='<td ></td>';
                                                                                               departmentTable+='<td ></td>';
                                                                                               departmentTable+='<td ></td>';
                                                                                               departmentTable+='<td ></td>';
                                                                                               departmentTable += '</tr>';
                                                                                              
                                                                                                var projectDesTable='';
                                                                                               var table3='';
                                                                                               for(p in projectArr[a][d])
                                                                                               {
                                                                                                               projectDesTable='';
                                                                                                               project_arr=projectArr[a][d][p];
                                                                                                               var projectName=project_arr.ProjectText;
                                                                                                               var projectId=project_arr.projectId;
                                                                                                               var count_task=project_arr.count_task;
                                                                                                              
                                                                                                                var late_update=project_arr.late_update;
                                                                                                               var project_type=project_type_arr[project_arr.project_type];
                                                                                                               var made_by=project_arr.made_by;
                                                                                                               var inbox_note_count=project_arr.inbox_note_count;
                                                                                                               var is_inbox=document.getElementById("is_inbox").value;
                                                                                                              
                                                                                                                var showProjectDetail=1;
                                                                                                               var filter_title=$("#filter_title_id").val();
                                                                                                               if(filter_title && filter_title!='' && (projectName.toLowerCase()).indexOf(filter_title.toLowerCase()) == -1)
                                                                                                               {
                                                                                                                               showProjectDetail=0;
                                                                                                               }
                                                                                              
                                                                                                 var filter_made_by=$("#filter_made_by_id").val();
                                                                                                               if(filter_made_by && filter_made_by!='' && (made_by.toLowerCase()).indexOf(filter_made_by.toLowerCase()) == -1)
                                                                                                               {
                                                                                                                               showProjectDetail=0;
                                                                                                               }
                                                                                              
                                                                                                var filter_project_type=$("#filter_project_type_id").val();
                                                                                              
                                                                                                                if(filter_project_type &&  filter_project_type!='0' && filter_project_type!=project_arr.project_type)
                                                                                                               {
                                                                                                                              
                                                                                                                                showProjectDetail=0;
                                                                                                               }
                                                                                                              
                                                                                                                if(showProjectDetail==1)
                                                                                                               {
                                                                                                               rClass=(p%2==0)? "odd" : "even";
                                                                                                              
                                                                                                                               
                                                                                                                jsonObjCopy[clientTemp][projectId]=project_arr;
                                                                                                              
                                                                                                                projectDesTable+='<tr class="'+rClass+'" id="TR_'+clientTemp+'_A'+projectId+'" onclick="Edit(this)">';
                                                                                                               projectDesTable+='<td></td>';
                                                                                                               projectDesTable+='<td></td>';
                                                                                                               projectDesTable+='<td></td>';
                                                                                                               projectDesTable+='<td>'+projectName+' ';
                                                                                                               if(inbox_note_count > 0 && is_inbox==1)
                                                                                                               {
                                                                                                                               projectDesTable+='<span style="color:red;"> ('+inbox_note_count+') </span>';
                                                                                                               }
                                                                                                               projectDesTable+='</td>';
                                                                                                              
                                                                                                                projectDesTable+='<td>'+late_update+'</td>';
                                                                                                               projectDesTable+='<td>'+made_by+'</td>';
                                                                                                               projectDesTable+='<td>'+project_type+'</td>';
                                                                                                              
                                                                                                                if(count_task==0)
                                                                                                               {
                                                                                                                               var class_name='view_active_icon_disable';
                                                                                                               }
                                                                                                               else
                                                                                                               {
                                                                                                                               var class_name='view_active_icon';
                                                                                                               }
                                                                                                               projectDesTable+='<td><div class="'+class_name+'" onclick="OpenProject('+projectId+','+clientTemp+')" style="cursor:pointer"></div></td>';
                                                                                                               projectDesTable+='</tr>';
                                                                                               }
                                                                                                               if(projectDesTable!='')
                                                                                                               {
                                                                                                                               table3+=projectDesTable;
                                                                                                               }
                                                                                               }
                                                                                               if(table3!='')
                                                                                               {
                                                                                                               table2+=departmentTable+table3;
                                                                                               }
                                                                                              
                                                                                }
                                                               }
                                                                               if(table2!='')
                                                                               {
                                                                                               table1+=areaTable+table2;
                                                                               }
                                                                                              
                                                }
                               }
                                               if(table1!='')
                                               {
                                                               table+=clientTable+table1;
                                               }
                               }
                              
                }
               
                if(table=='')
                {
                                                table+='<tr>';
                                table+='<td colspan="8" class=""  align="center" >No Records Found</td>';                         
                                table+='</tr>';
                }
               return table;
}
function enableField(ACT)
{
               var Field =  new Array();
               Field=    ObjInit();
                              
                if(ACT=="EDIT")
               {
                               if(Field['Add'])
                               {
                                               Field['Add'].className = "button";
                                               Field['Add'].disabled = '';
                               }
                               if(Field['Edit'])
                               {
                                               Field['Edit'].className = "button";
                                               Field['Edit'].disabled = '';
                               }
                               if(Field['archiveBtn'])
                               {
                                               Field['archiveBtn'].className = "button";
                                               Field['archiveBtn'].disabled = '';
                               }
                               if(Field['Save'])
                               {
                                               Field['Save'].className = "disbutton";
                                               Field['Save'].disabled = 'disabled';
                               }
                               if(Field['reset'])
                               {
                                               Field['reset'].className = "button";
                                               Field['reset'].disabled = '';
                               }
                               if(Field['selClient'])
                               {
                                               Field['selClient'].disabled = 'disabled';
                               }
                               if(Field['area'])
                               {
                                               Field['area'].disabled = 'disabled';
                               }
                               if(Field['dept'])
                               {
                                               Field['dept'].disabled = 'disabled';
                               }
                               if(Field['ProjectText'])
                               {
                                               Field['ProjectText'].disabled = 'disabled';
                               }
                               if(Field['project_type'])
                               {
                                               Field['project_type'].disabled = 'disabled';
                               }
                               if(Field['Reload'])
                               {
                                               Field['Reload'].className = "button";
                                               Field['Reload'].disabled = "";
                                                                                              
                                }
                              
                }
               else if(ACT=="FNEDIT")
               {
                               if(Field['Add'])
                               {
                                               Field['Add'].className = "disbutton";
                                               Field['Add'].disabled = 'disabled';
                               }
                               if(Field['Edit'])
                               {
                                               Field['Edit'].className = "disbutton";
                                               Field['Edit'].disabled = 'disabled';
                               }
                               if(Field['archiveBtn'])
                               {
                                               Field['archiveBtn'].className = "disbutton";
                                               Field['archiveBtn'].disabled = 'disabled';
                               }
                               if(Field['Save'])
                               {
                                               Field['Save'].className = "button";
                                               Field['Save'].disabled = '';
                               }
                               if(Field['reset'])
                               {
                                               Field['reset'].className = "button";
                                               Field['reset'].disabled = '';
                               }
                               if(Field['selClient'])
                               {
                                               Field['selClient'].disabled = '';
                               }
                               if(Field['area'])
                               {
                                               Field['area'].disabled = '';
                               }
                               if(Field['dept'])
                               {
                                               Field['dept'].disabled = '';
                               }
                               if(Field['ProjectText'])
                               {
                                               Field['ProjectText'].disabled = '';
                               }
                               if(Field['project_type'])
                               {
                                               Field['project_type'].disabled = '';
                               }
               }
              
                else if(ACT=="ADD")
               {
                               if(Field['Add'])
                               {
                                               Field['Add'].className = "disbutton";
                                               Field['Add'].disabled = 'disabled';
                               }
                               if(Field['Edit'])
                               {
                                               Field['Edit'].className = "disbutton";
                                               Field['Edit'].disabled = 'disabled';
                               }
                               if(Field['archiveBtn'])
                               {
                                               Field['archiveBtn'].className = "disbutton";
                                               Field['archiveBtn'].disabled = 'disabled';
                               }
                               if(Field['Save'])
                               {
                                               Field['Save'].className = "button";
                                               Field['Save'].disabled = '';
                               }
                               if(Field['selClient'])
                               {
                                              
                                                Field['selClient'].value= "0";
                                               Field['selClient'].disabled = '';
                               }
                               if(Field['area'])
                               {
                                              
                                                Field['area'].value= "";
                                               Field['area'].disabled = '';
                               }
                               if(Field['dept'])
                               {
                                              
                                                Field['dept'].value= "0";
                                               Field['dept'].disabled = '';
                               }
                               if(Field['ProjectText'])
                               {
                                              
                                                Field['ProjectText'].value= "";
                                               Field['ProjectText'].disabled = '';
                               }
                               if(Field['project_type'])
                               {
                                              
                                                Field['project_type'].value= "0";
                                               Field['project_type'].disabled = '';
                               }
                               if(Field['reset'])
                               {
                                               Field['reset'].className = "button";
                                               Field['reset'].disabled = '';
                               }
                               if(document.getElementById(CurrentID))
                               {
                                              
                                                document.getElementById(CurrentID).style.backgroundColor="";
                               }
                              
                }
               else if(ACT=="RESET")
               {
                               if(Field['Add'])
                               {
                                               Field['Add'].className = "button";
                                               Field['Add'].disabled = '';
                               }
                               if(Field['Edit'])
                               {
                                               Field['Edit'].className = "disbutton";
                                               Field['Edit'].disabled = 'disabled';
                               }
                               if(Field['archiveBtn'])
                               {
                                               Field['archiveBtn'].className = "disbutton";
                                               Field['archiveBtn'].disabled = 'disabled';
                               }
                               if(Field['Save'])
                               {
                                               Field['Save'].className = "disbutton";
                                               Field['Save'].disabled = 'disabled';
                               }
                              
                                if(Field['selClient'])
                               {
                                               Field['selClient'].value='';
                                               Field['selClient'].disabled = 'disabled';
                               }
                               if(Field['area'])
                               {
                                               Field['area'].value='';
                                               Field['area'].disabled = 'disabled';
                               }
                               if(Field['dept'])
                               {
                                               Field['dept'].value='0';
                                               Field['dept'].disabled = 'disabled';
                               }
                               if(Field['ProjectText'])
                               {
                                               Field['ProjectText'].value='';
                                               //Field['ProjectText'].className = "button";
                                               Field['ProjectText'].disabled = 'disabled';
                               }
                               if(Field['project_type'])
                               {
                                               Field['project_type'].value='0';
                                               //Field['ProjectText'].className = "button";
                                               Field['project_type'].disabled = 'disabled';
                               }
                               if(Field['reset'])
                               {
                                               Field['reset'].className = "button";
                                               Field['reset'].disabled = '';
                               }
                              
                                if(document.getElementById('selClient'))
                               {
                                               document.getElementById('selClient').value=0;
                               }
                               if(document.getElementById(CurrentID))
                               {
                                               document.getElementById(CurrentID).style.backgroundColor="";
                               }
                               ACT='';
                               CurrentID=0;
               }
               else if(ACT=="DELETE")
               {
                               if(Field['Add'])
                               {
                                               Field['Add'].className = "disbutton";
                                               Field['Add'].disabled = 'disabled';
                               }
                               if(Field['Edit'])
                               {
                                               Field['Edit'].className = "disbutton";
                                               Field['Edit'].disabled = 'disabled';
                               }
                               if(Field['archiveBtn'])
                               {
                                               Field['archiveBtn'].className = "disbutton";
                                               Field['archiveBtn'].disabled = 'disabled';
                               }
                               if(Field['Save'])
                               {
                                               Field['Save'].className = "button";
                                               Field['Save'].disabled = 'disabled';
                               }
                              
                                if(Field['ProjectText'])
                               {
                                              
                                                //Field['ProjectText'].className = "button";
                                               Field['ProjectText'].disabled = '';
                               }
                               if(Field['reset'])
                               {
                                               Field['reset'].className = "button";
                                               Field['reset'].disabled = '';
                               }
                               if(document.getElementById(CurrentID))
                               {
                                              
                                                document.getElementById(CurrentID).style.backgroundColor="";
                               }
                              
                }
              
 }
function getCurrentArr(id)
{
               var tempId=id.replace('A','');
               var tempArr=tempId.split("_");
               var val=tempArr[1];
              
                var CurrentArr =new Array();
               CurrentArr['ClientID']=tempArr[1];
               CurrentArr['ProjectText']=jsonObjCopy[tempArr[1]][tempArr[2]].ProjectText;
               CurrentArr['project_type']=jsonObjCopy[tempArr[1]][tempArr[2]].project_type;
               CurrentArr['projectId']=jsonObjCopy[tempArr[1]][tempArr[2]].projectId;
              
                CurrentArr['area']=jsonObjCopy[tempArr[1]][tempArr[2]].area;
               CurrentArr['dept']=jsonObjCopy[tempArr[1]][tempArr[2]].dept;
              
                return CurrentArr;
}
function Edit(obj)
{
               ACT="EDIT";
               enableField(ACT)
              
                var CurrentArr= getCurrentArr(obj.id);
                if(document.getElementById('selClient'))
               {
                               document.getElementById('selClient').value=CurrentArr['ClientID'];
               }
              
                if(document.getElementById('projectText'))
               {
                               document.getElementById('projectText').value=CurrentArr['ProjectText'];
               }
               if(document.getElementById('project_type'))
               {
                               document.getElementById('project_type').value=CurrentArr['project_type'];
               }
              
                if(document.getElementById(CurrentID))
               {
                               document.getElementById(CurrentID).style.backgroundColor="";
               }
              
                document.getElementById(obj.id).style.backgroundColor="#FFCC99";
               CurrentID=obj.id;
              
                xajax_SetFormIFM(CurrentArr['projectId']);
}
 function ObjInit()
{
               var Field =  new Array();
              
                Field['Add'] = document.getElementById("addBtn");
               Field['Edit'] = document.getElementById("editBtn");
                Field['archiveBtn'] = document.getElementById("archiveBtn");
                Field['Save'] = document.getElementById("saveBtn");
                Field['Reset'] = document.getElementById("resetBtn");
               Field['selClient'] = document.getElementById('selClient');
               Field['Reload'] = document.getElementById('reload');
               Field['area'] = document.getElementById('area');
               Field['dept'] = document.getElementById('dept');
               Field['ProjectText'] = document.getElementById('projectText');
               Field['project_type'] = document.getElementById('project_type');
               return Field;
}
function fnReset()
{
               ACT="RESET";
               enableField(ACT)
               resetArea();
               resetDept();
               return false;
}
function fnAdd()
{
               ACT="ADD";
               enableField(ACT)
              
                return false;      
}
function fnEdit()
{
               ACT="FNEDIT";
               enableField(ACT)
              
                return false;      
              
 }
function fnDelete()
{
              
                if(confirm("Are you sure you want to archive this Project?"))
               {
                               var CurrentArr= getCurrentArr(CurrentID);
                               xajax_Delete(CurrentArr['projectId']);
                               ACT="RESET";
                               enableField(ACT)
               }
              
                return false;      
              
 }
// for saving records
function fnSave()
{            
              
                var clientid=0;
               var projectText='';
               var area=0;
               var dept=0;
               var project_type=0;
              
                if(document.getElementById('selClient'))
               {
                               if(document.getElementById('selClient').value==0)
                               {
                                               alert("Please select Client.")
                                               return false;
                               }
                               else
                               {
                                               clientid=document.getElementById('selClient').value;
                               }
                              
                }
              
                if(document.getElementById('area'))
               {
                               if(document.getElementById('area').value=="")
                               {
                                               alert("Please select Area.")
                                               return false;
                               }
                               else
                               {
                                               area=document.getElementById('area').value;
                               }
                              
                }
              
                if(document.getElementById('dept'))
               {
                               if(document.getElementById('dept').value==0)
                               {
                                               alert("Please select Department.")
                                               return false;
                               }
                               else
                               {
                                               dept=document.getElementById('dept').value;
                               }
                              
                }
              
                if(document.getElementById('projectText'))
               {
                               if(document.getElementById('projectText').value=='')
                               {
                                               alert("Please Enter Project Title.")
                                               return false;
                               }
                               else
                               {
                                               projectText=document.getElementById('projectText').value;
                               }
                              
                }
               if(document.getElementById('project_type'))
               {
                               if(document.getElementById('project_type').value=='0')
                               {
                                               alert("Please Select Project Type.")
                                               return false;
                               }
                               else
                               {
                                               project_type=document.getElementById('project_type').value;
                               }
                              
                }
              
               
                if(ACT=="ADD")
               {
                               xajax_Save(clientid,projectText,area,dept,project_type);
                              
                }
               else if(ACT=="FNEDIT")
               {
                               var CurrentArr= getCurrentArr(CurrentID);
                               xajax_Update(clientid,projectText,CurrentArr['projectId'],area,dept,project_type);
                              
                }
                return false;
}
function setArea(client,selected)
{
               if(client != '0')
               {
                               var tdDept = '';
                               tdDept += '<select name="dept" id="dept">';
                               tdDept += '<option value="0">[Select Department]</option></select>';
                               document.getElementById('tdDept').innerHTML = tdDept;
                               document.getElementById('dept').value="0";
                               xajax_set_ddlUser(client,selected);
               }
               else
               {
                               var tdArea = '';
                               tdArea += '<select name="area" id="area" onchange="setDept(this.value,\'\');">';
                               tdArea += '<option value="">[Select Area]</option></select>';
                               document.getElementById('tdArea').innerHTML = tdArea;
                               document.getElementById('area').value="";
                               var tdDept = '';
                               tdDept += '<select name="dept" id="dept">';
                               tdDept += '<option value="0">[Select Department]</option></select>';
                               document.getElementById('tdDept').innerHTML = tdDept;
                               document.getElementById('dept').value="0";
               }
}
 function setDept(area,selected)
{
               if(area != '')
               {
                               xajax_set_ddlDept(area,selected);
               }
               else
               {
                               var tdDept = '';
                               tdDept += '<select name="dept" id="dept">';
                               tdDept += '<option value="0">[Select Department]</option></select>';
                               document.getElementById('tdDept').innerHTML = tdDept;
               }
}
function fnViewArchive()
{
              
                var Field =  new Array();
               Field = ObjInit();
              
                if(document.getElementById("is_archive").value == 0)
               {
                               document.getElementById("is_archive").value = 1;
                               document.getElementById("view_archive").value = btnHideArchive;
                               if(Field['Add'])
                               {
                                               Field['Add'].style.display = 'none';
                               }
                               if(Field['Save'])
                               {
                                               Field['Save'].style.display = 'none';
                               }
                               if(Field['Edit'])
                               {
                                               Field['Edit'].style.display = 'none';
                               }
                               if(Field['archiveBtn'])
                               {
                                               Field['archiveBtn'].style.display = 'none';
                               }
                               if(Field['Reset'])
                               {
                                               Field['Reset'].style.display = 'none';
                               }
                              
                                if(Field['Reload'])
                               {
                                               Field['Reload'].className = "disbutton";
                                               Field['Reload'].style.display = '';
                                               Field['Reload'].disabled = "disabled";
                                                                                              
                                }
               }
               else
               {
                               document.getElementById("is_archive").value = 0;
                               document.getElementById("view_archive").value = btnViewArchive;
                               if(Field['Add'])
                               {
                                               Field['Add'].style.display = '';
                               }
                               if(Field['Save'])
                               {
                                               Field['Save'].style.display = '';
                               }
                               if(Field['Edit'])
                               {
                                               Field['Edit'].style.display = '';
                               }
                               if(Field['archiveBtn'])
                               {
                                               Field['archiveBtn'].style.display = '';
                               }
                               if(Field['Reset'])
                               {
                                               Field['Reset'].style.display = '';
                               }
                              
                                if(Field['Reload'])
                               {
                                               Field['Reload'].style.display = 'none';
                               }
               }
               fnReset();
               loadGrid();
              
 }
function fnReload()
{
               if(confirm("Are you sure you want to reload this Project?"))
               {
                               var CurrentArr= getCurrentArr(CurrentID);
                               xajax_Reload(CurrentArr['projectId']);
                               ACT="RESET";
                               enableField(ACT)
               }
              
                return false;      
}
function OpenProject(pid)
{
              
                var popupUrl='staff_diary.php?linkid=10&section=1&pid='+pid;
               var mywin=window.open(popupUrl,'Diary','height='+screenH+',width='+screenW+',scrollbars=yes,resizable=yes,left=50,fullscreen=yes');
               mywin.focus();
}
 function resetArea()
{
               var tdArea = '';
               tdArea += '<select name="area" id="area" disabled = "disabled">';
               tdArea += '<option value="">[Select Area]</option></select>';
               document.getElementById('tdArea').innerHTML = tdArea;
}
 function resetDept()
{
               var tdDept = '';
               tdDept += '<select name="dept" id="dept" disabled = "disabled">';
               tdDept += '<option value="0">[Select Department]</option></select>';
               document.getElementById('tdDept').innerHTML = tdDept;
              
 }
 //################################ REPORT AND CONTROL MENU FUNCTIONS ###############################//
function manageSubMenuHOver_R()
{
               document.getElementById("manageSubMenu_R").style.position = "absolute";
               document.getElementById("manageSubMenu_R").style.display = "block";
}
function manageSubMenuOut_R()
{
               document.getElementById("manageSubMenu_R").style.display = "none";
}
function isMouseLeaveOrEnter(e, handler)
 {
                if (e.type != 'mouseout' && e.type != 'mouseover')
                                return false;
                var reltg = e.relatedTarget ? e.relatedTarget : e.type == 'mouseout' ? e.toElement : e.fromElement;
                while (reltg && reltg != handler)
                                reltg = reltg.parentNode;
                                return (reltg != handler);
 }
function manageSubMenuHOver_C()
{
               document.getElementById("manageSubMenu_C").style.position = "absolute";
               document.getElementById("manageSubMenu_C").style.display = "block";
}
function manageSubMenuOut_C()
{
               document.getElementById("manageSubMenu_C").style.display = "none";
}
 //################################ REPORT AND CONTROL MENU FUNCTIONS ###############################//
function open_report()
{
               var winName = window.open("staff_diary.php?section=2",winName,'height='+screenH+',width='+screenW+',scrollbars=yes,left=50,resizable=1,fullscreen=yes');
               winName.focus();
 }
 
function searchpage(page,key,fid)
{
               if(key==13)
               {                                              current_filter = fid;
                                               renderGrid(xmlDoc);
               }
}
 
 

Tosif Shaikh
System Development Engineer, FLYdocs
Intivia Tower, Plot No.117, Behind Prayosha Complex, Chhani Jakat Naka, Vadodara-390024, Gujarat, India
Office: +91 265 304 3341/+91 265 304 3342
tosif.shaik@flydocs.aero | www.flydocs.aero
 

 

This email is confidential and may contain legally privileged information; it is intended for the use of the addressee only. If you are not the addressee you must not read, use, distribute, copy or rely on this email. If you have received this communication in error please notify us immediately by email or by telephone (+44 (0) 1827 289 186). The contents of this email do not constitute a commitment by FLYdocs Systems Ltd (FLYdocs), unless separately endorsed by an authorised representative of FLYdocs. Any views or opinions expressed are solely those of the author and do not necessarily represent those of the company. FLYdocs believes that this email and any attachments are free of viruses. However, it is the responsibility of the recipient to ensure it is virus free and FLYdocs does not accept any responsibility for any loss or damage that may arise from the use of this email or its contents.
 
 