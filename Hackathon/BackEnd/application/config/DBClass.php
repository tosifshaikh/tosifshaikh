<?php
class Database{

    public function delete($tableName='')
    {
        $conn->delete('user', array('id' => 1));
        // DELETE FROM user WHERE id = ? (1)
    }


}
?>