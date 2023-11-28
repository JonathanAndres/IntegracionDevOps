<?php
class Product
{

    private $ProductTable = "Product";
    public $id;
    public $proname;
    public $amount;
    public $time;
    private $conn;
   
    public function __construct($db)
    {
        $this->conn = $db;
    }

    function read()
    {
        if ($this->id) {
            $stmt = $this->conn->prepare("SELECT * FROM " . $this->ProductTable . " WHERE id = ?");
            $stmt->bind_param("i", $this->id);
        } else {
            $stmt = $this->conn->prepare("SELECT * FROM " . $this->ProductTable);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        return $result;
    }

    function update()
    {

        $stmt = $this->conn->prepare("
			UPDATE " . $this->ProductTable . " 
			SET amount= ?
			WHERE id = ?");

        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->amount = htmlspecialchars(strip_tags($this->amount));	

        $stmt->bind_param("si", $this->amount, $this->id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
}
