</div> <!-- End main-content -->

<?php if (!isset($hide_main_footer) || !$hide_main_footer): ?>
<footer>
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-4">
                <h5 class="fw-bold mb-3 text-warning">Quick Links</h5>
                <ul class="list-unstyled">
                    <li><a href="index.php" class="footer-link">Home</a></li>
                    <li><a href="contact.php" class="footer-link">Contact</a></li>
                    <li><a href="secretary.php" class="footer-link">Secretary</a></li>
                    <li><a href="treasurer.php" class="footer-link">Treasurer</a></li>
                    <li><a href="gallery.php" class="footer-link">Gallery</a></li>
                </ul>
            </div>
            <div class="col-md-4 mb-4">
                <h5 class="fw-bold mb-3 text-warning">Contact Info</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><i class="fa fa-phone me-2 text-primary"></i>+94761929402</li>
                    <li class="mb-2"><i class="fa-brands fa-whatsapp me-2 text-success"></i>+94761929402</li>
                    <li class="mb-2"><i class="fa fa-envelope me-2 text-danger"></i>afsanmohammed7562@gmail.com<li>
                    <li class="mb-2"><i class="fa fa-map-marker-alt me-2 text-info"></i> 133A, Vilinayadi 03, Sammanthurai </li>
                </ul>
            </div>
            <div class="col-md-4 mb-4">
                <h5 class="fw-bold mb-3 text-warning">Follow Us</h5>
                <div class="d-flex gap-3">
                    <a href="#" class="text-white fs-4"><i class="fa-brands fa-facebook"></i></a>
                    <a href="#" class="text-white fs-4"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#" class="text-white fs-4"><i class="fa-brands fa-whatsapp"></i></a>
                    <a href="#" class="text-white fs-4"><i class="fa-brands fa-linkedin"></i></a>
                    <a href="#" class="text-white fs-4"><i class="fa-brands fa-x-twitter"></i></a>
                </div>
            </div>
        </div>
    </div>
</footer>
<?php endif; ?>

<div class="bg-dark text-center py-3 border-top border-secondary">
    <p class="mb-0 text-white-50">&copy; <?php echo date('Y'); ?> Society Management System. All Rights Reserved By ARMA .</p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
