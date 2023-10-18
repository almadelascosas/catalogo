<main class="cuerpo col-12 float-left">
    <div class="row">
        <div class="col-12 mb-5"></div>

        <div class="offset-md-1 col-md-3 offset-sm-1 col-sm-11 ">
            <div class="row">
                <div class="col-md-1 col-sm-1"></div>
                <?php $this->view('themes/modern/mi-cuenta/menu'); ?>
            </div>
        </div>

        <div class="col-md-7 col-11 miinfo">
            <form action="<?=base_url('cuenta/saveaddress')?>" method="post">
            <div class="row">

                <div class="offset-sm-1 col-10">
                    <div class="row">
                        <div class="col-md-12 col-12">&nbsp;</div>
                    </div>
                    <h3><?=(isset($address))?'Editar':'Agregar'?> dirección</h3>
                </div>
                <div class="col-12">
                    <hr>
                </div>               
                <div class="offset-sm-1 col-10">
                    <input type="hidden" name="txtId" value="<?=(isset($address))?$address['id_dir']:''?>">
                    <div class="row">
                        <!-- Si no tiene dato de DNI del Usuario... muestra para llenar este campo -->
                        <div class="col-sm-12 col-md-6 form-group <?=($this->session->userdata('login_user')['dni']==='')?'':'d-none'?>">
                            <label>Identificación</label>
                            <input type="text" class="form-control" name="txtDni" placeholder="No Cedula para Facturación" value="<?=(isset($address) && $address['dni']!=='')?$address['dni']:$_SESSION['dni']?>" required>
                        </div>

                        <div class="col-12 form-group">
                            <label>Nombre</label>
                            <input type="text" class="form-control" name="txtName" placeholder="Nombre de referencia" value="<?=(isset($address))?$address['nombre']:''?>" required>
                        </div>

                        <div class="col-md-6 col-12 form-group">
                            <label>Departamento</label>
                            <select onchange="obtenerMunicipios(this,'#pedidos_localidad')" required class="form-control" name="pedidos_departamento" id="pedidos_departamento">
                                <option value="">Seleccione...</option>
                                <?php 
                                foreach ($dptos->result_array() as $key => $value) { 
                                    $selected='';
                                    if(isset($address)){
                                        if($address['id_dpto']===$value['id_departamento']) $selected='selected';
                                    }
                                ?>
                                <option value="<?=$value['id_departamento']?>" <?=$selected?>><?=$value['departamento']?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-6 col-sm-12 form-group">
                            <label>Municipio</label>
                            <select required class="form-control" name="pedidos_localidad" id="pedidos_localidad">
                                <option value="">Seleccione...</option>
                                <?php 
                                if(isset($address)){
                                    foreach ($muni as $key => $value) { 
                                        $selected='';
                                        if(isset($address)){
                                            if($address['id_muni']===$value['id_municipio']) $selected='selected';
                                        }
                                ?>
                                <option value="<?=$value['id_municipio']?>" <?=$selected?>><?=$value['municipio']?></option>
                                <?php 
                                    }
                                }
                                ?>
                            </select>
                        </div>
       
                        <div class="col-md-6 col-12 form-group">
                            <label>Barrio</label>
                            <input type="text" class="form-control" name="txtBar" placeholder="Eje: La Candelaria" value="<?=(isset($address))?$address['barrio']:''?>" required>
                        </div>
                        <div class="col-md-6 col-12 form-group">
                            <label>Dirección</label>
                            <input type="text" class="form-control" name="txtDir" placeholder="Eje: Cra 4 # 00-00" value="<?=(isset($address))?$address['direccion']:''?>" required>
                        </div>
              
                        <div class="col-md-6 col-12 form-group">
                            <label>Telefono contacto</label>
                            <input type="text" class="form-control" name="txtTel" placeholder="numero de contacto de persona quien recibe" value="<?=(isset($address))?$address['telefono']:''?>" required>
                        </div>
                        <div class="col-md-6 col-12 form-group">
                            <label>Nombre de quien recibe</label>
                            <input type="text" class="form-control" name="txtPersona" placeholder="Nombre de persona quien recibe" value="<?=(isset($address))?$address['persona']:''?>" required>
                        </div>
                  
                        <div class="col-12 form-group">
                            <label>Referencia o indicaciones para llegar a la direccion (Opcional)</label>
                            <textarea class="form-control" name="txtRef" rows="3"><?=(isset($address))?$address['referencia']:''?></textarea>
                        </div>
               

                        <div class="offset-md-4 col-md-4 col-sm-12 text-center">
                            <button type="submit" class="btn btn-green-alma w-100">Guardar</button>
                        </div>
                        <div class="col-12">&nbsp;</div>
                    </div>
                </div>

            </div>
            </form>
        </div>

    </div>
</main>