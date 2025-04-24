<div class="footer">
    <div class="footer-content">
        <div class="footer-logo">
            <img src="./images/top_logo.ico" alt="LMS Logo">
            <strong>Library Management System</strong>
        </div>
        <div class="footer-links">
            <a href="./includes/footer/blog.php">Blog</a>
            <a href="./includes/footer/contact.php">Contact</a>
            <a href="./includes/footer/privacy.php">Privacy Policy</a>
            <a href="./includes/footer/terms.php">Terms of Service</a>
        </div>
        <div class="footer-social">
            <a href="#"><i class="fab fa-facebook-f"></i></a>
            <a href="#"><i class="fab fa-twitter"></i></a>
            <a href="#"><i class="fab fa-instagram"></i></a>
            <a href="#"><i class="fab fa-linkedin-in"></i></a>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; <?php echo date("Y"); ?> LMS. All rights reserved.</p>
    </div>
</div>

</div>
<style>
    .footer {
        position: fixed;
        bottom: 0;
        width: 100%;
        height: 90px;
        background-color: #fff;
        color: #0a2558;
        ;
        padding: 15px 0;
        text-align: center;
        box-shadow: 0 -5px 10px rgba(0, 0, 0, 0.1);
    }

    .footer-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        max-width: 90%;
        margin: 0 auto;
        flex-wrap: wrap;
    }

    .footer-logo img {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        margin-left: 250px;
    }

    .footer-links a {
        color: #0a2558;
        text-decoration: none;
        margin: 0 10px;
    }

    .footer-links a:hover {
        text-decoration: underline;
    }

    .footer-social a {
        color: #0a2558;
        margin: 0 10px;
        font-size: 20px;
    }

    .footer-bottom {
        margin-top: 20px;
        font-size: 14px;
        margin-left: 200px;
        color: #212121;
    }

    .book-card {
        width: 250px;
        background: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 5px 10px rgba(0, 0, 0, 0.3);
        text-align: center;
        transition: transform 0.2s;
        margin-bottom: 20px;
    }
</style>