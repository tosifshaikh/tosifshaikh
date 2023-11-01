<?php
namespace App\Models;

class Task
{
    private $db;
    const CREDENTIALS = [
        'host' => 'localhost',
        'username' => 'root', 'pass' => 'root', 'db' => 'toucantech'
    ];
    public function __construct() {
        $this->connect();
    }
    public function connect()
    {
        if (!$this->db) {
            $this->db = new \mysqli(
                self::CREDENTIALS['host'],
                self::CREDENTIALS['username'],
                self::CREDENTIALS['pass'],
                self::CREDENTIALS['db']
            ) or die('Connection issue');
        }
    }
    public function getSchool()
    {
        $data = [];
        if ($result = $this->db->query('select * from schools')) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }
    public function getGrid($from = 0)
    {
        $data = [];
        $cols = '*';
        if ($from == 1) {
            $cols = 'm.member_id,m.name,m.email_address,s.school_name';
        }
        if ($result = $this->db->query('SELECT ' . $cols . ' FROM members m JOIN schools s ON m.school_id = s.school_id')) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        if ($from == 1) {
            return $data;
        }
        return json_encode($data);
    }
    public function SaveMembers($_request)
    {
        $res['success'] = false;
        $sql = "INSERT INTO members (" . implode(',', array_keys($_request)) . ") VALUES ";
        $values = "(?" . str_repeat(",?", count($_request) - 1) . ")";
        $sql .= $values;
        $stmt = $this->db->prepare($sql);
        $types = str_repeat("s", count($_request));
        $stmt->bind_param($types, ...array_values($_request));
        if ($stmt->execute()) {
            $res['success'] = true;
        }
        return json_encode($res);
    }
    public function countNumMemberBySchool()
    {
        $data = [];
        if ($result = $this->db->query("SELECT COUNT(m.member_id) total_members,s.school_name FROM members m JOIN schools s USING (school_id) GROUP BY school_id")) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return json_encode($data);
    }
    public function export()
    {
        ob_start();
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=csv_export.csv');
        header('Cache-Control: max-age=0');
        $headers = array('#', 'Name', 'Email', 'School');
        $data = $this->getGrid(1);
        ob_end_clean();
        $output = fopen('php://output', 'w');
        fputcsv($output, $headers);
        foreach ($data as $data_item) {
            fputcsv($output, $data_item);
        }
        fclose($output);
        exit;
    }
    public function getTask2Grid($q = '')
    {
        $this->db->query("SET GLOBAL sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))");
        $sql  = 'SELECT  *
        FROM profiles p LEFT JOIN emails e ON e.UserRefID= p.UserRefID';
        if (isset($q['search']) && $q['search'] != '') {
            $sql  .= ' WHERE (p.Firstname LIKE "%' . $q["search"] . '%" OR p.Surname LIKE "%' . $q["search"] . '%") ';
        }

        $sql  .=  ' GROUP BY e.emailaddress';
        $data = [];
        if ($result = $this->db->query($sql)) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }

        return json_encode($data);
    }
}
