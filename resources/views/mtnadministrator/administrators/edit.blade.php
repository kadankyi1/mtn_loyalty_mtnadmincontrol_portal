<?php
$active_page = "Administrator";
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
<div class="main-content">
    <!-- Basic Form area Start -->
    <div class="container-fluid">
        <!-- Form row -->
        <div class="row">
            <div class="col-xl-12 box-margin height-card">
                <div class="card card-body">
                    <h4 class="card-title">Update Administrator</h4>
                    <div class="row">
                        <div class="col-sm-12 col-xs-12">
                            <div class="d-flex justify-content-center">
                                <div id="loader" class="customloader"></div>
                            </div> 
                            <form id="updateform">
                                <div class="form-group">
                                    <label for="admin_surname">Last Name</label>
                                    <input type="text" id="admin_surname" name="admin_surname" class="form-control" placeholder="Enter Last Name">
                                </div>
                                <div class="form-group">
                                    <label for="admin_firstname">First Name</label>
                                    <input type="text" id="admin_firstname" name="admin_firstname" class="form-control" placeholder="Enter First Name">
                                </div>
                                <div class="row">
                                    <div class="col-sm-3 col-xs-3">
                                        <div class="form-group">
                                            <label for="admin_add_admin">Add Admins</label>
                                            <select name="admin_add_admin" class="form-control">
                                                <option value="admin_add_admin">Yes</option>
                                                <option value="">No</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 col-xs-3">
                                        <div class="form-group">
                                            <label for="admin_view_admins">View Admins</label>
                                            <select name="admin_view_admins" class="form-control">
                                                <option value="admin_view_admins">Yes</option>
                                                <option value="">No</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 col-xs-3">
                                        <div class="form-group">
                                            <label for="admin_update_admin">Update Admins</label>
                                            <select name="admin_update_admin" class="form-control">
                                                <option value="admin_update_admin">Yes</option>
                                                <option value="">No</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 col-xs-3">
                                        <div class="form-group">
                                            <label for="admin_add_merchant">Add Merchant</label>
                                            <select name="admin_add_merchant" class="form-control">
                                                <option value="admin_add_merchant">Yes</option>
                                                <option value="">No</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 col-xs-3">
                                        <div class="form-group">
                                            <label for="admin_update_merchant">Update Merchant</label>
                                            <select name="admin_update_merchant" class="form-control">
                                                <option value="admin_update_merchant">Yes</option>
                                                <option value="">No</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 col-xs-3">
                                        <div class="form-group">
                                            <label for="admin_view_merchant">View Merchant</label>
                                            <select name="admin_view_merchant" class="form-control">
                                                <option value="admin_view_merchant">Yes</option>
                                                <option value="">No</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 col-xs-3">
                                        <div class="form-group">
                                            <label for="admin_view_claims">View Claims</label>
                                            <select name="admin_view_claims" class="form-control">
                                                <option value="admin_view_claims">Yes</option>
                                                <option value="">No</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="admin_pin">PIN</label>
                                    <input type="password" id="admin_pin" name="admin_pin" class="form-control" placeholder="PIN">
                                </div>
                                <button type="submit" class="btn btn-primary mr-2">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->
    </div>

    <!-- Footer Area -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <!-- Footer Area -->
                <footer class="footer-area d-sm-flex justify-content-center align-items-center justify-content-between">
                    <!-- Copywrite Text -->
                    <div class="copywrite-text">
                        <p>Created by @<a href="#">Shrinq Ghana</a></p>
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
<script src="/js/default-assets/basic-form.js"></script>
<script src="/js/default-assets/file-upload.js"></script>

<!-- CUSTOMJS -->
<script src="/js/custom/mtnadministrator/administrators/administrators.js"></script>

<script type="text/javascript">
    var i = '<?php echo intval($administrator_id); ?>';
    get_this_admin('<?php echo intval($administrator_id); ?>');
  </script>
@endsection