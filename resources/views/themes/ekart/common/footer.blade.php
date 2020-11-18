<body>
    <footer class="footer-area footer--light">
        <div class="footer-big">
            <div class="container">
                <div class="row">
                    <div class="col-md-3 ">
                        <div class="footer-widget d-flex justify-content-center">
                            <div class="widget-about">
                                <div class="col-12">
                                    <a href="{{ route('home') }}"><img src="{{ asset('images/headerlogo.png') }}"  alt="Logo"></a>
                                </div>
                                <div class="col-12 text-left">
                                    <div class="google-apple1">
                                        <a href="https://play.google.com"><img src="{{ theme('images/google1.png') }}" alt="Google Play Store"></img></a>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="row">
                                        <div class="social-button">
                                            <ul>
                                                <li class="social-icon">
                                                    <a href="https://www.instagram.com"><em class="fa fa-instagram"></em></a>
                                                </li>
                                                <li class="social-icon">
                                                    <a href="https://www.whatsapp.com"><em class="fa fa-whatsapp"></em></a>
                                                </li>
                                                <li class="social-icon">
                                                    <a href="https://www.facebook.com"><em class="fa fa-facebook"></em></a>
                                                </li>
                                                <li class="social-icon">
                                                    <a href="https://plus.google.com"><em class="fa fa-google-plus"></em></a>
                                                </li>
                                                <li class="social-icon">
                                                    <a href="https://www.linkedin.com"><em class="fa fa-linkedin"></em></a>
                                                </li>
                                                <li class="social-icon">
                                                    <a href="https://twitter.com"> <em class="fa fa-twitter"></em></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="footer-widget text-center d-flex justify-content-center">
                            <div class="footer-menu text-left no-padding">
                                <h4 class="footer-widget-title m-0">CUSTOMER SERVICES</h4>
                                <ul>
                                    <li><a href="{{ route('page', 'about') }}">About Us</a>
                                    <li><a href="{{ route('page', 'tnc') }}">Terms and Conditions</a>
                                    <li><a href="{{ route('page', 'faq') }}">FAQ</a>
                                    <li><a href="{{ route('page', 'privacy-policy') }}">Privacy Policy</a>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="footer-widget text-center d-flex justify-content-center">
                            <div class="footer-menu text-left no-padding">
                                <h4 class="footer-widget-title m-0">CONTACT US</h4>
                                <ul>
                                    <li><a class="noHover">WhatsApp us : 9033646589</a>
                                    <li><a class="noHover">Call Us : 1800 890 1222</a>
                                    <li><a class="noHover">8:00 AM to 8:00 PM, 365 days</a>
                                    <li><a class="noHover">email : info@ekart.com</a>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="footer-widget">
                            <div class="footer-menu text-left no-padding">
                                <h4 class="footer-widget-title m-0 row justify-content-center">NEWSLETTER</h4>
                                <div class="row justify-content-center">
                                <em class="fa fa-envelope-open-o fa-3x envelope mb-1" aria-hidden="true"></em>
                                </div>
                                <h5 class="mt-2 text-muted d-flex justify-content-center">Subscribe To Our Newsletter</h5>
                                <div class="well1">
                                    <form action="{{ route('newsletter') }}" method="POST" class="ajax-form">
                                        @csrf
                                        <div class="formResponse"></div>
                                        <div class="input-group">
                                            <input class="btn btn-lg border border-info" name="email" id="email" type="email" placeholder="Your Email" required>
                                            <button class="btn btn btn-lg" type="submit" name="submit" value="submit">SUBSCRIBE</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="line-footer">
                            <hr>
                            <div class="text-center">
                                <p class="copyright-text">Copyright &copy; 2020 made by
                                    <a href="https://wrteam.in/" class="text-primary name"> WRTeam.</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</body>
<!-- Firebase App (the core Firebase SDK) is always required and must be listed first -->
<script src="https://www.gstatic.com/firebasejs/7.16.0/firebase-app.js"></script>

<!-- If you enabled Analytics in your project, add the Firebase SDK for Analytics -->
<script src="https://www.gstatic.com/firebasejs/7.16.0/firebase-analytics.js"></script>

<!-- Add Firebase products that you want to use -->
<script src="https://www.gstatic.com/firebasejs/7.16.0/firebase-auth.js"></script>
<script src="https://www.gstatic.com/firebasejs/7.16.0/firebase-firestore.js"></script>
<script>
    // Your web app's Firebase configuration
    var firebaseConfig = {
        apiKey: "AIzaSyBll8aaTh5bXQq70Y_M4PR9Pd8iN3p-Mug",
        authDomain: "v2sdigitel-idya.firebaseapp.com",
        databaseasset: "https://v2sdigitel-idya.firebaseio.com",
        projectId: "v2sdigitel-idya",
        storageBucket: "v2sdigitel-idya.appspot.com",
        messagingSenderId: "596540358893",
        appId: "1:596540358893:web:ae35ee3f7f75cc28f88ce4",
        measurementId: "G-GWCMWT8VP0"
    };
    // Initialize Firebase
    firebase.initializeApp(firebaseConfig);
   
</script>
</html>