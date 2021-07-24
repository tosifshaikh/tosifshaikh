<?php 
//print '<pre>';
$conn=new MYSQLi('localhost','root','','test');
$request=(json_decode(file_get_contents('php://input'),true));

if(isset($request['act']) && $request['act']=='grid')
{
	$headers=$contents=$dispOrder=[];
	$headers[]=array('name'=>'#');
	$headers[]=array('name'=>'');
	$headers[]=array('name'=>'Tail');
	$headers[]=array('name'=>'MSN Number');
	$headers[]=array('name'=>'Check Name');
	$headers[]=array('name'=>'Received Date');
	$headers[]=array('name'=>'Defect');
	$headers[]=array('name'=>'Work Status');
	$headers[]=array('name'=>'Actions','width'=>'15%','align'=>"right");
	
	$data=getData();
	
	$columns['Tail']=["validate"=>[''],'filterid'=>'Tail','filtertype'=>1,'inputType'=>1,'filterVal'=>''];
	$columns['MSN_Number']=["filterid"=>'MSN_Number','filtertype'=>1,'inputType'=>1,'filterVal'=>''];
	$columns['Check_name']=['filterid'=>'Check_name','filtertype'=>1,'inputType'=>1,'filterVal'=>'','validate'=>['']];
	$columns['Received_Date']=['filterid'=>'Received_Date','filtertype'=>4,'inputType'=>4,'filterVal'=>0,'validate'=>['0']];
	$columns['Defect']=['filterid'=>'Defect','filtertype'=>1,'inputType'=>1,'filterVal'=>'','validate'=>['']];
	$columns['Work_status']=['filterid'=>'Work_status','filtertype'=>2,'inputType'=>3,'filterVal'=>0,'validate'=>['0']];
	       
	$json=array();
	$json['headers']=$headers;
	$json['contents']=$data['contents'];
	$json['columns']=$columns;
	$json['disporder']=$data['dispOrder'];
	$json['total']=count($data['contents']);
	print json_encode($json);
}
else if(isset($request['act']) && $request['act']=='save')
{
	//echo 'insave';
	//if(isset($request['save']))
	{
		
		save($request);

	}
	//if(isset($request['update']))
	{
		//update();
	}
	//else if(isset($request['update'])
	//print_r($request);exit;
}else if(isset($request['act']) && $request['act']=='delete')
{
	delete($request['id']);
}
function delete($params=array())
{
	global $conn;

	print '<pre>';print_r($conn);exit;

}
function update($params=array())
{
	global $conn;

	print '<pre>';print_r($conn);exit;

}
function save($saveArr=array())
{
	global $conn;
	$maxDisplayOrder=0;
	//if(isset($saveArr['insertlast']))
	{
		//$insertLast=$saveArr['insertlast'];
		$sql = "SELECT max(displayOrder) as displayorder FROM mcc_listing";
		$result = $conn->query($sql);
		//print '<pre>';print_r($result );
		//if ($result->num_rows > 0) {
			$row = $result->fetch_assoc();
			$maxDisplayOrder=$row['displayorder']+1;
		//}
			
	}
	$columnArr=array("Tail","MSN_Number","Check_name","Received_Date","Defect","Work_status","displayOrder");
	$insertedIds=[];
	if(isset($saveArr['insert']))
	{
		$insertRows=$saveArr['insert'];$insertStr=[];$updateStr='';
		
		foreach($insertRows as $rowID =>$rowVal)
		{
			$sqlStr=' Insert into mcc_listing('.implode(',',$columnArr).') values ';
		    $update=' update mcc_listing ';
			if(isset($saveArr['insertlast']) && isset($saveArr['insertlast'][$rowID]))
			{  
				//$myDateTime = DateTime::createFromFormat('Y-m-d', $dateString);
				//$newDateString = $myDateTime->format('d-m-Y');
				$sqlStr.=" ('".$rowVal["Tail"]."','".$rowVal["MSN_Number"]."',".$rowVal["Check_name"].",".$rowVal["Received_Date"].",".$rowVal["Defect"].",".$rowVal["Work_status"].",".$maxDisplayOrder.")";
				
				if($conn->query($sqlStr))
				{
					$insertedIds[]=$conn->insert_id;
				}
				$maxDisplayOrder++;
			}else if(!empty($saveArr['updateorder']) && array_search($rowID,$saveArr['updateorder'])>=0){
				$key=array_search($rowID,$saveArr['updateorder']);
				$update .=" set displayOrder=displayOrder+1 where displayOrder>=".($key+1);
				if($conn->query($update))
				{
					$sqlStr.=" ('".$rowVal["Tail"]."','".$rowVal["MSN_Number"]."',".$rowVal["Received_Date"].",".$rowVal["Defect"].",
				".$rowVal["Work_status"].") ";
					if($conn->query($sqlStr))
					{
						$insertedIds[]=$conn->insert_id;
					}
				}
			}
			$maxRecId++;
		}
		
	}
	if(isset($saveArr['update']))
	{
		$updateRows=$saveArr['update'];
	
		foreach($updateRows as $rowID =>$rowVal)
		{
			$update=' update mcc_listing ';	
			$update .=" set Tail='".$rowVal['Tail']."',MSN_Number='".$rowVal['MSN_Number']."',Received_Date=".$rowVal['Received_Date'].",Defect=".$rowVal["Defect"].",Work_status=".$rowVal["Work_status"];
			$update .=" where id=".$rowID;
			//echo $update;
			if($conn->query($update))
			{
				$insertedIds[]=$rowID;
			}
		}
	}
	$data=getData(array('id'=>$insertedIds));
	$json=array();
	$json['contents']=$data['contents'];
	$json['disporder']=$data['dispOrder'];
	$json['total']=count($data['contents']);
	print json_encode($json);
	exit;
}
function getData($idsArr=array())
{
	global $conn;
	$inStr='';
	$contents=$dispOrder=[];
	if(!empty($idsArr))
	{
		$inStr=" where ID in (".implode(",",$idsArr).")";
	}
	$sql = "SELECT * FROM mcc_listing ".$inStr." order by displayOrder";
	$result = $conn->query($sql);
	if ($result->num_rows > 0)
	 {
		while($row = $result->fetch_assoc()) 
		{

			$contents['_'.$row['ID']]=array(
				'Tail'=> $row['Tail'], 
				'MSN_Number'=>  $row['MSN_Number'], 
				'Check_name'=> $row['Check_name'],
				'Received_Date'=>$row['Received_Date'],
				'Defect'=>$row['Defect'],
				'Work_status'=>$row['Work_status'],
				'id'=>$row['ID']
			);
			$dispOrder[$row['displayOrder']]=$row['ID'];
		//	echo 'ingrid';print_r($row);exit;
			//echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
		}
	}
	return [
		'contents' =>$contents,
		'dispOrder' =>$dispOrder
	];
}
?>