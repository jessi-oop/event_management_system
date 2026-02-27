<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EMS - Event Management System</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="../assets/css/hero-page.css">
</head>
<body>
    <section class="hero-section">
        <div class="grid-overlay"></div>
        
        <div class="container hero-content">
            <div class="row align-items-center g-5">
                <!-- Left Content -->
                <div class="col-lg-6 hero-left">
                    <div class="brand-badge">
                        <div class="brand-icon">E</div>
                        <span class="brand-text">Event Management System</span>
                    </div>

                    <h1 class="hero-heading">
                        Create Unforgettable
                        <span class="highlight">Events</span>
                        Effortlessly
                    </h1>

                    <p class="hero-tagline">
                        Your all-in-one platform to plan, manage, and execute exceptional events. From conferences to concerts, we've got you covered.
                    </p>

                    <!-- Login Button -->
                    <div class="mb-5">
                        <a href="/Event-Management-System/auth/login.php" class="btn-login">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                                <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/>
                            </svg>
                            Sign In to Your Account
                        </a>
                    </div>

                    <div class="row stats-container g-4">
                        <div class="col-4">
                            <div class="stat-item">
                                <div class="stat-number">10K+</div>
                                <div class="stat-label">Events Created</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="stat-item">
                                <div class="stat-number">50K+</div>
                                <div class="stat-label">Active Users</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="stat-item">
                                <div class="stat-number">99.9%</div>
                                <div class="stat-label">Uptime</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Content - Feature Cards -->
                <div class="col-lg-6 hero-right">
                    <div class="row g-4">
                        <div class="col-12">
                            <div class="feature-card">
                                <div class="feature-icon">📅</div>
                                <h3 class="feature-title">Smart Scheduling</h3>
                                <p class="feature-description">
                                    Intelligent calendar management with automated reminders and conflict detection
                                </p>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="feature-card">
                                <div class="feature-icon">🎟️</div>
                                <h3 class="feature-title">Seamless Registration</h3>
                                <p class="feature-description">
                                    Quick and easy attendee registration with customizable forms and instant confirmations
                                </p>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="feature-card">
                                <div class="feature-icon">📊</div>
                                <h3 class="feature-title">Real-time Analytics</h3>
                                <p class="feature-description">
                                    Track attendance, engagement, and performance metrics with comprehensive dashboards
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Add hover effects to feature cards
        document.querySelectorAll('.feature-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-8px) scale(1.02)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });

        // Add parallax effect on scroll
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const heroSection = document.querySelector('.hero-section');
            if (heroSection) {
                heroSection.style.transform = `translateY(${scrolled * 0.5}px)`;
            }
        });
    </script>
</body>
</html>