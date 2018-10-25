@extends('layouts/base')

  @section('content')
	<!--====================================
    ——— BANNER
    ===================================== -->
    <section class="bannercontainer bannercontainerV1">
      <div class="fullscreenbanner-container">
        <div class="fullscreenbanner">
          <ul>
            <li data-transition="fade" data-slotamount="5" data-masterspeed="1000" data-title="Slide 2">
              <img src="assets/img/home/slider/slider-4.jpg" alt="slidebg1" data-bgfit="cover" data-bgposition="center center" data-bgrepeat="no-repeat">
              <div class="slider-caption container">
                <div class="tp-caption rs-caption-1 sft start text-center"
                  data-hoffset="0"
                  data-x="center"
                  data-y="200"
                  data-speed="800"
                  data-start="1000"
                  data-easing="Back.easeInOut"
                  data-endspeed="300">
                  Bienvenido a nuestra<br/> comunidad
                </div>

                <div class="tp-caption rs-caption-2 sft text-center"
                  data-hoffset="0"
                  data-x="center"
                  data-y="265"
                  data-speed="1000"
                  data-start="1500"
                  data-easing="Power4.easeOut"
                  data-endspeed="300"
                  data-endeasing="Power1.easeIn"
                  data-captionhidden="off"><br/><br/>
                  Comparte información con tu grupo en esta<br/> red social creada solo para ti <br/><br/>
                  <a href="usuario/create" class="btn btn-primary" target="blac">Entrar</a>
                </div>
              </div>
            </li>
            <li data-transition="fade" data-slotamount="5" data-masterspeed="700" data-title="Slide 1">
              <img src="assets/img/home/slider/slider-1.jpg" alt="slidebg1" data-bgfit="cover" data-bgposition="center center" data-bgrepeat="no-repeat">
              <div class="slider-caption container">
                <div class="tp-caption rs-caption-1 sft start"
                  data-hoffset="0"
                  data-y="200"
                  data-speed="800"
                  data-start="1000"
                  data-easing="Back.easeInOut"
                  data-endspeed="300">
                  <a href="http://programaciclon.edu.co/eventos/" class="btn btn-primary" target="blank">Ir al calendario</a>
                </div>

                <div class="tp-caption rs-caption-2 sft"
                  data-hoffset="0"
                  data-y="265"
                  data-speed="1000"
                  data-start="1500"
                  data-easing="Power4.easeOut"
                  data-endspeed="300"
                  data-endeasing="Power1.easeIn"
                  data-captionhidden="off">
                  
                </div>
              </div>
            </li>
            <li data-transition="fade" data-slotamount="5" data-masterspeed="1000" data-title="Slide 2">
              <img src="assets/img/home/slider/slider-2.jpg" alt="slidebg1" data-bgfit="cover" data-bgposition="center center" data-bgrepeat="no-repeat">
              <div class="slider-caption container">
                <div class="tp-caption rs-caption-1 sft start text-center"
                  data-hoffset="0"
                  data-x="center"
                  data-y="200"
                  data-speed="800"
                  data-start="1000"
                  data-easing="Back.easeInOut"
                  data-endspeed="300">
                  
                </div>

                <div class="tp-caption rs-caption-2 sft text-center"
                  data-hoffset="0"
                  data-x="center"
                  data-y="265"
                  data-speed="1000"
                  data-start="1500"
                  data-easing="Power4.easeOut"
                  data-endspeed="300"
                  data-endeasing="Power1.easeIn"
                  data-captionhidden="off">
                  
                </div>
              </div>
            </li>
            <li data-transition="fade" data-slotamount="5" data-masterspeed="700"  data-title="Slide 3">
              <img src="assets/img/home/slider/slider-3.jpg" alt="slidebg1" data-bgfit="cover" data-bgposition="center center" data-bgrepeat="no-repeat">
              <div class="slider-caption container">
                <div class="tp-caption rs-caption-1 sft start text-right"
                  data-hoffset="0"
                  data-y="200"
                  data-x="right"
                  data-speed="800"
                  data-start="1000"
                  data-easing="Back.easeInOut"
                  data-endspeed="300">
                  <a href="http://elmaestrotienelapalabra2018.blogspot.com/" class="btn btn-primary" target="_blank">Visitar</a>
                </div>

                <div class="tp-caption rs-caption-2 sft text-right"
                  data-hoffset="0"
                  data-y="265"
                  data-x="right"
                  data-speed="1000"
                  data-start="1500"
                  data-easing="Power4.easeOut"
                  data-endspeed="300"
                  data-endeasing="Power1.easeIn"
                  data-captionhidden="off">
                  
                </div>
              </div>
            </li>
            
          </ul>
        </div>
      </div>
    </section>

	<!--====================================
    ——— MAIN SECTION
    ===================================== -->
    <section class="clearfix linkSection hidden-xs">
      <div class="sectionLinkArea hidden-xs scrolling">
        <div class="container">
          <div class="row">
            <div class="col-sm-3">
              <a href="javascript:void(0)" class="sectionLink bg-color-1" id="coursesLink">
                <i class="fa fa-map-marker linkIcon border-color-1" aria-hidden="true"></i>
                <span class="linkText">28 municipios</span>
                <i class="fa fa-map-marker locateArrow" aria-hidden="true"></i>
              </a>
            </div>
            <div class="col-sm-3 ">
              <a href="javascript:void(0)" class="sectionLink bg-color-2" id="teamLink">
                <i class="fa fa-graduation-cap linkIcon border-color-2" aria-hidden="true"></i>
                <span class="linkText">110.880 Niños alcanzados</span>
                <i class="fa fa-graduation-cap locateArrow" aria-hidden="true"></i>
              </a>
            </div>
            <div class="col-sm-3 ">
              <a href="javascript:void(0)" class="sectionLink bg-color-3" id="galleryLink">
                <i class="fa fa-bank linkIcon border-color-3" aria-hidden="true"></i>
                <span class="linkText">320 Sedes Educativas</span>
                <i class="fa fa-bank locateArrow" aria-hidden="true"></i>
              </a>
            </div>
            <div class="col-sm-3">
              <a href="javascript:void(0)" class="sectionLink bg-color-4" id="newsLink">
                <i class="fa fa-male linkIcon border-color-4" aria-hidden="true"></i>
                <span class="linkText">3.386 Maestros capacitados</span>
                <i class="fa fa-male locateArrow" aria-hidden="true"></i>
              </a>
            </div>
          </div>
        </div>
      </div>
    </section>

	<!--====================================
    ——— FEATURE SECTION
    ===================================== -->
    <section class="mainContent full-width clearfix featureSection">
      <div class="container">
        <div class="sectionTitle text-center " >
          <h2 class="wow fadeInUp">
            <span class="shape shape-left bg-color-4"></span>
            <span>Otros servicios</span>
            <span class="shape shape-right bg-color-4"></span>
          </h2>
        </div>

        <div class="row ">
          <div class="col-sm-6 col-lg-4 col-xs-12">
            <div class="media featuresContent wow fadeInUp">
              <span class="media-left bg-color-1">
                <i class="fa fa-cog bg-color-1" aria-hidden="true"></i>
              </span>
              <div class="media-body">
                <h3 class="media-heading color-1">Estrategias</h3>
                <p>Nuestro programa descansa sobre cuatro estrategias básicamente: acompañamiento y formación de grupos, formación de maestros, sistemas de seguimiento y evaluación, comunidad de práctica, y consolidación.</p>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-lg-4 col-xs-12">
            <div class="media featuresContent wow fadeInUp">
              <span class="media-left bg-color-2">
                <i class="fa fa-flask bg-color-2" aria-hidden="true"></i>
              </span>
              <div class="media-body">
                <h3 class="media-heading color-2">Gózate la ciencia</h3>
                <p>Gózate la ciencia es un elemento de nuestro programa, orientado al fomento de la investigación y la innovación, por medio de ferias y otras actividades que estimulan la investigación y el trabajo en equipo.</p>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-lg-4 col-xs-12">
            <div class="media featuresContent wow fadeInUp">
              <span class="media-left bg-color-3">
                <i class="fa fa-maxcdn bg-color-3" aria-hidden="true"></i>
              </span>
              <div class="media-body">
                <h3 class="media-heading color-3">Macondo</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt.</p>
              </div>
            </div>
          </div>
          <!--<div class="col-sm-6 col-lg-4 col-xs-12">
            <div class="media featuresContent wow fadeInUp">
              <span class="media-left bg-color-4">
                <i class="fa fa-cutlery bg-color-4" aria-hidden="true"></i>
              </span>
              <div class="media-body">
                <h3 class="media-heading color-4">Delicious Food</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt.</p>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-lg-4 col-xs-12">
            <div class="media featuresContent wow fadeInUp">
              <span class="media-left bg-color-5">
                <i class="fa fa-heart bg-color-5" aria-hidden="true"></i>
              </span>
              <div class="media-body">
                <h3 class="media-heading color-5">Love &amp; Care</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt.</p>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-lg-4 col-xs-12">
            <div class="media featuresContent wow fadeInUp">
              <span class="media-left bg-color-6">
                <i class="fa fa-shield bg-color-6" aria-hidden="true"></i>
              </span>
              <div class="media-body">
                <h3 class="media-heading color-6">Meny Sports</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt.</p>
              </div>
            </div>
          </div>-->
        </div>
      </div>
    </section>

	<!--====================================
    ——— PROMOTION SECTION
    ===================================== -->
    <section class="promotionWrapper " style="background-image: url( {{ asset('assets/img/home/promotion-1.jpeg') }} );" >
      <div class="container">
        <div class="promotionInfo wow fadeInUp">
          <h2>Necesitas más información?</h2>
          <p>Contáctanos y en el menor tiempo posible te responderemos </p>
          <a href="#homeContactSection" class="btn btn-primary"><i class="fa fa-phone" aria-hidden="true"></i>Contactar</a>
        </div>
      </div>
    </section>

	<!--====================================
    ——— WHITE SECTION
    ===================================== 
    <section class="whiteSection full-width clearfix coursesSection "  id="ourCourses" >
      <div class="container">
        <div class="sectionTitle text-center">
          <h2 class="wow fadeInUp" >
            <span class="shape shape-left bg-color-4"></span>
            <span>Our Courses</span>
            <span class="shape shape-right bg-color-4"></span>
          </h2>
        </div>

        <div class="row ">
          <div class="col-sm-6 col-md-3 col-xs-12 block">
            <div class="thumbnail thumbnailContent wow fadeInUp">
              <a href="course-single-left-sidebar.html"><img src="assets/img/home/courses/course-1.jpg" alt="image" class="img-responsive"></a>
              <div class="sticker bg-color-1">$50</div>
              <div class="caption border-color-1">
                <h3><a href="course-single-left-sidebar.html" class="color-1">Morbi scelerisque nibh.</a></h3>
                <ul class="list-unstyled">
                  <li><i class="fa fa-calendar-o" aria-hidden="true"></i>Age 2 to 4 Years</li>
                  <li><i class="fa fa-clock-o" aria-hidden="true"></i>9.00AM-11.00AM</li>
                </ul>
                <p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. </p>
                <ul class="list-inline btn-yellow">
                  <li><a href="cart-page.html" class="btn btn-primary "><i class="fa fa-shopping-basket " aria-hidden="true"></i>Add to Cart</a></li>
                  <li><a href="course-single-left-sidebar.html" class="btn btn-link"><i class="fa fa-angle-double-right" aria-hidden="true"></i> More</a></li>
                </ul>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-md-3 col-xs-12 block ">
            <div class="thumbnail thumbnailContent wow fadeInUp">
              <a href="course-single-left-sidebar.html"><img src="assets/img/home/courses/course-2.jpg" alt="image" class="img-responsive"></a>
              <div class="sticker bg-color-2">$50</div>
              <div class="caption border-color-2">
                <h3><a href="course-single-left-sidebar.html" class="color-2">Phasellus convallis eros.</a></h3>
                <ul class="list-unstyled">
                  <li><i class="fa fa-calendar-o" aria-hidden="true"></i>Age 2 to 4 Years</li>
                  <li><i class="fa fa-clock-o" aria-hidden="true"></i>9.00AM-11.00AM</li>
                </ul>
                <p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. </p>
                <ul class="list-inline btn-green">
                  <li><a href="cart-page.html" class="btn btn-primary "><i class="fa fa-shopping-basket " aria-hidden="true"></i>Add to Cart</a></li>
                  <li><a href="course-single-left-sidebar.html" class="btn btn-link"><i class="fa fa-angle-double-right" aria-hidden="true"></i> More</a></li>
                </ul>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-md-3 col-xs-12 block ">
            <div class="thumbnail thumbnailContent wow fadeInUp">
              <a href="course-single-left-sidebar.html"><img src="assets/img/home/courses/course-3.jpg" alt="image" class="img-responsive"></a>
              <div class="sticker bg-color-3">$50</div>
              <div class="caption border-color-3">
                <h3><a href="course-single-left-sidebar.html" class="color-3">Suspendisse a libero da.</a></h3>
                <ul class="list-unstyled">
                  <li><i class="fa fa-calendar-o" aria-hidden="true"></i>Age 2 to 4 Years</li>
                  <li><i class="fa fa-clock-o" aria-hidden="true"></i>9.00AM-11.00AM</li>
                </ul>
                <p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. </p>
                <ul class="list-inline btn-red">
                  <li><a href="cart-page.html" class="btn btn-primary "><i class="fa fa-shopping-basket " aria-hidden="true"></i>Add to Cart</a></li>
                  <li><a href="course-single-left-sidebar.html" class="btn btn-link"><i class="fa fa-angle-double-right" aria-hidden="true"></i> More</a></li>
                </ul>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-md-3 col-xs-12 block ">
            <div class="thumbnail thumbnailContent wow fadeInUp">
              <a href="course-single-left-sidebar.html"><img src="assets/img/home/courses/course-4.jpg" alt="image" class="img-responsive"></a>
              <div class="sticker bg-color-4">$50</div>
              <div class="caption border-color-4">
                <h3><a href="course-single-left-sidebar.html" class="color-4">Aenean cursus urna nec.</a></h3>
                <ul class="list-unstyled">
                  <li><i class="fa fa-calendar-o" aria-hidden="true"></i>Age 2 to 4 Years</li>
                  <li><i class="fa fa-clock-o" aria-hidden="true"></i>9.00AM-11.00AM</li>
                </ul>
                <p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. </p>
                <ul class="list-inline btn-sky">
                  <li><a href="cart-page.html" class="btn btn-primary "><i class="fa fa-shopping-basket " aria-hidden="true"></i>Add to Cart</a></li>
                  <li><a href="course-single-left-sidebar.html" class="btn btn-link"><i class="fa fa-angle-double-right" aria-hidden="true"></i> More</a></li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>-->

	<!---====================================
    ——— COLOR SECTION
    ===================================== 
    <section class="colorSection full-width clearfix bg-color-5 teamSection" id="ourTeam">
      <div class="container">
        <div class="sectionTitle text-center alt">
          <h2 class="wow fadeInUp">
            <span class="shape shape-left bg-color-3"></span>
            <span>Meet Our Teachers</span>
            <span class="shape shape-right bg-color-3"></span>
          </h2>
        </div>

        <div class="row">
          <div class="col-xs-12">
            <div class="owl-carousel teamSlider">
              <div class="slide wow fadeInUp">
                <div class="teamContent">
                  <div class="teamImage">
                    <img src="assets/img/home/team/team-1.jpg" alt="img" class="img-circle">
                    <div class="maskingContent">
                      <ul class="list-inline">
                        <li><a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                        <li><a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                        <li><a href="#"><i class="fa fa-google-plus" aria-hidden="true"></i></a></li>
                        <li><a href="#"><i class="fa fa-pinterest-p" aria-hidden="true"></i></a></li>
                      </ul>
                    </div>
                  </div>
                  <div class="teamInfo">
                    <h3><a href="teachers-details.html">Amanda Smith</a></h3>
                    <p>English Teacher</p>
                  </div>
                </div>
              </div>
              <div class="slide wow fadeInUp">
                <div class="teamContent">
                  <div class="teamImage">
                    <img src="assets/img/home/team/team-2.jpg" alt="img" class="img-circle">
                    <div class="maskingContent">
                      <ul class="list-inline">
                        <li><a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                        <li><a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                        <li><a href="#"><i class="fa fa-google-plus" aria-hidden="true"></i></a></li>
                        <li><a href="#"><i class="fa fa-pinterest-p" aria-hidden="true"></i></a></li>
                      </ul>
                    </div>
                  </div>
                  <div class="teamInfo">
                    <h3><a href="teachers-details.html">Katrina Owen</a></h3>
                    <p>History Teacher</p>
                  </div>
                </div>
              </div>
              <div class="slide wow fadeInUp">
                <div class="teamContent">
                  <div class="teamImage">
                    <img src="assets/img/home/team/team-3.jpg" alt="img" class="img-circle">
                    <div class="maskingContent">
                      <ul class="list-inline">
                        <li><a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                        <li><a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                        <li><a href="#"><i class="fa fa-google-plus" aria-hidden="true"></i></a></li>
                        <li><a href="#"><i class="fa fa-pinterest-p" aria-hidden="true"></i></a></li>
                      </ul>
                    </div>
                  </div>
                  <div class="teamInfo">
                    <h3><a href="teachers-details.html">Uzzal Hossain</a></h3>
                    <p>Math Teacher</p>
                  </div>
                </div>
              </div>
              <div class="slide wow fadeInUp">
                <div class="teamContent">
                  <div class="teamImage">
                    <img src="assets/img/home/team/team-4.jpg" alt="img" class="img-circle">
                    <div class="maskingContent">
                      <ul class="list-inline">
                        <li><a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                        <li><a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                        <li><a href="#"><i class="fa fa-google-plus" aria-hidden="true"></i></a></li>
                        <li><a href="#"><i class="fa fa-pinterest-p" aria-hidden="true"></i></a></li>
                      </ul>
                    </div>
                  </div>
                  <div class="teamInfo">
                    <h3><a href="teachers-details.html">Monica Dincule</a></h3>
                    <p>Languages Teacher</p>
                  </div>
                </div>
              </div>
              <div class="slide wow fadeInUp">
                <div class="teamContent">
                  <div class="teamImage">
                    <img src="assets/img/home/team/team-1.jpg" alt="img" class="img-circle">
                    <div class="maskingContent">
                      <ul class="list-inline">
                        <li><a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                        <li><a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                        <li><a href="#"><i class="fa fa-google-plus" aria-hidden="true"></i></a></li>
                        <li><a href="#"><i class="fa fa-pinterest-p" aria-hidden="true"></i></a></li>
                      </ul>
                    </div>
                  </div>
                  <div class="teamInfo">
										<h3><a href="teachers-details.html">Jenny Bryan</a></h3>
                    <p>Sciences Teacher</p>
                  </div>
                </div>
              </div>
              <div class="slide wow fadeInUp">
                <div class="teamContent">
                  <div class="teamImage">
                    <img src="assets/img/home/team/team-2.jpg" alt="img" class="img-circle">
                    <div class="maskingContent">
                      <ul class="list-inline">
                        <li><a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                        <li><a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                        <li><a href="#"><i class="fa fa-google-plus" aria-hidden="true"></i></a></li>
                        <li><a href="#"><i class="fa fa-pinterest-p" aria-hidden="true"></i></a></li>
                      </ul>
                    </div>
                  </div>
                  <div class="teamInfo">
                    <h3><a href="teachers-details.html">Amanda Stone</a></h3>
                    <p>English Teacher</p>
                  </div>
                </div>
              </div>
              <div class="slide wow fadeInUp">
                <div class="teamContent">
                  <div class="teamImage">
                    <img src="assets/img/home/team/team-3.jpg" alt="img" class="img-circle">
                    <div class="maskingContent">
                      <ul class="list-inline">
                        <li><a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                        <li><a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                        <li><a href="#"><i class="fa fa-google-plus" aria-hidden="true"></i></a></li>
                        <li><a href="#"><i class="fa fa-pinterest-p" aria-hidden="true"></i></a></li>
                      </ul>
                    </div>
                  </div>
                  <div class="teamInfo">
                    <h3><a href="teachers-details.html">Oliver Gierke</a></h3>
                    <p>Religion Teacher</p>
                  </div>
                </div>
              </div>
              <div class="slide wow fadeInUp">
                <div class="teamContent">
                  <div class="teamImage">
                    <img src="assets/img/home/team/team-4.jpg" alt="img" class="img-circle">
                    <div class="maskingContent">
                      <ul class="list-inline">
                        <li><a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                        <li><a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                        <li><a href="#"><i class="fa fa-google-plus" aria-hidden="true"></i></a></li>
                        <li><a href="#"><i class="fa fa-pinterest-p" aria-hidden="true"></i></a></li>
                      </ul>
                    </div>
                  </div>
                  <div class="teamInfo">
                    <h3><a href="teachers-details.html">Peter Krumins</a></h3>
                    <p>Computer Teacher</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>-->

	<!--====================================
    ——— WHITE SECTION
    ===================================== -->
    <section class="whiteSection full-width clearfix homeGallerySection" id="ourGallery">
      <div class="container">
        <div class="sectionTitle text-center">
          <h2 class="wow fadeInUp">
            <span class="shape shape-left bg-color-4"></span>
            <span>Actividades recientes</span>
            <span class="shape shape-right bg-color-4"></span>
          </h2>
        </div>

        <div class="row">
          <div class="col-xs-12 ">
            <div class="filter-container isotopeFilters wow fadeInUp">
              <ul class="list-inline filter">
                <li class="active"><a href="#" data-filter="*">Todo</a></li>
                <!--<li><a href="#" data-filter=".charity">Charity</a></li>
                <li><a href="#" data-filter=".nature">nature</a></li>
                <li><a href="#" data-filter=".children">children</a></li>-->
              </ul>
            </div>
          </div>
        </div>
				<div class="row isotopeContainer" id="container">
          <div class="col-md-3 col-sm-6 col-xs-12 isotopeSelector charity ">
            <article class="wow fadeInUp">
              <figure>
                <img src="assets/img/home/home_gallery/gallery_sm_1.jpeg" alt="image" class="img-rounded">
                <div class="overlay-background">
                  <div class="inner"></div>
                </div>
                <div class="overlay">
                  <a data-fancybox="images" href="assets/img/home/home_gallery/gallery_lg_1.jpeg">
                    <i class="fa fa-search-plus" aria-hidden="true"></i>
                  </a>
                </div>
              </figure>
            </article>
          </div>

          <div class="col-md-3 col-sm-6 col-xs-12 isotopeSelector nature ">
            <article class="wow fadeInUp">
              <figure>
                <img src="assets/img/home/home_gallery/gallery_sm_2.jpeg" alt="image" class="img-rounded">
                <div class="overlay-background">
                  <div class="inner"></div>
                </div>
                <div class="overlay">
                  <a data-fancybox="images" href="assets/img/home/home_gallery/gallery_lg_2.jpeg">
                    <i class="fa fa-search-plus" aria-hidden="true"></i>
                  </a>
                </div>
              </figure>
            </article>
          </div>

          <div class="col-md-3 col-sm-6 col-xs-12 isotopeSelector nature ">
            <article class="wow fadeInUp">
              <figure>
                <img src="assets/img/home/home_gallery/gallery_sm_3.jpeg" alt="image" class="img-rounded">
                <div class="overlay-background">
                  <div class="inner"></div>
                </div>
                <div class="overlay">
                  <a data-fancybox="images" href="assets/img/home/home_gallery/gallery_lg_3.jpeg">
                    <i class="fa fa-search-plus" aria-hidden="true"></i>
                  </a>
                </div>
              </figure>
            </article>
          </div>

          <div class="col-md-3 col-sm-6 col-xs-12 isotopeSelector charity">
            <article class="wow fadeInUp">
              <figure>
                <img src="assets/img/home/home_gallery/gallery_sm_4.jpeg" alt="image" class="img-rounded">
                <div class="overlay-background">
                  <div class="inner"></div>
                </div>
                <div class="overlay">
                  <a data-fancybox="images" href="assets/img/home/home_gallery/gallery_lg_4.jpeg">
                    <i class="fa fa-search-plus" aria-hidden="true"></i>
                  </a>
                </div>
              </figure>
            </article>
          </div>

        <!--  <div class="col-md-3 col-sm-6 col-xs-12 isotopeSelector nature">
            <article class="wow fadeInUp">
              <figure>
                <img src="assets/img/home/home_gallery/gallery_sm_5.jpg" alt="image" class="img-rounded">
                <div class="overlay-background">
                  <div class="inner"></div>
                </div>
                <div class="overlay">
                  <a data-fancybox="images" href="assets/img/home/home_gallery/gallery_lg_5.jpg">
                    <i class="fa fa-search-plus" aria-hidden="true"></i>
                  </a>
                </div>
              </figure>
            </article>
          </div>

          <div class="col-md-3 col-sm-6 col-xs-12 isotopeSelector children ">
            <article class="wow fadeInUp">
              <figure>
                <img src="assets/img/home/home_gallery/gallery_sm_6.jpg" alt="image" class="img-rounded">
                <div class="overlay-background">
                  <div class="inner"></div>
                </div>
                <div class="overlay">
                  <a data-fancybox="images" href="assets/img/home/home_gallery/gallery_lg_6.jpg">
                    <i class="fa fa-search-plus" aria-hidden="true"></i>
                  </a>
                </div>
              </figure>
            </article>
          </div>

          <div class="col-md-3 col-sm-6 col-xs-12 isotopeSelector children">
            <article class="wow fadeInUp">
              <figure>
                <img src="assets/img/home/home_gallery/gallery_sm_7.jpg" alt="image" class="img-rounded">
                <div class="overlay-background">
                  <div class="inner"></div>
                </div>
                <div class="overlay">
                  <a data-fancybox="images" href="assets/img/home/home_gallery/gallery_lg_7.jpg">
                    <i class="fa fa-search-plus" aria-hidden="true"></i>
                  </a>
                </div>
              </figure>
            </article>
          </div>

          <div class="col-md-3 col-sm-6 col-xs-12 isotopeSelector children ">
            <article class="wow fadeInUp">
              <figure>
                <img src="assets/img/home/home_gallery/gallery_sm_8.jpg" alt="image" class="img-rounded">
                <div class="overlay-background">
                  <div class="inner"></div>
                </div>
                <div class="overlay">
                  <a data-fancybox="images" href="assets/img/home/home_gallery/gallery_lg_8.jpg">
                    <i class="fa fa-search-plus" aria-hidden="true"></i>
                  </a>
                </div>
              </figure>
            </article>
          </div>-->
        </div>

      <!--  <div class="btnArea">
          <a href="photo-gallery.html" class="btn btn-primary">View more</a>
        </div>-->

      </div>
    </section>

	<!--====================================
    ——— COUNT UP SECTION
    ===================================== -->
    <section class="countUpSection">
      <div class="container">
        <div class="sectionTitleSmall wow fadeInUp">
          <h2>Estrategias</h2>
          <p>Lorem ipsum dolor sit amet consectetur adipisicing elit sed do eiusmod</p>
        </div>

        <div class="row">
					<div class="col-sm-3 col-xs-12">
            <div class="text-center wow fadeInUp">
              <div class="counter">1</div>
              <div class="counterInfo bg-color-1" style="width: 250px;">Abiertos y preestructurados</div>
            </div>
          </div>
					<div class="col-sm-3 col-xs-12">
            <div class="text-center wow fadeInUp">
              <div class="counter">2</div>
              <div class="counterInfo bg-color-2" style="width: 250px;">Momentos pedagógico</div>
            </div>
          </div>
					<div class="col-sm-3 col-xs-12">
            <div class="text-center wow fadeInUp">
              <div class="counter">3</div>
              <div class="counterInfo bg-color-3" style="width: 250px;">Desarrollo de Dimensiones</div>
            </div>
          </div>
					<div class="col-sm-3 col-xs-12">
            <div class="text-center wow fadeInUp">
              <div class="counter">4</div>
              <div class="counterInfo bg-color-4" style="width: 250px;">Sesiones de trabajo</div>
            </div>
          </div>
        </div>
      </div>
    </section>

	<!--====================================
    ——— WHITE SECTION
    ===================================== -->
    <section class="whiteSection full-width clearfix newsSection" id="latestNews">
      <div class="container">
        <div class="sectionTitle text-center">
          <h2 class="wow fadeInUp">
            <span class="shape shape-left bg-color-4"></span>
            <span>Últimas noticias</span>
            <span class="shape shape-right bg-color-4"></span>
          </h2>
        </div>

        <div class="row">
          <div class="col-sm-4 col-xs-12 block ">
            <div class="thumbnail thumbnailContent wow fadeInUp">
              <a href="javascript:void(0)"><img src="assets/img/home/news/news-1.png" alt="image" class="img-responsive"></a>
              <div class="sticker-round bg-color-1">10 <br>July</div>
              <div class="caption border-color-1">
                <h3><a href="javascript:void(0)" class="color-1">CONVOCATORIA ESPACIO EL MAESTRO TIENE LA PALABRA 2018.</a></h3>
                <ul class="list-inline">
                  <li><a href="javascript:void(0)"><i class="fa fa-user" aria-hidden="true"></i>Jone Doe</a></li>
                  <li><a href="javascript:void(0)"><i class="fa fa-comments-o" aria-hidden="true"></i>4 Comments</a></li>
                </ul>
                <p>Por medio de la presente se invita a participar en los Espacios de Apropiación social EL MAESTRO TIENE LA PALABRA 2018, próximos a realizarse en los municipios del departamento del Magdalena y en los cuales los docentes dan cuenta de sus procesos de acompañamiento a los grupos, redes y espacios de apropiación, de formación, investigación y de las reflexiones que realicen sobre el rol que desempeñan...</p>
                <ul class="list-inline btn-yellow">
                  <li><a href="javascript:void(0)" class="btn btn-link"><i class="fa fa-angle-double-right" aria-hidden="true"></i> Leer más...</a></li>
                </ul>
              </div>
            </div>
          </div>
          <div class="col-sm-4 col-xs-12 block ">
            <div class="thumbnail thumbnailContent wow fadeInUp">
              <a href="javascript:void(0)"><img src="assets/img/home/news/news-2.png" alt="image" class="img-responsive"></a>
              <div class="sticker-round bg-color-2">10 <br>July</div>
              <div class="caption border-color-2">
                <h3><a href="javascript:void(0)" class="color-2">GRUPOS DESTACADOS II FERIA DEPARTAMENTAL INNOVAMAG 2018.</a></h3>
                <ul class="list-inline">
                  <li><a href="javascript:void(0)"><i class="fa fa-user" aria-hidden="true"></i>Jone Doe</a></li>
                  <li><a href="javascript:void(0)"><i class="fa fa-comments-o" aria-hidden="true"></i>4 Comments</a></li>
                </ul>
                <p>Con un gran numero de asistentes y estudiantes se clausuró la II Feria Departamental Innovamag 2018 de Ciencia, tecnología e innovación realizada en la Escuela Superior Normal Maria Auxiliadora, evento que tuvo acogida los días 5 y 6 de abril del presente año, y en la cual se escogieron 8 grupos de investigación mejor valorados de estas ferias, los cuales nos representaran a nivel regional... </p>
                <ul class="list-inline btn-green">
                  <li><a href="javascript:void(0)" class="btn btn-link"><i class="fa fa-angle-double-right" aria-hidden="true"></i> Leer más...</a></li>
                </ul>
              </div>
            </div>
          </div>
          <div class="col-sm-4 col-xs-12 block ">
            <div class="thumbnail thumbnailContent wow fadeInUp">
              <a href="javascript:void(0)"><img src="assets/img/home/news/news-3.jpg" alt="image" class="img-responsive"></a>
              <div class="sticker-round bg-color-3">10 <br>July</div>
              <div class="caption border-color-3">
                <h3><a href="javascript:void(0)" class="color-3">RESULTADOS PRIMER CONCURSO “PREGUNTEMOS AL CIENTIFICO”.</a></h3>
                <ul class="list-inline">
                  <li><a href="javascript:void(0)"><i class="fa fa-user" aria-hidden="true"></i>Jone Doe</a></li>
                  <li><a href="javascript:void(0)"><i class="fa fa-comments-o" aria-hidden="true"></i>4 Comments</a></li>
                </ul>
                <p>El concurso Preguntemos al científico tuvo como objetivo que cada sede educativa redactara una pregunta dirigida a un científico o investigador de la red de apoyo de Ciclón y de estas se seleccionaran los estudiantes  que participarían el día 5 de abril  en las instalaciones de la Universidad del Magdalena en el marco del segundo Encuentro de la Red de Apoyo Programa Ciclón en el conversatorio... </p>
                <ul class="list-inline btn-red">
                  <li><a href="javascript:void(0)" class="btn btn-link"><i class="fa fa-angle-double-right" aria-hidden="true"></i> Leer más...</a></li>
                </ul>
              </div>
            </div>
          </div>
        </div>

       <!-- <div class="btnArea">
          <a href="blog-grid.html" class="btn btn-primary">View more</a>
        </div>-->

      </div>
    </section>

	<!--====================================
    ——— LIGHT SECTION
    ===================================== -->
    <section class="lightSection full-width clearfix homeContactSection" id="homeContactSection">
      <div class="container">
        <div class="row">
          <div class="col-sm-6 col-xs-12 ">
            <div class="homeContactContent wow fadeInUp">
              <h2>Dónde encontrarnos?</h2>
              <p>Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
              <address>
                <p><i class="fa fa-map-marker bg-color-1" aria-hidden="true"></i>9/4/C Ring Road,Garden Street Dhaka,Bangladesh-1200</p>
                <p><i class="fa fa-envelope bg-color-2" aria-hidden="true"></i><a href="mailto:hello@example.com">hello@example.com</a></p>
                <p><i class="fa fa-phone bg-color-4" aria-hidden="true"></i>3333 222 1111</p>
              </address>
            </div>
          </div>
          <div class="col-sm-6 col-xs-12">
            <div class="homeContactContent wow fadeInUp">
              <form action="#" method="POST" role="form">
                <div class="form-group">
                  <i class="fa fa-user"></i>
                  <input type="text" class="form-control border-color-1" id="exampleInputEmail1" placeholder="Primer nombre">
                </div>
                <div class="form-group">
                  <i class="fa fa-envelope" aria-hidden="true"></i>
                  <input type="text" class="form-control border-color-2" id="exampleInputEmail2" placeholder="Correo electrónico">
                </div>
                <div class="form-group">
                  <i class="fa fa-comments" aria-hidden="true"></i>
                  <textarea class="form-control border-color-4" placeholder="Escribe tu mensaje"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Enviar mensaje</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>
  @endsection