<?php 
//print '<pre>';
$conn=new MYSQLi('localhost','root','','test');
$request=(json_decode(file_get_contents('php://input'),true));

if(isset($request['act']) && $request['act']=='grid')
{
	$headers=$contents=$dispOrder=[];
	$headers[]=array('name'=>'Sr No.');
	$headers[]=array('name'=>'');
	$headers[]=array('name'=>'company_name');
	$headers[]=array('name'=>'city');
	$headers[]=array('name'=>'turn_over');
	$headers[]=array('name'=>'status');
	$headers[]=array('name'=>'action');
	
	$data=getData();
	

	/*$contents['_200']=array( 'company_name'=> 'Amazon Web Services', 'city'=> 'New York', 'turn_over'=> 400,'status'=>2,'rec_id'=>1,'id'=>200);
	$contents['_100']=array( 'company_name'=> 'ABC Infotech', 'city'=> 'New Jersey', 'turn_over'=> 100,'status'=>1,'rec_id'=>3,'id'=>100);
	$contents['_500']=array( 'company_name'=> 'Digital Ocean', 'city'=> 'Washington', 'turn_over'=> 200,'status'=>3,'rec_id'=>4,'id'=>500);
	$contents['_22']= array( 'company_name'=> 'Digital Ocean1', 'city'=> 'Washington1', 'turn_over'=> 2020,'status'=>4,'rec_id'=>2,'id'=>22);
	$contents['_223']= array( 'company_name'=> 'Digital Ocean1', 'city'=> 'Washington1', 'turn_over'=> 2020,'status'=>4,'rec_id'=>5,'id'=>223);

	$dispOrder[]=22;
	$dispOrder[]=500;
	$dispOrder[]=223;
	$dispOrder[]=200;
	$dispOrder[]=100;
*/
	$columns['company_name']=["validate"=>[''],'filterid'=>'company_name','filtertype'=>1,'inputType'=>1,'filterVal'=>''];
	$columns['city']=["filterid"=>'city','filtertype'=>1,'inputType'=>2,'filterVal'=>''];
	$columns['turn_over']=['filterid'=>'turn_over','filtertype'=>1,'inputType'=>1,'filterVal'=>'','validate'=>['']];
	$columns['status']=['filterid'=>'status','filtertype'=>2,'inputType'=>3,'filterVal'=>0,'validate'=>['0']];
	       
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
	$maxDisplayOrder=$maxRecId=0;
	//if(isset($saveArr['insertlast']))
	{
		//$insertLast=$saveArr['insertlast'];
		$sql = "SELECT max(displayorder) as displayorder,max(recid) as maxRecid  FROM hack";
		$result = $conn->query($sql);
		//print '<pre>';print_r($result );
		//if ($result->num_rows > 0) {
			$row = $result->fetch_assoc();
			$maxDisplayOrder=$row['displayorder']+1;
			$maxRecId=$row['maxRecid']+1;
		//}
			
	}
	$insertedIds=[];
	if(isset($saveArr['insert']))
	{
		$insertRows=$saveArr['insert'];$insertStr=[];$updateStr='';
		
		foreach($insertRows as $rowID =>$rowVal)
		{
			$sqlStr=' Insert into hack(company,city,turn,status,displayorder,recid) values ';
		    $update=' update hack ';
			if(isset($saveArr['insertlast']) && isset($saveArr['insertlast'][$rowID]))
			{  
				$sqlStr.=" ('".$rowVal["company"]."','".$rowVal["city"]."',".$rowVal["turn"].",".$rowVal["status"].",
				".$maxDisplayOrder.",".$maxRecId.") ";
				
				if($conn->query($sqlStr))
				{
					$insertedIds[]=$conn->insert_id;
				}
				$maxDisplayOrder++;
			}else if(!empty($saveArr['updateorder']) && array_search($rowID,$saveArr['updateorder'])>=0){
				$key=array_search($rowID,$saveArr['updateorder']);
				$update .=" set displayorder=displayorder+1 where displayorder>=".($key+1);
				if($conn->query($update))
				{
					$sqlStr.=" ('".$rowVal["company"]."','".$rowVal["city"]."',".$rowVal["turn"].",".$rowVal["status"].",
				".($key+1).",".$maxRecId.") ";
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
			$update=' update hack ';	
			$update .=" set company='".$rowVal['company']."',city='".$rowVal['city']."',turn=".$rowVal['turn'].",status=".$rowVal["status"];
			$update .=" where id=".$rowID;
			//echo $update;
			if($conn->query($update))
			{
				$insertedIds[]=$rowID;
			}
		}
	}
	$data=getData($insertedIds);
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
		$inStr=" where id in (".implode(",",$idsArr).")";
	}
	$sql = "SELECT * FROM hack ".$inStr." order by displayorder";
	$result = $conn->query($sql);
	if ($result->num_rows > 0)
	 {
		while($row = $result->fetch_assoc()) 
		{

			$contents['_'.$row['id']]=array(
				'company_name'=> $row['company'], 
				'city'=>  $row['city'], 
				'turn_over'=> $row['turn'],
				'status'=>$row['status'],
				'rec_id'=>$row['recid'],
				'id'=>$row['id']
			);
			$dispOrder[]=$row['id'];
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