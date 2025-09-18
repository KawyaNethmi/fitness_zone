<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fitness Zone - Home</title>
    <link rel="stylesheet" href="css/home.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet"> <!-- Add Font Awesome -->
    <style>
        /* Body with Animated Background */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
            background: linear-gradient(135deg, rgba(255, 99, 71, 0.5), rgba(102, 204, 255, 0.5));
            background-size: 400% 400%;
            animation: backgroundAnimation 15s ease infinite;
        }

        @keyframes backgroundAnimation {
            0% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
            100% {
                background-position: 0% 50%;
            }
        }

        /* Header Styles */
        header {
            background-color: rgba(44, 62, 80, 0.9);
            color: white;
            text-align: center;
            padding: 20px 0;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
        }

        header h1 {
            font-size: 2.5rem;
            letter-spacing: 2px;
            font-family: 'Roboto', sans-serif;
            margin: 0;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.2);
        }

        /* Navigation Styles */
        nav ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
            text-align: center;
        }

        nav ul li {
            display: inline;
            margin-right: 20px;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
            font-size: 1.2rem;
            padding: 10px 15px;
            border-radius: 25px;
            transition: background-color 0.3s ease;
        }

        nav ul li a:hover {
            background-color: #FF6347;
        }

        /* Main Section */
        main {
            padding-top: 100px; /* Space for fixed header */
            padding-bottom: 30px;
        }

        /* Styling for the container sections with gradient colors */
        .slide {
            position: relative;
            width: 80%;
            margin: 170px auto;
            background: linear-gradient(135deg, #FFD700, #FF4500); /* Default gradient */
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 160px;
            display: flex;
            align-items: center;
            text-align: left;
            transition: transform 0.3s ease-in-out;
        }

        .slide:nth-child(odd) {
            background: linear-gradient(135deg, #ADD8E6, #4169E1); /* Light blue to royal blue */
        }

        .slide:nth-child(even) {
            background: linear-gradient(135deg, #32CD32, #008000); /* Lime green to forest green */
        }

        .slide:hover {
            transform: scale(1.02); /* Slight zoom effect on hover */
        }

        .slide h2 {
            font-size: 2.5rem;
            margin-bottom: 20px;
            font-family: 'Roboto', sans-serif;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.2);
            color: #fff;
        }

        .slide p {
            font-size: 1.2rem;
            line-height: 1.8;
            width: 60%; /* Set the text width */
            color: #f5f5f5; /* Light text for contrast */
        }

        /* Update for content to set the icon to the right side */
        .slide .content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            flex-direction: row-reverse; /* Change the flex direction to reverse */
        }

        .slide i {
            font-size: 150px; /* Larger icon size */
            color: #fff;
            margin-left: 30px;
        }

        /* Footer Styles */
        footer {
            background-color: rgba(44, 62, 80, 0.9);
            color: white;
            text-align: center;
            padding: 20px 0;
            font-size: 1rem;
            font-weight: normal;
            position: fixed;
            width: 100%;
            bottom: 0;
        }

        footer p {
            margin: 0;
        }

        /* Button Styles */
        button {
            background-color: #FFD700;
            border: none;
            padding: 10px 20px;
            font-size: 1.1rem;
            border-radius: 25px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-top: 20px;
        }

        button:hover {
            background-color: #FF4500;
            color: white;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <h1>Fitness Zone</h1>
        <nav>
            <ul>
                <li><a href="staff_login.php">Staff Login</a></li>
                <li><a href="member_login.php">Members Login</a></li>
                <li><a href="supplier_login.php">Supplier Login</a></li>
                <li><a href="contact_us.php">Contact Us</a></li>
            </ul>
        </nav>
    </header>

    <!-- Main Section -->
    <main>
        <!-- Motivation Section -->
        <div class="slide motivation">
            <h2>Motivation - Start Your Fitness Journey Today</h2>
            <div class="content">
                <i class="fas fa-running"></i> <!-- Icon for Motivation -->
                <p>Whether you’re new to fitness or an experienced athlete, getting motivated is the first step towards a healthy lifestyle. Our community supports you every step of the way to help you achieve your fitness goals.</p>
            </div>
        </div>

        <!-- About Us Section -->
        <div class="slide about">
            <h2>About Us - Fitness Zone</h2>
            <div class="content">
                <i class="fas fa-building"></i> <!-- Icon for About Us -->
                <p>Fitness Zone is dedicated to helping you reach your full fitness potential. Based in Sri Lanka, we provide world-class facilities and expert trainers to ensure you meet your fitness goals.</p>
            </div>
        </div>

        <!-- Services Section -->
        <div class="slide services">
            <h2>Our Services</h2>
            <div class="content">
                <i class="fas fa-dumbbell"></i> <!-- Icon for Services -->
                <p>We offer a wide range of services including personal training, yoga classes, fitness classes, and nutritional coaching. Our services are tailored to meet the unique needs of each member.</p>
            </div>
        </div>

        <!-- Trainers Section -->
        <div class="slide trainers">
            <h2>Meet Our Trainers</h2>
            <div class="content">
                <i class="fas fa-user-tie"></i> <!-- Icon for Trainers -->
                <p>Our trainers are highly experienced and certified, bringing years of expertise to help you achieve your fitness goals. They are passionate about fitness and are here to support you throughout your fitness journey.</p>
            </div>
        </div>

        <!-- Membership Section -->
        <div class="slide membership">
            <h2>Membership - Join Us Today</h2>
            <div class="content">
                <i class="fas fa-users"></i> <!-- Icon for Membership -->
                <p>Become a member of Fitness Zone today and start your fitness journey. We offer flexible membership plans to fit your lifestyle. Join us and transform your body and mind!</p>
            </div>
        </div>

        <!-- Testimonials Section -->
        <div class="slide testimonials">
            <h2>What Our Members Say</h2>
            <div class="content">
                <i class="fas fa-comments"></i> <!-- Icon for Testimonials -->
                <p>"Fitness Zone has transformed my life. The trainers are supportive and the classes are challenging but fun!" - Anjali, Member</p>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 Fitness Zone | Sri Lanka</p>
    </footer>

    <!-- JavaScript to handle the slide sequence -->
    <script>
        let currentSlide = 0;
        const slides = document.querySelectorAll('.slide');
        const totalSlides = slides.length;

        function showNextSlide() {
            // Hide the current slide
            slides[currentSlide].style.display = 'none';

            // Move to the next slide
            currentSlide = (currentSlide + 1) % totalSlides;

            // Show the next slide
            slides[currentSlide].style.display = 'block';
        }

        // Start the slide show when the page loads
        window.onload = function() {
            showNextSlide();
            setInterval(showNextSlide, 3000); // Change slide every 3 seconds
        };
    </script>
</body>
</html>
