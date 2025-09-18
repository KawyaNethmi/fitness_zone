<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <style>
        /* General Styling */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #F0F8FF; /* Light background */
            color: #483D8B; /* Dark Slate Blue */
        }

        header {
            background-color: #483D8B; /* Dark Slate Blue */
            color: white;
            text-align: center;
            padding: 20px;
        }

        header h1 {
            margin: 0;
        }

        main {
            max-width: 900px;
            margin: 30px auto;
            background: white;
            padding: 20px 30px;
            border-radius: 10px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #483D8B;
            margin-bottom: 20px;
        }

        p {
            text-align: center;
            margin-bottom: 30px;
            color: #666;
        }

        .back-to-home {
            display: inline-block;
            margin-bottom: 20px;
            color: #483D8B;
            text-decoration: none;
            font-weight: bold;
            font-size: 16px;
        }

        .back-to-home:hover {
            text-decoration: underline;
            color: #6A5ACD; /* Lighter Dark Slate Blue */
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        label {
            font-weight: bold;
            margin-bottom: 5px;
        }

        input, textarea, button {
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
            font-size: 16px;
        }

        input:focus, textarea:focus {
            outline: none;
            border-color: #483D8B;
            box-shadow: 0 0 5px rgba(72, 61, 139, 0.5);
        }

        textarea {
            resize: none;
            height: 150px;
        }

        button {
            background-color: #483D8B;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 18px;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #6A5ACD; /* Slightly lighter shade */
        }

        .thank-you-message {
            text-align: center;
            color: green;
            font-size: 20px;
            margin-top: 20px;
            font-weight: bold;
        }

        .contact-info {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
            gap: 20px;
        }

        .contact-info div {
            flex: 1;
            background-color: #F8F8FF;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .contact-info div h3 {
            margin-bottom: 10px;
            color: #483D8B;
        }

        .contact-info div p {
            margin: 0;
            color: #666;
        }

        footer {
            background-color: #483D8B;
            color: white;
            text-align: center;
            padding: 15px 0;
            margin-top: 30px;
        }

        footer a {
            color: #FFD700; /* Gold */
            text-decoration: none;
        }

        footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<header>
    <h1>Contact Us</h1>
</header>

<main>
    <a href="home.php" class="back-to-home">&larr; Back to Home</a>
    <h2>We'd Love to Hear From You!</h2>
    <p>Have a question, concern, or feedback? Fill out the form below, and we’ll get back to you as soon as possible.</p>

    <?php
    $thankYouMessage = "";
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = htmlspecialchars($_POST['name']);
        $email = htmlspecialchars($_POST['email']);
        $phone = htmlspecialchars($_POST['phone']);
        $message = htmlspecialchars($_POST['message']);

        if (!empty($name) && !empty($email) && !empty($phone) && !empty($message)) {
            $thankYouMessage = "Thank you for contacting us, $name! We will get back to you shortly.";
        }
    }
    ?>

    <?php if ($thankYouMessage): ?>
        <div class="thank-you-message"><?php echo $thankYouMessage; ?></div>
    <?php endif; ?>

    <form method="POST">
        <label for="name">Your Name</label>
        <input type="text" id="name" name="name" placeholder="Enter your full name" required>

        <label for="email">Your Email</label>
        <input type="email" id="email" name="email" placeholder="Enter your email address" required>

        <label for="phone">Your Phone Number</label>
        <input type="tel" id="phone" name="phone" placeholder="Enter your phone number" required>

        <label for="message">Your Message</label>
        <textarea id="message" name="message" placeholder="Write your message here..." required></textarea>

        <button type="submit">Send Message</button>
    </form>

    <div class="contact-info">
        <div>
            <h3>Email</h3>
            <p>support@fitnesszone.com</p>
        </div>
        <div>
            <h3>Phone</h3>
            <p>+076-086-0733</p>
        </div>
        <div>
            <h3>Location</h3>
            <p>123 Lane, Koswatta</p>
        </div>
    </div>
</main>

<footer>
    <p>&copy; 2024 Fitness Zone. All Rights Reserved. <a href="privacy_policy.php">Privacy Policy</a></p>
</footer>

</body>
</html>
