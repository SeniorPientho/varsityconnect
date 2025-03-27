<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "campusconnect";

// Creating connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Checking connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// Function to check user credentials
function checkUser($conn,$email) {
    // Prepare statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM student WHERE (`email`=?)");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
   
    // Check if user exists
    if ($result->num_rows> 0) {
        return false;
    } else {

        return true;
    }
}

// Example usage
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST["signup"])){
        $name = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $id=$_POST['id'];
        $phone=$_POST['phoneno'];
        // Call the function to check user
        if(checkUser($conn,$email)){
            $stmt = $conn->prepare("INSERT INTO student (`name`,`email`,`password`,`studentnid`,`phoneno`) VALUES (?,?,?,?,?)");
            $stmt->bind_param("sssss", $name, $email,$password,$id,$phone);
            if($stmt->execute()){
                header("Location:login_form.php");
            } else{
                $message = "REGISTRATION NOT SUCCESFULL";
            }
        
        }else{
            $message="USER ALREADY EXISTS";
        }
        echo $message;
    }else{
        echo "Wrong method!";
    }
}

// Close the connection
$conn->close();

?>