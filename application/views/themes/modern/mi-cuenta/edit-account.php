<main class="cuerpo col-12 float-left">
    <div class="row">
        <div class="col-12 mb-5"></div>

        <div class="offset-md-1 col-md-3 col-sm-11 ">
            <div class="row">
                <div class="col-md-1 col-sm-11"></div>
                <?php $this->view('themes/modern/mi-cuenta/menu'); ?>
            </div>
        </div>

        <div class="col-md-7 col-11 miinfo">
            <form class="col-12 col-md-12 contact-form" action="<?=base_url('cuenta/guardaruser')?>" method="post">
                <div class="row">
                    <div class="col-md-12 col-12">&nbsp;</div>
                </div>
                <div class="row">
                    <input type="hidden" name="usuarios_id" id="usuarios_id" value="<?=$_SESSION['usuarios_id']?>">
                    <div class="col-sm-12 col-md-6"><h3 class="rale">Mi Información</h3></div>
                    <div class="col-sm-12 offset-md-1 col-md-5 d-none d-sm-none d-md-block"><button type="submit" class="btn btn-green-alma w-100">Guardar cambios</button></div>
                    <div class="col-12"><hr></div>
                    <div class="col-lg-6 col-sm-12">
                        <div class="row">
                            <div class="form-group col-12">
                                <label for="website">Numero de Identificación <sup class="text-danger">*</sup></label>
                                <input required type="text" name="dni" id="dni" value="<?=$_SESSION['dni']?>" placeholder="00" class="form-control">
                            </div>
                            <div class="form-group col-12">
                                <label for="username">Nombre de Usuario <sup class="text-danger">*</sup></label>
                                <input <?=($_SESSION['socialmedia_providerid']==='')?'required':'readonly';?> type="text" name="username" id="username" placeholder="" value="<?=$_SESSION['username']?>" class="form-control" <?=($_SESSION['socialmedia_providerid']==='' || $_SESSION['socialmedia_providerid']===null)?'':'readonly'?>>
                            </div>
                            <div class="form-group col-12">
                                <label for="email">Correo Electrónico <sup class="text-danger">*</sup></label>
                                <input <?=($_SESSION['socialmedia_providerid']==='')?'required':'readonly';?> type="text" name="email" id="email" placeholder="" value="<?=$_SESSION['email']?>" class="form-control">
                            </div>
                            <div class="form-group col-12">
                                <label for="name">Nombre <sup class="text-danger">*</sup></label>
                                <input required type="text" name="name" id="name" value="<?=$_SESSION['name']?>" placeholder="" class="form-control">
                            </div>
                            <div class="form-group col-12">
                                <label for="lastname">Apellido <sup class="text-danger">*</sup></label>
                                <input required type="text" name="lastname" id="lastname" value="<?=$_SESSION['lastname']?>" placeholder="" class="form-control">
                            </div>
                            <!--
                            <div class="form-group col-12">
                                <label for="website">Sitio Web</label>
                                <input type="text" name="website" id="website" value="<?=$_SESSION['website']?>" placeholder="" class="form-control">
                            </div>
                            -->
                            <div class="fm-group col-12">
                                <label for="website">Telefono <sup class="text-danger">*</sup></label>
                                <input type="text" name="phone" id="phone" value="<?=(isset($_SESSION['phone']))?$_SESSION['phone']:''?>" placeholder="301xxxx - (60) (x) xxxx" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="d-none d-sm-none d-md-block">
                        <div class="col-lg-1 text-center d-flex" style="height: 600px;">
                            <div class="vr mycontent-left"></div>
                        </div>
                    </div>
                    <div class="col-lg-5 col-sm-5">
                        <div class="row">
                            <?php if ($_SESSION['socialmedia_providerid']==="" && $_SESSION['socialmedia_providerid']===NULL) { ?>
                            <div class="form-group col-12">
                                <label for="">
                                    Contraseña 
                                    <?php
                                    if ($_SESSION['usuarios_id']!="" && $_SESSION['usuarios_id']!=0 && $_SESSION['usuarios_id']!=NULL) { ?>
                                    ( <em class="text-danger text-sm">Deje vacío si no desea modificar la contraseña</em> )
                                    <?php
                                    }
                                    ?>
                                    <sup class="text-danger">*</sup>
                                </label>
                                <input <?php if ($_SESSION['usuarios_id']=="" || $_SESSION['usuarios_id']==0 || $_SESSION['usuarios_id']==NULL) { echo "required"; } ?> type="password" autocomplete="off" name="password" id="password" placeholder="" class="form-control">
                            </div>
                            <div class="form-group col-12">
                                <input class="form-control" type="password" autocomplete="off" name="txtPass" id="txtPass" placeholder="Confirmar contraseña">
                            </div>
                            <?php } ?>

                            <div class="form-group col-12">
                                <label>Departamento</label>
                                <select onchange="obtenerMunicipios(this,'#pedidos_municipio')" required class="form-control" name="usuarios_departamento">
                                    <option value="">Seleccione...</option>
                                    <?php foreach ($departamentos->result_array() as $key => $value) { ?>
                                    <option <?php if(isset($_SESSION['usuarios_departamento']) && $_SESSION['usuarios_departamento']==$value['id_departamento'] ){ echo "selected"; } ?> value="<?=$value['id_departamento']?>"><?=$value['departamento']?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group col-12">
                                <label>Ciudad</label>
                                <select required class="form-control" name="usuarios_municipio" id="pedidos_municipio">
                                    <option value="">Seleccione...</option>
                                    <?php 
                                    if(isset($_SESSION['usuarios_municipio'])){
                                        foreach ($muni as $key => $value) { 
                                            $selected='';
                                            if(isset($_SESSION['usuarios_municipio'])){
                                                if($_SESSION['usuarios_municipio']===$value['id_municipio']) $selected='selected';
                                            }
                                    ?>
                                    <option value="<?=$value['id_municipio']?>" <?=$selected?>><?=$value['municipio']?></option>
                                    <?php 
                                        }
                                    }
                                    ?>
                                </select>
                            </div>

                            <?php 
                            //Si es logeado por RedSocial
                            if($_SESSION['socialmedia_providerid']!=='' && $_SESSION['socialmedia_providerid']==='apple.com'){
                                //Verificamos el correo... si tiene dominio apple, mostramos opcion de ingresar correo auxiliar
                                list($nameemail, $domainemail) = explode('@', $_SESSION['email']);
                                if($domainemail==='privaterelay.appleid.com'){
                            ?>
                            <div class="form-group col-12">
                                <hr>
                            </div>
                            <div class="form-group col-12">
                                <label>Ingresa Correo Electronico para notificaciones y temas de envio de facturacion</label>
                            </div>
                            <div class="form-group col-12">
                                <label for="email">Correo Electrónico (Auxiliar) <sup class="text-danger">*</sup></label>
                                <input required type="text" name="email_aux" id="email_aux" placeholder="" value="<?=$_SESSION['email_aux']?>" class="form-control">
                            </div>
                            <?php 
                                }
                            } 
                            ?>
                        </div>


                    </div>
                    <div class="col-sm-12 offset-md-1 col-md-3 d-block d-sm-block d-md-none"><button type="submit" class="btn btn-green-alma w-100">Guardar cambios</button></div>

                    <!--  
                    <div class="col-12 mt-4 text-center">
                        <button type="submit" class="btn btn-default">Guardar</button>
                    </div>
                    -->
                </div>
            </form>
        </div>

    </div>
</main>
<script type="text/javascript">
$( document ).ready(function() {
    $('.form-perfil').submit(function(){
        let pass1 = $('#password').val()
        let pass2 = $('#txtPass').val()
        let camp = 0

        if(pass1!==''){
            if(pass2===''){
                toastr.error("Ingrese confirmacion contraseña de segundo campo PASS")
                return false;
            }

            if(pass1 !== pass2){
                toastr.error("Contraseñas no coinciden")
                camp++
            }

            if(camp>0) return false;

        }
    })
});        
</script>