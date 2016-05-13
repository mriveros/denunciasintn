<?php
include("../web/funciones.php");
conexionlocal();
$sql = "select den.den_cod, den.den_motivo,den.den_empresa,den.den_direccion,to_char(res.res_fecha,'DD/MM/YYYY')as res_fecha,
    res.res_obs,res.res_url,den.den_imagen,res.res_url,res.res_obs 
from denuncias den,respuestas res 
where den.den_cod=res.den_cod and res.res_activo='t'
order by res_fecha desc";
 
$resulset = pg_query($sql);
 
$arr = array();
while ($obj =pg_fetch_object($resulset)) {
    $arr[] = array('den_cod' => $obj->den_cod,
                   'den_motivo' => utf8_encode($obj->den_motivo),
                   'den_empresa' => $obj->den_empresa,
                   'den_direccion' => $obj->den_direccion,
                   'res_fecha' => $obj->res_fecha,
                   'den_imagen' => $obj->den_imagen,
                   'res_url' => $obj->res_url,
                   'res_obs' => $obj->res_obs,
                   
        );
}
$datares = array( 'status'=>200, 'Registros'=>$arr );
echo '' . json_encode($datares) . '';
?>
