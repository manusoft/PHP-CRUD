<?php
$insert = false;
$update = false;
$delete = false;
$title = "";
$note = "";

// Database connection
$host = "localhost";
$user = "root";
$pass = "";
$db = "notedb";

// Create connection
$conn = mysqli_connect($host, $user, $pass, $db);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['idEdit'])) {
        $titleEdit = $_POST['titleEdit'];
        $noteEdit = $_POST['noteEdit'];
        $sql = "UPDATE `note` SET `title` = '$titleEdit', `note` = '$noteEdit' WHERE `id` = " . $_POST['idEdit'];
        if (mysqli_query($conn, $sql)) {
            $update = true;
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            $update = false;
        }
    } else {
        $title = $_POST['title'];
        $note = $_POST['note'];

        if ($title != "" && $note != "") {
            $sql = "INSERT INTO `note` (`title`, `note`) VALUES ('$title', '$note')";
            if (mysqli_query($conn, $sql)) {
                $insert = true;
            } else {
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            }
        } else {
            $insert = false;
        }
    }
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM `note` WHERE `id` = $id";
    if (mysqli_query($conn, $sql)) {
        $delete = true;
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        $delete = false;
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="http://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">


    <title>PHP - CRUD Operations</title>
</head>

<body>
    <!-- Edit modal -->
    <!--<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal">
        Edit Model
    </button>-->

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="/crud/index.php?" method="POST" class="needs-validation">
                        <input type="hidden" name="idEdit" id="idEdit">
                        <div class="mb-3 my-4">
                            <label for="titleEdit" class="form-label">Note Title</label>
                            <input type="text" class="form-control" id="titleEdit" name="titleEdit" placeholder="Title">
                        </div>
                        <div class="mb-3">
                            <label for="noteEdit" class="form-label">Note Description</label>
                            <textarea class="form-control" id="noteEdit" name="noteEdit" rows="3" placeholder="Description"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">PHP CRUD</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Contact</a>
                    </li>
                </ul>
                <form class="d-flex">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container">
        <h2 class="my-4">Add Note</h2>
        <form action="/crud/index.php" method="POST" class=" class="needs-validation>
            <div class="mb-3 my-4">
                <label for="title" class="form-label">Note Title</label>
                <input type="text" class="form-control" id="title" name="title" placeholder="Title">
            </div>
            <div class="mb-3">
                <label for="note" class="form-label">Note Description</label>
                <textarea class="form-control" id="note" name="note" rows="3" placeholder="Description"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Add Note</button>
        </form>

        <?php
        if ($insert) {
            echo "<div class='alert alert-success alert-dismissible fade show my-5' role='alert'>
            <strong>Success!</strong> Your note added successfully!.
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>";
        }
        if ($update) {
            echo "<div class='alert alert-info alert-dismissible fade show my-5' role='alert'>
            <strong>Updated!</strong> Your note updated successfully!.
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>";
        }
        if ($delete) {
            echo "<div class='alert alert-danger alert-dismissible fade show my-5' role='alert'>
            <strong>Deleted!</strong> Your note deleted successfully!.
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>";
        }
        ?>

        <div class="mb-3 my-5">
            <table class="table" id="myTable">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Title</th>
                        <th scope="col">Notes</th>
                        <th scope="col">Date</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT * FROM note";
                    $result = mysqli_query($conn, $sql);
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>
                    <th scope='row'>" . $row['id'] . "</th> 
                    <td>" . $row['title'] . "</td>
                    <td>" . $row['note'] . "</td>
                    <td>" . $row['tstamp'] . "</td>
                    <td>" . "<a href='#' class='edit btn btn-primary'>Edit</a>" . " " . "<a href='#' class='delete btn btn-danger'>Delete</a></td> 
                    </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    -->
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="https:/cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js" type="text/javascript"></script>

    <script>
        $(document).ready(function() {
            $('#myTable').DataTable();
        });

        $(document).ready(function() {
            document.querySelectorAll('.edit').forEach(function(element) {
                element.addEventListener('click', function(e) {
                    var id = e.target.parentElement.parentElement.children[0].innerHTML;
                    var title = e.target.parentElement.parentElement.children[1].innerHTML;
                    var note = e.target.parentElement.parentElement.children[2].innerHTML;
                    idEdit.value = id;
                    titleEdit.value = title;
                    noteEdit.value = note;
                    $('#editModal').modal('toggle');
                    // window.location.href = "/crud/index.php?edit=" + e.target.parentElement.parentElement.children[0].innerHTML;
                });
            });
        });

        $(document).ready(function() {
            document.querySelectorAll('.delete').forEach(function(element) {
                element.addEventListener('click', function(e) {
                    if (confirm("Are you sure you want to delete this note?")) {
                        var id = e.target.parentElement.parentElement.children[0].innerHTML;
                        window.location.href = "/crud/index.php?delete=" + id;
                    }

                });
            });
        });
    </script>
</body>

</html>