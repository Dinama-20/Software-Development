<?php
// Include the header file for consistent page layout
include '../includes/header.php';

// Start the session only if it is not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include the configuration file to get the database connection
require_once '../config/config.php';

// Establish database connection
use Models\Database;
$db = (new Database())->getConnection();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not authenticated
    exit();
}

// Fetch repair orders associated with the logged-in user
$user_id = $_SESSION['user_id']; // Get the logged-in user's ID
$query = "SELECT * FROM reparations WHERE user_id = ?"; // SQL query to fetch reparations
$stmt = $db->prepare($query); // Prepare the SQL statement
$stmt->bind_param('i', $user_id); // Bind the user ID parameter
$stmt->execute(); // Execute the query
$result = $stmt->get_result(); // Get the result of the query

// Handle form submission for repair requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $service = trim($_POST['service']); // Get the selected service type
    $details = trim($_POST['details']); // Get additional details about the repair
    $contact = trim($_POST['contact']); // Get contact information
    $preferredDate = $_POST['preferred_date']; // Get the preferred date for the repair

    // Insert the repair request into the database
    $query = "INSERT INTO repairs (service_type, details, contact_info, preferred_date) VALUES (:service, :details, :contact, :preferred_date)";
    $stmt = $db->prepare($query); // Prepare the SQL statement
    $stmt->bindParam(':service', $service); // Bind the service type parameter
    $stmt->bindParam(':details', $details); // Bind the details parameter
    $stmt->bindParam(':contact', $contact); // Bind the contact information parameter
    $stmt->bindParam(':preferred_date', $preferredDate); // Bind the preferred date parameter

    // Execute the query and check for success
    if ($stmt->execute()) {
        $successMessage = "Your repair request has been submitted successfully!";
    } else {
        $errorMessage = "Failed to submit your repair request. Please try again.";
    }
}
?>

<!-- Include external CSS and JavaScript files -->
<link rel="stylesheet" href="../assets/css/style.css">
<script src="../assets/js/script.js" defer></script>

<main>
    <!-- Page heading -->
    <h2>Repair Services</h2>
    <p>Choose the type of repair service you need for your watch or jewelry:</p>

    <!-- Display success or error messages -->
    <?php if (isset($successMessage)): ?>
        <div class="confirmation-message">
            <p><?= htmlspecialchars($successMessage) ?></p>
        </div>
    <?php elseif (isset($errorMessage)): ?>
        <div class="error-message">
            <p><?= htmlspecialchars($errorMessage) ?></p>
        </div>
    <?php endif; ?>

    <!-- Form for submitting repair requests -->
    <form action="reparations.php" method="POST" class="reparations-form">
        <div class="form-group">
            <label for="service">Select Service</label>
            <select id="service" name="service" required>
                <option value="watch-repair">Watch Repair</option>
                <option value="jewelry-repair">Jewelry Repair</option>
                <option value="battery-replacement">Battery Replacement</option>
            </select>
        </div>
        <div class="form-group">
            <label for="details">Additional Details</label>
            <textarea id="details" name="details" rows="4" placeholder="Describe the issue..." required></textarea>
        </div>
        <div class="form-group">
            <label for="contact">Contact Information</label>
            <input type="text" id="contact" name="contact" placeholder="Enter your phone or email" required>
        </div>
        <div class="form-group">
            <label for="preferred_date">Preferred Date</label>
            <input type="date" id="preferred_date" name="preferred_date" required>
        </div>
        <button type="submit">Request Repair</button>
    </form>
</main>

<!-- Include the footer file for consistent page layout -->
<?php include '../includes/footer.php'; ?>
