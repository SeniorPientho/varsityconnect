<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <style>
        body { 
            font-family: Arial, sans-serif;
            background: url("logindispaly.jpg") no-repeat ;
            background-position: center;
            background-size: cover; 
            display: flex;
            height: 100vh;
            justify-content: center;
            align-items: center;
            

        }
        .container { 
            max-width: 800px;
            margin: auto;
            padding: 20px; 
            border: 10px rgb hsl(0, 0,0); 
            border-radius: 5px;
            background-color: rgba(250, 250, 250,0.4);
            backdrop-filter: blur(2px);
            box-shadow: 0 2px 4px 2px rgb(50, 50, 50);
         }
        input[type="email"],
        input[type="password"],
        input[type="text"],
        select{
            width: calc(80%);
            padding: 10px;
            margin: 10px 0;
            border-radius: 20px; 
            box-shadow: 0px,2px,4px,2px,rgba(0,0,0,.2);
        }

        .eye{
            width:20px; padding: 10px; 
            margin: 10px 0;
        }
        .pass{
            display: flex;
            justify-content: flex-start;
            align-items: center;
            width: calc(80%+20);
            
        }
       .error
       {
        width: 50%; 
        padding: 10px;
        display:none;
        background-color: rgb(250, 14, 15);
        color: rgb(250, 250, 250);
        border: 10px;
        border-radius:10px ;
        margin :5px 5px
         }
   

        .login { width: 50%; padding: 10px; background-color: #12dd60; color: rgb(15, 14, 15); border: 10px;border-radius:10px ; }
    </style>
</head>
<body>
    <div class="container" id="login">
        <h2>         Login to your CampusConnect account</h2>
        <form action="login.php" method="POST">
            <div class="error" id="error"><?php if(isset($_GET["error"])){
                 echo $_GET["error"]; 
                ?>
                <script>
                    errorDiv=document.getElementById("error");
                    errorDiv.style.display='inline';
                    setTimeout(()=>{
                        errorDiv.style.display='none';
                    },4000);
                </script>
                <?php }
                ?>
              </div>
            <input type="email" name="email" placeholder="Enter Your Email" required>
            <select name="role" id="" >
                <option value="">--select role--</option>
                <option value="student">student</option>
                <option value="landlord">landlord</option>
            </select>
            <p class="pass"><input type="password" name="password" id="password" placeholder="Enter Your Password" required>
                <img class="eye" id="eye" src="./show_password.png"/></p>
            <input name="login" class="login" type="submit" value="login">
        </form>
        <p>Don't have an account? <a href="registration.html">Register here</a></p>
    </div>
    <script>
        errorDiv=document.getElementById("error");
        eye=document.getElementById("eye");
        password=document.getElementById("password");

        eye.addEventListener("click",()=>{    
        type=document.getElementById("password").type;
            if(type=="password"){
                password.type='text';
                eye.src="./hidden_password.png";

            }else if(type=="text"){
                password.type='password';
                eye.src="./show_password.png";
            }
           
        })
    </script>
</body>
</html>