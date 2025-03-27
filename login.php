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
function checkUser($conn,$email,$pass,$role) {
    // Prepare statement to prevent SQL injection
    if($role=="student"){
        $stmt = $conn->prepare("SELECT * from student WHERE (`email`=? AND `password`=?) ");
    }else if($role=="landlord"){
        $stmt = $conn->prepare("SELECT * from landlord WHERE (`email`=? AND `password`=?) ");
    }
     
    $stmt->bind_param("ss", $email,$pass);
    $stmt->execute();
    $result = $stmt->get_result();
    $row=mysqli_fetch_array($result,MYSQLI_ASSOC);
    // Check if user exists
    if ($result->num_rows> 0) {
        session_start();
      
        $_SESSION["name"]=$row["name"];
        $_SESSION["email"]=$row['email'];
        $_SESSION["role"]=$role;
        header("Location:APP.php");
    } else {
        header("Location:login_form.php?error=Incorrect email or password ! ");
    }
}

// Example usage
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST["login"])){
      
        
        if((empty($_POST["email"]) or (empty($_POST["role"])) or empty($_POST["password"]))){
           
            header("Location:login_form.php?error=Please fill in all fields !");
        }else{
            $email = $_POST['email'];
            $password = $_POST['password'];
            $role=$_POST["role"];
            // Call the function to check user
            checkUser($conn,$email,$password,$role);
            
        }
        
        
    }else{
        echo "Wrong method!";
    }
}

// Close the connection
$conn->close();

