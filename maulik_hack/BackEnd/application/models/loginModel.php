<?php
class loginModel extends Model{
  public function isloginCheck($uname="",$pass="")
  {    
    $res_ar=$this->select("user","count(UserID) as cntVal",array("User_name"=>$uname,"password"=>$pass));
//    print_r($res_ar); exit;
    $cntVal=$res_ar['cntVal'];
    return ($cntVal>0)?1:0;
  }  

}
?>