<?php
// Include the header file for consistent page layout
include '../includes/header.php';

// Include the file that defines the Database class
require_once '../models/Database.php'; // Asegúrate de que la ruta sea correcta

// Check if a session is already active before starting a new one
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in
if (!isset($_SESSION['user']['id'])) { // Updated to check the correct session structure
    header("Location: login.php"); // Redirect to login if not authenticated
    exit();
}

// Establish database connection
use Models\Database;
$db = (new Database())->getConnection(); // Ensure $db is initialized before use

// Fetch all repair orders (since the table does not have a user_id column)
$query = "SELECT * FROM repairs"; // Updated query to fetch all repairs
$stmt = $db->prepare($query); // Prepare the SQL statement
$stmt->execute(); // Execute the query
$result = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all results as an associative array

// Handle form submission for repair requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $service = trim($_POST['service']); // Get the selected service type
    $details = trim($_POST['details']); // Get additional details about the repair
    $contact = trim($_POST['contact']); // Get contact information
    $preferredDate = $_POST['preferred_date']; // Get the preferred date for the repair

    // Handle image upload
    $imagePath = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/';
        $imagePath = $uploadDir . basename($_FILES['image']['name']);
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
            $errorMessage = "Failed to upload the image. Please try again.";
        }
    }

    // Insert the repair request into the database
    $query = "INSERT INTO repairs (service_type, details, contact_info, preferred_date, image_path) VALUES (:service, :details, :contact, :preferred_date, :image_path)";
    $stmt = $db->prepare($query); // Prepare the SQL statement
    $stmt->bindParam(':service', $service); // Bind the service type parameter
    $stmt->bindParam(':details', $details); // Bind the details parameter
    $stmt->bindParam(':contact', $contact); // Bind the contact information parameter
    $stmt->bindParam(':preferred_date', $preferredDate); // Bind the preferred date parameter
    $stmt->bindParam(':image_path', $imagePath); // Bind the image path parameter

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
    <form action="reparations.php" method="POST" class="reparations-form" enctype="multipart/form-data">
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
        <div class="form-group">
            <label for="image">Upload Image</label>
            <input type="file" id="image" name="image" accept="image/*">
        </div>
        <button type="submit">Request Repair</button>
    </form>
</main>

<!-- Include the footer file for consistent page layout -->
<?php include '../includes/footer.php'; ?>
