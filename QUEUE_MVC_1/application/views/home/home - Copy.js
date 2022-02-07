var homeObj=(function(d,jq){
        var dynamicGridObj={};
        dynamicGridObj.data=[];
        var cnt=0,rowIndex=0,indexCount=1,dataRowIndex=0;

        var loadGrid=function(){
             var defaults={tableData:{
                tableID:'dynamicTable',
                border:1,
                width:'100%',
                divID:'dynamicGrid'},
                header:function(){
                        var headerArr=[];
                        headerArr.push({name:'',id:'',eleType:5});
                        headerArr.push({name:'Sr no',id:'srno',eleType:0,defValue:[0,'']});
                        headerArr.push({name:'Work Item',id:'wino',eleType:1 ,defValue:[0,''] });
                        headerArr.push({name:'Title',id:'wititle',eleType:1 ,defValue:['']  });
                        headerArr.push({name:'Programmer/TL',id:'programmer',eleType:1 ,defValue:[''] });
                        headerArr.push({name:'Select Type',id:'selectType',eleType:2,selTypeArr:0 ,defValue:['']});
                        headerArr.push({name:'WI Size',id:'selectSize',eleType:2,selTypeArr:1 ,defValue:['']});
                        headerArr.push({name:'Estimation Date',id:'estimationDate',eleType:3 ,defValue:['']});
                        headerArr.push({name:'Comments',id:'comments',eleType:4 ,defValue:['']});
                        headerArr.push({name:'Page Location',id:'location',eleType:1 ,defValue:['']});
                        return headerArr;
                },
                selectObj:{
                        0:{0:'Bug',1:'Enhancement'},
                        1:{0:'Small',1:'Medium',2:'Big'}
                        }
                
              
             };
                this.init(defaults);  
        } 
        loadGrid.prototype.init=function(obj){
                var that = this;
                this.headers=obj.header();
               this.selObj= obj.selectObj;
               this.tableObj=obj.tableData;
               that.creatDynamicTable();
               
        }
        loadGrid.prototype.creatDynamicTable=function(){
                var that = this;
                var table=d.createElement('table');
                table.border =  this.tableObj.border;
                table.id=  this.tableObj.tableID;
                table.width= this.tableObj.width;
                document.getElementById(this.tableObj.divID).appendChild(table);
                this.dynamicTab=document.getElementById(this.tableObj.tableID);
                that.renderTable(this.tableObj);
        }
        loadGrid.prototype.renderTable=function(){
                this.renderHeaders();
                this.renderRows();
              
        },loadGrid.prototype.renderHeaders=function(){
                var table=this.dynamicTab,
                row = this.dynamicTab.insertRow(rowIndex),
                headeLen=this.headers.length;
                for(var i=0;i<headeLen;i++)
                {
                   row.insertCell(i).innerHTML = this.headers[i].name;
                }
                rowIndex++;
        }
        loadGrid.prototype.renderRows=function(){
                var table=this.dynamicTab,
                row=table.insertRow(rowIndex),
                headeLen=this.headers.length;
                row.className='cls_'+cnt;console.log(headeLen)
                for(var i=0;i<headeLen;i++)
                {
                        this.createForm(row,i,cnt);
                        
                }
                this.bindObj(cnt);
                cnt++;
                rowIndex++;
                indexCount++;
              
        }, loadGrid.prototype.createForm=function(r,y,rowCnt){
                var eleId=this.headers[y].id+'_'+rowCnt;
                      if(this.headers[y].eleType==0){
                        r.insertCell(-1).innerHTML=(indexCount)+'.';     
                }
                else if(this.headers[y].eleType==1){
                          r.insertCell(-1).innerHTML='<input type=text id='+eleId+' name='+eleId+'>';      
                        }else if(this.headers[y].eleType==2){
                                if( this.selObj[this.headers[y].selTypeArr]){
                               var optArr= this.selObj[this.headers[y].selTypeArr];
                                var optLen=Object.keys(optArr).length;
                               var selStr='<select id='+eleId+' name='+eleId+'>'; 
                               selStr +='<option value="">Select</option>';
                               for(var j in optArr){
                                selStr +='<option value='+j+'>'+optArr[j]+'</option>';
                               }
                                selStr +='</select>';
                                r.insertCell(-1).innerHTML=selStr ;
                                }

                        }else if(this.headers[y].eleType==3){
                                r.insertCell(-1).innerHTML='<input type=text id='+eleId+' name='+eleId+'>';  ;
                        }
                        else if(this.headers[y].eleType==4){
                                r.insertCell(-1).innerHTML='<textarea id='+eleId+' name='+eleId+'></textarea>';   
                        } else if(this.headers[y].eleType==5){
                                r.insertCell(-1).innerHTML='<image src='+imgPath+'plus.png width=20px height=20px onclick=homeObj.addRows() id=add_'+cnt+'>&nbsp;&nbsp;<image src='+imgPath+'remove.png width=20px height=20px  id=del_'+cnt+' onclick=homeObj.delRow()>';   
                        }
                        else{
                                r.insertCell(-1).innerHTML='';
                        }

        }, loadGrid.prototype.bindObj=function(val){
                $('#estimationDate_'+val).datepicker({
                        dateFormat: 'dd-mm-yy',
                        onClose:function(){
                                $(this).trigger('blur');
                                $(this).focus();
                        }
                });
                if(document.getElementById('btnSave')){
               document.getElementById('btnSave').addEventListener('click', this.SaveFn,false);
              }
              //exclude first row
              for(var i=0;i<(this.dynamicTab.rows.length-1);i++){
               var cells=document.querySelectorAll('.cls_'+i+' td ');
                 for(var p=0;p<cells.length;p++){
                        cells[p].addEventListener('blur',this.SaveRowVal,true);
                
               }
              } 
        
        //       if(document.getElementsByClassName('addClick')){
        //               var addClickClass=document.getElementsByClassName('addClick');
        //         for (var i = 0; i < addClickClass.length; i++) {
        //                 addClickClass[i].addEventListener('click', obj.addRows, false);
        //             }
        //       }
        
        };
       





//         dynamicGridObj.dynamicGrid='dynamicGrid',
//        // obj.dataGrid='dataGrid',
//         // obj.loadDataGrid=function(){
//         //         $.ajax({method:'post',url:'index.php?p=home&c=index&a=home&act=1',dataType:'JSON',success:function(data){
//         //                 obj.allData=data;
//         //                 obj.renderDataGrid();
//         //                 }}); 
//         // },obj.renderDataGrid=function(){
//         //         obj.details();
//         //         var table=d.createElement('table');
//         //         table.border = "1";
//         //         table.id='dataTable';
//         //         table.width='100%';
//         //         if(document.getElementById(this.dataGrid))
//         //         document.getElementById(this.dataGrid).appendChild(table);
//         //         this.dataTab=document.getElementById("dataTable");
//         //         obj.displayDataHeaders();
//         // },
//         dynamicGridObj.displayDataHeaders=function(){
//                 var table=this.dataTab;
//                 var row = table.insertRow(dataRowIndex);
//                 for(var i=0;i<this.headerLength;i++)
//                 {
//                    row.insertCell(i).innerHTML = this.headerArr[i].name;
//                 }
//                 dataRowIndex++;
//         },
//         dynamicGridObj.getHeaders=function(flg){
//                 var that=this;
//                 var i=0,j=0;
//                 this.filterObj={},this.headerName={},this.GridHeaders=[];

//                 this.headerArr=[];
//                 var allLocalHeader=[];
//                // this.headerArr[i]={name:'',id:'',filterType:1,eleType:5}
               
//                 // this.headerArr.push({name:'Sr no',id:'srno',filterType:1,eleType:0,defValue:[0,'']});
//                 // this.headerArr.push({name:'Work Item',id:'wino',filterType:1,eleType:1 ,defValue:[0,''] });
//                 // this.headerArr.push({name:'Title',id:'wititle',filterType:1,eleType:1 ,defValue:['']  });
//                 // this.headerArr.push({name:'Programmer/TL',id:'programmer',filterType:1,eleType:1 ,defValue:[''] });
//                 // this.headerArr.push({name:'Select Type',id:'selectType',filterType:1,eleType:2,selTypeArr:0 ,defValue:['']});
//                 // this.headerArr.push({name:'WI Size',id:'selectSize',filterType:1,eleType:2,selTypeArr:1 ,defValue:['']});
//                 // this.headerArr.push({name:'Estimation Date',id:'estimationDate',filterType:1,eleType:3 ,defValue:['']});
//                 // this.headerArr.push({name:'Comments',id:'comments',filterType:1,eleType:4 ,defValue:['']});
//                 // this.headerArr.push({name:'Page Location',id:'location',filterType:1,eleType:1 ,defValue:['']});

//                 allLocalHeader.push({name:'Sr no',id:'srno',filterType:1,eleType:0,defValue:[0,'']});
//                 allLocalHeader.push({name:'Work Item',id:'wino',filterType:1,eleType:1 ,defValue:[0,''] });
//                 allLocalHeader.push({name:'Title',id:'wititle',filterType:1,eleType:1 ,defValue:['']  });
//                 allLocalHeader.push({name:'Programmer/TL',id:'programmer',filterType:1,eleType:1 ,defValue:[''] });
//                 allLocalHeader.push({name:'Select Type',id:'selectType',filterType:1,eleType:2,selTypeArr:0 ,defValue:['']});
//                 allLocalHeader.push({name:'WI Size',id:'selectSize',filterType:1,eleType:2,selTypeArr:1 ,defValue:['']});
//                 allLocalHeader.push({name:'Estimation Date',id:'estimationDate',filterType:1,eleType:3 ,defValue:['']});
//                 allLocalHeader.push({name:'Comments',id:'comments',filterType:1,eleType:4 ,defValue:['']});
//                 allLocalHeader.push({name:'Page Location',id:'location',filterType:1,eleType:1 ,defValue:['']});
               
//                 Object.assign(this.GridHeaders, allLocalHeader);
//                 Object.assign(this.headerArr, allLocalHeader);
//                 this.headerArr.unshift({name:'',id:'',eleType:5});
//                 this.GridHeaders.push({name:'',id:'',eleType:5});
//                 this.headerLength=this.headerArr.length;
//                 for(var j=0;j<this.headerLength;j++){
//                 if(allLocalHeader[j]){
//                         if(!this.filterObj[allLocalHeader[j].id] && allLocalHeader[j].defValue)
//                         {
//                                 this.filterObj[allLocalHeader[j].id]=allLocalHeader[j].defValue;
//                                 this.headerName[allLocalHeader[j].id]=allLocalHeader[j].name;
//                         } 
//                   }
//                 }
//         },dynamicGridObj.createDynamicTable=function(){
//                 var table=d.createElement('table');
//                 table.border = "1";
//                 table.id='dynamicTable';
//                 table.width='100%';
//                 document.getElementById(dynamicGridObj.dynamicGrid).appendChild(table);
//         },
       
//         dynamicGridObj.loadGrid=function(){
//                 dynamicGridObj.renderTable({
//                         tableID:'dynamicTable',
//                         border:1,
//                         width:'100%',
//                         dynamicGrid:'dynamicGrid'
//                 });
//               //  dynamicGridObj.loadDataGrid();      
             
//         },dynamicGridObj.renderTable=function(obj){
//                 this.detailsObj=obj;
//                 // var table=d.createElement('table');
//                 // table.border = "1";
//                 // table.id='dynamicTable';
//                 // table.width='100%';
//                 // document.getElementById(dynamicGridObj.dynamicGrid).appendChild(table);
//                  dynamicGridObj.createDynamicTable(obj);
//                 this.dynamicTab=document.getElementById("dynamicTable");
//                 dynamicGridObj.details();
//                 dynamicGridObj.getHeaders();
//                 dynamicGridObj.displayHeaders();
//                 dynamicGridObj.getRows();
//                 var lastRow=table.insertRow(-1);
//                 lastRow.innerHTML='<input type="button" value="SAVE" id="btn_Save" onclick="homeObj.SaveFn()"></input>';
//         },
//         dynamicGridObj.details=function(){
//                   this.selTypeArr=[];
//                   this.selTypeArr[0]={0:'Bug',1:'Enhancement'} ;
//                   this.selTypeArr[1]={0:'Small',1:'Medium',2:'Big'} ;

//         },dynamicGridObj.displayHeaders=function(){
//                 var table=this.dynamicTab;
//                 var row = table.insertRow(rowIndex);
//                 for(var i=0;i<this.headerLength;i++)
//                 {
//                    row.insertCell(i).innerHTML = this.headerArr[i].name;
//                 }
//                 rowIndex++;
               
//         },dynamicGridObj.getRows=function(){
//                 var table=this.dynamicTab;
//                 var row=table.insertRow(rowIndex);
//                 row.className='cls_'+cnt;
//                 for(var i=0;i<this.headerLength;i++)
//                 {
//                         dynamicGridObj.createForm(row,i,cnt);
                       
//                 }
//                 dynamicGridObj.bindObj(cnt);
//                  cnt++;
//                  rowIndex++;
//                  indexCount++;
                
//         },dynamicGridObj.createForm=function(r,y,cnt){
               
//                 var eleId=this.headerArr[y].id+'_'+cnt;
//                 if(this.headerArr[y].eleType==0){
//                         r.insertCell(-1).innerHTML=(indexCount)+'.';     
//                 }
//                 else if(this.headerArr[y].eleType==1){
//                           r.insertCell(-1).innerHTML='<input type=text id='+eleId+' name='+eleId+'>';      
//                         }else if(this.headerArr[y].eleType==2){
//                                 if(this.selTypeArr[this.headerArr[y].selTypeArr]){
//                                var optArr=this.selTypeArr[this.headerArr[y].selTypeArr];
//                                 var optLen=Object.keys(optArr).length;
//                                var selStr='<select id='+eleId+' name='+eleId+'>'; 
//                                selStr +='<option value="">Select</option>';
//                                for(var j in optArr){
//                                 selStr +='<option value='+j+'>'+optArr[j]+'</option>';
//                                }
//                                 selStr +='</select>';
//                                 r.insertCell(-1).innerHTML=selStr ;
//                                 }

//                         }else if(this.headerArr[y].eleType==3){
//                                 r.insertCell(-1).innerHTML='<input type=text id='+eleId+' name='+eleId+'>';  ;
//                         }
//                         else if(this.headerArr[y].eleType==4){
//                                 r.insertCell(-1).innerHTML='<textarea id='+eleId+' name='+eleId+'></textarea>';   
//                         } else if(this.headerArr[y].eleType==5){
//                                 r.insertCell(-1).innerHTML='<image src='+imgPath+'plus.png width=20px height=20px onclick=homeObj.addRows() id=add_'+cnt+'>&nbsp;&nbsp;<image src='+imgPath+'remove.png width=20px height=20px  id=del_'+cnt+' onclick=homeObj.delRow()>';   
//                         }
//                         else{
//                                 r.insertCell(-1).innerHTML='';
//                         }
//         },
//         dynamicGridObj.bindObj=function(val){
//                 $('#estimationDate_'+val).datepicker({
//                         dateFormat: 'dd-mm-yy',
//                         onClose:function(){
//                                 $(this).trigger('blur');
//                                 $(this).focus();
//                         }
//                 });
//                 if(document.getElementById('btnSave')){
//                document.getElementById('btnSave').addEventListener('click', dynamicGridObj.SaveFn,false);
//               }
//               //exclude first row
//               for(var i=0;i<(dynamicGridObj.dynamicTab.rows.length-1);i++){
//                var cells=document.querySelectorAll('.cls_'+i+' td ');
//                  for(var p=0;p<cells.length;p++){
//                         cells[p].addEventListener('blur',dynamicGridObj.SaveRowVal,true);
                      
//                }
//               } 
           
//         //       if(document.getElementsByClassName('addClick')){
//         //               var addClickClass=document.getElementsByClassName('addClick');
//         //         for (var i = 0; i < addClickClass.length; i++) {
//         //                 addClickClass[i].addEventListener('click', obj.addRows, false);
//         //             }
//         //       }
             
//         },
//         dynamicGridObj.addRows=function(){
//                 dynamicGridObj.getRows();   
//         },
//         dynamicGridObj.Validate=function(flg,value,index1){

//                 if(flg==1 && dynamicGridObj.filterObj[index1[0]] && $.inArray(value,dynamicGridObj.filterObj[index1[0]])!='-1'){
//                         return false;
//                 }else{
//                         for(var i=0;i<(dynamicGridObj.dynamicTab.rows.length-2);i++){
//                                for(var p=0;p< dynamicGridObj.headerLength;p++){
//                               if(document.getElementById(dynamicGridObj.headerArr[p].id+'_'+i) && $.inArray(document.getElementById(dynamicGridObj.headerArr[p].id+'_'+i).value,dynamicGridObj.filterObj[dynamicGridObj.headerArr[p].id])!='-1'){
//                                          alert('Row:'+(i+1)+' Please enter Value in '+dynamicGridObj.headerName[dynamicGridObj.headerArr[p].id]);
//                                         return false;
//                                 }
//                               }
//                          }          
//                 }
//         },
//         dynamicGridObj.SaveRowVal=function(ele){
//                 if(ele){
                        
//                         var index1=(ele.target.id).split('_');
//                         if( dynamicGridObj.Validate(1,ele.target.value,index1)){
//                                 alert('Please enter Value in row'+index1[1]);
//                                 return false;
//                         }
//                         if(!dynamicGridObj.data[index1[1]]){
//                                 dynamicGridObj.data[index1[1]]={};
//                                 dynamicGridObj.data[index1[1]][index1[0]]=ele.target.value;
                         
//                         }else{
//                                 dynamicGridObj.data[index1[1]][index1[0]]=ele.target.value;
                           
//                         }
//                  }
//         },
//         dynamicGridObj.delRow=function(){
//                 if(dynamicGridObj.dynamicTab.rows.length==2){
//                         alert('Can not delete.');
//                         return false;
//                 }
//                 dynamicGridObj.dynamicTab.deleteRow(-1);
//                 --cnt;
//         },
       
//         dynamicGridObj.SaveFn=function(){
                
//                 if(dynamicGridObj.Validate(2)){
//                         return false;      
//                 }
//                 if(dynamicGridObj.data.length==0){
//                // alert('Please Enter Data.');
//                 //return false;
//                }
//                 // for(var i=0;i<(obj.dynamicTab.rows.length-2);i++){
//                 //         obj.data[i]={};
//                 //         for(var p=0;p< obj.headerLength;p++){

//                 //               if(document.getElementById(obj.headerArr[p].id+'_'+i)){
//                 //                 obj.data[i][obj.headerArr[p].id]=document.getElementById(obj.headerArr[p].id+'_'+i).value;    
//                 //               }
//                 //         }
//                 // }
//                 $.ajax({method:'post',url:'index.php?p=home&c=index&a=home&act=0',data:{'data':JSON.stringify(dynamicGridObj.data)},dataType:'JSON',success:function(data){
//                 console.log(data);
//                 }});
                
//         }
       
        
//return {init:dynamicGridObj.loadGrid,addRows:dynamicGridObj.addRows,delRow:dynamicGridObj.delRow,SaveFn:dynamicGridObj.SaveFn}
return {loadGrid:loadGrid,addRows:addRows}
}(document,$));
$( function() {
     var r=new homeObj.loadGrid();
      } );