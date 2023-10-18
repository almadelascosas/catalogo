<main class="col-12 cuerpo finalizar-compra float-left">
    <div class="row">
        <div class="col-12 pt-5"><br><br></div>
    </div>
    <div class="row">
        <div class="col-12 ">
            <form action="<?=base_url('tienda/eliminarDatoApp')?>" method="post">
            
                <div class="row">
                    <div class="col-12">
                        <?php if(isset($_SESSION['msj'])){ ?>
                        <div class="alert alert-<?=$_SESSION['msj']['tip']?>">
                          <strong><?=$_SESSION['msj']['tit']?></strong> <?=$_SESSION['msj']['txt']?>
                        </div>
                        <?php unset($_SESSION['msj']); } ?>
                        
                    </div>
                    
                    <div class="col-lg-2 col-1 px-0"></div>
                    <div class="col-lg-8 col-10 px-0 text-center">
                        <div class="d-none d-sm-none d-md-block"><center><h3 style="font-weight: bold; font-weight: 700; color:#B1AFAF">Eliminación de datos</h3></center></div>
                        <div class="d-block d-sm-block d-md-none"><center><h3 style="font-weight: bold; font-weight: 700; color:#B1AFAF">Eliminación de datos</h3></center></div>
                        <br>
                        <p>
                        Estamos comprometidos con la seguridad de tus datos. Por lo que si deseas eliminarlos, escribe tu correo a continuacion:
                        </p>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-lg-2 col-1 px-0"><br></div>
                    <div class="col-lg-8 col-10 px-0 text-center">
                        <input required class="required form-control" type="text" name="txtEmail" placeholder="Correo Electronico">
                    </div>
                    <div class="col-12">&nbsp;<br></div>
                </div>
                <div class="row">
                    <div class="col-lg-2 col-1 px-0">&nbsp;</div>
                    <div class="col-lg-8 col-10 px-0 text-center">
                        <button type="submit" class="btn btn-addcart w-100 pt-3">Eliminar</button>
                    </div>
                </div>
            
            </form>
        </div>
    </div>
</main>
