@extends('admin.layouts.app')
@section('content')
    <!-- Header Starts -->
    @include('admin.layouts.header')
    <!-- Header Ends -->
    <div class="container-fluid reg-bg2">
        <section class="container">
            <div class="row how-it-works">
                <h1><span class="back-btn" style="margin-left: 84px;" onclick="history.back()"></span>The value of getting verified with <a href="#"> Dialectb2b.com !</a></h1>
                <div class="d-flex flex-wrap">
               
                    <div class="sales-boxes box-shadow-how d-flex flex-column justify-content-center align-items-center">
                        <img src="{{ asset('assets/images/boosted-ico.svg') }}" alt="">
                        <h3 class="mt-3">Boosted Credibility</h3>
                        <p class="mt-2">The verified badge symbolizes authenticity and trustworthiness, leading to stronger connections and increased client trust.
                        </p>
                    </div>
                    
                    <div class="sales-boxes box-shadow-how d-flex flex-column justify-content-center align-items-center">
                        <img src="{{ asset('assets/images/visibility-ico.svg') }}" alt="">
                        <h3 class="mt-3">Enhanced Visibility</h3>
                        <p class="mt-2">Verified accounts stand out in searches and recommendations, facilitating easier engagement with clients and partners.
                        </p>
                    </div>
    
    
                    <div class="sales-boxes box-shadow-how d-flex flex-column justify-content-center align-items-center">
                        <img src="{{ asset('assets/images/protection-ico.svg') }}" alt="">
                        <h3 class="mt-3">Protection Against Impersonation</h3>
                        <p class="mt-2">Verification prevents malicious entities from creating fake accounts, safeguarding brand integrity and ensuring a secure online environment.
                        </p>
                    </div>
    
                    <div class="sales-boxes box-shadow-how d-flex flex-column justify-content-center align-items-center">
                        <img src="{{ asset('assets/images/risk-management-ico.svg') }}" alt="">
                        <h3 class="mt-3">Improved Risk Management</h3>
                        <p class="mt-2">Verification aids in identifying genuine entities, reducing the risk of scams and deceptive practices, and showcasing a commitment to maintaining a safe online presence.</p>
                    </div>
    
                </div>

                {{-- <h1 class="mt-5">Here's how this process might work</h1>

                <div class="d-flex flex-wrap position-relative mb-5">

                    <div class="how-process-main d-flex flex-column align-items-center">
                         <div style="height: 141px;"><img src="{{ asset('assets/images/login-admin-ico.svg') }}" alt=""></div>
                         <div class="process-count d-flex align-items-center justify-content-center">1 <div class="vertical-count-line"></div></div>
                        <h3 class="mt-3">Login to the <br>
                            Admin Account</h3>
                        <p class="mt-2">Access the admin account on Dialectb2b.com to initiate the verification process.

                        </p>
                    </div>
                    
                    <div class="how-process-main d-flex flex-column align-items-center ">
                        <div style="height: 141px;"><img src="{{ asset('assets/images/subscribe-ico.svg') }}" alt=""></div>
                        <div class="process-count d-flex align-items-center justify-content-center">2</div>
                        <h3 class="mt-3">Transfer for <br>
                            Verification</h3>
                        <p class="mt-2">Transfer $1 through the company account via the payment gateway to complete the verification process. Note: Transactions from individual bank accounts will not be accepted for verification.
                            
                        </p>
                    </div>
    
    
                    <div class="how-process-main d-flex flex-column align-items-center">
                        <div style="height: 141px;"><img src="{{ asset('assets/images/transaction-details-ico.svg') }}" alt=""></div>
                        <div class="process-count d-flex align-items-center justify-content-center">3</div>
                        <h3 class="mt-3">Now it’s <br>
                            our turn</h3>
                        <p class="mt-2">Upon completion of the payment, Dialectb2b.com receives and reviews the transaction details, including the company name associated with the sender's account.

                        </p>
                    </div>
    
                    <div class="how-process-main d-flex flex-column align-items-center">
                        <div style="height: 141px;"><img src="{{ asset('assets/images/verify-genuiness-ico.svg') }}" alt=""></div>
                        <div class="process-count d-flex align-items-center justify-content-center">4</div>
                        <h3 class="mt-3">Verification of<br>
                            Genuineness</h3>
                        <p class="mt-2">The presence of the company name in the transaction details verifies the genuineness of the company, confirming the authenticity of the transaction.</p>
                    </div>

                    <div class="how-process-main d-flex flex-column  align-items-center">
                        <div style="height: 141px;"><img src="{{ asset('assets/images/confirm-badge-ico.svg') }}" alt=""></div>
                        <div class="process-count d-flex align-items-center justify-content-center">5</div>
                        <h3 class="mt-3">Confirmation<br>
                            and Badge</h3>
                        <p class="mt-2">Once the transaction details are confirmed, Dialectb2b.com provides a "Verified" badge on the company's home page, indicating successful verification.</p>
                    </div>
                </div> --}}
                    
                <div class="form-group proceed-btn" style="text-align: right;">
                    <a href="{{ route('admin.paymentVerification.info1') }}" class="btn btn-secondary">Proceed</a>
                </div>
                
            </div>
        </section>
    </div>
    <script>

        function myFunction() {
            document.getElementById("myDropdown").classList.toggle("show");
        }

        window.onclick = function (event) {
            if (!event.target.matches('.dropbtn')) {
                var dropdowns = document.getElementsByClassName("dropdown-content");
                var i;
                for (i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
        }




        $(document).ready(function () {
            $('.nav-expand-ico').click(function () {
                var toggleWidth = $(".side-nav-main").width() == 285 ? "64px" : "285px";
                $('.side-nav-main').animate({ width: toggleWidth });
            });

            $(".nav-expand-ico").click(function () {
                $("#logo-toogle").toggleClass("hide-logo");
            });

            $(".nav-expand-ico").click(function () {
                $(this).toggleClass("nav-close-ico");
            });

        });



        function openCity(evt, cityName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }
            document.getElementById(cityName).style.display = "block";
            evt.currentTarget.className += " active";
        }

        function openCity2(evt, cityName) {
            var i, tabcontent2, tablinks2;
            tabcontent2 = document.getElementsByClassName("tabcontent2");
            for (i = 0; i < tabcontent2.length; i++) {
                tabcontent2[i].style.display = "none";
            }
            tablinks2 = document.getElementsByClassName("tablinks2");
            for (i = 0; i < tablinks2.length; i++) {
                tablinks2[i].className = tablinks2[i].className.replace(" active", "");
            }
            document.getElementById(cityName).style.display = "block";
            evt.currentTarget.className += " active";
        }





        var x, i, j, l, ll, selElmnt, a, b, c;
        /*look for any elements with the class "custom-select":*/
        x = document.getElementsByClassName("custom-select");
        l = x.length;
        for (i = 0; i < l; i++) {
            selElmnt = x[i].getElementsByTagName("select")[0];
            ll = selElmnt.length;
            /*for each element, create a new DIV that will act as the selected item:*/
            a = document.createElement("DIV");
            a.setAttribute("class", "select-selected");
            a.innerHTML = selElmnt.options[selElmnt.selectedIndex].innerHTML;
            x[i].appendChild(a);
            /*for each element, create a new DIV that will contain the option list:*/
            b = document.createElement("DIV");
            b.setAttribute("class", "select-items select-hide");
            for (j = 1; j < ll; j++) {
                /*for each option in the original select element,
                create a new DIV that will act as an option item:*/
                c = document.createElement("DIV");
                c.innerHTML = selElmnt.options[j].innerHTML;
                c.addEventListener("click", function (e) {
                    /*when an item is clicked, update the original select box,
                    and the selected item:*/
                    var y, i, k, s, h, sl, yl;
                    s = this.parentNode.parentNode.getElementsByTagName("select")[0];
                    sl = s.length;
                    h = this.parentNode.previousSibling;
                    for (i = 0; i < sl; i++) {
                        if (s.options[i].innerHTML == this.innerHTML) {
                            s.selectedIndex = i;
                            h.innerHTML = this.innerHTML;
                            y = this.parentNode.getElementsByClassName("same-as-selected");
                            yl = y.length;
                            for (k = 0; k < yl; k++) {
                                y[k].removeAttribute("class");
                            }
                            this.setAttribute("class", "same-as-selected");
                            break;
                        }
                    }
                    h.click();
                });
                b.appendChild(c);
            }
            x[i].appendChild(b);
            a.addEventListener("click", function (e) {
                /*when the select box is clicked, close any other select boxes,
                and open/close the current select box:*/
                e.stopPropagation();
                closeAllSelect(this);
                this.nextSibling.classList.toggle("select-hide");
                this.classList.toggle("select-arrow-active");
            });
        }
        function closeAllSelect(elmnt) {
            /*a function that will close all select boxes in the document,
            except the current select box:*/
            var x, y, i, xl, yl, arrNo = [];
            x = document.getElementsByClassName("select-items");
            y = document.getElementsByClassName("select-selected");
            xl = x.length;
            yl = y.length;
            for (i = 0; i < yl; i++) {
                if (elmnt == y[i]) {
                    arrNo.push(i)
                } else {
                    y[i].classList.remove("select-arrow-active");
                }
            }
            for (i = 0; i < xl; i++) {
                if (arrNo.indexOf(i)) {
                    x[i].classList.add("select-hide");
                }
            }
        }
        /*if the user clicks anywhere outside the select box,
        then close all select boxes:*/
        document.addEventListener("click", closeAllSelect);

    </script>


    <script type="text/javascript">
        $(window).on('load', function() {
            $('#selected-categories').modal('show');
        });
    </script>
@endsection