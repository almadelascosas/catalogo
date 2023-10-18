<option value="">Seleccione...</option>
<?php
foreach ($departamentos->result_array() as $key => $value) { ?>
<option <?php if($id_departamento==$value['id_departamento']){ echo "selected"; } ?> value="<?=$value['id_departamento']?>"><?=$value['departamento']?></option>
<?php
}
?>