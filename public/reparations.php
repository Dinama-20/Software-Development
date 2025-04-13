<?php include 'header.php'; ?>

<main>
    <h2>Repair Services</h2>
    <p>Choose the type of repair service you need for your watch or jewelry:</p>

    <form action="reparations.php" method="POST">
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
            <textarea id="details" name="details" rows="4"></textarea>
        </div>
        <button type="submit">Request Repair</button>
    </form>
</main>

<?php include 'footer.php'; ?>
