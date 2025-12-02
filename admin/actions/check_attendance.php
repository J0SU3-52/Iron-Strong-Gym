<?php
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$pin = isset($data['pin']) ? $data['pin'] : '';

if (empty($pin)) {
    echo json_encode(['status' => 'no_valido']);
    exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "iron";
$port = 3306;

$conn = new mysqli($servername, $username, $password, $dbname, $port);

$conn->options(MYSQLI_OPT_CONNECT_TIMEOUT, 10);

if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Connection failed: ' . $conn->connect_error]);
    exit;
}

$conn->query("SET SESSION wait_timeout = 54000;");
$conn->query("SET SESSION interactive_timeout = 54000;");

// Verificar el PIN y obtener el estado del plan
$sql = "SELECT user_id, fullname, status, paid_date, plan FROM members WHERE pin = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(['status' => 'error', 'message' => 'Prepare statement failed: ' . $conn->error]);
    $conn->close();
    exit;
}

$stmt->bind_param('s', $pin);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $currentDate = new DateTime();
    $paidDate = new DateTime($row['paid_date']);

    // Obtener la duración del plan y calcular la fecha de vencimiento
    $planDuration = (int)$row['plan']; // Plan en meses (1, 2, 3, 6, 12)
    $expiryDate = clone $paidDate;
    $expiryDate->modify("+$planDuration months");

    if ($row['status'] == 'Active' && $currentDate <= $expiryDate) {
        // Registrar asistencia
        $insertSql = "INSERT INTO attendance (member_id, attendance_date) VALUES (?, NOW())";
        $insertStmt = $conn->prepare($insertSql);
        $insertStmt->bind_param('i', $row['user_id']);
        $insertStmt->execute();

        if ($insertStmt->affected_rows > 0) {
            echo json_encode(['status' => 'activo', 'fullname' => $row['fullname']]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to record attendance']);
        }
        $insertStmt->close();
    } else {
        // Actualizar el estado del plan a vencido
        $updateSql = "UPDATE members SET status = 'Expired' WHERE user_id = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param('i', $row['user_id']);
        $updateStmt->execute();

        echo json_encode(['status' => 'vencido', 'fullname' => $row['fullname']]);
        $updateStmt->close();
    }
} else {
    echo json_encode(['status' => 'no_valido']);
}

$stmt->close();
$conn->close();
