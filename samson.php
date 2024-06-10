<?php
// Configuration
$db_host = 'localhost';
$db_username = 'root';
$db_password = '';
$db_name = 'registration';

// Create connection
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: ". $conn->connect_error);
}

// Create table if it doesn't exist
$sql = "CREATE TABLE IF NOT EXISTS mytable (
    id INT AUTO_INCREMENT,
    name VARCHAR(255),
    email VARCHAR(255),
    password VARCHAR(255),
    PRIMARY KEY (id)
)";
$conn->query($sql);

// Display data in table
$sql = "SELECT * FROM mytable";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Name</th><th>Email</th><th>Actions</th></tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>". $row["id"]. "</td>";
        echo "<td>". $row["name"]. "</td>";
        echo "<td>". $row["email"]. "</td>";
        echo "<td><a href='?action=delete&id=". $row["id"]. "'>Delete</a> | <a href='?action=update&id=". $row["id"]. "'>Update</a></td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "No data found";
}

// Add new data
if (isset($_POST["add"])) {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    $sql = "INSERT INTO mytable (name, email, password) VALUES ('$name', '$email', '$hashed_password')";
    $conn->query($sql);
    header("Location: ". $_SERVER["PHP_SELF"]);
    exit;
}

// Update data
if (isset($_POST["update"])) {
    $id = $_POST["id"];
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    $sql = "UPDATE mytable SET name='$name', email='$email', password='$hashed_password' WHERE id=$id";
    $conn->query($sql);
    header("Location: ". $_SERVER["PHP_SELF"]);
    exit;
}

// Delete data
if (isset($_GET["action"]) && $_GET["action"] == "delete") {
    $id = $_GET["id"];
    $sql = "DELETE FROM mytable WHERE id=$id";
    $conn->query($sql);
    header("Location: ". $_SERVER["PHP_SELF"]);
    exit;
}

// Update form
if (isset($_GET["action"]) && $_GET["action"] == "update") {
    $id = $_GET["id"];
    $sql = "SELECT * FROM mytable WHERE id=$id";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
   ?>
    <form action="<?php echo $_SERVER["PHP_SELF"];?>" method="post">
        <input type="hidden" name="id" value="<?php echo $id;?>">
        <label for="name">Name:</label>
        <input type="text" name="name" value="<?php echo $row["name"];?>"><br><br>
        <label for="email">Email:</label>
        <input type="email" name="email" value="<?php echo $row["email"];?>"><br><br>
        <label for="password">Password:</label>
        <input type="password" name="password"><br><br>
        <input type="submit" name="update" value="Update">
    </form>
    <?php
}

// Add new form
?>
<form action="<?php echo $_SERVER["PHP_SELF"];?>" method="post">
    <label for="name">Name:</label>
    <input type="text" name="name"><br><br>
    <label for="email">Email:</label>
    <input type="email" name="email"><br><br>
    <label for="password">Password:</label>
    <input type="password" name="password"><br><br>
    <input type="submit" name="add" value="Add">
</form>