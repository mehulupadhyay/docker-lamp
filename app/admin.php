<?php
    session_start();
    require_once "config.php";
    if(!isset($_SESSION['loggedin']) && !isset($_SESSION['admin'])) {
        header('LOCATION:index.php'); die();
    }   
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.js"></script>
    <style type="text/css">
        .wrapper{
            width: 650px;
            margin: 0 auto;
        }
        .page-header h2{
            margin-top: 0;
        }
        table tr td:last-child a{
            margin-right: 15px;
        }
    </style>
    <script type="text/javascript">
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();   
        });
    </script>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header clearfix">
                        <h2 class="pull-left">Users</h2>
                        <a href="register.php" class="btn btn-success pull-right">Add New User</a>
                    </div>
                    <?php
                    // Include config file
                    require_once "config.php";
                    
                    // Attempt select query execution
                    $sql = "SELECT * FROM users";
                    
                    if($result = $mysqli->query($sql)){
                        if($result->num_rows > 0){
                            echo "<table class='table table-bordered table-striped'>";
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>username</th>";
                                        echo "<th>project</th>";
                                        echo "<th>Admin</th>";
                                        echo "<th>Tools</th>";                                        
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = $result->fetch_array()){
                                    echo "<tr>";
                                        echo "<td>" . $row['username'] . "</td>";
                                        //echo "<td>" . $row['project'] . "</td>";
                                        $sql2 = "SELECT * FROM projects WHERE pid=" . $row['project'];
                                        $result2 = $mysqli->query($sql2);
                                        
                                        $row2 = $result2->fetch_assoc();
                                        
                                        echo "<td>" . $row2['ptitle'] . "</td>";
                                        
                                        if($row['admin'] == 1)
                                            echo "<td><input id=\"cAdmin_".$row['uid']."\" type=\"checkbox\" checked disabled></input></td>";
                                        else
                                            echo "<td><input id=\"cAdmin_".$row['uid']."\" type=\"checkbox\" disabled></input></td>";                                        
                                        echo "<td>";
                                            echo "<a href='read.php?id=". $row['uid'] ."' title='View Record' data-toggle='tooltip'><span class='glyphicon glyphicon-eye-open'></span></a>";
                                            echo "<a href='update.php?id=". $row['uid'] ."' title='Update Record' data-toggle='tooltip'><span class='glyphicon glyphicon-pencil'></span></a>";
                                            echo "<a href='delete_user.php?id=". $row['uid'] ."' title='Delete Record' data-toggle='tooltip'><span class='glyphicon glyphicon-trash'></span></a>";
                                        echo "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";                            
                            echo "</table>";
                            // Free result set
                            $result->free();
                        } else{
                            echo "<p class='lead'><em>No records were found.</em></p>";
                        }
                    } else{
                        echo "ERROR: Could not able to execute $sql. " . $mysqli->error;
                    }
                    
                    // Close connection
                    $mysqli->close();
                    ?>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>