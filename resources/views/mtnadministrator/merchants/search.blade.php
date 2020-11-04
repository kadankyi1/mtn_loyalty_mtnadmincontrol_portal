<?php
$active_page = "Merchants";
?>
<!-- INCLUDING THE FILE THAT HOLDS THE CORE STRUCTURE OF THE PAGE -->
@extends('mtnadministrator.app')

<!-- INCLUDING CUSTOM SCRIPTS AND STYLES -->
@section('top_scripts_and_styles')
    <link rel="stylesheet" href="/css/custom.css">
    <script src="/js/custom/mtnadministrator/config.js"></script>
    <script src="/js/custom/mtnadministrator/auth.js"></script>
@endsection()

@section('main_content_and_footer')
            <!-- Main Content Area -->
            <div class="main-content">
                
    <div class="container-fluid">
        <!-- Form row -->
        <div class="row" id="search_holder">
            <div class="col-xl-12 box-margin height-card">
                <div class="card card-body">
                    <h4 class="card-title">Find Merchant</h4>
                    <div class="row">
                        <div class="col-sm-12 col-xs-12">
                            <div class="d-flex justify-content-center">
                                <div id="loader" class="customloader" style="display: none;"></div>
                            </div> 
                            <form id="search_form">
                                <div class="form-group">
                                    <label for="merchant_name">Merchant Phone Number</label>
                                    <input type="text" id="merchant_phone_number" name="merchant_phone_number" class="form-control" placeholder="Enter Merchant Phone Number">
                                </div>
                                <button type="submit" class="btn btn-primary mr-2">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="container-fluid" id="profile_merchant" style="display: none;">
        <div class="row">
            <div class="col-12 col-md-4">
                <div class="card mb-30">
                    <img src="https://images.squarespace-cdn.com/content/v1/5800d9668419c2e2687202fb/1490082806442-CSGARE6B10QOBTX4MSZM/ke17ZwdGBToddI8pDm48kFooXVAIdtyZE6mtXN3sLjpZw-zPPgdn4jUwVcJE1ZvWEtT5uBSRWt4vQZAgTJucoTqqXjS3CfNDSuuf31e0tVHWOYm4wyK8I8kOZLgz5KpEQjP0el7Sq3g3LAIv9ljyiFtO8nJtk629tZGIWiyY3XQ/image-asset.png" class="profile-cover-img" alt="thumb">
                </div>
                <!-- ./profile -->

                <div class="card address mb-30">
                    <div class="card-body">
                        <h4 class="font-16 mb-15" >Vode For <b><span id="merchant_name2"></span></b></h4>
                        <div class="mt-3 d-flex align-items-center">
                            <i class="fa fa-map pr-2 align-self-start"></i>
                            <h6 class="font-14 mb-0" id="merchant_location"></h6>
                        </div>
                    </div>
                </div>
                <!-- ./address -->
            </div>

            <div class="col-12 col-md-8">
                <div class="profile-crm-area">
                    <div class="card mb-30">
                        <div class="card-body">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs profile-tab" id="myTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active show" id="basic-tab" data-toggle="tab" href="#basic" role="tab" aria-controls="basic" aria-selected="true">Details</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="myTabContent">
                                <!--first tab-->
                                <div class="tab-pane fade active show" id="basic" role="tabpanel" aria-labelledby="basic-tab">
                                    <div class="card-body">
                                        <div class="row profile-row">
                                            <div class="col-xs-5 col-sm-3">
                                                <span class="profile-cat">Name</span>
                                            </div>
                                            <div class="col-xl-7 col-sm-9">
                                                <span class="profile-info" id="merchant_name"></span>
                                            </div>
                                        </div>
                                        <div class="row profile-row">
                                            <div class="col-xs-5 col-sm-3">
                                                <span class="profile-cat">Phone</span>
                                            </div>
                                            <div class="col-xl-7 col-sm-9">
                                                <span class="profile-info" id="merchant_phone_number2"></span>
                                            </div>
                                        </div>
                                        <div class="row profile-row">
                                            <div class="col-xs-5 col-sm-3">
                                                <span class="profile-cat">Email</span>
                                            </div>
                                            <div class="col-xl-7 col-sm-9">
                                                <span class="profile-info" id="merchant_email"></span>
                                            </div>
                                        </div>
                                        <div class="row profile-row">
                                            <div class="col-xs-5 col-sm-3">
                                                <span class="profile-cat">Pending Claims</span>
                                            </div>
                                            <div class="col-xl-7 col-sm-9">
                                                <span class="profile-info"  id="merchant_pending_claims"></span>
                                            </div>
                                        </div>
                                        <div class="row profile-row">
                                            <div class="col-xs-5 col-sm-3">
                                                <span class="profile-cat">Balance</span>
                                            </div>
                                            <div class="col-xl-7 col-sm-9">
                                                <span class="profile-info">Ghc <span id="merchant_balance"></span> </span>
                                            </div>
                                        </div>

                                        <div class="row profile-row">
                                            <div class="col-xs-5 col-sm-3">
                                                <span class="profile-cat">Created On</span>
                                            </div>
                                            <div class="col-xl-7 col-sm-9">
                                                <span class="profile-info" id="created_at"></span>
                                            </div>
                                        </div>
                                        <div class="row profile-row">
                                            <div class="col-xs-5 col-sm-3">
                                                <span class="profile-cat">Created By</span>
                                            </div>
                                            <div class="col-xl-7 col-sm-9">
                                                <span class="profile-info" id="admin_name"></span>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
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
                                <p>Created by @<a href="#">Antina</a></p>
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
    

<script src="/js/core.js"></script>
<script src="/js/bundle.js"></script>

<!-- Inject JS -->
<script src="/js/default-assets/setting.js"></script>
<script src="/js/default-assets/active.js"></script>

<!-- Custom js -->
<script src="js/default-assets/demo.datatable-init.js"></script>

<!-- CUSTOMJS -->
<script src="/js/custom/mtnadministrator/merchants/merchants.js"></script>

@endsection