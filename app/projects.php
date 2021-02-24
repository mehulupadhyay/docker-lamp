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
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
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

<?php     
    //Fetch streams that this user has access to                                                         
    $result = $mysqli->query("SELECT projects.pid, projects.ptitle, streams.sid, streams.title, streams.url FROM ec_test.projects, ec_test.streams WHERE streams.project = projects.pid");
    
    //Initialize array variable
    $dbdata = array();
    
    //Fetch into associative array
    while ( $row = $result->fetch_assoc())  {
        $dbdata[]=$row;
    }
    
    $result = $mysqli->query("SELECT * FROM projects");
    
    //Initialize array variable
    $dbdata2 = array();
    
    //Fetch into associative array
    while ( $row = $result->fetch_assoc())  {
        $dbdata2[]=$row;
    }     
?> 

    <script type="text/javascript">
        $(document).ready(function(){
            $( function() {
                $( "#s1, #s2" ).sortable({connectWith:".sortable"}).disableSelection();
            });   
        });
    
    var oStreams = <?=json_encode($dbdata, JSON_UNESCAPED_SLASHES)?>;
    var oProjects = <?=json_encode($dbdata2, JSON_UNESCAPED_SLASHES)?>;
    
    $( function() {
        $( "#p1, #p2" ).sortable({connectWith:".sortable"}).disableSelection();
    });

    </script>
</head>
<body>
    <div class="wrapper-wide">
    <script>
    
//    debugger;

    
    </script>

<div class="page-header clearfix">
<h2 class="pull-left">Streams</h2>
<a class="btn btn-success pull-right">Add New Stream</a>
    </div>

<div class="col-md-12">
<table class='table table-bordered table-striped'>
<thead>
    <tr>
<th>Alpha</th><th>URL</th><th></th>
    </tr>
</thead>
<tbody id="p1" class="sortable">
<tr><td>PGM1 (HQ)</td><td>ws://test.url</td><td><a href="" title="Delete Stream" data-toggle='tooltip'><span class='glyphicon glyphicon-trash'></span></a><a title='Copy Stream' data-toggle='tooltip'><span class='glyphicon glyphicon-plus'></span></a><a title="Move" data-toggle="tooltip"><span class='glyphicon glyphicon glyphicon-menu-hamburger'></span></a></td></tr>
<tr><td>PGM2 (HQ)</td><td>ws://test.url</td><td><a href="" title="Delete Stream" data-toggle='tooltip'><span class='glyphicon glyphicon-trash'></span></a><a title='Copy Stream' data-toggle='tooltip'><span class='glyphicon glyphicon-plus'></span></a><a title="Move" data-toggle="tooltip"><span class='glyphicon glyphicon glyphicon-menu-hamburger'></span></a></td></tr>
<tr><td>PGM3 (HQ)</td><td>ws://test.url</td><td><a href="" title="Delete Stream" data-toggle='tooltip'><span class='glyphicon glyphicon-trash'></span></a><a title='Copy Stream' data-toggle='tooltip'><span class='glyphicon glyphicon-plus'></span></a><a title="Move" data-toggle="tooltip"><span class='glyphicon glyphicon glyphicon-menu-hamburger'></span></a></td></tr>
<tr><td>PGM4 (HQ)</td><td>ws://test.url</td><td><a href="" title="Delete Stream" data-toggle='tooltip'><span class='glyphicon glyphicon-trash'></span></a><a title='Copy Stream' data-toggle='tooltip'><span class='glyphicon glyphicon-plus'></span></a><a title="Move" data-toggle="tooltip"><span class='glyphicon glyphicon glyphicon-menu-hamburger'></span></a></td></tr>
</tbody>


</table>

<table class='table table-bordered table-striped'>
<thead>
    <tr>
<th>Bravo</th><th>URL</th><th></th>
    </tr>
</thead>
<tbody id="p2" class="sortable">
<tr><td>PGMA (HQ)</td><td>ws://test.url</td><td><a href="" title="Delete Stream" data-toggle='tooltip'><span class='glyphicon glyphicon-trash'></span></a><a title='Copy Stream' data-toggle='tooltip'><span class='glyphicon glyphicon-plus'></span></a><a title="Move" data-toggle="tooltip"><span class='glyphicon glyphicon glyphicon-menu-hamburger'></span></a></td></tr>
<tr><td>PGMB (HQ)</td><td>ws://test.url</td><td><a href="" title="Delete Stream" data-toggle='tooltip'><span class='glyphicon glyphicon-trash'></span></a><a title='Copy Stream' data-toggle='tooltip'><span class='glyphicon glyphicon-plus'></span></a><a title="Move" data-toggle="tooltip"><span class='glyphicon glyphicon glyphicon-menu-hamburger'></span></a></td></tr>
<tr><td>PGMC (HQ)</td><td>ws://test.url</td><td><a href="" title="Delete Stream" data-toggle='tooltip'><span class='glyphicon glyphicon-trash'></span></a><a title='Copy Stream' data-toggle='tooltip'><span class='glyphicon glyphicon-plus'></span></a><a title="Move" data-toggle="tooltip"><span class='glyphicon glyphicon glyphicon-menu-hamburger'></span></a></td></tr>
<tr><td>PGMD (HQ)</td><td>ws://test.url</td><td><a href="" title="Delete Stream" data-toggle='tooltip'><span class='glyphicon glyphicon-trash'></span></a><a title='Copy Stream' data-toggle='tooltip'><span class='glyphicon glyphicon-plus'></span></a><a title="Move" data-toggle="tooltip"><span class='glyphicon glyphicon glyphicon-menu-hamburger'></span></a></td></tr>
</tbody>


</table>

    </div>

    </div>

<?php
    $mysqli->close();
?>
</body>
</html>
