<?php
$active_page = "Dashboard";
?>
<!-- INCLUDING THE FILE THAT HOLDS THE CORE STRUCTURE OF THE PAGE -->
@extends('app')

<!-- INCLUDING CUSTOM SCRIPTS AND STYLES -->
@section('top_scripts_and_styles')
    <link rel="stylesheet" href="/css/custom.css">
    <script src="/js/custom/config.js"></script>
    <script src="/js/custom/signedinuser/auth.js"></script>
@endsection()

@section('main_content_and_footer')
<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-sm-6 col-xl">
                <!-- Card -->
                <div class="card box-margin">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <!-- Title -->
                                <h6 class="text-uppercase font-14">
                                    Budget
                                </h6>

                                <!-- Heading -->
                                <span class="font-24 text-dark mb-0">
                                    $24,500
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
                                    Total Hours
                                </h6>
                                <!-- Heading -->
                                <span class="font-24 text-dark mb-0">
                                    663.5
                                </span>
                            </div>
                            <div class="col-auto">
                                <!-- Icon -->
                                <div class="icon">
                                    <img src="img/bg-img/icon-9.png" alt="">
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
                                    Progress
                                </h6>
                                <div class="row align-items-center no-gutters">
                                    <div class="col-auto">
                                        <!-- Heading -->
                                        <span class="font-24 text-dark mr-2">
                                            84.5%
                                        </span>
                                    </div>
                                    <div class="col">
                                        <!-- Progress -->
                                        <div class="progress h-5">
                                            <div class="progress-bar bg-primary" role="progressbar" style="width: 85%" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
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

            <div class="col-12 col-sm-6 col-xl">
                <!-- Card -->
                <div class="card box-margin">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <!-- Title -->
                                <h6 class="font-14 text-uppercase">
                                    Cost/Unit
                                </h6>
                                <!-- Heading -->
                                <span class="font-24 text-dark">
                                    $7.50
                                </span>
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
        <!-- / .row -->

        <div class="row">
            <!-- Order Summary -->
            <div class="col-lg-8 col-12 box-margin height-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-50">
                            <h4 class="card-title mb-0">Order <span class="break-320-480-none">Summary</span></h4>
                            <div class="d-flex">
                                <button type="button" class="btn btn-primary btn-sm mr-2">Month</button>
                                <button type="button" class="btn btn-secondary btn-sm">Week</button>
                            </div>
                        </div>
                    </div>

                    <!-- Order Chart -->
                    <div class="card-body p-0">
                        <div class="card-content">
                            <div id="order-summary-chart"></div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Visits Chart Area -->
            <div class="col-lg-4 box-margin height-card ">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <h4 class="card-title">Visits of 2019</h4>
                            <div id="radial-chart"></div>
                            <ul class="list-inline d-flex justify-content-around mb-0">
                                <li> <i class="fa fa-circle mr-1 text-danger"></i>Target</li>
                                <li> <i class="fa fa-circle text-success mr-1"></i>Mart</li>
                                <li> <i class="fa fa-circle text-primary mr-1"></i>Ebay</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notification Area -->
            <div class="col-md-6 col-lg-4 box-margin height-card ">
                <div class="card">
                    <div class="card-body">
                        <div class="kt-portlet kt-portlet--tabs kt-portlet--height-fluid">
                            <div class="kt-portlet__head">
                                <div class="kt-portlet__head-label">
                                    <h3 class="card-title mb-0">
                                        Notifications
                                    </h3>
                                </div>
                                <div class="kt-portlet__head-toolbar">
                                    <ul class="nav nav-tabs nav-tabs-line nav-tabs-line-brand nav-tabs-bold" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" data-toggle="tab" href="#kt_portlet_tabs_1_1_1_content" role="tab" aria-selected="true">
                                                Week
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#kt_portlet_tabs_1_1_3_content" role="tab" aria-selected="false">
                                                Month
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="kt-portlet__body" id="notificationBox">
                                <div class="tab-content">
                                    <div class="tab-pane fade active show" id="kt_portlet_tabs_1_1_1_content" role="tabpanel">
                                        <div class="kt-scroll ps ps--active-y" data-scroll="true" data-mobile-height="350">
                                            <!--Begin::Timeline -->
                                            <div class="kt-timeline">
                                                <!--Begin::Item -->
                                                <div class="kt-timeline__item kt-timeline__item--success">
                                                    <div class="kt-timeline__item-section">
                                                        <div class="kt-timeline__item-section-border">
                                                            <div class="kt-timeline__item-section-icon">
                                                                <i class="icon_mail_alt text-primary font-weight-bold"></i>
                                                            </div>
                                                        </div>
                                                        <span class="kt-timeline__item-datetime">02:30 PM</span>
                                                    </div>

                                                    <a href="#" class="kt-timeline__item-text">Xviten created new layout whith tens of new options for Keen Admin panel</a>
                                                    <div class="kt-timeline__item-info">
                                                        HTML,CSS,VueJS
                                                    </div>
                                                </div>
                                                <!--End::Item -->

                                                <!--Begin::Item -->
                                                <div class="kt-timeline__item kt-timeline__item--danger">
                                                    <div class="kt-timeline__item-section">
                                                        <div class="kt-timeline__item-section-border">
                                                            <div class="kt-timeline__item-section-icon">
                                                                <i class="icon_genius font-weight-bold text-danger"></i>
                                                            </div>
                                                        </div>
                                                        <span class="kt-timeline__item-datetime">01:20 AM</span>
                                                    </div>

                                                    <a href="#" class="kt-timeline__item-text">
                                                        New secyrity alert by Firewall &amp; order to take aktion on User Preferences
                                                    </a>
                                                    <div class="kt-timeline__item-info">
                                                        Security, Fieewall
                                                    </div>
                                                </div>
                                                <!--End::Item -->

                                                <!--Begin::Item -->
                                                <div class="kt-timeline__item kt-timeline__item--brand">
                                                    <div class="kt-timeline__item-section">
                                                        <div class="kt-timeline__item-section-border">
                                                            <div class="kt-timeline__item-section-icon">
                                                                <i class="icon_gift_alt font-weight-bold text-success"></i>
                                                            </div>
                                                        </div>
                                                        <span class="kt-timeline__item-datetime">Yestardey</span>
                                                    </div>

                                                    <a href="#" class="kt-timeline__item-text">
                                                        FlyMore design mock-ups been uploadet by designers Bob, Naomi, Richard
                                                    </a>
                                                    <div class="kt-timeline__item-info">
                                                        PSD, Sketch, AJ
                                                    </div>
                                                </div>
                                                <!--End::Item -->


                                                <!--Begin::Item -->
                                                <div class="kt-timeline__item kt-timeline__item--danger">
                                                    <div class="kt-timeline__item-section">
                                                        <div class="kt-timeline__item-section-border">
                                                            <div class="kt-timeline__item-section-icon">
                                                                <i class="icon_genius font-weight-bold text-danger"></i>
                                                            </div>
                                                        </div>
                                                        <span class="kt-timeline__item-datetime">01:20 AM</span>
                                                    </div>

                                                    <a href="#" class="kt-timeline__item-text">
                                                        New secyrity alert by Firewall &amp; order to take aktion on User Preferences
                                                    </a>
                                                    <div class="kt-timeline__item-info">
                                                        Security, Fieewall
                                                    </div>
                                                </div>
                                                <!--End::Item -->

                                                <!--Begin::Item -->
                                                <div class="kt-timeline__item kt-timeline__item--warning">
                                                    <div class="kt-timeline__item-section">
                                                        <div class="kt-timeline__item-section-border">
                                                            <div class="kt-timeline__item-section-icon">
                                                                <i class="icon_datareport font-weight-bold text-warning"></i>
                                                            </div>
                                                        </div>
                                                        <span class="kt-timeline__item-datetime">Aug 13,2019</span>
                                                    </div>

                                                    <a href="#" class="kt-timeline__item-text">
                                                        Meeting with Ken Digital Corp ot Unit14, 3 Edigor Buildings, George Street, Loondon<br> England, BA12FJ
                                                    </a>
                                                    <div class="kt-timeline__item-info">
                                                        Meeting, Customer
                                                    </div>
                                                </div>
                                                <!--End::Item -->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="kt_portlet_tabs_1_1_3_content" role="tabpanel">
                                        <div class="kt-scroll ps" data-scroll="true" style="height: 420px; overflow: hidden;" data-mobile-height="350">
                                            <!--Begin::Timeline -->
                                            <div class="kt-timeline">
                                                <!--Begin::Item -->
                                                <div class="kt-timeline__item kt-timeline__item--success">
                                                    <div class="kt-timeline__item-section">
                                                        <div class="kt-timeline__item-section-border">
                                                            <div class="kt-timeline__item-section-icon">
                                                                <i class="icon_mail_alt text-primary font-weight-bold"></i>
                                                            </div>
                                                        </div>
                                                        <span class="kt-timeline__item-datetime">01:30 PM</span>
                                                    </div>

                                                    <a href="#" class="kt-timeline__item-text">Created new layout whith tens of new options for Keen Admin panel</a>
                                                    <div class="kt-timeline__item-info">
                                                        Vue,CSS,VueJS
                                                    </div>
                                                </div>
                                                <!--End::Item -->

                                                <!--Begin::Item -->
                                                <div class="kt-timeline__item kt-timeline__item--danger">
                                                    <div class="kt-timeline__item-section">
                                                        <div class="kt-timeline__item-section-border">
                                                            <div class="kt-timeline__item-section-icon">
                                                                <i class="icon_genius font-weight-bold text-danger"></i>
                                                            </div>
                                                        </div>
                                                        <span class="kt-timeline__item-datetime">01:20 AM</span>
                                                    </div>

                                                    <a href="#" class="kt-timeline__item-text">
                                                        Secyrity alert by Firewall &amp; order to take aktion on User Preferences
                                                    </a>
                                                    <div class="kt-timeline__item-info">
                                                        Security, Fieewall
                                                    </div>
                                                </div>
                                                <!--End::Item -->

                                                <!--Begin::Item -->
                                                <div class="kt-timeline__item kt-timeline__item--brand">
                                                    <div class="kt-timeline__item-section">
                                                        <div class="kt-timeline__item-section-border">
                                                            <div class="kt-timeline__item-section-icon">
                                                                <i class="icon_gift_alt font-weight-bold text-success"></i>
                                                            </div>
                                                        </div>
                                                        <span class="kt-timeline__item-datetime">Yestardey</span>
                                                    </div>

                                                    <a href="#" class="kt-timeline__item-text">
                                                        FlyMore design mock-ups been uploadet by designers Bob, Naomi, Richard
                                                    </a>
                                                    <div class="kt-timeline__item-info">
                                                        Hadrla, Sketch, AJ
                                                    </div>
                                                </div>
                                                <!--End::Item -->


                                                <!--Begin::Item -->
                                                <div class="kt-timeline__item kt-timeline__item--danger">
                                                    <div class="kt-timeline__item-section">
                                                        <div class="kt-timeline__item-section-border">
                                                            <div class="kt-timeline__item-section-icon">
                                                                <i class="icon_genius font-weight-bold text-danger"></i>
                                                            </div>
                                                        </div>
                                                        <span class="kt-timeline__item-datetime">01:20 AM</span>
                                                    </div>

                                                    <a href="#" class="kt-timeline__item-text">
                                                        New secyrity alert by Firewall &amp; order to take aktion on User Preferences
                                                    </a>
                                                    <div class="kt-timeline__item-info">
                                                        Security, Fieewall
                                                    </div>
                                                </div>
                                                <!--End::Item -->

                                                <!--Begin::Item -->
                                                <div class="kt-timeline__item kt-timeline__item--warning">
                                                    <div class="kt-timeline__item-section">
                                                        <div class="kt-timeline__item-section-border">
                                                            <div class="kt-timeline__item-section-icon">
                                                                <i class="icon_datareport font-weight-bold text-warning"></i>
                                                            </div>
                                                        </div>
                                                        <span class="kt-timeline__item-datetime">Aug 15,2019</span>
                                                    </div>

                                                    <a href="#" class="kt-timeline__item-text">
                                                        With Xita Digital Corp ot Unit14, 3 Edigor Buildings, George Street, Loondon<br> England, BA12FJ
                                                    </a>
                                                    <div class="kt-timeline__item-info">
                                                        Meeting, Clint
                                                    </div>
                                                </div>
                                                <!--End::Item -->
                                                <!--End::Item -->
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Latest Update Area -->
            <div class="col-md-6 col-lg-4 box-margin height-card">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Latest Update</h5>
                        <div class="transaction-area">
                            <div class="d-flex flex-row list-group-item align-items-center justify-content-between">
                                <div class="media d-flex justify-content-center align-items-center">
                                    <div class="icon-section bg-primary-soft">
                                        <i class="fa fa-file-code-o"></i>
                                    </div>
                                    <div class="media-body">
                                        <h6 class="mb-1 font-15">New Users</h6>
                                        <p class="mb-0 font-13">6 un 19, 10:25 AM</p>
                                    </div>
                                </div>

                                <div class="amount txt-gray-5">
                                    <p class="mb-0">57000</p>
                                </div>
                            </div>

                            <div class="d-flex flex-row list-group-item align-items-center justify-content-between">
                                <div class="media d-flex justify-content-center align-items-center">
                                    <div class="icon-section bg-danger-soft">
                                        <i class="fa fa-newspaper-o"></i>
                                    </div>
                                    <div class="media-body">
                                        <h6 class="mb-1 font-15">Page Views</h6>
                                        <p class="mb-0 font-13">9 Jul 19, 03:43 Pm</p>
                                    </div>
                                </div>

                                <div class="amount txt-gray-5">
                                    <p class="mb-0">79496</p>
                                </div>
                            </div>

                            <div class="d-flex flex-row list-group-item align-items-center justify-content-between">
                                <div class="media d-flex justify-content-center align-items-center">
                                    <div class="icon-section bg-success-soft">
                                        <i class="fa fa-clone"></i>
                                    </div>
                                    <div class="media-body">
                                        <h6 class="mb-1 font-15">Page Sessions</h6>
                                        <p class="mb-0 font-13">6 Apr 19, 02:34 PM</p>
                                    </div>
                                </div>

                                <div class="amount txt-gray-5">
                                    <p class="mb-0">47381</p>
                                </div>
                            </div>

                            <div class="d-flex flex-row list-group-item align-items-center justify-content-between">
                                <div class="media d-flex justify-content-center align-items-center">
                                    <div class="icon-section bg-danger-soft">
                                        <i class="fa fa-newspaper-o"></i>
                                    </div>
                                    <div class="media-body">
                                        <h6 class="mb-1 font-15">Page Views</h6>
                                        <p class="mb-0 font-13">9 Jul 19, 03:43 Pm</p>
                                    </div>
                                </div>

                                <div class="amount txt-gray-5">
                                    <p class="mb-0">79496</p>
                                </div>
                            </div>

                            <div class="d-flex flex-row list-group-item align-items-center justify-content-between">
                                <div class="media d-flex justify-content-center align-items-center">
                                    <div class="icon-section bg-danger-soft">
                                        <i class="icon-wallet"></i>
                                    </div>
                                    <div class="media-body">
                                        <h6 class="mb-1 font-15">Click Through</h6>
                                        <p class="mb-0 font-13">22 jan 19, 07:21 AM</p>
                                    </div>
                                </div>

                                <div class="amount txt-gray-5">
                                    <p class="mb-0">78294</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Calendar Area-->
            <div class="col-md-6 col-lg-4 box-margin height-card">
                <div class="card">
                    <div class="card-body">
                        <a href="calendar.html">
                            <h4 class="card-title mb-15">View full calenar <i class="fa fa-angle-double-right ml-1"></i></h4>
                        </a>
                    </div>
                    <div class="card-body p-0">
                        <div id="calendar2"></div>
                    </div>
                </div>
            </div>

            <!-- Projects of the Month -->
            <div class="col-md-6 col-xl-6 height-card box-margin">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title mb-30">Projects</h6>
                        <div class="table-responsive">
                            <table class="table table-nowrap table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Project</th>
                                        <th>Date</th>
                                        <th>Due Date</th>
                                        <th>Status</th>
                                        <th>Assign</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>jQuery</td>
                                        <td>07/07/2079</td>
                                        <td>26/04/2079</td>
                                        <td><span class="badge badge-danger">Released</span></td>
                                        <td>Hilana</td>
                                    </tr>
                                    <tr>
                                        <td>Angular</td>
                                        <td>07/07/2079</td>
                                        <td>26/04/2079</td>
                                        <td><span class="badge badge-success">Review</span></td>
                                        <td>Henson</td>
                                    </tr>
                                    <tr>
                                        <td>ReactJs</td>
                                        <td>07/05/2079</td>
                                        <td>10/09/2079</td>
                                        <td><span class="badge badge-info">Pending</span></td>
                                        <td>Jensen</td>
                                    </tr>
                                    <tr>
                                        <td>VueJs</td>
                                        <td>07/07/2079</td>
                                        <td>31/11/2079</td>
                                        <td><span class="badge badge-warning">Work in Progress</span>
                                        </td>
                                        <td>Burton</td>
                                    </tr>
                                    <tr>
                                        <td>Laravel</td>
                                        <td>07/07/2079</td>
                                        <td>31/12/2079</td>
                                        <td><span class="badge badge-danger">Coming soon</span></td>
                                        <td>Yaretzi</td>
                                    </tr>
                                    <tr>
                                        <td>NodeJs</td>
                                        <td>07/07/2079</td>
                                        <td>31/12/2079</td>
                                        <td><span class="badge badge-primary">Coming soon</span></td>
                                        <td>Henson</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Support Ticket -->
            <div class="col-lg-6 col-xl-6 height-card box-margin">
                <div class="card">
                    <div class="card-header bg-transparent user-area d-flex align-items-center justify-content-between">
                        <h6 class="card-title mb-0">Support Tickets</h6>
                        <ul class="nav nav-tabs mb-0" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link show active" id="sell-03-tab" data-toggle="tab" href="#sell-03" role="tab" aria-controls="sell-03" aria-selected="true">Today</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mr-0" id="rent-04-tab" data-toggle="tab" href="#rent-04" role="tab" aria-controls="rent-04" aria-selected="false">Week</a>
                            </li>
                        </ul>
                    </div>

                    <!-- Tab -->
                    <div class="card-body">
                        <div class="tab-content" id="ticketList">
                            <div class="tab-pane fade active show" id="sell-03" role="tabpanel" aria-labelledby="sell-03-tab">
                                <ul class="ticket-data-list">
                                    <li>
                                        <div class="avatar-area d-flex">
                                            <span class="avatar avatar-pending primary-color mr-3">Z</span>
                                            <div class="avatar-text">
                                                <div class="d-flex align-items-center">
                                                    <h6 class="mr-2 mb-0">Zara Smith</h6>
                                                    <p class="ticket-time mb-0 font-12">(9:40 PM)</p>
                                                </div>
                                                <p>Lorem ipsum dolor amet, consectetur adipisicing elit Nisila Lorem ipsum dolor sit amet, consectetur adipisicing.</p>
                                                <span class="badge badge-pill badge-primary">Open</span>
                                            </div>
                                        </div>
                                    </li>

                                    <li>
                                        <div class="avatar-area d-flex">
                                            <span class="avatar bg-info mr-3">A</span>
                                            <div class="avatar-text">
                                                <div class="d-flex align-items-center">
                                                    <h6 class="mr-2 mb-0">Ajoy Das</h6>
                                                    <p class="ticket-time mb-0 font-12">(8:40 PM)</p>
                                                </div>
                                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nisi eos recusandae obcaecati repudiandae harum quae.</p>
                                                <span class="badge badge-pill badge-secondary">Closed</span>
                                            </div>
                                        </div>
                                    </li>

                                    <li>
                                        <div class="avatar-area d-flex">
                                            <span class="avatar avatar-online bg-success mr-3">J</span>
                                            <div class="avatar-text">
                                                <div class="d-flex align-items-center">
                                                    <h6 class="mr-2 mb-0">Jhon Deo</h6>
                                                    <p class="ticket-time mb-0 font-12">(7:40 PM)</p>
                                                </div>
                                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit Lorem ipsum dolor Lorem ipsum dolor sit amet, consectetur...</p>
                                                <span class="badge badge-pill badge-warning">Pending</span>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>

                            <!-- Tab 2 -->
                            <div class="tab-pane fade" id="rent-04" role="tabpanel" aria-labelledby="rent-04-tab">
                                <ul class="ticket-data-list">
                                    <li>
                                        <div class="avatar-area d-flex">
                                            <span class="avatar avatar-pending bg-danger mr-3">J</span>
                                            <div class="avatar-text">
                                                <div class="d-flex align-items-center">
                                                    <h6 class="mr-2 mb-0">Jhon Deo</h6>
                                                    <p class="ticket-time mb-0 font-12">(9:40 PM)</p>
                                                </div>
                                                <p>Lorem ipsum dolor amet, consectetur adipisicing elit Nisila Lorem ipsum dolor sit amet, consectetur adipisicing.</p>
                                                <span class="badge badge-pill badge-primary">Open</span>
                                            </div>
                                        </div>
                                    </li>

                                    <li>
                                        <div class="avatar-area d-flex">
                                            <span class="avatar bg-success mr-3">L</span>
                                            <div class="avatar-text">
                                                <div class="d-flex align-items-center">
                                                    <h6 class="mr-2 mb-0">Lara Jhon</h6>
                                                    <p class="ticket-time mb-0 font-12">(8:40 PM)</p>
                                                </div>
                                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nisi eos recusandae obcaecati repudiandae harum quae.</p>
                                                <span class="badge badge-pill badge-secondary">Closed</span>
                                            </div>
                                        </div>
                                    </li>

                                    <li>
                                        <div class="avatar-area d-flex">
                                            <span class="avatar avatar-online primary-color mr-3">J</span>
                                            <div class="avatar-text">
                                                <div class="d-flex align-items-center">
                                                    <h6 class="mr-2 mb-0">Jhon Deo</h6>
                                                    <p class="ticket-time mb-0 font-12">(7:40 PM)</p>
                                                </div>
                                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit Lorem ipsum dolor Lorem ipsum dolor sit amet, consectetur...</p>
                                                <span class="badge badge-pill badge-warning">Pending</span>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Summary Area -->
            <div class="col-12 box-margin">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">Order Summary</h6>
                        <!-- Table -->
                        <div class="table-responsive">
                            <table class="table table-nowrap">
                                <thead>
                                    <tr>
                                        <th>Invoice</th>
                                        <th>Order</th>
                                        <th>Amount</th>
                                        <th>Client</th>
                                        <th>Modified</th>
                                        <th>Taxes</th>
                                        <th class="text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th scope="row">
                                            <button type="button" class="btn btn-sm btn-secondary btn-icon rounded-pill">
                                                <span class="btn-inner--icon"><i class="fa fa-download"></i></span>
                                                <span class="btn-inner--text">Invoice</span>
                                            </button>
                                        </th>
                                        <td class="order">
                                            <span class="font-14 mb-0">10/09/2018</span>
                                            <span class="d-block font-13">ABC 00023</span>
                                        </td>
                                        <td>2569854</td>
                                        <td>
                                            <span class="client">Apple Inc</span>
                                        </td>
                                        <td>18/11/19</td>
                                        <td>
                                            <span class="taxes text-sm mb-0">$1.115,45</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center justify-content-end">
                                                <button type="button" class="btn btn-rounded btn-outline-success">
                                                    <span class="btn-inner--text">Paid</span>
                                                </button>
                                                <!-- Actions -->
                                                <div class="actions ml-3">
                                                    <a href="#" class="action-item mr-2" data-toggle="tooltip" title="" data-original-title="Edit">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                    <a href="#" class="action-item mr-2" data-toggle="tooltip" title="" data-original-title="Archive">
                                                        <i class="fa fa-archive"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">
                                            <button type="button" class="btn btn-sm btn-secondary btn-icon rounded-pill">
                                                <span class="btn-inner--icon"><i class="fa fa-download"></i></span>
                                                <span class="btn-inner--text">Invoice</span>
                                            </button>
                                        </th>
                                        <td class="order">
                                            <span class="font-14 mb-0">10/09/2018</span>
                                            <span class="d-block font-13">ABC 00023</span>
                                        </td>
                                        <td>2569854</td>
                                        <td>
                                            <span class="client">Apple Inc</span>
                                        </td>
                                        <td>12/11/19</td>
                                        <td>
                                            <span class="taxes text-sm mb-0">$1.115,45</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center justify-content-end">
                                                <button type="button" class="btn btn-rounded btn-outline-warning">
                                                    <span class="btn-inner--text">Pay now</span>
                                                </button>
                                                <!-- Actions -->
                                                <div class="actions ml-3">
                                                    <a href="#" class="action-item mr-2" data-toggle="tooltip" title="" data-original-title="Edit">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                    <a href="#" class="action-item mr-2" data-toggle="tooltip" title="" data-original-title="Archive">
                                                        <i class="fa fa-archive"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">
                                            <button type="button" class="btn btn-sm btn-secondary btn-icon rounded-pill">
                                                <span class="btn-inner--icon"><i class="fa fa-download"></i></span>
                                                <span class="btn-inner--text">Invoice</span>
                                            </button>
                                        </th>
                                        <td class="order">
                                            <span class="font-14 mb-0">10/09/2018</span>
                                            <span class="d-block font-13">ABC 00023</span>
                                        </td>
                                        <td>2569854</td>
                                        <td>
                                            <span class="client">Apple Inc</span>
                                        </td>
                                        <td>20/11/19</td>
                                        <td>
                                            <span class="taxes text-sm mb-0">$1.115,45</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center justify-content-end">
                                                <button type="button" class="btn btn-rounded btn-outline-danger">
                                                    <span class="btn-inner--text">Delayed</span>
                                                </button>
                                                <!-- Actions -->
                                                <div class="actions ml-3">
                                                    <a href="#" class="action-item mr-2" data-toggle="tooltip" title="" data-original-title="Edit">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                    <a href="#" class="action-item mr-2" data-toggle="tooltip" title="" data-original-title="Archive">
                                                        <i class="fa fa-archive"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th scope="row">
                                            <button type="button" class="btn btn-sm btn-secondary btn-icon rounded-pill">
                                                <span class="btn-inner--icon"><i class="fa fa-download"></i></span>
                                                <span class="btn-inner--text">Invoice</span>
                                            </button>
                                        </th>
                                        <td class="order">
                                            <span class="font-14 mb-0">10/09/2018</span>
                                            <span class="d-block font-13">ABC 00023</span>
                                        </td>
                                        <td>2569854</td>
                                        <td>
                                            <span class="client">Apple Inc</span>
                                        </td>
                                        <td>28/11/19</td>
                                        <td>
                                            <span class="taxes text-sm mb-0">$1.115,45</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center justify-content-end">
                                                <button type="button" class="btn btn-rounded btn-outline-success">
                                                    <span class="btn-inner--text">Paid</span>
                                                </button>
                                                <!-- Actions -->
                                                <div class="actions ml-3">
                                                    <a href="#" class="action-item mr-2" data-toggle="tooltip" title="" data-original-title="Edit">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                    <a href="#" class="action-item mr-2" data-toggle="tooltip" title="" data-original-title="Archive">
                                                        <i class="fa fa-archive"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">
                                            <button type="button" class="btn btn-sm btn-secondary btn-icon rounded-pill">
                                                <span class="btn-inner--icon"><i class="fa fa-download"></i></span>
                                                <span class="btn-inner--text">Invoice</span>
                                            </button>
                                        </th>
                                        <td class="order">
                                            <span class="font-14 mb-0">10/09/2018</span>
                                            <span class="d-block font-13">ABC 00023</span>
                                        </td>
                                        <td>2569854</td>
                                        <td>
                                            <span class="client">Apple Inc</span>
                                        </td>
                                        <td>29/11/19</td>
                                        <td>
                                            <span class="taxes text-sm mb-0">$1.115,45</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center justify-content-end">
                                                <button type="button" class="btn btn-rounded btn-outline-success">
                                                    <span class="btn-inner--text">Paid</span>
                                                </button>
                                                <!-- Actions -->
                                                <div class="actions ml-3">
                                                    <a href="#" class="action-item mr-2" data-toggle="tooltip" title="" data-original-title="Edit">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                    <a href="#" class="action-item mr-2" data-toggle="tooltip" title="" data-original-title="Archive">
                                                        <i class="fa fa-archive"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
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
    

    <!-- Must needed plugins to the run this Template -->
    <script src="/js/core.js"></script>
    <script src="/js/bundle.js"></script>

    <!-- These plugins only need for the run this page -->
    <script src="/js/default-assets/mini-event-calendar.min.js"></script>
    <script src="/js/default-assets/apexchart.min.js"></script>

    <!-- Inject JS -->
    <script src="/js/default-assets/setting.js"></script>
    <script src="/js/default-assets/active.js"></script>

    <!-- Custom js for this page -->
    <script src="/js/default-assets/mini-calendar-active.js"></script>
    <script src="/js/default-assets/dashboard-active.js"></script>
@endsection