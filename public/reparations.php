<?php
include '../includes/header.php';
require_once '../models/database.php';

use Models\Database;

$db = (new Database())->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $service = trim($_POST['service']);
    $details = trim($_POST['details']);
    $contact = trim($_POST['contact']);
    $preferredDate = $_POST['preferred_date'];

    // Insert the repair request into the database
    $query = "INSERT INTO repairs (service_type, details, contact_info, preferred_date) VALUES (:service, :details, :contact, :preferred_date)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':service', $service);
    $stmt->bindParam(':details', $details);
    $stmt->bindParam(':contact', $contact);
    $stmt->bindParam(':preferred_date', $preferredDate);

    if ($stmt->execute()) {
        $successMessage = "Your repair request has been submitted successfully!";
    } else {
        $errorMessage = "Failed to submit your repair request. Please try again.";
    }
}
?>

<link rel="stylesheet" href="../assets/css/style.css">
<script src="../assets/js/script.js" defer></script>

<main>
    <h2>Repair Services</h2>
    <p>Choose the type of repair service you need for your watch or jewelry:</p>

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
            <label for="service">Select Service</label>
        </div>
        <button type="submit">Request Repair</button>
    </form>
</main>

<?php include '../includes/footer.php'; ?>