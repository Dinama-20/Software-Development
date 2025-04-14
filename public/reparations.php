<?php
include '../includes/header.php';
session_start();
require_once '../config/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM repairs WHERE user_id = ?";
$stmt = $db->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $service = trim($_POST['service']);
    $details = trim($_POST['details']);
    $contact = trim($_POST['contact']);
    $preferredDate = $_POST['preferred_date'];

    $query = "INSERT INTO repairs (service_type, details, contact_info, preferred_date) VALUES (?, ?, ?, ?)";
    $stmt = $db->prepare($query);
    $stmt->bind_param('ssss', $service, $details, $contact, $preferredDate);

    if ($stmt->execute()) {
        $successMessage = "¡Tu solicitud de reparación ha sido enviada con éxito!";
    } else {
        $errorMessage = "No se pudo enviar tu solicitud de reparación. Por favor, inténtalo de nuevo.";
    }
}
?>

<link rel="stylesheet" href="../assets/css/style.css">
<script src="../assets/js/script.js" defer></script>

<main>
    <h2>Servicios de Reparación</h2>
    <p>Elige el tipo de servicio de reparación que necesitas para tu reloj o joyería:</p>

    <?php if (isset($successMessage)): ?>
        <div class="confirmation-message">
            <p><?= htmlspecialchars($successMessage) ?></p>
        </div>
    <?php elseif (isset($errorMessage)): ?>
        <div class="error-message">
            <p><?= htmlspecialchars($errorMessage) ?></p>
        </div>
    <?php endif; ?>

    <form action="reparations.php" method="POST" class="reparations-form">
        <div class="form-group">
            <label for="service">Seleccionar Servicio</label>
            <select id="service" name="service" required>
                <option value="watch-repair">Reparación de Reloj</option>
                <option value="jewelry-repair">Reparación de Joyería</option>
                <option value="battery-replacement">Cambio de Batería</option>
            </select>
        </div>
        <div class="form-group">
            <label for="details">Detalles Adicionales</label>
            <textarea id="details" name="details" rows="4" placeholder="Describe el problema..." required></textarea>
        </div>
        <div class="form-group">
            <label for="contact">Información de Contacto</label>
            <input type="text" id="contact" name="contact" placeholder="Ingresa tu teléfono o correo" required>
        </div>
        <div class="form-group">
            <label for="preferred_date">Fecha Preferida</label>
            <input type="date" id="preferred_date" name="preferred_date" required>
        </div>
        <button type="submit">Solicitar Reparación</button>
    </form>
</main>

<?php include '../includes/footer.php'; ?>
