<?php
$active_page = "Claims";
?>
<!-- INCLUDING THE FILE THAT HOLDS THE CORE STRUCTURE OF THE PAGE -->
@extends('merchant.app')

<!-- INCLUDING CUSTOM SCRIPTS AND STYLES -->
@section('top_scripts_and_styles')
    <link rel="stylesheet" href="/css/custom.css">
    <script src="/js/custom/merchant/config.js"></script>
    <script src="/js/custom/merchant/auth.js"></script>
@endsection()

@section('main_content_and_footer')
<div class="main-content">
    <div class="container-fluid">
        <div class="row" id="stats_info">

            <div class="col-12 col-sm-6 col-xl">
                <!-- Card -->
                <div class="card box-margin">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <!-- Title -->
                                <h6 class="text-uppercase font-14">
                                    Balance
                                </h6>

                                <div class="d-flex justify-content-center">
                                    <div id="loader3" class="customloader" ></div>
                                </div> 
                                <!-- Heading -->
                                <span class="font-24 text-dark mb-0" id="balance_now" style="display: none;">
                                    
                                </span>
                            </div>

                            <div class="col-auto">
                                <!-- Icon -->
                                <div class="icon">
                                    <img src="img/bg-img/icon-8.png" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-12 col-sm-6 col-xl">
                <!-- Card -->
                <div class="card box-margin">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <!-- Title -->
                                <h6 class="text-uppercase font-14">
                                    Pending Claims
                                </h6>

                                <div class="d-flex justify-content-center">
                                    <div id="loader2" class="customloader" ></div>
                                </div> 
                                <!-- Heading -->
                                <span class="font-24 text-dark mb-0" id="pending_redemptions" style="display: none;">
                                    
                                </span>
                            </div>

                            <div class="col-auto">
                                <!-- Icon -->
                                <div class="icon">
                                    <img src="img/bg-img/icon-10.png" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- / .row -->

        <div class="row">
            
            <div class="col-xl-12 box-margin height-card">
                <div class="card card-body">
                    <h4 class="card-title">Make Claim</h4>
                    <p>All Claims Are Paid Via Momo To Your Number Registered On Loyalty</p>
                    <div class="row">
                        <div class="col-sm-12 col-xs-12">
                            <div class="d-flex justify-content-center">
                                <div id="loader" style="display: none;" class="customloader"></div>
                            </div> 
                            <form id="form">
                                <div class="form-group">
                                    <label for="claim_amount">Amount</label>
                                    <input type="text" id="claim_amount" name="claim_amount" required class="form-control" placeholder="Enter Amount">
                                </div>
                                <button type="submit" class="btn btn-primary mr-2">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Footer Area -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <!-- Footer Area -->
                <footer class="footer-area d-sm-flex justify-content-center align-items-center justify-content-between">
                    <!-- Copywrite Text -->
                    <div class="copywrite-text">
                        <p>Created by @<a href="#">ShrinqGhana</a></p>
                    </div>
                    <div class="fotter-icon text-center">
                        <a href="#" class="action-item mr-2" data-toggle="tooltip" title="Facebook">
                            <i class="fa fa-facebook" aria-hidden="true"></i>
                        </a>
                        <a href="#" class="action-item mr-2" data-toggle="tooltip" title="Twitter">
                            <i class="fa fa-twitter" aria-hidden="true"></i>
                        </a>
                        <a href="#" class="action-item mr-2" data-toggle="tooltip" title="Pinterest">
                            <i class="fa fa-pinterest-p" aria-hidden="true"></i>
                        </a>
                        <a href="#" class="action-item mr-2" data-toggle="tooltip" title="Instagram">
                            <i class="fa fa-instagram" aria-hidden="true"></i>
                        </a>
                    </div>
                </footer>
            </div>
        </div>
    </div>
</div>

@endsection

@section('bottom_scripts')
    

    <!-- Must needed plugins to the run this Template -->
    <script src="/js/core.js"></script>
    <script src="/js/bundle.js"></script>

    <!-- These plugins only need for the run this page -->
    <script src="/js/default-assets/mini-event-calendar.min.js"></script>
    <script src="/js/default-assets/apexchart.min.js"></script>

    <!-- Inject JS -->
    <script src="/js/default-assets/setting.js"></script>
    <script src="/js/default-assets/active.js"></script>

    <!-- CUSTOMJS -->
    <script src="/js/custom/merchant/claims/claims.js"></script>
    <script type="text/javascript">
        get_claims_info();
    </script>
    
@endsection