<!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
<script src="assets/bootstrap/js/jquery.min.js"></script>
<script src="assets/bootstrap/js/popper.min.js"></script>
<script src="assets/bootstrap/js/bootstrap.min.js"></script>
<script src="assets/js/general-function.js?<?=rand()?>"></script>
<script src="assets/js/toastr.min.js"></script>
<script src="assets/js/plugins/lazyload/jquery.lazy.min.js"></script>

<link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>

<?php
	//If JS Data exists, load files
	if(isset($js_data) and count($js_data)>0){
		foreach($js_data AS $js_file) echo '<script src="'.$js_file.'"></script>';
	}
?>
<script type="text/javascript">
    $(function() {
        $('.lazy').lazy();
    });
    
    <?php if($this->session->flashdata('success')){ ?>
        toastr.success("<?=$this->session->flashdata('success'); ?>");
    <?php }
    if($this->session->flashdata('error')){  ?>
        toastr.error("<?=$this->session->flashdata('error'); ?>");
    <?php }
    if($this->session->flashdata('warning')){  ?>
        toastr.warning("<?=$this->session->flashdata('warning'); ?>");
    <?php }
    if($this->session->flashdata('info')){  ?>
        toastr.info("<?=$this->session->flashdata('info'); ?>");
    <?php } 
    $this->session->unset_userdata('success');
    $this->session->unset_userdata('error');
    ?>
</script>

</body>
</html>
