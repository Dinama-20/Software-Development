<?php include '../includes/header.php'; ?>
<link rel="stylesheet" href="../assets/css/style.css">
<script src="../assets/js/script.js" defer></script>

<main>
    <h2>Repair Services</h2>
    <p>Choose the type of repair service you need for your watch or jewelry:</p>

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

    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
        <div class="confirmation-message">
            <p>Thank you for your request! We will contact you soon to confirm the details.</p>
        </div>
    <?php endif; ?>
</main>

<?php include '../includes/footer.php'; ?>
