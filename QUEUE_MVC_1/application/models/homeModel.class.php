<?php
class homeModel extends Model{

public function processData($data=array()){
        $optArr=array(0=>'save',1=>'read',2=>'update');
        $function=(isset($optArr[$data['act']]))?$optArr[$data['act']]:'error';
       if(method_exists($this,$function.'Data')){
        $this->{$function.'Data'}($data);
       }else{
               echo 'no method'. $function;
       }
     
}
private function readData(){
        $fp=fopen('queue.csv','r'); $data=array();
        if(file_exists('queue.csv')){
               
                while(! feof($fp))
                {
                        $data[]=fgetcsv($fp,0);
                }
         // $data=file('queue.csv',FILE_SKIP_EMPTY_LINES);
        }
        fclose($fp);
        unset($data[0]);
        echo json_encode($data);
        exit;
}   
private function saveData($data=array()){
       $fileData=json_decode($data['data'],true);
       $fp=fopen('queue.csv','a');
       if(file_exists('queue.csv')){
       $data=file('queue.csv',FILE_SKIP_EMPTY_LINES);
       $totIdCount=count($data);
       $lastID=$isHeader=0;
       $returnArr=array();
       if($totIdCount>0){
         $lastID=( $totIdCount-1);
       }
     
       if(empty($data)){
                $headerArr=array('id','wino','wititle','programmer','selectType','selectSize','estimationDate','comments','location'); 
                fputcsv($fp, $headerArr);
                $lastID=1;  
       }
       foreach ($fileData as $dat) {
        $tempArr=array('id'=>($lastID++));
        $dat=  $tempArr+$dat;
        $returnArr[]= $dat;
        fputcsv($fp, $dat);
       }}
        fclose($fp);
        echo json_encode($returnArr);
        exit;

    }
    

}
?>