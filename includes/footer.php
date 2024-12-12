<footer class="section-p1">
        <div class="footer-container">
            <div class="col" id="contact-section">
                <h4>LuXe</h4>
                <h5>Contact Us</h5>
                <p><strong>Gmail:</strong> luxe@gmail.com</p>
                <p><strong>Phone:</strong> 09123456789</p>
                <p><strong>Address:</strong> 123 Main St, Iloilo City, Iloilo 5000</p>
                <div class="follow">
                    <h6>Follow Us</h6>
                    <div class="icons">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                    </div>
                </div>
            </div>

            <div class="col col-about" id="about-section">
                <h4>About Us</h4>
                <h5>Vision</h5>
                <p>Our jewelry embodies a modern, practical style that anyone can express, making it versatile for every individual.</p>
                <h5>Products</h5>
                <p>LuXe products are well known for being non-tarnish and hypoallergenic, making them safe and suitable for all skin types.</p>
            </div>

            <div class="col col-account">
                <h4>Admin</h4>
                <a href="javascript:void(0);" onclick="logoutUser()">Manage Products</a>
            </div>
            
            <div class="col col-payment">
                <h4>Secured Payment Gateways</h4>
                <img src="./Image/paypal.png" alt="Paypal" class="img">
                <img src="./Image/visa.png" alt="Visa" class="img">
                <img src="./Image/gcash.png" alt="Gcash" class="img">
            </div>
        </div>

        <div class="copyright">
            <p>&copy; 2024 LuXe. Bagilidad-Ecommerce Website Midterm Project.</p>
        </div>
    </footer>
    
    <script>
                function logoutUser() {
                    alert('You are logged in as a user, you have been logged out from the session.');
                    
                    // Redirect to logout.php, passing the page you want to go to after logout
                    window.location.href = './action/logout.php?redirect=../loginPage.php';
                }
            </script>
