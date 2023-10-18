<option value="">Seleccione...</option>
<?php foreach ($municipios as $key => $value) { ?>
<option <?php if($id_municipio==$value['id_municipio']){ echo "selected"; } ?> value="<?=$value['id_municipio']?>"><?=$value['municipio']?></option>
<?php
}
?>