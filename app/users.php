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
    <link rel="stylesheet" href="./style-admin.css">
    <style type="text/css">
        .page-header h2{
            margin-top: 0;
        }
        table tr td:last-child a{
            margin-right: 15px;
        }
    </style>
    

    <script type="text/javascript">
        $(document).ready(function(){
            var ecdata = ecdata || {};
            
            $('[data-toggle="tooltip"]').tooltip();   
                $('select').on('change', function() {                    
                    console.log("User: " + $(this).attr('user') + " Val: " + this.value );
                    $.ajax({
                        type: "POST",
                        url: 'update.php',
                        data: {update:'users',uid:$(this).attr('user'),project:this.value},
                            success: function(data){
                            //console.log(data);
                        },
                        error: function(xhr, status, error){
                            //console.error(xhr);
                        }
                    });
                });
        });
    </script>
    
    <?php     
      //Fetch projects that this user has access to                                                         
      $result = $mysqli->query("SELECT * FROM projects");
      
      //Initialize array variable
      $dbdata = array();
      
      //Fetch into associative array
      while ( $row = $result->fetch_assoc())  {
          $dbdata[]=$row;
      }
   
    ?>   
  
  
    <script>
        var oProjects = <?=json_encode($dbdata, JSON_UNESCAPED_SLASHES)?>;     
    </script>   



</head>
<body>
    <div class="wrapper-wide">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header clearfix">
                        <h2 class="pull-left">Users</h2>
                        <a href="new.php" class="btn btn-success pull-right">Add New User</a>
                    </div>
                    <?php

                    
                    // Attempt select query execution
                    $sql = "SELECT * FROM users";
                    
                    if($result = $mysqli->query($sql)){
                        if($result->num_rows > 0){
                            echo "<table class='table table-bordered table-striped'>";
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>Username</th>";
                                        echo "<th>Project</th>";
                                        echo "<th class=\"small-header\">Manager</th>";
                                        echo "<th class=\"small-header\">Admin</th>";
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
                                        
                                        //echo "<td>" . $row2['ptitle'] . "</td>";
                                        
                                        echo '<td><select name="projectdd" user="'. $row['uid'] .'">';
                                        foreach($dbdata as $project) {
                                            if($project['pid'] == $row2["pid"]) { 
                                                echo '<option user="'. $row['uid'] .'" value="' . $project["pid"] . '" selected>' . $project['ptitle'] . '</option>';
                                            }
                                            else {
                                                echo '<option user="'. $row['uid'] .'" value="' . $project["pid"] . '">' . $project['ptitle'] . '</option>';
                                            }

                                        }
                                        echo '</select></td>';
                                        
                                        if($row['manager'] == 1)
                                            echo "<td><input class=\"centered\" id=\"cManager_".$row['uid']."\" type=\"checkbox\" checked disabled></input></td>";
                                        else
                                            echo "<td><input class=\"centered\" id=\"cManager_".$row['uid']."\" type=\"checkbox\" disabled></input></td>";
                                            
                                        if($row['admin'] == 1)
                                            echo "<td><input class=\"centered\" id=\"cAdmin_".$row['uid']."\" type=\"checkbox\" checked disabled></input></td>";
                                        else
                                            echo "<td><input class=\"centered\" id=\"cAdmin_".$row['uid']."\" type=\"checkbox\" disabled></input></td>";                                        
                                        echo "<td>";
                                            echo "<a href='delete_user.php?id=". $row['uid'] ."' title='Delete Record' data-toggle='tooltip'><span class='glyphicon glyphicon-trash'></span></a>";
                                        echo "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";                            
                            echo "</table>";
                            echo "<a href=\"./producer.php\">Back to Streams</a>";
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