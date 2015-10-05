 <?php
 /*PHP5 MYSQLi DB Connection Class implementing Singleton Design Pattern
 * 
 * Author  : Thanura Siribaddana
 * Date    : 05-APR-2010
 * Web Site: www.thanura.com
 * 
 *  Usage:
    $mysqli = DBAccess::getConnection();
    $query = "select * from TABLE";
    $result=$mysqli->selectQuery($query);
 */
//error_reporting(E_NOTICE);

class DBAccess{

 const DB_NAME="eyedock_data";

//  const DB_SERVER="localhost"; 
//  const DB_USER="root";
//  const DB_PASS="root";

 const DB_SERVER="mysql.eyedock.com"; 
 const DB_USER="eyedockdatauser";
 const DB_PASS="kvBS^VQR";
 
 private $_connection;
 private static $_dbinstance;

/* Made the constructor private, 
* to ensures that the class can only be instantiated 
* from within itself.
*/
 private function __construct(){
  try{
   $this->_connection= new mysqli(self::DB_SERVER,
                                     self::DB_USER,
                                     self::DB_PASS,
                                     self::DB_NAME);
   if ($this->_connection->connect_error){
       throw new Exception("Could not connect to Database.");
   }
  }catch(Exception $err){
   echo $err->getMessage();
   die();
  }
  
 }
 public static function getConnection(){
  if (is_null(self::$_dbinstance)){
   self::$_dbinstance= new DBAccess();
  }
  return self::$_dbinstance;
 }
 /* Execute a SQL query and returns the results*/
 public function selectQuery($query){
  $rs=$this->_connection->query($query);
  return $rs; 
 }

 public function last_ID() {
 	return mysqli_insert_id($this->_connection);
 }



}
