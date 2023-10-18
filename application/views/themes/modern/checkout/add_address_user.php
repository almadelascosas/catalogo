<main class="col-12 cuerpo float-left mb-5">
    <div class="d-none d-sm-none d-md-block">
        <div class="row">
            <div class="offset-md-2 col-md-5 col-sm-4 pt-5 mt-2">
                <br>
                <h3>Ingresa una dirección de envío</h3>
            </div>
            <div class="col-md-3 col-sm-4 div-volver height-40">
                <br>
                <a href="<?=base_url('checkout')?>" class="btn-yellow-alma-simple">Volver al carrito</a>
            </div>
        </div>
    </div>
    <div class="d-block d-sm-block d-md-none">
        <div class="row">
            <div class="offset-md-1 col-12"><br></div>
        </div>
    </div>
    <form action="<?=base_url('checkout/addDireccion')?>" method="post">
    <div class="row">
        <input type="hidden" name="id_usuario" value="<?=$this->session->userdata('login_user')['usuarios_id']?>"> 

        <div class="offset-md-2 col-md-8 col-sm-12 pt-5 mt-2">
            <div class="card-metodo-pago col-12">
                <div class="d-block d-sm-block d-md-none">
                    <div class="row">
                        <div class="col-12 text-right">
                            <a href="<?=base_url('checkout')?>" class="btn-yellow-alma-simple">Volver al carrito</a>
                        </div>
                        <div class="offset-md-1 col-12">
                            <h4>Ingresa una dirección de envío</h4>
                            <br>    
                        </div>                        
                    </div>
                </div>

                <div class="row">                    
                    <div class="offset-md-1 col-md-5 col-sm-12 col-xs-12 <?=($this->session->userdata('login_user')['dni']==='')?'':'d-none';?>">
                        <div class="form-group">
                            <label>Identificación</label>
                            <input required class="form-control" type="text" name="dni" id="dni" placeholder="No. Cedula de Ciudadania" value="<?=(isset($datos_usuario['usuarios_id']))?$datos_usuario['dni']:$this->session->userdata('login_user')['dni'];?>">
                        </div>
                    </div>

                    <div class="<?=($this->session->userdata('login_user')['dni']==='')?'':'offset-md-1';?> col-md-5 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label>Nombre</label>
                            <input required class="form-control" type="text" name="nombre" id="nombre" placeholder="Nombre de referencia, eje: Mi Casa" value="<?=(isset($datos_usuario['usuarios_id']))?$datos_usuario['nombre']:''?>">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="offset-md-1 col-md-5 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label>Departamento</label>
                            <select onchange="obtenerMunicipios(this,'#pedidos_municipio')" required class="form-control" name="id_dpto" id="pedidos_departamento">
                                <option value="">Departamento</option>
                                <?php foreach ($departamentos->result_array() as $key => $value) { ?>
                                <option value="<?=$value['id_departamento']?>"><?=$value['departamento']?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-5 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label>Ciudad</label>
                            <select onchange="ubicacionCheckout('#pedidos_departamento',this);" required class="form-control" name="id_muni" id="pedidos_municipio">
                                <option value="">Ciudad / Localidad</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="offset-md-1 col-md-3 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label>Barrio</label>
                            <input required class="form-control" type="text" name="barrio" placeholder="Nombre de referencia, eje: Mi Casa" value="">
                        </div>
                    </div>
                    <div class="col-md-7 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label>Dirección completa</label>
                            <input required class="form-control" type="text" name="direccion" placeholder="Eje: Carrera X, Calle X..." value="">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="offset-md-1 col-md-5 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label>Telefono</label>
                            <input required class="form-control" type="text" name="telefono" placeholder="Eje: 301..." value="">
                        </div>
                    </div>
                    <div class="col-md-5 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label>Persona quien recibe</label>
                            <input required class="form-control" type="text" name="persona" placeholder="Nombre de persona que recibe" value="">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="offset-md-1 col-md-10 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label>Referencia o indicaciones para llegar a la direccion (Opcional)</label>
                            <textarea class="form-control" name="referencia" rows="3"></textarea>
                        </div>
                    </div>
                </div>

                <div class="offset-md-1 col-12">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" value="1" id="prede">
                      <label class="form-check-label" for="flexCheckDefault">Guardar como predeterminado</label>
                    </div>
                </div>


                <div class="row">
                    <div class="offset-md-5 col-md-2 col-sm-12 col-xs-12 text-center">
                        <button type="submit" class="btnSuccessAlmaNew">Guardar</button>
                    </div>
                </div>
                <!--  -->
            </div>
        </div>
        
    </div>
    </form>
    
</main>