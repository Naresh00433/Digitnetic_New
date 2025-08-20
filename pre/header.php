    <!-- Topbar Start -->
    <div class="container-fluid bg-primary text-white d-none d-lg-flex">
        <div class="container py-3">
            <div class="d-flex align-items-center">
                <a href="index" class="d-flex align-items-center">
                    <img src="img/logo.png" alt="Digitnetic Logo" style="height:40px; width:auto; margin-right:10px;">
                    <!-- <h2 class="text-white fw-bold m-0">Digitnetic</h2> -->
                </a>
                <div class="ms-auto d-flex align-items-center">
                    <small class="ms-4">
                        <i class="fa fa-envelope me-3"></i>
                        <a href="mailto:info@digitnetic.com" class="text-white text-decoration-none">info@digitnetic.com</a>
                    </small>    
                    <small class="ms-4">
                        <i class="fa fa-phone-alt me-3"></i>
                        <a href="tel:+919990444673" class="text-white text-decoration-none">+91-9990 444 673</a>
                    </small>
                    <!-- <div class="ms-3 d-flex"> 
                        <a class="btn btn-sm-square btn-light text-primary rounded-circle ms-2" href="#"><i
                                class="fab fa-facebook-f"></i></a>
                        <a class="btn btn-sm-square btn-light text-primary rounded-circle ms-2" href="#"><i
                                class="fab fa-twitter"></i></a>
                        <a class="btn btn-sm-square btn-light text-primary rounded-circle ms-2" href="#"><i
                                class="fab fa-linkedin-in"></i></a>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
    <!-- Topbar End -->


    <!-- Navbar Start -->
    <div class="container-fluid bg-white sticky-top shadow-sm">
        <div class="container">
            <nav class="navbar navbar-expand-lg bg-white navbar-light p-lg-0">
                <a href="index" class="navbar-brand d-lg-none">
                    <h1 class="fw-bold m-0">GrowMark</h1>
                </a>
                <button type="button" class="navbar-toggler me-0" data-bs-toggle="collapse"
                    data-bs-target="#navbarCollapse">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <div class="navbar-nav">
                        <?php
                        $currentPage = basename($_SERVER['PHP_SELF'], ".php");
                        ?>
                        <a href="index" class="nav-item nav-link <?php echo ($currentPage == 'index') ? 'active' : ''; ?>">Home</a>
                        <a href="service" class="nav-item nav-link <?php echo ($currentPage == 'service') ? 'active' : ''; ?>">Our Services</a>
                        <a href="blogs" class="nav-item nav-link <?php echo ($currentPage == 'blogs') ? 'active' : ''; ?>">Blogs</a>
                        <!-- <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle <?php echo in_array($currentPage, ['feature', 'team', 'testimonial', 'quote', '404']) ? 'active' : ''; ?>" data-bs-toggle="dropdown">Pages</a>
                            <div class="dropdown-menu bg-light rounded-0 rounded-bottom m-0">
                                <a href="feature" class="dropdown-item <?php echo ($currentPage == 'feature') ? 'active' : ''; ?>">Features</a>
                                <a href="team" class="dropdown-item <?php echo ($currentPage == 'team') ? 'active' : ''; ?>">Our Team</a>
                                <a href="testimonial" class="dropdown-item <?php echo ($currentPage == 'testimonial') ? 'active' : ''; ?>">Testimonial</a>
                                <a href="quote" class="dropdown-item <?php echo ($currentPage == 'quote') ? 'active' : ''; ?>">Quotation</a>
                                <a href="404" class="dropdown-item <?php echo ($currentPage == '404') ? 'active' : ''; ?>">404 Page</a>
                            </div>
                        </div> -->
                        <a href="about" class="nav-item nav-link <?php echo ($currentPage == 'about') ? 'active' : ''; ?>">About</a>
                        <a href="contact" class="nav-item nav-link <?php echo ($currentPage == 'contact') ? 'active' : ''; ?>">Contact</a>
                    </div>
                    <div class="ms-auto d-none d-lg-block">
                        <a href="contact" class="btn btn-primary rounded-pill py-2 px-3">Get A Quote</a>
                    </div>
                </div>
            </nav>
        </div>
    </div>
    <!-- Navbar End -->