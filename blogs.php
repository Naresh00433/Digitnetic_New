<!DOCTYPE html>
<html lang="en">

<?php include 'pre/head.php'; ?>
<link rel="stylesheet" href="css/blogs.css">

<body>
    <!-- Spinner Start -->
    <div id="spinner"
        class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;"></div>
    </div>
    <!-- Spinner End -->


    <?php include 'pre/header.php'; ?>


    <!-- Page Header Start -->
    <!-- <div class="container-fluid page-header py-5 mb-5 wow fadeIn" data-wow-delay="0.1s">
        <div class="container text-center py-5">
            <h1 class="display-2 text-white mb-4 animated slideInDown">Projects</h1>
            <nav aria-label="breadcrumb animated slideInDown">
                <ol class="breadcrumb justify-content-center mb-0">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item"><a href="#">Pages</a></li>
                    <li class="breadcrumb-item text-primary" aria-current="page">Projects</li>
                </ol>
            </nav>
        </div>
    </div> -->
    <!-- Page Header End -->

    <div class="container-fluid bg-white sticky-top shadow-sm">
        <div class="container py-3">
            <form class="row g-2 align-items-center" method="GET" action="blogs.php">
                <div class="col-12 col-md-6 mb-2 mb-md-0">
                    <div class="input-group">
                        <input type="text" class="form-control border-primary" name="search" placeholder="Search blogs..."
                            aria-label="Search blogs">
                        <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i></button>
                    </div>
                </div>
                <div class="col-12 col-md-4 mb-2 mb-md-0">
                    <select class="form-select border-primary" name="category" aria-label="Filter by category">
                        <option value="">All Categories</option>
                        <option value="analytics">Analytics</option>
                        <option value="marketing">Marketing</option>
                        <option value="business">Business</option>
                        <option value="social">Social</option>
                        <!-- Add more categories as needed -->
                    </select>
                </div>
                <div class="col-12 col-md-2">
                    <button type="submit" class="btn btn-primary w-100"><i class="fa fa-filter me-2"></i>Filter</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Project Start -->
    <div class="container-xxl pt-5" id="blog">
        <div class="container">
            <div class="text-center text-md-start pb-5 pb-md-0 wow fadeInUp" data-wow-delay="0.1s"
                style="max-width: 700px;">
                <p class="fs-5 fw-medium text-primary">Our Blogs</p>
                <h1 class="display-5 mb-5">Delve into our blog and discover valuable insights!</h1>
            </div>
            <div class="owl-carousel project-carousel wow fadeInUp" data-wow-delay="0.1s">
                <div class="project-item mb-5">
                    <div class="position-relative">
                        <span class="blog-category">Analytics</span>
                        <div style="height: 240px; overflow: hidden;">
                            <img class="card-ing-top img-fluid" src="img/project-1.jpg" alt="">
                        </div>
                        <div class="project-overlay">
                            <a class="btn btn-lg-square btn-light rounded-circle m-1" href="img/project-1.jpg"
                                data-lightbox="project"><i class="fa fa-eye"></i></a>
                            <a class="btn btn-lg-square btn-light rounded-circle m-1" href="#"><i
                                    class="fa fa-link"></i></a>
                        </div>
                    </div>
                    <a href="#">
                        <div class="card-body d-flex flex-column">
                            <h3 class="card-title h5">Data Analytics & Insights</h3>
                            <div class="blog-meta">
                                <span><i class="far fa-calendar-alt"></i> January 01, 2023</span>
                                <span class="mx-2">•</span>
                                <span><i class="far fa-user"></i> Author Name</span>
                            </div>
                            <p class="card-text flex-grow-1 text-dark">Erat ipsum justo amet duo et elitr dolor, est duo duo eos lorem</p>
                            <span class="read-more text-primary mt-auto">
                                Read More <i class="fas fa-arrow-right ml-2"></i>
                            </span>
                        </div>
                    </a>
                </div>
                <div class="project-item mb-5">
                    <div class="position-relative">
                        <span class="blog-category">Marketing</span>
                        <div style="height: 240px; overflow: hidden;">
                            <img class="card-ing-top img-fluid" src="img/project-2.jpg" alt="">
                        </div>
                        <div class="project-overlay">
                            <a class="btn btn-lg-square btn-light rounded-circle m-1" href="img/project-2.jpg"
                                data-lightbox="project"><i class="fa fa-eye"></i></a>
                            <a class="btn btn-lg-square btn-light rounded-circle m-1" href="#"><i
                                    class="fa fa-link"></i></a>
                        </div>
                    </div>
                    <a href="#">
                        <div class="card-body d-flex flex-column">
                            <h3 class="card-title h5">Marketing Content Strategy</h3>
                            <div class="blog-meta">
                                <span><i class="far fa-calendar-alt"></i> February 15, 2023</span>
                                <span class="mx-2">•</span>
                                <span><i class="far fa-user"></i> Jane Doe</span>
                            </div>
                            <p class="card-text flex-grow-1 text-dark">Discover how to create compelling marketing content that drives engagement and growth.</p>
                            <span class="read-more text-primary mt-auto">
                                Read More <i class="fas fa-arrow-right ml-2"></i>
                            </span>
                        </div>
                    </a>
                </div>
                <div class="project-item mb-5">
                    <div class="position-relative">
                        <span class="blog-category">Business</span>
                        <div style="height: 240px; overflow: hidden;">
                            <img class="card-ing-top img-fluid" src="img/project-3.jpg" alt="">
                        </div>
                        <div class="project-overlay">
                            <a class="btn btn-lg-square btn-light rounded-circle m-1" href="img/project-3.jpg"
                                data-lightbox="project"><i class="fa fa-eye"></i></a>
                            <a class="btn btn-lg-square btn-light rounded-circle m-1" href="#"><i
                                    class="fa fa-link"></i></a>
                        </div>
                    </div>
                    <a href="#">
                        <div class="card-body d-flex flex-column">
                            <h3 class="card-title h5">Business Target Market</h3>
                            <div class="blog-meta">
                                <span><i class="far fa-calendar-alt"></i> March 10, 2023</span>
                                <span class="mx-2">•</span>
                                <span><i class="far fa-user"></i> John Smith</span>
                            </div>
                            <p class="card-text flex-grow-1 text-dark">Learn how to identify and reach your ideal business target market for maximum impact.</p>
                            <span class="read-more text-primary mt-auto">
                                Read More <i class="fas fa-arrow-right ml-2"></i>
                            </span>
                        </div>
                    </a>
                </div>
                <div class="project-item mb-5">
                    <div class="position-relative">
                        <span class="blog-category">Social</span>
                        <div style="height: 240px; overflow: hidden;">
                            <img class="card-ing-top img-fluid" src="img/project-4.jpg" alt="">
                        </div>
                        <div class="project-overlay">
                            <a class="btn btn-lg-square btn-light rounded-circle m-1" href="img/project-4.jpg"
                                data-lightbox="project"><i class="fa fa-eye"></i></a>
                            <a class="btn btn-lg-square btn-light rounded-circle m-1" href="#"><i
                                    class="fa fa-link"></i></a>
                        </div>
                    </div>
                    <a href="#">
                        <div class="card-body d-flex flex-column">
                            <h3 class="card-title h5">Social Marketing Strategy</h3>
                            <div class="blog-meta">
                                <span><i class="far fa-calendar-alt"></i> April 05, 2023</span>
                                <span class="mx-2">•</span>
                                <span><i class="far fa-user"></i> Emily Clark</span>
                            </div>
                            <p class="card-text flex-grow-1 text-dark">Explore effective social marketing strategies to boost your brand presence online.</p>
                            <span class="read-more text-primary mt-auto">
                                Read More <i class="fas fa-arrow-right ml-2"></i>
                            </span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- Project End -->


    <?php include 'pre/footer.php'; ?>

    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square rounded-circle back-to-top"><i
            class="bi bi-arrow-up"></i></a>


    <?php include 'pre/footer_script.php'; ?>
</body>

</html>