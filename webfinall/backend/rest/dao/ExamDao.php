<?php

class ExamDao
{

  private $conn;

  /**
   * constructor of dao class
   */
  public function __construct()
  {
    try {
      $dsn = "mysql:host=localhost;port=3306;dbname=webfinal";
      $this->conn = new PDO($dsn, "root", "root", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
      ]);
      /** TODO
       * List parameters such as servername, username, password, schema. Make sure to use appropriate port
       */

      /** TODO
       * Create new connection
       */
      echo "Connected successfully";
    } catch (PDOException $e) {
      echo "Connection failed: " . $e->getMessage();
    }
  }

  /** TODO
   * Implement DAO method used to get customer information
   */
  public function get_customers()
  {
    $stmt = $this->conn->prepare("SELECT * from customers");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  /** TODO
   * Implement DAO method used to get customer meals
   */
  public function get_customer_meals($customer_id)
  {
    $stmt = $this->conn->prepare("SELECT f.name , f.brand , m.created_at FROM meals m JOIN foods f ON m.food_id = f.id WHERE m.customer_id = (:customer_id)");
    $stmt->bindValue(":customer_id", $customer_id);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  /** TODO
   * Implement DAO method used to save customer data
   */
  public function add_customer($data)
  {
    $columns = implode(", ", array_keys($data));
    $placeholders = ":" . implode(", :", array_keys($data));
    $query = "INSERT INTO customers ($columns) VALUES ($placeholders)";
    $stmt = $this->conn->prepare($query);
    $stmt->execute($data);
    $data['id'] = $this->conn->lastInsertId();
    return $data;
  }

  /** TODO
   * Implement DAO method used to get foods report
   */
  public function get_foods_report() {
    /* SELECT 
    f.name,
    f.brand,
    f.image_url AS image,
    SUM(CASE WHEN n.name = 'energy' THEN fn.quantity ELSE 0 END) AS energy,
    SUM(CASE WHEN n.name = 'protein' THEN fn.quantity ELSE 0 END) AS protein,
    SUM(CASE WHEN n.name = 'fat' THEN fn.quantity ELSE 0 END) AS fat,
    SUM(CASE WHEN n.name = 'fiber' THEN fn.quantity ELSE 0 END) AS fiber,
	SUM(case when n.name = 'carbs' then fn.quantity else 0 end) as carbs
FROM foods f
LEFT JOIN food_nutrients fn ON f.id = fn.food_id
LEFT JOIN nutrients n ON fn.nutrient_id = n.id
GROUP BY f.id, f.name, f.brand, f.image_url
ORDER BY f.name
LIMIT 100 OFFSET 0; */

  }
}
