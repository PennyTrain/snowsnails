 <?php // Include the header file
 include "../header.php"; ?>
    <!-- CONTENT -->
    <main class="container">
        <!-- FORM -->
        <div class="row service-container">
            <h1 class="heading">Book with us!</h1>
            <p class="form-desc display-none">
                <span>Experience the Ultimate in Beauty and Relaxation!</span>
                <span>At Snows Nails Therapies, we offer a wide range of luxurious treatments designed to enhance your
                    natural beauty and rejuvenate your spirit. Whether you're looking for a stunning manicure,
                    soothing facial, or relaxing massage, we have the perfect service to meet your needs. Book your
                    appointment today and discover the blissful difference!</span>
            </p>
            <form class="col-lg-6 form-container" id="bookingform">
                <label for="bookfirstname" class="form-label">First Name:</label>
                <input type="text" id="bookfirstname" name="firstname" class="form-control" aria-label="First Name"
                    required>

                <label for="booklastname" class="form-label">Last Name:</label>
                <input type="text" id="booklastname" name="lastname" class="form-control" aria-label="Last Name"
                    required>

                <label for="bookemail" class="form-label">Email:</label>
                <input type="email" id="bookemail" name="email" class="form-control" aria-label="Email" required>

                <label for="phone" class="form-label">Phone Number:</label>
                <input type="tel" id="phone" name="phone" class="form-control" aria-label="Phone Number">

<label for="appointment_type" class="form-label">Appointment Type:</label>

<select id="appointment_type"
        name="appointment_type"
        class="form-control"
        aria-label="Appointment Type"
        required>

    <option value="" disabled selected>Select an option</option>

    <optgroup label="Nails">
        <option value="BIAB">BIAB</option>
        <option value="Infills">Infills</option>
        <option value="Acrylic">Acrylic</option>
        <option value="Gel">Gel</option>
        <option value="Pedicure">Pedicure</option>
        <option value="Manicure">Manicure</option>
    </optgroup>

    <optgroup label="Lashes">
        <option value="Lash Infill">Infill</option>
        <option value="Full Set">Full Set</option>
        <option value="Classics">Classics</option>
        <option value="Russians">Russians</option>
    </optgroup>

    <optgroup label="Body Treatments">
        <option value="Massage">Massage</option>
        <option value="Facial">Facial</option>
    </optgroup>

    <optgroup label="Brows">
        <option value="Threading">Threading</option>
        <option value="Plucking">Plucking</option>
        <option value="Shaping">Shaping</option>
        <option value="Laminating">Laminating</option>
    </optgroup>

</select>

                <label for="appointment_date" class="form-label">Appointment Date:</label>
                <input type="date" id="appointment_date" name="appointment_date" class="form-control"
                    aria-label="Appointment Date" required>

                <label for="appointment_time" class="form-label">Appointment Time:</label>
                <input type="time" id="appointment_time" name="appointment_time" class="form-control"
                    aria-label="Appointment Time" required>

                <button type="submit" class="button link" formaction="submit.html">Submit</button>
            </form>
            <!-- GOOGLE MAP -->
            <div class="col-lg-6 map-container">
                <iframe title="map" loading="lazy" allowfullscreen referrerpolicy="no-referrer-when-downgrade"
                    src="https://www.google.com/maps/embed/v1/place?key=AIzaSyCuBfkrmtyc3Rd8u2uYxb0xa2ozAvCz7fY&q=Beauty+Bliss,22+Lowestoft+Road,Carlton+Colville,NR33+8JD">
                </iframe>
            </div>
            <!-- CANELATION POLICY -->
            <p class="small-print">Can we politely remind everyone, we do require at least 24 hours notice to cancel or
                change your appointment. Failure to do so will result in 50% of your treatment value fee being charged.
                We reserve the right to refuse to rebook your appointment if you fail to comply with our policy. </p>
        </div>
    </main>
 <?php // Include the footer file
 include "../footer.php";
?>
