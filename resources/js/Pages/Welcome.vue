<script setup>
import Navigation from '@/Components/Navigation.vue';
import { Head } from '@inertiajs/vue3';
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
        <div class="max-w-7xl mx-auto p-6 lg:p-8">
            <!-- Rest of your content -->
        </div>
    </div>

    <!-- navbar -->
    <section>
        <nav class="navbar  navbar-expand-lg custom-nav">
            <div class="container-fluid">
                <a class="navbar-brand" href="#"><img src="/images/logo.png" width="40" height="40" alt="Logo">HR
                    ACADEMY</a>
                <button class="custom-toggler navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="custom-collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-btnss nav-item custom-nav-item">
                            <a class="nav-btn  nav-link " aria-current="page" href="#">HOME</a>
                        </li>
                        <li class="nav-btnss nav-item custom-nav-item">
                            <a class="nav-btn nav-link" href="#">ABOUT</a>
                        </li>
                        <li class="nav-btnss nav-item custom-nav-item">
                            <a class="nav-btn nav-link" href="#">CONTACT</a>
                        </li>
                        <li class="nav-item btn-login ">
                            <a class="nav-btn nav-link" href="#"><button type="button"
                                    class="btn btn-outline-light">LOGIN</button></a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </section>
    <div>
        <!-- Include Navigation Component -->
        <Navigation :can-login="canLogin" :can-register="canRegister" />
        <div class="max-w-7xl mx-auto p-6 lg:p-8">
            <!-- Rest of your content -->
        </div>
    </div>
    <!-- <div id="carouselExampleSlidesOnly" class="custom-carousel gradient-carousel carousel slide"
        data-bs-ride="carousel">
        <div class="custom-carousel-inner carousel-inner">
            <div class="carousel-item active">
                <img src="/images/1.png" class="d-block w-100 custom-img" alt="...">
            </div>
            <div class="carousel-item">
                <img src="/images/4.png" class="d-block w-100 custom-img" alt="...">
            </div>
            <div class="carousel-item">
                <img src="/images/3.png" class="d-block w-100 custom-img" alt="...">
            </div>

            <div class="custom-carousel-caption">
                <h1 class="display-4 fw-bold">
                    <spam class="text-dangersee">WHERE</spam>
                </h1>
                <h1 class="display-4 fw-bold">
                    <spam class="text-black">DREAM</spam>
                </h1>
                <h1 class="display-4 fw-bold">
                    <spam class="text-dangersee">ARE MADE!
                    </spam>
                </h1>
            </div>
        </div>
    </div> -->
    <section>
        <div id="carouselExampleSlidesOnly" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner custom-height">
                <div class="carousel-item active">
                    <img src="/images/1.png" class="d-block w-100 custom-img" alt="...">
                </div>
                <div class="carousel-item">
                    <img src="/images/3.png" class="d-block w-100 custom-img" alt="...">
                </div>
                <div class="carousel-item">
                    <img src="/images/4.png" class="d-block w-100 custom-img" alt="...">
                </div>
                <div class="custom-carousel-caption">
                    <h1 class="display-4 fw-bold">
                        <spam class="text-dangersee">WHERE</spam>
                    </h1>
                    <h1 class="display-4 fw-bold">
                        <spam class="text-black">DREAM</spam>
                    </h1>
                    <h1 class="display-4 fw-bold">
                        <spam class="text-dangersee">ARE MADE!
                        </spam>
                    </h1>
                </div>
            </div>
        </div>
    </section>
    <section class="section-space">
        <div class="container">
            <div class="row">
                <div class="col-12 justify-content-center align-items-center">
                    <h1 class="text-center courses-main-text">EXPLORE COURSES WITH US</h1>
                </div>
            </div>
            <div class="row section-space  d-flex justify-content-around">
                <a href="#course-content" style="display: contents;">
                    <div class="col-2 p-1 course-card">
                        <div class="row d-flex justify-content-center align-items-center">
                            <div class="col-3 d-flex justify-content-center align-items-center card-gif">
                                <img src="/images/line-chart.gif" alt="" height="100px" width="100px">
                            </div>
                        </div>
                        <div class="row d-flex justify-content-center align-items-center">

                            <div class="col-10 d-flex flex-column  justify-content-center align-items-center">
                                <h2 class="text-center">AAT</h2>
                                <p class="text-center">Our Professional Qualifications are recognised both locally and
                                    Internationally!</p>
                            </div>
                        </div>
                    </div>
                </a>
                <div class="col-2 p-1 course-card">
                    <div class="row d-flex justify-content-center align-items-center">
                        <div class="col-3 d-flex justify-content-center align-items-center card-gif">
                            <img src="/images/user.gif" alt="" height="100px" width="100px">
                        </div>
                    </div>
                    <div class="row d-flex justify-content-center align-items-center">
                        <div class="col-10 d-flex flex-column  justify-content-center align-items-center">
                            <h2 class="text-center">CIMA</h2>
                            <p class="text-center">Our Professional Qualifications are recognised both locally and
                                Internationally!</p>
                        </div>
                    </div>
                </div>
                <div class="col-2 p-1 course-card">
                    <div class="row d-flex justify-content-center align-items-center">
                        <div class="col-3 d-flex justify-content-center align-items-center card-gif">
                            <img src="/images/line-chart.gif" alt="" height="100px" width="100px">
                        </div>
                    </div>
                    <div class="row d-flex justify-content-center align-items-center">
                        <div class="col-10 d-flex flex-column  justify-content-center align-items-center">
                            <h2 class="text-center">NQHRM</h2>
                            <p class="text-center">Our Professional Qualifications are recognised both locally and
                                Internationally!</p>
                        </div>
                    </div>
                </div>
                <div class="col-2 p-1 course-card">
                    <div class="row d-flex justify-content-center align-items-center">
                        <div class="col-3 d-flex justify-content-center align-items-center card-gif">
                            <img src="/images/user.gif" alt="" height="100px" width="100px">
                        </div>
                    </div>
                    <div class="row d-flex justify-content-center align-items-center">
                        <div class="col-10 d-flex flex-column  justify-content-center align-items-center">
                            <h2 class="text-center">NQIEL</h2>
                            <p class="text-center">Our Professional Qualifications are recognised both locally and
                                Internationally!</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row section-space"></div>
        </div>
    </section>
    <section class="section-space">
        <div class="container-fluid course-content" id="course-content">
            <div class="container">
                <div class="row custom-row-height">
                    <div class="col-6 d-flex flex-column mt-5">
                        <h1 class="course-title">NATIONS HR ACADEMY</h1>
                        <p class="corse-description">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Deserunt
                            nulla,
                            eaque explicabo saepe excepturi ipsam dolore illo quisquam quis delectus
                            non laborum ratione et reprehenderit, incidunt dolor laboriosam culpa debitis!
                            Aspernatur voluptatum ipsam tenetur facere doloremque harum dolorem minima,
                            vero perspiciatis veritatis assumenda tempore libero, debitis dolores fugit neque?
                            Nam repudiandae iure a, similique mollitia ratione expedita quidem accusamus..</p>
                        <button class="btn btn btn-light course-main-button">Learn More</button>
                    </div>

                    <div class="col-6">
                        <img src="/images/f_girl.png" alt="">
                    </div>
                </div>
                <div class="row ">
                    <div class="col-4">
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
                                    <img src="/images/pexels-wildanzainulfaki-3660654.jpg" class="d-block w-100 "
                                        alt="a" height="100%">
                                    <div class="carousel-caption d-none d-md-block">
                                    </div>
                                </div>
                                <div class="carousel-item custom-carousel-item" data-bs-interval="2000">
                                    <img src="/images/pexels-pavel-danilyuk-7944065.jpg" class="d-block w-100" alt="a"
                                        height="100%">
                                </div>
                                <div class="carousel-item custom-carousel-item">
                                    <img src="/images/pexels-expressivestanley-3186386.jpg" class="d-block w-100"
                                        alt="a" height="100%">
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
                    <div class="col-8">
                        <div class="row">
                            <div class="col-12">
                                <h1 class="mt-4 text-end history-title">HISTORY OF THE COMPANY</h1>
                            </div>
                            <div class="col-12">
                                <p class="history-text mt-4">Global HR Solutions commenced its operations in 2008, and
                                    at the time was more focused on providing top-notch executive search services to
                                    local clients. Since then, the business has rapidly grown from its humble
                                    beginnings, diversifying and focusing its services into comprehensive Human Resource
                                    Solutions which enable businesses to grow. The values of the company are built on
                                    integrity in serving clients while ensuring that result driven and innovative
                                    solutions are delivered.
                                    Based on reviews by our clients, Global HR Solutions maintains a 100% Customer
                                    Satisfaction Rate owing to the drive of our team in helping you gain the best
                                    Global HR Solutions commenced its operations in 2008, and at the time was more
                                    focused on providing top-notch executive search services to local clients. Since
                                    then, the business has rapidly grown from its humble beginnings, diversifying and
                                    focusing its services into comprehensive Human Resource Solutions which enable
                                    businesses to grow. The values of the company are built on integrity in serving
                                    clients while ensuring that result driven and innovative solutions are delivered.
                                    Based on reviews by our clients, Global HR Solutions maintains a 100% Customer
                                    Satisfaction Rate owing to the drive of our team in helping you gain the best
                                    Global HR Solutions commenced its operations in 2008, and at the time was more
                                    focused on providing top-notch executive search services to local clients. Since
                                    then, the business has rapidly grown from its humble beginnings, diversifying and
                                    focusing its services into comprehensive Human Resource Solutions which enable
                                    businesses to grow. The values of the company are built on integrity in serving
                                    clients while ensuring that result driven and innovative solutions are delivered.
                                    Based on reviews by our clients, Global HR Solutions maintains a 100% Customer
                                    Satisfaction Rate owing to the drive of our team in helping you gain the best
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
                        <P>Global HR Solutions operates with the intent of providing powerful and innovative HR
                            Solutions which enable Businesses to grow to its maximum potential.</P>
                        <div class="v-image">
                            <img src="/images/unsplash_R-ki-drbwPU.png" alt="">
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
                <div class="col-8 event-description">
                    <h1>General <br> Convocation</h1>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quas molestiae dicta harum tempore, quos
                        commodi adipisci, ab facilis doloribus cumque ratione perferendis facere corrupti sequi natus
                        voluptatum quis id incidunt vel nostrum asperiores reiciendis eum ex qui. Recusandae voluptate
                        atque quisquam libero eaque? Nulla dolor perferendis quae molestias eveniet? Accusantium earum
                        magnam aut ea rerum nobis qui libero sapiente. Quos, cumque ipsum quod dolore dicta nihil
                        voluptates at dolorem ut ipsa et hic quasi minima sint ipsam ullam illum fuga
                        debitis molestiae voluptas earum. Dolorem amet est praesentium, animi aut nesciunt eveniet,
                        voluptatem vel laboriosam, sed accusamus adipisci facilis obcaecati?</p>
                    <button type="button" class="btn btn-outline-dark">Explore More</button>
                </div>
                <div class="col-4">
                    <img src="/images/images(10).png" alt="">
                </div>
            </div>
        </div>
    </section>
    <section class="section-space"></section>
    <section class="section-space"></section>
</template>