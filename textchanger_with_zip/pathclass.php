<?php
class pathclass 
{
	public $globalArr=array();
	/*public function getPathDetail()
	{
		$pathArr=array();
		$pathArr[1]="C:\\xampp\\htdocs\\textchanger\\";
		$pathArr[2]="F:\\test\\SIT\\";
		$pathArr[3]="F:\\test\\UAT\\";
		$pathArr[4]="F:\\test\\LIVE\\";
		$this->globalArr['pathinfo']=$pathArr;
	}*/

	public function createZip()
	{
		$tempPathArr=array();
		$tempPathArr[1]="LOCAL";
		$tempPathArr[2]="SIT";
		$tempPathArr[3]="UAT";
		$tempPathArr[4]="LIVE";
	
		$pathArr=json_decode($this->getData(),true);
		chdir("C:\\");//print "<pre>";print_r($_REQUEST);;print_r($tempPathArr);
		//print "<pre>";
	
		//print_r($_SERVER['DOCUMENT_ROOT']);
	//	$zip_temp_name =$_SERVER['DOCUMENT_ROOT']."/textchanger/".$tempPathArr[$_REQUEST["sel0"]]."_".$_REQUEST["workitem"]."_".date("d_m_Y").".zip"
	//echo getcwd();
	$zip_temp_name ="test5555.zip";
		$zip = new ZipArchive;
		$res=$zip->open($_SERVER['DOCUMENT_ROOT']."\\textchanger\\".$zip_temp_name ,ZipArchive::CREATE);
		print $res."--->";
		/*foreach(explode("\n",$_REQUEST["txtarea"]) as $path)
		{
			$zip->addFile($path,str_replace($pathArr,"",$path));
		}*/
		print_r($zip);
		$zip->close();
		
	}
	public function checkfile()
	{
		chdir("F:\\");
		$file="file/".$this->globalArr['filename'];
		if(!is_dir("file"))
		{
			mkdir("file",0777,true);
				if(!file_exists($file))
			{
				file_put_contents($file,"{}");
			}
		}
		
	}
	public function writeFileData()
	{
		
			$FLG=0;
			$pathArr=json_decode($this->getData(),true);
			if(isset($_REQUEST['data']) && $_REQUEST['data']["act"]=="EDIT")
			{
			$oldpathID=$_REQUEST['data']['selIndex'];
			$pathArr[$oldpathID]=$_REQUEST['data']['newpath'];
			}else
			{
				$key=0;
				if(!empty($pathArr))
				{
					end($pathArr);
					$key=key($pathArr);
				}
				$cnt=($key+1);
				$pathArr[$cnt]=$_REQUEST['data']['newpath'];
			}
			if(!empty($pathArr)){
			if(file_put_contents("file/".$this->globalArr['filename'],json_encode($pathArr)))
			{
				$FLG=1;
				$pathArr=json_decode($this->getData(),true);
			}
			echo  json_encode(array("FLG"=>$FLG,"path"=>$pathArr));
		}
	}
	public function getData()
	{
		$this->checkfile();
		
		return file_get_contents("file/".$this->globalArr['filename']);
	}
	public function deleteData()
	{
		$FLG=0;
		$key=$_REQUEST['id'];
		$pathArr=json_decode($this->getData(),true);
		unset($pathArr[$key]);
		if(file_put_contents($this->globalArr['filename'],json_encode($pathArr)))
		{
			$FLG=1;
			$pathArr=json_decode($this->getData(),true);
		}
			echo  json_encode(array("FLG"=>$FLG,"path"=>$pathArr));
	}
	
}
?>