<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$nombre = $direccion = $edad = "";
$nombre_err = $direccion_err = $edad_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate nombre
    $input_nombre = trim($_POST["nombre"]);
    if(empty($input_nombre)){
        $nombre_err = "Please enter a nombre.";
    } elseif(!filter_var($input_nombre, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $nombre_err = "Please enter a valid nombre.";
    } else{
        $nombre = $input_nombre;
    }
    
    // Validate direccion
    $input_direccion = trim($_POST["direccion"]);
    if(empty($input_direccion)){
        $direccion_err = "Please enter an direccion.";     
    } else{
        $direccion = $input_direccion;
    }
    
    // Validate edad
    $input_edad = trim($_POST["edad"]);
    if(empty($input_edad)){
        $edad_err = "Please enter the edad amount.";     
    } elseif(!ctype_digit($input_edad)){
        $edad_err = "Please enter a positive integer value.";
    } else{
        $edad = $input_edad;
    }
    
    // Check input errors before inserting in database
    if(empty($nombre_err) && empty($direccion_err) && empty($edad_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO empleados (nombre, direccion, edad) VALUES (?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sss", $param_nombre, $param_direccion, $param_edad);
            
            // Set parameters
            $param_nombre = $nombre;
            $param_direccion = $direccion;
            $param_edad = $edad;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper{
            width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">Create Record</h2>
                    <p>Please fill this form and submit to add employee record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>nombre</label>
                            <input type="text" name="nombre" class="form-control <?php echo (!empty($nombre_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $nombre; ?>">
                            <span class="invalid-feedback"><?php echo $name_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>direccion</label>
                            <textarea name="direccion" class="form-control <?php echo (!empty($direccion_err)) ? 'is-invalid' : ''; ?>"><?php echo $direccion; ?></textarea>
                            <span class="invalid-feedback"><?php echo $direccion_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>edad</label>
                            <input type="text" name="edad" class="form-control <?php echo (!empty($edad_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $edad; ?>">
                            <span class="invalid-feedback"><?php echo $salary_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>