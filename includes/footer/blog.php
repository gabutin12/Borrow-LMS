<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog - Library Management System</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="icon" href="../../images/top_logo.ico" type="image/x-icon">
    <style>
        .blog-section {
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            min-height: 100vh;
            padding: 80px 0;
        }

        .blog-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .blog-header {
            text-align: center;
            margin-bottom: 50px;
        }

        .blog-header h1 {
            color: #2c3e50;
            font-size: 2.5rem;
            margin-bottom: 1rem;
            position: relative;
        }

        .blog-header h1:after {
            content: '';
            display: block;
            width: 80px;
            height: 4px;
            background: #3498db;
            margin: 15px auto;
            border-radius: 2px;
        }

        .blog-header p {
            color: #7f8c8d;
            font-size: 1.2rem;
        }

        .blog-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }

        .blog-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: transform 0.3s ease;
        }

        .blog-card:hover {
            transform: translateY(-5px);
        }

        .blog-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .blog-content {
            padding: 25px;
        }

        .blog-date {
            color: #7f8c8d;
            font-size: 0.9rem;
            margin-bottom: 10px;
        }

        .blog-title {
            color: #2c3e50;
            font-size: 1.4rem;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .blog-excerpt {
            color: #666;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .blog-footer {
            text-align: center;
            margin-top: 50px;
            padding-top: 30px;
            border-top: 1px solid #eee;
        }

        .read-more {
            color: #3498db;
            text-decoration: none;
            font-weight: 500;
            display: inline-block;
            margin-top: 10px;
        }

        .read-more:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .blog-container {
                padding: 0 15px;
            }
            
            .blog-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="blog-section">
        <div class="blog-container">
            <div class="blog-header">
                <h1>Library News & Updates</h1>
                <p>Stay informed about our latest resources, events, and library developments</p>
            </div>
            
            <div class="blog-grid">
                <div class="blog-card">
                    <img src="  ./lib/images/library1.jpg" alt="New Digital Resources" class="blog-image">
                    <div class="blog-content">
                        <div class="blog-date">April 21, 2025</div>
                        <h2 class="blog-title">New Digital Resources Available</h2>
                        <p class="blog-excerpt">We've expanded our digital collection with new nursing journals and e-books. Access the latest medical research and nursing education materials from anywhere on campus.</p>
                        <a href="#" class="read-more">Read More →</a>
                    </div>
                </div>

                <div class="blog-card">
                    <img src="../lib/images/library2.jpg" alt="Study Space Updates" class="blog-image">
                    <div class="blog-content">
                        <div class="blog-date">April 18, 2025</div>
                        <h2 class="blog-title">Extended Library Hours During Finals</h2>
                        <p class="blog-excerpt">To support your exam preparation, we're extending our library hours. The library will remain open until 10 PM on weekdays throughout the finals period.</p>
                        <a href="#" class="read-more">Read More →</a>
                    </div>
                </div>

                <div class="blog-card">
                    <img src="../lib/images/library3.jpg" alt="Workshop Announcement" class="blog-image">
                    <div class="blog-content">
                        <div class="blog-date">April 15, 2025</div>
                        <h2 class="blog-title">Research Workshop Series</h2>
                        <p class="blog-excerpt">Join our upcoming workshop series on medical research methodologies. Learn how to effectively use our research databases and citation tools.</p>
                        <a href="#" class="read-more">Read More →</a>
                    </div>
                </div>
            </div>

            <div class="blog-footer">
                <p>Want to stay updated? Follow us on social media or subscribe to our newsletter.</p>
                <div style="margin-top: 20px;">
                    <small class="text-muted">© <?php echo date('Y'); ?> Library Management System - College of Nursing. All rights reserved.</small>
                </div>
            </div>
        </div>
    </div>
</body>
</html>