<style type="text/css">
.divimageprod{
    position: relative;
    margin:  0 auto;
    width: 100%;
    max-width: 300px;
    height: 260px;
    background-color: #EDEDED;
    border-radius: 15px;

}
.packprod{
    position: relative;
    margin-bottom: 23px;
}
</style>
<div class="content">
    <div class="heading">
        <div class="d-block d-sm-block d-md-none mt-4">.</div>
        <div class="row">
            <div class="offset-md-1 col-md-2 col-8 mt-5">
                <h3>&nbsp;&nbsp;Productos</h3>
                
            </div>
            <div class="col-2 d-block d-sm-block d-md-none mt-5">
                <a href="<?=base_url('productos/agregar')?>" class="btn btn-success d-block d-sm-block d-md-none"><i class="fa fa-plus" aria-hidden="true"></i></a>
            </div>
            <div class="col-md-4 offset-1 col-10 mt-4 text-center">
                <input type="text" class="form-control" name="filbus" value="" placeholder="Buscar por nombre o código">
            </div>
            <div class="col-md-1 col-6 mt-5 text-center d-none d-sm-none d-md-block">
                <button name="btnFiltro" class="btn btn-info d-none d-sm-none d-md-block" data-toggle="modal" data-target="#exampleModal">Filtros</button>
               
            <div class="col-md-1 col-6 mt-5 text-right">
                <a href="<?=base_url('productos/agregar')?>" class="btn btn-success d-none d-sm-none d-md-block">Agregar</a>
                
            </div>
        </div>
        <!-- INICIO MODAL -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Filtro de Busqueda</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <form class="col-12" action="<?=base_url('productos/busqueda')?>" method="post">
                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <input class="form-control" type="text" name="search" value="<?=isset($_SESSION['filtros']['where_arr'][" productos_titulo"])?$_SESSION['filtros']['where_arr']["productos_titulo"]:''?>" placeholder="Frase o palabra">
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="form-group">
                                        <select class="form-control" name="productos_estatus" id="productos_estatus">
                                            <option value="">Estado</option>
                                            <option <?=(isset($_SESSION['filtros']['where_arr']["productos_estatus"]) && intval($_SESSION['filtros']['where_arr']["productos_estatus"])===0)?"selected":"";?> value="0">Borrador</option>
                                            <option <?=(isset($_SESSION['filtros']['where_arr']["productos_estatus"]) && intval($_SESSION['filtros']['where_arr']["productos_estatus"])===1)?"selected":"";?> value="1">Público</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="form-group">
                                        <select class="form-control" name="productos_estado_inv" id="productos_estado_inv">
                                            <option value="">Estado Inventario</option>
                                            <option <?=(isset($_SESSION['filtros']['where_arr']["productos_estado_inv"]) && intval($_SESSION['filtros']['where_arr']["productos_estado_inv"])===0)?"selected":"";?> value="0">(Ninguno)</option>
                                            <option <?=(isset($_SESSION['filtros']['where_arr']["productos_estado_inv"]) && intval($_SESSION['filtros']['where_arr']["productos_estado_inv"])===1)?"selected":"";?> value="1">Hay Existencias</option>
                                            <option <?=(isset($_SESSION['filtros']['where_arr']["productos_estado_inv"]) && intval($_SESSION['filtros']['where_arr']["productos_estado_inv"])===2)?"selected":"";?> value="2">Agotado</option>
                                            <option <?=(isset($_SESSION['filtros']['where_arr']["productos_estado_inv"]) && intval($_SESSION['filtros']['where_arr']["productos_estado_inv"])===3)?"selected":"";?> value="3">EnReserva</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- 
                                <div class="col-md-2 col-12">
                                    <div class="form-group">
                                        <button type="submit" name="btnBuscar" class="btn btn-success">Buscar</button>
                                        <button type="submit" name="btnLimpiar" class="btn btn-warning">Limpiar</button>
                                    </div>
                                </div> 
                                -->
                            </div>
                        </form>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-success">Buscar</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- FINAL MODAL -->
    </div>
    <div class="col-12">
        <div class="col-12 my-5">
            <div class="wcfm-container booking">
                <div class="d-none d-sm-none d-md-block">   
                    <div class="row ">
                        <?php foreach ($productos->result_array() as $key => $value) { ?>
                        <div class="col-3 packprod">
                            <div class="row">
                                <div class="col-12">
                                    <div class="divimageprod">
                                        imagen
                                    </div>
                                </div>
                                <div class="offset-md-1 col-10"><b><?=$value['productos_titulo']?></b></div>
                                <div class="offset-md-1 col-10">
                                    <svg height="1em" viewBox="0 0 512 512"><path d="M326.7 403.7c-22.1 8-45.9 12.3-70.7 12.3s-48.7-4.4-70.7-12.3c-.3-.1-.5-.2-.8-.3c-30-11-56.8-28.7-78.6-51.4C70 314.6 48 263.9 48 208C48 93.1 141.1 0 256 0S464 93.1 464 208c0 55.9-22 106.6-57.9 144c-1 1-2 2.1-3 3.1c-21.4 21.4-47.4 38.1-76.3 48.6zM256 91.9c-11.1 0-20.1 9-20.1 20.1v6c-5.6 1.2-10.9 2.9-15.9 5.1c-15 6.8-27.9 19.4-31.1 37.7c-1.8 10.2-.8 20 3.4 29c4.2 8.8 10.7 15 17.3 19.5c11.6 7.9 26.9 12.5 38.6 16l2.2 .7c13.9 4.2 23.4 7.4 29.3 11.7c2.5 1.8 3.4 3.2 3.7 4c.3 .8 .9 2.6 .2 6.7c-.6 3.5-2.5 6.4-8 8.8c-6.1 2.6-16 3.9-28.8 1.9c-6-1-16.7-4.6-26.2-7.9l0 0 0 0 0 0c-2.2-.7-4.3-1.5-6.4-2.1c-10.5-3.5-21.8 2.2-25.3 12.7s2.2 21.8 12.7 25.3c1.2 .4 2.7 .9 4.4 1.5c7.9 2.7 20.3 6.9 29.8 9.1V304c0 11.1 9 20.1 20.1 20.1s20.1-9 20.1-20.1v-5.5c5.3-1 10.5-2.5 15.4-4.6c15.7-6.7 28.4-19.7 31.6-38.7c1.8-10.4 1-20.3-3-29.4c-3.9-9-10.2-15.6-16.9-20.5c-12.2-8.8-28.3-13.7-40.4-17.4l-.8-.2c-14.2-4.3-23.8-7.3-29.9-11.4c-2.6-1.8-3.4-3-3.6-3.5c-.2-.3-.7-1.6-.1-5c.3-1.9 1.9-5.2 8.2-8.1c6.4-2.9 16.4-4.5 28.6-2.6c4.3 .7 17.9 3.3 21.7 4.3c10.7 2.8 21.6-3.5 24.5-14.2s-3.5-21.6-14.2-24.5c-4.4-1.2-14.4-3.2-21-4.4V112c0-11.1-9-20.1-20.1-20.1zM48 352H64c19.5 25.9 44 47.7 72.2 64H64v32H256 448V416H375.8c28.2-16.3 52.8-38.1 72.2-64h16c26.5 0 48 21.5 48 48v64c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V400c0-26.5 21.5-48 48-48z"/></svg>
                                    Precio asesor <span>$ 78.000</span>
                                </div>
                                <div class="offset-md-1 col-10">
                                    <svg height="1em" viewBox="0 0 448 512"><path d="M388.32,104.1a4.66,4.66,0,0,0-4.4-4c-2,0-37.23-.8-37.23-.8s-21.61-20.82-29.62-28.83V503.2L442.76,472S388.72,106.5,388.32,104.1ZM288.65,70.47a116.67,116.67,0,0,0-7.21-17.61C271,32.85,255.42,22,237,22a15,15,0,0,0-4,.4c-.4-.8-1.2-1.2-1.6-2C223.4,11.63,213,7.63,200.58,8c-24,.8-48,18-67.25,48.83-13.61,21.62-24,48.84-26.82,70.06-27.62,8.4-46.83,14.41-47.23,14.81-14,4.4-14.41,4.8-16,18-1.2,10-38,291.82-38,291.82L307.86,504V65.67a41.66,41.66,0,0,0-4.4.4S297.86,67.67,288.65,70.47ZM233.41,87.69c-16,4.8-33.63,10.4-50.84,15.61,4.8-18.82,14.41-37.63,25.62-50,4.4-4.4,10.41-9.61,17.21-12.81C232.21,54.86,233.81,74.48,233.41,87.69ZM200.58,24.44A27.49,27.49,0,0,1,215,28c-6.4,3.2-12.81,8.41-18.81,14.41-15.21,16.42-26.82,42-31.62,66.45-14.42,4.41-28.83,8.81-42,12.81C131.33,83.28,163.75,25.24,200.58,24.44ZM154.15,244.61c1.6,25.61,69.25,31.22,73.25,91.66,2.8,47.64-25.22,80.06-65.65,82.47-48.83,3.2-75.65-25.62-75.65-25.62l10.4-44s26.82,20.42,48.44,18.82c14-.8,19.22-12.41,18.81-20.42-2-33.62-57.24-31.62-60.84-86.86-3.2-46.44,27.22-93.27,94.47-97.68,26-1.6,39.23,4.81,39.23,4.81L221.4,225.39s-17.21-8-37.63-6.4C154.15,221,153.75,239.8,154.15,244.61ZM249.42,82.88c0-12-1.6-29.22-7.21-43.63,18.42,3.6,27.22,24,31.23,36.43Q262.63,78.68,249.42,82.88Z"/></svg>
                                     Precio cliente <span>$ 95.000</span>
                                 </div>
                            </div>
                        </div>

                        <?php
                        }

                        if ($productos->num_rows() <= 0) { ?>
                        <div class="offset-md-3 col-md-5 text-center">
                            No existen registros
                        </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
                <div class="d-block d-sm-block d-md-none">
                    <div class="row ">
                        <div class="col-12">
                            <div class=" table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Articulo</th>
                                            <th>Precio</th>
                                            <th>Imagen</th>                                            
                                            <th>Acciones</th>                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($productos->result_array() as $key => $value) { ?>
                                        <tr>

                                            <td>
                                                <?=$value['productos_titulo']?>
                                                <br>
                                                <span style="font-size:12px; font-weight: bold;"><?=$value['productos_sku']?></span>
                                            </td>
                                            <td class="text-right">$<?=$value['productos_precio']?></td>
                                            <td class="text-center">
                                                <i class="fa fa-eye"></i>
                                            </td>
                                            
                                            <td>
                                                <a class="btn btn-info text-white btn-sm" href="<?=base_url('productos/editar/'.$value['productos_id'].'/'.limpiarUri($value['productos_titulo']))?>"><i class="fas fa-edit" aria-hidden="true"></i></a>
                                                <a class="btn btn-danger text-white btn-sm" onclick="confirmDelete(<?=$value['productos_id']?>)" href="javascript:void(0)"><i class="fas fa-trash" aria-hidden="true"></i></a>
                                            </td>
                                        </tr>
                                        <?php
                                        }
                                        if ($productos->num_rows() <= 0) { ?>
                                        <tr>
                                            <td colspan="20" class="text-center">No hay registros</td>
                                        </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                    <div class="row">
                        <div class="col-12 text-center py-4">
                            <div class="paginado">
                                <ul class="list-inline">
                                    <?php
                                    if (isset($_GET['page']) && $_GET['page'] > 1) { 
                                        ?>
                                    <li>
                                        <a href="<?=base_url()?>productos/?page=<?php if(isset($_GET['page'])){ echo $_GET['page']-1; } ?>">Anterior</a>
                                    </li>
                                    <?php
                                    }
                                    if (isset($_GET['page'])) {
                                        echo '<span class="btn rounded border mx-2">'.$_GET['page'].'</span>';
                                    }else{
                                        echo '<span class="btn rounded border mx-2">1</span>';
                                    }
                                    if ($productos->num_rows() == 12) { ?>
                                    <li>
                                        <a href="<?=base_url()?>productos/?page=<?php if(isset($_GET['page'])){ echo $_GET['page']+1; }else{ echo 2; } ?>">Siguiente</a>
                                    </li>
                                    <?php  } ?>
                                </ul>
                            </div>
                        </div>
                    </div>

            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="ModalDel" tabindex="-1" role="dialog" aria-labelledby="ModalDelLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h5 class=" text-center">Estás seguro que deseas eliminar este producto?</h5>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button id="btnEliminar" type="button" class="btn btn-danger">Eliminar</button>
            </div>
        </div>
    </div>
</div>