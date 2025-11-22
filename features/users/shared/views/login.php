<div class="brand-logo">
    <i class="fa-solid fa-mosque"></i> masjidkamek
</div>

<div class="login-wrapper">
    <!-- Event Carousel / Display Section -->
    <section class="event-display">
        <div class="event-card compact">
            <div class="event-image-compact">
                <div class="date-badge-small">
                    <span class="day">24</span>
                    <span class="month">NOV</span>
                </div>
                <i class="fa-solid fa-mosque fa-2x"></i>
            </div>
            <div class="event-content-compact">
                <span class="tag-small">Upcoming</span>
                <h3>Community Gathering</h3>
                <p>Friday, 8:00 PM • Main Hall</p>
                
                <div class="carousel-dots">
                    <span class="active"></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
        </div>
    </section>

    <!-- Login Card -->
    <main class="login-card">
        <h2>Login</h2>
        
        <?php if (!empty($message)): ?>
            <div class="notice <?php echo strpos($message, 'successful') !== false ? 'success' : 'error'; ?>">
                <?php echo e($message); ?>
            </div>
        <?php endif; ?>

        <form method="post" action="<?php echo url('login'); ?>">
            <?php echo csrfField(); ?>
            
            <label>
                Username or Email
                <input type="text" name="username" required autofocus>
            </label>
            
            <label>
                Password
                <input type="password" name="password" required>
            </label>
            
            <div class="actions">
                <button class="btn" type="submit">Sign in</button>
                <div class="register-row">
                    <span>Don't have an account?</span>
                    <a class="link" href="<?php echo url('register'); ?>">Register</a>
                </div>
            </div>
        </form>
    </main>
</div>
