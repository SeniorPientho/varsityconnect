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
function checkHostel($conn,$email,$hostelid) {
    // Prepare statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM listings WHERE (`contact`=? AND `hostelid`=?)");
    $stmt->bind_param("ss", $email,$hostelid);
    $stmt->execute();
    $result = $stmt->get_result();
   
    // Check if hostel exists
    if ($result->num_rows> 0) {
        return false;
    } else {

        return true;
    }
}

// Example usage
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST["addListings"])){

        $ammenities=[];
        $hostelid=$_POST["hostelid"];
        $title=$_POST["title"];
        $type=$_POST["type"];
        $price=$_POST["price"];
        $bedrooms=$_POST["bedRooms"];
        $location=$_POST["location"];
        $distance=$_POST["distance"];
        $amenities=explode(",",$_POST["amenities"]);
        $description=$_POST["description"];
        $contact=$_POST["contact"];
        $color=$_POST["color"];

        // Call the function to check user
        if(checkHostel($conn,$contact,$hostelid)){
            $stmt = $conn->prepare("INSERT INTO `listings` (`hosteltype`, `hosteltitle`, `bedrooms`, `location`, `hostelid`, `price`, `distance`, `description`, `contact`, `colour`) VALUES (?, ?, ?, ?, ?, ?,?,?,?,?)");
            $stmt->bind_param("ssssssssss", $type,$title,$bedrooms,$location,$hostelid,$price,$distance,$description,$contact,$color);
            if($stmt->execute()){
                session_start();
                header("Location:./APP.php?message=Hostel added successful");
            } else{
                $message = "REGISTRATION NOT SUCCESFULL";
            }
        
        }else{

         
            header("Location:./APP.php?error=Hostel already exists");
        }
        echo $message;
    }else{
        echo "Wrong method!";
    }
}

// Close the connection
$conn->close();

?>
?>