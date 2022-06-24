<?php 
include_once($_SERVER["DOCUMENT_ROOT"]."/maulik_hack/Database/vendor/autoload.php");
class Model
{

        protected $db; //database connection object
        protected $loader;
        
        public function __construct()
        {

                $config = new \Doctrine\DBAL\Configuration();
                $connectionParams = array(
                        'dbname' => 'hackathon_bugsquashers',
                        'user' => 'developmentsit',
                        'password' => 'Admin@123',
                        'host' => '192.168.100.58',
                        'driver' => 'pdo_mysql',
                );
                $this->db = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);     
                                
        }
        public function select($TableName='',$fields,$Whrarr=array())
        {                              
                if(!empty($TableName) && !empty($fields) )
                {
                        $query="SELECT ".$fields." FROM ".$TableName;
                        if(!empty($Whrarr))
                        {
                                $query.=" WHERE ". implode(' = ? and ',array_keys($Whrarr))." = ? ";                               
                        }
                        //echo $query;print_r($Whrarr); exit;
                        $statement = $this->db->executeQuery($query, array_values($Whrarr));
                        $Resultarr = $statement->fetch();                        
                        return $Resultarr;
                }
        }
		public function selectAll($TableName='',$fields,$Whrarr=array())
        {                              
                if(!empty($TableName) && !empty($fields) )
                {
                        $query="SELECT ".$fields." FROM ".$TableName;
                        if(!empty($Whrarr))
                        {
                                $query.=" WHERE ". implode(' = ? and ',array_keys($Whrarr))." = ? ";                               
                        }
                  //     echo $query;print_r($Whrarr); exit;
                        //$statement = $this->db->executeQuery($query, array_values($Whrarr));
                        //$Resultarr = $statement->fetchAssoc();                        
						$Resultarr = $this->db->fetchAll($query, array_values($Whrarr));        
		//print_r($Resultarr); exit;						
                        return $Resultarr;
                }
        }
        public function insert($TableName='',$Whrarr=array())
        {
                if(!empty($TableName) && !empty($Whrarr))
                {
				//echo $TableName;print_r($Whrarr); exit;
                        //$conn->insert('user', array('username' => 'jwage'));
                        $this->db->insert($TableName, $Whrarr);
                }
        }
        public function update($TableName='',$Uparr=array(),$Whrarr=array())
        {
                if(!empty($TableName) && !empty($Uparr) && !empty($Whrarr))
                {
                        $this->db->update($TableName, $Whrarr);
                }
        }
        public function delete($TableName='',$Whrarr=array())
        {
                if(!empty($TableName) && !empty($Whrarr))
                {
                        $this->db->delete($TableName, $Whrarr);//array('id' => 1));
                }
        }
}
?>