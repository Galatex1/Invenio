<?php
require_once('Link.php');


function contains($string, $substring)
{
   return strpos($string, $substring) !== FALSE;
}

function inArray($string, $array)
{
    foreach ($array as $key => $value) {
        if(strtolower($string) == strtolower($value))
            return true;
    }

    return false;
}

class Table{

    public $tblName;
    public $Db;
    public $name;
    private $conn;
    public $links = array();
    public $columns = array();
    public $showColumns = [];
    public $primary = [];
    public $auto_incremented = [];

    public function __construct($tbl, $conn, $_db){
        $this->tblName = $tbl;
        $this->conn = $conn;
        $this->name = $tbl;
        $this->Db = $_db;


        $res = $this->getColumns();
        while($row = mysqli_fetch_assoc($res))
        {       
            $this->columns[] = $row['Field'];
        }

        $res = $this->getPrimaryKeys();
        while($row = mysqli_fetch_assoc($res))
        {               
            $this->primary[] = $row['Column_name'];
        }

        $res = $this->getAutoIncremented();
        while($row = mysqli_fetch_assoc($res))
        {               
            $this->auto_incremented[] = $row['COLUMN_NAME'];
        }

        // print_r($this->primary);
        // print_r($this->auto_incremented);

        $this->showColumns = $this->columns;
    }

    public static function getForeignKeys($conn, $DB)
    {
        $sql = "SELECT TABLE_NAME, COLUMN_NAME, CONSTRAINT_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE REFERENCED_TABLE_SCHEMA = '$DB';";
        $resp = mysqli_query($conn, $sql);         
        return $resp;
    }

    public function getPrimaryKeys()
    {       
        $sql = "SHOW KEYS FROM $this->tblName WHERE Key_name = 'PRIMARY'";
        $resp = mysqli_query($this->conn, $sql);         
        return $resp;
    }

    public function getAutoIncremented()
    {       
        $sql = "SELECT * from INFORMATION_SCHEMA.COLUMNS where TABLE_NAME='$this->tblName' and EXTRA like '%auto_increment%';";
        $resp = mysqli_query($this->conn, $sql);         
        return $resp;
    }

    public function isPrimary($col)
    {
        return in_array($col, $this->primary);
    }
    
    public function isAutoInc($col)
    {
        return in_array($col, $this->auto_incremented);
    }

    public function getColumnType($col)
    {       
     $resp = mysqli_query($this->conn, "SELECT DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '$this->tblName' AND COLUMN_NAME = '$col'");         
     return $resp;
    }

    public static function getTables($conn){
        $resp = mysqli_query($conn, "SHOW TABLES");
            
        return $resp;
    }

    public static function getDBs($conn){
        $resp = mysqli_query($conn, "SHOW DATABASES");
        $DBs = [];

        while($row = mysqli_fetch_assoc($resp))
        {
            $DBs[] = $row['Database'];
        }
            
        return $DBs;
    }

    public static function getDB($conn){
        $resp = mysqli_query($conn, "SELECT DATABASE() as DB");
            
        return mysqli_fetch_assoc($resp);
    }

    public function getColumns(){
        $resp = mysqli_query($this->conn, "SHOW COLUMNS FROM $this->tblName");
            
        return $resp;
    }

    public function count(){
        $resp = mysqli_query($this->conn, "SELECT COUNT(*) as count FROM $this->tblName");
            
        if($res = mysqli_fetch_assoc($resp))
        {
            return $res['count'];
        }
        else {
            return 0;
        }
    }


    public function makeLinks($_linkedTbls, $_links, $_linksTo)
    {
        $this->links = array();
        for($i = 0; $i < count($_linkedTbls); $i++) {
            $this->makeLink($_linkedTbls[$i], $_links[$i], $_linksTo[$i]);
        }
    }

    public function makeLink($linkedTbl, $link, $linkTo)
    {
        if(in_array($link, $this->columns) && inArray($linkTo, $linkedTbl->columns))
        {
            // echo $linkedTbl->tblName;
            $this->links[$linkedTbl->tblName] = new Link($linkedTbl, $link, $linkTo);       
        }
        else {
            //echo "Cant LINK:".in_array($link, $this->columns).", LOOKING FOR: ".$linkTo.", IN: ". implode(", ",$linkedTbl->columns)."<br><br><br>";
        }
    }

    public function get($columns, $filter = "1", $limit = "0, 1000000")
    {
        $sql = "SELECT ";
        foreach ($columns as $key => $col) {
            $sql .= "$this->tblName.$col, "; 
        } 
        $sql .=" $this->tblName.id FROM $this->tblName WHERE $filter LIMIT $limit";
        // echo $sql;   
        $resp = mysqli_query($this->conn, $sql);
            
        return $resp;
    }

    public function findColumnTable($col)
    {
        if(in_array($col, $this->columns))
            return $this->tblName;
        else {
            foreach ($this->links as $name => $ln) {
                if(in_array($col, $ln->linkedTbl->columns))
                    return $ln->linkedTbl->tblName;
            }
        }
    }

    public function isLinked($col)
    {
        foreach ($this->links as $name => $ln) {
            if($col == $ln->link)
                return $ln->linkedTbl->tblName;
        }

        return false;
    }


    public function getAllLinked($columns, $filter = "1",  int $offset = 0, int $limit = 1000)
    {
        $linked = [$this->tblName];
        $join = "";
        $sql = "SELECT ";

        foreach ($columns as $key => $col) {

            if(contains($col, "*") !== FALSE)
            {
                //$sql .= "$this->tblName.$col, ";

                foreach ($this->columns as $c) {

                    $sql .= "$this->tblName.$c AS '$this->tblName.$c', ";
                }

                foreach ($this->links as $tbl => $lk) {

                    foreach ($lk->linkedTbl->columns as $key => $value) {
                        $t = mysqli_real_escape_string($this->conn, $tbl);
                        $v = mysqli_real_escape_string($this->conn, $value);
                        $sql .= "$t.$v AS '$t.$v', ";
                    }

                    if(!in_array($tbl, $linked))
                    {
                         list($tbl, $jn) = $this->findJoin($tbl);
                         $join .= $jn;
                         $linked[] = $tbl;
                    }
                    
                }

                
            }
            else
            {    

                $tbl = $this->findColumnTable($col);
                $skip = false;
                if(contains($col, "::"))
                {                
                    list($tbl, $col) = explode("::", $col);
                    //$skip = true;                          
                }
                else if(contains($col, ":"))
                {
                    list($tbl, $col) = explode(":", $col);            
                }

                
                if($skip === false)
                {
                    // $sql .= "$tbl.$col AS '$tbl.$col', ";
                    $t = mysqli_real_escape_string($this->conn, $tbl);
                    $v = mysqli_real_escape_string($this->conn, $col);
                    $sql .= "$t.$v AS '$t.$v', ";

                }

               if(!in_array($tbl, $linked))
               {
                    list($tbl, $jn) = $this->findJoin($tbl);
                    $join .= $jn;
                    $linked[] = $tbl;
               }

            }
            
            
        }

        $sql = rtrim($sql, ", ");

        $sql .= " FROM $this->tblName"; 

        $sql .= $join;      
        
        $sql .= " WHERE $filter LIMIT $offset, $limit";

        //echo $sql."<br>";
    

        $resp = mysqli_query($this->conn, $sql);   
        return $resp;
    }


    public function findJoin($tbl)
    {
        $join = "";
        $addLink = "";

        if(array_key_exists($tbl, $this->links))
        {
            $ln = $this->links[$tbl];
            $join = " LEFT JOIN ".$tbl." ON $this->tblName.".$ln->link." = ".$tbl.".".$ln->linksTo;
            $addLink = $tbl;
        }
        else {
            foreach ($this->links as $key => $ln) {
                list($addLink, $join) =   $ln->linkedTbl->findJoin($tbl);
                if($join != "" && $addLink != "")
                    break;
            }       
        }

        return [$addLink, $join];
    }


    public function getStructure(array &$structure)
    {
        foreach ($this->columns as $key) {

            $isLinked = $this->isLinked($key);

            if($isLinked === false)
            {
                $structure[$key] = $this->tblName; 
            }
            else {

                // foreach (->linkedTbl->columns as $cl) {
                    
                // }

                $table = $this->links[$isLinked]->linkedTbl;
                $structure[$key] = array();
                $table->getStructure($structure[$key]);
                
            }
        }
    }

    public function makeStructure()
    {
        $structure = [];

        foreach ($this->columns as $key) {

            $isLinked = $this->isLinked($key);

            if($isLinked === false)
            {
                $structure[$key] = $this->tblName; 
            }
            else {

                $table = $this->links[$isLinked]->linkedTbl;
                $structure[$key] = $table->makeStructure();
                          
            }
        }

        return $structure;
    }

    public function hasColumn($col)
    {
        foreach ($this->columns as $value) {
            if(strtolower($value) == strtolower($col))
                return $value;
        }

        return false;
    }

    public function makeTreeView(/* &$html,  */array $structure = [])
    {
        $html = "";

        if(count($structure) == 0)
            $structure = $this->makeStructure();

        foreach ($structure as $key => $value) {

            if(!is_array($value))
            {
                $html .= "<li class=\"chkBox\" style=\"white-space:nowrap;\">";

                $html .= "<input type=\"checkbox\" name=\"$value:$key\" id=\"$value:$key\" checked/>";
                if($this->isPrimary($key))                
                    $html .= "<img class=\"autoInc\" src=\"assets/img/key2.png\" title=\"Primární klíč\"/>";              
                else 
                    $html .= "<img class=\"autoInc\" />";
                
                $html .= "$key</li> ";
                $prev = $value;
            }
            else {

                $isLinked = $this->isLinked($key);
                $table = $this->links[$isLinked]->linkedTbl;

                $html .=  "<li class=\"nest\">";
                // form=\"dummy\"
                $html .=  "<span class=\"caret\"><input type=\"checkbox\" name=\"$this->tblName::$key\" id=\"$this->tblName::$key\" checked  />";
                if($this->isPrimary($key))                
                    $html .= "<img class=\"autoInc\" src=\"assets/img/key2.png\" title=\"Primární klíč\"/>";              
                else 
                    $html .= "<img class=\"autoInc\" />";

                $html .= "$key</span>";
                $html .=  "<ul class=\"nested\">";
                $html .= $table->makeTreeView(/* $html,  */$value);
                $html .=  "</ul></li>";
            }
             
        }

        return $html;
    }


    public function makeFilterList(array $structure, &$html)
    {
        foreach ($structure as $key => $value) {

            if(!is_array($value))
            {
                //$html .= "<li class=\"chkBox\" style=\"white-space:nowrap;\"><input type=\"checkbox\" name=\"$value.$key\" id=\"$value.$key\" checked/>$key</li> ";

                $html .= "<option value=\"$value.$key\">";
                $html .= "$value.$key";
                $html .= "</option>";

            }
            else {

                $this->makeFilterList($value, $html);
                
            }
            
        }
    }

    public function delete($cols)
    {
        $sql = "DELETE FROM $this->tblName WHERE ";

        foreach ($cols as $key => $value) {
            $sql .= mysqli_real_escape_string($this->conn, $key)." = '".mysqli_real_escape_string($this->conn, $value)."' AND ";
        }
        $sql .= "1";
        $res =  mysqli_query($this->conn, $sql);

        if(!$res)
            echo "Error - ".mysqli_error($this->conn);
    }

    public function add($data)
    {
        header('Content-Type: text/html; charset=utf-8');
        $sql = "INSERT INTO $this->tblName(";


        foreach ($this->columns as $key => $value) {
            if( strtolower($value) != "id")
                $sql .= $value.", ";
        }

        $sql = rtrim($sql, ", ");
        $sql .= ") VALUES (";

        foreach ($this->columns as $key => $value) {
            if(strtolower($value) != "id")
                $sql .=  (isset($data[$value]) ? "'".mysqli_real_escape_string($this->conn, $data[$value])."'" : "NULL").", ";
        }
        $sql = rtrim($sql, ", ");
        $sql .= ")";

         //echo $sql;

        mysqli_set_charset($this->conn, "utf8");
        mysqli_query($this->conn, "SET NAMES 'utf8'");
        mysqli_query($this->conn, "SET CHARACTER SET 'utf8'");
        $res = mysqli_query($this->conn, $sql);

        //echo $sql;

        if($res)
            echo "Položka přidána.";
        else 
            echo "Error - Položka nebyla přidáná. Zkuste to prosím znovu.<br><br>".mysqli_error($this->conn);
    }

    public function edit($data)
    {
        header('Content-Type: text/html; charset=utf-8');
        $sql = "UPDATE $this->tblName SET ";

        foreach ($this->columns as $key => $value) {

                $sql .= mysqli_real_escape_string($this->conn, $value)." = ".  (isset($data[$value]) ? "'".mysqli_real_escape_string($this->conn, $data[$value])."'" : "NULL").", ";
        }
        $sql = rtrim($sql, ", ");
        $sql .= " WHERE ";

        foreach ($this->primary as $value) {
            $sql .= "$value = ".  (isset($data["$value-old"]) ? "'".mysqli_real_escape_string($this->conn, $data["$value-old"])."'" : "NULL")." AND ";
        }

        $sql .= " 1 ";

        //echo $sql;

        mysqli_set_charset($this->conn, "utf8");
        mysqli_query($this->conn, "SET NAMES 'utf8'");
        mysqli_query($this->conn, "SET CHARACTER SET 'utf8'");
        $res = mysqli_query($this->conn, $sql);

        //echo $sql;

        if($res)
            echo "Položka upravena.";
        else 
            echo "Error - Položka nebyla upravena. Zkuste to prosím znovu.<br><br>".mysqli_error($this->conn);
    }

}
?>