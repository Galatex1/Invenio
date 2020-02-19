<?php
require_once('Link.php');

class Table{

    public $tblName;
    private $conn;
    public $links = array();
    public $columns = array();


    public static function getTables($conn){
        $resp = mysqli_query($conn, "SHOW TABLES");
            
        return $resp;
    }


    public function __construct($tbl, $conn){
        $this->tblName = $tbl;
        $this->conn = $conn;

        $res = $this->get(["*"], "1", "1");
        while($row = mysqli_fetch_array($res, 1))
        {       
            foreach ($row as $key => $value) {
                $this->columns[] = $key;
            }         
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

    public function getLinked($columns, $columnsLinked, $linkedTbl, $link, $linkTo, $filter = "1", $limit = "0, 1000000")
    {
        $sql = "SELECT ";
        foreach ($columns as $key => $col) {
            if($col != "id")
                $sql .= "$this->tblName.$col, "; 
        }
        foreach ($columnsLinked as $key => $col) {
            $sql .= "$linkedTbl.$col AS '$linkedTbl"."."."$col', "; 
        }
        
        $sql .=" $this->tblName.id FROM $this->tblName JOIN $linkedTbl ON $this->tblName.$link = $linkedTbl.$linkTo WHERE $filter LIMIT $limit";
        
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
                        $ln = $this->links[$tbl];
                        $join .= " JOIN ".$tbl." ON $this->tblName.".$ln->link." = ".$tbl.".".$ln->linksTo;
                        $linked[] = $tbl;
                    }
                    
                }

                
            }
            else
            {    

                $tbl = $this->findColumnTable($col);

                if(strpos($col, "."))
                {
                    list($tbl, $col) = explode(".", $col);            
                }
                else if(strpos($col, "_"))
                {                  
                    list($tbl, $col) = explode("_", $col);                          
                }
                

               $sql .= "$tbl.$col AS '$tbl.$col', ";


               if(!in_array($tbl, $linked))
               {
                    $ln = $this->links[$tbl];
                    $join .= " JOIN ".$tbl." ON $this->tblName.".$ln->link." = ".$tbl.".".$ln->linksTo;
                    $linked[] = $tbl;
               }

            }
            
            
        }

        $sql = rtrim($sql, ", ");

        $sql .= " FROM $this->tblName"; 

        $sql .= $join;      
        
        $sql .= " WHERE $filter LIMIT $limit";

        //  echo $sql."<br>";
    

        $resp = mysqli_query($this->conn, $sql);   
        return $resp;
    }

}


?>