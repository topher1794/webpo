
<footer class="main-footer sticky-bottom p-1">
  <!-- <div class="float-right d-none d-sm-block">
      <b>Version</b> 2.0
    </div> -->
  <div align="center">
    <strong>&copy; <?= $PROJECT_YEAR ?> <a href="#"><?= $PROJECT_TITLE ?></a>.</strong> All rights reserved.
  </div>
</footer>

<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
  
</aside>

</div>

<!-- jQuery -->
<script src="Assets/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="Assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- overlayScrollbars -->
<script src="Assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="Assets/dist/js/adminlte.min.js"></script>

<!-- AdminLTE for demo purposes -->
<script src="Assets/dist/js/demo.js"></script>

<!-- SweetAlert2 -->
<script src="Assets/plugins/sweetalert2/sweetalert2.all.min.js"></script>
<script src="Assets/plugins/sweetalert2/sweetalert2.min.js"></script>
<!-- InputMask -->
<script src="Assets/plugins/moment/moment.min.js"></script>
<script src="Assets/plugins/inputmask/jquery.inputmask.min.js"></script>
<!-- Toastr -->
<script src="Assets/plugins/toastr/toastr.min.js"></script>
<!-- DataTables  & Plugins -->
<script src="Assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="Assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="Assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="Assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="Assets/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="Assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="Assets/plugins/jszip/jszip.min.js"></script>
<script src="Assets/plugins/pdfmake/pdfmake.min.js"></script>
<script src="Assets/plugins/pdfmake/vfs_fonts.js"></script>
<script src="Assets/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="Assets/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="Assets/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<!-- Select2 -->
<script src="Assets/plugins/select2/js/select2.full.min.js"></script>
<!-- Ekko Lightbox -->
<!-- <script src="Assets/plugins/ekko-lightbox/ekko-lightbox.min.js"></script> -->
<script src="Assets/plugins/featherlight-1.7.13/release/featherlight.min.js" type="text/javascript" charset="utf-8"></script>

<script src="Assets/plugins/chart.js/Chart.min.js"></script>

<!-- InputMask -->
<script src="Assets/plugins/moment/moment.min.js"></script>
<script src="Assets/plugins/inputmask/jquery.inputmask.min.js"></script>

<!-- date-range-picker -->
<script src="Assets/plugins/daterangepicker/daterangepicker.js"></script>


<script type="module" src="Assets/js/ClsAsync.js"></script>


<?php if (in_array($controller, array("po"))) { ?>
  <?php if (in_array($action, array("newpo"))) { ?>
    <script type="module"  src="Assets/js/NewPo.js"></script>
  <?php } ?>
<?php } ?>


</body>

</html>