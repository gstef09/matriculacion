<?php
// used to get mysql database connection
class DatabaseService{

    private $db_host =  "localhost";#"20.20.20.113";
    private $db_name = "bd_academico_unesum";
    private $db_user = "mod_matricula";
    private $db_password = "66cbb56d8737712315e3ed5995d01d2c3a0365fe";
    private $connection;

    public function getConnection(){

        $this->connection = null;

        try{
            $this->connection = new PDO("mysql:host=" . $this->db_host . ";dbname=" . $this->db_name.";charset=UTF8", $this->db_user, $this->db_password);
        }catch(PDOException $exception){
            echo "Connection failed: " . $exception->getMessage();
        }

        return $this->connection;
    }
}
?>

