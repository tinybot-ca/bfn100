<footer class="text-muted">
    <div class="container">
        <p>&copy; <?php echo date('Y'); ?> Tinybot. All rights reserved.</p>
        <p>Logged in: <?php if(is_logged_in()) { echo 'true - ' . $_SESSION['username']; } else { echo 'false'; } ?></p>
        <p>Server timezone: <?php echo ini_get('date.timezone'); ?></p>
    </div>
</footer>

<!-- Bootstrap core JavaScript
    ================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

</body>
</html>

<?php db_disconnect($db); ?>