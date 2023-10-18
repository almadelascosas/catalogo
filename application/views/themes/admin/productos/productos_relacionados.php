<?php
foreach ($productos->result_array() as $key => $value) { ?>
<label class="w-100">
    <input type="checkbox" name="add[]" value="<?=$value['productos_id']?>">
    <span>
    <?=$value['productos_titulo']?>
    </span>
</label>
<?php
}
?>