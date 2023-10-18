<div class="content">
    <div class="col-12">
        <div class="wcfm-page-headig">
            <span class="wcfmfa fa-chalkboard"></span>
            <span class="wcfm-page-heading-text"><?=$texto?> Usuario</span>
            <span class="wcfm_menu_toggler wcfmfa fa-bars text_tip" data-tip="Mostrar / ocultar el menú" data-hasqtip="0"></span>
            <span class="wcfm-store-name-heading-text"><a href="#" target="_blank">Mi tienda</a></span>
            <div class="wcfm_header_panel">
                <a href="#" class="wcfm_header_panel_profile ">
                    <img class="wcfm_header_panel_profile_img  text_tip" src="<?=base_url('wp-content/plugins/wc-frontend-manager/assets/images/user.png')?>" data-tip="Perfil " data-hasqtip="1">
                </a>
                <a href="#" class="wcfm_header_panel_messages text_tip " data-tip="Tablero de avisos" data-hasqtip="2">
                <i class="wcfmfa fa-bell"></i>
                <span class="unread_notification_count message_count">0</span>
                <div class="notification-ring"></div>
                </a>
            </div>	
        </div>
        <div class="col-12 my-5">
            <div class="wcfm-container simple variable external grouped booking">
                <form class="col-12 contact-form" action="<?=base_url('usuarios/guardar')?>" method="post">
                    <div class="row">
                        <input type="hidden" name="editar" id="editar" value="<?=($usuario['usuarios_id']=="" || $usuario['usuarios_id']==0 || $usuario['usuarios_id']==NULL)?0:1?>">
                        

                        <div class="form-group col-md-6 col-12">
                            <label for="image_profile">Imagen de Usuario</label>
                            <div class="col-12 card-gallery border rounded mb-2 text-center">
                                <?php $img = ($usuario['image_profile']!="" && $usuario['image_profile']!=0 && $usuario['imagen_perfil']!="") ? $usuario['imagen_perfil'] : 'assets/uploads/Placeholder.png' ?>
  
                                <img class="principal" onclick="galeria(this);" src="<?=base_url($img)?>" alt="Placeholder">
                                <input type="hidden" id="image_profile" name="image_profile" value="<?=$usuario['image_profile']?>">
                            </div>
                        </div>
                        
                        <div class="form-group col-md-6 col-12">
                            <label for="usuarios_banner">Banner Vendedor (Tienda)</label>
                            <div class="col-12 card-gallery border rounded mb-2 text-center">
                                <?php $banner = ($usuario['usuarios_banner']!="" && $usuario['usuarios_banner']!=0 && $usuario['imagen_banner']!="") ? $usuario['imagen_banner'] : 'assets/uploads/Placeholder.png'; ?>
                                <img class="principal" onclick="galeriaMini(this);" src="<?=base_url($banner)?>" alt="Placeholder">
                                <input type="hidden" id="usuarios_banner" name="usuarios_banner" value="<?=$usuario['usuarios_banner']?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <input type="hidden" name="usuarios_id" id="usuarios_id" placeholder="" value="<?=$usuario['usuarios_id']?>" class="form-control">
                        
                        <div class="form-group offset-md-4  col-md-4 col-12">
                            <label>Nit o Identificación</label>
                            <input required type="text" name="dni" id="dni" placeholder="" value="<?=$usuario['dni']?>" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6 col-12">
                            <label for="username">Nombre de Usuario</label>
                            <input required type="text" name="username" id="username" placeholder="" value="<?=$usuario['username']?>" class="form-control" <?=($usuario['socialmedia_providerid']!==null && $usuario['socialmedia_providerid']!=='')?'readonly':''?>>
                        </div>
                        <div class="form-group col-md-6 col-12">
                            <label for="email">Correo Electrónico</label>
                            <input required type="text" name="email" id="email" placeholder="" value="<?=$usuario['email']?>" class="form-control" <?=($usuario['socialmedia_providerid']!==null && $usuario['socialmedia_providerid']!=='')?'readonly':''?>>
                        </div>
                        <div class="form-group col-md-6 col-12">
                            <label for="name">Nombre</label>
                            <input required type="text" name="name" id="name" value="<?=$usuario['name']?>" placeholder="" class="form-control">
                        </div>
                        <div class="form-group col-md-6 col-12">
                            <label for="lastname">Apellido</label>
                            <input required type="text" name="lastname" id="lastname" value="<?=$usuario['lastname']?>" placeholder="" class="form-control">
                        </div>
                        <div class="form-group col-md-6 col-12">
                            <label for="">
                                Contraseña 
                                <?php
                                if ($usuario['usuarios_id']!="" && $usuario['usuarios_id']!=0 && $usuario['usuarios_id']!=NULL) { ?>
                                ( <em class="text-danger text-sm">Deje vacío si no desea modificar la contraseña</em> )
                                <?php
                                }
                                ?>
                            </label>
                            <input <?php if ($usuario['usuarios_id']=="" || $usuario['usuarios_id']==0 || $usuario['usuarios_id']==NULL) { echo "required"; } ?> type="password" [formControl]="formGroup.controls['password']" autocomplete="new-password" name="password" id="password" placeholder="" class="form-control" value="" <?=($usuario['socialmedia_providerid']!==null && $usuario['socialmedia_providerid']!=='')?'readonly':''?>>
                        </div>
                        <div class="form-group col-md-6 col-12">
                            <label for="usuarios_comision">Comisión</label>
                            <input type="number" name="usuarios_comision" id="usuarios_comision" value="<?=$usuario['usuarios_comision']?>" placeholder="" class="form-control">
                        </div>

                        <div class="form-group col-md-6 col-12">
                            <label for="usuarios_departamento">Departamento</label>
                            <select class="form-control" onchange="cargarMunicipios();" name="usuarios_departamento" id="usuarios_departamento">
                                <option value="">Seleccione...</option>
                                <?php foreach ($departamentos->result_array() as $key => $value) { ?>
                                <option <?=($usuario['usuarios_departamento']==$value['id_departamento'])?"selected":""?> value="<?=$value['id_departamento']?>"><?=$value['departamento']?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <script>
                        var ubi = <?=($usuario['usuarios_municipio']!=0)?0:1;?>
                        </script>

                        <div class="form-group col-md-6 col-12">
                            <label for="usuarios_municipio">Municipio</label>
                            <select class="form-control" name="usuarios_municipio" id="usuarios_municipio">
                                <option value="">Seleccione...</option>
                                <?php
                                foreach ($municipios as $key => $value) { ?>
                                <option <?php if ($value['id_municipio']==$usuario['usuarios_municipio']) {
                                    echo "selected";
                                } ?> value="<?=$value['id_municipio']?>"><?=$value['municipio']?></option>
                                <?php
                                }
                                ?>                                
                            </select>
                        </div>

                        <div class="form-group col-md-6 col-12">
                            <label for="usuarios_categorias_id">Categoría:</label>
                            <select class="form-control" name="usuarios_categorias_id" id="usuarios_categorias_id">
                                <?php
                                foreach ($categorias->result_array() as $key => $value) { ?>
                                <option <?php if ($value['categorias_id']==$usuario['usuarios_categorias_id']) {
                                    echo "selected";
                                } ?> value="<?=$value['categorias_id']?>"><?=$value['categorias_nombre']?></option>
                                <?php
                                }
                                ?>                                
                            </select>
                        </div>

                        <div class="form-group col-md-6 col-12">
                            <label for="tipo_accesos">Tipo de Perfil</label>
                            <select required class="form-control" name="tipo_accesos" id="tipo_accesos">
                                <?php
                                foreach ($tipo_accesos as $key => $value) { ?>
                                <option <?php if($usuario['tipo_accesos']==$value['tipos_accesos_id']){ echo "selected"; } ?> value="<?=$value['tipos_accesos_id']?>"><?=$value['tipos_accesos_nombre']?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group col-md-6 col-12">
                            <label for="email">Dirección</label>
                            <input type="text" name="direccion" id="direccion" placeholder="" value="<?=(isset($usuario))?$usuario['direccion']:''?>" class="form-control">
                        </div>
                        <div class="form-group col-md-6 col-12">
                            <label for="email">Telefono</label>
                            <input type="text" name="phone" id="phone" placeholder="" value="<?=(isset($usuario))?$usuario['phone']:''?>" class="form-control">
                        </div>
                        <div class="col-12 mt-4 text-center">
                            <button type="submit" class="btn btn-success btn-sm">Guardar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="ModalGallery" tabindex="-1" role="dialog" aria-labelledby="ModalGalleryLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content" id="content-gallery">

    </div>
  </div>
</div>

