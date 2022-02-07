

// function require(script) {
//     $.ajax({
//         url: script,
//         dataType: "script",
//         async: false,           // <-- This is the key
//         success: function () {
//             // all good...
//         },
//         error: function () {
//             throw new Error("Could not load script " + script);
//         }
//     });
// }

console.log('eeeeee');
var homeObj=(function(d,jq){
        var dynamicGridObj={},renderData={};
        dynamicGridObj.data=[];
        var globObj={},saveObj={};
        
        function loadGrid(obj){
                globObj.saveRowData={};
                globObj.headers=obj.header();
                globObj.selObj= obj.selectObj;
                globObj.tableObj=obj.dynamicTabl;
                globObj.countObj=obj.countObj;
                globObj.settings=obj;
                init();  
        } 
       function init(){
               creatDynamicTable();   
        }
        function creatDynamicTable(){
             
                var table=d.createElement('table');
                table.border =  globObj.tableObj.border;
                table.id=  globObj.tableObj.tableID;
                table.width= globObj.tableObj.width;
                if(document.getElementById(globObj.tableObj.divID)){
                document.getElementById(globObj.tableObj.divID).appendChild(table);
                }
                if(document.getElementById(globObj.tableObj.tableID)){
                globObj.dynamicTab=document.getElementById(globObj.tableObj.tableID);
                }
                renderTable();
        }
        function renderTable(){
                 renderHeaders();
                 renderRows();
                 getLastRow();
        }
        function getLastRow(){
                var lastRow=globObj.dynamicTab.insertRow();
                lastRow.innerHTML='<input type="button" value="SAVE" id="btn_Save" onclick="homeObj.SaveFn()"></input>';
        }
        function getLastRowIndex(){
                return globObj.dynamicTab.rows.length; 
        }
        function renderHeaders(){
                var table=globObj.dynamicTab,
                row = globObj.dynamicTab.insertRow(getLastRowIndex()),
                headeLen=globObj.headers.length;
                for(var i=0;i<headeLen;i++)
                {
                        row.insertCell(i).innerHTML = globObj.headers[i].name;
                }
        }
        function renderRows(){
                var lastIndx=getLastRowIndex(),rowIndx=0;
                if(lastIndx==1)
                {
                        rowIndx= lastIndx 
                }else{
                        rowIndx=lastIndx-1;
                }
                 globObj.countObj.indexCount=rowIndx;
                var table=globObj.dynamicTab,
                row=table.insertRow(rowIndx),
                headeLen=globObj.headers.length;
                row.className='cls_'+(globObj.countObj.indexCount-1);

                for(var i=0;i<headeLen;i++)
                {
                        createForm(row,i); 
                }
                      
        }
        function createForm(r,y){
                var eleId=(globObj.countObj.indexCount-1)+'_'+y;
                      if(globObj.headers[y].eleType==0){
                        r.insertCell(-1).innerHTML="<span id='span_"+(globObj.countObj.indexCount-1)+"'>"+(globObj.countObj.indexCount)+"</span>";     
                }
                else if(globObj.headers[y].eleType==1){
                          r.insertCell(-1).innerHTML='<input type=text id='+eleId+' name='+eleId+'>';      
                        }else if(globObj.headers[y].eleType==2){
                                if( globObj.selObj[globObj.headers[y].selTypeArr]){
                                var optArr= globObj.selObj[globObj.headers[y].selTypeArr];
                                var optLen=Object.keys(optArr).length;
                                var selStr='<select id='+eleId+' name='+eleId+'>'; 
                                selStr +='<option value="">Select</option>';
                                for(var j in optArr){
                                selStr +='<option value='+j+'>'+optArr[j]+'</option>';
                                }
                                selStr +='</select>';
                                r.insertCell(-1).innerHTML=selStr ;
                                }

                        }else if(globObj.headers[y].eleType==3){
                                r.insertCell(-1).innerHTML='<input type=text id='+eleId+' name="edate[]">';
                                bindObj(eleId);
                        }
                        else if(globObj.headers[y].eleType==4){
                                r.insertCell(-1).innerHTML='<textarea id='+eleId+' name='+eleId+'></textarea>';   
                        } else if(globObj.headers[y].eleType==5){
                                r.insertCell(-1).innerHTML='<image src='+imgPath+'plus.png width=20px height=20px onclick=homeObj.addRows() id=add_'+(globObj.countObj.indexCount-1)+'>&nbsp;&nbsp;<image src='+imgPath+'Save.png width=20px height=20px onclick=homeObj.saveSingleRow('+(globObj.countObj.indexCount-1)+') id=add_'+(globObj.countObj.indexCount-1)+'>&nbsp;&nbsp;<image src='+imgPath+'remove.png width=20px height=20px  id=del_'+(globObj.countObj.indexCount-1)+' onclick=homeObj.delRow(this.id)>'; 

                        }
                        else{
                                r.insertCell(-1).innerHTML='';
                        }

        }//
        function addRows(){
                 renderRows();            
         }
         function delRow(delId){
                  if(document.getElementById(globObj.tableObj.tableID).rows.length<4){
                        alert('Can not delete.');
                        return false;
                }
                var delID=delId.split('_')[1];
                 if(document.getElementsByClassName('cls_'+delID)){
                        if(globObj.dynamicTab.rows.length==3){
                                return false;
                        }
                        document.getElementsByClassName('cls_'+delID)[0].outerHTML='';
                }
                reIndex();
               // globObj.dynamicTab.deleteRow( getLastRowIndex()-2);
         }
         function bindObj(id)
         {
                $('#'+id).datepicker({
                        dateFormat: 'dd-mm-yy',
                        changeMonth:1,
                        changeYear:1,
                        onClose:function(){    
                                $(this).trigger('blur');
                                $(this).focus();
                        },
                        beforeShow: function(){    
                        $(".ui-datepicker").css('font-size', 12) 
                         }
                });//
        //         if(document.getElementById('btnSave')){
        //        document.getElementById('btnSave').addEventListener('click', this.SaveFn,false);
        //       }
        //       //exclude first row
        //       for(var i=0;i<(this.dynamicTab.rows.length-1);i++){
        //        var cells=document.querySelectorAll('.cls_'+i+' td ');
        //          for(var p=0;p<cells.length;p++){
        //                 cells[p].addEventListener('blur',this.SaveRowVal,true);
        //        }
        //       } 
        
        // //       if(document.getElementsByClassName('addClick')){
        // //               var addClickClass=document.getElementsByClassName('addClick');
        // //         for (var i = 0; i < addClickClass.length; i++) {
        // //                 addClickClass[i].addEventListener('click', obj.addRows, false);
        // //             }
        // //       }
        
        }
        function reIndex(){
                 var totalRows=(globObj.dynamicTab.rows.length),indexCount=1;
                for(var i=0;i<totalRows;i++){
                        if(document.getElementById('span_'+i)){   
                                document.getElementById('span_'+i).outerHTML="<span id='span_"+(indexCount-1)+"'>"+indexCount+"</span>";
                                indexCount++;
                        }
                }
        }
        function saveSingleRow(rowID){
                var headeLen=globObj.headers.length;
                for(var i=0;i<headeLen;i++)
                {
                        saveFormValues(rowID,i); 
                }
                if(document.getElementsByClassName('cls_'+rowID)){
                        if(globObj.dynamicTab.rows.length==3){
                                return false;
                        }
                        document.getElementsByClassName('cls_'+rowID)[0].outerHTML='';
                }
               reIndex();
        }
       
        function saveFormValues(rowID,i){
              
                if(document.getElementById(rowID+"_"+i) && $.inArray(document.getElementById(rowID+"_"+i).value,globObj.headers[i].defValue)=='-1'){
                        if(!saveObj[rowID]){
                        saveObj[rowID]={};     
                        }
                var insValue=document.getElementById(rowID+"_"+i).value;
                if(globObj.headers[i].eleType==3){
                        insValue=insValue.split('-');
                        insValue=(insValue[2]+'-'+insValue[1]+'-'+insValue[0]);
                }
                 saveObj[rowID][globObj.headers[i].id]= insValue;
                
                }
              
        }
        loadGrid.prototype.filterRow=function(){
                var table=this.dynamicTab,
                row = this.dynamicTab.insertRow(this.countObj.rowIndex),  
                headeLen=this.headers.length;
                for(var i=0;i<headeLen;i++)
                {
                        row.insertCell(i).innerHTML = this.filterRowEle(i);
                }
                this.countObj.rowIndex++;
        }
        loadGrid.prototype.filterRowEle=function(i){
                
                
                        if(this.headers[i].filterType==1){
                          return '<input type=text>';      
                        }else{
                          return '';       
                        }

        
        }
        
       
        // loadGrid.filterRow=function(){
        //         var table=this.dynamicTab,
        //         row = this.dynamicTab.insertRow(this.countObj.rowIndex),
        //         headeLen=this.headers.length;
        //         for(var i=0;i<headeLen;i++)
        //         {
        //            row.insertCell(i).innerHTML = this.headers[i].name;
        //         }
        //         this.countObj.rowIndex++;
        // };
        // loadGrid.renderTable=function(){
        //         this.renderHeaders();
        //         if(this.settings.isGrid==false){
        //             // this.filterRow();   
        //         }
        //         this.renderRows();
        //         if(this.settings.isGrid==false){
        //         var lastRow=this.dynamicTab.insertRow(-1);
        //         lastRow.innerHTML='<input type="button" value="SAVE" id="btn_Save" onclick="homeObj.SaveFn()"></input>'; 
        //         }
        // };
        // loadGrid.renderHeaders=function(){
        //         var table=this.dynamicTab,
        //         row = this.dynamicTab.insertRow(this.countObj.rowIndex),
        //         headeLen=this.headers.length;
        //         for(var i=0;i<headeLen;i++)
        //         {
        //            row.insertCell(i).innerHTML = this.headers[i].name;
        //         }
        //         this.countObj.rowIndex++;
        // };
        // loadGrid.renderRows=function(){
        //         var table=this.dynamicTab,
        //         row=table.insertRow(this.countObj.rowIndex),
        //         headeLen=this.headers.length;
        //         row.className='cls_'+this.countObj.cnt;
        //         for(var i=0;i<headeLen;i++)
        //         {
        //                 this.createForm(row,i); 
        //         }
        //         this.bindObj();
        //         this.countObj.cnt++;
        //         this.countObj.rowIndex++;
        //         this.countObj.indexCount++;
              
        // };
        //  loadGrid.createForm=function(r,y){
        //         var eleId=this.countObj.cnt+'_'+y;
        //               if(this.headers[y].eleType==0){
        //                 r.insertCell(-1).innerHTML=(this.countObj.indexCount)+'.';     
        //         }
        //         else if(this.headers[y].eleType==1){
        //                   r.insertCell(-1).innerHTML='<input type=text id='+eleId+' name='+eleId+'>';      
        //                 }else if(this.headers[y].eleType==2){
        //                         if( this.selObj[this.headers[y].selTypeArr]){
        //                         var optArr= this.selObj[this.headers[y].selTypeArr];
        //                         var optLen=Object.keys(optArr).length;
        //                         var selStr='<select id='+eleId+' name='+eleId+'>'; 
        //                         selStr +='<option value="">Select</option>';
        //                         for(var j in optArr){
        //                         selStr +='<option value='+j+'>'+optArr[j]+'</option>';
        //                         }
        //                         selStr +='</select>';
        //                         r.insertCell(-1).innerHTML=selStr ;
        //                         }

        //                 }else if(this.headers[y].eleType==3){
        //                         r.insertCell(-1).innerHTML='<input type=text id='+eleId+' name="edate[]">';  ;
        //                 }
        //                 else if(this.headers[y].eleType==4){
        //                         r.insertCell(-1).innerHTML='<textarea id='+eleId+' name='+eleId+'></textarea>';   
        //                 } else if(this.headers[y].eleType==5){
        //                         r.insertCell(-1).innerHTML='<image src='+imgPath+'plus.png width=20px height=20px onclick=homeObj.addRows() id=add_'+this.countObj.cnt+'>&nbsp;&nbsp;<image src='+imgPath+'remove.png width=20px height=20px  id=del_'+this.countObj.cnt+' onclick=homeObj.delRow()>'; 

        //                 }
        //                 else{
        //                         r.insertCell(-1).innerHTML='';
        //                 }

        // };
        //  loadGrid.bindObj=function(){
        //           $('input[name="edate[]"]').datepicker({
        //                 dateFormat: 'dd-mm-yy',
        //                 onClose:function(){
        //                         $(this).trigger('blur');
        //                         $(this).focus();
        //                 }
        //         });//
        //         // $('#estimationDate_'+val).datepicker({
        //         //         dateFormat: 'dd-mm-yy',
        //         //         onClose:function(){    
        //         //                 $(this).trigger('blur');
        //         //                 $(this).focus();
        //         //         }
        //         // });
        //         if(document.getElementById('btnSave')){
        //        document.getElementById('btnSave').addEventListener('click', this.SaveFn,false);
        //       }
        //       //exclude first row
        //       for(var i=0;i<(this.dynamicTab.rows.length-1);i++){
        //        var cells=document.querySelectorAll('.cls_'+i+' td ');
        //          for(var p=0;p<cells.length;p++){
        //                 cells[p].addEventListener('blur',this.SaveRowVal,true);
        //        }
        //       } 
        
        // //       if(document.getElementsByClassName('addClick')){
        // //               var addClickClass=document.getElementsByClassName('addClick');
        // //         for (var i = 0; i < addClickClass.length; i++) {
        // //                 addClickClass[i].addEventListener('click', obj.addRows, false);
        // //             }
        // //       }
        
        // };
        // loadGrid.addRows=function(){
        //         loadGrid.renderRows();            
        // };
        // loadGrid.delRow=function(){
        //         if(document.getElementById(loadGrid.tableObj.tableID).rows.length<4){
        //                 alert('Can not delete.');
        //                 return false;
        //         }
        //         loadGrid.dynamicTab.deleteRow(loadGrid.countObj.rowIndex-1);
        //         --loadGrid.countObj.cnt;--loadGrid.countObj.indexCount;--loadGrid.countObj.rowIndex;
        // };
        // loadGrid.SaveRowVal=function(ele){
        //         if(ele){
        //                 var index1=(ele.target.id).split('_');
        //                 if( loadGrid.Validate(1,ele.target.value,index1)){
        //                         alert('Please enter Value in row'+index1[0]);
        //                         return false;
        //                 }
        //                 if(!loadGrid.saveRowData[index1[0]]){
        //                         loadGrid.saveRowData[index1[0]]={};
        //                         loadGrid.saveRowData[index1[0]][loadGrid.headers[index1[1]].id]=ele.target.value; 
        //                 }else{
        //                         loadGrid.saveRowData[index1[0]][loadGrid.headers[index1[1]].id]=ele.target.value;
                                
        //                 }
        //                loadGrid.finalData=Object.values(loadGrid.saveRowData);
        //         }
        // };
        // loadGrid.Validate=function(flg,value,index1){

        //         if(flg==1 && loadGrid.headers[index1[1]] && $.inArray(value,loadGrid.headers[index1[1]].defValue)!='-1'){
        //                 return false;
        //         }else{
        //                 for(var i=0;i<(loadGrid.dynamicTab.rows.length-2);i++){
        //                         for(var p=0;p< loadGrid.headers.length;p++){
        //                         if(document.getElementById(loadGrid.headers[p].id+'_'+i) && $.inArray(document.getElementById(loadGrid.headers[p].id+'_'+i).value,dynamicGridObj.filterObj[dynamicGridObj.headerArr[p].id])!='-1'){
        //                                         alert('Row:'+(i+1)+' Please enter Value in '+loadGrid.headers[p].name);
        //                                 return false;
        //                         }
        //                         }
        //                         }          
        //         }
        // };
        // loadGrid.SaveFn=function(){
                
        //         // console.log('final111',loadGrid.finalData);return
        //         // if(dynamicGridObj.Validate(2)){
        //         //         return false;      
        //         // }
        //         // if(dynamicGridObj.data.length==0){
        //         // alert('Please Enter Data.');
        //         //return false;
        //       //  } 
        //         // for(var i=0;i<(obj.dynamicTab.rows.length-2);i++){
        //         //         obj.data[i]={};
        //         //         for(var p=0;p< obj.headerLength;p++){
        //                 // 
        //         //               if(document.getElementById(obj.headerArr[p].id+'_'+i)){
        //         //                 obj.data[i][obj.headerArr[p].id]=document.getElementById(obj.headerArr[p].id+'_'+i).value;    
        //         //               }
        //         //         }
        //         // }
        //         $.ajax({method:'post',url:'index.php?p=home&c=index&a=home&act=0',
        //         data:{'data':JSON.stringify(loadGrid.finalData)},
        //         dataType:'JSON',
        //         success:function(data){
        //                loadGrid.renderData=data;
        //                 loadGrid.loadDataGrid();
        //         }});
                                
        // };
        // loadGrid.loadDataGrid=function(){
               
        //        // this.gridInit(defaults);
               
        //        // console.log(defaults,'defaults1',defaults.headers());
        // };
        
        

return {loadGrid:loadGrid,addRows:addRows,delRow:delRow,saveSingleRow:saveSingleRow}
}(document,$));
$( function() {
        var defaults={
                dynamicTabl:{
           tableID:'dynamicTable',
           border:1,
           width:'100%',
           divID:'dynamicGrid'
           },
        //         dataTabl:{
        //            tableID:'dataTable',
        //            border:1,
        //            width:'100%',
        //            divID:'dataGrid'
        //    },
           header:function(){
                   var headerArr=[];
                   headerArr.push({name:'',eleType:5});
                   headerArr.push({name:'Sr no',id:'srno',eleType:0,defValue:[0,'']});
                   headerArr.push({name:'Work Item',id:'wino',eleType:1 ,defValue:[0,''],filterType:1 });
                   headerArr.push({name:'Title',id:'wititle',eleType:1 ,defValue:[''] ,filterType:1 });
                   headerArr.push({name:'Programmer/TL',id:'programmer',eleType:1 ,defValue:[''] ,filterType:1});
                   headerArr.push({name:'Select Type',id:'selectType',eleType:2,selTypeArr:0 ,defValue:[''],filterType:2});
                   headerArr.push({name:'WI Size',id:'selectSize',eleType:2,selTypeArr:1 ,defValue:[''],filterType:2});
                   headerArr.push({name:'Estimation Date',id:'estimationDate',eleType:3 ,defValue:[''],filterType:1});
                   headerArr.push({name:'Comments',id:'comments',eleType:4 ,defValue:[''],filterType:1});
                   headerArr.push({name:'Page Location',id:'location',eleType:1 ,defValue:[''],filterType:1});
                   return headerArr;
           },
           isGrid:false,
           selectObj:{
                   0:{0:'Bug',1:'Enhancement'},
                   1:{0:'Small',1:'Medium',2:'Big'}
                   }, 
                   countObj:{cnt:0,rowIndex:0,dataRowIndex:0,indexCount:0}
           
         
        };
     var r=new homeObj.loadGrid(defaults);
//      var defaults2={
//         dynamicTabl:{
//                 tableID:'dynamicTable2',
//                 divID:'dataGrid',
//                 width:'100%',
//                 border:1
//         },
//         header:function(){
//                 var headArr= defaults.header()
//                 ,lastEle=headArr.shift();
//                 headArr.push(lastEle);
//                 return headArr
//         },
//         isGrid:true,
//         selectObj:defaults.selectObj,
//         countObj:{cnt:0,rowIndex:0,dataRowIndex:0,indexCount:1}
//};

   // var r2=new homeObj.loadGrid(defaults2);
   //  console.log(homeObj.addRows());
      } ); 