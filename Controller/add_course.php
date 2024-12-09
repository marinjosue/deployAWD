<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "courses";
$port = "3306";

$conn = new mysqli($servername, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_course = $_POST['id_course'];
    $name = $_POST['courseName'];
    $description = $_POST['courseDescription'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $price = $_POST['price'];
    $cedula = $_POST['cedula'];
    $id_category = $_POST['id_category'];
    $status = $_POST['status'];
    $youtube = $_POST['course_youtube'];
    $user_id = 1; // ID de ejemplo; ajusta según corresponda.

    if ($id_course) {
        $sql = "UPDATE courses SET course_name=?, course_description=?, start_date=?, end_date=?, price=?, cedula=?, id_category=?, status=?, course_youtube=?, user_id=? WHERE id_course=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssissssii", $name, $description, $start_date, $end_date, $price, $cedula, $id_category, $status, $youtube, $user_id, $id_course);
    } else {
        $sql = "INSERT INTO courses (course_name, course_description, start_date, end_date, price, cedula, id_category, status, course_youtube, user_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssissssi", $name, $description, $start_date, $end_date, $price, $cedula, $id_category, $status, $youtube, $user_id);
    }

    if ($stmt->execute()) {
        $course_id = $id_course ? $id_course : $stmt->insert_id;

        if ($id_course) {
            $conn->query("DELETE FROM course_units WHERE id_course = $course_id");
        }

        $unitTitles = $_POST['unitTitles'];
        $unitContents = $_POST['unitContents'];
        $unitSQL = "INSERT INTO course_units (id_course, unit_title, unit_content) VALUES (?, ?, ?)";
        $unitStmt = $conn->prepare($unitSQL);

        foreach ($unitTitles as $index => $title) {
            $content = $unitContents[$index];
            $unitStmt->bind_param("iss", $course_id, $title, $content);
            $unitStmt->execute();
        }

        echo "Curso guardado exitosamente.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
