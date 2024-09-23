<?php include("connect.php"); ?>
<link rel="stylesheet" href="index.css">
<div class="container">
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="left">
            <div class="menu-icon">☰ Sections</div>
            <div class="search-bar">
                <span class="search-icon">🔍</span>
                <input type="text" placeholder="Search">
            </div>
        </div>
        <div class="right">
            <a href="#">Subscribe Now</a>
            <!-- Display username after login -->
            <?php if(isset($_SESSION['username'])): ?>
                <a href="profile.php">Welcome, <?php echo $_SESSION['username']; ?></a>
            <?php else: ?>
                <a href="login.php">Sign In</a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Header -->
    <div class="header">
   
        <div class="left-header">
            <span>Boston and New York Bear Brunt</span>
        </div>
        <div class="center-header">
            <h1>Universal</h1>
        </div>
        <div class="right-header">
            <div id="date"></div>
            <div class="weather">
                <span id="weather"></span>
            </div>
        </div>
    </div>

    <!-- Navigation Bar -->
    <div class="nav-bar">
        <ul>
            <li><a href="#">NEWS</a></li>
            <li><a href="#">OPINION</a></li>
            <li><a href="#">SCIENCE</a></li>
            <li><a href="#">LIFE</a></li>
            <li><a href="#">TRAVEL</a></li>
            <li><a href="#">MONEY</a></li>
            <li><a href="#">ART & DESIGN</a></li>
            <li><a href="#">SPORTS</a></li>
            <li><a href="#">PEOPLE</a></li>
            <li><a href="#">HEALTH</a></li>
            <li><a href="#">EDUCATION</a></li>
        </ul>
    </div>