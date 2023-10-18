    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <script src="assets/bootstrap/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/popper.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/function.js?<?=rand()?>"></script>
    <script src="assets/js/toastr.min.js"></script>
    <script src="assets/js/plugins/lazyload/jquery.lazy.min.js"></script>

    <link href="assets/css/bootstrap4-toggle.min.css" rel="stylesheet">
    <script src="assets/js/bootstrap4-toggle.min.js"></script>

    <?php
    if(isset($js_data) and count($js_data)>0){
        foreach($js_data AS $js_file) echo '<script src="'.$js_file.'"></script>';
    }
    ?>
    <script type="text/javascript">
        $(function() {
            $('.lazy').lazy();
        });
        
        <?php if($this->session->flashdata('success')){ ?>
            toastr.success("<?php echo $this->session->flashdata('success'); ?>");
        <?php }
        if($this->session->flashdata('error')){  ?>
            toastr.error("<?php echo $this->session->flashdata('error'); ?>");
        <?php }
        if($this->session->flashdata('warning')){  ?>
            toastr.warning("<?php echo $this->session->flashdata('warning'); ?>");
        <?php }
        if($this->session->flashdata('info')){  ?>
            toastr.info("<?php echo $this->session->flashdata('info'); ?>");
        <?php } ?>
    </script>
    
    </body>
</html>
