<?php 
	require_once ($_SERVER['DOCUMENT_ROOT'] . '/serv-join-sql.php');

	$db = Database::getInstance();
	$db->connect();

	class Products {
		private $db;

		public function __construct($db) {
		$this->db = $db;
			
		}

		public function displayGroups($parentId = 0) {

		    $data_groups = $this->getGroups($parentId);
		    
		    if ($data_groups->num_rows > 0) {
		        echo "<ul>";
		        while ($row = $data_groups->fetch_assoc()) {
		            $groupId = $row["id"];
		            $groupName = $row["name"];
		            $productCount = $this->countProducts($groupId);

		            echo "<li><a href=\"?group=$groupId\">$groupName ($productCount)</a>";
		            $this->displayGroups($groupId);
		            echo "</li>";
		        }
		         echo "</ul>";
		    }
		}

		public function countProducts($groupId) {
		    $totalProducts = 0;

		    $sql = "SELECT COUNT(*) as total FROM products WHERE id_group = ?";
		    $stmt = $this->db->getConnection()->prepare($sql);
		    $stmt->bind_param("i", $groupId);
		    $stmt->execute();
		    $result = $stmt->get_result();
		    $row = $result->fetch_assoc();
		    $totalProducts += $row['total'];

		    $data_groups = $this->getGroups($groupId);

		    while ($row = $data_groups->fetch_assoc()) {
		        $totalProducts += $this->countProducts($row['id']);
		    }
		    return $totalProducts;
		}

		public function displayProducts($groupId) {
		    $sql = "SELECT id, name FROM products WHERE id_group = ?";
		    $stmt = $this->db->getConnection()->prepare($sql);
		    $stmt->bind_param("i", $groupId);
		    $stmt->execute();
		    $result = $stmt->get_result();

		    if ($result->num_rows > 0) {
		        while ($row = $result->fetch_assoc()) {
		            $productName = $row["name"];
		            echo "<li style='list-style-type: none;'>$productName</li>";
		        }
		    } else {
		        echo "Товаров в группе нет.";
		    }

		    $data_groups = $this->getGroups($groupId);

		    while ($row = $data_groups->fetch_assoc()) {
		        $this->displayProducts($row['id']);
		    }
		}

		public function getGroups ($id_parent){
			$sql = "SELECT id, name FROM groups WHERE id_parent = ?";
		    $stmt = $this->db->getConnection()->prepare($sql);
		    $stmt->bind_param("i", $id_parent);
		    $stmt->execute();
		    $result = $stmt->get_result();
		    return $result;
		}
	}
?>
