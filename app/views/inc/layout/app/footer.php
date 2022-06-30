            </div> <!-- content -->
                <!-- Footer Start -->
                <footer class="footer">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-6">
                                <script>document.write(new Date().getFullYear())</script> © Mack Software Solutions
                            </div>
                        </div>
                    </div>
                </footer><!-- end Footer -->
            </div><!-- End Page content -->
        </div><!-- END wrapper -->
        
        <!-- bundle -->
        <script src="<?php echo URLROOT; ?>/dist/js/vendor.min.js"></script>
        <script src="<?php echo URLROOT; ?>/dist/js/app.min.js"></script>
        <?php if(isset($data['has_datatable']) && $data['has_datatable'] === true) : ?>
            <!-- third party js -->
            <script src="<?php echo URLROOT; ?>/dist/js/vendor/jquery.dataTables.min.js"></script>
            <script src="<?php echo URLROOT; ?>/dist/js/vendor/dataTables.bootstrap5.js"></script>
            <script src="<?php echo URLROOT; ?>/dist/js/vendor/dataTables.responsive.min.js"></script>
            <script src="<?php echo URLROOT; ?>/dist/js/vendor/responsive.bootstrap5.min.js"></script>
            <script src="<?php echo URLROOT; ?>/dist/js/vendor/dataTables.buttons.min.js"></script>
            <script src="<?php echo URLROOT; ?>/dist/js/vendor/buttons.bootstrap5.min.js"></script>
            <script src="<?php echo URLROOT; ?>/dist/js/vendor/buttons.html5.min.js"></script>
            <script src="<?php echo URLROOT; ?>/dist/js/vendor/buttons.flash.min.js"></script>
            <script src="<?php echo URLROOT; ?>/dist/js/vendor/buttons.print.min.js"></script>
            <script src="<?php echo URLROOT; ?>/dist/js/vendor/dataTables.keyTable.min.js"></script>
            <script src="<?php echo URLROOT; ?>/dist/js/vendor/dataTables.select.min.js"></script>
        <?php endif; ?>
