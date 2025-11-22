</div>






</div>
</div>
<footer class="sticky-footer bg-white">
    <div class="container my-auto">
        <div class="copyright text-center my-auto">
            <span>Copyright &copy; Your Website <?= date('Y') ?></span>
        </div>
    </div>
</footer>
</div>
</div>
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <a class="btn btn-primary" href="login.html">Logout</a>
            </div>
        </div>
    </div>
</div>

<script src="<?= base_url ?>Assets/vendor/jquery/jquery.min.js"></script>
<script src="<?= base_url ?>Assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<script src="<?= base_url ?>Assets/vendor/jquery-easing/jquery.easing.min.js"></script>

<script src="<?= base_url ?>Assets/js/sb-admin-2.min.js"></script>
<script src="<?= base_url ?>Assets/js/dataTables.min.js"></script>

<script src="<?= base_url ?>Assets/js/sweetalert2.all.min.js"></script>

<script>
    const base_url = "<?= base_url ?>";
</script>

<script src="<?= base_url ?>Assets/js/facturar.js"></script>

<script src="<?= base_url ?>Assets/js/funciones.js"></script>

</body>

</html>