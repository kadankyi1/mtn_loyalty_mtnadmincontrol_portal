<?php
$active_page = "Dashboard";
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
        <div class="row" id="stats_info2">

            <div class="col-4 col-sm-4 col-xl">
                <!-- Card -->
                <div class="card box-margin">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <!-- Title -->
                                <h6 class="text-uppercase font-14">
                                    YOUR VCODE
                                </h6>

                                <div class="d-flex justify-content-center">
                                    <div id="vcode_loader" class="customloader" ></div>
                                </div> 
                                <!-- Heading -->
                                <span class="font-24 text-dark mb-0" id="vcode_img" style="display: none;">
                                    <img  id="real_vcode_img"/>
                                </span>
                            </div>

                            <div class="col-auto">
                                <!-- Icon -->
                                <div class="icon">
                                    <img src="img/bg-img/icon-13.png" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-8 col-sm-8 col-xl">
            
                <div class="col-12 col-sm-6 col-xl" style="display: none">
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
                                    <h6 class="font-14 text-uppercase">
                                        Pts Per Cedi<br>High-Value Customers
                                    </h6>
                                    <div class="d-flex justify-content-center">
                                        <div id="hvc_rate_loader" class="customloader" ></div>
                                    </div> 
                                    <div class="row align-items-center no-gutters">
                                        <div class="col-auto">
                                            <!-- Heading -->
                                            <span class="font-24 text-dark mr-0" id="hvc_rate_holder" style="display: none;">
                                                <span id="hvc_rate">100</span> Pts = GH¢1
                                            </span>
                                        </div>
                                    </div>
                                </div>
    
                                <div class="col-auto">
                                    <!-- Icon -->
                                    <div class="icon">
                                        <img src="img/bg-img/icon-11.png" alt="">
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
                                        Pts Per Cedi<br>Normal Customers
                                    </h6>
    
                                    <div class="d-flex justify-content-center">
                                        <div id="nc_rate_loader" class="customloader" ></div>
                                    </div> 
                                    <!-- Heading -->
                                    <div class="row align-items-center no-gutters">
                                        <div class="col-auto">
                                            <!-- Heading -->
                                            <span class="font-24 text-dark mr-0" id="nc_rate_holder" style="display: none;">
                                               <span id="hvc_rate">100</span> Pts =  GH¢1
                                            </span>
                                        </div>
                                    </div>
                                </div>
    
                                <div class="col-auto">
                                    <!-- Icon -->
                                    <div class="icon">
                                        <img src="img/bg-img/icon-11.png" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
    
            </div>
        </div>

        <!-- / .row -->

        <div class="row">
            <!-- Projects of the Month -->
            <div class="col-md-12 col-xl-12 height-card box-margin">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title mb-30">Redemptions</h6>
                        <div class="table-responsive">
                            <table class="table table-nowrap table-hover mb-0" id="dataTableExample">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Customer Phone</th>
                                        <th>Rate</th>
                                        <th>Redeemed-Amount</th>
                                        <th>Redeemed-Points</th>
                                        <th>Status</th>
                                        <th>Time</th>
                                    </tr>
                                </thead>
                                <tbody id="table_body_list">
                                </tbody>
                            </table>
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
    <script src="/js/custom/merchant/dashboard/dashboard.js"></script>
    <script type="text/javascript">
        get_dashboard_info();
    </script>
    
@endsection