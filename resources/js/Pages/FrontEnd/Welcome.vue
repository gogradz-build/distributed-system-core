<script setup>
import Navigation from "@/Components/Navigation.vue";
import { Head } from "@inertiajs/vue3";
defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
});
</script>

<template>

    <Head title="Welcome NHR" />
    <div>
        <!-- Include Navigation Component -->
        <Navigation :can-login="canLogin" :can-register="canRegister" />
    </div>
    <section>
        <div id="carouselExampleSlidesOnly" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner custom-height">
                <div class="carousel-item active">
                    <img src="/images/1.png" class="d-block w-100 custom-img" alt="..." />
                </div>
                <div class="carousel-item">
                    <img src="/images/3.png" class="d-block w-100 custom-img" alt="..." />
                </div>
                <div class="carousel-item">
                    <img src="/images/4.png" class="d-block w-100 custom-img" alt="..." />
                </div>
                <div class="custom-carousel-caption">
                    <h1 class="display-4 fw-bold">
                        <spam class="text-dangersee">WHERE</spam>
                    </h1>
                    <h1 class="display-4 fw-bold">
                        <spam class="text-black">DREAM</spam>
                    </h1>
                    <h1 class="display-4 fw-bold">
                        <spam class="text-dangersee">ARE MADE! </spam>
                    </h1>
                </div>
            </div>
        </div>
    </section>
    <section class="section-space">
        <div class="container">
            <div class="row">
                <div class="col-12 justify-content-center align-items-center">
                    <h1 class="text-center courses-main-text">
                        EXPLORE COURSES WITH US
                    </h1>
                </div>
            </div>
            <div class="row section-space d-flex justify-content-around">
                <a href="#course-content" style="display: contents">
                    <div class="col-lg-2 col-10 p-1 course-card">
                        <div class="row d-flex justify-content-center align-items-center">
                            <div class="col-3 d-flex justify-content-center align-items-center card-gif">
                                <img src="/images/line-chart.gif" alt="" height="100px" width="100px" />
                            </div>
                        </div>
                        <div class="row d-flex justify-content-center align-items-center">
                            <div class="col-10 d-flex flex-column justify-content-center align-items-center">
                                <h2 class="text-center">AAT</h2>
                                <p class="text-center">
                                    Our Professional Qualifications are
                                    recognised both locally and Internationally!
                                </p>
                            </div>
                        </div>
                    </div>
                </a>
                <div class="col-lg-2 col-10 p-1 course-card">
                    <div class="row d-flex justify-content-center align-items-center">
                        <div class="col-3 d-flex justify-content-center align-items-center card-gif">
                            <img src="/images/user.gif" alt="" height="100px" width="100px" />
                        </div>
                    </div>
                    <div class="row d-flex justify-content-center align-items-center">
                        <div class="col-10 d-flex flex-column justify-content-center align-items-center">
                            <h2 class="text-center">CIMA</h2>
                            <p class="text-center">
                                Our Professional Qualifications are recognised
                                both locally and Internationally!
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-10 p-1 course-card">
                    <div class="row d-flex justify-content-center align-items-center">
                        <div class="col-3 d-flex justify-content-center align-items-center card-gif">
                            <img src="/images/line-chart.gif" alt="" height="100px" width="100px" />
                        </div>
                    </div>
                    <div class="row d-flex justify-content-center align-items-center">
                        <div class="col-10 d-flex flex-column justify-content-center align-items-center">
                            <h2 class="text-center">NQHRM</h2>
                            <p class="text-center">
                                Our Professional Qualifications are recognised
                                both locally and Internationally!
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-10 mb-3 p-1 course-card">
                    <div class="row d-flex justify-content-center align-items-center">
                        <div class="col-3 d-flex justify-content-center align-items-center card-gif">
                            <img src="/images/user.gif" alt="" height="100px" width="100px" />
                        </div>
                    </div>
                    <div class="row d-flex justify-content-center align-items-center">
                        <div class="col-10 d-flex flex-column justify-content-center align-items-center">
                            <h2 class="text-center">NQIEL</h2>
                            <p class="text-center">
                                Our Professional Qualifications are recognised
                                both locally and Internationally!
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row section-space"></div>
        </div>
    </section>
    <section class="section-space" id="about">
        <div class="container-fluid course-content" id="course-content">
            <div class="container">
                <div class="row custom-row-height">
                    <div class="col-lg-6 col-12 d-flex flex-column">
                        <h1 class="course-title mt-5 mb-3">
                            NATIONS HR ACADEMY
                        </h1>
                        <p class="course-description">
                            Lorem, ipsum dolor sit amet consectetur adipisicing
                            elit. Deserunt nulla, eaque explicabo saepe
                            excepturi ipsam dolore illo quisquam quis delectus
                            non laborum ratione et repre
                        </p>
                        <button class="btn-lg btn-light course-main-button">
                            Learn More
                        </button>
                    </div>

                    <div class="col-lg-6 p-1">
                        <img src="/images/f_girl.png" alt="Girl Image" class="about-img mb-5" />
                    </div>
                </div>

                <div class="row history-section">
                    <div class="col-md-4 col-12">
                        <div id="carouselExampleDark" class="carousel carousel-dark slid custom-second-carousel"
                            data-bs-ride="carousel">
                            <div class="carousel-indicators">
                                <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="0"
                                    class="active custom-indicator" aria-current="true" aria-label="Slide 1"></button>
                                <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="1"
                                    aria-label="Slide 2" class="custom-indicator"></button>
                                <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="2"
                                    aria-label="Slide 3" class="custom-indicator"></button>
                            </div>
                            <div class="carousel-inner slider-inner">
                                <div class="carousel-item active custom-carousel-item" data-bs-interval="10000">
                                    <img src="/images/pexels-wildanzainulfaki-3660654.jpg" class="w-100 pb-4" alt="a" />
                                    <div class="carousel-caption d-none d-md-block"></div>
                                </div>
                                <div class="carousel-item custom-carousel-item" data-bs-interval="2000">
                                    <img src="/images/pexels-pavel-danilyuk-7944065.jpg" class="w-100 pb-4" alt="a"
                                        height="100%" />
                                </div>
                                <div class="carousel-item custom-carousel-item">
                                    <img src="/images/pexels-expressivestanley-3186386.jpg" class="w-100 pb-4" alt="a"
                                        height="100%" />
                                </div>
                            </div>
                            <button class="carousel-control-prev custom-carousel-item" type="button"
                                data-bs-target="#carouselExampleDark" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon d-none" aria-hidden="true"></span>
                                <span class="visually-hidden d-none">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleDark"
                                data-bs-slide="next">
                                <span class="carousel-control-next-icon d-none" aria-hidden="true"></span>
                                <span class="visually-hidden d-none">Next</span>
                            </button>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="row">
                            <div class="history">
                                <h1 class="history-title text-center">
                                    HISTORY OF THE COMPANY
                                </h1>
                            </div>
                            <div class="col-12 history-content ml-5">
                                <p class="history-text text-justify">
                                    Global HR Solutions commenced its operations
                                    in 2008, and at the time was more focused on
                                    providing top-notch executive search
                                    services to local clients. Since then, the
                                    business has rapidly grown from its humble
                                    beginnings, diversifying and focusing its
                                    services into comprehensive Human Resource
                                    Solutions which enable businesses to grow.
                                    The values of the company are built on
                                    integrity in serving clients while ensuring
                                    that result driven and innovative solutions
                                    are delivered.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section>
        <div class="container-fluid vision">
            <div class="container">
                <div class="row">
                    <div class="col-12 vision-content">
                        <h1 class="v-title">VISION STATEMENT</h1>
                        <P>Global HR Solutions operates with the intent of
                            providing powerful and innovative HR Solutions which
                            enable Businesses to grow to its maximum
                            potential.</P>
                        <div class="v-image">
                            <img src="/images/unsplash_R-ki-drbwPU.png" alt="" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="section-space">
        <div class="container">
            <div class="row">
                <div class="col-12 event-title">
                    <h1>EVENTS</h1>
                    <P>EXTERNAL ACTIVITIES</P>
                </div>
            </div>
            <div class="row event-content">
                <div class="col-lg-8 col-12 event-description">
                    <h1>
                        General <br />
                        Convocation
                    </h1>
                    <p>
                        Lorem ipsum dolor sit amet consectetur adipisicing elit.
                        Quas molestiae dicta harum tempore, quos commodi
                        adipisci, ab facilis doloribus cumque ratione
                        perferendis facere corrupti sequi natus voluptatum quis
                        id incidunt vel nostrum asperiores reiciendis eum ex
                        qui.
                    </p>
                    <button type="button" class="btn btn-outline-dark custom-explore">
                        Explore More
                    </button>
                </div>
                <div class="col-lg-4" style="
                        background-image: url('/images/images(10).png');
                        background-size: cover;
                        background-position: center;
                        border-top-right-radius: 50px;
                        border-bottom-right-radius: 50px;
                    ">
                    <!-- <img src="/images/images(10).png" alt=""> -->
                </div>
            </div>
        </div>
    </section>
    <section class="section-space">
        <div class="container">
            <div class="container custom-container-padding">
                <div class="row">
                    <div class="col-12 event-title justify-content-end lecture-section-title">
                        <div class="row">
                            <div class="col-12 d-flex justify-content-end lecture-title">
                                <h1>EDUCATIONAL ADVISORS</h1>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 d-flex justify-content-end">
                                <P class="lecture-title-d">YOUR TRUSTED REAL EDUCATIONAL ADVISORS</P>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row d-flex justify-content-end lecturer-section">
                    <div class="col-lg-3 lecturer-img-section" style="
                            background-image: url('/images/lec prof1.png');
                            background-size: cover;
                            background-position: center;
                            height: 300px;
                            border-radius: 10px;
                        ">
                        <!-- <img src="/images/images(10).png" alt=""> -->
                        <div class="row d-flex justify-content-center align-items-center">
                            <div class="col-11 lecture-infor mt-2 d-flex flex-column justify-content-start">
                                <P style="margin: 0px" class="postion-text">EXTERNAL ACTIVITIES</P>
                                <p class="lecture-name">LUDVIG MCFETRICH</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 lecturer-img-section" style="
                            background-image: url('/images/lec profo2.png');
                            background-size: cover;
                            background-position: center;
                            height: 300px;
                            border-radius: 10px;
                        ">
                        <!-- <img src="/images/images(10).png" alt=""> -->
                        <div class="row d-flex justify-content-center align-items-center">
                            <div class="col-11 lecture-infor mt-2 d-flex flex-column justify-content-start">
                                <P style="margin: 0px" class="postion-text">EXTERNAL ACTIVITIES</P>
                                <p class="lecture-name">LUDVIG MCFETRICH</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 lecturer-img-section" style="
                            background-image: url('/images/lec profo3.png');
                            background-size: cover;
                            background-position: center;
                            height: 300px;
                            border-radius: 10px;
                        ">
                        <!-- <img src="/images/images(10).png" alt=""> -->
                        <div class="row d-flex justify-content-center align-items-center">
                            <div class="col-11 lecture-infor mt-2 d-flex flex-column justify-content-start">
                                <P style="margin: 0px" class="postion-text">EXTERNAL ACTIVITIES</P>
                                <p class="lecture-name">LUDVIG MCFETRICH</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="section-space">
        <div class="container">
            <div class="row">
                <div class="col-12 event-title">
                    <h1>STUDENT FEEDBACK</h1>
                    <p>WHAT OUR STUDENTS SAY ABOUT US</p>
                </div>
            </div>
            <!-- Carousel wrapper -->
            <div id="carouselExampleControls" data-mdb-carousel-init class="carousel slide text-center carousel-dark"
                data-mdb-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img class="rounded-circle img-fluid shadow-1-strong mb-4" src="/images/user1.png" alt=""
                            style="width: 150px; height: 150px" />
                        <div class="row d-flex justify-content-center">
                            <div class="col-lg-8">
                                <h5 class="mb-3">Maria Kate</h5>
                                <p>NVQ student</p>
                                <p class="text-muted">
                                    <i class="fas fa-quote-left pe-2"></i>
                                    Lorem ipsum dolor sit amet consectetur
                                    adipisicing elit. Minus et deleniti nesciunt
                                    sint eligendi reprehenderit reiciendis,
                                    quibusdam illo, beatae quia fugit
                                    consequatur laudantium velit magnam error.
                                    Consectetur distinctio fugit doloremque.
                                </p>
                            </div>
                        </div>
                        <ul class="list-unstyled d-flex justify-content-center text-warning mb-0">
                            <li><i class="fas fa-star fa-sm"></i></li>
                            <li><i class="fas fa-star fa-sm"></i></li>
                            <li><i class="fas fa-star fa-sm"></i></li>
                            <li><i class="fas fa-star fa-sm"></i></li>
                            <li><i class="fas fa-star fa-sm"></i></li>
                        </ul>
                    </div>

                    <div class="carousel-item">
                        <img class="rounded-circle img-fluid shadow-1-strong mb-4" src="/images/user2.png" alt=""
                            style="width: 150px ; height: 150px" />
                        <div class="row d-flex justify-content-center">
                            <div class="col-lg-8">
                                <h5 class="mb-3">Anna Deynah</h5>
                                <p>NHR student</p>
                                <p class="text-muted">
                                    <i class="fas fa-quote-left pe-2"></i>
                                    Lorem ipsum dolor sit amet consectetur
                                    adipisicing elit. Minus et deleniti nesciunt
                                    sint eligendi reprehenderit reiciendis,
                                    quibusdam illo, beatae quia fugit
                                    consequatur laudantium velit magnam error.
                                    Consectetur distinctio fugit doloremque.
                                </p>
                            </div>
                        </div>
                        <ul class="list-unstyled d-flex justify-content-center text-warning mb-0">
                            <li><i class="fas fa-star fa-sm"></i></li>
                            <li><i class="fas fa-star fa-sm"></i></li>
                            <li><i class="fas fa-star fa-sm"></i></li>
                            <li><i class="fas fa-star fa-sm"></i></li>
                            <li><i class="fas fa-star fa-sm"></i></li>
                        </ul>
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls"
                    data-bs-slide="prev">
                    <span class="carousel-control-prev-icon text-body" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls"
                    data-bs-slide="next">
                    <span class="carousel-control-next-icon text-body" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
            <!-- Carousel wrapper -->
        </div>
    </section>
    <section class="section-space"></section>
    <section class="section-space">
        <div class="container" id="contact">
            <div class="row">
                <div class="col-lg-6">
                    <div class="row mb-4 event-title">
                        <h1>Get in touch</h1>
                        <p>Our friendly team would love to hear from you.</p>
                    </div>
                    <form class="row g-4 needs-validation" novalidate>
                        <div class="col-md-6">
                            <label for="validationCustom01" class="form-label">First name</label>
                            <input type="text" class="form-control" placeholder="First Name" id="validationCustom01"
                                required />
                            <div class="valid-feedback">Looks good!</div>
                        </div>
                        <div class="col-md-6">
                            <label for="validationCustom02" class="form-label">Last name</label>
                            <input type="text" class="form-control" placeholder="Last Name" id="validationCustom02"
                                required />
                            <div class="valid-feedback">Looks good!</div>
                        </div>

                        <div class="col-md-12">
                            <label for="validationCustom03" class="form-label">Email</label>
                            <input type="email" class="form-control" id="validationCustom03" required
                                placeholder="you@company.com" />
                            <div class="invalid-feedback">
                                Please provide a valid city.
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label for="validationCustom03" class="form-label">Message</label>
                            <textarea class="form-control" placeholder="Leave a comment here" id="floatingTextarea2"
                                style="height: 100px"></textarea>
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="invalidCheck" required />
                                <label class="form-check-label" for="invalidCheck">
                                    You agree to our friendly privacy policy.
                                </label>
                                <div class="invalid-feedback">
                                    You must agree before submitting.
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <button class="btn text-white contact-buton" type="submit">
                                Send Message
                            </button>
                        </div>
                    </form>
                </div>
                <div class="col-lg-6 map p-5 mt-5 ">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d5218.483663618943!2d79.92667247499706!3d6.9771636930236145!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ae257e99b6d62a1%3A0x5a2bb70740009f1c!2sNations%20HR%20Academy!5e1!3m2!1sen!2slk!4v1733200787491!5m2!1sen!2slk"
                        width="100%" height="90%" style="border: 0" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
    </section>
    <section class="section-space">
        <div class="row">
            <div class="col-12 footer">
                <div class="row">
                    <div class="col-lg-4 ml-4">
                        <h1 class="footer-title ">NHR ACADEMY</h1>
                        <P class="footer-description">Global HR Solutions is a comprehensive human
                            resource solutions provider that helps organizations
                            to adapt to the dynamic working environment</P>
                        <div class="row">
                            <div class="col-12 mt-4">
                                <div class="d-flex  align-items-center gap-3">
                                    <a href="https://www.linkedin.com" target="_blank" aria-label="LinkedIn">
                                        <img src="/images/in.png" alt="LinkedIn" class="social-icon" />
                                    </a>
                                    <a href="https://www.twitter.com" target="_blank" aria-label="Twitter">
                                        <img src="/images/twit.png" alt="Twitter" class="social-icon" />
                                    </a>
                                    <a href="https://www.facebook.com" target="_blank" aria-label="Facebook">
                                        <img src="/images/fb.png" alt="Facebook" class="social-icon" />
                                    </a>
                                    <a href="https://www.youtube.com" target="_blank" aria-label="YouTube">
                                        <img src="/images/youtube.png" alt="YouTube" class="social-icon" />
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-lg-7">
                        <div class="row d-flex justify-content-between">
                            <!-- Company Section -->
                            <div class="col-2">
                                <p class="section-title">Company</p>
                                <ul class="footer-links">
                                    <li><a href="/about" class="footer-link">About</a></li>
                                    <li><a href="/hr-solution" class="footer-link">HR Solution</a></li>
                                    <li><a href="/hr-support" class="footer-link">HR Support</a></li>
                                    <li><a href="/hr-constancy-services" class="footer-link">HR Constancy Services</a>
                                    </li>
                                </ul>
                            </div>

                            <!-- Help Section -->
                            <div class="col-2">
                                <p class="section-title">Help</p>
                                <ul class="footer-links">
                                    <li><a href="/customer-support" class="footer-link">Customer Support</a></li>
                                    <li><a href="/delivery-details" class="footer-link">Delivery Details</a></li>
                                    <li><a href="/terms-and-conditions" class="footer-link">Terms & Conditions</a></li>
                                    <li><a href="/privacy-policy" class="footer-link">Privacy Policy</a></li>
                                </ul>
                            </div>

                            <!-- Resources Section -->
                            <div class="col-2">
                                <p class="section-title">Resources</p>
                                <ul class="footer-links">
                                    <li><a href="/free-ebook" class="footer-link">Free eBook</a></li>
                                    <li><a href="/training-and-development" class="footer-link">Training and
                                            Development</a></li>
                                    <li><a href="/outsourcing" class="footer-link">Outsourcing</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
</template>
