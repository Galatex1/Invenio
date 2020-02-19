<?php
require_once('Link.php');

class Table{

    public $tblName;
    public $name;
    private $conn;
    public $links = array();
    public $columns = array();
    public $showColumns = [];

    public function __construct($tbl, $conn){
        $this->tblName = $tbl;
        $this->conn = $conn;
        $this->name = $tbl;

        //$res = $this->get(["*"], "1", "1");
        $res = $this->getColumns();
        while($row = mysqli_fetch_assoc($res))
        {       
            // foreach ($row as $key => $value) {
                
            // }    
            
            $this->columns[] = $row['Field'];
        }

        $this->showColumns = $this->columns;
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
            
        return $resp;
    }

    public static function getDB($conn){
        $resp = mysqli_query($conn, "SELECT DATABASE() as DB");
            
        return mysqli_fetch_assoc($resp);
    }

    public function getColumns(){
        $resp = mysqli_query($this->conn, "SHOW COLUMNS FROM $this->tblName");
            
        return $resp;
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
        if(in_array($link, $this->columns) && in_array($linkTo, $linkedTbl->columns))
        {
            // echo $linkedTbl->tblName;
            $this->links[$linkedTbl->tblName] = new Link($linkedTbl, $link, $linkTo);        
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


    public function getAllLinked($columns, $filter = "1", $limit = "0, 1000000")
    {
        $linked = [$this->tblName];
        $join = "";
        $sql = "SELECT ";

        foreach ($columns as $key => $col) {

            if(strpos($col, "*") !== FALSE)
            {
                $sql .= "$this->tblName.$col, ";

                foreach ($this->links as $tbl => $lk) {

                    foreach ($lk->linkedTbl->columns as $key => $value) {
                        $sql .= "$tbl.$value AS '$tbl.$value', ";
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
                if(strpos($col, ":"))
                {                
                    list($tbl, $col) = explode(":", $col);
                    $skip = true;                          
                }
                else if(strpos($col, "."))
                {
                    list($tbl, $col) = explode(".", $col);            
                }
                else if(strpos($col, "_"))
                {                  
                    list($tbl, $col) = explode("_", $col);                          
                }

                
                if($skip === false)
                {
                    $sql .= "$tbl.$col AS '$tbl.$col', ";
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
        
        $sql .= " WHERE $filter LIMIT $limit";

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
            $join = " JOIN ".$tbl." ON $this->tblName.".$ln->link." = ".$tbl.".".$ln->linksTo;
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

    public static function makeTreeView(array $structure, &$html)
    {
        $prev = "";
        foreach ($structure as $key => $value) {
            
            if(!is_array($value))
            {
                $html .= "<li class=\"chkBox\" style=\"white-space:nowrap;\"><input type=\"checkbox\" name=\"$value.$key\" id=\"$value.$key\" checked/>$key</li> ";
                $prev = $value;
            }
            else {
                $html .=  "<li class=\"nest\">";
                // form=\"dummy\"
                $html .=  "<span class=\"caret\"><input type=\"checkbox\" name=\"$prev:$key\" id=\"$prev:$key\" checked  />$key</span>";
                $html .=  "<ul class=\"nested\">";
                Table::makeTreeView($value, $html);
                $html .=  "</ul></li>";
            }
             
        }
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

    public function delete($id)
    {
        $sql = "DELETE FROM $this->tblName WHERE id=$id ";
        $res =  mysqli_query($this->conn, $sql);
    }

    public function add($data)
    {
        header('Content-Type: text/html; charset=utf-8');
        $sql = "INSERT INTO $this->tblName(";
        $sql .= implode(", ", $this->columns);
        $sql = str_replace("id, ", "", $sql);
        $sql = rtrim($sql, ",");
        $sql .= ") VALUES (";

        foreach ($this->columns as $key => $value) {
            if($value != "id")
            $sql .=  (isset($data[$value]) ? "'".$data[$value]."'" : "NULL").", ";
        }
        $sql = rtrim($sql, ", ");
        $sql .= ")";

        // echo $sql;

        mysqli_set_charset($this->conn, "utf8");
        mysqli_query($this->conn, "SET NAMES 'utf8'");
        mysqli_query($this->conn, "SET CHARACTER SET 'utf8'");
        $res = mysqli_query($this->conn, $sql);

        if($res)
            echo "Položka přidána.";
        else 
            echo "Error - Položka nebyla přidáná. Zkuste to prosím znovu.";
    }

}

// Elektrikář - slaboproudá a silnoproudá zařízení
?>