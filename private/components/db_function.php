<?php require_once (PRIVATE_PATH . '/path.php');

    function connect_db() {
        $conn = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
        if ($conn->connect_error) {
            echo "Connection failed: " . $conn->connect_error;
            exit;
        }
        return $conn;
    }

    function db_disconnect($connection) {
        if(isset($connection)) {
            $connection -> close();
        }
    }

    function register_user($info) {
        global $db;
        // $hashed_password = password_hash($info["password"], PASSWORD_DEFAULT);
        $query = "INSERT INTO user (first_name, last_name, email, password) VALUES (?, ?, ?, ?)";
        $stmt = $db->prepare($query);
        $stmt->bind_param('ssss', $info["first_name"], $info["last_name"], $info["email"], $info["password"]);
    
        if ($stmt->execute()) {
            return true;
        } else {
            echo "Error: " . $stmt->error;
        }
    }

    function check_register_duplicate($userEmail) {
        global $db;
        $isDuplicate = false;
        $query = "SELECT email FROM user ORDER BY id ASC";
        $stmt = $db->query($query);
        while($result = $stmt->fetch_assoc()) {
            if ($userEmail === $result["email"]) {
                $isDuplicate = true;
                break;
            }
        }
        $stmt->free_result();
        return $isDuplicate;
    }
    
    function get_user_login($userInfo) {
        global $db;
        $query = "SELECT id, email, first_name, last_name, password FROM user WHERE email=?";
        $stmt = $db -> prepare($query);
        $stmt -> bind_param('s', $userInfo["email"]);
        $stmt -> execute();
        $data = $stmt -> get_result();
        $result = $data -> fetch_assoc();
        $data->free_result();
        $stmt -> close();
        return $result;
    }

    function check_user_login($userInfo, $result) {        
        $message= "";
        switch ($userInfo) {
            case !isset($result["email"]):
                $message = '<script>alert("User don\'t exist, please register");</script>';
                break;
            case $userInfo["password"] !== $result["password"]:
                $message = '<script>alert("Password incorrect, please try again");</script>';
                break;
            default:
                $message = NULL;
        }
        return $message;
    }

    function add_password($user_info) {
        global $db;
        $query = "INSERT INTO passwords (user_id, pass) VALUES (?, ?)";
        $stmt = $db -> prepare($query);
        $stmt -> bind_param('ss', $user_info["id"], $user_info["genPassword"]);
        if ($stmt->execute()) {
            return true;
        } else {
            echo "Error: " . $stmt->error;
        }
    }

    function get_all_password($userId) {
        global $db;
        $query = "SELECT id, pass FROM passwords WHERE user_id = ?";
        $stmt = $db -> prepare($query);
        $stmt -> bind_param('s', $userId);
        $stmt -> execute();
        if ($stmt->execute()) {
            $data = $stmt -> get_result();
            $result = $data -> fetch_all(MYSQLI_ASSOC);
            $data->free_result();
            $stmt -> close();
            return $result;    
        } else {
            echo "Error: " . $stmt->error;
        }
    }

    function check_duplicate_password($generatePass) {
        global $db;
        $isDuplicate = false;
        $query = "SELECT pass FROM passwords ORDER BY id ASC";
        $stmt = $db->query($query);
        while($result = $stmt->fetch_assoc()) {
            if ($generatePass === $result["pass"]) {
                $isDuplicate = true;
                break;
            }
        }
        $stmt->free_result();
        return $isDuplicate;
    }
    
    function update_password($user_info) {
        global $db;
        $query = "UPDATE passwords SET pass=? WHERE id=? AND user_id=?";
        $stmt = $db -> prepare($query);
        $stmt -> bind_param('sii', $user_info["passwordValue"], $user_info["passwordID"], $user_info["id"]);
        if ($stmt->execute()) {
           return true;
        } else {
            echo "Error: " . $stmt->error;
        }
    }

    function delete_password($user_info) {
        global $db;
        $query = "DELETE FROM passwords WHERE id=? AND user_id=? LIMIT 1";
        $stmt = $db -> prepare($query);
        $stmt -> bind_param('ii', $user_info["passwordID"], $user_info["id"]);
        if ($stmt->execute()) {
            return true;
        } else {
            echo "Error: " . $stmt->error;
        }
    }

?>