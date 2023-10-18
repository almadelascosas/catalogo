<?php
if (isset($medios)) {
    foreach ($medios->result_array() as $key => $value) { ?>
    <div class="col-md-3 col-sm-4 col-6 image-gallery">
        <label for="image-<?=$value['medios_id']?>" onclick="selectImage(<?=$value['medios_id']?>,'<?=$value['medios_url']?>')">
            <input type="radio" name="image" id="image-<?=$value['medios_id']?>" value="<?=$value['medios_id']?>">
            <div class="rec-image">
                <img src="<?=$value['medios_url']?>" alt="<?=$value['medios_titulo']?>" srcset="<?=$value['medios_url']?>">
            </div>
        </label>
    </div>
    <?php
    }
    if ($medios->num_rows() <= 0) { ?>
    <div class="col-12 text-center">
        <h4>No hay elementos...</h4>
    </div>
    <?php
    }
}
?>