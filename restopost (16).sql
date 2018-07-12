-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 02-06-2017 a las 06:39:28
-- Versión del servidor: 10.1.10-MariaDB
-- Versión de PHP: 5.6.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `restopost`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `Bucarmesaporventa` (IN `idventa` INT)  NO SQL
select DISTINCT mesa.id as idmesa from venta INNER JOIN mesa 
WHERE venta.idMesa=mesa.id and venta.estado=0 and venta.id=idventa$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `buscarcliente` (IN `palabra` VARCHAR(100))  NO SQL
SELECT DISTINCT
  cliente.`id`,
  cliente.`Preferencias`,
  cliente.`nombre`,
  cliente.`direccion`,
  cliente.`telefonoFijo`,
  cliente.`celular`,
  cliente.`correo`,
  cliente.`razonSocial`,
  cliente.`NIT`,
  cliente.`eliminado`,
  (SELECT
    ciudad.`nombre`
  FROM
    ciudad
  WHERE ciudad.`id` = cliente.`idCiudad`) AS ciudad
FROM
  cliente
WHERE cliente.`NIT` LIKE CONCAT('%', palabra, '%')
  AND cliente.`NIT` = palabra$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `buscarmesa` (IN `estado` INT)  NO SQL
SELECT DISTINCT mesa.id, mesa.numeromesa,mesa.capacidad, mesa.estado FROM mesa
WHERE mesa.estado =estado$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `buscarProducto` (IN `parametro` VARCHAR(100), IN `almacen` INT(100))  NO SQL
SELECT
  producto.id,
  producto.nombre,
  producto.descripcion,
  producto.precioVenta,
  producto.modelo,
  producto.estilo,
  producto.corte,
  (SELECT
    marca.nombre
  FROM
    marca
  WHERE producto.idMarca = marca.id) AS marca,
  (SELECT
    `v_stockalmacensucursal`.`stock`
      FROM
    `v_stockalmacensucursal`
  WHERE v_stockalmacensucursal.`idproducto` = producto.id AND `v_stockalmacensucursal`.`idalmacen` = almacen) AS stock,
  producto.color,
  producto.`stockMin`,
  producto.`stockMax`,
  producto.tamano,
  producto.`tipoproducto`,
  producto.imagen,
  producto.material,
  producto.eliminado,
  producto.codigoInterno,
  producto.codigoDeBarra,
  
  (SELECT
    tipoproducto.nombre
  FROM
    tipoproducto
  WHERE tipoproducto.id = producto.`idTipoProducto`) AS categoria,
  (SELECT
    origen.nombre
  FROM
    origen
  WHERE origen.id = producto.`idOrigen`) AS origen
FROM
  producto
WHERE (
    producto.nombre LIKE CONCAT('%', parametro, '%')
    OR producto.`corte` LIKE CONCAT('%', parametro, '%')
    OR producto.modelo LIKE CONCAT('%', parametro, '%')
    OR producto.estilo LIKE CONCAT('%', parametro, '%')
    OR producto.tamano LIKE CONCAT('%', parametro, '%')
    OR producto.`codigoInterno` = parametro
    OR producto.codigoDeBarra = parametro
  )
  AND producto.eliminado = 0$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `Buscarventaconmesa` (IN `idmesa` INT)  NO SQL
select DISTINCT venta.id from venta INNER JOIN mesa 
WHERE venta.idMesa=mesa.id and venta.estado=0 and mesa.id=idmesa$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `Cambiarestadomesa` (IN `idmesa` INT)  NO SQL
UPDATE mesa SET estado=2 WHERE id=idmesa$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `comanda` (IN `idventa` INT)  NO SQL
SELECT
  producto.nombre AS producto,
  producto.`color`,
  producto.`tamano`,
  detalleventa.cantidad,
  detalleventa.idVenta,
  venta.fecha,
  venta.hora,
  venta.idMesa AS mesa,
  (SELECT mesa.numeromesa FROM mesa WHERE venta.idMesa = mesa.id) AS numeromesa,
  empleado.nombre AS empleado
  ,
  venta.ordennumero AS orden
FROM
  detalleventa
  INNER JOIN producto
    ON producto.id = detalleventa.idProducto
  INNER JOIN venta
    ON venta.id = detalleventa.idVenta
    AND venta.id = idventa
  INNER JOIN puntoventa
    ON puntoventa.id = venta.idPuntoVenta
  INNER JOIN empleado
    ON empleado.id = puntoventa.idEmpleado
WHERE venta.estado = 1$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `comandaventa` (IN `idfactura` INT)  NO SQL
SELECT
  producto.nombre AS producto,
  producto.color,
  producto.tamano,
  detalleventa.cantidad,
  detalleventa.precio,
  detalleventa.idVenta,
  venta.fecha,
  venta.hora,
  empleado.nombre AS empleado,
  factura.total as total,
  empresa.nombre,
  empresa.web,
  empresa.correo,
  sucursal.nombre as sucursal,
  empresa.imagen
FROM
  detalleventa
INNER JOIN
  producto ON producto.id = detalleventa.idProducto
INNER JOIN
  venta ON venta.id = detalleventa.idVenta  
INNER JOIN
  puntoventa ON puntoventa.id = venta.idPuntoVenta
INNER JOIN
  empleado ON empleado.id = puntoventa.idEmpleado
  INNER JOIN
  factura ON factura.idVenta = venta.id and factura.id=idfactura
      INNER JOIN
  sucursal ON sucursal.id = puntoventa.idSucursal
  
    INNER JOIN
  empresa ON empresa.id = sucursal.idEmpresa
WHERE
  venta.estado = 1 and factura.eliminado=0$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `editarfactura` (IN `id` INT)  NO SQL
SELECT factura.id ,
factura.razonSocial,factura.NIT
FROM factura
WHERE factura.id=id and factura.eliminado=0$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `egresosumasinempleado` (IN `Fechainicio` DATE, IN `Fechafin` DATE)  NO SQL
SELECT sum(Importe) as importe
FROM
(
SELECT
  sum(egreso.importe) AS Importe
FROM
  egreso
INNER JOIN
  tipoegreso ON tipoegreso.id = egreso.idTipoEgreso
INNER JOIN
  puntoventa ON puntoventa.id = egreso.idPuntoVenta
INNER JOIN
  empleado ON empleado.id = puntoventa.idEmpleado
WHERE
  egreso.fecha BETWEEN Fechainicio AND Fechafin   AND egreso.eliminado = 0
  
 UNION
 Select  sum(c.total) as importe
from compra c
inner JOIN puntoventa pv on   c.idPuntoVenta=pv.id
 inner JOIN empleado e on e.id=pv.idEmpleado 
 
where c.fecha BETWEEN  Fechainicio  AND Fechafin
AND c.eliminado=0
) gg$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `Flujodecajaporusuario` (IN `idempleado` INT(11), IN `fechainicio` DATE, IN `fechafin` DATE)  NO SQL
Select DISTINCT V.ID as idventa , v.fecha, t.nombre as turno, v.formaPago, c.nombre as cliente , s.nombre as sucursal, f.total as total,(SELECT sum(factura.total) 
            from factura 
            inner JOIN venta on venta.id=factura.idVENTA
         inner JOIN puntoventa on puntoventa.id=venta.idPuntoVenta 
            inner JOIN empleado on empleado.id=puntoventa.idEmpleado 
            WHERE  empleado.id=idempleado
           and factura.estado=0  
           and venta.estado=1
          and venta.fecha BETWEEN  fechainicio  AND fechafin
           ) as totales,(SELECT sum(factura.total) 
            from factura 
            inner JOIN venta on venta.id=factura.idVENTA
         inner JOIN puntoventa on puntoventa.id=venta.idPuntoVenta 
            inner JOIN empleado on empleado.id=puntoventa.idEmpleado 
            WHERE  empleado.id=idempleado
           and factura.estado=0  
           and venta.estado=1
          and venta.formaPago='Efectivo'
          and venta.fecha BETWEEN  fechainicio  AND fechafin
           ) as efectivo,
           (SELECT sum(factura.total) 
            from factura 
            inner JOIN venta on venta.id=factura.idVENTA
         inner JOIN puntoventa on puntoventa.id=venta.idPuntoVenta 
            inner JOIN empleado on empleado.id=puntoventa.idEmpleado 
            WHERE  empleado.id=idempleado
           and factura.estado=0  
           and venta.estado=1
          and venta.formaPago in ('Tarjeta de Credito','Tarjeta de Debito')
          and venta.fecha BETWEEN  fechainicio  AND fechafin
           ) as Tarjeta
from venta v
LEFT JOIN mesa m on v.idMesa=m.id
LEFT JOIN cliente c on v.idCliente=c.id
inner JOIN puntoventa pv on   v.idPuntoVenta=pv.id
inner JOIN empleado e on e.id=pv.idEmpleado
inner JOIN turno t on t.id=e.idTurno
inner JOIN sucursal s on s.id=pv.idSucursal
inner JOIN factura f on f.idVenta=v.id
where v.fecha BETWEEN  fechainicio  AND fechafin
and e.id=idempleado
and f.estado=0
AND v.estado=1$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `flujodecierredecajasinusuario` (IN `fechainicio` DATE, IN `fechafin` DATE)  NO SQL
Select DISTINCT V.ID as idventa , v.fecha, t.nombre as turno, v.formaPago, c.nombre as cliente , s.nombre as sucursal, f.total as total,(SELECT sum(factura.total) 
            from factura 
            inner JOIN venta on venta.id=factura.idVENTA
         inner JOIN puntoventa on puntoventa.id=venta.idPuntoVenta 
 inner JOIN empleado on empleado.id=puntoventa.idEmpleado
           and factura.estado=0  
           and venta.estado=1
          and venta.fecha BETWEEN  fechainicio  AND fechafin
           ) as totales,(SELECT sum(factura.total) 
            from factura 
            inner JOIN venta on venta.id=factura.idVENTA
         inner JOIN puntoventa on puntoventa.id=venta.idPuntoVenta 
 
           and factura.estado=0  
           and venta.estado=1
          and venta.formaPago='Efectivo'
          and venta.fecha BETWEEN  fechainicio  AND fechafin
           ) as efectivo,
           (SELECT sum(factura.total) 
            from factura 
            inner JOIN venta on venta.id=factura.idVENTA
         inner JOIN puntoventa on puntoventa.id=venta.idPuntoVenta 
 
           and factura.estado=0  
           and venta.estado=1
          and venta.formaPago in ('Tarjeta de Credito','Tarjeta de Debito')
          and venta.fecha BETWEEN  fechainicio  AND fechafin
           ) as Tarjeta
from venta v
LEFT JOIN mesa m on v.idMesa=m.id
LEFT JOIN cliente c on v.idCliente=c.id
inner JOIN puntoventa pv on   v.idPuntoVenta=pv.id
 inner JOIN empleado e on e.id=pv.idEmpleado
inner JOIN turno t on t.id=e.idTurno
inner JOIN sucursal s on s.id=pv.idSucursal
inner JOIN factura f on f.idVenta=v.id
where v.fecha BETWEEN  fechainicio  AND fechafin
 
and f.estado=0
AND v.estado=1$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `kardexinventario` (IN `productoparam` INT, IN `fechainicio` DATE, IN `fechafin` DATE)  NO SQL
select 'Compra' AS `origen`,
		`restopost`.`compra`.`id` AS `transaccion`,
		`restopost`.`compra`.`fecha` AS `fecha`,
		`restopost`.`detallecompra`.`cantidad` as `ingresos`,
		'0' as `egresos`
from (`restopost`.`detallecompra`
	join `restopost`.`compra`
	on(
		(
			(`restopost`.`compra`.`id` = `restopost`.`detallecompra`.`idcompra`)
			and (`restopost`.`detallecompra`.`idProducto` = productoparam)
			and (`restopost`.`compra`.`eliminado` = 0)
			and (`restopost`.`compra`.`fecha`BETWEEN fechainicio and fechafin)
		)
	)
)
union
select 'Inventario (Ingreso)' AS `origen`,
		`restopost`.`inventario`.`id` AS `transaccion`,
		`restopost`.`inventario`.`fecha` AS `fecha`,
		`restopost`.`detalleinventario`.`cantidad` as `ingresos`,
		'0' as `egresos`
from (`restopost`.`detalleinventario`
	join `restopost`.`inventario`
	on(
		(
			(`restopost`.`inventario`.`id` = `restopost`.`detalleinventario`.`IdInventario`)
			and (`restopost`.`detalleinventario`.`idProducto` = productoparam)
			and (`restopost`.`inventario`.`eliminado` = 0)
			and (`restopost`.`inventario`.`idtipoinventario` = 'Ingreso')
			and (`restopost`.`inventario`.`fecha`BETWEEN fechainicio and fechafin)
		)
	)
)
union
select 'Venta' AS `origen`,
		`restopost`.`venta`.`id` AS `transaccion`,
		`restopost`.`venta`.`fecha` AS `fecha`,
		'0' as `ingresos`,
		`restopost`.`detalleventa`.`cantidad` as `egresos`
from (`restopost`.`detalleventa`
	join `restopost`.`venta`
	on(
		(
			(`restopost`.`venta`.`id` = `restopost`.`detalleventa`.`idVenta`)
			and (`restopost`.`detalleventa`.`idProducto` = productoparam)
			and (`restopost`.`venta`.`eliminado` = 0)
			and (`restopost`.`venta`.`fecha`BETWEEN fechainicio and fechafin)
		)
	)
)
union
select 'Inventario (Ingreso)' AS `origen`,
		`restopost`.`inventario`.`id` AS `transaccion`,
		`restopost`.`inventario`.`fecha` AS `fecha`,
		`restopost`.`detalleinventario`.`cantidad` AS `ingresos`,
		'0' as `egresos`
from (`restopost`.`detalleinventario`
	join `restopost`.`inventario`
	on(
		(
			(`restopost`.`inventario`.`id` = `restopost`.`detalleinventario`.`IdInventario`)
			and (`restopost`.`detalleinventario`.`idProducto` = productoparam)
			and (`restopost`.`inventario`.`eliminado` = 0)
			and (`restopost`.`inventario`.`idtipoinventario` = 'Egreso')
			and (`restopost`.`inventario`.`fecha`BETWEEN fechainicio and fechafin)
		)
	)
)$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `listadevestas` (IN `Fechainicio` DATE, IN `Fechafin` DATE)  NO SQL
Select  V.ID as idventa , v.fecha,e.nombre as usuario, t.nombre as turno, v.formaPago, c.nombre as cliente , s.nombre as sucursal, f.total as total,(SELECT sum(factura.total) 
            from factura 
            inner JOIN venta on venta.id=factura.idVENTA
         inner JOIN puntoventa on puntoventa.id=venta.idPuntoVenta 
 
           and factura.eliminado=0  
           and venta.estado=1
           WHERE venta.fecha  BETWEEN Fechainicio  AND Fechafin
           ) as totales
from venta v
LEFT JOIN mesa m on v.idMesa=m.id
LEFT JOIN cliente c on v.idCliente=c.id
inner JOIN puntoventa pv on   v.idPuntoVenta=pv.id
inner JOIN empleado e on e.id=pv.idEmpleado
inner JOIN turno t on t.id=e.idTurno
inner JOIN sucursal s on s.id=pv.idSucursal
inner JOIN factura f on f.idVenta=v.id
where v.fecha BETWEEN Fechainicio  AND Fechafin
 
and f.eliminado=0
AND v.estado=1$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `listadodecompraporusuario` (IN `Fechainicio` DATE, IN `Fechafin` DATE, IN `idempleado` INT)  NO SQL
SELECT compra.id,compra.fecha,compra.hora, compra.total,(SELECT sum(compra.total) from   compra  
INNER JOIN puntoventa on puntoventa.id=compra.idPuntoventa
INNER JOIN empleado on empleado.id=puntoventa.idEmpleado
WHERE compra.fecha BETWEEN Fechainicio and Fechafin
and empleado.id=idempleado) astotalimporte
from compra  
INNER JOIN puntoventa on puntoventa.id=compra.idPuntoventa
INNER JOIN empleado on empleado.id=puntoventa.idEmpleado
WHERE compra.fecha BETWEEN Fechainicio and Fechafin
and empleado.id=idempleado
 GROUP by compra.id$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `listadoegresototal` (IN `Fechainicio` DATE, IN `Fechafin` DATE)  NO SQL
SELECT tipoegreso.nombre as tipoegreso,egreso.fecha as fecha,egreso.hora as hora,egreso.importe as importe ,(SELECT sum(egreso.importe)  from egreso LEFT JOIN puntoventa on puntoventa.id=egreso.idPuntoVenta
 
WHERE egreso.fecha BETWEEN  Fechainicio  AND Fechafin
  ) as total  ,empleado.nombre as empleado
from egreso
LEFT JOIN tipoegreso on tipoegreso.id=egreso.idTipoEgreso 
LEFT JOIN puntoventa on puntoventa.id=egreso.idPuntoVenta
 LEFT JOIN empleado on empleado.id=puntoventa.idEmpleado
WHERE egreso.fecha BETWEEN  Fechainicio  AND Fechafin
 
GROUP by egreso.id$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `listadoingresototal` (IN `Fechainicio` DATE, IN `Fechafin` DATE)  NO SQL
SELECT tipoingreso.nombre as TipoIngreso,ingreso.fecha as fecha,ingreso.hora as hora,ingreso.importe as importeingreso ,empleado.nombre from ingreso
LEFT JOIN tipoingreso on tipoingreso.id=ingreso.idTipoIngreso 
LEFT JOIN puntoventa on puntoventa.id=ingreso.idPuntoVenta
 LEFT JOIN empleado on empleado.id=puntoventa.idEmpleado
WHERE ingreso.fecha BETWEEN  Fechainicio  AND Fechafin
 
and ingreso.eliminado=0$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `listadototalporegreso` (IN `Fechainicio` DATE, IN `Fechafin` DATE, IN `idEmpleado` INT)  NO SQL
SELECT tipoegreso.nombre as tipoegreso,egreso.fecha as fecha,egreso.hora as hora,egreso.importe as importe ,(SELECT sum(egreso.importe)  from egreso LEFT JOIN puntoventa on puntoventa.id=egreso.idPuntoVenta
LEFT JOIN empleado on empleado.id=puntoventa.idEmpleado
WHERE egreso.fecha BETWEEN  Fechainicio  AND Fechafin
and empleado.id=idEmpleado ) as total ,empleado.nombre as empleado
from egreso
LEFT JOIN tipoegreso on tipoegreso.id=egreso.idTipoEgreso 
LEFT JOIN puntoventa on puntoventa.id=egreso.idPuntoVenta
LEFT JOIN empleado on empleado.id=puntoventa.idEmpleado
WHERE egreso.fecha BETWEEN  Fechainicio  AND Fechainicio
and empleado.id=idEmpleado
GROUP by egreso.id$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `listadototalporingreso` (IN `Fechainicio` DATE, IN `Fechafin` DATE, IN `idempleado` INT)  NO SQL
SELECT tipoingreso.nombre as TipoIngreso,ingreso.fecha as fecha,ingreso.hora as hora,ingreso.importe as importeingreso ,empleado.nombre from ingreso
LEFT JOIN tipoingreso on tipoingreso.id=ingreso.idTipoIngreso 
LEFT JOIN puntoventa on puntoventa.id=ingreso.idPuntoVenta
LEFT JOIN empleado on empleado.id=puntoventa.idEmpleado
WHERE ingreso.fecha BETWEEN  Fechainicio  AND Fechafin
and empleado.id=idempleado
and ingreso.eliminado=0$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `listadoventasconempleado` (IN `Fechainicio` DATE, IN `Fechafin` DATE, IN `idempleado` INT)  NO SQL
Select  V.ID as idventa , v.fecha, t.nombre as turno, v.formaPago, c.nombre as cliente , s.nombre as sucursal, f.total as total,(SELECT sum(factura.total) 
            from factura 
            inner JOIN venta on venta.id=factura.idVENTA
         inner JOIN puntoventa on puntoventa.id=venta.idPuntoVenta 
            inner JOIN empleado on empleado.id=puntoventa.idEmpleado 
            WHERE  empleado.id=idempleado
           and factura.estado=0  
           and venta.estado=1
          and venta.fecha BETWEEN  Fechainicio  AND Fechafin
           ) as totales
from venta v
LEFT JOIN mesa m on v.idMesa=m.id
LEFT JOIN cliente c on v.idCliente=c.id
inner JOIN puntoventa pv on   v.idPuntoVenta=pv.id
inner JOIN empleado e on e.id=pv.idEmpleado
inner JOIN turno t on t.id=e.idTurno
inner JOIN sucursal s on s.id=pv.idSucursal
inner JOIN factura f on f.idVenta=v.id
where v.fecha BETWEEN  Fechainicio  AND Fechafin
and e.id=idempleado
and f.estado=0
AND v.estado=1$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `listarcliente` ()  NO SQL
SELECT 
		cliente.`id`,
		cliente.`Preferencias`,
		cliente.`nombre`,
		cliente.`direccion`,
		cliente.`telefonoFijo`,
		cliente.`celular`,
		cliente.`correo`,
		cliente.`razonSocial`,
		cliente.`NIT`,
		cliente.`eliminado`,
        cliente.descuentoporcliente,
		(SELECT ciudad.`nombre` 
		FROM ciudad WHERE ciudad.`id` = cliente.`idCiudad`) AS ciudad, 
		tipocliente.`nombre` AS tipocliente
		
	FROM
	  cliente, tipocliente
	WHERE cliente.eliminado = 0 AND cliente.`idTipoCliente` = `tipocliente`.`id`$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `listarfacturasparaanular` (IN `fechalistar` DATE)  NO SQL
SELECT 
  factura.nroFactura,
  factura.id,
  factura.idVenta,
  factura.fecha,
  factura.razonSocial,
  factura.NIT,
  factura.total,
  empleado.nombre 
FROM
  factura 
  INNER JOIN puntoventa 
    ON puntoventa.id = factura.idPuntoVenta 
  LEFT JOIN empleado 
    ON empleado.id = puntoventa.idEmpleado 
WHERE factura.eliminado = 0 and factura.fecha = fechalistar
ORDER BY factura.id DESC$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `listarmesa` ()  NO SQL
SELECT
mesa.id,mesa.numeromesa,mesa.ubicacion,mesa.capacidad,mesa.estado,sucursal.nombre from mesa INNER JOIN sucursal WHERE mesa.idSucual=sucursal.id and mesa.eliminado=0$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `listarproducdoporcategoria` (IN `idtipoproducto` INT)  NO SQL
select producto.id,producto.nombre,producto.precioVenta,producto.imagen,v_stockporproducto.Stock
from producto
		INNER JOIN  tipoproducto on tipoproducto.id=producto.idTipoProducto and tipoproducto.id=idtipoproducto
        LEFT JOIN v_stockporproducto on v_stockporproducto.idProducto=producto.id 
        
 		WHERE producto.eliminado=0 and producto.tipoproducto='Comida' and producto.ventadirecta=0$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `listarprogramacion` (IN `idventa` INT)  NO SQL
SELECT 
venta.entregadomicilio,
venta.direccionenvio,
venta.importetransporte,
venta.personaentrega,
venta.minutostoleranciaentrega ,
venta.fechaentrega,
venta.horaentrega
FROM venta  WHERE venta.id=idventa$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `listarsumaegreso` (IN `Fechainicio` DATE, IN `Fechafin` DATE, IN `idempleado` INT)  NO SQL
SELECT sum(Importe) as importe
FROM
(
SELECT
  SUM(egreso.importe) AS Importe
FROM
  egreso
INNER JOIN
  tipoegreso ON tipoegreso.id = egreso.idTipoEgreso
INNER JOIN
  puntoventa ON puntoventa.id = egreso.idPuntoVenta
INNER JOIN
  empleado ON empleado.id = puntoventa.idEmpleado and empleado.id=idempleado
WHERE
  egreso.fecha BETWEEN Fechainicio AND Fechafin   AND egreso.eliminado = 0
  
 UNION
 Select   sum(c.total) as importe
from compra c
inner JOIN puntoventa pv on   c.idPuntoVenta=pv.id
 inner JOIN empleado e on e.id=pv.idEmpleado and e.id=idempleado
 
where c.fecha BETWEEN  Fechainicio  AND Fechafin
AND c.eliminado=0
) gg$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `listarsumaingreso` (IN `Fechainicio` DATE, IN `Fechafin` DATE, IN `idempleado` INT)  NO SQL
SELECT sum(Importe) as importe
FROM
(
SELECT
   sum(ingreso.importe) AS Importe
FROM
  ingreso
INNER JOIN
  tipoingreso ON tipoingreso.id = ingreso.idTipoIngreso
 INNER JOIN
  puntoventa ON puntoventa.id = ingreso.idPuntoVenta
INNER JOIN
  empleado ON empleado.id = puntoventa.idEmpleado and empleado.id=idempleado
WHERE
  ingreso.fecha BETWEEN Fechainicio AND Fechafin
     AND ingreso.eliminado = 0
  
 UNION
 Select    sum(f.total) as importe
from venta v
inner JOIN puntoventa pv on   v.idPuntoVenta=pv.id
 inner JOIN empleado e on e.id=pv.idEmpleado and e.id=idempleado
inner JOIN factura f on f.idVenta=v.id 
where v.fecha BETWEEN  Fechainicio  AND Fechafin
AND v.estado=1
and f.eliminado=0
    and v.formaPago='Efectivo'
) gg$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `listarsumaventaconingresos` (IN `Fechainicio` DATE, IN `Fechafin` DATE, IN `idempleado` INT)  NO SQL
SELECT Tipo, id, fecha, hora, empleado, importe
FROM
(
SELECT
  tipoingreso.nombre AS Tipo,
  ingreso.id AS id,
  ingreso.fecha AS Fecha,
  ingreso.hora AS hora,
  empleado.nombre AS empleado,
  ingreso.importe AS Importe
FROM
  ingreso
INNER JOIN
  tipoingreso ON tipoingreso.id = ingreso.idTipoIngreso
INNER JOIN
  puntoventa ON puntoventa.id = ingreso.idPuntoVenta
INNER JOIN
  empleado ON empleado.id = puntoventa.idEmpleado and empleado.id=idempleado
WHERE
  ingreso.fecha BETWEEN Fechainicio  AND Fechafin   AND ingreso.eliminado = 0
  
 UNION
 Select  'Venta' as Tipo, V.ID as id , v.fecha as Fecha,v.hora, e.nombre as empleado, f.total as importe
from venta v
inner JOIN puntoventa pv on   v.idPuntoVenta=pv.id
 inner JOIN empleado e on e.id=pv.idEmpleado and e.id=idempleado
inner JOIN factura f on f.idVenta=v.id 
where v.fecha BETWEEN  Fechainicio  AND Fechafin
AND v.estado=1
    and f.eliminado=0
    AND v.formaPago='Efectivo'
) tmp$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `listarsumconcomprasegreso` (IN `Fechainicio` DATE, IN `Fechafin` DATE, IN `idempleado` INT)  NO SQL
SELECT Tipo, id, fecha, hora, empleado, importe
FROM
(
SELECT
  tipoegreso.nombre AS Tipo,
  egreso.id AS id,
  egreso.fecha AS Fecha,
  egreso.hora AS hora,
  empleado.nombre AS empleado,
  egreso.importe AS Importe
FROM
  egreso
INNER JOIN
  tipoegreso ON tipoegreso.id = egreso.idTipoEgreso
INNER JOIN
  puntoventa ON puntoventa.id = egreso.idPuntoVenta
INNER JOIN
  empleado ON empleado.id = puntoventa.idEmpleado and empleado.id=idempleado
WHERE
 egreso.fecha BETWEEN Fechainicio  AND Fechafin  
    AND egreso.eliminado = 0
  
 UNION
 Select  'Compras' as Tipo, c.ID as id , c.fecha as Fecha,c.hora, e.nombre as empleado, c.total as importe
from compra c
inner JOIN puntoventa pv on   c.idPuntoVenta=pv.id
 inner JOIN empleado e on e.id=pv.idEmpleado 
     and e.id=idempleado
 
where c.fecha BETWEEN  Fechainicio  AND Fechafin
 AND e.eliminado = 0
) tmp$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `listarventas` (IN `idempleado` INT, IN `iddelpuntoventa` INT)  NO SQL
SELECT venta.id,
        venta.fecha,
        cliente.nombre as nombrecli,
        (select factura.razonSocial from factura where factura.idVenta = venta.id) as razon,
        empleado.nombre,
        venta.estado,
        (SELECT sum(detalleventa.cantidad*detalleventa.precio) 
                from detalleventa 
                INNER JOIN producto
                WHERE producto.id=detalleventa.idProducto and detalleventa.idVenta=venta.id) as total
        from venta 
        inner JOIN puntoventa 
        INNER JOIN empleado
        LEFT JOIN cliente ON  cliente.id =venta.idCliente
        where venta.idPuntoVenta=puntoventa.id 
                   and puntoventa.idEmpleado=empleado.id
                   and empleado.id=idempleado
                   and puntoventa.id=iddelpuntoventa
                   and venta.eliminado=0
        ORDER BY id DESC limit 100$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `obteneralmacen` (IN `puntoventaid` INT)  NO SQL
SELECT puntoventa.almacenpordefecto AS idAlmacen
FROM puntoventa
WHERE puntoventa.id = puntoventaid$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proforma` (IN `idventapro` INT)  SELECT distinct
  producto.nombre AS producto,
  producto.`codigoDeBarra`,
  producto.`precioVenta`,
  producto.`descripcion`,
  producto.`tamano`,
  producto.`material`,
  producto.`imagen` AS imgpro,
  detalleventa.cantidad,
  detalleventa.precio,
  detalleventa.idVenta,
  detalleventa.`porcentajedescuento` AS porproductopor,
  ROUND((detalleventa.`importedescuento` / `detalleventa`.`cantidad`),2) AS imporproducto,
  ROUND((detalleventa.`totalneto` / `detalleventa`.`cantidad`),2) AS preciofinal,
  detalleventa.`total` AS subtotal,
  detalleventa.`totalneto` AS totalsubpro,
  venta.fecha,
  venta.hora,
  venta.aCuenta,
  venta.`cobrarCada`,
  venta.`observaciones`,
  venta.`importedescuento`,
  venta.`saldoACobrar`,
  venta.garantia,
  venta.fechaentrega,
  venta.horaentrega,
  venta.personaentrega,
  venta.ci,
  venta.telefono,
  venta.`etapa`,
  (select ciudad.nombre from ciudad where ciudad.id = venta.ciudad) as ciudadEnvio,
  venta.importetransporte,
  venta.direccionenvio,
  venta.estadoVenta,
  
  (venta.total - venta.`importedescuento`) AS totalNeto,
  empleado.nombre AS empleado,
  empresa.nombre,
  empresa.`propietario`,
  empresa.`actividad`,
  `empleado`.`nombre` AS empleado,
  sucursal.nombre AS sucursal,
  sucursal.`direccion`,
  `sucursal`.`telefono`,
  (SELECT ciudad.`nombre` FROM ciudad WHERE ciudad.`id` = sucursal.`idCiudad`) AS ciudad,
  (SELECT pais.`nombre` FROM pais,ciudad WHERE ciudad.`id` = sucursal.`idCiudad` AND pais.`id` = ciudad.`idPais`) AS pais,
  empresa.imagen,
  cliente.nombre AS cliente,
  cliente.`telefonoFijo`,
  cliente.`celular`,
  (SELECT ciudad.`nombre` FROM ciudad WHERE ciudad.`id` = cliente.`idCiudad`) AS ciudadcliente,
  cliente.`correo`,
  cliente.`id` AS codcliente,
  venta.`total`,
  venta.`formaPago`,
  cliente.`NIT`,
  CASE WHEN  venta.descuentocliente=0 
           THEN
             venta.descuentocliente                  
	    ELSE 
	      ROUND((venta.total-(venta.total*venta.descuentocliente)/100) ,2)  
	    END descuentocliente,
  venta.descuentocliente as descuentoclienteliteral,
  (select tipodescuento.descuento from tipodescuento where tipodescuento.id=venta.idTipoDescuento)as descuentomasivoliteral
FROM
  ciudad,detalleventa
  INNER JOIN producto
    ON producto.id = detalleventa.idProducto
  INNER JOIN venta
    ON venta.id = detalleventa.idVenta
  INNER JOIN puntoventa
    ON puntoventa.id = venta.idPuntoVenta
  INNER JOIN empleado
    ON empleado.id = puntoventa.idEmpleado
  INNER JOIN sucursal
    ON sucursal.id = puntoventa.idSucursal
  INNER JOIN empresa
    ON empresa.id = sucursal.idEmpresa
  INNER JOIN cliente
    ON cliente.`id` = venta.`idCliente`
WHERE venta.`eliminado` = 0 
AND venta.id = idventapro$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `pro_producto` (IN `idproducto` INT(15))  NO SQL
SELECT * from producto WHERE producto.id=idproducto$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `rakingproducto` (IN `Fechainicio` DATE, IN `Fechafin` DATE)  NO SQL
SELECT producto.nombre AS Producto,producto.descripcion, producto.color,  producto.tamano, SUM((detalleventa.Cantidad)) AS  Cantidadvendida, SUM((detalleventa.total)) AS ImporteTotal, sucursal.nombre AS Sucursal
FROM detalleventa INNER JOIN producto ON detalleventa.idproducto = producto.id
INNER JOIN venta ON venta.id= detalleventa.idVenta
INNER JOIN puntoventa ON puntoventa.id = venta.idPuntoVenta
INNER JOIN factura ON factura.idVenta = venta.id
INNER JOIN sucursal ON puntoventa.idsucursal = sucursal.id
WHERE (((venta.fecha) BETWEEN Fechainicio AND Fechafin))
and venta.estado=1
and factura.eliminado=0
GROUP BY producto.id
ORDER BY Cantidadvendida DESC$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `rakingProductocantidad` (IN `Fechainicio` DATE, IN `Fechafin` DATE)  NO SQL
SELECT producto.nombre AS Producto,producto.descripcion, producto.color,  producto.tamano, SUM((detalleventa.Cantidad)) AS  Cantidadvendida, SUM((detalleventa.total)) AS ImporteTotal, sucursal.nombre AS Sucursal
FROM detalleventa INNER JOIN producto ON detalleventa.idproducto = producto.id
INNER JOIN venta ON venta.id= detalleventa.idVenta
INNER JOIN puntoventa ON puntoventa.id = venta.idPuntoVenta
INNER JOIN factura ON factura.idVenta = venta.id
INNER JOIN sucursal ON puntoventa.idsucursal = sucursal.id
WHERE (((venta.fecha) BETWEEN Fechainicio AND Fechafin))
and venta.estado=1
and factura.eliminado=0
GROUP BY producto.nombre,sucursal.nombre
ORDER BY SUM(detalleventa.cantidad) DESC$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `rakingProductototal` (IN `Fechainicio` DATE, IN `Fechafin` DATE)  NO SQL
SELECT  producto.nombre AS Producto,producto.descripcion, producto.color,  producto.tamano, SUM((detalleventa.Cantidad)) AS CantidadVendido, SUM((detalleventa.total)) AS ImporteTotal, sucursal.nombre AS Sucursal
FROM detalleventa INNER JOIN producto ON detalleventa.idproducto = producto.id
INNER JOIN venta ON venta.id= detalleventa.idVenta
INNER JOIN puntoventa ON puntoventa.id = venta.idPuntoVenta
INNER JOIN factura ON factura.idVenta = venta.id
INNER JOIN sucursal ON puntoventa.idsucursal = sucursal.id
WHERE (((venta.fecha) BETWEEN Fechainicio AND Fechafin))
and venta.estado=1
and factura.eliminado=0
GROUP BY producto.nombre,sucursal.nombre
ORDER BY SUM(detalleventa.total) DESC$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `recibo` (IN `codventacre` INT, IN `codcuotaven` INT)  SELECT 
  producto.nombre AS producto,
  producto.`descripcion`,
  producto.`tamano`,
  producto.`material`,
  detalleventa.cantidad,
  detalleventa.precio,
  detalleventa.idVenta,
  detalleventa.`total` AS subtotal,
  venta.fecha,
  venta.hora,
  venta.aCuenta,
  `cuentacobrar`.`fechaVencimiento`,
  IF((`cuentacobrar`.`importe` - (SELECT 
    SUM(cobrocuota.importe) 
  FROM
    cobrocuota 
  WHERE cobrocuota.`idCuentaCobrar` = cuentacobrar.`id`) )<> 0 AND `cuentacobrar`.`fechaVencimiento` > NOW(), `cuentacobrar`.`fechaVencimiento`,
  (SELECT c.`fechaVencimiento` FROM (SELECT  * from `cuentacobrar` where `cuentacobrar`.`idVenta` = codventacre) as c WHERE c.`id` = (
	(select `cobrocuota`.`idCuentaCobrar` from `cobrocuota` 
	  where `cobrocuota`.`id` = codcuotaven 
	  and `cobrocuota`.`idCuentaCobrar` = `cuentacobrar`.id 
	  and venta.id = `cuentacobrar`.`idVenta`
	  and venta.id = codventacre) + 1))) as fechacuotacredito,
  (
    (SELECT 
      SUM(detalleventa.importedescuento) 
    FROM
      detalleventa 
    WHERE detalleventa.idVenta = venta.id) + venta.importedescuento
  ) AS importedescuento,
  venta.`saldoACobrar`,
  round(
    (
      venta.total - venta.`importedescuento`
    ),
    1
  ) AS totalNeto,
  round(
    (
      (SELECT 
        SUM(pago.pagado) AS cuotasPagadas 
      FROM
        (SELECT 
          SUM(cobrocuota.`importe`) AS pagado,
          cuentacobrar.`id`,
          cuentacobrar.`idVenta` 
        FROM
          cobrocuota,
          cuentacobrar 
        WHERE cobrocuota.`idCuentaCobrar` = cuentacobrar.`id` 
          and `cobrocuota`.`eliminado` = 0 
        GROUP BY cuentacobrar.`id`) AS pago 
      WHERE pago.idVenta = venta.`id`) + venta.`aCuenta`
    ),
    2
  ) AS pgado,
  ROUND((SELECT 
          SUM(cobrocuota.`importe`) AS pagado
        FROM
          cobrocuota
        WHERE cobrocuota.`idCuentaCobrar` = cuentacobrar.`id` 

          AND `cobrocuota`.`eliminado` = 0) , 2)AS pago, 
  empleado.nombre AS empleado,
  empresa.nombre,
  sucursal.nombre AS sucursal,
  empresa.imagen,
  empresa.`propietario`,
  sucursal.`telefono` AS tel,
  (SELECT 
    ciudad.`nombre` 
  FROM
    ciudad 
  WHERE ciudad.`id` = sucursal.`idCiudad`) AS ciudad,
  (SELECT 
    pais.`nombre` 
  FROM
    pais,
    ciudad 
  WHERE ciudad.`id` = sucursal.`idCiudad` 
    AND pais.`id` = ciudad.`idPais`) AS pais,
  cliente.nombre AS cliente,
  cliente.`telefonoFijo`,
  cliente.`celular`,
  cliente.`correo`,
  sucursal.`direccion` AS direc,
  round(
    (SELECT 
      SUM(detalleventa.total) 
    FROM
      detalleventa 
    WHERE detalleventa.idVenta = venta.id),
    1
  ) AS total,
  cliente.`NIT`,
  ROUND(cuentacobrar.`importe`,2) AS totalcredito,
  cobrocuota.`importe` AS totalcuota,
  cobrocuota.`fecha` AS fechacuota,
  ROUND((cobrocuota.pago), 2) AS totalcancelado,
  cuentacobrar.`id` AS cuotacobrar,
  cuentacobrar.glosa,
  ROUND(((`cuentacobrar`.`importe` - (SELECT 
    SUM(cobrocuota.importe) 
  FROM
    cobrocuota 
  WHERE cobrocuota.`idCuentaCobrar` = cuentacobrar.`id`)) ) ,2)AS saldo 
FROM
  detalleventa 
  INNER JOIN producto 
    ON producto.id = detalleventa.idProducto 
  INNER JOIN venta 
    ON venta.id = detalleventa.idVenta 
  INNER JOIN puntoventa 
    ON puntoventa.id = venta.idPuntoVenta 
  INNER JOIN empleado 
    ON empleado.id = puntoventa.idEmpleado 
  INNER JOIN sucursal 
    ON sucursal.id = puntoventa.idSucursal 
  INNER JOIN empresa 
    ON empresa.id = sucursal.idEmpresa 
  INNER JOIN cliente 
    ON cliente.`id` = venta.`idCliente` 
  INNER JOIN cuentacobrar 
    ON cuentacobrar.`idVenta` = venta.`id` 
  INNER JOIN `cobrocuota` 
    ON cobrocuota.`idCuentaCobrar` = cuentacobrar.`id` 
WHERE venta.`eliminado` = 0 
  AND venta.id = codventacre 
  AND cobrocuota.`id` = codcuotaven$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `Reportedeventa` (IN `Fechanicio` DATE, IN `Fechafin` DATE, IN `Turno` INT, IN `Formadepago` VARCHAR(25), IN `Cliente` INT, IN `Mesa` INT)  NO SQL
    DETERMINISTIC
Select V.ID as idventa , v.fecha, t.nombre as turno, v.formaPago, c.nombre as cliente , s.nombre as sucursal, f.total as total
from venta v
LEFT JOIN mesa m on v.idMesa=m.id
LEFT JOIN cliente c on v.idCliente=c.id
LEFT JOIN puntoventa pv on   v.idPuntoVenta=pv.id
LEFT JOIN empleado e on e.id=pv.idEmpleado
LEFT JOIN turno t on t.id=e.idTurno
LEFT JOIN sucursal s on s.id=pv.idSucursal
LEFT JOIN factura f on f.idVenta=v.id
where v.fecha BETWEEN  Fechanicio AND Fechafin$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `reportelibroventa` (IN `Fechainicio` DATE, IN `Fechafin` DATE, IN `sucursal` INT)  NO SQL
SELECT
  factura.fecha,
  factura.nroFactura,
  factura.nroAutorizacion,
  CASE factura.eliminado
  WHEN 0 THEN 'V'
  WHEN 1 THEN 'A'
 
 END AS estado
  ,  factura.total AS importe,
0 AS 'tasa',0 AS 'ventascero',0 AS 'exportaciones',0 AS 'gravadas',factura.total AS subtotal,0 AS 'DESCUENTOS',0 AS 'Debitofiscal',
factura.NIT,
  factura.razonSocial,
  factura.codigoControl,
  factura.total
FROM
  factura
  INNER JOIN
  `libroorden` ON libroorden.`idSucursal` = sucursal
WHERE
 factura.fecha BETWEEN Fechainicio AND Fechafin and libroorden.id = factura.idLibroOrden ORDER BY factura.nroFactura$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sinempleado` (IN `Fechainicio` DATE, IN `Fechafin` DATE)  NO SQL
SELECT Tipo, id, fecha, hora, empleado, importe
FROM
(
SELECT
  tipoingreso.nombre AS Tipo,
  ingreso.id AS id,
  ingreso.fecha AS Fecha,
  ingreso.hora AS hora,
  empleado.nombre AS empleado,
  ingreso.importe AS Importe
FROM
  ingreso
INNER JOIN
  tipoingreso ON tipoingreso.id = ingreso.idTipoIngreso
INNER JOIN
  puntoventa ON puntoventa.id = ingreso.idPuntoVenta
INNER JOIN
  empleado ON empleado.id = puntoventa.idEmpleado
WHERE
  ingreso.fecha BETWEEN Fechainicio  AND Fechafin   AND ingreso.eliminado = 0
  
 UNION
 Select  'Venta' as Tipo, V.ID as id , v.fecha as Fecha,v.hora, e.nombre as empleado, f.total as importe
from venta v
inner JOIN puntoventa pv on   v.idPuntoVenta=pv.id
 inner JOIN empleado e on e.id=pv.idEmpleado  
inner JOIN factura f on f.idVenta=v.id and f.eliminado=0
where v.fecha BETWEEN  Fechainicio  AND Fechafin
AND v.estado=1
) tmp$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sumadetarjeta` (IN `Fechainicio` DATE, IN `Fechafin` DATE, IN `idempleado` INT)  NO SQL
SELECT sum(Importe) as importe
FROM
(
 Select   f.total as importe
from venta v
inner JOIN puntoventa pv on   v.idPuntoVenta=pv.id
 inner JOIN empleado e on e.id=pv.idEmpleado and e.id=idempleado
inner JOIN factura f on f.idVenta=v.id and f.eliminado=0
where v.fecha BETWEEN  Fechainicio  AND Fechafin
AND v.estado=1 and v.formaPago in ('Tarjeta de Debito','Tarjeta de Credito')
) gg$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sumasinempleado` (IN `Fechainicio` DATE, IN `Fechafin` DATE)  NO SQL
SELECT sum(Importe) as importe 
FROM
(
    Select   f.total as importe,f.idVenta
from venta v
inner JOIN factura f on f.idVenta=v.id 
where v.fecha BETWEEN  Fechainicio  AND Fechafin
and f.eliminado=0
AND v.estado=1
and v.formaPago='Efectivo'
 UNION
SELECT
  ingreso.importe AS Importe,ingreso.id
FROM
  ingreso
 
WHERE
ingreso.fecha BETWEEN Fechainicio AND Fechafin   
    AND ingreso.eliminado = 0
  
) gg$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sumasinempleadoegre` (IN `Fechainicio` DATE, IN `Fechafin` DATE)  NO SQL
SELECT Tipo, id, fecha, hora, empleado, importe
FROM
(
SELECT
  tipoegreso.nombre AS Tipo,
  egreso.id AS id,
  egreso.fecha AS Fecha,
  egreso.hora AS hora,
  empleado.nombre AS empleado,
  egreso.importe AS Importe
FROM
  egreso
INNER JOIN
  tipoegreso ON tipoegreso.id = egreso.idTipoEgreso
INNER JOIN
  puntoventa ON puntoventa.id = egreso.idPuntoVenta
INNER JOIN
  empleado ON empleado.id = puntoventa.idEmpleado
WHERE
  egreso.fecha BETWEEN Fechainicio  AND Fechafin  AND egreso.eliminado = 0
  
 UNION
 Select  'Compras' as Tipo, c.ID as id , c.fecha as Fecha,c.hora, e.nombre as empleado, c.total as importe
from compra c
inner JOIN puntoventa pv on   c.idPuntoVenta=pv.id
 inner JOIN empleado e on e.id=pv.idEmpleado  
 
where c.fecha BETWEEN  Fechainicio  AND Fechafin
 AND e.eliminado = 0
) tmp$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `tarjetasinempleado` (IN `Fechainicio` DATE, IN `Fechafin` DATE)  NO SQL
SELECT sum(Importe) as importe
FROM
(
 Select   f.total as importe
from venta v
inner JOIN puntoventa pv on   v.idPuntoVenta=pv.id
 inner JOIN empleado e on e.id=pv.idEmpleado  
inner JOIN factura f on f.idVenta=v.id 
where v.fecha BETWEEN  Fechainicio  AND Fechafin
AND v.estado=1 and v.formaPago in ('Tarjeta de Debito','Tarjeta de Credito')and f.eliminado=0
) gg$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `Ventacruzadas` (IN `producto` INT)  NO SQL
SELECT Producto.id, Producto.nombre, SUM(detalleventa.cantidad) Cantidad FROM detalleventa
INNER JOIN Producto ON Producto.id=detalleventa.idProducto
WHERE detalleventa.idVenta in
(
SELECT DISTINCT detalleventa.idVenta FROM detalleventa
INNER JOIN Venta ON Venta.id=detalleventa.idVenta and Venta.estado=1
WHERE detalleventa.idProducto=producto)
and Producto.id<>producto
GROUP BY producto.id, Producto.nombre
ORDER BY Cantidad DESC$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `almacen`
--

CREATE TABLE `almacen` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `idsucursal` int(11) NOT NULL,
  `eliminado` tinyint(4) NOT NULL DEFAULT '0',
  `idEmpleado` int(11) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `almacen`
--

INSERT INTO `almacen` (`id`, `nombre`, `idsucursal`, `eliminado`, `idEmpleado`) VALUES
(1, 'Almacén Central', 1, 0, 1),
(5, 'Almacén Central 2', 2, 0, 4),
(6, 'centralalma', 1, 0, 2),
(7, 'almamedioedio', 2, 0, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `banco`
--

CREATE TABLE `banco` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `eliminado` int(11) DEFAULT NULL
) ENGINE=Aria DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `banco`
--

INSERT INTO `banco` (`id`, `nombre`, `eliminado`) VALUES
(1, 'BNB', 0),
(2, 'Banco Union', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bitacora`
--

CREATE TABLE `bitacora` (
  `id` int(11) NOT NULL,
  `formulario` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `tabla` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `accion` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `fecha` datetime NOT NULL,
  `origen` varchar(10) COLLATE utf8_spanish_ci DEFAULT NULL,
  `idPuntoVenta` int(11) NOT NULL,
  `eliminado` tinyint(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cargo`
--

CREATE TABLE `cargo` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `eliminado` tinyint(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `cargo`
--

INSERT INTO `cargo` (`id`, `nombre`, `eliminado`) VALUES
(1, 'Asesores', 0),
(2, 'Atencion Al Cliente', 0),
(3, 'Gerencia', 0),
(4, 'lacayo', 1),
(11, 'Ventas', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ciudad`
--

CREATE TABLE `ciudad` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `idPais` int(11) NOT NULL,
  `eliminado` tinyint(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `ciudad`
--

INSERT INTO `ciudad` (`id`, `nombre`, `idPais`, `eliminado`) VALUES
(1, 'Santa Cruz de la Sierra', 1, 0),
(2, 'limas', 2, 1),
(3, ' lima', 2, 1),
(4, 'Sucre', 1, 0),
(5, 'Rio', 6, 0),
(7, 'santiago', 4, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `cliente` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `direccion` varchar(250) COLLATE utf8_spanish_ci DEFAULT NULL,
  `telefonoFijo` varchar(10) COLLATE utf8_spanish_ci DEFAULT NULL,
  `celular` varchar(10) COLLATE utf8_spanish_ci DEFAULT NULL,
  `correo` varchar(20) COLLATE utf8_spanish_ci DEFAULT NULL,
  `razonSocial` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `NIT` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `eliminado` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `idCiudad` int(11) DEFAULT '1',
  `Preferencias` text COLLATE utf8_spanish_ci,
  `idTipoCliente` int(11) DEFAULT '1',
  `idDescuento` int(11) DEFAULT '1',
  `descuentoporcliente` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`id`, `nombre`, `direccion`, `telefonoFijo`, `celular`, `correo`, `razonSocial`, `NIT`, `eliminado`, `idCiudad`, `Preferencias`, `idTipoCliente`, `idDescuento`, `descuentoporcliente`) VALUES
(1, 'Sin Nombre', '', '       ', '        ', '', 'Sin Nombre', '0', 0, 1, '', 1, 0, 0),
(15, 'ronny', 'direccion', '78787878', '787878787', 'correorafa@gmail.com', 'raxonsocialll', '7878878787', 0, 1, 'preferenciaassss', 1, 0, 25);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cobroacuota`
--

CREATE TABLE `cobroacuota` (
  `id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `importe` decimal(65,2) NOT NULL,
  `idPuntoVenta` int(11) NOT NULL,
  `idCuentaaCobrar` int(11) NOT NULL,
  `eliminado` tinyint(4) NOT NULL,
  `hora` time DEFAULT '00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `cobroacuota`
--

INSERT INTO `cobroacuota` (`id`, `fecha`, `importe`, `idPuntoVenta`, `idCuentaaCobrar`, `eliminado`, `hora`) VALUES
(1, '2017-05-09', '4.00', 1, 1, 0, '10:58:44');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cobrocuota`
--

CREATE TABLE `cobrocuota` (
  `id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `importe` double NOT NULL,
  `idPuntoVenta` int(11) NOT NULL,
  `idCuentaCobrar` int(11) NOT NULL,
  `eliminado` tinyint(3) UNSIGNED NOT NULL,
  `hora` time DEFAULT '00:00:00',
  `formaPago` varchar(20) COLLATE utf8_spanish_ci DEFAULT NULL,
  `pago` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `cobrocuota`
--

INSERT INTO `cobrocuota` (`id`, `fecha`, `importe`, `idPuntoVenta`, `idCuentaCobrar`, `eliminado`, `hora`, `formaPago`, `pago`) VALUES
(1, '2017-05-07', 2, 1, 29, 0, '19:26:00', 'Efectivo', 2),
(2, '2017-05-07', 124, 1, 29, 0, '19:26:24', 'Efectivo', 124.2),
(3, '2017-05-07', 0.20000000000000284, 1, 30, 0, '19:26:24', 'Efectivo', 124.2),
(4, '2017-05-07', 0.8, 1, 30, 0, '19:26:36', 'Efectivo', 0.8),
(5, '2017-05-07', 2, 1, 30, 0, '19:52:28', 'Efectivo', 2),
(6, '2017-05-07', 2, 1, 30, 0, '19:53:28', 'Efectivo', 2),
(7, '2017-05-07', 5, 1, 30, 0, '19:56:37', 'Efectivo', 5),
(8, '2017-05-07', 5, 1, 30, 0, '20:00:38', 'Efectivo', 5),
(9, '2017-05-07', 2, 1, 30, 0, '20:24:45', 'Efectivo', 2),
(10, '2017-05-07', 2, 1, 26, 0, '20:36:31', 'Efectivo', 2),
(11, '2017-05-07', 109, 1, 30, 0, '20:40:43', 'Efectivo', 109),
(12, '2017-05-07', 126, 1, 31, 0, '20:41:34', 'Cheque', 378),
(13, '2017-05-07', 126, 1, 32, 0, '20:41:34', 'Cheque', 378),
(14, '2017-05-07', 126, 1, 33, 0, '20:41:34', 'Cheque', 378),
(15, '2017-05-08', 0.2, 1, 34, 0, '09:56:55', 'Efectivo', 0.2),
(16, '2017-05-08', 0.1, 1, 34, 0, '09:57:12', 'Efectivo', 0.1),
(17, '2017-05-08', 14, 1, 38, 0, '09:57:24', 'Efectivo', 69.69999999999709),
(18, '2017-05-08', 14, 1, 35, 0, '09:57:24', 'Efectivo', 69.69999999999709),
(19, '2017-05-08', 13.7, 1, 34, 0, '09:57:24', 'Efectivo', 69.69999999999709),
(20, '2017-05-08', 14, 1, 37, 0, '09:57:24', 'Efectivo', 69.69999999999709),
(21, '2017-05-08', 14, 1, 36, 0, '09:57:24', 'Efectivo', 69.69999999999709),
(22, '2017-05-11', 10, 1, 26, 0, '13:39:30', 'Deposito Banco', 10),
(23, '2017-05-11', 10, 1, 26, 0, '13:40:00', 'Efectivo', 10),
(24, '2017-05-11', 1.3333333333330017, 1, 26, 0, '23:16:46', 'Tarjeta de Credito', 20),
(25, '2017-05-11', 18.666666666667, 1, 27, 0, '23:16:46', 'Tarjeta de Credito', 20),
(26, '2017-05-11', 2, 1, 27, 0, '23:19:59', 'Cheque', 2),
(27, '2017-05-11', 2, 1, 24, 0, '23:20:26', 'Cheque', 2),
(28, '2017-05-11', 2, 1, 22, 0, '23:20:48', 'Cheque', 2),
(29, '2017-05-11', 10, 1, 11, 0, '23:23:12', 'Deposito Banco', 10),
(30, '2017-05-16', 0.5, 1, 39, 0, '11:41:57', 'Cheque', 0.5),
(31, '2017-05-16', 2, 1, 39, 0, '11:42:28', 'Tarjeta de Debito', 2),
(32, '2017-05-16', 1, 1, 39, 0, '11:43:23', 'Efectivo', 1),
(33, '2017-05-30', 75.6, 1, 52, 0, '12:08:39', 'Efectivo', 100),
(34, '2017-05-30', 24.400000000000006, 1, 53, 0, '12:08:40', 'Efectivo', 100),
(35, '2017-05-30', 51.2, 1, 53, 0, '12:09:42', 'Efectivo', 51.2),
(36, '2017-06-01', 3500, 1, 54, 0, '14:47:40', 'Efectivo', 7000),
(37, '2017-06-01', 3500, 1, 55, 0, '14:47:40', 'Efectivo', 7000),
(38, '2017-06-01', 3795.75, 1, 56, 0, '14:49:58', 'Efectivo', 7591.5),
(39, '2017-06-01', 3795.75, 1, 57, 0, '14:49:59', 'Efectivo', 7591.5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `composicionproducto`
--

CREATE TABLE `composicionproducto` (
  `idProducto` int(11) NOT NULL,
  `idcomposicion` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `eliminado` tinyint(3) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `composicionproducto`
--

INSERT INTO `composicionproducto` (`idProducto`, `idcomposicion`, `id`, `cantidad`, `eliminado`, `created_at`, `updated_at`) VALUES
(378, 375, 18, 2, 0, NULL, NULL),
(378, 376, 23, 2, 0, NULL, NULL),
(379, 375, 21, 2, 0, NULL, NULL),
(379, 376, 22, 2, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `composicionproductodetalleventa`
--

CREATE TABLE `composicionproductodetalleventa` (
  `id` int(11) NOT NULL,
  `idventa` int(11) NOT NULL,
  `idproducto` int(11) DEFAULT NULL,
  `idcomposicion` int(11) DEFAULT NULL,
  `cantidadoriginal` int(11) DEFAULT NULL,
  `cantidadpedida` int(11) DEFAULT NULL,
  `costooriginal` decimal(12,2) DEFAULT NULL,
  `costocompartido` decimal(12,2) DEFAULT NULL,
  `fechahora` datetime DEFAULT NULL,
  `eliminado` tinyint(4) DEFAULT NULL,
  `idalmacen` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `composicionproductodetalleventa`
--

INSERT INTO `composicionproductodetalleventa` (`id`, `idventa`, `idproducto`, `idcomposicion`, `cantidadoriginal`, `cantidadpedida`, `costooriginal`, `costocompartido`, `fechahora`, `eliminado`, `idalmacen`) VALUES
(190, 179, 378, 375, 2, 2, '300.00', '0.00', '2017-06-01 20:56:56', 1, 1),
(191, 179, 378, 376, 2, 2, '300.00', '0.00', '2017-06-01 20:56:56', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compra`
--

CREATE TABLE `compra` (
  `id` int(11) NOT NULL,
  `fecha` date DEFAULT NULL,
  `total` decimal(65,2) DEFAULT NULL,
  `estado` tinyint(4) NOT NULL DEFAULT '0',
  `glosa` varchar(200) COLLATE utf8_spanish_ci DEFAULT NULL,
  `hora` time NOT NULL,
  `eliminado` tinyint(4) NOT NULL DEFAULT '0',
  `idPuntoventa` int(11) NOT NULL,
  `idProveedor` int(11) DEFAULT NULL,
  `idAlmacen` int(11) NOT NULL,
  `idpagocredito` int(11) DEFAULT NULL,
  `cuotassaldo` decimal(65,2) DEFAULT '0.00',
  `acuenta` int(11) DEFAULT '0',
  `saldoacobrar` int(11) DEFAULT '0',
  `cobrarcada` int(11) DEFAULT '0',
  `formaPago` varchar(20) COLLATE utf8_spanish_ci DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `compra`
--

INSERT INTO `compra` (`id`, `fecha`, `total`, `estado`, `glosa`, `hora`, `eliminado`, `idPuntoventa`, `idProveedor`, `idAlmacen`, `idpagocredito`, `cuotassaldo`, `acuenta`, `saldoacobrar`, `cobrarcada`, `formaPago`) VALUES
(25, '2017-06-01', NULL, 0, NULL, '17:15:27', 0, 1, 1, 1, NULL, '0.00', 0, 0, 0, ''),
(26, '2017-06-01', NULL, 0, NULL, '17:15:27', 0, 1, 1, 1, NULL, '0.00', 0, 0, 0, ''),
(27, '2017-06-01', NULL, 0, NULL, '21:03:43', 0, 1, 1, 1, NULL, '0.00', 0, 0, 0, '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `concepto`
--

CREATE TABLE `concepto` (
  `id` int(11) NOT NULL,
  `nombre` varchar(20) DEFAULT NULL,
  `eliminado` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `concepto`
--

INSERT INTO `concepto` (`id`, `nombre`, `eliminado`) VALUES
(1, 'Efectivo', 0),
(2, 'Banco', 0),
(3, 'Cheque', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuentaacobrar`
--

CREATE TABLE `cuentaacobrar` (
  `id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `importe` decimal(65,2) NOT NULL,
  `glosa` varchar(500) NOT NULL,
  `idCompra` int(11) NOT NULL,
  `idPuntoVenta` int(11) NOT NULL,
  `eliminado` tinyint(4) NOT NULL,
  `fechaVencimiento` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `cuentaacobrar`
--

INSERT INTO `cuentaacobrar` (`id`, `fecha`, `importe`, `glosa`, `idCompra`, `idPuntoVenta`, `eliminado`, `fechaVencimiento`) VALUES
(1, '2017-04-25', '4.00', '', 1, 1, 0, '2017-05-10'),
(2, '2017-05-10', '4.00', '', 1, 1, 0, '2017-06-10'),
(3, '2017-06-10', '4.00', '', 1, 1, 0, '2017-07-10'),
(4, '2017-07-10', '4.00', '', 1, 1, 0, '2017-08-10'),
(5, '2017-08-10', '4.00', '', 1, 1, 0, '2017-09-10'),
(6, '2017-05-03', '119.00', '', 6, 2, 0, '2017-05-31'),
(7, '2017-05-31', '119.00', '', 6, 2, 0, '2017-07-01'),
(8, '2017-07-01', '119.00', '', 6, 2, 0, '2017-08-01'),
(9, '2017-08-01', '119.00', '', 6, 2, 0, '2017-09-01'),
(10, '2017-09-01', '119.00', '', 6, 2, 0, '2017-10-01'),
(11, '2017-10-01', '119.00', '', 6, 2, 0, '2017-11-01'),
(12, '2017-11-01', '119.00', '', 6, 2, 0, '2017-12-01'),
(13, '2017-12-01', '119.00', '', 6, 2, 0, '2018-01-01'),
(14, '2018-01-01', '119.00', '', 6, 2, 0, '2018-02-01'),
(15, '2018-02-01', '119.00', '', 6, 2, 0, '2018-03-01'),
(16, '2017-05-29', '225.00', '', 20, 1, 0, '2017-06-15'),
(17, '2017-06-15', '225.00', '', 20, 1, 0, '2017-07-15'),
(18, '2017-07-15', '225.00', '', 20, 1, 0, '2017-08-15'),
(19, '2017-08-15', '225.00', '', 20, 1, 0, '2017-09-15'),
(20, '2017-05-29', '100.00', '', 18, 1, 0, '2017-06-02');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuentabancaria`
--

CREATE TABLE `cuentabancaria` (
  `id` int(11) NOT NULL,
  `banco` int(11) DEFAULT NULL,
  `nroCuenta` bigint(20) DEFAULT NULL,
  `tipoCuenta` varchar(50) DEFAULT NULL,
  `eliminado` int(11) DEFAULT NULL,
  `razonSocial` varchar(50) DEFAULT NULL,
  `moneda` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `cuentabancaria`
--

INSERT INTO `cuentabancaria` (`id`, `banco`, `nroCuenta`, `tipoCuenta`, `eliminado`, `razonSocial`, `moneda`) VALUES
(1, 1, 125, 'ahorro', 0, 'Luis Taraune', 'Bs'),
(2, 1, 1253, 'ahorro', 0, 'pedro', 'Bs');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuentacobrar`
--

CREATE TABLE `cuentacobrar` (
  `id` int(11) NOT NULL,
  `fecha` datetime NOT NULL,
  `fechaVencimiento` date DEFAULT '0000-00-00',
  `importe` double NOT NULL,
  `glosa` varchar(250) COLLATE utf8_spanish_ci DEFAULT NULL,
  `idVenta` int(11) DEFAULT NULL,
  `idPuntoVenta` int(11) NOT NULL,
  `eliminado` tinyint(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `cuentacobrar`
--

INSERT INTO `cuentacobrar` (`id`, `fecha`, `fechaVencimiento`, `importe`, `glosa`, `idVenta`, `idPuntoVenta`, `eliminado`) VALUES
(6, '2017-04-24 00:00:00', '2017-05-24', 252, '', 1, 1, 0),
(7, '2017-05-24 00:00:00', '2017-06-24', 252, '', 1, 1, 0),
(8, '2017-06-24 00:00:00', '2017-07-24', 252, '', 1, 1, 0),
(9, '2017-07-24 00:00:00', '2017-08-24', 252, '', 1, 1, 0),
(10, '2017-08-24 00:00:00', '2017-09-24', 252, '', 1, 1, 0),
(11, '2017-04-25 00:00:00', '2017-05-25', 2800, '', 5, 1, 0),
(12, '2017-05-25 00:00:00', '2017-06-25', 2800, '', 5, 1, 0),
(13, '2017-06-25 00:00:00', '2017-07-25', 2800, '', 5, 1, 0),
(14, '2017-07-25 00:00:00', '2017-08-25', 2800, '', 5, 1, 0),
(15, '2017-08-25 00:00:00', '2017-09-25', 2800, '', 5, 1, 0),
(16, '2017-04-25 00:00:00', '2017-05-25', 700, '', 7, 1, 0),
(17, '2017-04-25 00:00:00', '2017-05-25', 15592.5, '', 13, 1, 0),
(18, '2017-04-25 00:00:00', '2017-05-25', 1120, '', 14, 1, 0),
(19, '2017-05-25 00:00:00', '2017-06-25', 1120, '', 14, 1, 0),
(20, '2017-04-28 00:00:00', '2017-05-05', 35, '', 17, 1, 0),
(21, '2017-05-05 00:00:00', '2017-05-12', 35, '', 17, 1, 0),
(22, '2017-05-03 00:00:00', '2017-06-02', 56.7, '', 27, 2, 0),
(23, '2017-06-02 00:00:00', '2017-07-02', 56.7, '', 27, 2, 0),
(24, '2017-05-06 00:00:00', '2017-05-07', 35, '', 34, 1, 0),
(25, '2017-05-07 00:00:00', '2017-05-08', 35, '', 34, 1, 0),
(26, '2017-05-06 00:00:00', '2017-06-05', 23.333333333333, '', 36, 1, 0),
(27, '2017-06-05 00:00:00', '2017-07-05', 23.333333333333, '', 36, 1, 0),
(28, '2017-07-05 00:00:00', '2017-08-05', 23.333333333333, '', 36, 1, 0),
(29, '2017-05-07 00:00:00', '2017-05-22', 126, '', 38, 1, 0),
(30, '2017-05-22 00:00:00', '2017-06-06', 126, '', 38, 1, 0),
(31, '2017-06-06 00:00:00', '2017-06-21', 126, '', 38, 1, 0),
(32, '2017-06-21 00:00:00', '2017-07-06', 126, '', 38, 1, 0),
(33, '2017-07-06 00:00:00', '2017-07-21', 126, '', 38, 1, 0),
(34, '2017-05-07 00:00:00', '2017-05-22', 14, '', 39, 1, 0),
(35, '2017-05-22 00:00:00', '2017-06-06', 14, '', 39, 1, 0),
(36, '2017-06-06 00:00:00', '2017-06-21', 14, '', 39, 1, 0),
(37, '2017-06-21 00:00:00', '2017-07-06', 14, '', 39, 1, 0),
(38, '2017-07-06 00:00:00', '2017-07-21', 14, '', 39, 1, 0),
(39, '2017-05-16 00:00:00', '2017-06-15', 140, '', 47, 1, 0),
(40, '2017-06-15 00:00:00', '2017-07-15', 140, '', 47, 1, 0),
(41, '2017-07-15 00:00:00', '2017-08-15', 140, '', 47, 1, 0),
(42, '2017-08-15 00:00:00', '2017-09-15', 140, '', 47, 1, 0),
(43, '2017-09-15 00:00:00', '2017-10-15', 140, '', 47, 1, 0),
(44, '2017-05-22 00:00:00', '2017-05-29', 37.8, '', 56, 1, 0),
(45, '2017-05-29 00:00:00', '2017-06-05', 37.8, '', 56, 1, 0),
(46, '2017-05-30 00:00:00', '2017-05-31', 50, '', 151, 1, 0),
(47, '2017-05-31 00:00:00', '2017-06-01', 50, '', 151, 1, 0),
(48, '2017-05-30 00:00:00', '2017-06-06', 250, '', 152, 1, 0),
(49, '2017-06-06 00:00:00', '2017-06-13', 250, '', 152, 1, 0),
(50, '2017-05-30 00:00:00', '2017-06-14', 35, '', 159, 1, 0),
(51, '2017-06-14 00:00:00', '2017-06-29', 35, '', 159, 1, 0),
(52, '2017-05-30 00:00:00', '2017-06-06', 75.6, '', 164, 1, 0),
(53, '2017-06-06 00:00:00', '2017-06-13', 75.6, '', 164, 1, 0),
(54, '2017-06-01 00:00:00', '2017-06-08', 3500, '', 173, 1, 0),
(55, '2017-06-08 00:00:00', '2017-06-15', 3500, '', 173, 1, 0),
(56, '2017-06-01 00:00:00', '2017-06-08', 3795.75, '', 174, 1, 0),
(57, '2017-06-08 00:00:00', '2017-06-15', 3795.75, '', 174, 1, 0),
(58, '2017-06-01 00:00:00', '2017-06-08', 315, '', 176, 1, 0),
(59, '2017-06-08 00:00:00', '2017-06-15', 315, '', 176, 1, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detallecompra`
--

CREATE TABLE `detallecompra` (
  `id` int(11) NOT NULL,
  `idcompra` int(11) NOT NULL,
  `idProducto` int(11) NOT NULL,
  `idUnidadMedida` int(11) DEFAULT NULL,
  `cantidad` int(11) NOT NULL,
  `costo` decimal(65,2) NOT NULL,
  `total` decimal(65,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detallefactura`
--

CREATE TABLE `detallefactura` (
  `id` int(11) NOT NULL,
  `idFactura` int(11) NOT NULL,
  `detalleFactura` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  `precio` float NOT NULL,
  `subTotal` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalleinventario`
--

CREATE TABLE `detalleinventario` (
  `id` int(11) NOT NULL,
  `idProducto` int(11) NOT NULL,
  `idUnidadMedida` int(11) NOT NULL,
  `IdInventario` int(11) NOT NULL DEFAULT '1',
  `cantidad` int(11) NOT NULL,
  `costo` decimal(65,2) NOT NULL,
  `total` decimal(65,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `detalleinventario`
--

INSERT INTO `detalleinventario` (`id`, `idProducto`, `idUnidadMedida`, `IdInventario`, `cantidad`, `costo`, `total`) VALUES
(26, 375, 1, 21, 100, '1.00', '100.00'),
(27, 376, 1, 21, 100, '1.00', '100.00'),
(28, 378, 1, 21, 20, '1.00', '20.00'),
(29, 379, 1, 21, 20, '1.00', '20.00'),
(30, 380, 1, 22, 100, '1.00', '100.00'),
(31, 381, 1, 22, 100, '1.00', '100.00'),
(32, 382, 1, 22, 20, '1.00', '20.00'),
(33, 383, 1, 22, 20, '1.00', '20.00'),
(34, 382, 1, 23, 20, '1.00', '20.00'),
(35, 383, 1, 23, 20, '1.00', '20.00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalleproforma`
--

CREATE TABLE `detalleproforma` (
  `id` int(11) NOT NULL,
  `idProforma` int(11) NOT NULL,
  `idProducto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio` decimal(65,2) NOT NULL,
  `total` decimal(65,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `idtipodescuento` int(11) DEFAULT '0',
  `porcentajedescuento` int(11) DEFAULT '0',
  `importedescuento` decimal(65,2) DEFAULT '0.00',
  `totalneto` decimal(65,2) DEFAULT '0.00',
  `estado` tinyint(3) NOT NULL DEFAULT '2',
  `nroFacturaCompra` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalleventa`
--

CREATE TABLE `detalleventa` (
  `id` int(11) NOT NULL,
  `idVenta` int(11) NOT NULL,
  `idProducto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio` decimal(65,2) NOT NULL,
  `total` decimal(65,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `idtipodescuento` int(11) DEFAULT '0',
  `porcentajedescuento` int(11) DEFAULT '0',
  `importedescuento` decimal(65,2) DEFAULT '0.00',
  `totalneto` decimal(65,2) DEFAULT '0.00',
  `estado` tinyint(3) NOT NULL DEFAULT '2'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `detalleventa`
--

INSERT INTO `detalleventa` (`id`, `idVenta`, `idProducto`, `cantidad`, `precio`, `total`, `created_at`, `updated_at`, `idtipodescuento`, `porcentajedescuento`, `importedescuento`, `totalneto`, `estado`) VALUES
(387, 171, 382, 40, '120.00', '4800.00', NULL, NULL, 1, 0, '0.00', '4800.00', 2),
(388, 171, 383, 10, '130.00', '1300.00', NULL, NULL, 1, 0, '0.00', '1300.00', 2),
(389, 173, 375, 50, '200.00', '10000.00', NULL, NULL, 1, 0, '0.00', '10000.00', 2),
(390, 174, 375, 10, '200.00', '2000.00', NULL, NULL, 1, 0, '0.00', '2000.00', 2),
(391, 174, 376, 50, '201.00', '10050.00', NULL, NULL, 1, 0, '0.00', '10050.00', 2),
(392, 175, 376, 10, '201.00', '2010.00', NULL, NULL, 1, 0, '0.00', '2010.00', 2),
(393, 176, 375, 5, '200.00', '1000.00', NULL, NULL, 1, 0, '0.00', '1000.00', 2),
(403, 179, 378, 1, '300.00', '300.00', NULL, NULL, 1, 0, '0.00', '300.00', 2),
(404, 179, 383, 1, '130.00', '130.00', NULL, NULL, 1, 0, '0.00', '130.00', 2),
(405, 179, 375, 1, '200.00', '200.00', NULL, NULL, 1, 0, '0.00', '200.00', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `egreso`
--

CREATE TABLE `egreso` (
  `id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `importe` decimal(65,2) NOT NULL,
  `pagadoA` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `glosa` varchar(250) COLLATE utf8_spanish_ci DEFAULT NULL,
  `idTipoEgreso` int(11) NOT NULL,
  `idPuntoVenta` int(11) NOT NULL,
  `idProveedor` int(11) NOT NULL,
  `txnOrigen` varchar(10) COLLATE utf8_spanish_ci DEFAULT NULL,
  `eliminado` tinyint(3) UNSIGNED NOT NULL,
  `formaPago` int(11) DEFAULT '1',
  `nroCuenta` bigint(30) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `ejercicio1`
--
CREATE TABLE `ejercicio1` (
`producto` varchar(50)
,`productoid` int(11)
,`almacenid` int(11)
,`cantidad` decimal(41,0)
,`ideal` decimal(32,0)
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleado`
--

CREATE TABLE `empleado` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `fechaNacimiento` date NOT NULL,
  `genero` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `telefonoFijo` varchar(10) COLLATE utf8_spanish_ci DEFAULT NULL,
  `celular` varchar(10) COLLATE utf8_spanish_ci DEFAULT NULL,
  `docIdentidad` varchar(20) COLLATE utf8_spanish_ci DEFAULT NULL,
  `correoElectronico` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `idCargo` int(11) NOT NULL,
  `idTurno` int(11) NOT NULL,
  `eliminado` tinyint(3) UNSIGNED NOT NULL,
  `comision` int(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `empleado`
--

INSERT INTO `empleado` (`id`, `nombre`, `fechaNacimiento`, `genero`, `telefonoFijo`, `celular`, `docIdentidad`, `correoElectronico`, `idCargo`, `idTurno`, `eliminado`, `comision`) VALUES
(1, 'Administrador', '2017-04-21', 'Masculino', '', '0', '0', 'admin@bellemariee.com', 3, 1, 0, 10),
(2, 'Luis Taraune', '1998-03-10', 'Masculino', 'NaN', '75336475', '5620018', 'jtaraune@osbolivia.com', 2, 3, 0, 10),
(3, 'Prueva', '2017-04-21', 'Masculino', 'NaN', 'NaN', '1234567', 'prueba@gmail.com', 3, 3, 1, 0),
(4, 'ronny', '1990-07-01', 'Masculino', '', '2132312231', '6387164', 's@s.com', 11, 1, 0, 99);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresa`
--

CREATE TABLE `empresa` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `imagen` mediumblob,
  `web` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `correo` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `propietario` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `actividad` varchar(300) COLLATE utf8_spanish_ci NOT NULL,
  `eliminado` tinyint(4) NOT NULL,
  `venderSinStock` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `empresa`
--

INSERT INTO `empresa` (`id`, `nombre`, `imagen`, `web`, `correo`, `propietario`, `actividad`, `eliminado`, `venderSinStock`) VALUES
(1, 'DADCO', 0x646174613a696d6167652f706e673b6261736536342c6956424f5277304b47676f414141414e535568455567414141447741414142414341594141414247484254494141414141584e535230494172733463365141414141526e51553142414143786a777638595155414141414a6345685a6377414143784541414173524158396b583545414141415a6445565964464e765a6e523359584a6c41484268615735304c6d356c644341304c6a41754d545a4561612f314141415a6b306c455156526f513857624231795639376e4837553376626250544e45336270426e4779547163632b44736764746f58484846765931476a51624668587367494d6857514652774d4e7749446c4152465677343430673069546135375532623371524e62356f6c6348373339377a6e76486a415131787465767a38344a772f384a373365333750382f796e5451413873507075746a3279704e7a6563555735765443783350486470684e4f5646344f4b2f6e7954323361415a312b357574762f6c337932586933656e6d563564664e306933446d7136796c6a36665a7633793252514c6e71462b6d326142666f30563477707466793036343879742b564f624546392f2f2b2b517a3859373666454579384e504a566b4755686365543754675a7973732b4b39344378376d3938635333486f6f7a6f496d79793049575774465a6f586a4c352f63434f766a36316f2f746e77322f70422b6e6d4452503578674b5871455550794f52776e385a4a494676306932344a63694f767772366a65706271642f79672b6932536f724d6f343676763369763975303858584e48314d2b4778735459516352386f38432b796a314a4748704d70373277456f3453316a2f576f4731676d474f46776e3761494956786e553237447a7476497250327a7a7636396f2f6c6e77324e6c5354424d745043526e2f534b4c6c753863492b515168465665703230437035776a367535574570563469734f69705a437447373744683247566e6e4b2f332b4c486b733946624248324d6271365876425251635654435631795630425856413656653849432b5444564e743649354a573448724c5969356244397338382f6152506b3637312b44506c73565056776f7556684f72704a43704d34326a423866363343456b614142465a786c524c51567a79774c544f73614555397a2f5a524f2b776f663963523465763966677a3562465431524b496c6c616f484b6f3671346673634a614469714f49714a61362b516a556e594175714e65576636585a586668613277596173536e76702f317a7239495376392f78587932656a69415670776c4f456c5478396d6e714773416f6f7059517639594b586f79706f4d7a726167684a485664684177675a52726667384f4e7545686675635837382b5a7471325a6d4852715a727569324d30765a61386f2b6d39724864776e31686a63502f34703333647a7a394c5068755a6f7862714b3346564c556a75627359547568354870514b2f37416e645a68354857784b714e655650514845314b4d734b44516368576b7244352f37726a496a595a38567259794c67317a454b6d68354c6f656e4637333169454e777637693845506845384d484674384f446b456470684b312f77645838506f7473616d4b75504d6e52507166337073774c71435630315230564b51524a5850546b71456c412f4436794169675255704f634152455a665157744e6d465a715271384a552b48584f5271616e6f547474597a417353417367743959676542425364414f53585a7068365665303435596c61556473397259384437765637633145485347556e6b39726b726c56664f304c6e544655556f745343716f4f42726f4261716a5a4b515653686e5838546c66477a5959454c48626a6f37445a38487656514b7273503369334c414445675157327145703041355067335a6b4f6e536a4d7a2f5876626b6d547a642b6e6262682f6436723672333456624b6c4a59765264535638565663624f4b714165734a58636c514e587855306d464169635652414451515657457332503444564e6e51753147466158686545396f6943583963596148704849376a5063726537684130656c4568677767346a374968563049374b6747374d61684159757265792f36616275483570794b5431762f432b3733745276526345585379773375477275456f3137474b5567755364707835584a5777564a7a32675a73704b5743744857767031466f77724438615142575052736e3038676e72533464374d5854576378643342346d35716e627661305a6e516a6332436274786141595a2b346e726f4a3238387267387673486a662b39327137736c76553630764576546a656e3270684b2f4856584655514a5563395170664656514e58344d6e664d3245464e6e585732484c4a697a622b783049784e54637274423269556272726f5473513366376972734d3577474a644a657734753649572b3479663156336f5a7530676243626f4a2b5343774a2f45784b785a5977337a4e326f37676c427830673349794d6b415a57426778712b617034716a684b777a6c464b5278416c547a324f6d6a796731687772484a5356734a5a63492f727644384c6b2f4d347739567143706f354542506149706350783050526c336a4a3374594d546f42764b596a574d376971777a463056647677363643626b514638486e416639744d30674d454a6d375a6a724458516e4b563965574756396846314d6b58644255726f5944366833515a4975526e4a55584730494b6e6b716f44624b755a3667444f50326552594d79572b446670476a6f65735967325a74436469625852467a562f4e36505078377071425639335330377255616757387768426e4b757045724763724d335961682f505a476a3776356274675a3278417965776443496775584e415272544d6f5867725a693648367446694d5a4945694f316f4653616c39613579686c494b44527931455631434777644c6258466774473537644439775744595a38784175316a65364e583171766f6e394d5267395a3378704473317a426b64532f305478714b4c764f6d776a772b46674744737541334b4165616b5775676635504164466376376e72424b75354f4a2b784d7773376569644335526137516563577a47734c356b764b466a67365467715443716e33706251584a4179734679555370655371676468553278363068322b775957326848352f564f644e706b51382f744a7651724e474c6754684f47377a4a6a544c455a622b307859334b4a43644d50474444376f42477a39396f785958316664467359436533594e6641626e6f7667636573524d6b6d415061456358754147466e646e4558684f4959474c4554702f7a7a6568432f594f61416a5955453259717738526447566451664934366731615635413834537551617669716f47303452685a6e323232305963784f47775a7673796e46717530474b37726d323943443672335a686a6532324443555078764a536353622f45416d46746e785472456430776762575772466b764a517846574559473568462f52597642422b6f376641662b785768457a4f526368556a3773537a6a4f335379676a644d3475684d3762446349696446484a7036474c532f313967617071306a4c642b696a44393644336d4c657549416b6f7051346356466474486c656c4b495552736931684262676439655a4f4f2f6f5254503557506f694f644c6472336832414f52434a495044736658624d336566453476304f4a42304c515561566c6e33325546626e5844516276526636715155496d55625936667741784e314967533053647847366b4d434c537847363945435a496572677a333342697071774b4c334d304c326d356d6d774231543655796c49536a6444514356504b656c6d424e524a754c596532505a3056574146517141432b4f464a447376504f747744384377437a79757859386b424235596443455043455273327665755078494d644544596e453833486c6268685a3942647956317864344871376a36454c746b5077374979474b4950546651464b32724379747565346673337162774b71436430473461766a58415373754b6f754b6d43696f5079664e42574f775a535574556c2f4f334d592f6d392b77574f50756841584a6b546965566879443066684c58482b46364c4d74467163676c435a7a4e2f6c5642576374634457777044314147426853483238436547355564384c6955314957676b382f52374a55633973477066716f437165637162397762745148556d6a44782f6e544444743975565642444a364d726d42647946774e326f336757336747556834453741385963593275554f7042344a5139345a485659666151666e67673049694e6748777a7a6d7235713744475844556e4833494177783553417344504556383330434d33513345376257752f4b7158597964626b6f654b6e6c4b4d41575541464a3142625a7a4c6d456f6355744358537138354c7a374f697861684a612f365537517670767465494d5230482b4c46514f704964755a3777536573747542615873614230342b374d444b6f77356b566a697835617765695357396f593873526e416b49526353654245645639776c724c69376e4d44785232426363657a337873546a4c3934475446637231594c6b58586e56676c517666436b42665a575158616c582b58776733587164514e4b6c535232514769434654324148627258683753496e4a68645a4d577162486e304c417446315577427a50344344456e3859317761693030593978752b795966352b4a2b5a4a30576f456548574641396e48484e68316e6f5573647871615454754f6b41554d35635543724c6f7277496646585267546a73475964487a456263414d33354d4b4b4b555549395652516b6f686b744156696173717149546e6131525068764949756875793171614d7a71524c6b39574f376e6c574c4f564e4a3551374d5831504d474662596e7968445a45486869436d596870694b694d78362b4155394e3357687a6d76787a4f4a7a567976357570645551664458414b3769465861462f4136416d2b704d6d506247643750386b316f50764d345446456c6e74786c73596f6c624e7852442b774a47464e4f37544f6b56645862366d6c43522f6651555a643346794f6761703671345375684b3544644b536c4133616c423475356d39376862526d6b7944682b7933597163343037655a426a43643758416a443157624c3230416839386668376656332f4c39377a312b507433582b4c494a30637871585257625a4f59356a58617a4e617575454e68724e444f526f45336e6e42672f79554e6c68572f6952617a54304b2f2b42434d64653479642b506f62744a78474a4e507770686139613170355a6d57664b74627742775678624567335a545156554170716279644a48774a325958507063703249324150635a58682b7a717272525367596378447956325a58636d5573692f624e353979497173697a42573575376b7275614a2f3763642f7665675375447338616775766c6459386e6d4374625a505432735849634d55304574496254345368364a774275383779486c6473527174357032434d4954424432656a4f586349534f50556b5443745055326447382f713367416e364f677653333555634656424b516c6431564d4a58334652414b656c612b724c53446d422b536f374b694f795a4648634e57482f6367627954484469557448536c564936732f722f7676716a6d6d39774e7350784f37616c5033363970757170747a5673374e625878724d3578506f455a317166734f485170434245466339467177526d455249757a416c7670646a654673476c564d4b303643315036325378652b78597751567378644738497248666f4b7144694b4746377161425550384a4b317a4c455536776b6e4a394f746e4b455a45667857536553797a577553627337566c2f39347664334336732b354864724e6c7735574e4e6d766234322f70445635647468422f4a50746b455a773372316f5465676a7a6f4b7a6249544d4d5637636a655a755a7436797531754f6f457a7a78383372627634454b2f74426e35316f2b31786768355743354b45727a677165647251316634435331634855524c4f3072382b6b655165656d3676636d4c724b6175726630464154667235764a7538654331314c7738425670774f507a696e4a6d4a336b43755252532f4a42334442795443555841684234656d32614a2b30437746525a397a754a7272644e5372756e6f45703478784d71392f39314a523136566c65317733385771377449576f5635513564635a525351436e46565570636c544157574846332b4135336c79586270474d34667136384a4e324b336d5862304c333673362f764f70523950576f722f6e4375656d4b526f546235734c3152344b4a7a5a7052644445482f4e646e77693770416479576336573479632f7157757a426c58667a61764f5a5333654b66386f5767413856524356323149496d6a646143556741366d717a4a4b457468526c4948646b6577354a644f4a672b66446f46306457447635774879427656643376522b756631546672496b2b504b676d746b7a506b4a614b667a74774962756d6f316543384f616d4a5067746578656869523759564c7162546e637a36573757757944734e2b61316c39767a7572654143646d552b734937547956386c634a45514a6e7144535773684c4830752b4c6f57456f6d43532b75736d4576637a6671674231504a6762575a4a3350767039776276696f32584a68397331464a514563567261354458677a432b4f4f3077355558676c412b4a5a6f424d52634a4c4441537534534e7032776d5266455851357a7233786e7a6e36764e36393543356a7550555248312f596a6f4467713156636b2f61774d4156565147554b4f4975673444676c464d70514d5857644436546b6e38392b435a354d314e51587662662b4f31377a66634659654c7269716431395a2b7533436658353342493759746851426351524f72764c6b4c6b4d3567364738576e475851397a33766a666e764e2b666c3730464c48706a6d36334c414949706f5375676c49414f3934434f4a7167344b2b506674336135422f32744f624c716c6d666e544d59422f307a5a6277717357586468677a6a38594d417556383332693548664c79774a39416c63514f436470316b334344786c617977433469386a4e49584f6972735a424361735357445858514668767a4f76763971446c363050624f6f37524e4e375a6673766868565a33486b71726c497971786c4e79584b4e7749366e424461634d78785a44656d33785431786634586430334f704162574c4b6d49654e4b5264746136616d6e576e526c557633613946436d644b6a6562773553434d7956384a762f6a335945686c6f5649717335653741727a2b36726455573136335076422f4e6f6c7461527330384d594947656a7664752f6a6971755371776f6f585657646e56787335786a5a726c52704b5841444f424f5370643258306a53316662594e717136757261365247366675352b4836797a397533497770633959734c374d32577252326e7a5868494c756d766a6e35384676785069757a562b37656368666d4464662b596437345153437657782f343534387465615a5a69386b6e2b735233776352794938595357485655425a336b675a55314b48465638727a444a76645555746b39544139784e563856574c502f786f454863626d32394772537a586c37417a6e34434774303446463651593864566533514e714d4d51596e767761793679304a6c576e745a636c66634257482f614d363758726346577763385a70376d503135364d61496770504e59764c585067676c374c5858684f346e416b2f6c3943734d346e4c42543661374d58616454736a41676f79315a4241786159384d4c7153316466626231722f36472f336a646533585a39622f2f2b4c6836795835377a5a4c3970683863615232364749533051384f685462344958526f6456584c5855356c5634493066774a7a3730524862746a2f38684e65754479774b4d4c343974326d4c366567366f7a2f43447873786b61427671363453566b426c736a36646b2f555a31424a3252644a317965524235734d437246316a786e4e4a76367564587a3550584c3458614e66584e2f39656e5654785276574d59722f616d49504f526959504d714b7a6f65784345435a746a5566724664646856454c5a37573464724c693736554e59636a394b34725872474f7342427a6b6d76755a6e43502b32646567304445767667756d486a486948774f4779496947676c49434b757a4f70526156327a4b646b735539795748596d517462616f637379754a716e76564b37345043636d312f662f4f704f30504b7a32732b2b2b75526d5575585136696d3757745975336d39334e625941494d44465a2f5763514c526c4f422b43662b4b48486e66725657624a585667454f502f47494637664e37432f65664c7a676659706c316f62706b50582b52314d324e49577338744e6d4c62623451616c4a48666e6c4c68584a6b5478683653344d61774a4c417633737470707a7261357a4e6b6d42476636315134743746753937384e646e446c39575333566c2b386a7561326f6873587472392f3875587276746179624d2f6531715a3555364638377238544779484836424a6235634f354a446e544f42324675385579305372674f517a7244324a65374447644c3376585043667953796965714279774b7449566e4254716e6f6f567844697a393373594d3373434343674e6d376e45676b7241434b65744f34757743537349366a7441644e72726e786145456c69556a57654a707538454f61335977374e6c4274634d4c6539784d4f72486f3572597232545646567a665762722b79756a7231784979623458733731497a654875436158477a67682b6c6f6445314c42643561465972385532466f6b336b5941556d45796d437838756e7552777a6e363973736d322f55582f48776669454b744964334341794c2b436177335577304e7979456665686b7a43757a495071456e6c4e4168774b366b4671306e7a636d347333466c546d556f57557763316957656d524e54463231374c544a6a7137352f45413236467874636c72576473687034657136736157725a32364c32763446667136784f7732753844314f524f78312f4f41696e67446e484c4f6a36477741786d2b4e6f37736675304e5a4855493264446633756f527a33345a3839563649416e56762f6953773759774b415135715077644e51364e68486a6764382f66626b484247367759746457417062796a4b63324f696c55666b77334377597273583947523553466e38383679593943787763454c6955435969777a697747625854776237647961374f715254454f363561486733446a6a4e2b69436f5a67384345717a43737641427a4a6f453551626a4e335679366d332f6a6663766d6a3238374b56447668537243446770715037745730796b5351612f4f7730766d35516a744e78637a74335a47796c6b6459737073684855696c7334753530334a6a596e57566a6f593867356c45313267316158632b3132496c32764b3944446c534673555648456955643444357453544345346d58415a48567579477a444b7145746a36755176432b747839754b3142464e687034554e4248534d5042335765433033582b64423057346757625a636a734673307871514f5176494a4935497154466a4f72694f755846596e48556a6b6a535854355133734a2b5647355443703744664c306c45506a736275612b66686b4a505864574c6a4b58386b6c6e57445939566842435952616856684777316c786433544248375346397474446171434f73317445395235337664425852635165424743657936465878666d54746445764259784455754b4f794c6a64436a536a706e706742327042425a4a2b416d304642695a664d67617452517857544c71512b43366b503542595065715a644a6845374a502b47485233694777706c59776c4b3978764f795a3349753745736f4e2b3932383679354c776538482b6d49532b5777552f6359326f556c516c2f6b786d74635751744e397366733831657579613738637262716c4b4d63556873574e52384b42646c68585a634461557761734f573747366b6f37306f38364764354f6244346c4f5768585a6c2f4b6c696f6c36325579317836366e564e4e516f2f6a344761534d724278734a396e53705461734b794d4558516b6d4f36614d445a2f416254784636424a494a69366b75455a516971774573376537755a64332b534c52355850526c56423352623967753665635a2b5757775a4e37316a6c45497032514477432b69576a5766633143426d656a4f48786b78477a7578757954357052634636487a6566317944396a774b5a545a6e596a4e6853647353506e75485135446d586d355a3576323558394b484635436963726b535547524a5546492b3549494b4c4c5448686e7879693054797545662b7a3743456b384458506179567672564f6f45515941396863726a37767455765836336f587732656f76752b6d74364c66327a754b73634c2f4963494e4d4f546f522b65424c38426d54696c6434624554526944627045526d4653316c74597672736e73697644734f574d4554733579432b2b714d5868397a536f2b6941594a3638466f2b78794d497266315744377553446b6e516e4570696f4e73696f4e5748366743795a746d59704f61666b4969723641774e684c4d435a584b6f584b6c43617a49656d47334e4d2f5a59496773434b33752f3948574a7376426d2f356247776f54612b6f396f5439717536303345445045614f687164434e53494e75644159436871314473774546654758775667534f3351524852415a36527356695a4e7063544d344f78387938435a697a5a53775762682b4e5a627547496270344b42627348495077676e63774d6e73707571626d774268394148364c4c38412f36684a433467695a564f6c655931626d75683559397a7256726278567571455061793335317766377576654738746e6f53777a6c50734839343739796e356154733542794e48416c744350536f52755641663359316443505834506763546b49474a7548316d4f336f6557595172515958347857452f6241627a49567668642b456673514d474d762f4765586f6c586b4962534d724553724f5366687437414b6d69556e45524a6241644d4b57574d6d624c30315a7139314b71395174696a643045654e626f41336c4d2f4778685463663055337576746e39316c493954785668707946724865655367366868457a5a425030372b64434662344675326e5a6f4933596965455952676d6674686a6179424c71354236426263424168693874675746704764366e6c3554444b677270736c33683245497870486e66565171554f4d7479463669744f4549623475746647354c5078683052334c6471684b5765567335447567352f655a794768717a7450355435786f357a4a6b424d336e6b4d6f49584f397a3253554550614165322f58733546746c4b334f524d2f7548393239746362636f4e2f64634f31444671714f76753778682b537a38553753446b76396a585a34326c7274715054763632446c41466e6461626c637856333956446c787339554e4f33756e636b7768524934704c4a426a437035395857556a6d7934726d324665327956314f776a694c734e5a526c5475627169613456784964357636757263377957666a33556f334d6d4d6f67552b726f64796f7538714a477a6c5056662f456a654b7548454b527255356c4d307a633959537972397864642b55793352337036313775566a346237305630397063456e6b506753777173754275656878445658512b73416a792f376a775659516d733775764779456132374e7037746b7453434b766d726e75756534506430447743333361453456376c732f462b46444968357955436a366137752f56544336727268374c4858535755393846512f38534e4f3366567255356c6238697a475a5a2b376944646e57526163365865707661447947666a6730672f706542782f64517451534854743034696342366472517164752b765430486d377632456f313742517564776e626953556c554a565333652f592b352b787477395a307970796d636f68374e5147557a7035352f793952345049702b4e2f7977522b476373564d2b457a746d6c5a2b364f444632346279317a74354b356535574636674e4462506c78356d344f512f6b7468724b427566744c5939715a662b462f763057542f77644c6c6c7851323067666f4141414141424a52553545726b4a6767673d3d, '', '', 'Dadco', 'Motos', 0, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura`
--

CREATE TABLE `factura` (
  `id` int(11) NOT NULL,
  `idLibroOrden` int(11) NOT NULL,
  `idVenta` int(11) DEFAULT NULL,
  `idPuntoVenta` int(11) NOT NULL,
  `nroFactura` bigint(20) NOT NULL,
  `nroAutorizacion` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `codigoControl` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `fecha` date NOT NULL,
  `NIT` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `razonSocial` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `fechaLimite` date NOT NULL,
  `total` decimal(65,2) NOT NULL,
  `totalLiteral` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  `eliminado` tinyint(3) UNSIGNED NOT NULL,
  `estado` tinyint(3) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `factura`
--

INSERT INTO `factura` (`id`, `idLibroOrden`, `idVenta`, `idPuntoVenta`, `nroFactura`, `nroAutorizacion`, `codigoControl`, `fecha`, `NIT`, `razonSocial`, `fechaLimite`, `total`, `totalLiteral`, `eliminado`, `estado`) VALUES
(18, 1, 149, 1, 18, '272401600194439', 'F2-1D-FA-5D-16', '2017-05-30', '6387164', 'David', '2024-04-30', '900.00', '  NOVECIENTOS  BOLIVIANOS 00/100 Bs.', 0, 0),
(19, 1, 164, 1, 19, '272401600194439', '2F-F6-42-62-56', '2017-05-30', '0', 'SIN NOMBRE', '2024-04-30', '216.00', '  DOSCIENTOS DIECISEIS BOLIVIANOS 00/100 Bs.', 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gastocompra`
--

CREATE TABLE `gastocompra` (
  `idGastoCompra` int(11) NOT NULL,
  `idCompra` int(11) NOT NULL,
  `idTipoGastoCompra` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `idProveedor` int(11) NOT NULL,
  `idFormaPago` int(11) NOT NULL,
  `nroCuenta` bigint(30) NOT NULL,
  `importe` int(11) NOT NULL,
  `afectaCostoProducto` tinyint(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `gastocompra`
--

INSERT INTO `gastocompra` (`idGastoCompra`, `idCompra`, `idTipoGastoCompra`, `fecha`, `hora`, `idProveedor`, `idFormaPago`, `nroCuenta`, `importe`, `afectaCostoProducto`) VALUES
(1, 1, 1, '2017-05-05', '22:01:40', 1, 1, 0, 200, 1),
(2, 15, 1, '2017-05-29', '13:36:47', 1, 1, 0, 1000, 1),
(3, 23, 2, '2017-05-30', '11:22:30', 1, 1, 0, 39, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horario`
--

CREATE TABLE `horario` (
  `id` int(11) NOT NULL,
  `dia` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `horaEntrada` time NOT NULL,
  `horaSalida` time NOT NULL,
  `eliminado` tinyint(3) UNSIGNED NOT NULL,
  `idTurno` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `horario`
--

INSERT INTO `horario` (`id`, `dia`, `horaEntrada`, `horaSalida`, `eliminado`, `idTurno`) VALUES
(1, 'Lunes', '03:00:00', '20:00:00', 0, 1),
(2, 'Martes', '15:00:00', '20:00:00', 0, 1),
(3, 'Miercoles', '15:00:00', '20:00:00', 0, 1),
(4, 'Jueves', '09:00:00', '12:30:00', 0, 1),
(5, 'Jueves', '15:00:00', '20:00:00', 0, 1),
(6, 'Viernes', '09:00:00', '12:30:00', 0, 1),
(7, 'Viernes', '15:00:00', '20:00:00', 0, 1),
(8, 'Sabado', '09:00:00', '12:30:00', 0, 1),
(9, 'Sabado', '15:00:00', '20:00:00', 0, 1),
(10, 'Lunes', '09:00:00', '12:30:00', 0, 2),
(11, 'Lunes', '15:00:00', '20:00:00', 0, 2),
(12, 'Martes', '09:00:00', '12:30:00', 0, 2),
(13, 'Martes', '15:00:00', '20:00:00', 0, 2),
(14, 'Miercoles', '09:00:00', '12:30:00', 0, 2),
(15, 'Miercoles', '15:00:00', '20:00:00', 0, 2),
(16, 'Jueves', '09:00:00', '12:30:00', 0, 2),
(17, 'Jueves', '15:00:00', '12:30:00', 0, 2),
(18, 'Viernes', '09:00:00', '12:30:00', 0, 2),
(19, 'Sabado', '09:00:00', '12:30:00', 0, 2),
(20, 'Sabado', '15:00:00', '20:00:00', 0, 2),
(21, 'Lunes', '09:00:00', '12:30:00', 0, 3),
(22, 'Lunes', '15:00:00', '20:00:00', 0, 3),
(23, 'Martes', '09:00:00', '12:30:00', 0, 3),
(24, 'Martes', '15:00:00', '20:00:00', 0, 3),
(25, 'Miercoles', '09:00:00', '12:30:00', 0, 3),
(26, 'Miercoles', '15:00:00', '20:00:00', 0, 3),
(27, 'Jueves', '09:00:00', '12:30:00', 0, 3),
(28, 'Jueves', '15:00:00', '20:00:00', 0, 3),
(29, 'Viernes', '09:00:00', '12:03:00', 0, 3),
(30, 'Viernes', '15:00:00', '20:00:00', 0, 3),
(31, 'Sabado', '09:00:00', '12:30:00', 0, 3);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `idealtodoslosanios`
--
CREATE TABLE `idealtodoslosanios` (
`producto` varchar(50)
,`productoid` int(11)
,`almacenid` int(11)
,`anio` int(4)
,`mes` int(2)
,`ideal` decimal(32,0)
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ingrediente`
--

CREATE TABLE `ingrediente` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `eliminado` tinyint(3) UNSIGNED DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ingredienteproducto`
--

CREATE TABLE `ingredienteproducto` (
  `id` int(11) NOT NULL,
  `idIngrediente` int(11) NOT NULL,
  `idUnidadMedida` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `costo` decimal(65,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `eliminar` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `ingredienteproducto`
--

INSERT INTO `ingredienteproducto` (`id`, `idIngrediente`, `idUnidadMedida`, `cantidad`, `costo`, `created_at`, `updated_at`, `eliminar`) VALUES
(382, 380, 1, 2, '12.00', NULL, NULL, 0),
(382, 381, 1, 2, '12.00', NULL, NULL, 0),
(383, 380, 1, 2, '12.00', NULL, NULL, 0),
(383, 381, 1, 2, '12.00', NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ingredienteproductodetalleventa`
--

CREATE TABLE `ingredienteproductodetalleventa` (
  `id` int(11) NOT NULL,
  `idventa` int(11) NOT NULL,
  `idproducto` int(11) DEFAULT NULL,
  `idingrediente` int(11) DEFAULT NULL,
  `cantidadoriginal` int(11) DEFAULT NULL,
  `cantidadpedida` int(11) DEFAULT NULL,
  `costooriginal` decimal(12,2) DEFAULT NULL,
  `costocompartido` decimal(12,2) DEFAULT NULL,
  `fechahora` datetime DEFAULT NULL,
  `eliminado` tinyint(4) DEFAULT NULL,
  `idalmacen` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `ingredienteproductodetalleventa`
--

INSERT INTO `ingredienteproductodetalleventa` (`id`, `idventa`, `idproducto`, `idingrediente`, `cantidadoriginal`, `cantidadpedida`, `costooriginal`, `costocompartido`, `fechahora`, `eliminado`, `idalmacen`) VALUES
(38, 171, 382, 380, 2, 80, '120.00', '0.00', '2017-06-01 14:41:23', 1, 1),
(39, 171, 382, 381, 2, 80, '120.00', '0.00', '2017-06-01 14:41:23', 1, 1),
(40, 171, 383, 380, 2, 20, '130.00', '0.00', '2017-06-01 14:41:38', 1, 1),
(41, 171, 383, 381, 2, 20, '130.00', '0.00', '2017-06-01 14:41:38', 1, 1),
(46, 179, 383, 380, 2, 2, '130.00', '0.00', '2017-06-01 20:58:10', 1, 1),
(47, 179, 383, 381, 2, 2, '130.00', '0.00', '2017-06-01 20:58:10', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ingreso`
--

CREATE TABLE `ingreso` (
  `id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `importe` decimal(65,2) NOT NULL,
  `recibidoDe` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `glosa` varchar(250) COLLATE utf8_spanish_ci DEFAULT NULL,
  `idTipoIngreso` int(11) NOT NULL,
  `idPuntoVenta` int(11) NOT NULL,
  `txnOrigen` varchar(10) COLLATE utf8_spanish_ci DEFAULT NULL,
  `eliminado` tinyint(3) UNSIGNED NOT NULL,
  `formaPago` int(11) DEFAULT '1',
  `nroCuenta` bigint(50) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventario`
--

CREATE TABLE `inventario` (
  `id` int(11) NOT NULL,
  `fecha` date DEFAULT NULL,
  `idAlmacen` int(11) DEFAULT '0',
  `idAlmacenDestino` int(11) DEFAULT '0',
  `idMotivomovimiento` int(11) DEFAULT NULL,
  `idPuntoventa` int(11) NOT NULL,
  `glosa` varchar(500) COLLATE utf8_spanish_ci DEFAULT NULL,
  `estado` tinyint(4) NOT NULL,
  `eliminado` tinyint(4) NOT NULL,
  `idtipoinventario` varchar(20) COLLATE utf8_spanish_ci DEFAULT NULL,
  `hora` time DEFAULT '00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `inventario`
--

INSERT INTO `inventario` (`id`, `fecha`, `idAlmacen`, `idAlmacenDestino`, `idMotivomovimiento`, `idPuntoventa`, `glosa`, `estado`, `eliminado`, `idtipoinventario`, `hora`) VALUES
(21, '2017-05-31', 0, 1, 1, 1, '', 1, 0, 'Ingreso', '10:54:23'),
(22, '2017-06-01', 0, 1, 1, 1, '', 1, 0, 'Ingreso', '13:06:52'),
(23, '2017-06-01', 0, 1, 1, 1, '', 1, 0, 'Ingreso', '13:23:35');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `libroorden`
--

CREATE TABLE `libroorden` (
  `id` int(11) NOT NULL,
  `idSucursal` int(11) NOT NULL,
  `NIT` varchar(20) COLLATE utf8_spanish_ci DEFAULT NULL,
  `nroAutorizacion` varchar(20) COLLATE utf8_spanish_ci DEFAULT NULL,
  `nroInicio` int(11) DEFAULT NULL,
  `nroFin` int(11) DEFAULT NULL,
  `nroActual` int(11) NOT NULL,
  `tipo` varchar(20) COLLATE utf8_spanish_ci DEFAULT NULL,
  `fechaInicio` date DEFAULT NULL,
  `fechaFin` date DEFAULT NULL,
  `estado` tinyint(1) NOT NULL,
  `llave` varchar(1000) COLLATE utf8_spanish_ci NOT NULL,
  `eliminado` tinyint(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `libroorden`
--

INSERT INTO `libroorden` (`id`, `idSucursal`, `NIT`, `nroAutorizacion`, `nroInicio`, `nroFin`, `nroActual`, `tipo`, `fechaInicio`, `fechaFin`, `estado`, `llave`, `eliminado`) VALUES
(1, 1, '328092020', '272401600194439', 1, 999999, 19, '', '2017-04-01', '2024-04-30', 0, '8%M[Nr@PBJk@RsReDBhRsBB)sX)gQ@8qL(@B(2wF_s2X3KDd[SpZfDMBNZTGX[AL', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `marca`
--

CREATE TABLE `marca` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `abreviatura` varchar(20) NOT NULL,
  `eliminado` tinyint(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `marca`
--

INSERT INTO `marca` (`id`, `nombre`, `abreviatura`, `eliminado`) VALUES
(1, 'Belle Mariee', 'BLLMEE', 0),
(2, 'KOVI', 'KV', 0),
(3, 'marca', 'm', 1),
(4, 'marca', 'm', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `marcacion`
--

CREATE TABLE `marcacion` (
  `id` int(11) NOT NULL,
  `fecha` datetime NOT NULL,
  `horaEntrada` time NOT NULL,
  `horaSalida` time NOT NULL,
  `idEmpleado` int(11) NOT NULL,
  `eliminado` tinyint(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mesa`
--

CREATE TABLE `mesa` (
  `id` int(11) NOT NULL,
  `numeromesa` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `ubicacion` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `capacidad` int(11) NOT NULL,
  `estado` tinyint(4) NOT NULL,
  `idSucual` int(11) NOT NULL,
  `eliminado` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `mesa`
--

INSERT INTO `mesa` (`id`, `numeromesa`, `ubicacion`, `capacidad`, `estado`, `idSucual`, `eliminado`) VALUES
(1, '1', 'Centro', 4, 2, 1, 0),
(2, 'izquierda', 'izquierda', 10, 0, 1, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`migration`, `batch`) VALUES
('2016_06_01_112338_create_productos_table', 1),
('2016_06_01_112411_create_tipo_productos_table', 1),
('2016_06_01_112422_create_composicion_productos_table', 1),
('2016_06_06_180144_create_unidadmedidas_table', 2),
('2016_06_06_180152_create_ingredienteproductos_table', 2),
('2016_06_06_180159_create_ingredientes_table', 2),
('2016_06_06_181101_create_pruebas_table', 3),
('2016_06_08_114826_create_detalleventas_table', 4),
('2016_06_08_114843_create_ventas_table', 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modulo`
--

CREATE TABLE `modulo` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `eliminado` tinyint(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `modulo`
--

INSERT INTO `modulo` (`id`, `nombre`, `eliminado`) VALUES
(1, 'Modulo de ventas', 0),
(2, 'Modulo Empleado', 0),
(3, 'Modulo Ingrediente', 0),
(4, 'Modulo Reportes', 0),
(8, 'Modulo Producto', 0),
(9, 'Modulo Gestionar Producto', 0),
(10, 'Modulo Medida', 0),
(11, 'Modulo Seguridad', 0),
(12, 'Modulo Sucursal', 0),
(13, 'Modulo Compras', 0),
(14, 'Modulo Egreso/Ingreso', 0),
(15, 'Modulo mapa mesa', 0),
(16, 'Modulo Inventario', 0),
(17, 'Gestionar Cliente', 0),
(18, 'Gestionar Mesa', 0),
(19, 'Gestionar Factura', 0),
(20, 'GestionarProductosResto', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `motivomovimiento`
--

CREATE TABLE `motivomovimiento` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `eliminado` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `motivomovimiento`
--

INSERT INTO `motivomovimiento` (`id`, `nombre`, `eliminado`) VALUES
(1, 'Carga Inicial', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `objeto`
--

CREATE TABLE `objeto` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `tipoObjeto` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `urlObjeto` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `habilitado` tinyint(3) UNSIGNED NOT NULL,
  `visibleEnMenu` tinyint(3) UNSIGNED NOT NULL,
  `idModulo` int(11) NOT NULL,
  `eliminado` tinyint(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `objeto`
--

INSERT INTO `objeto` (`id`, `nombre`, `tipoObjeto`, `urlObjeto`, `habilitado`, `visibleEnMenu`, `idModulo`, `eliminado`) VALUES
(1, 'Gestionar Categoria', 'Formulario', '/Categoria', 1, 1, 8, 0),
(4, 'Gestionar Productos', 'Formulario', '/Productos', 1, 1, 8, 0),
(5, ' Gestionar Medida', 'Formulario', '/Medida', 1, 1, 3, 0),
(6, 'Gestionar Ingredientes', 'Formulario', '/Ingredientes', 1, 1, 3, 0),
(7, 'Gestionar compras', 'Formulario', '/Gestionarcompras', 1, 1, 13, 0),
(8, ' Gestionar Proveedor', 'Formulario', '/GestionarProveedor', 1, 1, 13, 0),
(9, ' Gestionar Venta', 'Formulario', '/listadeventa', 1, 1, 1, 0),
(10, 'Gestionar Empleados', 'Formulario', '/Empleados', 1, 1, 2, 0),
(11, ' Gestionar Cargo', 'Formulario', '/cargoempleado', 1, 1, 2, 0),
(12, 'Gestionar turno', 'Formulario', '/Gestionarturno', 1, 1, 2, 0),
(13, ' Gestionar Objeto', 'Formulario', '/GestionarObjeto', 1, 1, 11, 0),
(14, 'Gestionar Modulo', 'Formulario', '/GestionarModulo', 1, 1, 11, 0),
(15, 'Gestionar Perfil', 'Formulario', '/GestionarPerfil', 1, 1, 11, 0),
(16, 'Gestionar Libroorden', 'Formulario', '/Gestionarlibroorden', 1, 1, 1, 0),
(17, 'Gestionar Sucursal', 'Formulario', '/GestionarSucursal', 1, 1, 12, 0),
(18, 'Gestionar Pais', 'Formulario', '/GestionarPais', 1, 1, 12, 0),
(19, 'Gestionar Reporte', 'Reporte', '/GestionarReporte', 1, 1, 4, 1),
(20, 'Gestionar Ciudad', 'Formulario', '/GestionarCiudad/', 1, 1, 12, 0),
(21, 'Gestionar Egreso', 'Formulario', '/GestionarEgreso', 1, 1, 14, 0),
(22, 'Gestionar Ingreso', 'Formulario', '/GestionarIngreso', 1, 1, 14, 0),
(24, 'Gestionar Tipo Egreso', 'Formulario', '/GestionarTipoEgreso', 1, 1, 14, 0),
(25, 'Gestionar Tipo Ingreso', 'Formulario', '/GestionarTipoIngreso', 1, 1, 14, 0),
(26, 'Gestionar Mapa Mesa', 'Formulario', '/GestionarMapamesa', 1, 1, 1, 0),
(27, 'Gestionar Mesa', 'Formulario', '/GestionarMesa', 1, 1, 1, 0),
(28, 'Gestionar Inventario', 'Formulario', '/gestionarinventario', 1, 1, 16, 0),
(29, 'Gestionar Almacen', 'Formulario', '/gestionaralmacen', 1, 1, 16, 0),
(30, 'Gestionar Motivo', 'Formulario', '/gestionarmotivo', 1, 1, 16, 0),
(31, 'Gestionar Cliente', 'Formulario', '/Gestionarcliente', 1, 1, 17, 0),
(32, 'Gestionar Factura', 'Formulario', '/GestionarFactura', 1, 1, 19, 0),
(33, 'Reporte de Ventas del usuario actual', 'Formulario', '/ReportVentasporusuario', 1, 1, 4, 0),
(34, ' Reporte de Venta Detallada del Usr. Actual', 'Reporte', '/Reporttodousurios', 1, 1, 4, 0),
(35, 'Reporte de Venta por Usuarios', 'Reporte', '/Reportporusuario', 1, 1, 4, 0),
(36, 'Reporte Flujo de Caja del Usuario Actual', 'Reporte', '/Reportflujousuario', 1, 1, 4, 0),
(37, 'Flujo de caja de todos los usuarios', 'Reporte', '/Reportflujocompleto', 1, 1, 4, 1),
(38, 'Reporte Flujo de Caja por Usuario', 'Reporte', '/Reportflujoporusuario', 1, 1, 4, 0),
(39, 'Reporte de Egreso del Usuario Actual', 'Reporte', '/ReportEgreso', 1, 1, 4, 0),
(40, 'Gestionar Empresa', 'Formulario', '/Gestionarempresa', 1, 1, 12, 0),
(41, 'Gestionar Descuentos', 'Formulario', '/Descuentos', 1, 1, 1, 0),
(42, 'Gestionar Creditos', 'Formulario', '/Creditos', 1, 1, 1, 0),
(43, ' Gestionar Ventas', 'Formulario', ' /listadeventa', 1, 1, 1, 0),
(44, 'Gestionar Descuento', 'Formulario', '/Descuentos', 1, 1, 1, 1),
(45, 'Gestionar Creditos', 'Formulario', '/Creditos', 1, 1, 1, 0),
(46, 'Gestionar Origen', 'Formulario', '/Origen', 1, 1, 16, 0),
(47, 'Gestionar Marca', 'Formulario', '/Marca', 1, 1, 16, 0),
(48, 'Reporte de Ingreso del Usuario Actual', 'Reporte', '/ReportIngreso', 1, 1, 4, 0),
(49, ' Reporte Kardex de Inventario', 'Reporte', '/ReporteKardexInventario', 1, 1, 4, 0),
(50, 'Reporte Ranking de Productos', 'Reporte', '/ReportRaking', 1, 1, 4, 0),
(51, ' Reporte Venta Cruzada', 'Reporte', '/Ventacruzada', 1, 1, 4, 0),
(52, ' Reporte Libro de Venta', 'Reporte', '/Reportelibroventa', 1, 1, 4, 0),
(53, 'Dashboard', 'Formulario', '/index', 1, 1, 1, 0),
(54, 'Gestionar Ventas RESTO', 'Formulario', '/listadeventares', 1, 1, 1, 0),
(55, 'Gestionar Alquiler', 'Formulario', '/listaralquiler', 1, 1, 1, 0),
(56, 'Gestionar Descuentos', 'Formulario', '/Descuentos', 1, 1, 1, 0),
(57, 'Gestionar Productos RESTO', 'Formulario', '/ProductosResto', 1, 1, 8, 0),
(58, 'Gestionar Tipo de Gasto de Compra', 'Formulario', '/GestionarGastoCompra', 1, 1, 13, 0),
(59, 'Gestionar Ventas OPT', 'Formulario', '/listadeventaopt', 1, 1, 1, 0),
(60, 'Gestionar Moneda', 'Formulario', '/Gestionarmoneda', 1, 1, 1, 0),
(61, ' Gestionar Forma de Pago', 'Formulario', '/GestionarConcepto', 1, 1, 14, 0),
(62, 'Gestionar Compras a Creditos', 'Formulario', '/CompraCredito', 1, 1, 13, 0),
(63, 'Gestionar Tipo de Cliente', 'Formulario', '/TipoCliente1', 1, 1, 1, 0),
(64, 'Gestionar Contador', 'Formulario', '/gestionarcontador', 1, 1, 1, 0),
(65, 'Gestionar Banco', 'Formulario', '/GestionarBanco', 1, 1, 14, 0),
(66, 'Gestionar Cuenta Bancaria', 'Formulario', '/GestionarCuentaBancaria', 1, 1, 14, 0),
(67, 'Reporte de Venta Detallada por Usr.', 'Formulario', '/Reporttodousuriosporusuario', 1, 1, 4, 0),
(68, 'Reporte de Ventas a Credito', 'Formulario', '/ReportVentaACredito', 1, 1, 4, 0),
(69, 'Reporte de Ventas Anuladas y Eliminadas', 'Formulario', '/ReportVentasanuladasyeliminadas', 1, 1, 4, 0),
(70, 'Reporte de Ventas por Fechas de Entrega', 'Formulario', '/ReportVentaDetalleBelleMarie', 1, 1, 4, 0),
(71, 'Reporte de Comision del Usr. Actual', 'Formulario', '/ReportVentasComisionActual', 1, 1, 4, 0),
(72, 'Reporte de Comison por Usuarios', 'Formulario', '/ReportVentasComisionPorUsuario', 1, 1, 4, 0),
(73, 'Reporte de Alquileres Usr. Actual', 'Formulario', '/ReportGarantiaAlquilerActual', 1, 1, 4, 0),
(74, 'Reporte de Alquileres por Usr.', 'Formulario', '/ReportGarantiaAlquilerPorUsuario', 1, 1, 4, 0),
(75, 'Reporte de Flujos de Ingresos y Egresos', 'Formulario', '/reportflujoingresosegresos', 1, 1, 4, 0),
(76, 'Reportes de Compras por Usuario', 'Formulario', '/CompraCredito1', 1, 1, 4, 0),
(77, 'Reporte de Detalles de Compras del Usr. Actual', 'Formulario', '/detallecomprasactual', 1, 1, 4, 0),
(78, 'Reporte de Compras Detalladas por Usr.', 'Formulario', '/detallecomprasporusuario', 1, 1, 4, 0),
(79, 'Reporte de Egresos por Usr.', 'Formulario', '/ReportEgresoPorUsuario', 1, 1, 4, 0),
(80, 'Reporte de Ingresos por Usr.', 'Formulario', '/ReportIngresoPorUsuario', 1, 1, 4, 0),
(81, 'Reporte de Estado de Inventario', 'Formulario', '/EstadoInventario', 1, 1, 4, 0),
(82, 'Reporte de Movimiento de Inventario', 'Formulario', '/ReporteMovimientoInventario', 1, 1, 4, 0),
(83, 'Gestionar Precios Por Sucursal', 'Formulario', '/Productosucursal', 1, 1, 16, 0),
(84, 'ProductosResto', 'Formulario', 'ProductosResto/', 1, 1, 20, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `origen`
--

CREATE TABLE `origen` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `abreviatura` varchar(20) NOT NULL,
  `eliminado` tinyint(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `origen`
--

INSERT INTO `origen` (`id`, `nombre`, `abreviatura`, `eliminado`) VALUES
(1, 'Francia', 'FR', 0),
(2, 'Holanda ', 'HO', 0),
(3, 'origen', 'O', 1),
(4, 'Origen', 'O', 1),
(5, 'Origen', 'O', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagocredito`
--

CREATE TABLE `pagocredito` (
  `id` int(11) NOT NULL,
  `idpuntoventa` int(11) NOT NULL,
  `fechapago` date NOT NULL,
  `importe` decimal(65,2) NOT NULL,
  `idCompra` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pais`
--

CREATE TABLE `pais` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `eliminado` tinyint(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `pais`
--

INSERT INTO `pais` (`id`, `nombre`, `eliminado`) VALUES
(1, 'Bolivia', 0),
(2, 'Peru', 1),
(3, 'CHile', 1),
(4, ' chile', 0),
(5, ' uruguay', 0),
(6, ' Brazil', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `perfil`
--

CREATE TABLE `perfil` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `eliminado` tinyint(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `perfil`
--

INSERT INTO `perfil` (`id`, `nombre`, `eliminado`) VALUES
(1, 'Administrador', 0),
(2, 'Costura', 0),
(4, 'Asesora', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `perfilobjeto`
--

CREATE TABLE `perfilobjeto` (
  `id` int(11) NOT NULL,
  `idPerfil` int(11) NOT NULL,
  `idObjeto` int(11) NOT NULL,
  `puedeGuardar` tinyint(3) UNSIGNED NOT NULL,
  `puedeModificar` tinyint(3) UNSIGNED NOT NULL,
  `puedeEliminar` tinyint(3) UNSIGNED NOT NULL,
  `puedeListar` tinyint(3) UNSIGNED NOT NULL,
  `puedeVerReporte` tinyint(3) UNSIGNED NOT NULL,
  `puedeImprimir` tinyint(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `perfilobjeto`
--

INSERT INTO `perfilobjeto` (`id`, `idPerfil`, `idObjeto`, `puedeGuardar`, `puedeModificar`, `puedeEliminar`, `puedeListar`, `puedeVerReporte`, `puedeImprimir`) VALUES
(1, 1, 1, 1, 1, 1, 1, 1, 1),
(2, 1, 4, 1, 1, 1, 1, 1, 1),
(3, 1, 5, 1, 1, 1, 1, 1, 1),
(4, 1, 6, 1, 1, 1, 1, 1, 1),
(5, 1, 7, 1, 1, 1, 1, 1, 1),
(6, 1, 8, 1, 1, 1, 1, 1, 1),
(7, 1, 9, 1, 1, 1, 1, 1, 1),
(8, 1, 10, 1, 1, 1, 1, 1, 1),
(9, 1, 11, 1, 1, 1, 1, 1, 1),
(10, 1, 12, 1, 1, 1, 1, 1, 1),
(11, 1, 13, 1, 1, 1, 1, 1, 1),
(12, 1, 14, 1, 1, 1, 1, 1, 1),
(13, 1, 15, 1, 1, 1, 1, 1, 1),
(14, 1, 16, 1, 1, 1, 1, 1, 1),
(15, 1, 17, 1, 1, 1, 1, 1, 1),
(16, 1, 18, 1, 1, 1, 1, 1, 1),
(20, 1, 20, 1, 1, 1, 1, 1, 1),
(21, 1, 21, 1, 1, 1, 1, 1, 1),
(22, 1, 22, 1, 1, 1, 1, 1, 1),
(23, 1, 24, 1, 1, 1, 1, 1, 1),
(24, 1, 25, 1, 1, 1, 1, 1, 1),
(239, 1, 26, 0, 0, 0, 0, 0, 0),
(238, 1, 27, 0, 0, 0, 0, 0, 0),
(94, 1, 28, 1, 1, 1, 1, 1, 1),
(99, 1, 29, 1, 1, 1, 1, 1, 1),
(100, 1, 30, 1, 1, 1, 1, 1, 1),
(91, 1, 31, 1, 1, 1, 1, 1, 1),
(97, 1, 32, 1, 1, 1, 1, 1, 1),
(90, 1, 33, 0, 0, 0, 0, 0, 0),
(81, 1, 34, 0, 0, 0, 0, 0, 0),
(82, 1, 35, 1, 1, 1, 1, 1, 1),
(83, 1, 36, 0, 0, 0, 0, 0, 0),
(85, 1, 38, 1, 1, 1, 1, 1, 1),
(86, 1, 39, 0, 0, 0, 0, 0, 0),
(98, 1, 40, 1, 1, 1, 1, 1, 1),
(93, 1, 45, 1, 1, 1, 1, 1, 1),
(95, 1, 46, 1, 1, 1, 1, 1, 1),
(96, 1, 47, 1, 1, 1, 1, 1, 1),
(124, 1, 48, 0, 0, 0, 0, 0, 0),
(125, 1, 49, 1, 1, 1, 1, 1, 1),
(126, 1, 50, 1, 1, 1, 1, 1, 1),
(127, 1, 51, 1, 1, 1, 1, 1, 1),
(128, 1, 52, 1, 1, 1, 1, 1, 1),
(203, 1, 53, 1, 1, 1, 1, 1, 1),
(208, 1, 54, 0, 0, 0, 0, 0, 0),
(213, 1, 55, 0, 0, 0, 0, 0, 0),
(218, 1, 56, 1, 1, 1, 1, 1, 1),
(223, 1, 57, 1, 1, 1, 1, 1, 1),
(228, 1, 58, 1, 1, 1, 1, 1, 1),
(233, 1, 59, 0, 0, 0, 0, 0, 0),
(240, 1, 60, 1, 1, 1, 1, 1, 1),
(245, 1, 61, 0, 0, 0, 0, 0, 0),
(250, 1, 62, 1, 1, 1, 1, 1, 1),
(251, 1, 63, 1, 1, 1, 1, 1, 1),
(252, 1, 64, 0, 0, 0, 0, 0, 0),
(253, 1, 65, 1, 1, 1, 1, 1, 1),
(254, 1, 66, 1, 1, 1, 1, 1, 1),
(257, 1, 67, 1, 1, 1, 1, 1, 1),
(258, 1, 68, 1, 1, 1, 1, 1, 1),
(259, 1, 69, 1, 1, 1, 1, 1, 1),
(260, 1, 70, 1, 1, 1, 1, 1, 1),
(261, 1, 71, 0, 0, 0, 0, 0, 0),
(262, 1, 72, 1, 1, 1, 1, 1, 1),
(263, 1, 73, 0, 0, 0, 0, 0, 0),
(264, 1, 74, 1, 1, 1, 1, 1, 1),
(265, 1, 75, 1, 1, 1, 1, 1, 1),
(266, 1, 76, 1, 1, 1, 1, 1, 1),
(267, 1, 77, 0, 0, 0, 0, 0, 0),
(268, 1, 78, 1, 1, 1, 1, 1, 1),
(269, 1, 79, 1, 1, 1, 1, 1, 1),
(270, 1, 80, 1, 1, 1, 1, 1, 1),
(271, 1, 81, 1, 1, 1, 1, 1, 1),
(272, 1, 82, 0, 0, 0, 0, 0, 0),
(425, 1, 83, 1, 1, 1, 1, 1, 1),
(428, 1, 84, 1, 1, 1, 1, 1, 1),
(273, 2, 1, 0, 0, 0, 0, 0, 0),
(274, 2, 4, 0, 0, 0, 0, 0, 0),
(275, 2, 5, 0, 0, 0, 0, 0, 0),
(276, 2, 6, 0, 0, 0, 0, 0, 0),
(277, 2, 7, 0, 0, 0, 0, 0, 0),
(278, 2, 8, 0, 0, 0, 0, 0, 0),
(279, 2, 9, 0, 0, 0, 0, 0, 0),
(280, 2, 10, 0, 0, 0, 0, 0, 0),
(281, 2, 11, 0, 0, 0, 0, 0, 0),
(282, 2, 12, 0, 0, 0, 0, 0, 0),
(283, 2, 13, 0, 0, 0, 0, 0, 0),
(284, 2, 14, 0, 0, 0, 0, 0, 0),
(285, 2, 15, 0, 0, 0, 0, 0, 0),
(286, 2, 16, 0, 0, 0, 0, 0, 0),
(287, 2, 17, 0, 0, 0, 0, 0, 0),
(288, 2, 18, 0, 0, 0, 0, 0, 0),
(289, 2, 20, 0, 0, 0, 0, 0, 0),
(290, 2, 21, 0, 0, 0, 0, 0, 0),
(291, 2, 22, 0, 0, 0, 0, 0, 0),
(292, 2, 24, 0, 0, 0, 0, 0, 0),
(293, 2, 25, 0, 0, 0, 0, 0, 0),
(294, 2, 26, 0, 0, 0, 0, 0, 0),
(295, 2, 27, 0, 0, 0, 0, 0, 0),
(296, 2, 28, 0, 0, 0, 0, 0, 0),
(297, 2, 29, 0, 0, 0, 0, 0, 0),
(298, 2, 30, 0, 0, 0, 0, 0, 0),
(299, 2, 31, 0, 0, 0, 0, 0, 0),
(300, 2, 32, 0, 0, 0, 0, 0, 0),
(301, 2, 33, 0, 0, 0, 0, 0, 0),
(302, 2, 34, 0, 0, 0, 0, 0, 0),
(303, 2, 35, 0, 0, 0, 0, 0, 0),
(304, 2, 36, 0, 0, 0, 0, 0, 0),
(305, 2, 38, 0, 0, 0, 0, 0, 0),
(306, 2, 39, 0, 0, 0, 0, 0, 0),
(307, 2, 40, 0, 0, 0, 0, 0, 0),
(308, 2, 41, 0, 0, 0, 0, 0, 0),
(309, 2, 42, 0, 0, 0, 0, 0, 0),
(310, 2, 43, 0, 0, 0, 0, 0, 0),
(311, 2, 45, 0, 0, 0, 0, 0, 0),
(312, 2, 46, 0, 0, 0, 0, 0, 0),
(313, 2, 47, 0, 0, 0, 0, 0, 0),
(314, 2, 48, 0, 0, 0, 0, 0, 0),
(315, 2, 49, 0, 0, 0, 0, 0, 0),
(316, 2, 50, 0, 0, 0, 0, 0, 0),
(317, 2, 51, 0, 0, 0, 0, 0, 0),
(318, 2, 52, 0, 0, 0, 0, 0, 0),
(319, 2, 53, 0, 0, 0, 0, 0, 0),
(320, 2, 54, 0, 0, 0, 0, 0, 0),
(321, 2, 55, 0, 0, 0, 0, 0, 0),
(322, 2, 56, 0, 0, 0, 0, 0, 0),
(323, 2, 57, 0, 0, 0, 0, 0, 0),
(324, 2, 58, 0, 0, 0, 0, 0, 0),
(325, 2, 59, 0, 0, 0, 0, 0, 0),
(326, 2, 60, 0, 0, 0, 0, 0, 0),
(327, 2, 61, 0, 0, 0, 0, 0, 0),
(328, 2, 62, 0, 0, 0, 0, 0, 0),
(329, 2, 63, 0, 0, 0, 0, 0, 0),
(330, 2, 64, 0, 0, 0, 0, 0, 0),
(331, 2, 65, 0, 0, 0, 0, 0, 0),
(332, 2, 66, 0, 0, 0, 0, 0, 0),
(333, 2, 67, 0, 0, 0, 0, 0, 0),
(334, 2, 68, 0, 0, 0, 0, 0, 0),
(335, 2, 69, 0, 0, 0, 0, 0, 0),
(336, 2, 70, 1, 1, 1, 1, 1, 1),
(337, 2, 71, 0, 0, 0, 0, 0, 0),
(338, 2, 72, 0, 0, 0, 0, 0, 0),
(339, 2, 73, 0, 0, 0, 0, 0, 0),
(340, 2, 74, 0, 0, 0, 0, 0, 0),
(341, 2, 75, 0, 0, 0, 0, 0, 0),
(342, 2, 76, 0, 0, 0, 0, 0, 0),
(343, 2, 77, 0, 0, 0, 0, 0, 0),
(344, 2, 78, 0, 0, 0, 0, 0, 0),
(345, 2, 79, 0, 0, 0, 0, 0, 0),
(346, 2, 80, 0, 0, 0, 0, 0, 0),
(347, 2, 81, 0, 0, 0, 0, 0, 0),
(348, 2, 82, 0, 0, 0, 0, 0, 0),
(426, 2, 83, 1, 1, 1, 1, 1, 1),
(429, 2, 84, 1, 1, 1, 1, 1, 1),
(349, 4, 1, 0, 0, 0, 0, 0, 0),
(350, 4, 4, 0, 0, 0, 0, 0, 0),
(351, 4, 5, 0, 0, 0, 0, 0, 0),
(352, 4, 6, 1, 1, 1, 1, 1, 1),
(353, 4, 7, 0, 0, 0, 0, 0, 0),
(354, 4, 8, 0, 0, 0, 0, 0, 0),
(355, 4, 9, 1, 1, 1, 1, 1, 1),
(356, 4, 10, 0, 0, 0, 0, 0, 0),
(357, 4, 11, 0, 0, 0, 0, 0, 0),
(358, 4, 12, 0, 0, 0, 0, 0, 0),
(359, 4, 13, 0, 0, 0, 0, 0, 0),
(360, 4, 14, 0, 0, 0, 0, 0, 0),
(361, 4, 15, 0, 0, 0, 0, 0, 0),
(362, 4, 16, 0, 0, 0, 0, 0, 0),
(363, 4, 17, 0, 0, 0, 1, 0, 0),
(364, 4, 18, 0, 0, 0, 0, 0, 0),
(365, 4, 20, 1, 1, 1, 1, 1, 1),
(366, 4, 21, 1, 1, 1, 1, 1, 1),
(367, 4, 22, 1, 1, 1, 1, 1, 1),
(368, 4, 24, 0, 0, 0, 0, 0, 0),
(369, 4, 25, 0, 0, 0, 0, 0, 0),
(370, 4, 26, 0, 0, 0, 0, 0, 0),
(371, 4, 27, 0, 0, 0, 0, 0, 0),
(372, 4, 28, 0, 0, 0, 0, 0, 0),
(373, 4, 29, 0, 0, 0, 0, 0, 0),
(374, 4, 30, 0, 0, 0, 0, 0, 0),
(375, 4, 31, 1, 1, 1, 1, 1, 1),
(376, 4, 32, 1, 1, 0, 1, 1, 1),
(377, 4, 33, 1, 1, 1, 1, 1, 1),
(378, 4, 34, 1, 1, 1, 1, 1, 1),
(379, 4, 35, 0, 0, 0, 0, 0, 0),
(380, 4, 36, 1, 1, 1, 1, 1, 1),
(381, 4, 38, 0, 0, 0, 0, 0, 0),
(382, 4, 39, 1, 1, 1, 1, 1, 1),
(383, 4, 40, 0, 0, 0, 0, 0, 0),
(384, 4, 41, 1, 0, 0, 1, 0, 0),
(385, 4, 42, 1, 1, 1, 1, 1, 1),
(386, 4, 43, 1, 1, 1, 1, 1, 1),
(387, 4, 45, 1, 1, 1, 1, 1, 1),
(388, 4, 46, 0, 0, 0, 0, 0, 0),
(389, 4, 47, 0, 0, 0, 0, 0, 0),
(390, 4, 48, 1, 1, 1, 1, 1, 1),
(391, 4, 49, 0, 0, 0, 0, 0, 0),
(392, 4, 50, 0, 0, 0, 0, 0, 0),
(393, 4, 51, 0, 0, 0, 0, 0, 0),
(394, 4, 52, 0, 0, 0, 0, 0, 0),
(395, 4, 53, 0, 0, 0, 0, 0, 0),
(396, 4, 54, 0, 0, 0, 0, 0, 0),
(397, 4, 55, 1, 1, 1, 1, 1, 1),
(399, 4, 57, 0, 0, 0, 0, 0, 0),
(400, 4, 58, 0, 0, 0, 0, 0, 0),
(401, 4, 59, 0, 0, 0, 0, 0, 0),
(402, 4, 60, 1, 1, 0, 1, 0, 0),
(403, 4, 61, 0, 0, 0, 0, 0, 0),
(404, 4, 62, 0, 0, 0, 0, 0, 0),
(405, 4, 63, 1, 1, 1, 1, 1, 1),
(406, 4, 64, 0, 0, 0, 0, 0, 0),
(407, 4, 65, 0, 0, 0, 0, 0, 0),
(408, 4, 66, 0, 0, 0, 0, 0, 0),
(409, 4, 67, 0, 0, 0, 0, 0, 0),
(410, 4, 68, 1, 1, 1, 1, 1, 1),
(411, 4, 69, 0, 0, 0, 0, 0, 0),
(412, 4, 70, 1, 1, 1, 1, 1, 1),
(413, 4, 71, 0, 0, 0, 0, 0, 0),
(414, 4, 72, 0, 0, 0, 0, 0, 0),
(415, 4, 73, 1, 1, 1, 1, 1, 1),
(416, 4, 74, 0, 0, 0, 0, 0, 0),
(417, 4, 75, 0, 0, 0, 0, 0, 0),
(418, 4, 76, 0, 0, 0, 0, 0, 0),
(419, 4, 77, 1, 1, 1, 1, 1, 1),
(420, 4, 78, 0, 0, 0, 0, 0, 0),
(421, 4, 79, 0, 0, 0, 0, 0, 0),
(422, 4, 80, 0, 0, 0, 0, 0, 0),
(423, 4, 81, 0, 0, 0, 0, 0, 0),
(424, 4, 82, 0, 0, 0, 0, 0, 0),
(427, 4, 83, 1, 1, 1, 1, 1, 1),
(430, 4, 84, 1, 1, 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `descripcion` varchar(250) COLLATE utf8_spanish_ci DEFAULT NULL,
  `precioVenta` decimal(65,2) DEFAULT NULL,
  `idTipoProducto` int(11) DEFAULT NULL,
  `idSubTipoProducto` int(11) DEFAULT '0',
  `imagen` mediumblob,
  `tipoproducto` varchar(12) COLLATE utf8_spanish_ci DEFAULT NULL,
  `eliminado` tinyint(3) UNSIGNED DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `codigoInterno` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `codigoDeBarra` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `idOrigen` int(11) DEFAULT NULL,
  `idMarca` int(11) DEFAULT NULL,
  `material` text COLLATE utf8_spanish_ci,
  `usado` text COLLATE utf8_spanish_ci,
  `color` varchar(150) COLLATE utf8_spanish_ci DEFAULT NULL,
  `tamano` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `peso` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `unidadesCaja` int(11) DEFAULT NULL,
  `stockMin` int(11) DEFAULT NULL,
  `stockMax` int(11) DEFAULT NULL,
  `ventadirecta` tinyint(4) NOT NULL,
  `modelo` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `estilo` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `corte` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `precioVentaCredito` decimal(65,2) DEFAULT '0.00',
  `conStock` tinyint(3) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`id`, `nombre`, `descripcion`, `precioVenta`, `idTipoProducto`, `idSubTipoProducto`, `imagen`, `tipoproducto`, `eliminado`, `created_at`, `updated_at`, `codigoInterno`, `codigoDeBarra`, `idOrigen`, `idMarca`, `material`, `usado`, `color`, `tamano`, `peso`, `unidadesCaja`, `stockMin`, `stockMax`, `ventadirecta`, `modelo`, `estilo`, `corte`, `precioVentaCredito`, `conStock`) VALUES
(375, 'productoitem1', 'descripcionproducto1', '100.00', 1, 8, 0x646174613a696d6167652f706e673b6261736536342c6956424f5277304b47676f414141414e5355684555674141414867414141426f4341494141414331304b4d654141414141584e535230494172733463365141414141526e51553142414143786a777638595155414141414a6345685a6377414144734d4141413744416364767147514141424a505355524256486865375a3162634650484763636c2b534a62386755625932706a456936474249504247427775355356704c704d4a675347426159615a4e4d4f6b435a4e6d4a74503249592b5a79557737504b6270537959506d647a547a49424c48704a43486e4a7253777234677247354f64526348475077426475794a646d794c4b6d2f63373744515a614e6b644352644654375038356d393973396538372b39372f66666e736b624f753161396363446f66646272656f794d6a49794d374b6c6a7759472f585259506d4b5a58352f77477131597248616747566f6150696a6a7a3736387373764a79596d4c426d327a4d784d70394d35506a377563726d4b69347676762f2f2b6e4a77636a4b46516941613575626e59723179355170372b673847676444367259423063484953676f714b697249777369383053436c6c2b2f34632f58727a55415231723136356c41686f624737576d4b74482f39354268566c64584e7a51305a47566c4b624b3642516968646c71686950334d6d54506b6b5264464946562b763538714b315a7939446738374835352f333633323431436c7931624a6a4b38644f6b537770514c5a676d45736a5672316b42786533733735454352564b31667635353849424351596a677730724b6a5178456f46793564757051316a52306a4b586d4661465930375a376176694e2f5869475a38516c2f31594f726d4279574f5933306d5a6b6c594e515141714156796b36644f69552b6b4b725230644861327470706c65667a2b633665505974655a544c676c325a43656e392f2f3430624e327a3069496d4b374e796373624778425173572b48336a734a79646e61334b66336178444743486c4a324773597365785149514a707246434a55594561576b464339637541426a654671787743385a374c3239765679435256453058657a61746575423157736f6a34794d64485a3253722b7a475455314e59693075626b354c7938504c553456484b4b55444645414461433473724a5366414130496d3249626d7472493456306a417252396658314f50372f58726b4b7935703139676b354171787351455a63714d67784844436f5a7a5a76336f785845563868786e506e7a684669514b59655a646d597430382b2f31744f6e74506a385742566e4d57735a786c3064336644444d496b306f426c6e5545646b43696f714b68413057526f413243504c5a524c734a44584c375432396658742f63337a6d444949372b597742666742396a64304c5852446e366962564355746f367171696a7a367855646a623231747059313636535459704955306d734e5549477038416d4531394146646f51422b78536b4463636f7948324b4a6749305a6f385764717563414f57794a5455314e524e5955785245444d685135377146523247747061634576593947706a34435645472f665337395635306f7a7a5745716f413946456f7151586c64426b4363784e567866766e795a79492b71476259334b3376673775642b505564304e474237334c527045344564456f5a69614c313538796252734c43734e626f446c473378715a3037356f694f4872414d616243732b4f7a4a586e7347524e7475446a6f67467a6d54777668646861784469616931374c334335584a784f706f3932796b6a6c6345696169444775324b61694339574f42774f6a70527a4b324e6d3250517a2b7a3244454a4a314a5075795a6a497869425a366533736c4c755a4570316b544435754252785864437a4547795a674b346c6852773871564b2b56316a3854437959487873387049354f32693254436b6f7279386e4d68584d79555232736e64514b427259486933386344746476663339342b506a2b666d3567344d4445532f67786b496d2f3678724c45776c6664677535342f662f3643425173534e4e686f6b436736524e48516a53744d3153624a4c67327a654754324965544d593651774e457173376867595849736e5365614335615a794f2b3762337434756e344249566171517641582b7a445050614c6e454133376e7a5a76486e6f7951552b4b527030494a643752734973466f362b76726b625a575468676b674e753664537642736d597942777734676b6350665649547356564b6d4d7877486e3330305750486a7547677451707a494157784158516e497443574d4a6c643463534a4535724a5446412b79744b7979514b6941775a756a31367656772b546d55574b576f575a59464f2b4135594b734d794e4372626f4a2b5668386c31684778703261646e6b5168534e414b464a3939307841532b4d53746a30696f714b5568346d33785732386f6f4b4c5a734b34454e6b42794d5039574b4d426a51476e45544b793876782b476257736b414c37464d4f38647061345737772b5877776935775274546c66594532467261656e5238756d46437a38614c7832666e342b6a674b505044773872487758566f56575a323759544b5549506234575a78494f4a457a74344f4467794d6749576f5a6c72534a4e59434d6b30724b6d4159524f5061386a64687946772b46493573636942734a6d74684d55674e4f4442772f6973703939396c6c494a344f635366455379592f366a554a536a2b417841566f50487a344d313457466851382f2f444475596d4267514b744c51316a627a7039372f665858315931494d356b456242354546787a7a46693161524772436c5263547a4f673667487a6853672b54355348544a634359466f6e364b4373654947533852476c704b667a715156464d6762594a5965545844654b48486962726e364c714b705a6a75706b5032544d44353579434e36565477634b435844314d76704e4430352f32336c36507042436d59426b673163574c4630635a4a707445484445683964386d35514677767544697859745268736c63496c4770424e70694e446d4d2f774a4e6c49416779504a345048495951636a3338435431396657516e685a63383543706555706f4a6270343964565832653769504f2f743272557256584b4a48716c6857594b322f667633662f44424237416335787369644d317337646d7a7837536e58474474377531353863555830585553664458484543676d6f4e7932626474333333326e575932474f6862545259464a5662514578647533622f2f6969792f456b676959382f56656b6f6a47503768634c6f4a66346f54446877385846685a7146516e41724659304b734e7039506633662f333131356f706b56414f6c435a374d5a4a596f676b474247695a4d4b3634754a68345471744c4a47425a626d7165344339524438487769414677455778396a446c66685661584c504141346b5949534452543670416f6f6947336f4b434151534b756c48387351767a483832694646434568524575594c4b2b4834672b543434664d64326f4462534f4a486830646454716457566c5a6a7a2f2b4f416f7957356831384f424243587530636e4a684a4e473575626d516d2b67774f5534634f6e516f4a64756a41626645556541664f50555255586939336b53487958454348794b68434b6d34754f5441414b4a7866424339632b644f2f49615a337a614541355a78644d6c3857674f4952737645634565504874584b61514a6b6b55776659734364554164454a2b636b6b6e595163706a523545337072415645352b586c7a5246745047536e4a634374714b6859766e7a35344f4367792b576149397034454f4d53323553566c5246396e546c7a787546774b43384470413736675867534d6e71614c6c474571514452363961744b796b706b563974536e68545656576c4d4c747935636f48486e69414f7372723136396e4f325a436c693162746d624e47757879774d4f69646a4b48365946476f5169754547687061536b57696b533951696d5267764a523168747676484874326a5561553863684663467a6a4f3774375a56767361786475355941727257314e5a6e6866546f436c717572717948743674577241774d446e4a4e524d4a5165503334384a79644855545173693244466577775044394d4f63696d4378735a4733506d71566174774f6b79443275636362674f4b534f47337271344f6438773243464834417a5a415958587a3573324b3134372b77316b757070654f6a6737696c546e476463416d5070626c446a4f51506a593268684f474b4f534c5a4d6e55317462534a6761696162786b79524b384f78657a6d6370307a5145326b42336b697253685a57526b4249726e7a352b5077436d32744c5241722b49364b4b447438764a7969556a773346794d3631443775513036756e7a354d72566378527a4f326f434567554d466a433163754a43396a6a324d4a58372b2f486c6f68426b6f37757a7378506465756e534a4e764b62596546545566546262372b4e344f31323556384e3051565749684957676b78444246676a64727564755671386544473961395a5a42706942596e676b526d436a5133616e54703343434930624e3236453861616d4a74676e673135395070397943662f424c466131423058647a4668425155464e54593159496f44587037336237655953452f364c726951414c634c5036644f6e662f72704a39684130596950464d5932624e69412b4841646b413654744e512f787074477330416d51592f6e754978725a476b776a63774549412f6430694474454c49715032444d34383277574732576f50366a316b6443744d6b4b6e356941757247686f59487362467872634f504757744b3274744d6f47727267424839393965726c72437a384b6c3046595a75722b46466378317476766158326464766e77695a7a6866354a345a532b4956706e756269346d464d38553071654a39437553537349793961514a642f682f507a7a7a3153624271566d7567397933534d6568794e484245664b324b484d362f45346e4536707a6374332b7366684c56507449684c544534317a7548486a526c39664830527a4f4b53496c78434253324448546d7243662f77535061775a79726368726c2f725076486a6a794b564d522b376c4471696b44576f576d7771335a49502b674d546f6141394f38732f3776763535352b584c612f306a667673484f697374734345337838493547546c4449304d467863573044686743656d4f416c57544a3532656147594d4b74584659704d2f3277444c796878614c4c445072736f456b4539544f594e414b4d6942776a5069626d6c713172663836725856704545596e6a4973687338786f7175724538317833694e304f334869424c5251704a594e384f544a52756753516b4b686151345a43744837397532624e32396552635639304572305271534e723243764579316a314a70617257317462586750657353657069776a46344a55486834457876316e7a375147417667425a53787261745a4c6d334151494a4d536868476f63533348504d675a476870694a39793061524f643950623258726c7952565134417851536f5a4a746a593434686e424f70314e59727179736c4371686c5477545149596578553870563663745249796949574835546d687562696157355a684744436462464b755a434a6f444e3530514c2b4e4a37736f7955425164666a4c6b5976706c6d656a383470527633727a5a336430645458646d426b4e6a343245345a414157617a445531747043526b5339746e6144326e41535949624775493648486e6f49577476623235314f5a33563164553950543164586c376f776f744a634a4e474c466930714b69715369306d3542306f6e35666d69374e476367474c304b434d53415147646141794947396442417853325973554b2f414d6e4554596b3469744554527430566c74627937556f757147685161597165747a6143473542337046714259766c374e6d7a386b63613070706c655836675578794f69516e746c7a6f7863466865756e51704c7051444e4636597053792f3345315738386d544a306e52746669636d444470416836496d6553755448356a59794f4242336c75482b76736d5151777936364630784f5049526170436f65774e6a6a6f676b316d34754c46692b783457375a737753397a46544566446469785a426f49427a70752f5858496d4b43346a763337392b4f446d4f79536b684a6342373349533477303552656f386c582b6e43347077354555753479497642624142555066662f4e7453556b787066372b2f6b63656677786d56362b754c6973724f2f436e502b666d324874366576507a6e55514b4d494d4534336e6c6f45776d2b7555475656565673457952417946547039616d4b3642795a47514566735670614e6270384f4748483749546b694841526277416a3846476c357472392f6e473659657472375330564f4a64756554656f436a367a546666704c764f7a713742775545574344705061793050447738546f5a49586479466333306e526d5662627156504e566b7549413454484e2f625959342f68736d6c322f4e695068515635355045727449396665597169695a3076584c6a416a5a673055594855705231342b4f765872384d795178426d42634b3156684448456c4a656443695a7a497a756e683461454e51574f765071317463693671416c394d4f2f2f2b5561647666323953453751395a337a4c756e6d554530786f59576b31426f7648506e546e57377368485050664845453854496b50374f4f2b2b77754d764b46736255327778516946596d4e6f376f6a656344754232746e416f51683845797045544a697a356539696635537a534b50776d4664757a5951567a426d6942576565474646374448773077344446413046424f3076506261613134566d6a587845466f424c464f63796f6859777531544c594956717834633953752f4f7833466e507a50386144507a7752633765356158564e442f2b4c723434514252484e32346d6e65666664647773526b666756642b434a534a6850756b65384e48454d6b51322f76766663656e68716c2b33772b7a747947764873776747674578546a78612b2b2f2f2f3654547a364a46676974744c724541482f4b476d496c775934732b516952556f7977414c464971757834366d466162616a386c6977384e58623150424863746d30724b554c6d7a4c4a392b33614d7976587877636a4e6b506b2f637554493774323769556b315532494142544b584d4336575741477a7341796257766e57332f6e5739314b4b654770414d37552b5868684a4e4d45346b2f2f7878782f444f4c754b5a6a55617345796b6a417868524569354b36537835434758366547776830556e326d724e344766544c37663551785a37646961615039643675736a706447526d577533326a5a753376505758762f6f6e34764a4f6b572f766a414a4359476e544c617576714b684939717634495234356e434e6855453846616f316d6c497a6b392b37642b2b6d6e6e34704672626c64425a417a4958505469654e536c436c5a664e2b536f704c35334e51374e6e71366f51454e4d52787045424f4d5648513458433758776f554c43776f4b654d53757269374e476763594e7141337252773759506d7a7a795a39446873422f416c72706136756a6a7a725268566638464a4868332f4d35786e31636d76556732366b63617849464e48736a527731455973515244524371745846446e536b484a48566679616a432f424f304364447a346a6e465a624653427165416554787948444e6758423535556f6c30676a426a69307a4b32507638337664376d486d75724b71617432476a66562f2f384c6a6a666d505143624b6455534135325977375057732f5369334678354a416c6a325053614a6f6c4173715435743455624a36426269482b3549476d376e4d634b6e58497954556e5632416d506a353836644a61764f687358743962413962743279626354726b52443264792b2f394d6f72727a415772704a356d686d4a556e514565427234366c48664b6d696d7577474347414171672b4a6f526a49564f467732353374375532484c7971797265306a2f68675a7a4136635a4e6c76702f424a4559382f4e2b655354543270726138656a2f684e5253564b30446f364f4c452f6f6d2b47335376446f61426b464d62784a5767744c497853745a30676c6f7938494d556f71475946756a436a7147516a6c4c6a6a6f3967766e4e5337562f33453633374e6e7a3444364c53306556546e616545655a674f2b2f2f316170316c6f7051704631724a6153546a5467396b382f2f66525858333246786a6e79617459774d44786d676f772b5a6a326a707a7252494e7a4f3842694c61745973676f687239664648744a466968444854616a766430757a33423941303130722f7a4f48446a2f794b327845706f6d734778644b68534a563838795943535849643463424e487a703069444e6b4f466b43586374614f586167723343616f6f5243384f5372784149734e6d764145717264554e656c2f4f75543230656b4d652f594439392b632b796650797a36525a6b6c454d7a4f73553845417a546d4a32544c6d5079444a5a59766f68734c5842355242424b51514a76444e486c6336745431486d584b4a44333333484f45466c495575304430533059337176554b5a73694846775857594b69317455576b726132495739412f676a6c77344d412f6a6836526779746a3452446e64587532624e6e795039415645516d2f433132544141414141456c46546b5375516d4343, 'item', 0, '2017-05-31 14:19:23', '2017-05-31 14:19:23', '123456', '123456', 5, 4, 'materialproducto1', 'NO', 'colorproducto1', 'tamanioproducto1', 'pesoprooducto1', 10, 2, 20, 0, 'modeloproducto1', 'estiloproducto1', 'corteproducto1', '200.00', 1);
INSERT INTO `producto` (`id`, `nombre`, `descripcion`, `precioVenta`, `idTipoProducto`, `idSubTipoProducto`, `imagen`, `tipoproducto`, `eliminado`, `created_at`, `updated_at`, `codigoInterno`, `codigoDeBarra`, `idOrigen`, `idMarca`, `material`, `usado`, `color`, `tamano`, `peso`, `unidadesCaja`, `stockMin`, `stockMax`, `ventadirecta`, `modelo`, `estilo`, `corte`, `precioVentaCredito`, `conStock`) VALUES
(376, 'productoitem2', 'descripcionproductoitem2', '101.00', 1, 8, 0x646174613a696d6167652f6a7065673b6261736536342c2f396a2f34414151536b5a4a5267414241514141415141424141442f3277434541416b4742784d54456855544578495646525558467867594742675647526359476867564678675846786759474267594853676747426f6c48525559495445694a536b724c6934754742387a4f444d744e7967744c69734243676f4b4467304f477841514779386c494359744c5330744c5330744c5330744c5330744c5330744c5330744c5330744c5330744c5330744c5330744c5338744c5330744c5330744c5330744c5330744c5330744c662f4141424549414c634245774d4245514143455145444551482f78414163414141434177454241514541414141414141414141414145425149444267634241416a2f784142444541414241675144425155474177554842414d4141414142416845414177516842524978426b465259584554496f47526f516379516c4b78775350523842526963704c684653517a51314f4338574f697375495746384c2f784141624151414341774542415141414141414141414141414141434177454542514147422f2f45414455524141494341514d434177634442414d4241414d414141454341414d52424249684d554546453145694d6d4678675a4768464c48774938485238554a53345255474d324c2f3267414d41774541416845444551412f414d7474564d70436c36554b54794c333834726b4b4f6b3231563859615932615669366b6b43497744423375766150396c3866374146505a68626c3778773468627438366c734c5879706f6e4c536e4c4d6357505451637252352f783273756759412f5356745172444531435a6741334a50336a796c6274572b3453727965444173577870556d577857644f4f7362766839396f72334d784f65677a4e72773351693342497a4273436d4c6e6c306a753645376e6a54727161346343584e614b394f4d4d655a375834736d564d4b474b696e577841383471585674573241527831457833317964414a344d655774786c4345745a6937774a31566a2b7a306c76533037795359745146546c466c4d334837515172475a736b725376496a50434e6e564b57366c6430616e386f75616653372f6c4b657138525656776f3568474b556557616a735a68546b31476f55434e44465055304c6b68656b6f344e39594c386335346a6243616f5a77564d5874357772773076527168356e4950457058304d6f6a4b726c6a4f6d316e6a306570787555343469716d4951387a3275546f77386f7236784d344145366b39637779696b7079677466664676535556697345446d49746474784575374250434c506b312b6b58356a536551634950614d51636d664a544841596e5a6b4a6b6b45756672484541795178485352374162767245625a3236536130646964506b6949356e4577657471784c316a4f313276476c484d4a526d434978554b30744753504831626a704438737936524f49336778646f31787879774d67726d4a73576e46697037336962624365535a7155494f6b7a794d515754547471736c34554879424c5a72484d61794b78514f762b666c384e384e4668422b7351315950326d6f37646679786f2b5a622f316d567372395a3932362f6c39596e7a4c662b736a5a58367a3056432f6c39593450623654746c6672507632686679657364356c762f41466e624539596b7238476b7a566c537052633674465236797a5a4b7a527031747453425662694b6c374e30543353727a564659314a36533650454e586a676a38526257344268456c4379716c55737047674d7853695275417a61776466695662326557464f5a532f724e6a6b54525565413046564b412f595542414675316c736f644876476b6a4c61505a4248346c57377a4b44686d422b41356d573267396d4e456f6b79486b4c4734456c4375524230384951316751344a7a48314f783549676d7a6b71584b6d2f733661594266787266634e37373456714e6256585675504d6464555647356a394a704b36596853776b4d3664786a7856746a574d624d59426d55374174695a62614b586d544d563369744a444a476b58644932436f375463384c38554b4555746744316a5859476575656a7345756e4c645a34506f49336835747169696f343954384a533854744675705a7335416a6e4556796b5a6b7159735343654d592b713051724a77636d4e703052745559455531654444734d386c544b5663412b363332696d75703273412f31693637374e492b4d644f786e744c6855384a6c736b4f64534459635359304b624e337444704e63363668777a452f536153686b4c6c4a4a5776753644393478704c66354e486d4f6341394a6b36692b7479416f356d64704a6f6e31716b35694141535735572b38554874634c6b5459755536665268694a70714b6c534a71436b676748667a7439346e773355423951417842394a6732616a7a454961614e6373486848725755475a3462456770436e335173725a6e50454946635361656b4d475232676d666545647a365476725066434a35394a45394554496c526759633953576a675a786b79494938775a564d6e424b53654556724e5174645a5939704f4d6d592f474b35536938654831747a36697a63335350417849596574347a4c52694e5761476d44787361504464594a6c5749344c327153417370654e7761657831346a61645a355a35455753396c6c69616c57594d684c4a3638594b765332467470474a5a6278437371654f544846466753455a53727645457134444d642f724768586f315135626d55724e593735433852684e5679697957416c594c6e764b4f304f3542387841372f6844326647664365666b507048655a384a336c2f77443943664359666c4d443576776e62506a4a6957535154756a68754a7a69647541474a345a536543596767656b6a6333724d4a563765544737737443547a424d5a703853636e32514a366d7277477650744d53496a6d6255315367535a684849572b6b5633316c70505761532b46365a4341466c4e5074424e49494a637855735a73354a6876346655446b435734426a7166326c7067377a46754d4931466247766431457966474b41745149375452556d4f55367947515153564d53474259376a47645a706d72544f507a78504b4c63704d41724d6343444d4a514d6f5a6a784a335231656c4c3763486d634c44754f5246456e6149494b7a4b41517066764e78306a58302f6d3044414d3968346434596c6d6e563248586d4630693172424b7043696f2f456f4d5042346332534f56356c7131555134562b505151695257545163716b6744714c526d616d6b486b395a5631476c7074724a484a6a4b6b72796746373374654b594c413455386478504d5055564f444c5531696c4f46756f6272365246746c6c6d4157504862744172566732534a58684370636b4b496c714d3166764c62516267473352713236332b6c74526554316c7658363679374366385232684f7a38394b3668544b4a49755277384e3055744a57567572336a764d36747435494861624757744a4e6a655059565831574e6c547a445a57416c336a46334a37474c6e775559344d3037452b7a474a334764695353596c535a426e69706747706748765376336a4a436b394a4343424235456d655045795a374c5665425269535a42457a574f56705334655046654961687a615542346a674f4a6d31596734796d4b2b573262544f4559345976534d2b3452366961436b584672515738387a6d45635356523766523267796b346e7379593134633175487a4f5663385253716450424c67454532484b42747459647059727154755a526d576f6b454b534c6c2b4a3443424747584851787675746e6769444a6e384a307764556e386f56783259797a676e725750764b6c31696438396667672f6c416e482f65454550617638792b6a576b6b48745a6836676766534352567a6e6359466d2f474e67456447656b4a4f705952634c71424d2f79324a6c6371615341517a486e417151526b5357727763476352727169356a7a694c6d665361612b424b3664524b68654a5943545a67434d707447453934634962355756356c5262393345794f4f314f56516d494c4b4233657350303165564b4e306c5478476b5053564d324f796332525555637a4974357372763550694f2b33573468566d67596b7578776567486245384e2b6e326e616676453663626c7a774138792b357653452f7047704a50454778646a6254482f414c50634f6b66747170696c4570524c556f4a554e46756b5a672b384234304e4859702f2f5a3245316631397830336b6f666e3870714b7a445631436c4c6c5453512b69777a6372525566537465533162452f4f58394e724b36554164667446502f782b616b2f69424e394346666e46532f5457566a4a6d6a2f384151715966302f32673150517a684d496d417251506459702f4f4b6c6f554a6c65444d7a5872573444566a35792b6254544151454a5a4c5863683338345147556a322b737954572f59522f676c424e4d7371556b4e78422f4b472f38417a72374638327267534d67657930304a5169576c4a49426d5a577a4d48626d59334c37456f6f555763766a39344e61466d4f4f6b477035747a47526f3379544c5472504a31615259474f314f745a4f464d6c4b51657372546943754a696950456453502b52686e5472365366396f712b614450696d6f2f37516630792b6b757071306c567a466e512b495774614e7869374b4142784a314d356c5133573237627752306731706c59776c4c63435054364f3364574a5659594d6e467a4d436570304a674547415a426d42326f6e4d73783466554966314c41787050457930716665474d6e456c4a6f384b6e36526d33704c494d306c4375384a303346736c6a78484e4f755056614f346a6956584568694a376d7258743169385158594873444a6f39365a53757170716c484d7339773551316e35326968347271636e43507973317448554658326c363877796e71436b417155533767586538577443746d7a645932535971344c757771786375726d676b64706f6434455a583672554b785574304d76436d6f6a4f3243544d536e66503643462f723776575746303150704a5557495463347a4c4a484330485672374134334869446270367470326a6d4f4b75705374436b422b383179546270476c667156644369392f784b465652527735375169686f6371456745734f664f4836656f72574144453358376e4a4d346e557a376d38566b546965383359346c434b73704f7347617752464d777a67773671786a75684c376f46456270462b576f4f5a6d712b63344d58616c775a543164674b474c38457870644855496e49334f4644356b485552664b6231496e6a6451634e4e506949524c6e706d536a2f4148656f377956664b7333496a4e6464366b64312f615637686c515a7374683652557853386a71413149346b365253324f32646f3752326a4946626b2f4154706d4559637043564f7764524d615768713874446e75594c32637766474d45564e4b534a6d566e3350393444574948344a6c7a53363056412b7a6d4a367a5a42536d4b5a774234356636786c666f30586a4a2b3062627256735572742f4d6f6e37477a43484651482f4149663678413071676466784b4f5a714d436b47545435464545676e546e654e50545765567053765538342b735555793871716753784e6f78745458592b47655861384467536c4b54357768554e61353959776b5375644d524c3936353451726258587a594d6d4571765a377653437278646a5a41626e48667151446856474939644a6b636d5356697957447939594e7452557777556b44534e6e337064496e793148756e4b72676449577464544547763254467658596f39726b516965376930647243537935424269557869473045397536593166444e5379657730723356353545594a56487038385a6c51796137415151345744336d647833414a637739704d55516b62685a2f474d6a5565486f624463787749366f623243784a545958526c575873796b664d46462b75735531716f5a73484f4a6f766f7971354857573472674a706d556852584c4a7364344f344674583478553853384e4e47485535552f695545737a77596668694a6848754b385152474e2b67314c484e614837513934376d4f70435662784776706448724150615445577a724a564d737161326c2f474e2b6d68776f7a326e5675464a6d652f737163354a547165496a7a7570384b3162755843352b73312f31644f414159594b46514b48536371626e72476c587062554e536b634b4d6e352b6b516231496242354d5531594a55705242446b36686f784c3363327337416a4a376a45765645425141656b587a5558697365737471654a394946344a4f5745357a78475567586a5371484d7076306d726f5a583461656b656f303666306c6d486333746d63536b37423153304261706b71576f6e2f44576f35674f62413335526c6d3274654d7a32442b4a707649414a487232694846746e3671544d4b444a577069774b41564258416872785972777748786e48574b3374417a784f7a395973503243303362385475456b36414a55784f75345157304a316b5072556267474f6349396d3036616e2b385452546b6b736c6773734e3579715943464e71305667424b463270797041475a6974744e6b35394850374a6a4e546c436b72516c544546396542746f3861656c74467162685050366f2b3049667358554a6d705868382f75352b394b4b72464d7758595043645568552b597631694d3547444f782b7971557152544b544e526c58326967656558756739437a786a48584c546577504b3853797450394d592b4d333875656c7459324b7458515642426c646b624d394b776434672f4f71626f524f326b547a796964796e306e5435687969505948704a356b46464f3869464f2b6e4876455351473751657071305a536b582f4144696e6672394f617971637831645437736d4a4d5172444c4444336a763452673261697972672b38652f704e47696b57484a3652504d6e4652636d2f7742596f737863354d767167515948535249674d77737a347074306a737941655a4a61574159752b764b4873716841564f665763446b387775546961304d6c517a446e723577354e52596f43754d6956333079506c687847744e4e536f68514e6e76794d5071565461726738452f615562465a5156496a7843626152374d44496d575467794e584e79334a30694c5832444a685670764f424d746a654a6d5963723930526861725747773752306d356f394d4b786e7646394a654b31664d74577a6259596b646b6b4f2f58375236505441655542504f616b6b326d46416752596c65554c56416b77674a537063426d46696642556449784c554b6778496b736f4e69416573515556756f6b686d48517852744267737673315445444b704964686f527674474e346e345a543554576f4d45632f417a53304f74733877567479444d6e4a3936504d306a4c69626a3949796b5271494a5565624f69542b476e6f493954534d566a35547a317839737a41346e6838677a5571375359685934483376412f61504a6b6f4a36536d3237595151434946503257714656516e497155706c35584b6a6d55516f48335568394f62786f4b796e6e50316c63366b4162647630682b4b794a435a387563745579624e53775437796b497332645141596449744d654e78357a3963514b677847776344392f6c474748536a32717770556f6f315163336675412b5a4c4e71397846634a5275494f4d794c5862594d412f486a694d68504b41784b47757a6c6e2f704256586d6e3253526a393557617062446e426d595849714a6c535a7976325a5151507778716d373275486363594839635762646e365333354e4972326254385a5473786947494763453164506c43697179556a4c7864306b74347846744e566c674c4145546d7270386f37546769626f6f53513178447a3466533634516b544c38786765524b707449746e5363335458796a4f3148684f6f5162717a752f654e533943634e78466b327249336d50505757575a7743524c71306779686463654d4c3332482f6b5977554430673032753578327774316a6c6f2b454c7775626d462b4c2b55584b416f41725047546e3752476f58615942584c7a4c4a423377753167584a4573307274514177646f58485a6b6e69494f4a4a4679334b4a786d5133417a5043327636654a494742677965656b677543335a684357346655464d774463645962575172444d58665747512b73313942564833483030365236767737565a2f7045386a7038706733316638344c74444e6158314d48346b354655626f4579387945795a486e51334d3941717779686935564b393031744572384e4d622b6e507343595634397379307a4446674756797372584e69444a416c4b702f4b427a437850555468776a684949684d73386f4d5144435a66534445417a366f6c7055677058377044486441326f6a6f56666f65734e475a57425471496a6d624e5379586c4c49354b7636786a4e344c566e6455325067655a704c346d34347345585461636f4c4b444552536174716d3274316c746242594d6962436c4863543045656e7248734359466e766d63543272326d4a5758414574495945574c386a764d65664e597462474a3671756f56727766764757487a5a6b69556c64544e43625a684b64793544674b50324556646c59733435692b624f3331694b767261326f56327165316e494937675233555a527145376a6f3238786f4e6353634d635174744e593445337545347968614a6333493361416c6c415a304d4e47346a534b41735a4c446e6e342f77434a55657663764278447039524c6e4579794170444d584c6133596339494332313262326362666a31674a57794463447a45754b564b5a4d79584a4341417339785263693179432b396f42382b38414d43574b327970596e6d4d3661736d7043696c4353526341456771355874427063773667524c6f6a6454446154464173417671416238393357454a7258573359547846767073646f77773270576f32515266586333326a663074317a734f4f5057564c366b55645970326f57424e7476414a36336a433863526631655637675a2b664d3050446754567a4d394f7159793153617131786456566b5755716c75756d614c5a756f42415339322b736375304f4d2f475a4f7672494f36555470624b4934474b68344f44476f3234417a784974724545643578497a4c454a6530516f796343435474356a4f524b434158627975656b61315641714747354d704f35667041617041444f6563556456574662325a5a724a50534272496545674757414469565a2b454669486a316a37445a6853454633414c50794d58744c593164794f4f6d63452f4f5a6d6f554e75474d52336a6c4c6d6b6d784b724d33363452366e58564236506a326d626f724e7433586a764d544f70566733517279663652357a79484236543061576f662b51686c464c5638717649785a7252765178467a4c36696169687367576272473170736841444d532f6c7a4c544675567a4b706b635a30485841795a4f5549366359644b6778466d4649686767525674564d615142387968364f6674475034355a74303250556a2f4d305044567a646e30457a32485636304b736f74774d6564306575757163633865686d74714e4f6a72794a6f4d5254326b704b78725a75687330656b315946394b324472782b5a6b36636d71776f59376c426742796a57555941457a6d4f53544f625972374f714f65704b3569706849566d56336c414c666351506448384c52515854765837724437545262576c786870666957795571657347616f5a557379556c51647443705770614b67304e694d53434930613442646f7a435a7544706c793053365a53554a51536370555759334e7943626b77743942617a5a3343516d72555a3343496c374a7a753137564578414c714a54327138727133674d574f756e47444f686372743341664845594e616e63475853396a35715a71567071585131304c4b6951514c424b67413665734366447979344a4766574750454648474a6f55344d566f416e4c537061584b564a53653654597335345767763841357551564c63664b563231677a6c5668314c6843553672556649517866444b78314a4d533272627349525459524a526349633639346b2b68734973562b483661733543632f486d432b737563594a2b30504558506c4b30794f30307a38552f776a37783548786f5a31492b512f76505165476a4e50316d5571703055305762694b496e724b694c6c615337586751765a7a6153574a776c356d554764392f53466172525765587637544f31537061577242396f63346d33784751433077454d7258724647785273446a763841764d71697a48394d3952415859633453566c6a475a494c4976455978794a425547586f716a5948646f64344a69777572744334697a554f534f38436e7a435362332b734b4754795a595251424b46477a775145594f7347556f7730434f41426a58425a3667447742423859344d794d436f376a39355331614b534a735a733872596e796a3162327661636d656656416e416e6a515369546d58537844314555786e73346151654a7979754743435a5773524a6b536c535947544c4a5359345344444a516867697a4355694445475a6e62476f64614a592b45456e716450703678356a782b334c4a574f334a2b7332764371384b7a6e767845644e4b5570514351535447445657316a6855366d61646a7171354d336c4a54414a43543849486d493939545346514a365479396c684c46683368735759695a795959535979437a54416d45494f565175474a624c4d515a4d4951596b534443705a6867676d4579344b435a636d4a677955644f6d4132786e4e50496652496a793369677a7154386850542b466a2b674a6b3669644664466d7370697171584674424868346a6d306b7855354b7053437051754d757238343061634f7072395a6e367578613346784854764f71374d5975466f3747625a546147782f35456559314e5270596b653665735872744d51526456306831584c794b626a6f6555566d553965305455336d444d6f4333313352787a67434d4b3436547736504553652b4a47594949644a4b77655972647567774931524b657a4a50644250495177656b5a7541484d6e6a4f4c4368706974545a6751514f4b7477686d6a52373777692b7566744b4e6a492b36787664416d6e3246784372724a435a382b584c6c49554155414f564b5364464d666442335236394b5750552f695946723170776f4d645656644b6c4b7972566c504f417375717062447961364c6256334b4f495854565346447571536568697858625734396b694965703139345364514853594e2b566731384e41304746715978684a4e425263675578306d57796b784967457771574959494a6c7346426736354b5661704236694550556a2b384d787975793944493039444c6c6e4d6849436a415536536d6b376b554179624e525a594e7248694579454d354a3169796f696d50595336446754477172776438494a6a63536c645349456d5342426a566a63353658696e647136712b4d2f614d48786c6b69714234673844614170316c567077447a3859653350534d4a4b3475434c4d4e6c4b6867674746494d535a457553596d445056474a6e546c4f31566346564579347357386730655931514c3373666a50566149626146455147636b6e33684377704861577a5a6954566879316830533569787843464e3573304f524c4d5a49346976315335786b52662f41476f71696d6f5747544d34472f6433754e30574e4e6c7a7558744b757231414932487642366e487338307a456e4b5358747836784c6166494f37764e6a53586f61677332654162625331415336674137733336306a4d743062312b364d723664783870573148682b54766f4f443654544c70305441465346416a6738556e72512b357838444b433250576358446d4454615659315359723753447a484c6168377a785649734a63704d547449353754686368624759757843656d57414641356a6f4859414456524c473057714b5664647850796a307978396e7044384971705754744a6173366c57613975567836777730324b3453735a4a2f4572366b4f54682b414f387a47317545544b6c5865424947362b7362476c307261595a4858755a5665354858594f6b364e7374504b3649496d6f556b70514a62683067355179536b6a776a545137716a762f414d544d74585a634e6e7a39597658686931414a6d454b4a4e684e4f623145597070744a77782b357a4e49586f4f55483234692b5468544c4f524f56516538755a2f38416c5835516e79337a676667787a576a626b3950694a424f4f56556d59556471466b66424e4253534f522b384e545633552b396e487867485430326a4f50714a6f4e6e3857375a5066546b574e5575376444764561326e75577863695a757071324750456d4c637047664e48515a5a4c45454a42684b424442426e737778786e41534757496853514554496e75614f7a4f784a70654a475a4278507a314932743578547a675a4d66784e4c68633155305a70687970345063395979723955584f41634344753949306b34784a43757a513167484930443241697635546b5a78784f7a694534696b4b515435627278557355715165386651324c467836797a424b764d6b4a4a6453517865373844486f644c6476584236694f31564f78736a6f59396b7169364a524d4d6c7167344d75426a704558375159716d6e6b4c6d712b464a59635475486e414f323153596461466d774a77366674444b556f715654676b6c79307851756464305934706339542b502f5a7067754f41786c4a787847694a434576764b6c4b2b30534b477a6b6e38526d57376b796d72784b73794d61716145365a554b494464486933765051774555446f4f666c4d784d53536f6c53696f377964596343414d41596e5979636d56396f553647437744445777316e4b6d576f7846745945305a6c705045736459307737614f5a4b4c6f6d45654e6f715861464c42375379387669434f4d4e677a53557674476e70444b79713536526e50344a5554786b53442b6b626b6a48316a6e4264724b36725745534a475a2f6975456a6d7052734957664245486377436445764a6d787173466c464156556a7456674f72765a5538774568586548574c436152614b2b503336664c347971757163747471396c666c6b2f55346e314a58304d674d6c4f556677355430484743717453733864344e314f707539342f6d4534586a744a4e554742556f757956626d6535334e5a373859753161744736695662644461673677446248616a75396b6c696b53387856635864303552344d5030494455616e65504c486353786f744673506d4e317a3069576a787061707374316b686143574f3559595036786c6c327753542f41417a536568647541503841556b617735315a534845785855414b3536776c6a6779565546526e306a696f5569664c4571636a7445376c5842535476536f585365594d5771377a6a42356c46745067376c344d58304f7a6b2b5650544e6b5648615377344b5a716d55452b544b385976615656795375424b6571633441594762796d6d6c727435677870444d7932684b5442514a6167775167776843784241794d5352554e375232665754494b576b6278486353655a3469596b32426670484445676d58705242625a473654676f4d2f48324165396d55624452347a395837753051324f5a302f5937446c317855564b5569536767416a346a7641504c377851723034543577314759377865676b495769544b427a6351587974764f2f77416555534c4e705070445963534f4d596769566c6c46546c742b2b4d787a356a3541344574364b724c62765344304f4970536f4563515044662b7555584b58324f444e4332766568426d79703538626f6d4559624c6e515547657a71314b41354c52784945344b54306e4f7474367064577951534a615334484538544647357978343654523039595163395a7a2b717764514a597643777848555377563944424252714633696434504537423635684b35717967704d486b596973484d54726b4639494d4d4a50496b42534b55575341384547456b6d433156424d4771596372724b74696b396f4f6146513469476559496a796a326e564e684e6735637557696f7135437038315756534a4c6b424358634b576b65397573706b377278527531682f344c6e2b38753036505076766a352f74382f764e74696d49564a644f58494336636853434d756a5736376f7937645663784f345948397071553662546744427a6a764d2b6e47464a555173464c427371526c306469783636394f45563978366a6a35533735494939594855666a506b555833766f62767075674e78552b304977444555307463554c633930674b44645152393473374f505a677467384744544b307a69684c4e38475939304d6f7348365135617476654c5a2b75424e4c67714b564153705578552b614c70374a30792b384576646e494848653268694c4671544959482b666d4b335857594b34412f50382f6d593756585534496c696e546d57626c31472f654a4a4a5967576a7132705959326a37784c56334432742f543452665337543037744d6c716c416b70634b7a6431327a5a566435516439373267664a724f4d4167656f352f6e336a436c6e58494a3944782f5074484578616c7975306f35676d68766773744c432b6157653976335045765136446457632f672f48694b57786432323462666e302b2f53513263783161335250516866654b53644344755a68793561776465735a573274794a4770304b466479635254746a696332686159387855685249436b5a694548636c5a3361324f2b4c394c4733335a6a32714b2b737a69506157503841556d65735038743472656b766c2b307366504d506e4862486b626c684d7232694656686e3854486257395a47524e44732f6a676e712f455552774477536963526964477732616c4b51414142792b384f553469574759787a43447a467a334e485a6e54386a3074417673786c5353385a62334c76354d4d546f6d41592f4e52536f703664444b4468536d304a3339626b7852757349626b38666d4f42414559796c6f705a526d7a5338772f4572556e63497074596247326f4a79716247784d665659714a363146652f516a64776979744a72484532716746554b4a39496e4b53514354784233486e453751656b65446a724e6c543751454451734e3573504d7873687354424b5a4d45727474564a736c767248467a6a69534b68336964574e7a5a686463776e6c7046473172637937556c6549544a716e393554776e7a6d4573436c5a5971584c4e7a4243776d54355945446d346568576a743168675977646f6779734942304b684862794a336c715a576e4241787558356942336d463559693666686842736f5135582b45553966786e706f465a676d366c6b734141547279347730474a496d70326232566c7936684b71755a4a42546353737757724e78574233513355394c5171793156344c527155574d4e77517a5434786a534f386c4532616761686747567a7a4175664f4d367935474232457a516f307263466c422f6e70695a617272366b676c4d34725164516f6c78753577674d704f487a6d58664b56656969445371372f4b6d6b763841434673354c66417654646f5934313862303664386633456b4d7566517748457045354c726b714f5a4c456a5377632b62665348554e573373754f494e753844324f733878755156396a4f6c6838364170615532556b74336d44614f2b7641772b6f415a542f5572324d77352b50316c6d783245543632596c4d6c48635151537451377165664e566a614848547378774f7067507136366b7933302b4d33633759305347544c6d414d4e4a67317537706177473755785876303771326378644775563135583764706e3671686d53796336776f66437049756e6d3778556177715a6f4c35646734474973785043564b55564b64453149643036545563526233682b7556784c6736386465346c625a74627278362f324d46774b6f6e425a564a4b7379526d655859736b356935444f4c427766574f4f35434376426a6a735a534877524f6d34506941576c5a6d79684d5746454665554a556f416d2f646137446476486e7875564749735850306d625a53526a79327750544f51507647395668464a57535a744f46652b686c494f35785a535834454f446534693752565135335574673963544f756139422f564752307a4f4a6254657a716252725a597a494a4f56593049484835547969327a737657495646595a455479384b41335247366473454b6b305145546d52695063495355714452325a324a306e4363564a53424267785245324748533146494b763049596f696d5050455043594c4547634477524d7673773545654e314a66664844306c3166745a5355716371475576676e6a7a4f364830364f3633746a346d4d465a3738546e654f62527a616c655a52594433556a5166316a61302b6a536b636466574e556865424b4b4f724c77566c6374565752784d72464d6b416b485639344556367167473352756f73396e614f386d6d6f576456456b63597353706a456d435470347757494a50704436615554416c595161484d514e497250564c4b576d444b55726843396b507a444c354d35514f6b47466e655a474b4a346137694a43475435676b6b4c536f734864394769664c4d487a524c4a3949426f485639503677785578415a387868686d48496c704b3569776c53334364424d4961345476414f387446545574327a6744724c656b55353342636e3439424539616f4139326d374e44754372336964484a75664e6f6f74744a396c7072717a48336a6b78577163414130776758594b4c70424a33754c4470423753547949575a6651315953695a4c6d5757556b4a42314a494c4d643463445343387345376830697248786753365256796c68416d6a4e4c6d4a414437693178785a77343450416247526956366943335053557a5171536f53314b7a6732537368335151564943755959705068422b793374442b487649424c44426a725a7a4170696c6d59556c4d6b674f70497a45704e776c494633636e6b48676c7335474d343734475a5876734179764737747a6962584674725a644a544a7930363561416c6768676c53526f4c6161747a7647737570557141415238356a666f6d4c6b737762346a6b546c3156747a557a46715553565358756b6764332f63425933316975364d7777544e4b7356706a4168464c69535641462b367263626a4e75483635786c32306b54515667597a536f54556d586d4c2b386857384b48446b6548555168474b4e6d4577794d7972596d6b437069313730727972535178436e63462b4266526f745757465748666a6a36784e784331346d7737583864636f67676c41576b365a726b48546547486e43374d6c7478366d56517639494f4f7878386f52573034556830736d616b756c647751526655584839596c656d5163483169716e4b74673871656f6c4b6366584e6c396e5579307a45685756627076626942626d3758447863585732737548357759542b48564b32366f34794f50542b6674465536697046724e504e6c496b465a65564f6c4268794253374b426264425536333269746f343745524e75684a7238796f3578314269784f786337506b414375436b33536f626944476d6f44644a6c4d3233673854513462374f31327a724365517559614b346b322b6b312b45374e795a44454171567856663030686755434c4c6b78304242515a394854702b4b30566b77426e4a48427a435455684f636379796c72724b317a6e34784958454a72675a5546775749726643715a34573445656a6b52315446546148786842555267636e6d4779556b6d38454267514353547a4435457143784233546f6d4362433970496c7a545043444d47624c6b646b6e532b596275572b4557576f6e424d344f6577684533594e5962386156666a6d48324d4a7331465334444d426e70474b355061564c32416e422b2f4b2f6d5639307842485055527133723647566a59656550395038416e456343423348336b6d31666a3970665237447a6c48764b51676463782f37667a68794d70377854576754535964735a5479727257705a5044756a30763677372b6b4f706944613536434d7064444a6c2f7743484a5147336d35387935684c61797465692f744f7737645445566673354d6d4b557356435a616c4736676b6c5852334259437744694d38316879574c64655a724a726c52516f516b443752542f415058556f3358564c562f4367442f7955714f3231714f735966464c443051666557443265306242356b3873543853413456754979366342416559677a414f76765059666e2f4d6f32683255705a4e484d584b5373716b704d774653796f355533554c323063395245376b734f46344a6b30367934324466302b55354e55545a6b7770544c6452766b516b4653754c4a43626b6a587a693356574165527a4c39746d426d62716a3251715a346c476372734571434f3475387979677179625a6443325937744c6d454c56355a77652b63524c36746345707a69644a6b55307041434558435133766b426d4679415146576257474b7461384c2b387a57737359376d372f442b2f615a5847396b544f576741796b49564d4369796c4f6f482f4541645076464f3532684b3037587a6e67792b4e61436842424a48772b33667048796632656c51696e6b6f796f415551456839375a696f33556f3854444e54714547462b63703155574f43352b455631324430565548556b79466c752b684b5535332b634d515334393433357770645658594d4e6b48735934432b6e336661487036664b536b624e306c4f4237387851596a504d79754831634d4234784667715833682f626a2b66775131314e396e5467664c4d2b45366a6b7a43744d6f6771624f51564132334b424c4f4e4e335742653754676863486a2b667a39345971314e7138742f50352f714f70644c4c6e424d3655633655754141474b6448436f6531433272356c58492f6235796d6258704a7173344a2b782b557071694679695a5a754c6a6934346a72614b2b466443565038455a586c4c4148694356566c534255414f46484b744944393177435331387966744330596737766f5a6f46414435587079442f4f786c474c303361533555784448737067594f774b62576663374a45466b5a794f524f527472737037694d396b646f524c5169576f4f704c7049476e634a436c4136424e724f30584b626d7059454449506155645870526557594847492b6e3763556f664b6f714131554163723966434e427464574f6e4d7a55384f74505869527064756164616d63645162667a4b4153656a774931365a775a4c65484f426b52355359744b6d614b59757a4b4755767744325068466c4c30666f5a556654324a31454f6830545079584e773658727165416972754d756852316932625367754d734d45556573724645447936686f374a6b38517552537041756f45384243327a44552b735a53554533444e4542524a5a7a3268386952427865597a70704d526963434a3253556a73356375587551684b664a494565593174704e706c75705269654c6d417334647670465631573342635a78783949304c6a70505a7338714c6e30697939724f3234794672436a456a326c69327341574f446a7243323838796f4b50452b635678554f704a2b35683448704c5a4d776746524a5a764d7779732b5570624a78366459444b4351423167697352494e303236776a39572f55722b593861634563474579352b594f4446704c6479354553796254677a334e45356e596e6d61494a6e596e32514c436b485253534350335347503368756d55732f456875426d4b4b4368707141424568425358757255713433314f377a69384c516c75346b6b6a37665152345637313972475035316d53785046706935685543317371514347435162414563412f6e464e334c746b2f7754555370555848387a33684f4759724d566e51396a6c6333664c38586e456269507241737258672b6b6e55317037513546585554657a70434c4675704a6638416845432f7577366c41544c442f774268744a55685379445a6a763841336b685832685a786e4d437844736879357164624f4f49666e70484842475a5751484f4a557647637270576c505a6b6c52336b48666c443733423844446b754a51716658502b766e2f614e4f6c79515165656e2b2f6c4273546f733651704f68736e77305479734c644955526e44434d7174326b71305634586930366d5557732b6a466e3638644e447a68744e72566e63686a72364b37317730335748566b7570526d5351685a446b6161333650303552655570666b6737576d4a64572b6e594138724d646a6c582b7a465669676857596a514b4768505063376352464c796e4462655a7171513668733547507446394e744f4a6b6c67514f3045334b445a676e34796548434a61703679516563592f4d4b74466368684d35692b3142566b6c766d546c7972494a53546346695271486373646252646f6f634b536670386f75396b445948722b59485634756b69796c6742694f38647734456b656b474b6d3659697477484f594e54567972744e4367644d316a7a42744850554f36342b55355850726d4e734e787a737932513558636c4263503038374e436a56337a39354a6164446f4e70795a61536d7041445737366550425163654d474c7246474e30724e70717963375a792b5a6835485565636135416d494330456e4a55377350446a78694e764749652f6e4d47576c522b6b466a45444f5a4e464d6f6c3344384769435951475a70634332517170313053535238792b346e774a3138486a67435a4249453375452b7a674a593145306677797750384179562b55466a48574c335a36545430574630636a334a535372356a336a356e5477697662724b7178795978615861575661375a74586a797574645549636367793955446e624636716b6344465664537037475768555a5561314f392f4b4769394959706157304b4f326d3931544a534c7472724637533648395659487a674166574263664972396f636d523267786154494b554142537a636b37682b5a6978722f4b51624b317965356b364c53586167467a774a524c78644d305766532f49786c5732466a676a7447746f3271504d487170536c4545584461516c367933496a4b3356426777716c7a49517a4f583436636f59753946774245324658624d73457959624141657347504e4a774d514d566945696a6d675a70684355384c416e6b4f486a46746442714d6272446766544d5435315a4f31426b7751315454436d316b6732765a513477612b7763667a6d5038724e59507166326d5978797455745a414e6b4335487a462f7342435332546d6156465952506e4d6e5772556c6d47717236626f73566854445a754f49307777356273345a2f4851422b642f4b457679594c446a457045395341684b4d716c4b4a7a462f6d55705a4437774371434933354c644f304d41642b3050713171457a697275324739677a6a7272435641784a474e676851714c2b462b757068654d784a47444b5670566c4f59653451397778537068647478454f586a7047466c4d746c59366955424c6e4a505a5441436c61626c4c4d78626577793661615869797457436364347471545a375348326832395a665755535a694f31437757446b707543666d486950446c436857527a495730716470456c7331564c4757586f7a6b7671344973664e3463696e5045445542534d7a555673784b6b392b5769624b5541466f574d795663387073447750534848557656375935486365737a52534779756348735a7a4432673745396c2f654b4759727369416c55712b5a4354627571636b6f6578427548336732304b3771483448666d4b5a745150655077346d41714b4661436c5075755069494174727231687759486b7a6c4439466c3071574347576f66374c2f5733724332344f566c6c417a444451365268424b4654456865564e79556a51633236516b326b6e4573667077713542676b75644c4e6b7a5354726f3539494d7134354b79754c5562674e4e5652346b674953436957736744764b5463395842696b775947577775526d615370777657332f504d7875625a356a6549746e59512b36494149367957495053436e4351502b496e45484d336e733732566b6d575a367746714369454a4e776e4b3132336e3651515876495a75303366704547434972784357556c387a694d6e5746314f4d7939527449674a56474e61435a6457456c2b7a486a395952716c506c71497454375a692b5a4d44654d564563464f6e655731553569544773544435456b416d784f352b4478615653787a4e4c53615934334e4c4b57705253532b3155543279683351545a43534139743536786f426d6f543250665034482b59466c5436757a793139776466695a695a364a382b59706141755a6d4c6c516367486d72516563476f415832767a50516f3946434247774d667a70487443465577433571772b6d525065642b4a30454961744d356d6664743152325644366e6a3851796474453643704357626a76302f4d52474142377372703461412b484d6655553774474b545a675364774268566146327750715a6a324b554f444b613361507331646c4c377438705741436f6b5a5363704e6872774e6f303074576f624b2b50553935796150654e37382f44744a34706a786d533356335567714c42793442536b453853517146366a5676637654762f50784761665272552f48582f5a342b6f6947707849695956454d364d6a486b464b536544325635434b684a494d764c537530445066502b5974714b674a6c7a4c756534427a4c5a6a3954355279563578474d6551666e456f534a67796b68774154774469317a313949734546446b5163677769724a4f564575374135315733574830506c41674b764c665351435952686b6f4b576b4779685a4949767663676548704157644f494c4e74454d726c666942797842532b36795135303551757351776636592f6e655a4f5a696b784c6d3479716347374f706a72705a6a626738615170516e694331704752473244596b366a4c557275464f5168375a5667704b692f41724236474650574677594a4a5938545430564f69624b524c55674b4b565a56416c6944765a397a35755265466334774f782f6541574b73546e47522f503532784a5944534b6b71374654464d7a33663467435152725973503049685353342b4d693677574c7537695253735335705541536f584936574c65485856584b476273486952677375444e54545445716c71533767452b542f63454747345571524b4c426c634e4b364e4b5653564a4a423935423374773867336c41616467712f4b4671464a6670316e4239744b705972465a305a6371556849335a57647878424a4a6a62433731426c4662504c5977444261635470687a717953774f385272794135777535685542786b6d4e705a72574a48515462594c55716b68557555727554686b7a473746514c466a614b4b7675596a6f5a654a365a6d507844435a74425079544e434355714769687936634930582f414b717a4c72587958783250662b656b7047497772795a632f5543666f576252386f767a416936646838644f675536675044396661496b792f434b6d5a546e756e756e564a466a7a35486e48596e5a6d6e704e6f6b4b44724252785076422f7248546f306c7a305442384b782b365839494236316359595a6b7178586b47512f5945483353334c3958696d33683966565938616c75386f7235524357416530596669326d7577504c58492b45746165785365544d6e6946566c4259687839597771716954677a636f514d77457a2b497a4f7a56633935724a35384662772f446f386274645a513450576139432b59764854312f7838704f564c6b6d576d644d535a79314a436a32696955673777454267774c324c776f3350764b6a6a48667559746a61584e536e616f4f4267632f552f346746587451584f593930426b67614138414e77677870532f4a355078686a5256316a6a7233694f75784359706666462f68532b6a372b63573071554467786f49555957584a6e646b6a764b75713351517372356a65794f6b59624f35375466374d4a7979457353584a555835364436576975624d636647656631584e684a6d65727071555435695650717056694c335a787a596d486f4d726e3478764f41523643447936307151744f623448416477464261566a77492f566f6b44623168455a5038415053417a367674436b38546d38416b672b716a4542646f4d49475734334e73707463344461614954666947654f70474d45775365414942517164595534304a4e74343771452f5177327a335349413678684f5579637774376e5573472b742f474b2f5673474d37536b315034794573374b377232766d4a752b3644436b726b5154674b5a4b70716330326156456e766d353441456630675175464749772b364150535a6245316a4b5642785a4a6270766a5370424a775a54314462566b714f714b53706a63793954784a75504b4f65734544357a6b66422b3033744258676d596c374b6c69632b684864426365495a75555a35544862726b66582f63657938672b682f6e34682b4e7a796e396c6d356e41576b6c513062522b4f3736774849475231784631444c737550555254695655557a317143743968754f59662b7252774f3770367977696a6141593977544577512f3769334a462b5164754a62776764355675596d326e503345585473613746436c4c573270562f466c4150714868534b37766849793449426b39422b30357674444a6e5430496d716c717a6439675178374d725570494f2b325974794d656e70396b626653656431574762634f38535579316f475336584c33424548596f4a7a413039684337666a47644e6935523252336f6d4a552f494f546145436e326977394a613834594150724e627431693069736f35595433703656416f4351354c6b41674455324f6e4b4371593738514e516746524f665445797450734c6953306853614f597844683871543470555152347862346d6275616670535a4a676f7143544b626c48546f4f756d45644f78424b684e6a393436644d2f696d49496c67356a34434f6b695a4f753273556d386f6c42473845672b63524f6b734d39726c5a4b4c545a614a365033753474754f59573949366469627242506174683839684d57716e58776e44752f7a697a645769436f4d6b4d524e58324d696f546d485a7a556e525353465736694557615a483934523165706450644f4a6d63573244537335704d355573693453526d534730796b4d552b7356546f46427973394270662f7741695a427474514e3865682b76592f695a35654256744f6c534653653152636855733532346a4c37337046432f77397932345456723855306d6f594864745078342f386d5372457938784338795344644a44456563536f735559784e42734f4f4344395a453136456b6b42314865626c3434314f2f76474b4a5242795a585541457973796e57705369623253414f366e71536653476a4151375a536538766142304839357671544730532b4a53575a747833672f7264474d4b7a756d666353387a7531556f7a5a6e61797931764e3262306935706241755662316c69733551447549686c564b677358495065353667737a3673597473674b7a6c4a7a6945537741704a34356742344a2f4c30685a795152386f51395a50457167715570523372575165706258665a6f3674522b30682b426944534a6f424235663139435838494e6c4a34677247695a6a6f494465387a366c676b426f724559626d4e7a67543667514d366c4d2b573435474f636b4c694c4179514a5456715a793475547277495642566a4f42477631694f706c7544774c412b746f764932434a5373475a475569344c66753336452b635354326e416435724d4b41505a7a486343564e5431417a416548346b5a316a455a487845755a33446a34536464574b376b6b2b376d64333063756f65462f4f4256665a4c534342357559706e31766154465a58756576384178443171324b4d77664e796343465473546e4a6c396e54793154466e5652736c49666a385236634e594b72513732335077507a2f414f536e714e65454f4547542b422f6d4c4b6643617559734b6e4a7a4d5841633551654f586634786f725374597858784d31745139682f71662b54553165453141555572377a424e30364546494e764f47674552444d47356969753244724b7075796c39464b736b654a2b304d584d577841377833672f73565a6c566338725079536d536e6f566d353847686769576664314d364a675779744e53702f436b6f514e35417566346c717566574f416b4d354d4e5869314d6b735a3874787a4a395242514a664d676f4d48576d4f6e5165596d4f6b3569764643516b324a69444f45355a74436c31367162655475384f4d41637867784d335070774648556a6343475056512b3336455a4d4c41674e52715949514442467948695a45736f4b716f70315a3545325a4b5037696948366757506a48627047777a61344e3758363655776e6f6c314352765063582f41444a742f77427354775a48496d2b77583275304535684e4b3664522f7742514f6e2b644c734f7252425753444e53715653567141576b3143446f526c583548555144566739592b76554f6e7548457a654a2b796d696d45716b545a6b6858423836664a562f57467453434f4a6172385373552b324d78446966737371416c6b4c4369506954793335547669754e4f366e4a35457474346b726a4134694f5a73625849566563417a393070494639584436326754556d4d62596f366c69323764394f302b71734d726b6f594a6c7135755166704342704b39325a592f576265514f666e4d374f704b74426463676d373930694c586b4c6a414d44396532637350744b716d7555426443303557624d6b2b5033686136666d4e2f584c6a5053567a3856536f616a552b4474484c70797068747245595a7a4b3648454567335675382b55466253534f6b3672564a367833683963674a424b724f2f7743754d5562616d4a3445732b63753372445a7457794f375945754f4b7951772f326945716d54672f366a45626e4d6f7161686d41765a39334a6a34757144524d3879532f65414c5675356447697742456d5568484f3238656672425a6767522f68552f4b6b6c6c61466b674f584f72634e4236525565707247327247506374535a614453384a7135366e55524c54775348504e796453654d6156656d725165737962646263374535784e5868477a794a597548504f476557427a69494e7a48676d61576a777371393146765344416979776a6d6b774d66455142792f4d7759574b5a34336b3055704c4d6a4d775a7a66314e6f5a67524a596d4a386532326f61527850716b42512f79356634692f4a4c744259677a6e6d4d2b326f6b6c4e4853742f314b6775656f516b2f654f3654687a4d30766132727148565554314c652b58335541614d4570747831666445536353614d6255327039493664784f2f7167344548504f2f7048547055754f6b356756614570517053374a5343536457413130764854706d4b7242517638525351506c5459355164353471365748505741497a4442784d7069327a313752474d5364325a6d352b466c42494c4e48455467596d72557357455342494a67356c474a7a496b4679596a4d6e47594e4d6b694a42676b536448506d79565a3555786374584643696b2b6b546d6469624c422f617869456c684d55696f535039514d7075536b7436677833426b63696276412f624e534c59546b545a4372663952442b462f534f784f7a4f68306d49793536416f4e4d5352597352364b4543523677676364494e4e77795772514e302f4932674e676a425959746e37503841796b487261493277785a46565a67796446494870413759572b4b4b725a61517257576e794564695475697964734a546e2f4c41365233507249396e754949763266536477626f5945377657474e6f35784b5637427148757a566a2f632f7742594171653445634c694f6850336c633359757050757a683155484a505547494651394977367438597a49793967616f367a6b6a6f5036775777656b5764552f72484f463742396d58584e4b2b566750534f4e495057514e5777377a5430654270466b7045534b38644974726433574d354f447471664b4742596f7647644a686148736c2b734546674d38686a75503031436a5055544d67305a4b464b4c384c4344327852616334787632327044706f36567a646c7a7a6271454a314869494c4167354d77474f626334685676327453734a5077532f7730394754636a71544853496752496a704d4e6c7932694d77735179584d41386450312b74596a4d3745733751382f4f4a6b542f32513d3d, 'item', 0, '2017-05-31 14:21:20', '2017-05-31 14:21:20', '123457', '123457', 5, 4, 'materialproducto2', 'NO', 'colorproducto2', 'tamanioproducto2', 'pesoprooducto2', 12, 2, 10, 0, 'modeloproducto2', 'estiloproducto2', 'corteproducto2', '201.00', 0);
INSERT INTO `producto` (`id`, `nombre`, `descripcion`, `precioVenta`, `idTipoProducto`, `idSubTipoProducto`, `imagen`, `tipoproducto`, `eliminado`, `created_at`, `updated_at`, `codigoInterno`, `codigoDeBarra`, `idOrigen`, `idMarca`, `material`, `usado`, `color`, `tamano`, `peso`, `unidadesCaja`, `stockMin`, `stockMax`, `ventadirecta`, `modelo`, `estilo`, `corte`, `precioVentaCredito`, `conStock`) VALUES
(378, 'productocombo1', 'descripcionproducto3', '102.00', 1, 8, 0x646174613a696d6167652f6a7065673b6261736536342c2f396a2f34414151536b5a4a5267414241514141415141424141442f3277434541416b4742784d544568555345784d5646525558465263594678675846786358474267574678635846685556467859594853676747426f6c48525556495445684a536b724c6934754678387a4f444d744e7967744c69734243676f4b4467304f477841514779306c494359744c5330744c5330744c5330744c5330744c5330744c5330744c5330744c5330744c5330744c5330744c5330744c5330744c5330744c5330744c5330744c5330744c662f4141424549414c634246414d4245514143455145444551482f78414163414141444151454241514542414141414141414141414145425159444167634241416a2f784141384541414241774d44416755434177594642414d424141414241674d524141516842524978426b4554496c466863544b425170476842794e6973634868464256533066416b636f4c784d304f5346762f4541426f4241414d424151454241414141414141414141414141414944424145414251622f784141304551414341674545414151444277554141674d4141414142416741444551515349544554496b46524647474242544a786b61477838435042306548784d3049564a464c2f3267414d41774541416845444551412f414c62616b4441724444453462555432724f544379425071392f624664744d7a634a326c61674d6d74327a4e387957374e5a4d455458374a556f664e5357444c5368446751427a523557536150773472664f6271335133455650714746533851577578334f6d37694269764766556c7a694a4f7150704e6b334a4e48585770504d4961686a4d56754c5759514a717976544d35386f6a6b7350724d64547331464a6b5269715470794f34336544314a514a554351445358724d51344f5a30303270556d6b575a67506b7a375961635375545457765a5577734c5a784b7074714246546645326e73774e733557794b44646e7563562b63345a42516f4b4859303147326b4d494256766558576c61776c7849484237673137745636324c784f5835773075436e5a6a496d31713768436f394b45744756727a4a6253704b69545362476e6f31714d536f30356f41376a57494d636d49766339546e556230724f306355693633636343495250574242464978475456746974784d4a6872544d5673456d6270525734676b7a5a446445424d684c62564742417a4e2f446a4e5a746e5a6b6a31687177625163356f6d47424e555a4d5164503362425353374d6b34394455796c4239365769736b63523637713971306d636365302f6c54316573644364344c47526d6f64577155736c4a4954326f747a474e464b67637a30524e78694b39444d386e452b6550516b7a5a716879756e544e3179734a6d695a46654a6f4443416e656b7365497554774b556f3350436334457a3673576c704141674b556f41667a4e4e757771385359735a4b3343385a7a586d4f4d3977434d776133667a586d585662546d4b326e4d4d446b30796b5a4d614d4350374248686f33424955666d766670477865493453643667315a77412b574438554c735359594f4a49324472716a394a4a4a394b7749544d4c69556c746f4677555345354e45644b5436545045574e6446365966456c7a48734b55326a42453378633843664c7a393276596138652b767733784d356d586955695a6b7a353436653955314148696357346e357038704f354a7178613272387978586941797330563850493538773545352f4b724b3764346a6c496a4b32306b4c426b545643637a53666149726d7943466b41556c2b35516c6878464770616f5565575946495a6a30495a35354d787339575365394c3247595349335975306e7658596d5a68725436665775784d4d4b51735673417a6475746d51706f5551676d464e706f344d36662b6d695551535a453951645071667a744a416f6a555768492b336d5348557a447254496262615635653847684f6e4d7372314b6951373136392b4a4b76754458436f4348385442565868394452625a6e6a7a2b67454d514b7078494a795257547030465630365a7156516d45424d3171455a6f59516a72526b684b4e7769542f414370594f336b54534d7a7a2f724a39313238415639434d44307a6b782b6e35565066617a504a54775a7864306b7a494675696b324c6d615a3030705469676c4e4b70724f37453556784b6d31537043516b6b2f657659556b44456669457173304c2b6f41305968596d6c767037615449514b4d4d5231424b417833617638416142545046614473454f57373544516c6a4d78504d4e555554634b727a3771777a5a4d6f52654a73477846535070784438494759754d7a5364685577447035676873707170627742677956744d51595a706a68447950384175464c5277624269646767543045616b6c744954426b2b6b563759634b495179596e76485a4d6e765357356a306b6231434a6d707334614e394a4d4a61494f4352564159474b4968624e34346e764e626854427959657a724467727644457a635a563650656c5945314f347759594f59395a58517a4966626d6945457739704e4841686256704f5656516963637a4d516b494552464f7a43416737317168584b51667457627032324b722f514c59676c5461514979594662766e624a3431723271574b5831706259337053593343494a484d54536a63767448727058497a6d656e4f7170684d6e67536a51356d346e77717270776d5a4e444367656f4b4f49456e306f4c47774961444d6f39494f3173596a48656c5a7a365173524e3157796e77314c6a336d7373484553363853627774415550536b347a4552614746754c4345416b6b2f6c5369684a774a73394d365836555332674b55504d6561747170434347424d2b6f4c545973525248754749497a785243484e3030516e51793372594a686c7959516134776657656376736b7571563731417a354d71526343457474474b5554484366517961474e426e5867554a544d4571444e3950732f3371442f4142555656586e426b397451775a53616b33394e656e5a36535a427a466c3654536d4d6142456c335a54537473504d57506161665374475a6b797474494b31705141636d744c6b436371626a6957467030516e3851584d543831696d356837526a563067397a3632686870577753434f5a3756356475735a4832764c4530536c64776a5a545932626b4c416e68536b6e6254686259526c635950726a694b6570454f47485074507475754933334343663455482b745556502f774470676677456e6451667572447a714b32787632423141456b6f506d414863704e556933484f33492b582b496e77686e4763544b7936355957726151552b353472713964533577444750704c554753492b73373970345332744b76673155727177797069437058677a743078577a6f6e312b7758634d4c62536f704a474978514f7534596768384765537639484f4252436d6a503841447766656b6975566a557a30427842716b794b444b624d316b324832566b305435316766656d4252367743786a6c2f51477969554766316d736e416e336b6e65756f626332524b6832484e523257444f4a5367347a4774766271574156434232465950637a63776658394e5534307049394b78775745776b596b7630486f4b6c376b4f543556464d66464656586b63795543656a36543079307a6b4154546c514c437848433168496f69514951456c65703341714937476c46675443784537526f78436d79445769644462635673457a653956354b462b70793978437859376954556131356c4262453346684272696d4a6f65637273714572444454685670516b51673030735749576e356f712f76514c443559377632354171312b704b766356504d357055624d54615457546f537a6f5a4f536d4d534a7750372f4147716536786c474548315055645571486c7a39505744337a7a4675704f38464b6c534157776366686b7a49417046657079663676483779784e495742616f5a2f474d334e664c4c6158454b4c365349415643434436545454725572554f4357583976387a452b7a7a61536a6556767a6d4855476e74334b5733314b46764943694442335a796b45444a50464f625a714b6c64754d2f5742536261624772547a664f4f4c566c702b334c4d6b51493769434469423270716f6a3147755233563256507550724966573949764c64344a445a577754683047516e313344745562615a712b756f314c3062373363707449645a5a5146714a5650346a362f776a306f6752574e324d2f7a30696d334f6354742f544c4335334a3270436c5a4a523556664e43746d6b7659676a422f4978762f414e6d6f412b6e36535476756d6e375a616c5737684b5152747a436a392b445531315a70664e5a7839522f444c4b3755745162787a48756939527649455863653347372f414d6f71716a584e31624a4c394d702f3855734e4f7647336b376d314251377765506b5636646469754d71637a7a33725a4468684e6c6c4d38436d3469795a49616554634a4a623541705743773870683767447a45723977705249506c435a33664935724153427a432b3865496f6631654d492f4d3936686656456e797978644f4d63786a3031727a78584553505563553754366c6963474a7570554469574e76594e764c443230626f673154596f5937704f6849346a707531464346685a6d685954476133457a4d6e304b51792f694d38316e526d59356a52335530396a574d2b4951454557385630676b744741415144563754794530595845776e4d524e476d43644e30716f70304d74334b3051545074322f4f4b462b524e555453796272465845497444464e436c324363706d536b69707a477764324b416d454a773052494e61766378756f6174326171354d5230594f704d306f357a47436645747951423372753576552f6453617434545161624d716a2f38415078373146713769713752332b332b3536583266704259336950312b2f774471526a32714c556b62314579543957636950586a6d764e427449374a2f5765797931566e67596a617743464e797478414b456c51516f7a6a423368735a372f465730365a47544c6e7230505036547a374e5136763542776655663567756f583639344a4a556b2f536b44636b62675a4943652f78484559717266362b2f3145797647306763483139442f507868392f66764d73693451704b6d2f77445678745041437766362b7446613167554f6e495036666a45566970334b507766332f435a3658317553734e5046726171424b465a42564d5241493548663169695237462b38526a3566366757366570786c41636a336a713974724f464b6464575441494a50396f39714a78567a754a6b4839554841456c4e53312b78747a4c6269314b374151506a7a5a7155303139726b787765316868704a33485864795842744f4a342f76536a6f77664d7a4850704c394d55504257554e3172694c746e61366b6f636a4469655166512b6f6f5875423874677952305a6a3666776d33566e6a326a2f396d6d6b4c527575464568494731497a35385a4a6e6b442b5939717630476e4f66464d383357586876494a5776334370723170353034304f3353786237455a55636b3179316852784d4a6b4a314576776d6e5366784b4f6664527150556e465a4572303438776b576d3633454a39363835554a6c3750675431586f2b78516c75594578587056566852504f73624a6a6652485148462b6d61643652555a3357716f514f614573424e784a54574f72694a43424a704c572b304d4a454e674837687a656f6b447446597553636d59524c4b7730776a4a4e48737a4f427847374e734252684d54737a4c553044596130695a4968536f4a48765141773538572f46626d454a2b7437676b774b30476352484e7261546b30595741544754624556754d544f3532384d5574786b54524644376842715571593447594e705574554368465a59346e4d34555131656c71465038444169664679597930725444744a5565394f715869597835692f5546424a49465a596f4d35574d584f583278436e4445416436677638745a4d746f5132574252466c6d2b70354b5671504a564873416f524d313535444256352f6d5a37696855334b76702f6953717251687877756b715a624b6c4b4534674441476553596f3673416344502b5a6c6d3678786a6a4d7a76645a61556f424b564167375a45655a424133452b2f5036436e4256786b6a6d454b3742786e6a2b2f70486d6e58545951564a7750712b50556a506f4b7a61564f612b766239354659485a734e33312b4d3362316c7368647570776547344343434a4253636e4567794f634761725668316e75545755754d4f42794a4f72304b44765166454b4d3547465236474a347a6e3271646b592b5547553161705163486a4d36764f6f43704251435647504d68512b6b384861656577726d596a48392f3751726167567a3765337438354e445158486c53303234736b3443556b6e2b5655492b56346e6d4e67486b796f3033396d56386f4a334a533266566168492b795a726a52597836684c716b5756326d2f73306353527666544863424a4f666b304a2b7a53334a4d4536383941533036666a77664437744b4b6674795035316470694e753332346b646779632b387a7531674b716b6d4b69327875534b3047446949757562634b74466763796d50736f5569355179344d62557855356e6e2b6e6161516f486b314d5641366c4f374d39473068316157343478464f514845517835684165556b776e763841797248506f4a77454631527855514a4a704c6b6a714d55526459364f7078516d685243544e646836543048534e4a53326b5971774c675245592b474257346e544e783043756e524272576f3449547a53324d4e524a555771795354514b7068543675304e6274684348325451487a52724f4d61326f564e4d454178797969525252632f4c747a5332454d47444f326f35696877424d4f594f30516b7a46646b51546e316a47304a576561495a4d774b4963474341664e5267436445446c6d436f6b6d5a704a484d5942424e53736b464f306f4367654165506b31357571645735417a2b30743037736e33546a39344263587a467355742b456c795a7959473341776c4d52324835564d2b6f5373444b67793754303233416b4f522f6551765647746c61584745705332327451564342395245515665697045597869736f735a786b634431414539477567567347624a50755a4c704d43597a3630386e306a47684f6e5774772b536c7665546a435a494139774b334f4f686d5450596963744b2b78364575794a5757305a7a752b6f51415241536b676a507254567063386e695132666146594f46475933307a70526151417438375a67375151536d594d456e456a356f317050715a4339344a79424c4870337053795a456f59515663376c2b64552f4b706a3756556c4b64346b396c396a396d556f414177414237552b4a69356a5555465245393649596777743937796b7047355559487165777243434f7075594870646b57327a75494b3145715752787550596577346f616b3244356e6b7a57624d45756d4a56544442455273313037452b336d6d4b66624b5267534a2f4f67595a45334f49497a6f7a62583853717749424f334578677a596b354f42324659544e454c613030714e42747a4e7a435636556e754b33594a3259545a6165415a6969416d513553696d696d514a36387a58476141597131432b4a774b457777735546424a6d687844366e33776a58596e5a6e614c516d75784f7a4432726443526b31336949765a6d3747505168435667416e2f41456a4e614c56494a48704d3248504d576e724a744a327051667669764e663759724138716d574c396d75526b6d59336657537a787353507a70412b32433351784844374f55444a4d3464765833454262617477505943756533565067316b63776854537077776e36777433743075723550464f5262382b5a703169616344796962765033414a4452474f2f65686336726468434a7464656a7743344d2b576655537a4c62686851483531314f75747955664759567632645552767236684b6e314a79556b67392b337a516e55366866764c7837796634656e42775a4d6a56336b6c536e424b74784363344352394a727a374e55796e67356a36644d72786170536c54754d6b6d662f56526c7367792f49516a62465631594261787a49716d7577714d43483435786b796e3054396e796e6b68546f384e73352f695077447750632f6c58703666524f336d666a393535642f326c68764c4c48702f7078717a53744c57346c66314b56456d415947414d5a5035313646564972344538362f554e63637444323233564c65436b674942547356334a326a646a30482b394d47347351527836524a78675262636946416638782f376f434a6f6a76526c536b303276714133634c7531516852396a54494a6b5662727a3936454745524743627451346f393567346d7a65716b63316f6365737a4549463844523567784a62706d6c6b786b304c366953457a464c337a73516d3174436530316d544e6a6c7179506574784d6d70495452546f4d39646a6d756d346e44577244674375426d375a6a63334b6c64344662696342413344504761374d325a493031616a4a774b7a615a75345456566b6841387976734f614378307247574d4a465a7a78415536327746464842423556696657764c663758722f384151666e4c6b2b7a3349795a7562317436557037526b556f36317257323478383452303368444a687470614d6a6b7a393570697053526b35503578445732394362366b32326c73717a786e626b35397170613275744e773636346955334d2b44504f64515a4b5853326f5935536f6377654b3853334f2f6232506c506f6147553138647a617a36655a55557155347236684b54383164567045774e7878373953612b36775a784b3277654f395453626453454a2b6c5743435057765472786e626a41485874504c636e4763357a436d4747637257504d676e76323961414d6f7a38706e6e4a7750574670647431704b30464a6a42494d2f593031536a6a654f5a685378547461532b7061436c317a633036456e6b2b6f6e30727a374e4d6c7475556242395a36564772616d76446a496a4235705454615571584d704955654a3756327362774539383853546978386954656f4e2b61596a306b64757872774c392b374a4539476c687434677246737052386f6e7437666e51316f376e4369477a67446d55656b61556c6f3731625672357a6b4a2b423350765875615054696e7a4e7966322f4365646662763448416c414e59575071466571756f3978496a535053614a315a437563482b394e46696d4c4e5a6a43317530714a4150422f556a2f6e3530593536674545525a64616275654b2b32774a48795353663655707138746d614734784f6d5850414f6544576a797a7534467265726d4953634775617a4855344c454446304161774e4e496a5270344555634366565258546f4f704e644f686a4c5943596d73504d36624e4144307241495863623244794f31476f6d455170312f474b4c624d6935347a33724d516842486d69654b346962506a567058425a684d4a525a4475614c62377a4d7856633954326a634a53744a55666d4166346a474b69625831626331386e362f724c5067624650395159452b2f356e34774b5733554b55424a536779635a77615532717363454c6a50794a684c5853726335783835336f7a616e5579704a51756678454578367a535041613542346e44666a4832324a57634c79494a6636497970386279415a456e6a6e6952555077594675316a786e754d5455734b38724456576a5a3373492f64715042544249786731366e674a5a75513848355355324e78596566786972707270753561635634734f43534a3352393450745336744c596a486745546452716b6344614d5273357044724f557238514b4d45455a48704237306d7a5250534d6f632f4b4b53304f6552694a64667353736953477043747869636476767a55316c5465556e7939356e70366243354935452f5766544b416a7777566c55536b71497a5063527a3235716f55456a484f65782f71625a71426e6363593667533964657331467030454c524577526c4a34554f7846443851314c62574248316748534a634e3648673954562b386175464a63512b554265436b78756e32534f61323279703233427341392f77445057445870624b775152312f4f3461396f71552f764332513045655a53563756456a6c5251503530384773715352786a736348386f704c43534633633539526e395a4c5848557a4c5a68726552496b455a4d6348644e524b34586c4d342f6e7250572b4449582b6f524b2f543963446a526c49456945535a494d45666e6d6e44576877636a476576786e6c3361546279446e4863797672487846704f593270424845625568497a3778557570714e724b63656d50796d56754b6c784233374e4a386b775062465856614f734a744d51326f59746d434f6149766c7534576b2b687950316f7667314833535a6e784a2f384159525a6549314a6f796c5348556a7363476946546a6a4d376570394a6a5964543350694a6264746c4a4b69414432353961306c6c376e59584562326e56545358564e654945725372494a67547a57313345444d45706b5369747465554d34494a2f742f53716c314876456d71484456576e634b776163726f305755496e4e787069564479776149312b307a644a2f5574485750776d6c464443794974614c714b455a4534696266356f6f63696933516473354f7465787264307a4571625854307154494a6f77674d346a454b6673556c4d6347744b436170784d725270534436697541496d735159656b7a52774a30576137453663452b316469645079556a31494e644f69625874665377327159564949416d506e50734b6e74744142416a367138734450467450363149576f4a5956754c67385077396f4b6b672f53726d5469652f4a46517270776742794a54626339784f636d5757724a495131644a5a6474336c454b557074514245672b5665306b414b4566324e62665955584b4c7a372f77413568365452437838574e78376576383935736a716c53556c4c6856764b54736342796b776473704754427a4a6d6f3076385276366e66754f4a652b6d52435044486c3951655970737570586c4b4b7932537431446143346b4652386d34626c4473664e3938315157566c3537674e7031446b6f6344324d4d306671753859644c62773353726e626b41656847436b2f42396f72687144574d592f6e306d746f6137664d70346c75313164347743577a7356795a456a62475950622b39443864346743715370392f6c4150325634586d666b66336d477158626a63535a4375444d796674556571705a47484f63387a6b56434352786943705a63575348647a637a42556b6953424f30547a576244393233792b32595069686555356e7a566b334e73675174546953536f4b536b7041482b677763784850656e334c62556f436b6b66582f4d646f6e7075592b4a674835794e3157374677366f6d5351416d5a4f652f396630715463783830735969767972304936365230524c5a337a75644d375353414544326e47373370696c4c473239534c56367177726a306c4262617174473574345a43544d7843686e6a735a6f4b6d656b45502f77426b4c49487756696d3165746c50376861424b694e7379492f37676b6356337843444745355035666c2f79574d746d7a6c386a2b65736558627a554a4367684a426c4a474e7839506356513139624b467341795061534b72676b72367a6c6232784f346e79774a50706a696638416e4e4f30394a63672b6b5662594f6f45693962575a4368564c42737a6c7869456b474d4768566a4f494577566348685a4839613032426544424335366e49645371594d67416e504f4b45616974764b447a4d5a574554616a307461334d724b64717a3353594d2b744f4b7142784f5632455636686f3138777454724b2f45515354743942365237564d796b63695549796b594d4930725846764877314e4b43787a6a48334a34724673627163364163787462616f3831776f6e325039366f58557373516167592b734f726b4c387267672b395631366c5737695770496a454a59644a536b674b6959707535474f3064774d4d426d413358546b53526d73384f5a75695a7a526c54776144615a764573644b756b4f4e686141514432394b617268756f4a454a55334e484d6e4f3444414272703031427270307a57736574644f673935646f61547557714232395366514476574d516f795a6f475a4f334f757263576b415169524d665642784a4653745a754f4d3445614677506e4f6452306844796c6c513875794a6b544754506f42586b334d62624e7466513935364e4b6c463877356b6a6f6e533754547374687462697043696f7149536734674a534f34354d2b77464e56697968574f66655546546b7541522f506e4c612f7447747952636f6a6277554b684b6847504b526e4e48594276473466687a4a5574594b64682f4d526e63576143317562536c53776b626477423567524d592f74546d414b63486d4952765035757033702b6774464955554a426e6349412b71434a4254794d392f51635532756c534d6a38594e313741346d6c7a7044535368514953516f44506c6d65496a7637567249716c6563632f6e41577832424863387731612f7744384e6575495a5168616433414257494943694a486230453135746c614a63536f79506165335459397449446b6a2b64796959366a5148646f5146494d46767474555435694242704a3147484f426b656e796937714d31444a3539666e37536a313234506842654155356851676e324250666d6d61327a645668736657655a70562f71596e4f6d583457416d42736a4d6d494766316d4b52704e5467424d44623739596a7236755332655968366c734c426858694a51344672473437444b4d474a4950306e4e4e3148674447776e6e32782b73713056576f31414f534d4433372b6b5432657232356b44784e306b5a496a50644a422f516730677053696b594f6635312f7965673332633537492f6e792f334d4672635753686174703365556e365644736f38786a4e42735837724876453838564e7649413669367a756e476c50427a636d4341434f347a394a5078546642437a626348474a782f6e6169684b6c6a616f7149534f534236783234724c4b32786866534b5447376d4f7a624f504d4a54757a79526b664750697139442f53424f4f2f30696230446d4b32644966623359555366706a396561743864477a6d542b4577366a65302f3664726336345170584f634a6e68496a6b31353270315950466636537254365a33507650794d6d54494870332f77444938443471494a7a6b6b2f542b356c444652774d48352b6e30393433546237557a747752424d35794d2f46586970617876416b4c50754f4359493759342f64754b4744695a6e48725445314159346737534245706263596c53334844676b4e6f387931522b6748765451345041352f43647450727845642f316f6b4f4938693035457a6b527878362f373049566964796a39596541504b54474b2b75625575536c433969675a5467464a69504b592b59706863687432336a322f774152497234786d4b3952366a5374304a6151436b7042472b51556d63704a412b383572484654444a3448796a55653165687a38342b3033556e4151562b516d436753664d4935425049714546366d334b654f2b5a616131745163666a4c625464654a416e4d313764476f33726d65546253556245624a7655484f4b7044694a775a397332787368486c78694b38716879426b536c303949485a366b7442384a785735594a79527944776356516d73584f3175346868677879484d5a542b5657775a67342b50394a2b3831733641363572545673313469794d6d45706e366c4867543248636e7342574d77417a4e417a4939765645756e78484653705866473043634a544f414b67734c735a536755546b5068352f77454c4364735365366c63374f63594271643377706c466163676d483637715867737132714359784b684139496b697649324e5933486376725175634153532f5a2b346c4b3347334d715575556b5342746745464d67474a6d724c4e6749475072433146746f35422b6b75656f5a4d4e33456c6b67467435503162686b5a2f543371693139762f6b35553947496f5262426d72687656542f502b524e2f6e36625553307054363545426662316b4a705333566867564a4d4e744937447a59555465373634574e7132304f79516479464142416a767a77666d6e32616b416731352f744a64506f316345324d426a3834626164554c654b433630493267774645676d51557169493966576c76396f6c474164595a306c57443462546a55644d756e566e77586d416b542b3743464a55556b534571584233486a30346e464d4e6a572f646236596866306b4179702f4850722b6d497475325462674b4c436d6c5468556b676577566d5035313546326e3142633577423870514c4b32484a7a43644e31354d42447266694e37747843354a547a6b65744d307475332b6e594e772b66704a62314f647963474d7262573231714c61326b784a3277416b7832685359696e4d7a73323071437634524147467a6b35676c765a75584b3357454f454947464c4979457134534f785648666975703057363038385367587055425a6a4a39495862394657545359384a62684167724b31676b2f36764b51416669765165757065317a4e2f2b54314c4e6b4e6a3559483934753158707070545951323636325571334a4b6c65494236677a42492b3954734b6a3050776a31316c68626334422b6d4a5033576b4a74326c6c3051755a385a4a5555714a37516f6e4d3869427a38567751424363352f6e38372f4f413446316732635a394a7a70576e577237626a696e6c466152434145516433715275794f63555659724b4d577a4a745456625577417844306452433351574867537048436b7867596748754d5a694d5679715647306e48746d5437736e496d7964656c734f4b425332723643434372684d376b396a4a344d592b4d3562566c4f4f767a6d725964307765384a3153567263436b4a68535144414a48632f38375643714c5732584d39424e5179316c6178676e737a4f2b313557354b4c644155465475684d776b6567397a544465574a3263667a3268616253316b5a74683968664f68486e547478426b5350765772592b4363635472367141634c7a456571363857556b68494d71496b4b6b514979422b456533614b5855454a776e66724e5776634d6e6a4838356e576c64526f644363374648436b37437153634a5546416a4d64766571787471506b4f4d2b776a506864363772426e32356d434e4c5a75447463536e664a68534a423970516639366d2b4a4374672f6e4f752b7a3132466b4d4c30726f693163644d37796e684b53594a55496d56447354492b4456316a4b4832716367395478363743557952434757645042574657714758456e6b6866494a4730797152774b6b47704c5a537759492f4b564e547377796e494d614f32696268704b30754a4c734862346843595667514342416d44452b6f785478717137464250667a2f414a2f65414131546c6654355263625334444a425473576b655655685353522f456b524750317267775168312b7549566d312b415a6e7033566a52522b395634617759556b39694f59397139494f434a43566e70476d4f4170727a61624152695073516777687867547643556b2b395730426433496b31692b73346475696e366b6a3836767a694b6e49756b48322b654b334d37453832315455315856307061424c4c636f5442475343517463636d542b674654324e6d476f784f745163513277747774464a4167656b34452b3149594848426a30497a7a466e54566b524a53417031554c6a76415541534d2b35704c3134416c4664673353323636306f4b74304630535545516652524741665938564d2b6d4e594a4a6c476976444f775831695779365643346638554a56506c47544139545537556f557970376d32616a613233475a5a74324c76684273764e7154452f543666797230457073384d4c75424834547a4764513263455264702b6d74654b64794e796c6371674441344145554e57336667696139726c635a3445563954394f7645724447314957596d5070423567556d344d726b67635171335572676d456148303257534672574e71557041527a45443150764e4b46497a346a6e48796a7a65436f525239592b2f784f5a5150597833726e73773235654a7171434d4d5a766574426257317847354b38455a4241395a716c636c5163647853344463487154714e4b733072414e7370734a6e7a53734139784d6e7a564b376257785a58783779737132336372676e326a7653377131514341515a4d5274416a3871743031744f4a48654c4431784e5737316b4c4b652f71497a365446412b7043766769463454736d524a48725055567063384f534542755245674b4a4a2f5041465461687434474a58705643386a755376537237373130416c524b4569566b6e43552f66756654326f5530355963656b76314e39597135374d7664557470625834626f414d6a62746e4237456e46556a4b747572596a35442f63386c4c4f527558507a6e6a6c7968304f76737373752f534a47785379496a796767596e6b48326f3171484250702f4f5a7a755353494b3534376169307533664377524d74714a424f6377506575616f6b6b354835785949474f4a5a5750536c322b6b42626762627875436372554f5939452f7238556d746c775a7849426a7450536c71776d566f6355426a39347679677a2f44416961493751657534797437484f7866306e4e736774724b6d53674143556f32704535374b5065614256326e672f686d656868534d4d44387a4f625056623075456d324737764d435a2b38476944585a7941444850547064754e356e3235303574384b54634d427152494b59414250655267696b715838516d77592b63575652514255325a4d61333032746f456f49556c4d5a5436646a376a37316f474353655a516c6f786738516e70355a6247346b7a7a4d314d64724d5a6c6c6e7048546d73674c33435250507a574e754a7a504f7351413852676a57304f4461366c4b306e6e634d38524950494e61747235382f4969436d507538517533302b334968707762592b6c5a4d6a767a334e566976547479702b6869764573587354386472457138586369434e695249672b737a49397146617642795133487342312b6349322b494d6265666551757639504f4635536d776b4a55416f416b642b617071757775494c4b4d7a31665441527961386d6869706c562b44314771626756364b616a426b52724d306349554f4161394f72556275346871385354367566537862504f4e714b4642424154366c586c5448334e554e67444d415a4d6e4f6b32774c64416e6743636b632b6b66794e5346736e4d64695a396277575569534276457a387963392b4b7a655351495155596d6652746e34622f2b4c635543436b496151442b475a4a5076493471585661676877676a366151564c5430526a564575536c61596e73636769734e7a4c7934676e546a75737a38725357675a436f422f444f4b344c54327635526536772f77435a742f68776b486245625a674b394b70777a44414d5777357966326b7378317743387047304e684a3279723669667655506a73726559596c4c615a51426a6d506b64534d65476646356a74456e3346572b4a554579776966415a6d38737862655a63515670644a536541526b483339616b54345a675357687458617242514a4a6f313135433170564132716a48456574523769546965685a5857414d647a5a6655436e456c746268416d516f5949396a57765a614d62544178574f51764d706d583233576b744f457241414f2b596b396a6a7656593150696a62594d794971554f354f506c496a5869376172564d624f557248702f454b5361416a6348755549346354507076567647646b4c334869616d765677344576514c3466416c6a71396970376234595370653362426a383831366d6e7279755057656276326b6b39525a5961413477777074494b48446b72556b414b554f4a322f68375667726349564978447375566e44486b664b644e58716d456745685367504e48425074586d426d72626a6d656e585174715a3668756b6456327055556c4f7861764b72474436563664566c622b764a394a46666f725231794250742f6f6a6f5370786c7a78436f6b776f77632b6846412b6d344f4478467259704956686a456a3136303830646a6f4b464351527833354872586e4e54597034504563364a36537036663233445169466b4537676f2b2b43425871364e41552b66726d4b73796e4d48366e7337687367744e74724566527766744f436164645154794349576d757236596b664f643649773836513475335568615149796b52364442794b79744354794a3131694c777238526e713171746367494b464162676f5279526b48317a585055533249704c516f7a6e496b375a616d6c4374696d6c4957634c6c4a324c373442782b564c66465a47423333375367686e584f37494858754977744c43336353564a536c4b344957676770487950513050684b3258413539524a7253366e4765505178473662667855747258426d44783968556c46572b7a6d62597a4b75635132373661524a4b466c4b534d535a416a3446585861525163716343543161676e68687a416d394741676f755568556b516f456a4834676f443654385641666877526c77446d57596231553468577339505071516b74723875344a55516f4c37655a5149417850725639745943626c36694b6d5150677a52746f494154764b3477544d356e49396f394b6835504d61334a366c4530374171665a475a426d794861304c677754694e4e50584a72314e4c33496275704c2f746130395372552b4833576a6448614641672f70586f334f46544a6b74664a78464f674b48684954674541546a76554173552f644d6f7a37784e31363251796f2f36564a4f50536330785835454c48456b744236774455744f676c4155536b6a4d547949394b5671644958594f76634f6d2f614e706e6f2f5432756f65626c4243302f4d456631464c595a387269477259355579706276576e77473970426a76322b445362744d70486b6d3173366e646d4c6451617557452f75554a5765786e6b664272713743766c48637652714c6a357a69634a732f45536a2f4142444b64353968672f4e57564e6b59626d545856727550686e69456168706c7547774330764d68525475417a3239425647784d6453554c5a6e75433662704730674e4b5874485a7a507033714f3352566e6c654954584e2f7743333651753830793363537031594a4b634b4354415076696b6c4171456b6369437066492b63554a5a307852387146456a2b4e55667a6f427161416f79764d4c5a64377838786f3650434c69437043556951435a426a3571696d68534e7747424573784277655a4e3332713236776b7538464d65344e4663716c636e714e302b347474395a6a70375456756c4c6a6145425468786759704b384463787a50574b4c6a5a30424b4d61343162746831794a503468324e573045487a4765625970596c52487162345844634957424977526d724754496b4b6b4933496b4c31573073416c49424b5235696e73423349727a6454703977796f354539505461674b63453847526467306c546f55705a6852456b56352f734745395247374b7a314852417370387537484250635664545765784939515534335465377562636a39366c4b75796751445273364c33464c7037547776306e6e317a6366344f364b4756516865556e2f544a34715478634e7553656c5652757232764c33785672614471414859534e7756676e33466567746d527545386578417262547845746831596c4b79464b6a5048636531532b4b79767a476e54466c346c6f30304857777457386273706b356a3756667333444a6b52625932305934696d377344346a6139776351446c436f422b783961426557473645324168774d474576504e714a324a4c6268354a7837526e4270704b6738647841563865346e6b6656765372397337342f4b564b6b5a7a7a4d3148596e6869574330575176706e72634a5834623555414a327135482f6152327074626a5a6c704e59684a774a56573273326a6e6c574730714a77524565304b4842715631303775516343565643384a6b5a49683774793445625571547353652b444d43632f6937666c53724c43414b31506c684b692f65493567416554796f5a4f63544239446a3270364b46554178446b6b2b57612f356b4f3165513134394a614b6f58614f71556343755632597a47554355756d4e6b51545872615867387a7a376a37516a576b4e754e4b516f6a7a435076322f57765464513646543679484f446d65574e72676b447359503272354e6d5a4749396f38744275704c695743446b48483272307162537979696f5a4d386b76324e6976616356367462376846574a734d4a306e564847466878745242376a73523645567a6f47376e4b534a36703033314d6934414b566256393039353976616f33526b366a6c59536850554467494378493961527442354d6f556a306a6d777676456a67696d6d764979706d4677446a7146584c5430456f636c486442456b66426f42714c514d5a6d67314534646566654b6276555574625170525343636a2b744e386261504f59596f4c6b37524e644c766b4f4f4c45796b3855735849374543555730736c536e316e625852724b58764552415354754950723755317155636965556247475a514643593267776b666c5431515978365242597a792f715054422f69747261675571387848594548745358555a7744505130766f5748555642447478633757306b4e746943527875397151795a45394b323449754365544b413649363832576a4b556e7561796b576a6738435157576f446d4f2b6d6445754c5542426861527765385636534e59426952577655787a3059586549536c39494b542b384d4b37794b3763515150654c5253774a39704e6457644b70746c68396c506b4b68754859653471625630414c755758614c564d54744d4a306a57536c65536f41343971687074737a7a314b72305862784d4e645a4c5a4c734653446b6e30724e5453326477356a4e4e713977326b344d6b687154623639734464783778537970524d6d564337443847576e54724677456874745754684f3434465030753975466b6d70314e4c484c696172734c704473504d4963414d376848356961743357713231774d65386d7a703258636a45664b506233714973495353325349694535696d766673455456705674596a4f4a794e637433647531514250494f444e4a2b49726242426874704c6138786730456c73714b643370427a5061714e69375333636a7951324f6f6f3161396265523454725a5641326952492f4d64366c61316d47434a5658537564774d6b4c585272457662536b70495066492b436b3971476f7147775a5462706d385065764d5a74394f4d423471536c4357675a6a675438487457577057373567317579313479637a613666516c43316d504241774f354950366973524639756f6d79776a38597374727555676c5a53534a676c4a696149344a694f5a5361666f67534a4e65616c496c72584750625732536e6756556c51456c65776d45687a7458477a42774947336a4d694f746e586d6c427843694278386568706a58324b7552464f6f6b546158336d695a396138783679546d4c4d4f763768436f624f444577652f705631466242655248306e416b6e72576e44493756516a46444b43413638795865594b443756636a68704979465a38596655685157685243687752526b416a4269387935304872514b684434672f367533337156364d64527932537973372b4346744b424873615543566a4d687535544e613841695349497166554b483548635971354f445068315a6c394f55676e33726c5274764a6a553868344d51334c4c74757678477875625035697051436a5a5765694c467358613363717445317044364144685665685863746778504f76303756746b64546271466c51626c736b597a52587356474245554257506d6e6a2b73366b74747a767537647966696f30566a5058715646425979312f5a36566f6242577771535354504f5436565a566b45547a4e58686a6b47573139634a69646d3272484939704457705072414c5858664d515241486575573064527a6159597a4774732b30566235436c5531634535694758614d5258314a2b2f486854744850464264577a7269616c7156386a6b785262364f6f4a4b6362666a4e516d6c674d526e6a4b546d55646a59536a616f426153497071564d6f393442634538635347314c6f424676636d3651464b546b37426b4139365271465a5677426d5656336278337a50714f7332306d42414b667363564a385459427773503462505a6e312f726b724849704c616d35767652673071723145317a72355849423570715745446d463465444f744b366365754662392b7968717138544f4f7051327446597869574457696c7474535650724a6a454742567956374632376a4947754e6a3532695936613870446174704b782f467a6a304e597573432b58456f6254715747376a384947454e4171754855674b37653154737a5750787848732f6872735138524c7176566a5162556c61706b2b56493539685436394f32636b794f32385a34697a523175334b6b3768746254394b42496a307a545748704a69633879706355326e424d5934674773416d52387655523631352f69675376777959525a333030784c73786231596a41657463527a42485543317930513632516f416971714d65736e7442394a3565786f65315a564f416f78376961466177482b554462784175725670495345346353667648662b6c58755274457973455a694a476f6b6a6176383656346365486d647861434f5a70514a426a657845393159526c4e554a64377964367661424b42474349707777656f7242454f3037563357544b4647505474517457473768423561365231756c5132756a616630716179676a714e563435617557313551766e304e544f687869554c626a754e644f7656665173376b314e7349456f3855486b523170544d4b4b6b774d79503730717533776a694d5a677938786735726d464a636745436d2f47462f4b594a305141444c31494470356a785831334b302f694f7748734165314b74735a58434c395a58576d36727a537066764c6b4b68747679787a69716b385876455561744f6f38786954584f6f6e54355a4b534f617837334a32786955306f4e33636a72377142386770436f392b394d54505a6b463579334548306e715a36335675336c57654365617572745070504f73727a3350517266722b33635a33717773446a76373151626830596761632b6b50306272466c354d4177666646527663426d50464a45643275744544616a4e625871512f416a527078325a516f7569555170506171794d6a42694e6f7a6b54782f7250706b473438564b534571506d6a2b64655871566441536f7a505570495941457a6d7836515a556b714b31715059412f7742425564467a4f755349316c5947644d644f4c4a2f646f327765566e39614c59443937694e7a6a75553974594c5a544a636a47514b4c7935386b6e4142504d4466316b7a6b344656494f4f59447350534a39513671434479416e3070677047654968726a6953327364587658457473706765744e324b766354754a676d6d614174546953737953636b316f625055773853347437684c4d6a474f314963346d714d784d2f7242556f6d4b376154475a41346a6c4f6f5433727774706e71375933307939794d30354f444557446956746f39497230464752505059344d4831647945476d424d514e325a43584c706b304a6e5364315a6a666e38513731717352436b35634e657455493045695a65496f444861694b676d6147496e41765a4d47734e5848453057387a5235684b7867436c4b78513878784159514637535663707079366b657353644f6653427274314a35464f46697430597259796e6d614d334b6b384b4972434244426a6979366b65522b4b666d6b7455444442784b6a512b753068593853514b6d73306d656f305863596c6b2f716474636746437850736147725468655745503468386251654a2b595579325232714a2b4c4e776e6f3133485a746c4462616f33484970793357457952307a36784c72746b3038647741393470646c68335a4d706f515977306e335032664e752b594c556e346a2b744d585574364c45326f6f4f4975642f5a666b2f3841554b507942564878624166646b6e674b544f572f32636c4d417643506a5038414f6c767169656352693171493673656a32734a334548314270564675397350474d6d426c59314f6c727468755373716a4f6166384d7462623050306d6f2f696556784f5875725874736842785454726749394e445544676d50644d315a7439727a6741786d61594e616a4c784a4c74475566796e694a4c2b335179647a4b754f526e4e52574d716e4b79796c6d78683470314871333049534254774134366b725074395a4d6170316b4a6e655665773470696163784436675248633951767647454342547857716a6d546c326147615a30303436647a684b7635554457353445344c6a75557a656c4e4d447a526968555a6e466f74316656435038413478385256436a41697963774e70703178515573376478344a6f436768683864537174656d664b4e797744364450363038496f484d53624d784f30686676586966445a366e722f4552767072626b69416130614e79654974395176724c2f51375a6344645870553659714f5a3531747762714e377178436b3570374943496b4e495457394c326b78554e6962544b4662496b323562484d3076454b4b6451735a77425767346d78486432716b3035584534785934316d714165496f6a6d624e5950745332414d617049686a5633744d48696c4e566d4e5779474b4c617853646a434d33417a352f2f41446f587752572b4d797a4e716d666e656a7a324e61757150744d384a59517a2b7a6c35596b4c412b31505855664b4b5a424d334f683732324f394b6b7141394352546936734f596f416738545139544b535963624d6a42714d304b54775a574c53427a43322b72326a79434b3777474534574348576e574c53424531686f4a394958692f4f4570363351524156464d57724870464d2f50633061366f62504c6e36306668353949506947593350554c61763841376631705a702b554d58544272716874426e784a70523032546b434f476f346d643731346b346d525866443245596d2b4f673569747a72524d5142574437503435697a717563774e585743344f304556672b7a7750574d47734a67332b63334b78745449422b615a3850577079544d6255757767697449645639524f617057786653534d7050634c736441377172477539707931783962573761494f4d564d7a6b78777278474475736255776a4643435a684145534f764b664a5354564e533435694c477848656d324731414c67456a36663841656e484a50456d7a7a4462445366455875554d546766316f774d54533075725454427445696d42594261523352695047414a464c727178475750505137485230707a465568514a4d574a6a6c6d33417851735a6f4531554d55454f4b4e527351735a7062726d4572596b6271576b375366536f6e54615a51477a454e785a775a70634b4b376931424e614f35325a694e43626353635172317036744269433930705344677a52354532422b435a6731303266564969686877687539576b594e447442504d334a416d3674646441426e696938465a6e694750744f3639324a47354e594b534a7863474f5664624e75494a495048705845456354414d7945764c744c6a696942676d6b4d68484d6f4239494b576b7a7858417469627843547069434a465a3472436474426762756d6754546c764d5731596e367a305971504e45392b4f6f41726a565853766c6e64537871446d45617841463645427961594c3450687a6c476b49373058696d6434554865596253596971463669443347566d706b416558394b6d7355356a6c50456247356245516e394b6d494d594a6863586356675977396f674c742b6542545654504a676c3864544d334a504a6f76444557584d374e386b434f39454b695942614e7445303875656338544141376e337036316752547469576c6870796a47347a2f536d525571744d3073436a5659426150554d5149706b43662f32513d3d, 'combo', 0, '2017-05-31 14:24:24', '2017-05-31 14:24:24', '123458', '123458', 5, 4, 'materialproducto2', 'NO', 'colorproducto2', 'tamanio ', 'pesoprooducto3', 10, 2, 20, 0, 'modeloproducto3', 'estiloproducto3', 'corteproducto2', '202.00', 0);
INSERT INTO `producto` (`id`, `nombre`, `descripcion`, `precioVenta`, `idTipoProducto`, `idSubTipoProducto`, `imagen`, `tipoproducto`, `eliminado`, `created_at`, `updated_at`, `codigoInterno`, `codigoDeBarra`, `idOrigen`, `idMarca`, `material`, `usado`, `color`, `tamano`, `peso`, `unidadesCaja`, `stockMin`, `stockMax`, `ventadirecta`, `modelo`, `estilo`, `corte`, `precioVentaCredito`, `conStock`) VALUES
(379, 'productocombo2', 'descripcionproducto4', '103.00', 1, 8, 0x646174613a696d6167652f706e673b6261736536342c6956424f5277304b47676f414141414e5355684555674141414867414141426f4341494141414331304b4d654141414141584e535230494172733463365141414141526e51553142414143786a777638595155414141414a6345685a6377414144734d4141413744416364767147514141424a505355524256486865375a3162634650484763636c2b534a62386755625932706a456936474249504247427775355356704c704d4a675347426159615a4e4d4f6b435a4e6d4a74503249592b5a79557737504b6270537959506d647a547a49424c48704a43486e4a7253777234677247354f64526348475077426475794a646d794c4b6d2f63373744515a614e6b644352644654375038356d393973396538372b39372f66666e736b624f753161396363446f66646272656f794d6a49794d374b6c6a7759472f585259506d4b5a58352f77477131597248616747566f6150696a6a7a3736387373764a79596d4c426d327a4d784d70394d35506a377563726d4b69347676762f2f2b6e4a77636a4b46516941613575626e59723179355170372b673847676444367259423063484953676f714b697249777369383053436c6c2b2f34632f58727a55415231723136356c41686f624737576d4b74482f39354268566c64584e7a51305a47566c4b624b3642516968646c71686950334d6d54506b6b5264464946562b763538714b315a7939446738374835352f333633323431436c7931624a6a4b38644f6b537770514c5a676d45736a5672316b42786533733735454352564b31667635353849424351596a677730724b6a5178456f46793564757051316a52306a4b586d4661465930375a376176694e2f5869475a38516c2f31594f726d4279574f5933306d5a6b6c594e515141714156796b36644f69552b6b4b725230644861327470706c65667a2b633665505974655a544c676c325a43656e392f2f3430624e327a3069496d4b374e796373624778425173572b48336a734a79646e61334b66336178444743486c4a324773597365785149514a707246434a55594561576b464339637541426a654671787743385a374c3239765679435256453058657a61746575423157736f6a34794d64485a3253722b7a475455314e59693075626b354c7938504c553456484b4b55444645414461433473724a5366414130496d3249626d7472493456306a417252396658314f50372f58726b4b7935703139676b354171787351455a63714d67784844436f5a7a5a76336f785845563868786e506e7a684669514b59655a646d597430382b2f31744f6e74506a385742566e4d57735a786c3064336644444d496b306f426c6e5545646b43696f714b68413057526f413243504c5a524c734a44584c375432396658742f63337a6d444949372b597742666742396a64304c5852446e366962564355746f367171696a7a367855646a623231747059313636535459704955306d734e5549477038416d4531394146646f51422b78536b4463636f7948324b4a6749305a6f385764717563414f57794a5455314e524e5955785245444d685135377146523247747061634576593947706a34435645472f665337395635306f7a7a5745716f413946456f7151586c64426b4363784e567866766e795a79492b71476259334b3376673775642b505564304e474237334c527045344564456f5a69614c313538796252734c43734e626f446c473378715a3037356f694f4872414d616243732b4f7a4a586e7347524e7475446a6f67467a6d54777668646861784469616931374c334335584a784f706f3932796b6a6c6345696169444775324b61694339574f42774f6a70527a4b324e6d3250517a2b7a3244454a4a314a5075795a6a497869425a366533736c4c755a4570316b544435754252785864437a4547795a674b346c6852773871564b2b56316a3854437959487873387049354f32693254436b6f7279386e4d68584d79555232736e64514b427259486933386344746476663339342b506a2b666d3567344d4445532f67786b496d2f3678724c45776c6664677535342f662f3643425173534e4e686f6b436736524e48516a53744d3153624a4c67327a654754324965544d593651774e457173376867595849736e5365614335615a794f2b3762337434756e344249566171517641582b7a445050614c6e454133376e7a5a76486e6f7951552b4b527030494a643752734973466f362b76726b625a575468676b674e753664537642736d597942777734676b6350665649547356564b6d4d7877486e3330305750486a7547677451707a494157784158516e497443574d4a6c643463534a4535724a5446412b79744b7979514b6941775a756a31367656772b546d55574b576f575a59464f2b4135594b734d794e4372626f4a2b5668386c31684778703261646e6b5168534e414b464a3939307841532b4d53746a30696f714b5568346d33785732386f6f4b4c5a734b34454e6b42794d5039574b4d426a51476e45544b793876782b476257736b414c37464d4f38647061345737772b5877776935775274546c66594532467261656e5238756d46437a38614c7832666e342b6a674b505044773872487758566f56575a323759544b5549506234575a78494f4a457a74344f4467794d6749576f5a6c72534a4e59434d6b30724b6d4159524f5061386a64687946772b46493573636942734a6d74684d55674e4f4442772f6973703939396c6c494a344f635366455379592f366a554a536a2b417841566f50487a344d313457466851382f2f444475596d4267514b744c51316a627a7039372f665858315931494d356b456242354546787a7a46693161524772436c5263547a4f673667487a6853672b54355348544a634359466f6e364b4373654947533852476c704b667a715156464d6762594a5965545844654b48486962726e364c714b705a6a75706b5032544d44353579434e36565477634b435844314d76704e4430352f32336c36507042436d59426b673163574c4630635a4a707445484445683964386d35514677767544697859745268736c63496c4770424e70694e446d4d2f774a4e6c49416779504a345048495951636a3338435431396657516e685a63383543706555706f4a6270343964565832653769504f2f743272557256584b4a48716c6857594b322f667633662f44424237416335787369644d317337646d7a7837536e58474474377531353863555830585553664458484543676d6f4e7932626474333333326e575932474f6862545259464a5662514578647533622f2f6969792f456b676959382f56656b6f6a47503768634c6f4a66346f54446877385846685a7146516e41724659304b734e7039506633662f333131356f706b56414f6c435a374d5a4a596f676b474247695a4d4b3634754a68345471744c4a47425a626d7165344339524438487769414677455778396a446c66685661584c504141346b5949534452543670416f6f6947336f4b434151534b756c48387351767a483832694646434568524575594c4b2b4834672b543434664d64326f4462534f4a486830646454716457566c5a6a7a2f2b4f416f7957356831384f424243587530636e4a684a4e473575626d516d2b67774f5534634f6e516f4a64756a41626645556541664f50555255586939336b53487958454348794b68434b6d34754f5441414b4a7866424339632b644f2f49615a337a614541355a78644d6c3857674f4952737645634565504874584b61514a6b6b55776659734364554164454a2b636b6b6e595163706a523545337072415645352b586c7a5246745047536e4a634374714b6859766e7a35344f4367792b576149397034454f4d53323553566c5246396e546c7a787546774b43384470413736675867534d6e71614c6c474571514452363961744b796b706b563974536e68545656576c4d4c747935636f48486e69414f7372723136396e4f325a436c693162746d624e47757879774d4f69646a4b48365946476f5169754547687061536b57696b533951696d5267764a523168747676484874326a5561553863684663467a6a4f3774375a56767361786475355941727257314e5a6e6866546f436c717572717948743674577241774d446e4a4e524d4a5165503334384a79644855545173693244466577775044394d4f63696d4378735a4733506d71566174774f6b79443275636362674f4b534f47337271344f6438773243464834417a5a415958587a3573324b3134372b77316b757070654f6a6737696c546e476463416d5070626c446a4f51506a593268684f474b4f534c5a4d6e55317462534a6761696162786b79524b384f78657a6d6370307a5145326b42336b697253685a57526b4249726e7a352b5077436d32744c5241722b49364b4b447438764a7969556a773346794d3631443775513036756e7a354d72566378527a4f326f434567554d466a433163754a43396a6a324d4a58372b2f486c6f68426b6f37757a7378506465756e534a4e764b62596546545566546262372b4e344f31323556384e3051565749684957676b78444246676a64727564755671386544473961395a5a42706942596e676b526d436a5133616e54703343434930624e3236453861616d4a74676e673135395070397943662f424c466131423058647a4668425155464e54593159496f44587037336237655953452f364c726951414c634c5036644f6e662f72704a39684130596950464d5932624e69412b4841646b413654744e512f787074477330416d51592f6e754978725a476b776a63774549412f6430694474454c49715032444d34383277574732576f50366a316b6443744d6b4b6e356941757247686f59487362467872634f504757744b3274744d6f47727267424839393965726c72437a384b6c3046595a75722b46466378317476766158326464766e77695a7a6866354a345a532b4956706e756269346d464d38553071654a39437553537349793961514a642f682f507a7a7a3153624271566d7567397933534d6568794e484245664b324b484d362f45346e4536707a6374332b7366684c56507449684c544534317a7548486a526c39664830527a4f4b53496c78434253324448546d7243662f77535061775a79726368726c2f725076486a6a794b564d522b376c4471696b44576f576d7771335a49502b674d546f6141394f38732f3776763535352b584c612f306a667673484f697374734345337838493547546c4449304d467863573044686743656d4f416c57544a3532656147594d4b74584659704d2f3277444c796878614c4c445072736f456b4539544f594e414b4d6942776a5069626d6c713172663836725856704545596e6a4973687338786f7175724538317833694e304f334869424c5251704a594e384f544a52756753516b4b686151345a43744837397532624e32396552635639304572305271534e723243764579316a314a70617257317462586750657353657069776a46344a55486834457876316e7a375147417667425a53787261745a4c6d334151494a4d536868476f63533348504d675a476870694a39793061524f643950623258726c7952565134417851536f5a4a746a593434686e424f70314e59727179736c4371686c5477545149596578553870563663745249796949574835546d687562696157355a684744436462464b755a434a6f444e3530514c2b4e4a37736f7955425164666a4c6b5976706c6d656a383470527633727a5a336430645458646d426b4e6a343245345a414157617a445531747043526b5339746e6144326e41535949624775493648486e6f49577476623235314f5a33563164553950543164586c376f776f744a634a4e474c466930714b69715369306d3542306f6e35666d69374e476367474c304b434d53415147646141794947396442417853325973554b2f414d6e4554596b3469744554527430566c74627937556f757147685161597165747a6143473542337046714259766c374e6d7a386b63613070706c655836675578794f69516e746c7a6f7863466865756e51704c7051444e4636597053792f3345315738386d544a306e52746669636d444470416836496d6553755448356a59794f4242336c75482b76736d5151777936364630784f5049526170436f65774e6a6a6f676b316d34754c46692b783457375a737753397a46544566446469785a426f49427a70752f5858496d4b43346a763337392b4f446d4f79536b684a6342373349533477303552656f386c582b6e43347077354555753479497642624142555066662f4e7453556b787066372b2f6b63656677786d56362b754c6973724f2f436e502b666d324874366576507a6e55514b4d494d4534336e6c6f45776d2b7555475656565673457952417946547039616d4b3642795a47514566735670614e6270384f4748483749546b694841526277416a3846476c357472392f6e473659657472375330564f4a64756554656f436a367a546666704c764f7a713742775545574344705061793050447738546f5a49586479466333306e526d5662627156504e566b7549413454484e2f625959342f68736d6c322f4e695068515635355045727449396665597169695a3076584c6a416a5a673055594855705231342b4f765872384d795178426d42634b3156684448456c4a656443695a7a497a756e683461454e51574f765071317463693671416c394d4f2f2f2b5561647666323953453751395a337a4c756e6d554530786f59576b31426f7648506e546e57377368485050664845453854496b50374f4f2b2b77754d764b46736255327778516946596d4e6f376f6a656344754232746e416f51683845797045544a697a356539696635537a534b50776d4664757a5951567a426d6942576565474646374448773077344446413046424f3076506261613134566d6a587845466f424c464f63796f6859777531544c594956717834633953752f4f7833466e507a50386144507a7752633765356158564e442f2b4c723434514252484e32346d6e65666664647773526b666756642b434a534a6850756b65384e48454d6b51322f76766663656e68716c2b33772b7a747947764873776747674578546a78612b2b2f2f2f3654547a364a46676974744c724541482f4b476d496c775934732b516952556f7977414c464971757834366d466162616a386c6977384e58623150424863746d30724b554c6d7a4c4a392b33614d7976587877636a4e6b506b2f637554493774323769556b315532494142544b584d4336575741477a7341796257766e57332f6e5739314b4b654770414d37552b5868684a4e4d45346b2f2f7878782f444f4c754b5a6a55617345796b6a417868524569354b36537835434758366547776830556e326d724e344766544c37663551785a37646961615039643675736a706447526d577533326a5a753376505758762f6f6e34764a4f6b572f766a414a4359476e544c617576714b684939717634495234356e434e6855453846616f316d6c497a6b392b37642b2b6d6e6e34704672626c64425a417a4958505469654e536c436c5a664e2b536f704c35334e51374e6e71366f51454e4d52787045424f4d5648513458433758776f554c43776f4b654d53757269374e476763594e7141337252773759506d7a7a795a39446873422f416c72706136756a6a7a725268566638464a4868332f4d35786e31636d76556732366b63617849464e48736a527731455973515244524371745846446e536b484a48566679616a432f424f304364447a346a6e465a624653427165416554787948444e6758423535556f6c30676a426a69307a4b32507638337664376d486d75724b71617432476a66562f2f384c6a6a666d505143624b6455534135325977375057732f5369334678354a416c6a325053614a6f6c4173715435743455624a36426269482b3549476d376e4d634b6e58497954556e5632416d506a353836644a61764f687358743962413962743279626354726b52443264792b2f394d6f72727a415772704a356d686d4a556e514565427234366c48664b6d696d7577474347414171672b4a6f526a49564f467732353374375532484c7971797265306a2f68675a7a4136635a4e6c76702f424a4559382f4e2b655354543270726138656a2f684e5253564b30446f364f4c452f6f6d2b47335376446f61426b464d62784a5767744c497853745a30676c6f7938494d556f71475946756a436a7147516a6c4c6a6a6f3967766e4e5337562f33453633374e6e7a3444364c53306556546e616545655a674f2b2f2f316170316c6f7051704631724a6153546a5467396b382f2f66525858333246786a6e79617459774d44786d676f772b5a6a326a707a7252494e7a4f3842694c61745973676f687239664648744a466968444854616a766430757a33423941303130722f7a4f48446a2f794b327845706f6d734778644b68534a563838795943535849643463424e487a703069444e6b4f466b43586374614f586167723343616f6f5243384f5372784149734e6d764145717264554e656c2f4f75543230656b4d652f594439392b632b796650797a36525a6b6c454d7a4f73553845417a546d4a32544c6d5079444a5a59766f68734c5842355242424b51514a76444e486c6336745431486d584b4a44333333484f45466c495575304430533059337176554b5a73694846775857594b69317455576b726132495739412f676a6c77344d412f6a6836526779746a3452446e64587532624e6e795039415645516d2f433132544141414141456c46546b5375516d4343, 'combo', 0, '2017-05-31 14:36:29', '2017-05-31 14:36:29', '123459', '123459', 5, 4, 'materialproducto2', 'NO', 'colorproducto2', 'tamanio4', 'pesoprooducto3', 20, 2, 10, 0, 'modeloproducto4', 'estiloproducto4', 'corteproducto4', '203.00', 0),
(380, 'productoingrediente1', 'dessss', '100.00', 8, 0, 0x646174613a696d6167652f706e673b6261736536342c6956424f5277304b47676f414141414e5355684555674141414867414141426f4341494141414331304b4d654141414141584e535230494172733463365141414141526e51553142414143786a777638595155414141414a6345685a6377414144734d4141413744416364767147514141424a505355524256486865375a3162634650484763636c2b534a62386755625932706a456936474249504247427775355356704c704d4a675347426159615a4e4d4f6b435a4e6d4a74503249592b5a79557737504b6270537959506d647a547a49424c48704a43486e4a7253777234677247354f64526348475077426475794a646d794c4b6d2f63373744515a614e6b644352644654375038356d393973396538372b39372f66666e736b624f753161396363446f66646272656f794d6a49794d374b6c6a7759472f585259506d4b5a58352f77477131597248616747566f6150696a6a7a3736387373764a79596d4c426d327a4d784d70394d35506a377563726d4b69347676762f2f2b6e4a77636a4b46516941613575626e59723179355170372b673847676444367259423063484953676f714b697249777369383053436c6c2b2f34632f58727a55415231723136356c41686f624737576d4b74482f39354268566c64584e7a51305a47566c4b624b3642516968646c71686950334d6d54506b6b5264464946562b763538714b315a7939446738374835352f333633323431436c7931624a6a4b38644f6b537770514c5a676d45736a5672316b42786533733735454352564b31667635353849424351596a677730724b6a5178456f46793564757051316a52306a4b586d4661465930375a376176694e2f5869475a38516c2f31594f726d4279574f5933306d5a6b6c594e515141714156796b36644f69552b6b4b725230644861327470706c65667a2b633665505974655a544c676c325a43656e392f2f3430624e327a3069496d4b374e796373624778425173572b48336a734a79646e61334b66336178444743486c4a324773597365785149514a707246434a55594561576b464339637541426a654671787743385a374c3239765679435256453058657a61746575423157736f6a34794d64485a3253722b7a475455314e59693075626b354c7938504c553456484b4b55444645414461433473724a5366414130496d3249626d7472493456306a417252396658314f50372f58726b4b7935703139676b354171787351455a63714d67784844436f5a7a5a76336f785845563868786e506e7a684669514b59655a646d597430382b2f31744f6e74506a385742566e4d57735a786c3064336644444d496b306f426c6e5545646b43696f714b68413057526f413243504c5a524c734a44584c375432396658742f63337a6d444949372b597742666742396a64304c5852446e366962564355746f367171696a7a367855646a623231747059313636535459704955306d734e5549477038416d4531394146646f51422b78536b4463636f7948324b4a6749305a6f385764717563414f57794a5455314e524e5955785245444d685135377146523247747061634576593947706a34435645472f665337395635306f7a7a5745716f413946456f7151586c64426b4363784e567866766e795a79492b71476259334b3376673775642b505564304e474237334c527045344564456f5a69614c313538796252734c43734e626f446c473378715a3037356f694f4872414d616243732b4f7a4a586e7347524e7475446a6f67467a6d54777668646861784469616931374c334335584a784f706f3932796b6a6c6345696169444775324b61694339574f42774f6a70527a4b324e6d3250517a2b7a3244454a4a314a5075795a6a497869425a366533736c4c755a4570316b544435754252785864437a4547795a674b346c6852773871564b2b56316a3854437959487873387049354f32693254436b6f7279386e4d68584d79555232736e64514b427259486933386344746476663339342b506a2b666d3567344d4445532f67786b496d2f3678724c45776c6664677535342f662f3643425173534e4e686f6b436736524e48516a53744d3153624a4c67327a654754324965544d593651774e457173376867595849736e5365614335615a794f2b3762337434756e344249566171517641582b7a445050614c6e454133376e7a5a76486e6f7951552b4b527030494a643752734973466f362b76726b625a575468676b674e753664537642736d597942777734676b6350665649547356564b6d4d7877486e3330305750486a7547677451707a494157784158516e497443574d4a6c643463534a4535724a5446412b79744b7979514b6941775a756a31367656772b546d55574b576f575a59464f2b4135594b734d794e4372626f4a2b5668386c31684778703261646e6b5168534e414b464a3939307841532b4d53746a30696f714b5568346d33785732386f6f4b4c5a734b34454e6b42794d5039574b4d426a51476e45544b793876782b476257736b414c37464d4f38647061345737772b5877776935775274546c66594532467261656e5238756d46437a38614c7832666e342b6a674b505044773872487758566f56575a323759544b5549506234575a78494f4a457a74344f4467794d6749576f5a6c72534a4e59434d6b30724b6d4159524f5061386a64687946772b46493573636942734a6d74684d55674e4f4442772f6973703939396c6c494a344f635366455379592f366a554a536a2b417841566f50487a344d313457466851382f2f444475596d4267514b744c51316a627a7039372f665858315931494d356b456242354546787a7a46693161524772436c5263547a4f673667487a6853672b54355348544a634359466f6e364b4373654947533852476c704b667a715156464d6762594a5965545844654b48486962726e364c714b705a6a75706b5032544d44353579434e36565477634b435844314d76704e4430352f32336c36507042436d59426b673163574c4630635a4a707445484445683964386d35514677767544697859745268736c63496c4770424e70694e446d4d2f774a4e6c49416779504a345048495951636a3338435431396657516e685a63383543706555706f4a6270343964565832653769504f2f743272557256584b4a48716c6857594b322f667633662f44424237416335787369644d317337646d7a7837536e58474474377531353863555830585553664458484543676d6f4e7932626474333333326e575932474f6862545259464a5662514578647533622f2f6969792f456b676959382f56656b6f6a47503768634c6f4a66346f54446877385846685a7146516e41724659304b734e7039506633662f333131356f706b56414f6c435a374d5a4a596f676b474247695a4d4b3634754a68345471744c4a47425a626d7165344339524438487769414677455778396a446c66685661584c504141346b5949534452543670416f6f6947336f4b434151534b756c48387351767a483832694646434568524575594c4b2b4834672b543434664d64326f4462534f4a486830646454716457566c5a6a7a2f2b4f416f7957356831384f424243587530636e4a684a4e473575626d516d2b67774f5534634f6e516f4a64756a41626645556541664f50555255586939336b53487958454348794b68434b6d34754f5441414b4a7866424339632b644f2f49615a337a614541355a78644d6c3857674f4952737645634565504874584b61514a6b6b55776659734364554164454a2b636b6b6e595163706a523545337072415645352b586c7a5246745047536e4a634374714b6859766e7a35344f4367792b576149397034454f4d53323553566c5246396e546c7a787546774b43384470413736675867534d6e71614c6c474571514452363961744b796b706b563974536e68545656576c4d4c747935636f48486e69414f7372723136396e4f325a436c693162746d624e47757879774d4f69646a4b48365946476f5169754547687061536b57696b533951696d5267764a523168747676484874326a5561553863684663467a6a4f3774375a56767361786475355941727257314e5a6e6866546f436c717572717948743674577241774d446e4a4e524d4a5165503334384a79644855545173693244466577775044394d4f63696d4378735a4733506d71566174774f6b79443275636362674f4b534f47337271344f6438773243464834417a5a415958587a3573324b3134372b77316b757070654f6a6737696c546e476463416d5070626c446a4f51506a593268684f474b4f534c5a4d6e55317462534a6761696162786b79524b384f78657a6d6370307a5145326b42336b697253685a57526b4249726e7a352b5077436d32744c5241722b49364b4b447438764a7969556a773346794d3631443775513036756e7a354d72566378527a4f326f434567554d466a433163754a43396a6a324d4a58372b2f486c6f68426b6f37757a7378506465756e534a4e764b62596546545566546262372b4e344f31323556384e3051565749684957676b78444246676a64727564755671386544473961395a5a42706942596e676b526d436a5133616e54703343434930624e3236453861616d4a74676e673135395070397943662f424c466131423058647a4668425155464e54593159496f44587037336237655953452f364c726951414c634c5036644f6e662f72704a39684130596950464d5932624e69412b4841646b413654744e512f787074477330416d51592f6e754978725a476b776a63774549412f6430694474454c49715032444d34383277574732576f50366a316b6443744d6b4b6e356941757247686f59487362467872634f504757744b3274744d6f47727267424839393965726c72437a384b6c3046595a75722b46466378317476766158326464766e77695a7a6866354a345a532b4956706e756269346d464d38553071654a39437553537349793961514a642f682f507a7a7a3153624271566d7567397933534d6568794e484245664b324b484d362f45346e4536707a6374332b7366684c56507449684c544534317a7548486a526c39664830527a4f4b53496c78434253324448546d7243662f77535061775a79726368726c2f725076486a6a794b564d522b376c4471696b44576f576d7771335a49502b674d546f6141394f38732f3776763535352b584c612f306a667673484f697374734345337838493547546c4449304d467863573044686743656d4f416c57544a3532656147594d4b74584659704d2f3277444c796878614c4c445072736f456b4539544f594e414b4d6942776a5069626d6c713172663836725856704545596e6a4973687338786f7175724538317833694e304f334869424c5251704a594e384f544a52756753516b4b686151345a43744837397532624e32396552635639304572305271534e723243764579316a314a70617257317462586750657353657069776a46344a55486834457876316e7a375147417667425a53787261745a4c6d334151494a4d536868476f63533348504d675a476870694a39793061524f643950623258726c7952565134417851536f5a4a746a593434686e424f70314e59727179736c4371686c5477545149596578553870563663745249796949574835546d687562696157355a684744436462464b755a434a6f444e3530514c2b4e4a37736f7955425164666a4c6b5976706c6d656a383470527633727a5a336430645458646d426b4e6a343245345a414157617a445531747043526b5339746e6144326e41535949624775493648486e6f49577476623235314f5a33563164553950543164586c376f776f744a634a4e474c466930714b69715369306d3542306f6e35666d69374e476367474c304b434d53415147646141794947396442417853325973554b2f414d6e4554596b3469744554527430566c74627937556f757147685161597165747a6143473542337046714259766c374e6d7a386b63613070706c655836675578794f69516e746c7a6f7863466865756e51704c7051444e4636597053792f3345315738386d544a306e52746669636d444470416836496d6553755448356a59794f4242336c75482b76736d5151777936364630784f5049526170436f65774e6a6a6f676b316d34754c46692b783457375a737753397a46544566446469785a426f49427a70752f5858496d4b43346a763337392b4f446d4f79536b684a6342373349533477303552656f386c582b6e43347077354555753479497642624142555066662f4e7453556b787066372b2f6b63656677786d56362b754c6973724f2f436e502b666d324874366576507a6e55514b4d494d4534336e6c6f45776d2b7555475656565673457952417946547039616d4b3642795a47514566735670614e6270384f4748483749546b694841526277416a3846476c357472392f6e473659657472375330564f4a64756554656f436a367a546666704c764f7a713742775545574344705061793050447738546f5a49586479466333306e526d5662627156504e566b7549413454484e2f625959342f68736d6c322f4e695068515635355045727449396665597169695a3076584c6a416a5a673055594855705231342b4f765872384d795178426d42634b3156684448456c4a656443695a7a497a756e683461454e51574f765071317463693671416c394d4f2f2f2b5561647666323953453751395a337a4c756e6d554530786f59576b31426f7648506e546e57377368485050664845453854496b50374f4f2b2b77754d764b46736255327778516946596d4e6f376f6a656344754232746e416f51683845797045544a697a356539696635537a534b50776d4664757a5951567a426d6942576565474646374448773077344446413046424f3076506261613134566d6a587845466f424c464f63796f6859777531544c594956717834633953752f4f7833466e507a50386144507a7752633765356158564e442f2b4c723434514252484e32346d6e65666664647773526b666756642b434a534a6850756b65384e48454d6b51322f76766663656e68716c2b33772b7a747947764873776747674578546a78612b2b2f2f2f3654547a364a46676974744c724541482f4b476d496c775934732b516952556f7977414c464971757834366d466162616a386c6977384e58623150424863746d30724b554c6d7a4c4a392b33614d7976587877636a4e6b506b2f637554493774323769556b315532494142544b584d4336575741477a7341796257766e57332f6e5739314b4b654770414d37552b5868684a4e4d45346b2f2f7878782f444f4c754b5a6a55617345796b6a417868524569354b36537835434758366547776830556e326d724e344766544c37663551785a37646961615039643675736a706447526d577533326a5a753376505758762f6f6e34764a4f6b572f766a414a4359476e544c617576714b684939717634495234356e434e6855453846616f316d6c497a6b392b37642b2b6d6e6e34704672626c64425a417a4958505469654e536c436c5a664e2b536f704c35334e51374e6e71366f51454e4d52787045424f4d5648513458433758776f554c43776f4b654d53757269374e476763594e7141337252773759506d7a7a795a39446873422f416c72706136756a6a7a725268566638464a4868332f4d35786e31636d76556732366b63617849464e48736a527731455973515244524371745846446e536b484a48566679616a432f424f304364447a346a6e465a624653427165416554787948444e6758423535556f6c30676a426a69307a4b32507638337664376d486d75724b71617432476a66562f2f384c6a6a666d505143624b6455534135325977375057732f5369334678354a416c6a325053614a6f6c4173715435743455624a36426269482b3549476d376e4d634b6e58497954556e5632416d506a353836644a61764f687358743962413962743279626354726b52443264792b2f394d6f72727a415772704a356d686d4a556e514565427234366c48664b6d696d7577474347414171672b4a6f526a49564f467732353374375532484c7971797265306a2f68675a7a4136635a4e6c76702f424a4559382f4e2b655354543270726138656a2f684e5253564b30446f364f4c452f6f6d2b47335376446f61426b464d62784a5767744c497853745a30676c6f7938494d556f71475946756a436a7147516a6c4c6a6a6f3967766e4e5337562f33453633374e6e7a3444364c53306556546e616545655a674f2b2f2f316170316c6f7051704631724a6153546a5467396b382f2f66525858333246786a6e79617459774d44786d676f772b5a6a326a707a7252494e7a4f3842694c61745973676f687239664648744a466968444854616a766430757a33423941303130722f7a4f48446a2f794b327845706f6d734778644b68534a563838795943535849643463424e487a703069444e6b4f466b43586374614f586167723343616f6f5243384f5372784149734e6d764145717264554e656c2f4f75543230656b4d652f594439392b632b796650797a36525a6b6c454d7a4f73553845417a546d4a32544c6d5079444a5a59766f68734c5842355242424b51514a76444e486c6336745431486d584b4a44333333484f45466c495575304430533059337176554b5a73694846775857594b69317455576b726132495739412f676a6c77344d412f6a6836526779746a3452446e64587532624e6e795039415645516d2f433132544141414141456c46546b5375516d4343, 'ingrediente', 0, '2017-06-01 16:58:51', '2017-06-01 16:58:51', NULL, NULL, 5, 4, NULL, NULL, NULL, NULL, NULL, 10, 2, 5, 0, NULL, NULL, NULL, '101.00', 1);
INSERT INTO `producto` (`id`, `nombre`, `descripcion`, `precioVenta`, `idTipoProducto`, `idSubTipoProducto`, `imagen`, `tipoproducto`, `eliminado`, `created_at`, `updated_at`, `codigoInterno`, `codigoDeBarra`, `idOrigen`, `idMarca`, `material`, `usado`, `color`, `tamano`, `peso`, `unidadesCaja`, `stockMin`, `stockMax`, `ventadirecta`, `modelo`, `estilo`, `corte`, `precioVentaCredito`, `conStock`) VALUES
(381, 'productoingrediente2', 'ddddddd', '110.00', 8, 0, 0x646174613a696d6167652f6a7065673b6261736536342c2f396a2f34414151536b5a4a5267414241514141415141424141442f3277434541416b4742784d54456855544578495646525558467867594742675647526359476867564678675846786759474267594853676747426f6c48525559495445694a536b724c6934754742387a4f444d744e7967744c69734243676f4b4467304f477841514779386c494359744c5330744c5330744c5330744c5330744c5330744c5330744c5330744c5330744c5330744c5330744c5338744c5330744c5330744c5330744c5330744c5330744c662f4141424549414c634245774d4245514143455145444551482f78414163414141434177454241514541414141414141414141414145425149444267634241416a2f784142444541414241675144425155474177554842414d4141414142416845414177516842524978426b465259584554496f47526f516379516c4b78775350523842526963704c684653517a51314f4338574f697375495746384c2f784141624151414341774542415141414141414141414141414141434177454542514147422f2f45414455524141494341514d434177634442414d4241414d414141454341414d52424249684d554546453145694d6d4678675a4768464c48774938485238554a53345255474d324c2f3267414d41774541416845444551412f414d7474564d70436c36554b54794c333834726b4b4f6b3231563859615932615669366b6b43497744423375766150396c3866374146505a68626c3778773468627438366c734c5879706f6e4c536e4c4d6357505451637252352f783273756759412f5356745172444531435a6741334a50336a796c6274572b3453727965444173577870556d577857644f4f7362766839396f72334d784f65677a4e72773351693342497a4273436d4c6e6c306a753645376e6a54727161346343584e614b394f4d4d655a375834736d564d4b474b696e577841383471585674573241527831457833317964414a344d655774786c4345745a6937774a31566a2b7a306c76533037795359745146546c466c4d334837515172475a736b725376496a50434e6e564b57366c6430616e386f75616653372f6c4b657138525656776f3568474b556557616a735a68546b31476f55434e44465055304c6b68656b6f344e39594c386335346a6243616f5a77564d5874357772773076527168356e4950457058304d6f6a4b726c6a4f6d316e6a306570787555343469716d4951387a3275546f77386f7236784d344145366b39637779696b7079677466664676535556697345446d49746474784575374250434c506b312b6b58356a536551634950614d51636d664a544841596e5a6b4a6b6b45756672484541795178485352374162767245625a3236536130646964506b6949356e4577657471784c316a4f313276476c484d4a526d434978554b30744753504831626a704438737936524f49336778646f31787879774d67726d4a73576e46697037336962624365535a7155494f6b7a794d515754547471736c34554879424c5a72484d61794b78514f762b666c384e384e4668422b7351315950326d6f37646679786f2b5a622f316d567372395a3932362f6c39596e7a4c662b736a5a58367a3056432f6c39593450623654746c6672507632686679657364356c762f41466e624539596b7238476b7a566c537052633674465236797a5a4b7a527031747453425662694b6c374e30543353727a564659314a36533650454e586a676a38526257344268456c4379716c55737047674d7853695275417a61776466695662326557464f5a532f724e6a6b54525565413046564b412f595542414675316c736f644876476b6a4c61505a4248346c57377a4b44686d422b41356d573267396d4e456f6b79486b4c4734456c4375524230384951316751344a7a48314f783549676d7a6b71584b6d2f733661594266787266634e37373456714e6256585675504d6464555647356a394a704b36596853776b4d3664786a7856746a574d624d59426d55374174695a62614b586d544d563369744a444a476b58644932436f375463384c38554b4555746744316a5859476575656a7345756e4c645a34506f49336835747169696f343954384a533854744675705a7335416a6e4556796b5a6b7159735343654d592b713051724a77636d4e703052745559455531654444734d386c544b5663412b363332696d75703273412f31693637374e492b4d644f786e744c6855384a6c736b4f64534459635359304b624e337444704e63363668777a452f536153686b4c6c4a4a5776753644393478704c66354e486d4f6341394a6b36692b7479416f356d64704a6f6e31716b35694141535735572b38554874634c6b5459755536665268694a70714b6c534a71436b676748667a7439346e773355423951417842394a6732616a7a454961614e6373486848725755475a3462456770436e335173725a6e50454946635361656b4d475232676d666545647a365476725066434a35394a45394554496c526759633953576a675a786b79494938775a564d6e424b53654556724e5174645a5939704f4d6d592f474b35536938654831747a36697a63335350417849596574347a4c52694e5761476d44787361504464594a6c5749344c327153417370654e7761657831346a61645a355a35455753396c6c69616c57594d684c4a3638594b765332467470474a5a6278437371654f544846466753455a53727645457134444d642f724768586f315135626d55724e593735433852684e5679697957416c594c6e764b4f304f3542387841372f6844326647664365666b507048655a384a336c2f77443943664359666c4d443576776e62506a4a6957535154756a68754a7a69647541474a345a536543596767656b6a6333724d4a563765544737737443547a424d5a703853636e32514a366d7277477650744d53496a6d6255315367535a684849572b6b5633316c70505761532b46365a4341466c4e5074424e49494a637855735a73354a6876346655446b435734426a7166326c7067377a46754d4931466247766431457966474b41745149375452556d4f55367947515153564d53474259376a47645a706d72544f507a78504b4c63704d41724d6343444d4a514d6f5a6a784a335231656c4c3763486d634c44754f5246456e6149494b7a4b41517066764e78306a58302f6d3044414d3968346434596c6d6e563248586d4630693172424b7043696f2f456f4d5042346332534f56356c7131555134562b505151695257545163716b6744714c526d616d6b486b395a5631476c7074724a484a6a4b6b72796746373374654b594c413455386478504d5055564f444c5531696c4f46756f6272365246746c6c6d4157504862744172566732534a58684370636b4b496c714d3166764c62516267473352713236332b6c74526554316c7658363679374366385232684f7a38394b3668544b4a49755277384e3055744a57567572336a764d36747435494861624757744a4e6a655059565831574e6c547a445a57416c336a46334a37474c6e775559344d3037452b7a474a334764695353596c535a426e69706747706748765376336a4a436b394a4343424235456d655045795a374c5665425269535a42457a574f56705334655046654961687a615542346a674f4a6d31596734796d4b2b573262544f4559345976534d2b3452366961436b584672515738387a6d45635356523766523267796b346e7379593134633175487a4f5663385253716450424c67454532484b42747459647059727154755a526d576f6b454b534c6c2b4a3443424747584851787675746e6769444a6e384a307764556e386f56783259797a676e725750764b6c31696438396667672f6c416e482f65454550617638792b6a576b6b48745a6836676766534352567a6e6359466d2f474e67456447656b4a4f705952634c71424d2f79324a6c6371615341517a486e417151526b5357727763476352727169356a7a694c6d665361612b424b3664524b68654a5943545a67434d707447453934634962355756356c5262393345794f4f314f56516d494c4b4233657350303165564b4e306c5478476b5053564d324f796332525555637a4974357372763550694f2b33573468566d67596b7578776567486245384e2b6e326e616676453663626c7a774138792b357653452f7047704a50454778646a6254482f414c50634f6b66747170696c4570524c556f4a554e46756b5a672b384234304e4859702f2f5a3245316631397830336b6f666e3870714b7a445631436c4c6c5453512b69777a6372525566537465533162452f4f58394e724b36554164667446502f782b616b2f69424e394346666e46532f5457566a4a6d6a2f384151715966302f32673150517a684d496d417251506459702f4f4b6c6f554a6c65444d7a5872573444566a35792b6254544151454a5a4c5863683338345147556a322b737954572f59522f676c424e4d7371556b4e78422f4b472f38417a72374638327267534d67657930304a5169576c4a49426d5a577a4d48626d59334c37456f6f555763766a39344e61466d4f4f6b477035747a47526f3379544c5472504a31615259474f314f745a4f464d6c4b51657372546943754a696950456453502b52686e5472365366396f712b614450696d6f2f37516630792b6b757071306c567a466e512b495774614e7869374b4142784a314d356c5133573237627752306731706c59776c4c63435054364f3364574a5659594d6e467a4d436570304a674547415a426d42326f6e4d73783466554966314c41787050457930716665474d6e456c4a6f384b6e36526d33704c494d306c4375384a303346736c6a78484e4f755056614f346a6956584568694a376d7258743169385158594873444a6f39365a53757170716c484d7339773551316e35326968347271636e43507973317448554658326c363877796e71436b417155533767586538577443746d7a645932535971344c757771786375726d676b64706f6434455a583672554b785574304d76436d6f6a4f3243544d536e66503643462f723776575746303150704a5557495463347a4c4a484330485672374134334869446270367470326a6d4f4b75705374436b422b383179546270476c667156644369392f784b465652527735375169686f6371456745734f664f4836656f72574144453358376e4a4d346e557a376d38566b546965383359346c434b73704f7347617752464d777a67773671786a75684c376f46456270462b576f4f5a6d712b63344d58616c775a543164674b474c38457870644855496e49334f4644356b485552664b6231496e6a6451634e4e506949524c6e706d536a2f4148656f377956664b7333496a4e6464366b64312f615637686c515a7374683652557853386a71413149346b365253324f32646f3752326a4946626b2f4154706d4559637043564f7764524d615768713874446e75594c32637766474d45564e4b534a6d566e3350393444574948344a6c7a53363056412b7a6d4a367a5a42536d4b5a774234356636786c666f30586a4a2b3062627256735572742f4d6f6e37477a43484651482f4149663678413071676466784b4f5a714d436b47545435464545676e546e654e50545765567053765538342b735555793871716753784e6f78745458592b47655861384467536c4b54357768554e61353959776b5375644d524c3936353451726258587a594d6d4571765a377653437278646a5a41626e48667151446856474939644a6b636d5356697957447939594e7452557777556b44534e6e337064496e793148756e4b72676449577464544547763254467658596f39726b516965376930647243537935424269557869473045397536593166444e5379657730723356353545594a56487038385a6c51796137415151345744336d647833414a637739704d55516b62685a2f474d6a5565486f624463787749366f623243784a545958526c575873796b664d46462b75735531716f5a73484f4a6f766f7971354857573472674a706d556852584c4a7364344f344674583478553853384e4e47485535552f695545737a77596668694a6848754b385152474e2b67314c484e614837513934376d4f70435662784776706448724150615445577a724a564d737161326c2f474e2b6d68776f7a326e5675464a6d652f737163354a547165496a7a7570384b3162755843352b73312f31644f414159594b46514b48536371626e72476c587062554e536b634b4d6e352b6b516231496242354d5531594a55705242446b36686f784c3363327337416a4a376a45765645425141656b587a5558697365737471654a394946344a4f5745357a78475567586a5371484d7076306d726f5a583461656b656f303666306c6d486333746d63536b37423153304261706b71576f6e2f44576f35674f62413335526c6d3274654d7a32442b4a707649414a487232694846746e3671544d4b444a577069774b41564258416872785972777748786e48574b3374417a784f7a395973503243303362385475456b36414a55784f75345157304a316b5072556267474f6349396d3036616e2b385452546b6b736c6773734e3579715943464e71305667424b463270797041475a6974744e6b35394850374a6a4e546c436b72516c544546396542746f3861656c74467162685050366f2b3049667358554a6d705868382f75352b394b4b72464d7758595043645568552b597631694d3547444f782b7971557152544b544e526c58326967656558756739437a786a48584c546577504b3853797450394d592b4d333875656c7459324b7458515642426c646b624d394b776434672f4f71626f524f326b547a796964796e306e5435687969505948704a356b46464f3869464f2b6e4876455351473751657071305a536b582f4144696e6672394f617971637831645437736d4a4d5172444c4444336a763452673261697972672b38652f704e47696b57484a3652504d6e4652636d2f7742596f737863354d767167515948535249674d77737a347074306a737941655a4a61574159752b764b4873716841564f665763446b387775546961304d6c517a446e723577354e52596f43754d6956333079506c687847744e4e536f68514e6e76794d5071565461726738452f615562465a5156496a7843626152374d44496d575467794e584e79334a30694c5832444a685670764f424d746a654a6d5963723930526861725747773752306d356f394d4b786e7646394a654b31664d74577a6259596b646b6b4f2f58375236505441655542504f616b6b326d46416752596c65554c56416b77674a537063426d46696642556449784c554b6778496b736f4e69416573515556756f6b686d48517852744267737673315445444b704964686f527674474e346e345a543554576f4d45632f417a53304f74733877567479444d6e4a3936504d306a4c69626a3949796b5271494a5565624f69542b476e6f493954534d566a35547a317839737a41346e6838677a5571375359685934483376412f61504a6b6f4a36536d3237595151434946503257714656516e497155706c35584b6a6d55516f48335568394f62786f4b796e6e50316c63366b4162647630682b4b794a435a387563745579624e53775437796b497332645141596449744d654e78357a3963514b677847776344392f6c474748536a32717770556f6f315163336675412b5a4c4e71397846634a5275494f4d794c5862594d412f486a694d68504b41784b47757a6c6e2f704256586d6e3253526a393557617062446e426d595849714a6c535a7976325a5151507778716d373275486363594839635762646e365333354e4972326254385a5473786947494763453164506c43697179556a4c7864306b74347846744e566c674c4145546d7270386f37546769626f6f53513178447a3466533634516b544c38786765524b707449746e5363335458796a4f3148684f6f5162717a752f654e533943634e78466b327249336d50505757575a7743524c71306779686463654d4c3332482f6b5977554430673032753578327774316a6c6f2b454c7775626d462b4c2b55584b416f41725047546e3752476f58615942584c7a4c4a423377753167584a4573307274514177646f58485a6b6e69494f4a4a4679334b4a786d5133417a5043327636654a494742677965656b677543335a684357346655464d774463645962575172444d58665747512b73313942564833483030365236767737565a2f7045386a7038706733316638344c74444e6158314d48346b354655626f4579387945795a486e51334d3941717779686935564b393031744572384e4d622b6e507343595634397379307a4446674756797372584e69444a416c4b702f4b427a437850555468776a684949684d73386f4d5144435a66534445417a366f6c7055677058377044486441326f6a6f56666f65734e475a57425471496a6d624e5379586c4c49354b7636786a4e344c566e6455325067655a704c346d34347345585461636f4c4b444552536174716d3274316c746242594d6962436c4863543045656e7248734359466e766d63543272326d4a5758414574495945574c386a764d65664e597462474a3671756f56727766764757487a5a6b69556c64544e43625a684b64793544674b50324556646c59733435692b624f3331694b767261326f56327165316e494937675233555a527145376a6f3238786f4e6353634d635174744e593445337545347968614a6333493361416c6c415a304d4e47346a534b41735a4c446e6e342f77434a55657663764278447039524c6e4579794170444d584c6133596339494332313262326362666a31674a57794463447a45754b564b5a4d79584a4341417339785263693179432b396f42382b38414d43574b327970596e6d4d3661736d7043696c4353526341456771355874427063773667524c6f6a6454446154464173417671416238393357454a7258573359547846767073646f77773270576f32515266586333326a663074317a734f4f5057564c366b55645970326f57424e7476414a36336a433863526631655637675a2b664d3050446754567a4d394f7159793153617131786456566b5755716c75756d614c5a756f42415339322b736375304f4d2f475a4f7672494f36555470624b4934474b68344f44476f3234417a784974724545643578497a4c454a6530516f796343435474356a4f524b434158627975656b61315641714747354d704f35667041617041444f6563556456574662325a5a724a50534272496545674757414469565a2b454669486a316a37445a6853454633414c50794d58744c593164794f4f6d63452f4f5a6d6f554e75474d52336a6c4c6d6b6d784b724d33363452366e58564236506a326d626f724e7433586a764d544f70566733517279663652357a79484236543061576f662b51686c464c5638717649785a7252765178467a4c36696169687367576272473170736841444d532f6c7a4c544675567a4b706b635a30485841795a4f5549366359644b6778466d4649686767525674564d615142387968364f6674475034355a74303250556a2f4d305044567a646e30457a32485636304b736f74774d6564306575757163633865686d74714e4f6a72794a6f4d5254326b704b78725a75687330656b315946394b324472782b5a6b36636d71776f59376c426742796a57555941457a6d4f53544f625972374f714f65704b3569706849566d56336c414c666351506448384c52515854765837724437545262576c786870666957795571657347616f5a557379556c51647443705770614b67304e694d53434930613442646f7a435a7544706c793053365a53554a51536370555759334e7943626b77743942617a5a3343516d72555a3343496c374a7a753137564578414c714a54327138727133674d574f756e47444f686372743341664845594e616e63475853396a35715a71567071585131304c4b6951514c424b67413665734366447979344a4766574750454648474a6f55344d566f416e4c537061584b564a53653654597335345767763841357551564c63664b563231677a6c5668314c6843553672556649517866444b78314a4d533272627349525459524a526349633639346b2b68734973562b483661733543632f486d432b737563594a2b30504558506c4b30794f30307a38552f776a37783548786f5a31492b512f76505165476a4e50316d5571703055305762694b496e724b694c6c615337586751765a7a6153574a776c356d554764392f53466172525765587637544f31537061577242396f63346d33784751433077454d7258724647785273446a763841764d71697a48394d3952415859633453566c6a475a494c4976455978794a425547586f716a5948646f64344a69777572744334697a554f534f38436e7a435362332b734b4754795a595251424b46477a775145594f7347556f7730434f41426a58425a3667447742423859344d794d436f376a39355331614b534a735a733872596e796a3162327661636d656656416e416e6a515369546d58537844314555786e73346151654a7979754743435a5773524a6b536c535947544c4a5359345344444a516867697a4355694445475a6e62476f64614a592b45456e716450703678356a782b334c4a574f334a2b7332764371384b7a6e767845644e4b5570514351535447445657316a6855366d61646a7171354d336c4a54414a43543849486d493939545346514a365479396c684c46683368735759695a795959535979437a54416d45494f565175474a624c4d515a4d4951596b534443705a6867676d4579344b435a636d4a677955644f6d4132786e4e50496652496a793369677a7154386850542b466a2b674a6b3669644664466d7370697171584674424868346a6d306b7855354b7053437051754d757238343061634f7072395a6e367578613346784854764f71374d5975466f3747625a546147782f35456559314e5270596b653665735872744d51526456306831584c794b626a6f6555566d553965305455336d444d6f4333313352787a67434d4b3436547736504553652b4a47594949644a4b77655972647567774931524b657a4a50644250495177656b5a7541484d6e6a4f4c4368706974545a6751514f4b7477686d6a52373777692b7566744b4e6a492b36787664416d6e3246784372724a435a382b584c6c49554155414f564b5364464d666442335236394b5750552f695946723170776f4d645656644b6c4b7972566c504f417375717062447961364c6256334b4f495854565346447571536568697858625734396b694965703139345364514853594e2b566731384e41304746715978684a4e425263675578306d57796b784967457771574959494a6c7346426736354b5661704236694550556a2b384d787975793944493039444c6c6e4d6849436a415536536d6b376b554179624e525a594e7248694579454d354a3169796f696d50595336446754477172776438494a6a63536c645349456d5342426a566a63353658696e647136712b4d2f614d48786c6b69714234673844614170316c567077447a3859653350534d4a4b3475434c4d4e6c4b6867674746494d535a457553596d445056474a6e546c4f31566346564579347357386730655931514c3373666a50566149626146455147636b6e33684377704861577a5a6954566879316830533569787843464e3573304f524c4d5a49346976315335786b52662f41476f71696d6f5747544d34472f6433754e30574e4e6c7a7558744b757231414932487642366e487338307a456e4b5358747836784c6166494f37764e6a53586f61677332654162625331415336674137733336306a4d743062312b364d723664783870573148682b54766f4f443654544c70305441465346416a6738556e72512b357838444b433250576358446d4454615659315359723753447a484c6168377a785649734a63704d547449353754686368624759757843656d57414641356a6f4859414456524c473057714b5664647850796a307978396e7044384971705754744a6173366c57613975567836777730324b3453735a4a2f4572366b4f54682b414f387a47317545544b6c5865424947362b7362476c307261595a4858755a5665354858594f6b364e7374504b3649496d6f556b70514a62683067355179536b6a776a545137716a762f414d544d74585a634e6e7a39597658686931414a6d454b4a4e684e4f623145597070744a77782b357a4e49586f4f55483234692b5468544c4f524f56516538755a2f38416c5835516e79337a676667787a576a626b3950694a424f4f56556d59556471466b66424e4253534f522b384e545633552b396e487867485430326a4f50714a6f4e6e3857375a5066546b574e5575376444764561326e75577863695a757071324750456d4c637047664e48515a5a4c45454a42684b424442426e737778786e41534757496853514554496e75614f7a4f784a70654a475a4278507a314932743578547a675a4d66784e4c68633155305a70687970345063395979723955584f41634344753949306b34784a43757a513167484930443241697635546b5a78784f7a694534696b4b515435627278557355715165386651324c467836797a424b764d6b4a4a6453517865373844486f644c6476584236694f31564f78736a6f59396b7169364a524d4d6c7167344d75426a704558375159716d6e6b4c6d712b464a59635475486e414f323153596461466d774a77366674444b556f715654676b6c79307851756464305934706339542b502f5a7067754f41786c4a787847694a434576764b6c4b2b30534b477a6b6e38526d57376b796d72784b73794d61716145365a554b494464486933765051774555446f4f666c4d784d53536f6c53696f377964596343414d41596e5979636d56396f553647437744445777316e4b6d576f7846745945305a6c705045736459307737614f5a4b4c6f6d45654e6f715861464c42375379387669434f4d4e677a53557674476e70444b79713536526e50344a5554786b53442b6b626b6a48316a6e4264724b36725745534a475a2f6975456a6d7052734957664245486377436445764a6d787173466c464156556a7456674f72765a5538774568586548574c436152614b2b503336664c347971757163747471396c666c6b2f55346e314a58304d674d6c4f556677355430484743717453733864344e314f707539342f6d4534586a744a4e554742556f757956626d6535334e5a373859753161744736695662644461673677446248616a75396b6c696b53387856635864303552344d5030494455616e65504c486353786f744673506d4e317a3069576a787061707374316b686143574f3559595036786c6c327753542f41417a536568647541503841556b617735315a534845785855414b3536776c6a6779565546526e306a696f5569664c4571636a7445376c5842535476536f585365594d5771377a6a42356c46745067376c344d58304f7a6b2b5650544e6b5648615377344b5a716d55452b544b385976615656795375424b6571633441594762796d6d6c727435677870444d7932684b5442514a6167775167776843784241794d5352554e375232665754494b576b6278486353655a3469596b32426670484445676d58705242625a473654676f4d2f48324165396d55624452347a395837753051324f5a302f5937446c317855564b5569536767416a346a7641504c377851723034543577314759377865676b495769544b427a6351587974764f2f77416555534c4e705070445963534f4d596769566c6c46546c742b2b4d787a356a3541344574364b724c62765344304f4970536f4563515044662b7555584b58324f444e4332766568426d79703538626f6d4559624c6e515547657a71314b41354c52784945344b54306e4f7474367064577951534a615334484538544647357978343654523039595163395a7a2b717764514a597643777848555377563944424252714633696434504537423635684b35717967704d486b596973484d54726b4639494d4d4a50496b42534b55575341384547456b6d433156424d4771596372724b74696b396f4f6146513469476559496a796a326e564e684e6735637557696f7135437038315756534a4c6b424358634b576b65397573706b377278527531682f344c6e2b38753036505076766a352f74382f764e74696d49564a644f58494336636853434d756a5736376f7937645663784f345948397071553662546744427a6a764d2b6e47464a555173464c427371526c306469783636394f45563978366a6a35533735494939594855666a506b555833766f62767075674e78552b304977444555307463554c633930674b44645152393473374f505a677467384744544b307a69684c4e38475939304d6f7348365135617476654c5a2b75424e4c67714b564153705578552b614c70374a30792b384576646e494848653268694c4671544959482b666d4b335857594b34412f50382f6d593756585534496c696e546d57626c31472f654a4a4a5967576a7132705959326a37784c56334432742f543452665337543037744d6c716c416b70634b7a6431327a5a566435516439373267664a724f4d4167656f352f6e336a436c6e58494a3944782f5074484578616c7975306f35676d68766773744c432b6157653976335045765136446457632f672f48694b57786432323462666e302b2f53513263783161335250516866654b53644344755a68793561776465735a573274794a4770304b466479635254746a696332686159387855685249436b5a694548636c5a3361324f2b4c394c4733335a6a32714b2b737a69506157503841556d65735038743472656b766c2b307366504d506e4862486b626c684d7232694656686e3854486257395a47524e44732f6a676e712f455552774477536963526964477732616c4b51414142792b384f553469574759787a43447a467a334e485a6e54386a3074417673786c5353385a62334c76354d4d546f6d41592f4e52536f703664444b4468536d304a3339626b7852757349626b38666d4f42414559796c6f705a526d7a5338772f4572556e63497074596247326f4a79716247784d665659714a363146652f516a64776979744a72484532716746554b4a39496e4b53514354784233486e453751656b65446a724e6c543751454451734e3573504d7873687354424b5a4d45727474564a736c767248467a6a69534b68336964574e7a5a686463776e6c7046473172637937556c6549544a716e393554776e7a6d4573436c5a5971584c4e7a4243776d54355945446d346568576a743168675977646f6779734942304b684862794a336c715a576e4241787558356942336d463559693666686842736f5135582b45553966786e706f465a676d366c6b734141547279347730474a496d70326232566c7936684b71755a4a42546353737757724e78574233513355394c5171793156344c527155574d4e77517a5434786a534f386c4532616761686747567a7a4175664f4d367935474232457a516f307263466c422f6e70695a617272366b676c4d34725164516f6c78753577674d704f487a6d58664b56656969445371372f4b6d6b763841434673354c66417654646f5934313862303664386633456b4d7566517748457045354c726b714f5a4c456a5377632b62665348554e573373754f494e753844324f733878755156396a4f6c6838364170615532556b74336d44614f2b7641772b6f415a542f5572324d77352b50316c6d783245543632596c4d6c48635151537451377165664e566a614848547378774f7067507136366b7933302b4d33633759305347544c6d414d4e4a67317537706177473755785876303771326378644775563135583764706e3671686d53796336776f66437049756e6d3778556177715a6f4c35646734474973785043564b55564b64453149643036545563526233682b7556784c6736386465346c625a74627278362f324d46774b6f6e425a564a4b7379526d655859736b356935444f4c427766574f4f35434376426a6a735a534877524f6d34506941576c5a6d79684d5746454665554a556f416d2f646137446476486e7875564749735850306d625a53526a79327750544f51507647395668464a57535a744f46652b686c494f35785a535834454f446534693752565135335574673963544f756139422f564752307a4f4a6254657a716252725a597a494a4f56593049484835547969327a737657495646595a455479384b41335247366473454b6b305145546d52695063495355714452325a324a306e4363564a53424267785245324748533146494b763049596f696d5050455043594c4547634477524d7673773545654e314a66664844306c3166745a5355716371475576676e6a7a4f364830364f3633746a346d4d465a3738546e654f62527a616c655a52594433556a5166316a61302b6a536b636466574e556865424b4b4f724c77566c6374565752784d72464d6b416b485639344556367167473352756f73396e614f386d6d6f576456456b63597353706a456d435470347757494a50704436615554416c595161484d514e497250564c4b576d444b55726843396b507a444c354d35514f6b47466e655a474b4a346137694a43475435676b6b4c536f734864394769664c4d487a524c4a3949426f485639503677785578415a387868686d48496c704b3569776c53334364424d4961345476414f387446545574327a6744724c656b55353342636e3439424539616f4139326d374e44754372336964484a75664e6f6f74744a396c7072717a48336a6b78577163414130776758594b4c70424a33754c4470423753547949575a6651315953695a4c6d5757556b4a42314a494c4d643463445343387345376830697248786753365256796c68416d6a4e4c6d4a414437693178785a77343450416247526956366943335053557a5171536f53314b7a6732537368335151564943755959705068422b793374442b487649424c44426a725a7a4170696c6d59556c4d6b674f70497a45704e776c494633636e6b48676c7335474d343734475a5876734179764737747a6962584674725a644a544a7930363561416c6768676c53526f4c6161747a7647737570557141415238356a666f6d4c6b737762346a6b546c3156747a557a46715553565358756b6764332f63425933316975364d7777544e4b7356706a4168464c69535641462b367263626a4e75483635786c32306b54515667597a536f54556d586d4c2b386857384b48446b6548555168474b4e6d4577794d7972596d6b437069313730727972535178436e63462b4266526f745757465748666a6a36784e784331346d7737583864636f67676c41576b365a726b48546547486e43374d6c7478366d56517639494f4f7878386f52573034556830736d616b756c647751526655584839596c656d5163483169716e4b74673871656f6c4b6366584e6c396e5579307a45685756627076626942626d3758447863585732737548357759542b48564b32366f34794f50542b6674465536697046724e504e6c496b465a65564f6c4268794253374b426264425536333269746f343745524e75684a7238796f3578314269784f786337506b414375436b33536f626944476d6f44644a6c4d3233673854513462374f31327a724365517559614b346b322b6b312b45374e795a44454171567856663030686755434c4c6b78304242515a394854702b4b30566b77426e4a48427a435455684f636379796c72724b317a6e34784958454a72675a5546775749726643715a34573445656a6b52315446546148786842555267636e6d4779556b6d38454267514353547a4435457143784233546f6d4362433970496c7a545043444d47624c6b646b6e532b596275572b4557576f6e424d344f6577684533594e5962386156666a6d48324d4a7331465334444d426e70474b355061564c32416e422b2f4b2f6d5639307842485055527133723647566a59656550395038416e456343423348336b6d31666a3970665237447a6c48764b51676463782f37667a68794d70377854576754535964735a5479727257705a5044756a30763677372b6b4f706944613536434d7064444a6c2f7743484a5147336d35387935684c61797465692f744f7737645445566673354d6d4b557356435a616c4736676b6c5852334259437744694d38316879574c64655a724a726c52516f516b443752542f415058556f3358564c562f4367442f7955714f3231714f735966464c443051666557443265306242356b3873543853413456754979366342416559677a414f76765059666e2f4d6f32683255705a4e484d584b5373716b704d774653796f355533554c323063395245376b734f46344a6b30367934324466302b55354e55545a6b7770544c6452766b516b4653754c4a43626b6a587a693356574165527a4c39746d426d62716a3251715a346c476372734571434f3475387979677179625a6443325937744c6d454c56355a77652b63524c36746345707a69644a6b55307041434558435133766b426d4679415146576257474b7461384c2b387a57737359376d372f442b2f615a5847396b544f576741796b49564d4369796c4f6f482f4541645076464f3532684b3037587a6e67792b4e61436842424a48772b33667048796632656c51696e6b6f796f415551456839375a696f33556f3854444e54714547462b63703155574f43352b455631324430565548556b79466c752b684b5535332b634d515334393433357770645658594d4e6b48735934432b6e336661487036664b536b624e306c4f4237387851596a504d79754831634d4234784667715833682f626a2b66775131314e396e5467664c4d2b45366a6b7a43744d6f6771624f51564132334b424c4f4e4e335742653754676863486a2b667a39345971314e7138742f50352f714f70644c4c6e424d3655633655754141474b6448436f6531433272356c58492f6235796d6258704a7173344a2b782b557071694679695a5a754c6a6934346a72614b2b466443565038455a586c4c4148694356566c534255414f46484b744944393177435331387966744330596737766f5a6f46414435587079442f4f786c474c303361533555784448737067594f774b62576663374a45466b5a794f524f527472737037694d396b646f524c5169576f4f704c7049476e634a436c4136424e724f30584b626d7059454449506155645870526557594847492b6e3763556f664b6f714131554163723966434e427464574f6e4d7a55384f74505869527064756164616d63645162667a4b4153656a774931365a775a4c65484f426b52355359744b6d614b59757a4b4755767744325068466c4c30666f5a556654324a31454f6830545079584e773658727165416972754d756852316932625367754d734d45556573724645447936686f374a6b38517552537041756f45384243327a44552b735a53554533444e4542524a5a7a3268386952427865597a70704d526963434a3253556a73356375587551684b664a494565593174704e706c75705269654c6d417334647670465631573342635a78783949304c6a70505a7338714c6e30697939724f3234794672436a456a326c69327341574f446a7243323838796f4b50452b635678554f704a2b35683448704c5a4d776746524a5a764d7779732b5570624a78366459444b4351423167697352494e303236776a39572f55722b593861634563474579352b594f4446704c6479354553796254677a334e45356e596e6d61494a6e596e32514c436b485253534350335347503368756d55732f456875426d4b4b4368707141424568425358757255713433314f377a69384c516c75346b6b6a37665152345637313972475035316d53785046706935685543317371514347435162414563412f6e464e334c746b2f7754555370555848387a33684f4759724d566e51396a6c6333664c38586e456269507241737258672b6b6e55317037513546585554657a70434c4675704a6638416845432f7577366c41544c442f774268744a55685379445a6a763841336b685832685a786e4d437844736879357164624f4f49666e70484842475a5751484f4a557647637270576c505a6b6c52336b48666c443733423844446b754a51716658502b766e2f614e4f6c79515165656e2b2f6c4273546f733651704f68736e77305479734c644955526e44434d7174326b71305634586930366d5557732b6a466e3638644e447a68744e72566e63686a72364b37317730335748566b7570526d5351685a446b6161333650303552655570666b6737576d4a64572b6e594138724d646a6c582b7a465669676857596a514b4768505063376352464c796e4462655a7171513668733547507446394e744f4a6b6c67514f3045334b445a676e34796548434a61703679516563592f4d4b74466368684d35692b3142566b6c766d546c7972494a53546346695271486373646252646f6f634b536670386f75396b445948722b59485634756b69796c6742694f38647734456b656b474b6d3659697477484f594e54567972744e4367644d316a7a42744850554f36342b55355850726d4e734e787a737932513558636c4263503038374e436a56337a39354a6164446f4e70795a61536d7041445737366550425163654d474c7246474e30724e70717963375a792b5a6835485565636135416d494330456e4a55377350446a78694e764749652f6e4d47576c522b6b466a45444f5a4e464d6f6c3344384769435951475a70634332517170313053535238792b346e774a3138486a67435a4249453375452b7a674a593145306677797750384179562b55466a48574c335a36545430574630636a334a535372356a336a356e5477697662724b7178795978615861575661375a74586a797574645549636367793955446e624636716b6344465664537037475768555a5561314f392f4b4769394959706157304b4f326d3931544a534c7472724637533648395659487a674166574263664972396f636d523267786154494b554142537a636b37682b5a6978722f4b51624b317965356b364c53586167467a774a524c78644d305766532f49786c5732466a676a7447746f3271504d487170536c4545584461516c367933496a4b3356426777716c7a49517a4f583436636f59753946774245324658624d73457959624141657347504e4a774d514d566945696a6d675a70684355384c416e6b4f486a46746442714d6272446766544d5435315a4f31426b7751315454436d316b6732765a513477612b7763667a6d5038724e59507166326d5978797455745a414e6b4335487a462f7342435332546d6156465952506e4d6e5772556c6d47717236626f73566854445a754f49307777356273345a2f4851422b642f4b457679594c446a457045395341684b4d716c4b4a7a462f6d55705a4437774371434933354c644f304d41642b3050713171457a697275324739677a6a7272435641784a474e676851714c2b462b757068654d784a47444b5670566c4f59653451397778537068647478454f586a7047466c4d746c59366955424c6e4a505a5441436c61626c4c4d78626577793661615869797457436364347471545a375348326832395a665755535a694f31437757446b707543666d486950446c436857527a495730716470456c7331564c4757586f7a6b7671344973664e3463696e5045445542534d7a555673784b6b392b5769624b5541466f574d795663387073447750534848557656375935486365737a52534779756348735a7a4432673745396c2f654b4759727369416c55712b5a4354627571636b6f6578427548336732304b3771483448666d4b5a745150655077346d41714b4661436c5075755069494174727231687759486b7a6c4439466c3071574347576f66374c2f5733724332344f566c6c417a444451365268424b4654456865564e79556a51633236516b326b6e4573667077713542676b75644c4e6b7a5354726f3539494d7134354b79754c5562674e4e5652346b674953436957736744764b5463395842696b775947577775526d615370777657332f504d7875625a356a6549746e59512b36494149367957495053436e4351502b496e45484d336e733732566b6d575a367746714369454a4e776e4b3132336e3651515876495a75303366704547434972784357556c387a694d6e5746314f4d7939527449674a56474e61435a6457456c2b7a486a395952716c506c71497454375a692b5a4d44654d564563464f6e655731553569544773544435456b416d784f352b4478615653787a4e4c53615934334e4c4b57705253532b3155543279683351545a43534139743536786f426d6f543250665034482b59466c5436757a793139776466695a695a364a382b59706141755a6d4c6c516367486d72516563476f415832767a50516f3946434247774d667a70487443465577433571772b6d525065642b4a30454961744d356d6664743152325644366e6a3851796474453643704357626a76302f4d52474142377372703461412b484d6655553774474b545a675364774268566146327750715a6a324b554f444b613361507331646c4c377438705741436f6b5a5363704e6872774e6f303074576f624b2b50553935796150654e37382f44744a34706a786d533356335567714c42793442536b453853517146366a5676637654762f50784761665272552f48582f5a342b6f6947707849695956454d364d6a486b464b536544325635434b684a494d764c537530445066502b5974714b674a6c7a4c756534427a4c5a6a3954355279563578474d6551666e456f534a67796b68774154774469317a313949734546446b5163677769724a4f564575374135315733574830506c41674b764c665351435952686b6f4b576b4779685a4949767663676548704157644f494c4e74454d726c666942797842532b36795135303551757351776636592f6e655a4f5a696b784c6d3479716347374f706a72705a6a626738615170516e694331704752473244596b366a4c557275464f5168375a5667704b692f41724236474650574677594a4a5938545430564f69624b524c55674b4b565a56416c6944765a397a35755265466334774f782f6541574b73546e47522f503532784a5944534b6b71374654464d7a33663467435152725973503049685353342b4d693677574c7537695253735335705541536f584936574c65485856584b476273486952677375444e54545445716c71533767452b542f63454747345571524b4c426c634e4b364e4b5653564a4a423935423374773867336c41616467712f4b4671464a6670316e4239744b705972465a305a6371556849335a57647878424a4a6a62433731426c4662504c5977444261635470687a717953774f385272794135777535685542786b6d4e705a72574a48515462594c55716b68557555727554686b7a473746514c466a614b4b7675596a6f5a654a365a6d507844435a74425079544e434355714769687936634930582f414b717a4c72587958783250662b656b7047497772795a632f5543666f576252386f767a416936646838644f675536675044396661496b792f434b6d5a546e756e756e564a466a7a35486e48596e5a6d6e704e6f6b4b44724252785076422f7248546f306c7a305442384b782b365839494236316359595a6b7178586b47512f5945483353334c3958696d33683966565938616c75386f7235524357416530596669326d7577504c58492b45746165785365544d6e6946566c4259687839597771716954677a636f514d77457a2b497a4f7a56633935724a35384662772f446f386274645a513450576139432b59764854312f7838704f564c6b6d576d644d535a79314a436a32696955673777454267774c324c776f3350764b6a6a48667559746a61584e536e616f4f4267632f552f346746587451584f593930426b67614138414e77677870532f4a355078686a5256316a6a7233694f75784359706666462f68532b6a372b63573071554467786f49555957584a6e646b6a764b75713351517372356a65794f6b59624f35375466374d4a7979457353584a555835364436576975624d636647656631584e684a6d65727071555435695650717056694c335a787a596d486f4d726e3478764f41523643447936307151744f623448416477464261566a77492f566f6b44623168455a5038415053417a367674436b38546d38416b672b716a4542646f4d49475734334e73707463344461614954666947654f70474d45775365414942517164595534304a4e74343771452f5177327a335349413678684f5579637774376e5573472b742f474b2f5673474d37536b315034794573374b377232766d4a752b3644436b726b5154674b5a4b70716330326156456e766d353441456630675175464749772b364150535a6245316a4b5642785a4a6270766a5370424a775a54314462566b714f714b53706a63793954784a75504b4f65734544357a6b66422b3033744258676d596c374b6c69632b684864426365495a75555a35544862726b66582f63657938672b682f6e34682b4e7a796e396c6d356e41576b6c513062522b4f3736774849475231784631444c737550555254695655557a317143743968754f59662b7252774f3770367977696a6141593977544577512f3769334a462b5164754a62776764355675596d326e503345585473613746436c4c573270562f466c4150714868534b37766849793449426b39422b30357674444a6e5430496d716c717a6439675178374d725570494f2b325974794d656e70396b626653656431574762634f38535579316f475336584c33424548596f4a7a413039684337666a47644e6935523252336f6d4a552f494f546145436e326977394a613834594150724e627431693069736f35595433703656416f4351354c6b41674455324f6e4b4371593738514e516746524f665445797450734c6953306853614f597844683871543470555152347862346d6275616670535a4a676f7143544b626c48546f4f756d45644f78424b684e6a393436644d2f696d49496c67356a34434f6b695a4f753273556d386f6c42473845672b63524f6b734d39726c5a4b4c545a614a365033753474754f59573949366469627242506174683839684d57716e58776e44752f7a697a645769436f4d6b4d524e58324d696f546d485a7a556e525353465736694557615a483934523165706450644f4a6d63573244537335704d355573693453526d534730796b4d552b7356546f46427973394270662f7741695a427474514e3865682b76592f695a35654256744f6c534653653152636855733532346a4c37337046432f77397932345456723855306d6f594864745078342f386d5372457938784338795344644a44456563536f735559784e42734f4f4344395a453136456b6b42314865626c3434314f2f76474b4a5242795a585541457973796e57705369623253414f366e71536653476a4151375a536538766142304839357671544730532b4a53575a747833672f7264474d4b7a756d666353387a7531556f7a5a6e61797931764e3262306935706241755662316c69733551447549686c564b677358495065353667737a3673597473674b7a6c4a7a6945537741704a34356742344a2f4c30685a795152386f51395a50457167715570523372575165706258665a6f3674522b30682b426944534a6f424235663139435838494e6c4a34677247695a6a6f494465387a366c676b426f724559626d4e7a67543667514d366c4d2b573435474f636b4c694c4179514a5456715a793475547277495642566a4f42477631694f706c7544774c412b746f764932434a5373475a475569344c66753336452b635354326e416435724d4b41505a7a486343564e5431417a416548346b5a316a455a487845755a33446a34536464574b376b6b2b376d64333063756f65462f4f4256665a4c534342357559706e31766154465a58756576384178443171324b4d77664e796343465473546e4a6c396e54793154466e5652736c49666a385236634e594b72513732335077507a2f414f536e714e65454f4547542b422f6d4c4b6643617559734b6e4a7a4d5841633551654f586634786f725374597858784d31745139682f71662b54553165453141555572377a424e30364546494e764f47674552444d47356969753244724b7075796c39464b736b654a2b304d584d577841377833672f73565a6c566338725079536d536e6f566d353847686769576664314d364a675779744e53702f436b6f514e35417566346c717566574f416b4d354d4e5869314d6b735a3874787a4a395242514a664d676f4d48576d4f6e5165596d4f6b3569764643516b324a69444f45355a74436c31367162655475384f4d41637867784d335070774648556a6343475056512b3336455a4d4c41674e52715949514442467948695a45736f4b716f70315a3545325a4b5037696948366757506a48627047777a61344e3758363655776e6f6c314352765063582f41444a742f77427354775a48496d2b77583275304535684e4b3664522f7742514f6e2b644c734f7252425753444e53715653567141576b3143446f526c583548555144566739592b76554f6e7548457a654a2b796d696d45716b545a6b6858423836664a562f57467453434f4a6172385373552b324d78446966737371416c6b4c4369506954793335547669754e4f366e4a35457474346b726a4134694f5a73625849566563417a393070494639584436326754556d4d62596f366c69323764394f302b71734d726b6f594a6c7135755166704342704b39325a592f576265514f666e4d374f704b74426463676d373930694c586b4c6a414d44396532637350744b716d7555426443303557624d6b2b5033686136666d4e2f584c6a5053567a3856536f616a552b4474484c70797068747245595a7a4b3648454567335675382b55466253534f6b3672564a367833683963674a424b724f2f7743754d5562616d4a3445732b63753372445a7457794f375945754f4b7951772f326945716d54672f366a45626e4d6f7161686d41765a39334a6a34757144524d3879532f65414c5675356447697742456d5568484f3238656672425a6767522f68552f4b6b6c6c61466b674f584f72634e4236525565707247327247506374535a614453384a7135366e55524c54775348504e796453654d6156656d725165737962646263374535784e5868477a794a597548504f476557427a69494e7a48676d61576a777371393146765344416979776a6d6b774d66455142792f4d7759574b5a34336b3055704c4d6a4d775a7a66314e6f5a67524a596d4a386532326f61527850716b42512f79356634692f4a4c744259677a6e6d4d2b326f6b6c4e4853742f314b6775656f516b2f654f3654687a4d30766132727148565554314c652b58335541614d4570747831666445536353614d6255327039493664784f2f7167344548504f2f7048547055754f6b356756614570517053374a5343536457413130764854706d4b7242517638525351506c5459355164353471365748505741497a4442784d7069327a313752474d5364325a6d352b466c42494c4e48455467596d72557357455342494a67356c474a7a496b4679596a4d6e47594e4d6b694a42676b536448506d79565a3555786374584643696b2b6b546d6469624c422f617869456c684d55696f535039514d7075536b7436677833426b63696276412f624e534c59546b545a4372663952442b462f534f784f7a4f68306d49793536416f4e4d5352597352364b4543523677676364494e4e77795772514e302f4932674e676a425959746e37503841796b487261493277785a46565a67796446494870413759572b4b4b725a61517257576e794564695475697964734a546e2f4c41365233507249396e754949763266536477626f5945377657474e6f35784b5637427148757a566a2f632f7742594171653445634c694f6850336c633359757050757a683155484a505547494651394977367438597a49793967616f367a6b6a6f5036775777656b5764552f72484f463742396d58584e4b2b566750534f4e495057514e5777377a5430654270466b7045534b38644974726433574d354f447471664b4742596f7647644a686148736c2b734546674d38686a75503031436a5055544d67305a4b464b4c384c4344327852616334787632327044706f36567a646c7a7a6271454a314869494c4167354d77474f626334685676327453734a5077532f7730394754636a71544853496752496a704d4e6c7932694d77735179584d41386450312b74596a4d3745733751382f4f4a6b542f32513d3d, 'ingrediente', 0, '2017-06-01 16:59:18', '2017-06-01 16:59:18', NULL, NULL, 5, 4, NULL, NULL, NULL, NULL, NULL, 2, 2, 2, 0, NULL, NULL, NULL, '111.00', 0);
INSERT INTO `producto` (`id`, `nombre`, `descripcion`, `precioVenta`, `idTipoProducto`, `idSubTipoProducto`, `imagen`, `tipoproducto`, `eliminado`, `created_at`, `updated_at`, `codigoInterno`, `codigoDeBarra`, `idOrigen`, `idMarca`, `material`, `usado`, `color`, `tamano`, `peso`, `unidadesCaja`, `stockMin`, `stockMax`, `ventadirecta`, `modelo`, `estilo`, `corte`, `precioVentaCredito`, `conStock`) VALUES
(382, 'productocomida1', 'dddd', '120.00', 8, 0, 0x646174613a696d6167652f6a7065673b6261736536342c2f396a2f34414151536b5a4a5267414241514141415141424141442f3277434541416b4742784d544568555345784d5646525558465263594678675846786358474267574678635846685556467859594853676747426f6c48525556495445684a536b724c6934754678387a4f444d744e7967744c69734243676f4b4467304f477841514779306c494359744c5330744c5330744c5330744c5330744c5330744c5330744c5330744c5330744c5330744c5330744c5330744c5330744c5330744c5330744c5330744c5330744c662f4141424549414c634246414d4245514143455145444551482f78414163414141444151454241514542414141414141414141414145425159444167634241416a2f784141384541414241774d44416755434177594642414d424141414241674d524141516842524978426b4554496c466863544b425170476842794e6973634868464256533066416b636f4c784d304f5346762f4541426f4241414d424151454241414141414141414141414141414944424145414251622f784141304551414341674545414151444277554141674d4141414142416741444551515349544554496b46524647474242544a786b61477838435042306548784d3049564a464c2f3267414d41774541416845444551412f414c62616b4441724444453462555432724f544379425071392f624664744d7a634a326c61674d6d74327a4e387957374e5a4d455458374a556f664e5357444c5368446751427a523557536150773472664f6271335133455650714746533851577578334f6d37694269764766556c7a694a4f7150704e6b334a4e48585770504d4961686a4d56754c5759514a717976544d35386f6a6b7350724d64547331464a6b5269715470794f34336544314a514a554351445358724d51344f5a30303270556d6b575a67506b7a375961635375545457765a5577734c5a784b7074714246546645326e73774e733557794b44646e7563562b63345a42516f4b4859303147326b4d494256766558576c61776c7849484237673137745636324c784f5835773075436e5a6a496d31713768436f394b45744756727a4a6253704b69545362476e6f31714d536f30356f41376a57494d636d49766339546e556230724f306355693633636343495250574242464978475456746974784d4a6872544d5673456d6270525734676b7a5a446445424d684c62564742417a4e2f446a4e5a746e5a6b6a31687177625163356f6d47424e555a4d5164503362425353374d6b34394455796c4239365769736b63523637713971306d636365302f6c54316573644364344c47526d6f64577155736c4a4954326f747a474e464b67637a30524e78694b39444d386e452b6550516b7a5a716879756e544e3179734a6d695a46654a6f4443416e656b7365497554774b556f3350436334457a3673576c704141674b556f41667a4e4e757771385359735a4b3343385a7a586d4f4d3977434d776133667a586d585662546d4b326e4d4d446b30796b5a4d614d4350374248686f33424955666d766670477865493453643667315a77412b574438554c735359594f4a49324472716a394a4a4a394b7749544d4c69556c746f4677555345354e45644b5436545045574e6446365966456c7a48734b55326a42453378633843664c7a393276596138652b767733784d356d586955695a6b7a353436653955314148696357346e357038704f354a7178613272387978586941797330563850493538773545352f4b724b3764346a6c496a4b32306b4c426b545643637a53666149726d7943466b41556c2b35516c6878464770616f5565575946495a6a30495a35354d787339575365394c3247595349335975306e7658596d5a68725436665775784d4d4b51735673417a6475746d51706f5551676d464e706f344d36662b6d695551535a453951645071667a744a416f6a555768492b336d5348557a447254496262615635653847684f6e4d7372314b6951373136392b4a4b76754458436f4348385442565868394452625a6e6a7a2b67454d514b7078494a795257547030465630365a7156516d45424d3171455a6f59516a72526b684b4e7769542f414370594f336b54534d7a7a2f724a39313238415639434d44307a6b782b6e35565066617a504a54775a7864306b7a494675696b324c6d615a3030705469676c4e4b70724f37453556784b6d31537043516b6b2f657659556b44456669457173304c2b6f41305968596d6c767037615449514b4d4d5231424b417833617638416142545046614473454f57373544516c6a4d78504d4e555554634b727a3771777a5a4d6f52654a73477846535070784438494759754d7a5364685577447035676873707170627742677956744d51595a706a68447950384175464c5277624269646767543045616b6c744954426b2b6b563759634b495179596e76485a4d6e765357356a306b6231434a6d707334614e394a4d4a61494f4352564159474b4968624e34346e764e626854427959657a724467727644457a635a563650656c5945314f347759594f59395a58517a4966626d6945457739704e4841686256704f5656516963637a4d516b494552464f7a43416737317168584b51667457627032324b722f514c59676c5461514979594662766e624a3431723271574b5831706259337053593343494a484d54536a63767448727058497a6d656e4f7170684d6e67536a51356d346e77717270776d5a4e444367656f4b4f49456e306f4c47774961444d6f39494f3173596a48656c5a7a365173524e3157796e77314c6a336d7373484553363853627774415550536b347a4552614746754c4345416b6b2f6c5369684a774a73394d365836555332674b55504d6561747170434347424d2b6f4c545973525248754749497a785243484e3030516e51793372594a686c7959516134776657656376736b7571563731417a354d71526343457474474b5554484366517961474e426e5867554a544d4571444e3950732f3371442f4142555656586e426b397451775a53616b33394e656e5a36535a427a466c3654536d4d6142456c335a54537473504d57506161665374475a6b797474494b31705141636d744c6b436371626a6957467030516e3851584d543831696d356837526a563067397a3632686870577753434f5a3756356475735a4832764c4530536c64776a5a545932626b4c416e68536b6e6254686259526c635950726a694b6570454f47485074507475754933334343663455482b745556502f774470676677456e6451667572447a714b32787632423141456b6f506d414863704e556933484f33492b582b496e77686e4763544b7936355957726151552b353472713964533577444750704c554753492b73373970345332744b76673155727177797069437058677a743078577a6f6e312b7758634d4c62536f704a474978514f7534596768384765537639484f4252436d6a503841447766656b6975566a557a30427842716b794b444b624d316b324832566b305435316766656d4252367743786a6c2f51477969554766316d736e416e336b6e65756f626332524b6832484e523257444f4a5367347a4774766271574156434232465950637a63776658394e5534307049394b78775745776b596b7630486f4b6c376b4f543556464d66464656586b63795543656a36543079307a6b4154546c514c437848433168496f69514951456c65703341714937476c46675443784537526f78436d79445769644462635673457a653956354b462b70793978437859376954556131356c4262453346684272696d4a6f65637273714572444454685670516b51673030735749576e356f712f76514c443559377632354171312b704b766356504d357055624d54615457546f537a6f5a4f536d4d534a7750372f4147716536786c474548315055645571486c7a39505744337a7a4675704f38464b6c534157776366686b7a49417046657079663676483779784e495742616f5a2f474d334e664c4c6158454b4c365349415643434436545454725572554f4357583976387a452b7a7a61536a6556767a6d4855476e74334b5733314b46764943694442335a796b45444a50464f625a714b6c64754d2f5742536261624772547a664f4f4c566c702b334c4d6b51493769434469423270716f6a3147755233563256507550724966573949764c64344a445a577754683047516e313344745562615a712b756f314c3062373363707449645a5a5146714a5650346a362f776a306f6752574e324d2f7a30696d334f6354742f544c4335334a3270436c5a4a523556664e43746d6b7659676a422f4978762f414e6d6f412b6e36535476756d6e375a616c5737684b5152747a436a392b445531315a70664e5a7839522f444c4b3755745162787a48756939527649455863653347372f414d6f71716a584e31624a4c394d702f3855734e4f7647336b376d314251377765506b5636646469754d71637a7a33725a4468684e6c6c4d38436d3469795a49616554634a4a623541705743773870683767447a45723977705249506c435a33664935724153427a432b3865496f6631654d492f4d3936686656456e797978644f4d63786a3031727a78584553505563553754366c6963474a7570554469574e76594e764c443230626f673154596f5937704f6849346a707531464346685a6d685954476133457a4d6e304b51792f694d38316e526d59356a52335530396a574d2b4951454557385630676b744741415144563754794530595845776e4d524e476d43644e30716f70304d74334b3051545074322f4f4b462b524e555453796272465845497444464e436c324363706d536b69707a477764324b416d454a773052494e61766378756f6174326171354d5230594f704d306f357a47436645747951423372753576552f6453617434545161624d716a2f38415078373146713769713752332b332b3536583266704259336950312b2f774471526a32714c556b62314579543957636950586a6d764e427449374a2f5765797931566e67596a617743464e797478414b456c51516f7a6a423368735a372f465730365a47544c6e7230505036547a374e5136763542776655663567756f583639344a4a556b2f536b44636b62675a4943652f78484559717266362b2f3145797647306763483139442f507868392f66764d73693451704b6d2f77445678745041437766362b7446613167554f6e495036666a45566970334b507766332f435a3658317553734e5046726171424b465a42564d5241493548663169695237462b38526a3566366757366570786c41636a336a713974724f464b6464575441494a50396f39714a78567a754a6b4839554841456c4e53312b78747a4c6269314b374151506a7a5a7155303139726b787765316868704a33485864795842744f4a342f76536a6f77664d7a4850704c394d55504257554e3172694c746e61366b6f636a4469655166512b6f6f5875423874677952305a6a3666776d33566e6a326a2f396d6d6b4c527575464568494731497a35385a4a6e6b442b5939717630476e4f66464d383357586876494a5776334370723170353034304f3353786237455a55636b3179316852784d4a6b4a314576776d6e5366784b4f6664527150556e465a4572303438776b576d3633454a39363835554a6c3750675431586f2b78516c75594578587056566852504f73624a6a6652485148462b6d61643652555a3357716f514f614573424e784a54574f72694a43424a704c572b304d4a454e674837687a656f6b447446597553636d59524c4b7730776a4a4e48737a4f427847374e734252684d54737a4c553044596130695a4968536f4a48765141773538572f46626d454a2b7437676b774b30476352484e7261546b30595741544754624556754d544f3532384d5574786b54524644376842715571593447594e705574554368465a59346e4d34555131656c71465038444169664679597930725444744a5565394f715869597835692f5546424a49465a596f4d35574d584f583278436e4445416436677638745a4d746f5132574252466c6d2b70354b5671504a564873416f524d313535444256352f6d5a37696855334b76702f6953717251687877756b715a624b6c4b4534674441476553596f3673416344502b5a6c6d3678786a6a4d7a76645a61556f424b564167375a45655a424133452b2f5036436e4256786b6a6d454b3742786e6a2b2f70486d6e58545951564a7750712b50556a506f4b7a61564f612b766239354659485a734e33312b4d3362316c7368647570776547344343434a4253636e4567794f634761725668316e75545755754d4f42794a4f72304b44765166454b4d3547465236474a347a6e3271646b592b5547553161705163486a4d36764f6f43704251435647504d68512b6b384861656577726d596a48392f3751726167567a3765337438354e445158486c53303234736b3443556b6e2b5655492b56346e6d4e67486b796f3033396d56386f4a334a533266566168492b795a726a52597836684c716b5756326d2f73306353527666544863424a4f666b304a2b7a53334a4d4536383941533036666a77664437744b4b6674795035316470694e753332346b646779632b387a7531674b716b6d4b69327875534b3047446949757562634b74466763796d50736f5569355179344d62557855356e6e2b6e6161516f486b314d5641366c4f374d39473068316157343478464f514845517835684165556b776e763841797248506f4a77454631527855514a4a704c6b6a714d55526459364f7078516d685243544e646836543048534e4a53326b5971774c675245592b474257346e544e783043756e524272576f3449547a53324d4e524a555771795354514b7068543675304e6274684348325451487a52724f4d61326f564e4d454178797969525252632f4c747a5332454d47444f326f35696877424d4f594f30516b7a46646b51546e316a47304a576561495a4d774b4963474341664e5267436445446c6d436f6b6d5a704a484d5942424e53736b464f306f4367654165506b31357571645735417a2b30743037736e33546a39344263587a467355742b456c795a7959473341776c4d52324835564d2b6f5373444b67793754303233416b4f522f6551765647746c61584745705332327451564342395245515665697045597869736f735a786b634431414539477567567347624a50755a4c704d43597a3630386e306a47684f6e5774772b536c7665546a435a494139774b334f4f686d5450596963744b2b78364575794a5757305a7a752b6f51415241536b676a507254567063386e695132666146594f46475933307a70526151417438375a67375151536d594d456e456a356f317050715a4339344a79424c4870337053795a456f59515663376c2b64552f4b706a3756556c4b64346b396c396a396d556f414177414237552b4a69356a5555465245393649596777743937796b7047355559487165777243434f7075594870646b57327a75494b3145715752787550596577346f616b3244356e6b7a57624d45756d4a56544442455273313037452b336d6d4b66624b5267534a2f4f67595a45334f49497a6f7a62583853717749424f334578677a596b354f42324659544e454c613030714e42747a4e7a435636556e754b33594a3259545a6165415a6969416d513553696d696d514a36387a58476141597131432b4a774b457777735546424a6d687844366e33776a58596e5a6e614c516d75784f7a4432726443526b31336949765a6d3747505168435667416e2f41456a4e614c56494a48704d3248504d576e724a744a327051667669764e663759724138716d574c396d75526b6d59336657537a787353507a70412b32433351784844374f55444a4d3464765833454262617477505943756533565067316b63776854537077776e36777433743075723550464f5262382b5a703169616344796962765033414a4452474f2f65686336726468434a7464656a7743344d2b576655537a4c62686851483531314f75747955664759567632645552767236684b6e314a79556b67392b337a516e55366866764c7837796634656e42775a4d6a56336b6c536e424b74784363344352394a727a374e55796e67356a36644d72786170536c54754d6b6d662f56526c7367792f49516a62465631594261787a49716d7577714d43483435786b796e3054396e796e6b68546f384e73352f695077447750632f6c58703666524f336d666a393535642f326c68764c4c48702f7078717a53744c57346c66314b56456d415947414d5a5035313646564972344538362f554e63637444323233564c65436b674942547356334a326a646a30482b394d47347351527836524a78675262636946416638782f376f434a6f6a76526c536b303276714133634c7531516852396a54494a6b5662727a3936454745524743627451346f393567346d7a65716b63316f6365737a4549463844523567784a62706d6c6b786b304c366953457a464c337a73516d3174436530316d544e6a6c7179506574784d6d70495452546f4d39646a6d756d346e44577244674375426d375a6a63334b6c64344662696342413344504761374d325a493031616a4a774b7a615a75345456566b6841387976734f614378307247574d4a465a7a78415536327746464842423556696657764c663758722f384151666e4c6b2b7a3349795a7562317436557037526b556f36317257323478383452303368444a687470614d6a6b7a393570697053526b35503578445732394362366b32326c73717a786e626b35397170613275744e773636346955334d2b44504f64515a4b5853326f5935536f6377654b3853334f2f6232506c506f6147553138647a617a36655a55557155347236684b54383164567045774e7878373953612b36775a784b3277654f395453626453454a2b6c5743435057765472786e626a41485874504c636e4763357a436d4747637257504d676e76323961414d6f7a38706e6e4a7750574670647431704b30464a6a42494d2f593031536a6a654f5a685378547461532b7061436c317a633036456e6b2b6f6e30727a374e4d6c7475556242395a36564772616d76446a496a4235705454615571584d704955654a3756327362774539383853546978386954656f4e2b61596a306b64757872774c392b374a4539476c687434677246737052386f6e7437666e51316f376e4369477a67446d55656b61556c6f3731625672357a6b4a2b423350765875615054696e7a4e7966322f4365646662763448416c414e59575071466571756f3978496a535053614a315a437563482b394e46696d4c4e5a6a43317530714a4150422f556a2f6e3530593536674545525a64616275654b2b32774a48795353663655707138746d614734784f6d5850414f6544576a797a7534467265726d4953634775617a4855344c454446304161774e4e496a5270344555634366565258546f4f704e644f686a4c5943596d73504d36624e4144307241495863623244794f31476f6d455170312f474b4c624d6935347a33724d516842486d69654b346962506a567058425a684d4a525a4475614c62377a4d7856633954326a634a53744a55666d4166346a474b69625831626331386e362f724c5067624650395159452b2f356e34774b5733554b55424a536779635a77615532717363454c6a50794a684c5853726335783835336f7a616e5579704a51756678454578367a535041613542346e44666a4832324a57634c79494a6636497970386279415a456e6a6e6952555077594675316a786e754d5455734b38724456576a5a3373492f64715042544249786731366e674a5a75513848355355324e78596566786972707270753561635634734f43534a3352393450745336744c596a486745546452716b6344614d5273357044724f557238514b4d45455a48704237306d7a5250534d6f632f4b4b53304f6552694a64667353736953477043747869636476767a55316c5465556e7939356e70366243354935452f5766544b416a7777566c55536b71497a5063527a3235716f55456a484f65782f71625a71426e6363593667533964657331467030454c524577526c4a34554f7846443851314c62574248316748534a634e3648673954562b386175464a63512b554265436b78756e32534f61323279703233427341392f77445057445870624b775152312f4f3461396f71552f764332513045655a53563756456a6c5251503530384773715352786a736348386f704c43534633633539526e395a4c5848557a4c5a68726552496b455a4d6348644e524b34586c4d342f6e7250572b4449582b6f524b2f543963446a526c49456945535a494d45666e6d6e44576877636a476576786e6c3361546279446e4863797672487846704f593270424845625568497a3778557570714e724b63656d50796d56754b6c784233374e4a386b775062465856614f734a744d51326f59746d434f6149766c7534576b2b687950316f7667314833535a6e784a2f384159525a6549314a6f796c5348556a7363476946546a6a4d376570394a6a5964543350694a6264746c4a4b69414432353961306c6c376e59584562326e56545358564e654945725372494a67547a57313345444d45706b5369747465554d34494a2f742f53716c314876456d71484456576e634b776163726f305755496e4e787069564479776149312b307a644a2f5574485750776d6c464443794974614c714b455a4534696266356f6f63696933516473354f7465787264307a4571625854307154494a6f77674d346a454b6673556c4d6347744b436170784d725270534436697541496d735159656b7a52774a30576137453663452b316469645079556a31494e644f69625874665377327159564949416d506e50734b6e74744142416a367138734450467450363149576f4a5956754c67385077396f4b6b672f53726d5469652f4a46517270776742794a54626339784f636d5757724a495131644a5a6474336c454b557074514245672b5665306b414b4566324e62665955584b4c7a372f77413568365452437838574e78376576383935736a716c53556c4c6856764b54736342796b776473704754427a4a6d6f3076385276366e66754f4a652b6d52435044486c3951655970737570586c4b4b7932537431446143346b4652386d34626c4473664e3938315157566c3537674e7031446b6f6344324d4d306671753859644c62773353726e626b41656847436b2f42396f72687144574d592f6e306d746f6137664d70346c75313164347743577a7356795a456a62475950622b39443864346743715370392f6c4150325634586d666b66336d477158626a63535a4375444d796674556571705a47484f63387a6b56434352786943705a63575348647a637a42556b6953424f30547a576244393233792b32595069686555356e7a566b334e73675174546953536f4b536b7041482b677763784850656e334c62556f436b6b66582f4d646f6e7075592b4a674835794e3157374677366f6d5351416d5a4f652f396630715463783830735969767972304936365230524c5a337a75644d375353414544326e47373370696c4c473239534c56367177726a306c4262617174473574345a43544d7843686e6a735a6f4b6d656b45502f77426b4c49487756696d3165746c50376861424b694e7379492f37676b6356337843444745355035666c2f79574d746d7a6c386a2b65736558627a554a4367684a426c4a474e7839506356513139624b467341795061534b72676b72367a6c6232784f346e79774a50706a696638416e4e4f30394a63672b6b5662594f6f45693962575a4368564c42737a6c7869456b474d4768566a4f494577566348685a4839613032426544424335366e49645371594d67416e504f4b45616974764b447a4d5a574554616a307461334d724b64717a3353594d2b744f4b7142784f5632455636686f3138777454724b2f45515354743942365237564d796b63695549796b594d4930725846764877314e4b43787a6a48334a34724673627163364163787462616f3831776f6e325039366f58557373516167592b734f726b4c387267672b395631366c5737695770496a454a59644a536b674b6959707535474f3064774d4d426d413358546b53526d73384f5a75695a7a526c54776144615a764573644b756b4f4e686141514432394b617268756f4a454a55334e484d6e4f3444414272703031427270307a57736574644f673935646f61547557714232395366514476574d516f795a6f475a4f334f757263576b415169524d665642784a4653745a754f4d3445614677506e4f6452306844796c6c513875794a6b544754506f42586b334d62624e7466513935364e4b6c463877356b6a6f6e533754547374687462697043696f7149536734674a534f34354d2b77464e56697968574f66655546546b7541522f506e4c612f7447747952636f6a6277554b684b6847504b526e4e48594276473466687a4a5574594b64682f4d526e63576143317562536c53776b626477423567524d592f74546d414b63486d4952765035757033702b6774464955554a426e6349412b71434a4254794d392f51635532756c534d6a38594e313741346d6c7a7044535368514953516f44506c6d65496a7637567249716c6563632f6e41577832424863387731612f7744384e6575495a5168616433414257494943694a486230453135746c614a63536f79506165335459397449446b6a2b64796959366a5148646f5146494d46767474555435694242704a3147484f426b656e796937714d31444a3539666e37536a313234506842654155356851676e324250666d6d61327a645668736657655a70562f71596e4f6d583457416d42736a4d6d494766316d4b52704e5467424d44623739596a7236755332655968366c734c426858694a51344672473437444b4d474a4950306e4e4e3148674447776e6e32782b73713056576f31414f534d4433372b6b5432657232356b44784e306b5a496a50644a422f516730677053696b594f6635312f7965673332633537492f6e792f334d4672635753686174703365556e365644736f38786a4e42735837724876453838564e7649413669367a756e476c50427a636d4341434f347a394a5078546642437a626348474a782f6e6169684b6c6a616f7149534f534236783234724c4b32786866534b5447376d4f7a624f504d4a54757a79526b664750697139442f53424f4f2f30696230446d4b32644966623359555366706a396561743864477a6d542b4577366a65302f3664726336345170584f634a6e68496a6b31353270315950466636537254365a33507650794d6d54494870332f77444938443471494a7a6b6b2f542b356c444652774d48352b6e30393433546237557a747752424d35794d2f46586970617876416b4c50754f4359493759342f64754b4744695a6e48725445314159346737534245706263596c53334844676b4e6f387931522b6748765451345041352f43647450727845642f316f6b4f4938693035457a6b527878362f373049566964796a39596541504b54474b2b75625575536c433969675a5467464a69504b592b59706863687432336a322f774152497234786d4b3952366a5374304a6151436b7042472b51556d63704a412b383572484654444a3448796a55653165687a38342b3033556e4151562b516d436753664d4935425049714546366d334b654f2b5a616131745163666a4c625464654a416e4d313764476f33726d65546253556245624a7655484f4b7044694a775a397332787368486c78694b38716879426b536c303949485a366b7442384a785735594a79527944776356516d73584f3175346868677879484d5a542b5657775a67342b50394a2b3831733641363572545673313469794d6d45706e366c4867543248636e7342574d77417a4e417a4939765645756e78484653705866473043634a544f414b67734c735a536755546b5068352f77454c4364735365366c63374f63594271643377706c466163676d483637715867737132714359784b684139496b697649324e5933486376725175634153532f5a2b346c4b3347334d715575556b5342746745464d67474a6d724c4e6749475072433146746f35422b6b75656f5a4d4e33456c6b67467435503162686b5a2f543371693139762f6b35553947496f5262426d72687656542f502b524e2f6e36625553307054363545426662316b4a705333566867564a4d4e744937447a59555465373634574e7132304f79516479464142416a767a77666d6e32616b416731352f744a64506f316345324d426a3834626164554c654b433630493267774645676d51557169493966576c76396f6c474164595a306c57443462546a55644d756e566e77586d416b542b3743464a55556b534571584233486a30346e464d4e6a572f646236596866306b4179702f4850722b6d497475325462674b4c436d6c5468556b676577566d5035313546326e3142633577423870514c4b32484a7a43644e31354d42447266694e37747843354a547a6b65744d307475332b6e594e772b66704a62314f647963474d7262573231714c61326b784a3277416b7832685359696e4d7a73323071437634524147467a6b35676c765a75584b3357454f454947464c4979457134534f785648666975703057363038385367587055425a6a4a39495862394657545359384a62684167724b31676b2f36764b51416669765165757065317a4e2f2b54314c4e6b4e6a3559483934753158707070545951323636325571334a4b6c65494236677a42492b3954734b6a3050776a31316c68626334422b6d4a5033576b4a74326c6c3051755a385a4a5555714a37516f6e4d3869427a38567751424363352f6e38372f4f413446316732635a394a7a70576e577237626a696e6c466152434145516433715275794f63555659724b4d577a4a745456625577417844306452433351574867537048436b7867596748754d5a694d5679715647306e48746d5437736e496d7964656c734f4b425332723643434372684d376b396a4a344d592b4d3562566c4f4f767a6d725964307765384a3153567263436b4a68535144414a48632f38375643714c5732584d39424e5179316c6178676e737a4f2b313557354b4c644155465475684d776b6567397a544465574a3263667a3268616253316b5a74683968664f68486e547478426b5350765772592b4363635472367141634c7a456571363857556b68494d71496b4b6b514979422b456533614b5855454a776e66724e5776634d6e6a4838356e576c64526f644363374648436b37437153634a5546416a4d64766571787471506b4f4d2b776a506864363772426e32356d434e4c5a75447463536e664a68534a423970516639366d2b4a4374672f6e4f752b7a3132466b4d4c30726f693163644d37796e684b53594a55496d56447354492b4456316a4b4832716367395478363743557952434757645042574657714758456e6b6866494a4730797152774b6b47704c5a537759492f4b564e547377796e494d614f32696268704b30754a4c734862346843595667514342416d44452b6f785478717137464250667a2f414a2f65414131546c6654355263625334444a425473576b655655685353522f456b524750317267775168312b7549566d312b415a6e7033566a52522b395634617759556b39694f59397139494f434a43566e70476d4f4170727a61624152695073516777687867547643556b2b395730426433496b31692b73346475696e366b6a3836767a694b6e49756b48322b654b334d37453832315455315856307061424c4c636f5442475343517463636d542b674654324e6d476f784f745163513277747774464a4167656b34452b3149594848426a30497a7a466e54566b524a53417031554c6a76415541534d2b35704c3134416c4664673353323636306f4b74304630535545516652524741665938564d2b6d4e594a4a6c476976444f775831695779365643346638554a56506c47544139545537556f557970376d32616a613233475a5a74324c76684273764e7154452f543666797230457073384d4c75424834547a4764513263455264702b6d74654b64794e796c6371674441344145554e57336667696139726c635a3445563954394f7645724447314957596d5070423567556d344d726b67635171335572676d456148303257534672574e71557041527a45443150764e4b46497a346a6e48796a7a65436f525239592b2f784f5a5150597833726e73773235654a7171434d4d5a766574426257317847354b38455a4241395a716c636c5163647853344463487154714e4b733072414e7370734a6e7a53734139784d6e7a564b376257785a58783779737132336372676e326a7653377131514341515a4d5274416a3871743031744f4a48654c4431784e5737316b4c4b652f71497a365446412b7043766769463454736d524a48725055567063384f534542755245674b4a4a2f5041465461687434474a58705643386a755376537237373130416c524b4569566b6e43552f66756654326f5530355963656b76314e39597135374d7664557470625834626f414d6a62746e4237456e46556a4b747572596a35442f63386c4c4f527558507a6e6a6c7968304f76737373752f534a47785379496a796767596e6b48326f3171484250702f4f5a7a755353494b3534376169307533664377524d74714a424f6377506575616f6b6b354835785949474f4a5a5750536c322b6b42626762627875436372554f5939452f7238556d746c775a7849426a7450536c71776d566f6355426a39347679677a2f44416961493751657534797437484f7866306e4e736774724b6d53674143556f32704535374b5065614256326e672f686d656868534d4d44387a4f625056623075456d324737764d435a2b38476944585a7941444850547064754e356e3235303574384b54634d427152494b59414250655267696b715838516d77592b63575652514255325a4d61333032746f456f49556c4d5a5436646a376a37316f474353655a516c6f786738516e70355a6247346b7a7a4d314d64724d5a6c6c6e7048546d73674c33435250507a574e754a7a504f7351413852676a57304f4461366c4b306e6e634d38524950494e61747235382f4969436d507538517533302b334968707762592b6c5a4d6a767a334e566976547479702b6869764573587354386472457138586369434e695249672b737a49397146617642795133487342312b6349322b494d6265666551757639504f4635536d776b4a55416f416b642b617071757775494c4b4d7a31665441527961386d6869706c562b44314771626756364b616a426b52724d306349554f4161394f72556275346871385354367566537862504f4e714b4642424154366c586c5448334e554e67444d415a4d6e4f6b32774c64416e6743636b632b6b66794e5346736e4d64695a396277575569534276457a387963392b4b7a655351495155596d6652746e34622f2b4c635543436b496151442b475a4a5076493471585661676877676a366151564c5430526a564575536c61596e73636769734e7a4c7934676e546a75737a38725357675a436f422f444f4b344c54327635526536772f77435a742f68776b486245625a674b394b70777a44414d5777357966326b7378317743387047304e684a3279723669667655506a73726559596c4c615a51426a6d506b64534d65476646356a74456e3346572b4a554579776966415a6d38737862655a63515670644a536541526b483339616b54345a675357687458617242514a4a6f313135433170564132716a48456574523769546965685a5857414d647a5a6655436e456c746268416d516f5949396a57765a614d62544178574f51764d706d583233576b744f457241414f2b596b396a6a7656593150696a62594d794971554f354f506c496a5869376172564d624f557248702f454b5361416a6348755549346354507076567647646b4c334869616d765677344576514c3466416c6a71396970376234595370653362426a383831366d6e7279755057656276326b6b39525a5961413477777074494b48446b72556b414b554f4a322f68375667726349564978447375566e44486b664b644e58716d456745685367504e48425074586d426d72626a6d656e585174715a3668756b6456327055556c4f7861764b72474436563664566c622b764a394a46666f725231794250742f6f6a6f5370786c7a78436f6b776f77632b6846412b6d344f4478467259704956686a456a3136303830646a6f4b464351527833354872586e4e54597034504563364a36537036663233445169466b4537676f2b2b43425871364e41552b66726d4b73796e4d48366e7337687367744e74724566527766744f436164645154794349576d757236596b664f643649773836513475335568615149796b52364442794b79744354794a3131694c777238526e713171746367494b464162676f5279526b48317a585055533249704c516f7a6e496b375a616d6c4374696d6c4957634c6c4a324c373442782b564c66465a47423333375367686e584f37494858754977744c43336353564a536c4b344957676770487950513050684b3258413539524a7253366e4765505178473662667855747258426d44783968556c46572b7a6d62597a4b75635132373661524a4b466c4b534d535a416a3446585861525163716343543161676e68687a416d394741676f755568556b516f456a4834676f443654385641666877526c77446d57596231553468577339505071516b74723875344a55516f4c37655a5149417850725639745943626c36694b6d5150677a52746f494154764b3477544d356e49396f394b6835504d61334a366c4530374171665a475a426d794861304c677754694e4e50584a72314e4c33496275704c2f746130395372552b4833576a6448614641672f70586f334f46544a6b74664a78464f674b48684954674541546a76554173552f644d6f7a37784e31363251796f2f36564a4f50536330785835454c48456b744236774455744f676c4155536b6a4d547949394b5671644958594f76634f6d2f614e706e6f2f5432756f65626c4243302f4d456631464c595a387269477259355579706276576e77473970426a76322b445362744d70486b6d3173366e646d4c6451617557452f75554a5765786e6b664272713743766c48637652714c6a357a69634a732f45536a2f4142444b64353968672f4e57564e6b59626d545856727550686e69456168706c7547774330764d68525475417a3239425647784d6453554c5a6e75433662704730674e4b5874485a7a507033714f3352566e6c654954584e2f7743333651753830793363537031594a4b634b4354415076696b6c4171456b6369437066492b63554a5a307852387146456a2b4e55667a6f427161416f79764d4c5a64377838786f3650434c69437043556951435a426a3571696d68534e7747424573784277655a4e3332713236776b7538464d65344e4663716c636e714e302b347474395a6a70375456756c4c6a6145425468786759704b384463787a50574b4c6a5a30424b4d61343162746831794a503468324e573045487a4765625970596c52487162345844634957424977526d724754496b4b6b4933496b4c31573073416c49424b5235696e73423349727a6454703977796f354539505461674b63453847526467306c546f55705a6852456b56352f734745395247374b7a314852417370387537484250635664545765784939515534335465377562636a39366c4b75796751445273364c33464c7037547776306e6e317a6366344f364b4756516865556e2f544a34715478634e7553656c5652757232764c33785672614471414859534e7756676e33466567746d527545386578417262547845746831596c4b79464b6a5048636531532b4b79767a476e54466c346c6f30304857777457386273706b356a3756667333444a6b52625932305934696d377344346a6139776351446c436f422b783961426557473645324168774d474576504e714a324a4c6268354a7837526e4270704b6738647841563865346e6b6656765372397337342f4b564b6b5a7a7a4d3148596e6869574330575176706e72634a5834623555414a327135482f6152327074626a5a6c704e59684a774a56573273326a6e6c574730714a77524565304b4842715631303775516343565643384a6b5a49683774793445625571547353652b444d43632f6937666c53724c43414b31506c684b692f65493567416554796f5a4f63544239446a3270364b46554178446b6b2b57612f356b4f3165513134394a614b6f58614f71556343755632597a47554355756d4e6b51545872615867387a7a376a37516a576b4e754e4b516f6a7a435076322f57765464513646543679484f446d65574e72676b447359503272354e6d5a4749396f38744275704c695743446b48483272307162537979696f5a4d386b76324e6976616356367462376846574a734d4a306e564847466878745242376a73523645567a6f47376e4b534a36703033314d6934414b566256393039353976616f33526b366a6c59536850554467494378493961527442354d6f556a306a6d777676456a67696d6d764979706d4677446a7146584c5430456f636c486442456b66426f42714c514d5a6d67314534646566654b6276555574625170525343636a2b744e386261504f59596f4c6b37524e644c766b4f4f4c45796b3855735849374543555730736c536e316e625852724b58764552415354754950723755317155636965556247475a514643593267776b666c5431515978365242597a792f715054422f69747261675571387848594548745358555a7744505130766f5748555642447478633757306b4e746943527875397151795a45394b323449754365544b413649363832576a4b556e7561796b576a6738435157576f446d4f2b6d6445754c5542426861527765385636534e59426952577655787a3059586549536c39494b542b384d4b37794b3763515150654c5253774a39704e6457644b70746c68396c506b4b68754859653471625630414c755758614c564d54744d4a306a57536c65536f41343971687074737a7a314b72305862784d4e645a4c5a4c734653446b6e30724e5453326477356a4e4e713977326b344d6b687154623639734464783778537970524d6d564337443847576e54724677456874745754684f3434465030753975466b6d70314e4c484c696172734c704473504d4963414d376848356961743357713231774d65386d7a703258636a45664b506233714973495353325349694535696d766673455456705674596a4f4a794e637433647531514250494f444e4a2b49726242426874704c6138786730456c73714b643370427a5061714e69375333636a7951324f6f6f3161396265523454725a5641326952492f4d64366c61316d47434a5658537564774d6b4c585272457662536b70495066492b436b3971476f7147775a5462706d385065764d5a74394f4d423471536c4357675a6a675438487457577057373567317579313479637a613666516c43316d504241774f354950366973524639756f6d79776a38597374727555676c5a53534a676c4a696149344a694f5a5361666f67534a4e65616c496c72584750625732536e6756556c51456c65776d45687a7458477a42774947336a4d694f746e586d6c427843694278386568706a58324b7552464f6f6b546158336d695a396138783679546d4c4d4f763768436f624f444577652f705631466242655248306e416b6e72576e44493756516a46444b43413638795865594b443756636a68704979465a38596655685157685243687752526b416a4269387935304872514b684434672f367533337156364d64527932537973372b4346744b424873615543566a4d687535544e613841695349497166554b483548635971354f445068315a6c394f55676e33726c5274764a6a553868344d51334c4c74757678477875625035697051436a5a5765694c467358613363717445317044364144685665685863746778504f76303756746b64546271466c51626c736b597a52587356474245554257506d6e6a2b73366b74747a767537647966696f30566a5058715646425979312f5a36566f6242577771535354504f5436565a566b45547a4e58686a6b47573139634a69646d3272484939704457705072414c5858664d515241486575573064527a6159597a4774732b30566235436c5531634535694758614d5258314a2b2f486854744850464264577a7269616c7156386a6b785262364f6f4a4b6362666a4e516d6c674d526e6a4b546d55646a59536a616f426153497071564d6f393442634538635347314c6f424676636d3651464b546b37426b4139365271465a5677426d5656336278337a50714f7332306d42414b667363564a385459427773503462505a6e312f726b724849704c616d35767652673071723145317a72355849423570715745446d463465444f744b366365754662392b7968717138544f4f7051327446597869574457696c7474535650724a6a454742567956374632376a4947754e6a3532695936613870446174704b782f467a6a304e597573432b58456f6254715747376a384947454e4171754855674b37653154737a5750787848732f6872735138524c7176566a5162556c61706b2b56493539685436394f32636b794f32385a34697a523175334b6b3768746254394b42496a307a545748704a69633879706355326e424d5934674773416d52387655523631352f69675376777959525a333030784c73786231596a41657463527a42485543317930513632516f416971714d65736e7442394a3565786f65315a564f416f78376961466177482b554462784175725670495345346353667648662b6c58755274457973455a694a476f6b6a6176383656346365486d647861434f5a70514a426a657845393159526c4e554a64377964367661424b42474349707777656f7242454f3037563357544b4647505474517457473768423561365231756c5132756a616630716179676a714e563435617557313551766e304e544f687869554c626a754e644f7656665173376b314e7349456f3855486b523170544d4b4b6b774d79503730717533776a694d5a677938786735726d464a636745436d2f47462f4b594a305141444c31494470356a785831334b302f694f7748734165314b74735a58434c395a58576d36727a537066764c6b4b68747679787a69716b385876455561744f6f38786954584f6f6e54355a4b534f617837334a32786955306f4e33636a72377142386770436f392b394d54505a6b463579334548306e715a36335675336c57654365617572745070504f73727a3350517266722b33635a33717773446a76373151626830596761632b6b50306272466c354d4177666646527663426d50464a45643275744544616a4e625871512f416a527078325a516f7569555170506171794d6a42694e6f7a6b54782f7250706b473438564b534571506d6a2b64655871566441536f7a505570495941457a6d7836515a556b714b31715059412f7742425564467a4f755349316c5947644d644f4c4a2f646f327765566e39614c59443937694e7a6a75553974594c5a544a636a47514b4c7935386b6e4142504d4466316b7a6b344656494f4f59447350534a39513671434479416e3070677047654968726a6953327364587658457473706765744e324b766354754a676d6d614174546953737953636b316f625055773853347437684c4d6a474f314963346d714d784d2f7242556f6d4b376154475a41346a6c4f6f5433727774706e71375933307939794d30354f444557446956746f39497230464752505059344d4831647945476d424d514e325a43584c706b304a6e5364315a6a666e38513731717352436b35634e657455493045695a65496f444861694b676d6147496e41765a4d47734e5848453057387a5235684b7867436c4b78513878784159514637535663707079366b657353644f6653427274314a35464f46697430597259796e6d614d334b6b384b4972434244426a6979366b65522b4b666d6b7455444442784b6a512b753068593853514b6d73306d656f305863596c6b2f716474636746437850736147725468655745503468386251654a2b595579325232714a2b4c4e776e6f3133485a746c4462616f33484970793357457952307a36784c72746b3038647741393470646c68335a4d706f515977306e335032664e752b594c556e346a2b744d585574364c45326f6f4f4975642f5a666b2f3841554b507942564878624166646b6e674b544f572f32636c4d417643506a5038414f6c767169656352693171493673656a32734a334548314270564675397350474d6d426c59314f6c727468755373716a4f6166384d7462623050306d6f2f696556784f5875725874736842785454726749394e445544676d50644d315a7439727a6741786d61594e616a4c784a4c74475566796e694a4c2b335179647a4b754f526e4e52574d716e4b79796c6d78683470314871333049534254774134366b725074395a4d6170316b4a6e655665773470696163784436675248633951767647454342547857716a6d546c326147615a30303436647a684b7635554457353445344c6a75557a656c4e4d447a526968555a6e466f74316656435038413478385256436a41697963774e70703178515573376478344a6f436768683864537174656d664b4e797744364450363038496f484d53624d784f30686676586966445a366e722f4552767072626b69416130614e79654974395176724c2f51375a6344645870553659714f5a3531747762714e377178436b3570374943496b4e495457394c326b78554e6962544b4662496b323562484d3076454b4b6451735a77425767346d78486432716b3035584534785934316d714165496f6a6d624e5950745332414d617049686a5633744d48696c4e566d4e5779474b4c617853646a434d33417a352f2f41446f587752572b4d797a4e716d666e656a7a324e61757150744d384a59517a2b7a6c35596b4c412b31505855664b4b5a424d334f683732324f394b6b7141394352546936734f596f416738545139544b535963624d6a42714d304b54775a574c53427a43322b72326a79434b3777474534574348576e574c53424531686f4a394958692f4f4570363351524156464d57724870464d2f50633061366f62504c6e36306668353949506947593350554c61763841376631705a702b554d58544272716874426e784a70523032546b434f476f346d643731346b346d525866443245596d2b4f673569747a72524d5142574437503435697a717563774e585743344f304556672b7a7750574d47734a67332b63334b78745449422b615a3850577079544d6255757767697449645639524f617057786653534d7050634c736441377172477539707931783962573761494f4d564d7a6b78777278474475736255776a4643435a684145534f764b664a5354564e533435694c477848656d324731414c67456a36663841656e484a50456d7a7a4462445366455875554d546766316f774d54533075725454427445696d42594261523352695047414a464c727178475750505137485230707a465568514a4d574a6a6c6d33417851735a6f4531554d55454f4b4e527351735a7062726d4572596b6271576b375366536f6e54615a51477a454e785a775a70634b4b376931424e614f35325a694e43626353635172317036744269433930705344677a52354532422b435a6731303266564969686877687539576b594e447442504d334a416d3674646441426e696938465a6e694750744f3639324a47354e594b534a7863474f5664624e75494a495048705845456354414d7945764c744c6a696942676d6b4d68484d6f4239494b576b7a7858417469627843547069434a465a3472436474426762756d6754546c764d5731596e367a305971504e45392b4f6f41726a565853766c6e64537871446d45617841463645427961594c3450687a6c476b49373058696d6434554865596253596971463669443347566d706b416558394b6d7355356a6c50456247356245516e394b6d494d594a6863586356675977396f674c742b6542545654504a676c3864544d334a504a6f76444557584d374e386b434f39454b695942614e7445303875656338544141376e337036316752547469576c6870796a47347a2f536d525571744d3073436a5659426150554d5149706b43662f32513d3d, 'comida', 0, '2017-06-01 16:59:44', '2017-06-01 16:59:44', NULL, NULL, 5, 4, NULL, NULL, NULL, NULL, NULL, 2, 2, 2, 0, NULL, NULL, NULL, '121.00', 0);
INSERT INTO `producto` (`id`, `nombre`, `descripcion`, `precioVenta`, `idTipoProducto`, `idSubTipoProducto`, `imagen`, `tipoproducto`, `eliminado`, `created_at`, `updated_at`, `codigoInterno`, `codigoDeBarra`, `idOrigen`, `idMarca`, `material`, `usado`, `color`, `tamano`, `peso`, `unidadesCaja`, `stockMin`, `stockMax`, `ventadirecta`, `modelo`, `estilo`, `corte`, `precioVentaCredito`, `conStock`) VALUES
(383, 'productocomida2', 'desss', '130.00', 8, 0, 0x646174613a696d6167652f706e673b6261736536342c6956424f5277304b47676f414141414e5355684555674141414867414141426f4341494141414331304b4d654141414141584e535230494172733463365141414141526e51553142414143786a777638595155414141414a6345685a6377414144734d4141413744416364767147514141424a505355524256486865375a3162634650484763636c2b534a62386755625932706a456936474249504247427775355356704c704d4a675347426159615a4e4d4f6b435a4e6d4a74503249592b5a79557737504b6270537959506d647a547a49424c48704a43486e4a7253777234677247354f64526348475077426475794a646d794c4b6d2f63373744515a614e6b644352644654375038356d393973396538372b39372f66666e736b624f753161396363446f66646272656f794d6a49794d374b6c6a7759472f585259506d4b5a58352f77477131597248616747566f6150696a6a7a3736387373764a79596d4c426d327a4d784d70394d35506a377563726d4b69347676762f2f2b6e4a77636a4b46516941613575626e59723179355170372b673847676444367259423063484953676f714b697249777369383053436c6c2b2f34632f58727a55415231723136356c41686f624737576d4b74482f39354268566c64584e7a51305a47566c4b624b3642516968646c71686950334d6d54506b6b5264464946562b763538714b315a7939446738374835352f333633323431436c7931624a6a4b38644f6b537770514c5a676d45736a5672316b42786533733735454352564b31667635353849424351596a677730724b6a5178456f46793564757051316a52306a4b586d4661465930375a376176694e2f5869475a38516c2f31594f726d4279574f5933306d5a6b6c594e515141714156796b36644f69552b6b4b725230644861327470706c65667a2b633665505974655a544c676c325a43656e392f2f3430624e327a3069496d4b374e796373624778425173572b48336a734a79646e61334b66336178444743486c4a324773597365785149514a707246434a55594561576b464339637541426a654671787743385a374c3239765679435256453058657a61746575423157736f6a34794d64485a3253722b7a475455314e59693075626b354c7938504c553456484b4b55444645414461433473724a5366414130496d3249626d7472493456306a417252396658314f50372f58726b4b7935703139676b354171787351455a63714d67784844436f5a7a5a76336f785845563868786e506e7a684669514b59655a646d597430382b2f31744f6e74506a385742566e4d57735a786c3064336644444d496b306f426c6e5545646b43696f714b68413057526f413243504c5a524c734a44584c375432396658742f63337a6d444949372b597742666742396a64304c5852446e366962564355746f367171696a7a367855646a623231747059313636535459704955306d734e5549477038416d4531394146646f51422b78536b4463636f7948324b4a6749305a6f385764717563414f57794a5455314e524e5955785245444d685135377146523247747061634576593947706a34435645472f665337395635306f7a7a5745716f413946456f7151586c64426b4363784e567866766e795a79492b71476259334b3376673775642b505564304e474237334c527045344564456f5a69614c313538796252734c43734e626f446c473378715a3037356f694f4872414d616243732b4f7a4a586e7347524e7475446a6f67467a6d54777668646861784469616931374c334335584a784f706f3932796b6a6c6345696169444775324b61694339574f42774f6a70527a4b324e6d3250517a2b7a3244454a4a314a5075795a6a497869425a366533736c4c755a4570316b544435754252785864437a4547795a674b346c6852773871564b2b56316a3854437959487873387049354f32693254436b6f7279386e4d68584d79555232736e64514b427259486933386344746476663339342b506a2b666d3567344d4445532f67786b496d2f3678724c45776c6664677535342f662f3643425173534e4e686f6b436736524e48516a53744d3153624a4c67327a654754324965544d593651774e457173376867595849736e5365614335615a794f2b3762337434756e344249566171517641582b7a445050614c6e454133376e7a5a76486e6f7951552b4b527030494a643752734973466f362b76726b625a575468676b674e753664537642736d597942777734676b6350665649547356564b6d4d7877486e3330305750486a7547677451707a494157784158516e497443574d4a6c643463534a4535724a5446412b79744b7979514b6941775a756a31367656772b546d55574b576f575a59464f2b4135594b734d794e4372626f4a2b5668386c31684778703261646e6b5168534e414b464a3939307841532b4d53746a30696f714b5568346d33785732386f6f4b4c5a734b34454e6b42794d5039574b4d426a51476e45544b793876782b476257736b414c37464d4f38647061345737772b5877776935775274546c66594532467261656e5238756d46437a38614c7832666e342b6a674b505044773872487758566f56575a323759544b5549506234575a78494f4a457a74344f4467794d6749576f5a6c72534a4e59434d6b30724b6d4159524f5061386a64687946772b46493573636942734a6d74684d55674e4f4442772f6973703939396c6c494a344f635366455379592f366a554a536a2b417841566f50487a344d313457466851382f2f444475596d4267514b744c51316a627a7039372f665858315931494d356b456242354546787a7a46693161524772436c5263547a4f673667487a6853672b54355348544a634359466f6e364b4373654947533852476c704b667a715156464d6762594a5965545844654b48486962726e364c714b705a6a75706b5032544d44353579434e36565477634b435844314d76704e4430352f32336c36507042436d59426b673163574c4630635a4a707445484445683964386d35514677767544697859745268736c63496c4770424e70694e446d4d2f774a4e6c49416779504a345048495951636a3338435431396657516e685a63383543706555706f4a6270343964565832653769504f2f743272557256584b4a48716c6857594b322f667633662f44424237416335787369644d317337646d7a7837536e58474474377531353863555830585553664458484543676d6f4e7932626474333333326e575932474f6862545259464a5662514578647533622f2f6969792f456b676959382f56656b6f6a47503768634c6f4a66346f54446877385846685a7146516e41724659304b734e7039506633662f333131356f706b56414f6c435a374d5a4a596f676b474247695a4d4b3634754a68345471744c4a47425a626d7165344339524438487769414677455778396a446c66685661584c504141346b5949534452543670416f6f6947336f4b434151534b756c48387351767a483832694646434568524575594c4b2b4834672b543434664d64326f4462534f4a486830646454716457566c5a6a7a2f2b4f416f7957356831384f424243587530636e4a684a4e473575626d516d2b67774f5534634f6e516f4a64756a41626645556541664f50555255586939336b53487958454348794b68434b6d34754f5441414b4a7866424339632b644f2f49615a337a614541355a78644d6c3857674f4952737645634565504874584b61514a6b6b55776659734364554164454a2b636b6b6e595163706a523545337072415645352b586c7a5246745047536e4a634374714b6859766e7a35344f4367792b576149397034454f4d53323553566c5246396e546c7a787546774b43384470413736675867534d6e71614c6c474571514452363961744b796b706b563974536e68545656576c4d4c747935636f48486e69414f7372723136396e4f325a436c693162746d624e47757879774d4f69646a4b48365946476f5169754547687061536b57696b533951696d5267764a523168747676484874326a5561553863684663467a6a4f3774375a56767361786475355941727257314e5a6e6866546f436c717572717948743674577241774d446e4a4e524d4a5165503334384a79644855545173693244466577775044394d4f63696d4378735a4733506d71566174774f6b79443275636362674f4b534f47337271344f6438773243464834417a5a415958587a3573324b3134372b77316b757070654f6a6737696c546e476463416d5070626c446a4f51506a593268684f474b4f534c5a4d6e55317462534a6761696162786b79524b384f78657a6d6370307a5145326b42336b697253685a57526b4249726e7a352b5077436d32744c5241722b49364b4b447438764a7969556a773346794d3631443775513036756e7a354d72566378527a4f326f434567554d466a433163754a43396a6a324d4a58372b2f486c6f68426b6f37757a7378506465756e534a4e764b62596546545566546262372b4e344f31323556384e3051565749684957676b78444246676a64727564755671386544473961395a5a42706942596e676b526d436a5133616e54703343434930624e3236453861616d4a74676e673135395070397943662f424c466131423058647a4668425155464e54593159496f44587037336237655953452f364c726951414c634c5036644f6e662f72704a39684130596950464d5932624e69412b4841646b413654744e512f787074477330416d51592f6e754978725a476b776a63774549412f6430694474454c49715032444d34383277574732576f50366a316b6443744d6b4b6e356941757247686f59487362467872634f504757744b3274744d6f47727267424839393965726c72437a384b6c3046595a75722b46466378317476766158326464766e77695a7a6866354a345a532b4956706e756269346d464d38553071654a39437553537349793961514a642f682f507a7a7a3153624271566d7567397933534d6568794e484245664b324b484d362f45346e4536707a6374332b7366684c56507449684c544534317a7548486a526c39664830527a4f4b53496c78434253324448546d7243662f77535061775a79726368726c2f725076486a6a794b564d522b376c4471696b44576f576d7771335a49502b674d546f6141394f38732f3776763535352b584c612f306a667673484f697374734345337838493547546c4449304d467863573044686743656d4f416c57544a3532656147594d4b74584659704d2f3277444c796878614c4c445072736f456b4539544f594e414b4d6942776a5069626d6c713172663836725856704545596e6a4973687338786f7175724538317833694e304f334869424c5251704a594e384f544a52756753516b4b686151345a43744837397532624e32396552635639304572305271534e723243764579316a314a70617257317462586750657353657069776a46344a55486834457876316e7a375147417667425a53787261745a4c6d334151494a4d536868476f63533348504d675a476870694a39793061524f643950623258726c7952565134417851536f5a4a746a593434686e424f70314e59727179736c4371686c5477545149596578553870563663745249796949574835546d687562696157355a684744436462464b755a434a6f444e3530514c2b4e4a37736f7955425164666a4c6b5976706c6d656a383470527633727a5a336430645458646d426b4e6a343245345a414157617a445531747043526b5339746e6144326e41535949624775493648486e6f49577476623235314f5a33563164553950543164586c376f776f744a634a4e474c466930714b69715369306d3542306f6e35666d69374e476367474c304b434d53415147646141794947396442417853325973554b2f414d6e4554596b3469744554527430566c74627937556f757147685161597165747a6143473542337046714259766c374e6d7a386b63613070706c655836675578794f69516e746c7a6f7863466865756e51704c7051444e4636597053792f3345315738386d544a306e52746669636d444470416836496d6553755448356a59794f4242336c75482b76736d5151777936364630784f5049526170436f65774e6a6a6f676b316d34754c46692b783457375a737753397a46544566446469785a426f49427a70752f5858496d4b43346a763337392b4f446d4f79536b684a6342373349533477303552656f386c582b6e43347077354555753479497642624142555066662f4e7453556b787066372b2f6b63656677786d56362b754c6973724f2f436e502b666d324874366576507a6e55514b4d494d4534336e6c6f45776d2b7555475656565673457952417946547039616d4b3642795a47514566735670614e6270384f4748483749546b694841526277416a3846476c357472392f6e473659657472375330564f4a64756554656f436a367a546666704c764f7a713742775545574344705061793050447738546f5a49586479466333306e526d5662627156504e566b7549413454484e2f625959342f68736d6c322f4e695068515635355045727449396665597169695a3076584c6a416a5a673055594855705231342b4f765872384d795178426d42634b3156684448456c4a656443695a7a497a756e683461454e51574f765071317463693671416c394d4f2f2f2b5561647666323953453751395a337a4c756e6d554530786f59576b31426f7648506e546e57377368485050664845453854496b50374f4f2b2b77754d764b46736255327778516946596d4e6f376f6a656344754232746e416f51683845797045544a697a356539696635537a534b50776d4664757a5951567a426d6942576565474646374448773077344446413046424f3076506261613134566d6a587845466f424c464f63796f6859777531544c594956717834633953752f4f7833466e507a50386144507a7752633765356158564e442f2b4c723434514252484e32346d6e65666664647773526b666756642b434a534a6850756b65384e48454d6b51322f76766663656e68716c2b33772b7a747947764873776747674578546a78612b2b2f2f2f3654547a364a46676974744c724541482f4b476d496c775934732b516952556f7977414c464971757834366d466162616a386c6977384e58623150424863746d30724b554c6d7a4c4a392b33614d7976587877636a4e6b506b2f637554493774323769556b315532494142544b584d4336575741477a7341796257766e57332f6e5739314b4b654770414d37552b5868684a4e4d45346b2f2f7878782f444f4c754b5a6a55617345796b6a417868524569354b36537835434758366547776830556e326d724e344766544c37663551785a37646961615039643675736a706447526d577533326a5a753376505758762f6f6e34764a4f6b572f766a414a4359476e544c617576714b684939717634495234356e434e6855453846616f316d6c497a6b392b37642b2b6d6e6e34704672626c64425a417a4958505469654e536c436c5a664e2b536f704c35334e51374e6e71366f51454e4d52787045424f4d5648513458433758776f554c43776f4b654d53757269374e476763594e7141337252773759506d7a7a795a39446873422f416c72706136756a6a7a725268566638464a4868332f4d35786e31636d76556732366b63617849464e48736a527731455973515244524371745846446e536b484a48566679616a432f424f304364447a346a6e465a624653427165416554787948444e6758423535556f6c30676a426a69307a4b32507638337664376d486d75724b71617432476a66562f2f384c6a6a666d505143624b6455534135325977375057732f5369334678354a416c6a325053614a6f6c4173715435743455624a36426269482b3549476d376e4d634b6e58497954556e5632416d506a353836644a61764f687358743962413962743279626354726b52443264792b2f394d6f72727a415772704a356d686d4a556e514565427234366c48664b6d696d7577474347414171672b4a6f526a49564f467732353374375532484c7971797265306a2f68675a7a4136635a4e6c76702f424a4559382f4e2b655354543270726138656a2f684e5253564b30446f364f4c452f6f6d2b47335376446f61426b464d62784a5767744c497853745a30676c6f7938494d556f71475946756a436a7147516a6c4c6a6a6f3967766e4e5337562f33453633374e6e7a3444364c53306556546e616545655a674f2b2f2f316170316c6f7051704631724a6153546a5467396b382f2f66525858333246786a6e79617459774d44786d676f772b5a6a326a707a7252494e7a4f3842694c61745973676f687239664648744a466968444854616a766430757a33423941303130722f7a4f48446a2f794b327845706f6d734778644b68534a563838795943535849643463424e487a703069444e6b4f466b43586374614f586167723343616f6f5243384f5372784149734e6d764145717264554e656c2f4f75543230656b4d652f594439392b632b796650797a36525a6b6c454d7a4f73553845417a546d4a32544c6d5079444a5a59766f68734c5842355242424b51514a76444e486c6336745431486d584b4a44333333484f45466c495575304430533059337176554b5a73694846775857594b69317455576b726132495739412f676a6c77344d412f6a6836526779746a3452446e64587532624e6e795039415645516d2f433132544141414141456c46546b5375516d4343, 'comida', 0, '2017-06-01 17:00:12', '2017-06-01 17:00:12', NULL, NULL, 5, 4, NULL, NULL, NULL, NULL, NULL, 2, 2, 2, 0, NULL, NULL, NULL, '131.00', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productosucursal`
--

CREATE TABLE `productosucursal` (
  `idproducto` int(11) NOT NULL,
  `idsucursal` int(11) NOT NULL,
  `precioVenta` decimal(65,2) DEFAULT NULL,
  `precioVentaCredito` decimal(65,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `productosucursal`
--

INSERT INTO `productosucursal` (`idproducto`, `idsucursal`, `precioVenta`, `precioVentaCredito`) VALUES
(375, 1, '200.00', '300.00'),
(376, 1, '201.00', '301.00'),
(378, 1, '300.00', '400.00'),
(379, 1, '301.00', '401.00'),
(380, 1, '100.00', '101.00'),
(381, 1, '110.00', '111.00'),
(382, 1, '120.00', '121.00'),
(383, 1, '130.00', '131.00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proforma`
--

CREATE TABLE `proforma` (
  `id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `idPuntoVenta` int(11) NOT NULL,
  `idCliente` int(11) DEFAULT NULL,
  `formaPago` varchar(19) COLLATE utf8_spanish_ci NOT NULL,
  `Pago` decimal(65,2) DEFAULT NULL,
  `Cambio` decimal(65,2) DEFAULT NULL,
  `estado` tinyint(3) DEFAULT NULL,
  `eliminado` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `idAlmacen` int(11) NOT NULL,
  `idMesa` int(11) DEFAULT NULL,
  `Motivo` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `horaentrega` time NOT NULL,
  `fechaentrega` date NOT NULL,
  `entregadomicilio` tinyint(4) NOT NULL,
  `cobroalentregar` tinyint(4) NOT NULL,
  `direccionenvio` varchar(500) COLLATE utf8_spanish_ci NOT NULL,
  `importetransporte` int(11) NOT NULL,
  `personaentrega` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `minutostoleranciaentrega` time NOT NULL,
  `geolocalizacion` varchar(300) COLLATE utf8_spanish_ci NOT NULL,
  `idTipoDescuento` int(11) NOT NULL DEFAULT '0',
  `porcentajedescuento` int(11) NOT NULL DEFAULT '0',
  `importedescuento` decimal(65,2) NOT NULL DEFAULT '0.00',
  `total` decimal(65,2) DEFAULT '0.00',
  `aCuenta` decimal(65,2) DEFAULT '0.00',
  `saldoACobrar` decimal(65,2) DEFAULT '0.00',
  `cuotasSaldo` decimal(65,2) DEFAULT '0.00',
  `cobrarCada` int(11) DEFAULT '0',
  `ordennumero` int(11) DEFAULT '0',
  `observaciones` text COLLATE utf8_spanish_ci,
  `garantia` int(11) DEFAULT '0',
  `telefono` int(11) DEFAULT '0',
  `ci` int(11) DEFAULT '0',
  `ciudad` int(11) DEFAULT '0',
  `estadoVenta` int(11) DEFAULT '1',
  `nroCuenta` bigint(100) DEFAULT '0',
  `etapa` varchar(20) COLLATE utf8_spanish_ci DEFAULT 'venta',
  `comision` decimal(65,2) DEFAULT '0.00',
  `alquiler` tinyint(4) DEFAULT '1',
  `fechaEntregaVisal` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `cobroAnticipo` int(11) DEFAULT '0',
  `esVenta` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `proforma`
--

INSERT INTO `proforma` (`id`, `fecha`, `hora`, `idPuntoVenta`, `idCliente`, `formaPago`, `Pago`, `Cambio`, `estado`, `eliminado`, `idAlmacen`, `idMesa`, `Motivo`, `created_at`, `updated_at`, `horaentrega`, `fechaentrega`, `entregadomicilio`, `cobroalentregar`, `direccionenvio`, `importetransporte`, `personaentrega`, `minutostoleranciaentrega`, `geolocalizacion`, `idTipoDescuento`, `porcentajedescuento`, `importedescuento`, `total`, `aCuenta`, `saldoACobrar`, `cuotasSaldo`, `cobrarCada`, `ordennumero`, `observaciones`, `garantia`, `telefono`, `ci`, `ciudad`, `estadoVenta`, `nroCuenta`, `etapa`, `comision`, `alquiler`, `fechaEntregaVisal`, `cobroAnticipo`, `esVenta`) VALUES
(6, '2017-05-26', '15:15:15', 1, NULL, 'Efectivo', NULL, NULL, 0, 0, 1, NULL, '', NULL, NULL, '00:00:00', '0000-00-00', 0, 0, '', 0, '', '00:00:00', '', 0, 0, '0.00', '0.00', '0.00', '0.00', '0.00', 0, 0, NULL, 0, 0, 0, 0, 1, 0, 'venta', '0.00', 1, NULL, 0, 0),
(7, '2017-05-26', '15:15:22', 1, NULL, 'Efectivo', NULL, NULL, 0, 0, 1, NULL, '', NULL, NULL, '00:00:00', '0000-00-00', 0, 0, '', 0, '', '00:00:00', '', 0, 0, '0.00', '0.00', '0.00', '0.00', '0.00', 0, 0, NULL, 0, 0, 0, 0, 1, 0, 'venta', '0.00', 1, NULL, 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedor`
--

CREATE TABLE `proveedor` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `direccion` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  `telefono` varchar(10) COLLATE utf8_spanish_ci DEFAULT NULL,
  `paginaWeb` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `contactoRef` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `telefonoContacto` varchar(10) COLLATE utf8_spanish_ci DEFAULT NULL,
  `correoContato` varchar(20) COLLATE utf8_spanish_ci DEFAULT NULL,
  `idCiudad` int(11) NOT NULL,
  `eliminado` tinyint(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `proveedor`
--

INSERT INTO `proveedor` (`id`, `nombre`, `direccion`, `telefono`, `paginaWeb`, `contactoRef`, `telefonoContacto`, `correoContato`, `idCiudad`, `eliminado`) VALUES
(1, 'Luis Taraune', 'SCZ', '', '', 'Luis Taraune', '75336475', '', 1, 0),
(2, 'David', 'SCZ', '', '', 'daVID', '121381923', '', 1, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `puntoventa`
--

CREATE TABLE `puntoventa` (
  `id` int(11) NOT NULL,
  `idSucursal` int(11) NOT NULL,
  `idEmpleado` int(11) NOT NULL,
  `fechainicio` date NOT NULL,
  `fechafin` date NOT NULL,
  `ventamultialmacen` int(11) DEFAULT '1',
  `almacenpordefecto` int(11) DEFAULT '1',
  `puedevender` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `puntoventa`
--

INSERT INTO `puntoventa` (`id`, `idSucursal`, `idEmpleado`, `fechainicio`, `fechafin`, `ventamultialmacen`, `almacenpordefecto`, `puedevender`) VALUES
(1, 1, 1, '2017-04-21', '2017-04-21', 0, 1, 0),
(2, 2, 4, '0000-00-00', '2018-05-24', 0, 5, 0),
(3, 1, 2, '2017-05-02', '2022-05-11', 0, 1, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `subtipoproducto`
--

CREATE TABLE `subtipoproducto` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `imagen` mediumblob,
  `idtipoproducto` int(11) NOT NULL,
  `eliminado` tinyint(3) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `subtipoproducto`
--

INSERT INTO `subtipoproducto` (`id`, `nombre`, `imagen`, `idtipoproducto`, `eliminado`) VALUES
(1, 'subAccesorios', 0x646174613a696d6167652f6a7065673b6261736536342c2f396a2f34414151536b5a4a5267414241514141415141424141442f3277434541416b4742784d54456855544578495646525558467867594742675647526359476867564678675846786759474267594853676747426f6c48525559495445694a536b724c6934754742387a4f444d744e7967744c69734243676f4b4467304f477841514779386c494359744c5330744c5330744c5330744c5330744c5330744c5330744c5330744c5330744c5330744c5330744c5338744c5330744c5330744c5330744c5330744c5330744c662f4141424549414c634245774d4245514143455145444551482f78414163414141434177454241514541414141414141414141414145425149444267634241416a2f784142444541414241675144425155474177554842414d4141414142416845414177516842524978426b465259584554496f47526f516379516c4b78775350523842526963704c684653517a51314f4338574f697375495746384c2f784141624151414341774542415141414141414141414141414141434177454542514147422f2f45414455524141494341514d434177634442414d4241414d414141454341414d52424249684d554546453145694d6d4678675a4768464c48774938485238554a53345255474d324c2f3267414d41774541416845444551412f414d7474564d70436c36554b54794c333834726b4b4f6b3231563859615932615669366b6b43497744423375766150396c3866374146505a68626c3778773468627438366c734c5879706f6e4c536e4c4d6357505451637252352f783273756759412f5356745172444531435a6741334a50336a796c6274572b3453727965444173577870556d577857644f4f7362766839396f72334d784f65677a4e72773351693342497a4273436d4c6e6c306a753645376e6a54727161346343584e614b394f4d4d655a375834736d564d4b474b696e577841383471585674573241527831457833317964414a344d655774786c4345745a6937774a31566a2b7a306c76533037795359745146546c466c4d334837515172475a736b725376496a50434e6e564b57366c6430616e386f75616653372f6c4b657138525656776f3568474b556557616a735a68546b31476f55434e44465055304c6b68656b6f344e39594c386335346a6243616f5a77564d5874357772773076527168356e4950457058304d6f6a4b726c6a4f6d316e6a306570787555343469716d4951387a3275546f77386f7236784d344145366b39637779696b7079677466664676535556697345446d49746474784575374250434c506b312b6b58356a536551634950614d51636d664a544841596e5a6b4a6b6b45756672484541795178485352374162767245625a3236536130646964506b6949356e4577657471784c316a4f313276476c484d4a526d434978554b30744753504831626a704438737936524f49336778646f31787879774d67726d4a73576e46697037336962624365535a7155494f6b7a794d515754547471736c34554879424c5a72484d61794b78514f762b666c384e384e4668422b7351315950326d6f37646679786f2b5a622f316d567372395a3932362f6c39596e7a4c662b736a5a58367a3056432f6c39593450623654746c6672507632686679657364356c762f41466e624539596b7238476b7a566c537052633674465236797a5a4b7a527031747453425662694b6c374e30543353727a564659314a36533650454e586a676a38526257344268456c4379716c55737047674d7853695275417a61776466695662326557464f5a532f724e6a6b54525565413046564b412f595542414675316c736f644876476b6a4c61505a4248346c57377a4b44686d422b41356d573267396d4e456f6b79486b4c4734456c4375524230384951316751344a7a48314f783549676d7a6b71584b6d2f733661594266787266634e37373456714e6256585675504d6464555647356a394a704b36596853776b4d3664786a7856746a574d624d59426d55374174695a62614b586d544d563369744a444a476b58644932436f375463384c38554b4555746744316a5859476575656a7345756e4c645a34506f49336835747169696f343954384a533854744675705a7335416a6e4556796b5a6b7159735343654d592b713051724a77636d4e703052745559455531654444734d386c544b5663412b363332696d75703273412f31693637374e492b4d644f786e744c6855384a6c736b4f64534459635359304b624e337444704e63363668777a452f536153686b4c6c4a4a5776753644393478704c66354e486d4f6341394a6b36692b7479416f356d64704a6f6e31716b35694141535735572b38554874634c6b5459755536665268694a70714b6c534a71436b676748667a7439346e773355423951417842394a6732616a7a454961614e6373486848725755475a3462456770436e335173725a6e50454946635361656b4d475232676d666545647a365476725066434a35394a45394554496c526759633953576a675a786b79494938775a564d6e424b53654556724e5174645a5939704f4d6d592f474b35536938654831747a36697a63335350417849596574347a4c52694e5761476d44787361504464594a6c5749344c327153417370654e7761657831346a61645a355a35455753396c6c69616c57594d684c4a3638594b765332467470474a5a6278437371654f544846466753455a53727645457134444d642f724768586f315135626d55724e593735433852684e5679697957416c594c6e764b4f304f3542387841372f6844326647664365666b507048655a384a336c2f77443943664359666c4d443576776e62506a4a6957535154756a68754a7a69647541474a345a536543596767656b6a6333724d4a563765544737737443547a424d5a703853636e32514a366d7277477650744d53496a6d6255315367535a684849572b6b5633316c70505761532b46365a4341466c4e5074424e49494a637855735a73354a6876346655446b435734426a7166326c7067377a46754d4931466247766431457966474b41745149375452556d4f55367947515153564d53474259376a47645a706d72544f507a78504b4c63704d41724d6343444d4a514d6f5a6a784a335231656c4c3763486d634c44754f5246456e6149494b7a4b41517066764e78306a58302f6d3044414d3968346434596c6d6e563248586d4630693172424b7043696f2f456f4d5042346332534f56356c7131555134562b505151695257545163716b6744714c526d616d6b486b395a5631476c7074724a484a6a4b6b72796746373374654b594c413455386478504d5055564f444c5531696c4f46756f6272365246746c6c6d4157504862744172566732534a58684370636b4b496c714d3166764c62516267473352713236332b6c74526554316c7658363679374366385232684f7a38394b3668544b4a49755277384e3055744a57567572336a764d36747435494861624757744a4e6a655059565831574e6c547a445a57416c336a46334a37474c6e775559344d3037452b7a474a334764695353596c535a426e69706747706748765376336a4a436b394a4343424235456d655045795a374c5665425269535a42457a574f56705334655046654961687a615542346a674f4a6d31596734796d4b2b573262544f4559345976534d2b3452366961436b584672515738387a6d45635356523766523267796b346e7379593134633175487a4f5663385253716450424c67454532484b42747459647059727154755a526d576f6b454b534c6c2b4a3443424747584851787675746e6769444a6e384a307764556e386f56783259797a676e725750764b6c31696438396667672f6c416e482f65454550617638792b6a576b6b48745a6836676766534352567a6e6359466d2f474e67456447656b4a4f705952634c71424d2f79324a6c6371615341517a486e417151526b5357727763476352727169356a7a694c6d665361612b424b3664524b68654a5943545a67434d707447453934634962355756356c5262393345794f4f314f56516d494c4b4233657350303165564b4e306c5478476b5053564d324f796332525555637a4974357372763550694f2b33573468566d67596b7578776567486245384e2b6e326e616676453663626c7a774138792b357653452f7047704a50454778646a6254482f414c50634f6b66747170696c4570524c556f4a554e46756b5a672b384234304e4859702f2f5a3245316631397830336b6f666e3870714b7a445631436c4c6c5453512b69777a6372525566537465533162452f4f58394e724b36554164667446502f782b616b2f69424e394346666e46532f5457566a4a6d6a2f384151715966302f32673150517a684d496d417251506459702f4f4b6c6f554a6c65444d7a5872573444566a35792b6254544151454a5a4c5863683338345147556a322b737954572f59522f676c424e4d7371556b4e78422f4b472f38417a72374638327267534d67657930304a5169576c4a49426d5a577a4d48626d59334c37456f6f555763766a39344e61466d4f4f6b477035747a47526f3379544c5472504a31615259474f314f745a4f464d6c4b51657372546943754a696950456453502b52686e5472365366396f712b614450696d6f2f37516630792b6b757071306c567a466e512b495774614e7869374b4142784a314d356c5133573237627752306731706c59776c4c63435054364f3364574a5659594d6e467a4d436570304a674547415a426d42326f6e4d73783466554966314c41787050457930716665474d6e456c4a6f384b6e36526d33704c494d306c4375384a303346736c6a78484e4f755056614f346a6956584568694a376d7258743169385158594873444a6f39365a53757170716c484d7339773551316e35326968347271636e43507973317448554658326c363877796e71436b417155533767586538577443746d7a645932535971344c757771786375726d676b64706f6434455a583672554b785574304d76436d6f6a4f3243544d536e66503643462f723776575746303150704a5557495463347a4c4a484330485672374134334869446270367470326a6d4f4b75705374436b422b383179546270476c667156644369392f784b465652527735375169686f6371456745734f664f4836656f72574144453358376e4a4d346e557a376d38566b546965383359346c434b73704f7347617752464d777a67773671786a75684c376f46456270462b576f4f5a6d712b63344d58616c775a543164674b474c38457870644855496e49334f4644356b485552664b6231496e6a6451634e4e506949524c6e706d536a2f4148656f377956664b7333496a4e6464366b64312f615637686c515a7374683652557853386a71413149346b365253324f32646f3752326a4946626b2f4154706d4559637043564f7764524d615768713874446e75594c32637766474d45564e4b534a6d566e3350393444574948344a6c7a53363056412b7a6d4a367a5a42536d4b5a774234356636786c666f30586a4a2b3062627256735572742f4d6f6e37477a43484651482f4149663678413071676466784b4f5a714d436b47545435464545676e546e654e50545765567053765538342b735555793871716753784e6f78745458592b47655861384467536c4b54357768554e61353959776b5375644d524c3936353451726258587a594d6d4571765a377653437278646a5a41626e48667151446856474939644a6b636d5356697957447939594e7452557777556b44534e6e337064496e793148756e4b72676449577464544547763254467658596f39726b516965376930647243537935424269557869473045397536593166444e5379657730723356353545594a56487038385a6c51796137415151345744336d647833414a637739704d55516b62685a2f474d6a5565486f624463787749366f623243784a545958526c575873796b664d46462b75735531716f5a73484f4a6f766f7971354857573472674a706d556852584c4a7364344f344674583478553853384e4e47485535552f695545737a77596668694a6848754b385152474e2b67314c484e614837513934376d4f70435662784776706448724150615445577a724a564d737161326c2f474e2b6d68776f7a326e5675464a6d652f737163354a547165496a7a7570384b3162755843352b73312f31644f414159594b46514b48536371626e72476c587062554e536b634b4d6e352b6b516231496242354d5531594a55705242446b36686f784c3363327337416a4a376a45765645425141656b587a5558697365737471654a394946344a4f5745357a78475567586a5371484d7076306d726f5a583461656b656f303666306c6d486333746d63536b37423153304261706b71576f6e2f44576f35674f62413335526c6d3274654d7a32442b4a707649414a487232694846746e3671544d4b444a577069774b41564258416872785972777748786e48574b3374417a784f7a395973503243303362385475456b36414a55784f75345157304a316b5072556267474f6349396d3036616e2b385452546b6b736c6773734e3579715943464e71305667424b463270797041475a6974744e6b35394850374a6a4e546c436b72516c544546396542746f3861656c74467162685050366f2b3049667358554a6d705868382f75352b394b4b72464d7758595043645568552b597631694d3547444f782b7971557152544b544e526c58326967656558756739437a786a48584c546577504b3853797450394d592b4d333875656c7459324b7458515642426c646b624d394b776434672f4f71626f524f326b547a796964796e306e5435687969505948704a356b46464f3869464f2b6e4876455351473751657071305a536b582f4144696e6672394f617971637831645437736d4a4d5172444c4444336a763452673261697972672b38652f704e47696b57484a3652504d6e4652636d2f7742596f737863354d767167515948535249674d77737a347074306a737941655a4a61574159752b764b4873716841564f665763446b387775546961304d6c517a446e723577354e52596f43754d6956333079506c687847744e4e536f68514e6e76794d5071565461726738452f615562465a5156496a7843626152374d44496d575467794e584e79334a30694c5832444a685670764f424d746a654a6d5963723930526861725747773752306d356f394d4b786e7646394a654b31664d74577a6259596b646b6b4f2f58375236505441655542504f616b6b326d46416752596c65554c56416b77674a537063426d46696642556449784c554b6778496b736f4e69416573515556756f6b686d48517852744267737673315445444b704964686f527674474e346e345a543554576f4d45632f417a53304f74733877567479444d6e4a3936504d306a4c69626a3949796b5271494a5565624f69542b476e6f493954534d566a35547a317839737a41346e6838677a5571375359685934483376412f61504a6b6f4a36536d3237595151434946503257714656516e497155706c35584b6a6d55516f48335568394f62786f4b796e6e50316c63366b4162647630682b4b794a435a387563745579624e53775437796b497332645141596449744d654e78357a3963514b677847776344392f6c474748536a32717770556f6f315163336675412b5a4c4e71397846634a5275494f4d794c5862594d412f486a694d68504b41784b47757a6c6e2f704256586d6e3253526a393557617062446e426d595849714a6c535a7976325a5151507778716d373275486363594839635762646e365333354e4972326254385a5473786947494763453164506c43697179556a4c7864306b74347846744e566c674c4145546d7270386f37546769626f6f53513178447a3466533634516b544c38786765524b707449746e5363335458796a4f3148684f6f5162717a752f654e533943634e78466b327249336d50505757575a7743524c71306779686463654d4c3332482f6b5977554430673032753578327774316a6c6f2b454c7775626d462b4c2b55584b416f41725047546e3752476f58615942584c7a4c4a423377753167584a4573307274514177646f58485a6b6e69494f4a4a4679334b4a786d5133417a5043327636654a494742677965656b677543335a684357346655464d774463645962575172444d58665747512b73313942564833483030365236767737565a2f7045386a7038706733316638344c74444e6158314d48346b354655626f4579387945795a486e51334d3941717779686935564b393031744572384e4d622b6e507343595634397379307a4446674756797372584e69444a416c4b702f4b427a437850555468776a684949684d73386f4d5144435a66534445417a366f6c7055677058377044486441326f6a6f56666f65734e475a57425471496a6d624e5379586c4c49354b7636786a4e344c566e6455325067655a704c346d34347345585461636f4c4b444552536174716d3274316c746242594d6962436c4863543045656e7248734359466e766d63543272326d4a5758414574495945574c386a764d65664e597462474a3671756f56727766764757487a5a6b69556c64544e43625a684b64793544674b50324556646c59733435692b624f3331694b767261326f56327165316e494937675233555a527145376a6f3238786f4e6353634d635174744e593445337545347968614a6333493361416c6c415a304d4e47346a534b41735a4c446e6e342f77434a55657663764278447039524c6e4579794170444d584c6133596339494332313262326362666a31674a57794463447a45754b564b5a4d79584a4341417339785263693179432b396f42382b38414d43574b327970596e6d4d3661736d7043696c4353526341456771355874427063773667524c6f6a6454446154464173417671416238393357454a7258573359547846767073646f77773270576f32515266586333326a663074317a734f4f5057564c366b55645970326f57424e7476414a36336a433863526631655637675a2b664d3050446754567a4d394f7159793153617131786456566b5755716c75756d614c5a756f42415339322b736375304f4d2f475a4f7672494f36555470624b4934474b68344f44476f3234417a784974724545643578497a4c454a6530516f796343435474356a4f524b434158627975656b61315641714747354d704f35667041617041444f6563556456574662325a5a724a50534272496545674757414469565a2b454669486a316a37445a6853454633414c50794d58744c593164794f4f6d63452f4f5a6d6f554e75474d52336a6c4c6d6b6d784b724d33363452366e58564236506a326d626f724e7433586a764d544f70566733517279663652357a79484236543061576f662b51686c464c5638717649785a7252765178467a4c36696169687367576272473170736841444d532f6c7a4c544675567a4b706b635a30485841795a4f5549366359644b6778466d4649686767525674564d615142387968364f6674475034355a74303250556a2f4d305044567a646e30457a32485636304b736f74774d6564306575757163633865686d74714e4f6a72794a6f4d5254326b704b78725a75687330656b315946394b324472782b5a6b36636d71776f59376c426742796a57555941457a6d4f53544f625972374f714f65704b3569706849566d56336c414c666351506448384c52515854765837724437545262576c786870666957795571657347616f5a557379556c51647443705770614b67304e694d53434930613442646f7a435a7544706c793053365a53554a51536370555759334e7943626b77743942617a5a3343516d72555a3343496c374a7a753137564578414c714a54327138727133674d574f756e47444f686372743341664845594e616e63475853396a35715a71567071585131304c4b6951514c424b67413665734366447979344a4766574750454648474a6f55344d566f416e4c537061584b564a53653654597335345767763841357551564c63664b563231677a6c5668314c6843553672556649517866444b78314a4d533272627349525459524a526349633639346b2b68734973562b483661733543632f486d432b737563594a2b30504558506c4b30794f30307a38552f776a37783548786f5a31492b512f76505165476a4e50316d5571703055305762694b496e724b694c6c615337586751765a7a6153574a776c356d554764392f53466172525765587637544f31537061577242396f63346d33784751433077454d7258724647785273446a763841764d71697a48394d3952415859633453566c6a475a494c4976455978794a425547586f716a5948646f64344a69777572744334697a554f534f38436e7a435362332b734b4754795a595251424b46477a775145594f7347556f7730434f41426a58425a3667447742423859344d794d436f376a39355331614b534a735a733872596e796a3162327661636d656656416e416e6a515369546d58537844314555786e73346151654a7979754743435a5773524a6b536c535947544c4a5359345344444a516867697a4355694445475a6e62476f64614a592b45456e716450703678356a782b334c4a574f334a2b7332764371384b7a6e767845644e4b5570514351535447445657316a6855366d61646a7171354d336c4a54414a43543849486d493939545346514a365479396c684c46683368735759695a795959535979437a54416d45494f565175474a624c4d515a4d4951596b534443705a6867676d4579344b435a636d4a677955644f6d4132786e4e50496652496a793369677a7154386850542b466a2b674a6b3669644664466d7370697171584674424868346a6d306b7855354b7053437051754d757238343061634f7072395a6e367578613346784854764f71374d5975466f3747625a546147782f35456559314e5270596b653665735872744d51526456306831584c794b626a6f6555566d553965305455336d444d6f4333313352787a67434d4b3436547736504553652b4a47594949644a4b77655972647567774931524b657a4a50644250495177656b5a7541484d6e6a4f4c4368706974545a6751514f4b7477686d6a52373777692b7566744b4e6a492b36787664416d6e3246784372724a435a382b584c6c49554155414f564b5364464d666442335236394b5750552f695946723170776f4d645656644b6c4b7972566c504f417375717062447961364c6256334b4f495854565346447571536568697858625734396b694965703139345364514853594e2b566731384e41304746715978684a4e425263675578306d57796b784967457771574959494a6c7346426736354b5661704236694550556a2b384d787975793944493039444c6c6e4d6849436a415536536d6b376b554179624e525a594e7248694579454d354a3169796f696d50595336446754477172776438494a6a63536c645349456d5342426a566a63353658696e647136712b4d2f614d48786c6b69714234673844614170316c567077447a3859653350534d4a4b3475434c4d4e6c4b6867674746494d535a457553596d445056474a6e546c4f31566346564579347357386730655931514c3373666a50566149626146455147636b6e33684377704861577a5a6954566879316830533569787843464e3573304f524c4d5a49346976315335786b52662f41476f71696d6f5747544d34472f6433754e30574e4e6c7a7558744b757231414932487642366e487338307a456e4b5358747836784c6166494f37764e6a53586f61677332654162625331415336674137733336306a4d743062312b364d723664783870573148682b54766f4f443654544c70305441465346416a6738556e72512b357838444b433250576358446d4454615659315359723753447a484c6168377a785649734a63704d547449353754686368624759757843656d57414641356a6f4859414456524c473057714b5664647850796a307978396e7044384971705754744a6173366c57613975567836777730324b3453735a4a2f4572366b4f54682b414f387a47317545544b6c5865424947362b7362476c307261595a4858755a5665354858594f6b364e7374504b3649496d6f556b70514a62683067355179536b6a776a545137716a762f414d544d74585a634e6e7a39597658686931414a6d454b4a4e684e4f623145597070744a77782b357a4e49586f4f55483234692b5468544c4f524f56516538755a2f38416c5835516e79337a676667787a576a626b3950694a424f4f56556d59556471466b66424e4253534f522b384e545633552b396e487867485430326a4f50714a6f4e6e3857375a5066546b574e5575376444764561326e75577863695a757071324750456d4c637047664e48515a5a4c45454a42684b424442426e737778786e41534757496853514554496e75614f7a4f784a70654a475a4278507a314932743578547a675a4d66784e4c68633155305a70687970345063395979723955584f41634344753949306b34784a43757a513167484930443241697635546b5a78784f7a694534696b4b515435627278557355715165386651324c467836797a424b764d6b4a4a6453517865373844486f644c6476584236694f31564f78736a6f59396b7169364a524d4d6c7167344d75426a704558375159716d6e6b4c6d712b464a59635475486e414f323153596461466d774a77366674444b556f715654676b6c79307851756464305934706339542b502f5a7067754f41786c4a787847694a434576764b6c4b2b30534b477a6b6e38526d57376b796d72784b73794d61716145365a554b494464486933765051774555446f4f666c4d784d53536f6c53696f377964596343414d41596e5979636d56396f553647437744445777316e4b6d576f7846745945305a6c705045736459307737614f5a4b4c6f6d45654e6f715861464c42375379387669434f4d4e677a53557674476e70444b79713536526e50344a5554786b53442b6b626b6a48316a6e4264724b36725745534a475a2f6975456a6d7052734957664245486377436445764a6d787173466c464156556a7456674f72765a5538774568586548574c436152614b2b503336664c347971757163747471396c666c6b2f55346e314a58304d674d6c4f556677355430484743717453733864344e314f707539342f6d4534586a744a4e554742556f757956626d6535334e5a373859753161744736695662644461673677446248616a75396b6c696b53387856635864303552344d5030494455616e65504c486353786f744673506d4e317a3069576a787061707374316b686143574f3559595036786c6c327753542f41417a536568647541503841556b617735315a534845785855414b3536776c6a6779565546526e306a696f5569664c4571636a7445376c5842535476536f585365594d5771377a6a42356c46745067376c344d58304f7a6b2b5650544e6b5648615377344b5a716d55452b544b385976615656795375424b6571633441594762796d6d6c727435677870444d7932684b5442514a6167775167776843784241794d5352554e375232665754494b576b6278486353655a3469596b32426670484445676d58705242625a473654676f4d2f48324165396d55624452347a395837753051324f5a302f5937446c317855564b5569536767416a346a7641504c377851723034543577314759377865676b495769544b427a6351587974764f2f77416555534c4e705070445963534f4d596769566c6c46546c742b2b4d787a356a3541344574364b724c62765344304f4970536f4563515044662b7555584b58324f444e4332766568426d79703538626f6d4559624c6e515547657a71314b41354c52784945344b54306e4f7474367064577951534a615334484538544647357978343654523039595163395a7a2b717764514a597643777848555377563944424252714633696434504537423635684b35717967704d486b596973484d54726b4639494d4d4a50496b42534b55575341384547456b6d433156424d4771596372724b74696b396f4f6146513469476559496a796a326e564e684e6735637557696f7135437038315756534a4c6b424358634b576b65397573706b377278527531682f344c6e2b38753036505076766a352f74382f764e74696d49564a644f58494336636853434d756a5736376f7937645663784f345948397071553662546744427a6a764d2b6e47464a555173464c427371526c306469783636394f45563978366a6a35533735494939594855666a506b555833766f62767075674e78552b304977444555307463554c633930674b44645152393473374f505a677467384744544b307a69684c4e38475939304d6f7348365135617476654c5a2b75424e4c67714b564153705578552b614c70374a30792b384576646e494848653268694c4671544959482b666d4b335857594b34412f50382f6d593756585534496c696e546d57626c31472f654a4a4a5967576a7132705959326a37784c56334432742f543452665337543037744d6c716c416b70634b7a6431327a5a566435516439373267664a724f4d4167656f352f6e336a436c6e58494a3944782f5074484578616c7975306f35676d68766773744c432b6157653976335045765136446457632f672f48694b57786432323462666e302b2f53513263783161335250516866654b53644344755a68793561776465735a573274794a4770304b466479635254746a696332686159387855685249436b5a694548636c5a3361324f2b4c394c4733335a6a32714b2b737a69506157503841556d65735038743472656b766c2b307366504d506e4862486b626c684d7232694656686e3854486257395a47524e44732f6a676e712f455552774477536963526964477732616c4b51414142792b384f553469574759787a43447a467a334e485a6e54386a3074417673786c5353385a62334c76354d4d546f6d41592f4e52536f703664444b4468536d304a3339626b7852757349626b38666d4f42414559796c6f705a526d7a5338772f4572556e63497074596247326f4a79716247784d665659714a363146652f516a64776979744a72484532716746554b4a39496e4b53514354784233486e453751656b65446a724e6c543751454451734e3573504d7873687354424b5a4d45727474564a736c767248467a6a69534b68336964574e7a5a686463776e6c7046473172637937556c6549544a716e393554776e7a6d4573436c5a5971584c4e7a4243776d54355945446d346568576a743168675977646f6779734942304b684862794a336c715a576e4241787558356942336d463559693666686842736f5135582b45553966786e706f465a676d366c6b734141547279347730474a496d70326232566c7936684b71755a4a42546353737757724e78574233513355394c5171793156344c527155574d4e77517a5434786a534f386c4532616761686747567a7a4175664f4d367935474232457a516f307263466c422f6e70695a617272366b676c4d34725164516f6c78753577674d704f487a6d58664b56656969445371372f4b6d6b763841434673354c66417654646f5934313862303664386633456b4d7566517748457045354c726b714f5a4c456a5377632b62665348554e573373754f494e753844324f733878755156396a4f6c6838364170615532556b74336d44614f2b7641772b6f415a542f5572324d77352b50316c6d783245543632596c4d6c48635151537451377165664e566a614848547378774f7067507136366b7933302b4d33633759305347544c6d414d4e4a67317537706177473755785876303771326378644775563135583764706e3671686d53796336776f66437049756e6d3778556177715a6f4c35646734474973785043564b55564b64453149643036545563526233682b7556784c6736386465346c625a74627278362f324d46774b6f6e425a564a4b7379526d655859736b356935444f4c427766574f4f35434376426a6a735a534877524f6d34506941576c5a6d79684d5746454665554a556f416d2f646137446476486e7875564749735850306d625a53526a79327750544f51507647395668464a57535a744f46652b686c494f35785a535834454f446534693752565135335574673963544f756139422f564752307a4f4a6254657a716252725a597a494a4f56593049484835547969327a737657495646595a455479384b41335247366473454b6b305145546d52695063495355714452325a324a306e4363564a53424267785245324748533146494b763049596f696d5050455043594c4547634477524d7673773545654e314a66664844306c3166745a5355716371475576676e6a7a4f364830364f3633746a346d4d465a3738546e654f62527a616c655a52594433556a5166316a61302b6a536b636466574e556865424b4b4f724c77566c6374565752784d72464d6b416b485639344556367167473352756f73396e614f386d6d6f576456456b63597353706a456d435470347757494a50704436615554416c595161484d514e497250564c4b576d444b55726843396b507a444c354d35514f6b47466e655a474b4a346137694a43475435676b6b4c536f734864394769664c4d487a524c4a3949426f485639503677785578415a387868686d48496c704b3569776c53334364424d4961345476414f387446545574327a6744724c656b55353342636e3439424539616f4139326d374e44754372336964484a75664e6f6f74744a396c7072717a48336a6b78577163414130776758594b4c70424a33754c4470423753547949575a6651315953695a4c6d5757556b4a42314a494c4d643463445343387345376830697248786753365256796c68416d6a4e4c6d4a414437693178785a77343450416247526956366943335053557a5171536f53314b7a6732537368335151564943755959705068422b793374442b487649424c44426a725a7a4170696c6d59556c4d6b674f70497a45704e776c494633636e6b48676c7335474d343734475a5876734179764737747a6962584674725a644a544a7930363561416c6768676c53526f4c6161747a7647737570557141415238356a666f6d4c6b737762346a6b546c3156747a557a46715553565358756b6764332f63425933316975364d7777544e4b7356706a4168464c69535641462b367263626a4e75483635786c32306b54515667597a536f54556d586d4c2b386857384b48446b6548555168474b4e6d4577794d7972596d6b437069313730727972535178436e63462b4266526f745757465748666a6a36784e784331346d7737583864636f67676c41576b365a726b48546547486e43374d6c7478366d56517639494f4f7878386f52573034556830736d616b756c647751526655584839596c656d5163483169716e4b74673871656f6c4b6366584e6c396e5579307a45685756627076626942626d3758447863585732737548357759542b48564b32366f34794f50542b6674465536697046724e504e6c496b465a65564f6c4268794253374b426264425536333269746f343745524e75684a7238796f3578314269784f786337506b414375436b33536f626944476d6f44644a6c4d3233673854513462374f31327a724365517559614b346b322b6b312b45374e795a44454171567856663030686755434c4c6b78304242515a394854702b4b30566b77426e4a48427a435455684f636379796c72724b317a6e34784958454a72675a5546775749726643715a34573445656a6b52315446546148786842555267636e6d4779556b6d38454267514353547a4435457143784233546f6d4362433970496c7a545043444d47624c6b646b6e532b596275572b4557576f6e424d344f6577684533594e5962386156666a6d48324d4a7331465334444d426e70474b355061564c32416e422b2f4b2f6d5639307842485055527133723647566a59656550395038416e456343423348336b6d31666a3970665237447a6c48764b51676463782f37667a68794d70377854576754535964735a5479727257705a5044756a30763677372b6b4f706944613536434d7064444a6c2f7743484a5147336d35387935684c61797465692f744f7737645445566673354d6d4b557356435a616c4736676b6c5852334259437744694d38316879574c64655a724a726c52516f516b443752542f415058556f3358564c562f4367442f7955714f3231714f735966464c443051666557443265306242356b3873543853413456754979366342416559677a414f76765059666e2f4d6f32683255705a4e484d584b5373716b704d774653796f355533554c323063395245376b734f46344a6b30367934324466302b55354e55545a6b7770544c6452766b516b4653754c4a43626b6a587a693356574165527a4c39746d426d62716a3251715a346c476372734571434f3475387979677179625a6443325937744c6d454c56355a77652b63524c36746345707a69644a6b55307041434558435133766b426d4679415146576257474b7461384c2b387a57737359376d372f442b2f615a5847396b544f576741796b49564d4369796c4f6f482f4541645076464f3532684b3037587a6e67792b4e61436842424a48772b33667048796632656c51696e6b6f796f415551456839375a696f33556f3854444e54714547462b63703155574f43352b455631324430565548556b79466c752b684b5535332b634d515334393433357770645658594d4e6b48735934432b6e336661487036664b536b624e306c4f4237387851596a504d79754831634d4234784667715833682f626a2b66775131314e396e5467664c4d2b45366a6b7a43744d6f6771624f51564132334b424c4f4e4e335742653754676863486a2b667a39345971314e7138742f50352f714f70644c4c6e424d3655633655754141474b6448436f6531433272356c58492f6235796d6258704a7173344a2b782b557071694679695a5a754c6a6934346a72614b2b466443565038455a586c4c4148694356566c534255414f46484b744944393177435331387966744330596737766f5a6f46414435587079442f4f786c474c303361533555784448737067594f774b62576663374a45466b5a794f524f527472737037694d396b646f524c5169576f4f704c7049476e634a436c4136424e724f30584b626d7059454449506155645870526557594847492b6e3763556f664b6f714131554163723966434e427464574f6e4d7a55384f74505869527064756164616d63645162667a4b4153656a774931365a775a4c65484f426b52355359744b6d614b59757a4b4755767744325068466c4c30666f5a556654324a31454f6830545079584e773658727165416972754d756852316932625367754d734d45556573724645447936686f374a6b38517552537041756f45384243327a44552b735a53554533444e4542524a5a7a3268386952427865597a70704d526963434a3253556a73356375587551684b664a494565593174704e706c75705269654c6d417334647670465631573342635a78783949304c6a70505a7338714c6e30697939724f3234794672436a456a326c69327341574f446a7243323838796f4b50452b635678554f704a2b35683448704c5a4d776746524a5a764d7779732b5570624a78366459444b4351423167697352494e303236776a39572f55722b593861634563474579352b594f4446704c6479354553796254677a334e45356e596e6d61494a6e596e32514c436b485253534350335347503368756d55732f456875426d4b4b4368707141424568425358757255713433314f377a69384c516c75346b6b6a37665152345637313972475035316d53785046706935685543317371514347435162414563412f6e464e334c746b2f7754555370555848387a33684f4759724d566e51396a6c6333664c38586e456269507241737258672b6b6e55317037513546585554657a70434c4675704a6638416845432f7577366c41544c442f774268744a55685379445a6a763841336b685832685a786e4d437844736879357164624f4f49666e70484842475a5751484f4a557647637270576c505a6b6c52336b48666c443733423844446b754a51716658502b766e2f614e4f6c79515165656e2b2f6c4273546f733651704f68736e77305479734c644955526e44434d7174326b71305634586930366d5557732b6a466e3638644e447a68744e72566e63686a72364b37317730335748566b7570526d5351685a446b6161333650303552655570666b6737576d4a64572b6e594138724d646a6c582b7a465669676857596a514b4768505063376352464c796e4462655a7171513668733547507446394e744f4a6b6c67514f3045334b445a676e34796548434a61703679516563592f4d4b74466368684d35692b3142566b6c766d546c7972494a53546346695271486373646252646f6f634b536670386f75396b445948722b59485634756b69796c6742694f38647734456b656b474b6d3659697477484f594e54567972744e4367644d316a7a42744850554f36342b55355850726d4e734e787a737932513558636c4263503038374e436a56337a39354a6164446f4e70795a61536d7041445737366550425163654d474c7246474e30724e70717963375a792b5a6835485565636135416d494330456e4a55377350446a78694e764749652f6e4d47576c522b6b466a45444f5a4e464d6f6c3344384769435951475a70634332517170313053535238792b346e774a3138486a67435a4249453375452b7a674a593145306677797750384179562b55466a48574c335a36545430574630636a334a535372356a336a356e5477697662724b7178795978615861575661375a74586a797574645549636367793955446e624636716b6344465664537037475768555a5561314f392f4b4769394959706157304b4f326d3931544a534c7472724637533648395659487a674166574263664972396f636d523267786154494b554142537a636b37682b5a6978722f4b51624b317965356b364c53586167467a774a524c78644d305766532f49786c5732466a676a7447746f3271504d487170536c4545584461516c367933496a4b3356426777716c7a49517a4f583436636f59753946774245324658624d73457959624141657347504e4a774d514d566945696a6d675a70684355384c416e6b4f486a46746442714d6272446766544d5435315a4f31426b7751315454436d316b6732765a513477612b7763667a6d5038724e59507166326d5978797455745a414e6b4335487a462f7342435332546d6156465952506e4d6e5772556c6d47717236626f73566854445a754f49307777356273345a2f4851422b642f4b457679594c446a457045395341684b4d716c4b4a7a462f6d55705a4437774371434933354c644f304d41642b3050713171457a697275324739677a6a7272435641784a474e676851714c2b462b757068654d784a47444b5670566c4f59653451397778537068647478454f586a7047466c4d746c59366955424c6e4a505a5441436c61626c4c4d78626577793661615869797457436364347471545a375348326832395a665755535a694f31437757446b707543666d486950446c436857527a495730716470456c7331564c4757586f7a6b7671344973664e3463696e5045445542534d7a555673784b6b392b5769624b5541466f574d795663387073447750534848557656375935486365737a52534779756348735a7a4432673745396c2f654b4759727369416c55712b5a4354627571636b6f6578427548336732304b3771483448666d4b5a745150655077346d41714b4661436c5075755069494174727231687759486b7a6c4439466c3071574347576f66374c2f5733724332344f566c6c417a444451365268424b4654456865564e79556a51633236516b326b6e4573667077713542676b75644c4e6b7a5354726f3539494d7134354b79754c5562674e4e5652346b674953436957736744764b5463395842696b775947577775526d615370777657332f504d7875625a356a6549746e59512b36494149367957495053436e4351502b496e45484d336e733732566b6d575a367746714369454a4e776e4b3132336e3651515876495a75303366704547434972784357556c387a694d6e5746314f4d7939527449674a56474e61435a6457456c2b7a486a395952716c506c71497454375a692b5a4d44654d564563464f6e655731553569544773544435456b416d784f352b4478615653787a4e4c53615934334e4c4b57705253532b3155543279683351545a43534139743536786f426d6f543250665034482b59466c5436757a793139776466695a695a364a382b59706141755a6d4c6c516367486d72516563476f415832767a50516f3946434247774d667a70487443465577433571772b6d525065642b4a30454961744d356d6664743152325644366e6a3851796474453643704357626a76302f4d52474142377372703461412b484d6655553774474b545a675364774268566146327750715a6a324b554f444b613361507331646c4c377438705741436f6b5a5363704e6872774e6f303074576f624b2b50553935796150654e37382f44744a34706a786d533356335567714c42793442536b453853517146366a5676637654762f50784761665272552f48582f5a342b6f6947707849695956454d364d6a486b464b536544325635434b684a494d764c537530445066502b5974714b674a6c7a4c756534427a4c5a6a3954355279563578474d6551666e456f534a67796b68774154774469317a313949734546446b5163677769724a4f564575374135315733574830506c41674b764c665351435952686b6f4b576b4779685a4949767663676548704157644f494c4e74454d726c666942797842532b36795135303551757351776636592f6e655a4f5a696b784c6d3479716347374f706a72705a6a626738615170516e694331704752473244596b366a4c557275464f5168375a5667704b692f41724236474650574677594a4a5938545430564f69624b524c55674b4b565a56416c6944765a397a35755265466334774f782f6541574b73546e47522f503532784a5944534b6b71374654464d7a33663467435152725973503049685353342b4d693677574c7537695253735335705541536f584936574c65485856584b476273486952677375444e54545445716c71533767452b542f63454747345571524b4c426c634e4b364e4b5653564a4a423935423374773867336c41616467712f4b4671464a6670316e4239744b705972465a305a6371556849335a57647878424a4a6a62433731426c4662504c5977444261635470687a717953774f385272794135777535685542786b6d4e705a72574a48515462594c55716b68557555727554686b7a473746514c466a614b4b7675596a6f5a654a365a6d507844435a74425079544e434355714769687936634930582f414b717a4c72587958783250662b656b7047497772795a632f5543666f576252386f767a416936646838644f675536675044396661496b792f434b6d5a546e756e756e564a466a7a35486e48596e5a6d6e704e6f6b4b44724252785076422f7248546f306c7a305442384b782b365839494236316359595a6b7178586b47512f5945483353334c3958696d33683966565938616c75386f7235524357416530596669326d7577504c58492b45746165785365544d6e6946566c4259687839597771716954677a636f514d77457a2b497a4f7a56633935724a35384662772f446f386274645a513450576139432b59764854312f7838704f564c6b6d576d644d535a79314a436a32696955673777454267774c324c776f3350764b6a6a48667559746a61584e536e616f4f4267632f552f346746587451584f593930426b67614138414e77677870532f4a355078686a5256316a6a7233694f75784359706666462f68532b6a372b63573071554467786f49555957584a6e646b6a764b75713351517372356a65794f6b59624f35375466374d4a7979457353584a555835364436576975624d636647656631584e684a6d65727071555435695650717056694c335a787a596d486f4d726e3478764f41523643447936307151744f623448416477464261566a77492f566f6b44623168455a5038415053417a367674436b38546d38416b672b716a4542646f4d49475734334e73707463344461614954666947654f70474d45775365414942517164595534304a4e74343771452f5177327a335349413678684f5579637774376e5573472b742f474b2f5673474d37536b315034794573374b377232766d4a752b3644436b726b5154674b5a4b70716330326156456e766d353441456630675175464749772b364150535a6245316a4b5642785a4a6270766a5370424a775a54314462566b714f714b53706a63793954784a75504b4f65734544357a6b66422b3033744258676d596c374b6c69632b684864426365495a75555a35544862726b66582f63657938672b682f6e34682b4e7a796e396c6d356e41576b6c513062522b4f3736774849475231784631444c737550555254695655557a317143743968754f59662b7252774f3770367977696a6141593977544577512f3769334a462b5164754a62776764355675596d326e503345585473613746436c4c573270562f466c4150714868534b37766849793449426b39422b30357674444a6e5430496d716c717a6439675178374d725570494f2b325974794d656e70396b626653656431574762634f38535579316f475336584c33424548596f4a7a413039684337666a47644e6935523252336f6d4a552f494f546145436e326977394a613834594150724e627431693069736f35595433703656416f4351354c6b41674455324f6e4b4371593738514e516746524f665445797450734c6953306853614f597844683871543470555152347862346d6275616670535a4a676f7143544b626c48546f4f756d45644f78424b684e6a393436644d2f696d49496c67356a34434f6b695a4f753273556d386f6c42473845672b63524f6b734d39726c5a4b4c545a614a365033753474754f59573949366469627242506174683839684d57716e58776e44752f7a697a645769436f4d6b4d524e58324d696f546d485a7a556e525353465736694557615a483934523165706450644f4a6d63573244537335704d355573693453526d534730796b4d552b7356546f46427973394270662f7741695a427474514e3865682b76592f695a35654256744f6c534653653152636855733532346a4c37337046432f77397932345456723855306d6f594864745078342f386d5372457938784338795344644a44456563536f735559784e42734f4f4344395a453136456b6b42314865626c3434314f2f76474b4a5242795a585541457973796e57705369623253414f366e71536653476a4151375a536538766142304839357671544730532b4a53575a747833672f7264474d4b7a756d666353387a7531556f7a5a6e61797931764e3262306935706241755662316c69733551447549686c564b677358495065353667737a3673597473674b7a6c4a7a6945537741704a34356742344a2f4c30685a795152386f51395a50457167715570523372575165706258665a6f3674522b30682b426944534a6f424235663139435838494e6c4a34677247695a6a6f494465387a366c676b426f724559626d4e7a67543667514d366c4d2b573435474f636b4c694c4179514a5456715a793475547277495642566a4f42477631694f706c7544774c412b746f764932434a5373475a475569344c66753336452b635354326e416435724d4b41505a7a486343564e5431417a416548346b5a316a455a487845755a33446a34536464574b376b6b2b376d64333063756f65462f4f4256665a4c534342357559706e31766154465a58756576384178443171324b4d77664e796343465473546e4a6c396e54793154466e5652736c49666a385236634e594b72513732335077507a2f414f536e714e65454f4547542b422f6d4c4b6643617559734b6e4a7a4d5841633551654f586634786f725374597858784d31745139682f71662b54553165453141555572377a424e30364546494e764f47674552444d47356969753244724b7075796c39464b736b654a2b304d584d577841377833672f73565a6c566338725079536d536e6f566d353847686769576664314d364a675779744e53702f436b6f514e35417566346c717566574f416b4d354d4e5869314d6b735a3874787a4a395242514a664d676f4d48576d4f6e5165596d4f6b3569764643516b324a69444f45355a74436c31367162655475384f4d41637867784d335070774648556a6343475056512b3336455a4d4c41674e52715949514442467948695a45736f4b716f70315a3545325a4b5037696948366757506a48627047777a61344e3758363655776e6f6c314352765063582f41444a742f77427354775a48496d2b77583275304535684e4b3664522f7742514f6e2b644c734f7252425753444e53715653567141576b3143446f526c583548555144566739592b76554f6e7548457a654a2b796d696d45716b545a6b6858423836664a562f57467453434f4a6172385373552b324d78446966737371416c6b4c4369506954793335547669754e4f366e4a35457474346b726a4134694f5a73625849566563417a393070494639584436326754556d4d62596f366c69323764394f302b71734d726b6f594a6c7135755166704342704b39325a592f576265514f666e4d374f704b74426463676d373930694c586b4c6a414d44396532637350744b716d7555426443303557624d6b2b5033686136666d4e2f584c6a5053567a3856536f616a552b4474484c70797068747245595a7a4b3648454567335675382b55466253534f6b3672564a367833683963674a424b724f2f7743754d5562616d4a3445732b63753372445a7457794f375945754f4b7951772f326945716d54672f366a45626e4d6f7161686d41765a39334a6a34757144524d3879532f65414c5675356447697742456d5568484f3238656672425a6767522f68552f4b6b6c6c61466b674f584f72634e4236525565707247327247506374535a614453384a7135366e55524c54775348504e796453654d6156656d725165737962646263374535784e5868477a794a597548504f476557427a69494e7a48676d61576a777371393146765344416979776a6d6b774d66455142792f4d7759574b5a34336b3055704c4d6a4d775a7a66314e6f5a67524a596d4a386532326f61527850716b42512f79356634692f4a4c744259677a6e6d4d2b326f6b6c4e4853742f314b6775656f516b2f654f3654687a4d30766132727148565554314c652b58335541614d4570747831666445536353614d6255327039493664784f2f7167344548504f2f7048547055754f6b356756614570517053374a5343536457413130764854706d4b7242517638525351506c5459355164353471365748505741497a4442784d7069327a313752474d5364325a6d352b466c42494c4e48455467596d72557357455342494a67356c474a7a496b4679596a4d6e47594e4d6b694a42676b536448506d79565a3555786374584643696b2b6b546d6469624c422f617869456c684d55696f535039514d7075536b7436677833426b63696276412f624e534c59546b545a4372663952442b462f534f784f7a4f68306d49793536416f4e4d5352597352364b4543523677676364494e4e77795772514e302f4932674e676a425959746e37503841796b487261493277785a46565a67796446494870413759572b4b4b725a61517257576e794564695475697964734a546e2f4c41365233507249396e754949763266536477626f5945377657474e6f35784b5637427148757a566a2f632f7742594171653445634c694f6850336c633359757050757a683155484a505547494651394977367438597a49793967616f367a6b6a6f5036775777656b5764552f72484f463742396d58584e4b2b566750534f4e495057514e5777377a5430654270466b7045534b38644974726433574d354f447471664b4742596f7647644a686148736c2b734546674d38686a75503031436a5055544d67305a4b464b4c384c4344327852616334787632327044706f36567a646c7a7a6271454a314869494c4167354d77474f626334685676327453734a5077532f7730394754636a71544853496752496a704d4e6c7932694d77735179584d41386450312b74596a4d3745733751382f4f4a6b542f32513d3d, 3, 1);
INSERT INTO `subtipoproducto` (`id`, `nombre`, `imagen`, `idtipoproducto`, `eliminado`) VALUES
(2, 'subVelos', 0x646174613a696d6167652f706e673b6261736536342c6956424f5277304b47676f414141414e5355684555674141414867414141426f4341494141414331304b4d654141414141584e535230494172733463365141414141526e51553142414143786a777638595155414141414a6345685a6377414144734d4141413744416364767147514141424a505355524256486865375a3162634650484763636c2b534a62386755625932706a456936474249504247427775355356704c704d4a675347426159615a4e4d4f6b435a4e6d4a74503249592b5a79557737504b6270537959506d647a547a49424c48704a43486e4a7253777234677247354f64526348475077426475794a646d794c4b6d2f63373744515a614e6b644352644654375038356d393973396538372b39372f66666e736b624f753161396363446f66646272656f794d6a49794d374b6c6a7759472f585259506d4b5a58352f77477131597248616747566f6150696a6a7a3736387373764a79596d4c426d327a4d784d70394d35506a377563726d4b69347676762f2f2b6e4a77636a4b46516941613575626e59723179355170372b673847676444367259423063484953676f714b697249777369383053436c6c2b2f34632f58727a55415231723136356c41686f624737576d4b74482f39354268566c64584e7a51305a47566c4b624b3642516968646c71686950334d6d54506b6b5264464946562b763538714b315a7939446738374835352f333633323431436c7931624a6a4b38644f6b537770514c5a676d45736a5672316b42786533733735454352564b31667635353849424351596a677730724b6a5178456f46793564757051316a52306a4b586d4661465930375a376176694e2f5869475a38516c2f31594f726d4279574f5933306d5a6b6c594e515141714156796b36644f69552b6b4b725230644861327470706c65667a2b633665505974655a544c676c325a43656e392f2f3430624e327a3069496d4b374e796373624778425173572b48336a734a79646e61334b66336178444743486c4a324773597365785149514a707246434a55594561576b464339637541426a654671787743385a374c3239765679435256453058657a61746575423157736f6a34794d64485a3253722b7a475455314e59693075626b354c7938504c553456484b4b55444645414461433473724a5366414130496d3249626d7472493456306a417252396658314f50372f58726b4b7935703139676b354171787351455a63714d67784844436f5a7a5a76336f785845563868786e506e7a684669514b59655a646d597430382b2f31744f6e74506a385742566e4d57735a786c3064336644444d496b306f426c6e5545646b43696f714b68413057526f413243504c5a524c734a44584c375432396658742f63337a6d444949372b597742666742396a64304c5852446e366962564355746f367171696a7a367855646a623231747059313636535459704955306d734e5549477038416d4531394146646f51422b78536b4463636f7948324b4a6749305a6f385764717563414f57794a5455314e524e5955785245444d685135377146523247747061634576593947706a34435645472f665337395635306f7a7a5745716f413946456f7151586c64426b4363784e567866766e795a79492b71476259334b3376673775642b505564304e474237334c527045344564456f5a69614c313538796252734c43734e626f446c473378715a3037356f694f4872414d616243732b4f7a4a586e7347524e7475446a6f67467a6d54777668646861784469616931374c334335584a784f706f3932796b6a6c6345696169444775324b61694339574f42774f6a70527a4b324e6d3250517a2b7a3244454a4a314a5075795a6a497869425a366533736c4c755a4570316b544435754252785864437a4547795a674b346c6852773871564b2b56316a3854437959487873387049354f32693254436b6f7279386e4d68584d79555232736e64514b427259486933386344746476663339342b506a2b666d3567344d4445532f67786b496d2f3678724c45776c6664677535342f662f3643425173534e4e686f6b436736524e48516a53744d3153624a4c67327a654754324965544d593651774e457173376867595849736e5365614335615a794f2b3762337434756e344249566171517641582b7a445050614c6e454133376e7a5a76486e6f7951552b4b527030494a643752734973466f362b76726b625a575468676b674e753664537642736d597942777734676b6350665649547356564b6d4d7877486e3330305750486a7547677451707a494157784158516e497443574d4a6c643463534a4535724a5446412b79744b7979514b6941775a756a31367656772b546d55574b576f575a59464f2b4135594b734d794e4372626f4a2b5668386c31684778703261646e6b5168534e414b464a3939307841532b4d53746a30696f714b5568346d33785732386f6f4b4c5a734b34454e6b42794d5039574b4d426a51476e45544b793876782b476257736b414c37464d4f38647061345737772b5877776935775274546c66594532467261656e5238756d46437a38614c7832666e342b6a674b505044773872487758566f56575a323759544b5549506234575a78494f4a457a74344f4467794d6749576f5a6c72534a4e59434d6b30724b6d4159524f5061386a64687946772b46493573636942734a6d74684d55674e4f4442772f6973703939396c6c494a344f635366455379592f366a554a536a2b417841566f50487a344d313457466851382f2f444475596d4267514b744c51316a627a7039372f665858315931494d356b456242354546787a7a46693161524772436c5263547a4f673667487a6853672b54355348544a634359466f6e364b4373654947533852476c704b667a715156464d6762594a5965545844654b48486962726e364c714b705a6a75706b5032544d44353579434e36565477634b435844314d76704e4430352f32336c36507042436d59426b673163574c4630635a4a707445484445683964386d35514677767544697859745268736c63496c4770424e70694e446d4d2f774a4e6c49416779504a345048495951636a3338435431396657516e685a63383543706555706f4a6270343964565832653769504f2f743272557256584b4a48716c6857594b322f667633662f44424237416335787369644d317337646d7a7837536e58474474377531353863555830585553664458484543676d6f4e7932626474333333326e575932474f6862545259464a5662514578647533622f2f6969792f456b676959382f56656b6f6a47503768634c6f4a66346f54446877385846685a7146516e41724659304b734e7039506633662f333131356f706b56414f6c435a374d5a4a596f676b474247695a4d4b3634754a68345471744c4a47425a626d7165344339524438487769414677455778396a446c66685661584c504141346b5949534452543670416f6f6947336f4b434151534b756c48387351767a483832694646434568524575594c4b2b4834672b543434664d64326f4462534f4a486830646454716457566c5a6a7a2f2b4f416f7957356831384f424243587530636e4a684a4e473575626d516d2b67774f5534634f6e516f4a64756a41626645556541664f50555255586939336b53487958454348794b68434b6d34754f5441414b4a7866424339632b644f2f49615a337a614541355a78644d6c3857674f4952737645634565504874584b61514a6b6b55776659734364554164454a2b636b6b6e595163706a523545337072415645352b586c7a5246745047536e4a634374714b6859766e7a35344f4367792b576149397034454f4d53323553566c5246396e546c7a787546774b43384470413736675867534d6e71614c6c474571514452363961744b796b706b563974536e68545656576c4d4c747935636f48486e69414f7372723136396e4f325a436c693162746d624e47757879774d4f69646a4b48365946476f5169754547687061536b57696b533951696d5267764a523168747676484874326a5561553863684663467a6a4f3774375a56767361786475355941727257314e5a6e6866546f436c717572717948743674577241774d446e4a4e524d4a5165503334384a79644855545173693244466577775044394d4f63696d4378735a4733506d71566174774f6b79443275636362674f4b534f47337271344f6438773243464834417a5a415958587a3573324b3134372b77316b757070654f6a6737696c546e476463416d5070626c446a4f51506a593268684f474b4f534c5a4d6e55317462534a6761696162786b79524b384f78657a6d6370307a5145326b42336b697253685a57526b4249726e7a352b5077436d32744c5241722b49364b4b447438764a7969556a773346794d3631443775513036756e7a354d72566378527a4f326f434567554d466a433163754a43396a6a324d4a58372b2f486c6f68426b6f37757a7378506465756e534a4e764b62596546545566546262372b4e344f31323556384e3051565749684957676b78444246676a64727564755671386544473961395a5a42706942596e676b526d436a5133616e54703343434930624e3236453861616d4a74676e673135395070397943662f424c466131423058647a4668425155464e54593159496f44587037336237655953452f364c726951414c634c5036644f6e662f72704a39684130596950464d5932624e69412b4841646b413654744e512f787074477330416d51592f6e754978725a476b776a63774549412f6430694474454c49715032444d34383277574732576f50366a316b6443744d6b4b6e356941757247686f59487362467872634f504757744b3274744d6f47727267424839393965726c72437a384b6c3046595a75722b46466378317476766158326464766e77695a7a6866354a345a532b4956706e756269346d464d38553071654a39437553537349793961514a642f682f507a7a7a3153624271566d7567397933534d6568794e484245664b324b484d362f45346e4536707a6374332b7366684c56507449684c544534317a7548486a526c39664830527a4f4b53496c78434253324448546d7243662f77535061775a79726368726c2f725076486a6a794b564d522b376c4471696b44576f576d7771335a49502b674d546f6141394f38732f3776763535352b584c612f306a667673484f697374734345337838493547546c4449304d467863573044686743656d4f416c57544a3532656147594d4b74584659704d2f3277444c796878614c4c445072736f456b4539544f594e414b4d6942776a5069626d6c713172663836725856704545596e6a4973687338786f7175724538317833694e304f334869424c5251704a594e384f544a52756753516b4b686151345a43744837397532624e32396552635639304572305271534e723243764579316a314a70617257317462586750657353657069776a46344a55486834457876316e7a375147417667425a53787261745a4c6d334151494a4d536868476f63533348504d675a476870694a39793061524f643950623258726c7952565134417851536f5a4a746a593434686e424f70314e59727179736c4371686c5477545149596578553870563663745249796949574835546d687562696157355a684744436462464b755a434a6f444e3530514c2b4e4a37736f7955425164666a4c6b5976706c6d656a383470527633727a5a336430645458646d426b4e6a343245345a414157617a445531747043526b5339746e6144326e41535949624775493648486e6f49577476623235314f5a33563164553950543164586c376f776f744a634a4e474c466930714b69715369306d3542306f6e35666d69374e476367474c304b434d53415147646141794947396442417853325973554b2f414d6e4554596b3469744554527430566c74627937556f757147685161597165747a6143473542337046714259766c374e6d7a386b63613070706c655836675578794f69516e746c7a6f7863466865756e51704c7051444e4636597053792f3345315738386d544a306e52746669636d444470416836496d6553755448356a59794f4242336c75482b76736d5151777936364630784f5049526170436f65774e6a6a6f676b316d34754c46692b783457375a737753397a46544566446469785a426f49427a70752f5858496d4b43346a763337392b4f446d4f79536b684a6342373349533477303552656f386c582b6e43347077354555753479497642624142555066662f4e7453556b787066372b2f6b63656677786d56362b754c6973724f2f436e502b666d324874366576507a6e55514b4d494d4534336e6c6f45776d2b7555475656565673457952417946547039616d4b3642795a47514566735670614e6270384f4748483749546b694841526277416a3846476c357472392f6e473659657472375330564f4a64756554656f436a367a546666704c764f7a713742775545574344705061793050447738546f5a49586479466333306e526d5662627156504e566b7549413454484e2f625959342f68736d6c322f4e695068515635355045727449396665597169695a3076584c6a416a5a673055594855705231342b4f765872384d795178426d42634b3156684448456c4a656443695a7a497a756e683461454e51574f765071317463693671416c394d4f2f2f2b5561647666323953453751395a337a4c756e6d554530786f59576b31426f7648506e546e57377368485050664845453854496b50374f4f2b2b77754d764b46736255327778516946596d4e6f376f6a656344754232746e416f51683845797045544a697a356539696635537a534b50776d4664757a5951567a426d6942576565474646374448773077344446413046424f3076506261613134566d6a587845466f424c464f63796f6859777531544c594956717834633953752f4f7833466e507a50386144507a7752633765356158564e442f2b4c723434514252484e32346d6e65666664647773526b666756642b434a534a6850756b65384e48454d6b51322f76766663656e68716c2b33772b7a747947764873776747674578546a78612b2b2f2f2f3654547a364a46676974744c724541482f4b476d496c775934732b516952556f7977414c464971757834366d466162616a386c6977384e58623150424863746d30724b554c6d7a4c4a392b33614d7976587877636a4e6b506b2f637554493774323769556b315532494142544b584d4336575741477a7341796257766e57332f6e5739314b4b654770414d37552b5868684a4e4d45346b2f2f7878782f444f4c754b5a6a55617345796b6a417868524569354b36537835434758366547776830556e326d724e344766544c37663551785a37646961615039643675736a706447526d577533326a5a753376505758762f6f6e34764a4f6b572f766a414a4359476e544c617576714b684939717634495234356e434e6855453846616f316d6c497a6b392b37642b2b6d6e6e34704672626c64425a417a4958505469654e536c436c5a664e2b536f704c35334e51374e6e71366f51454e4d52787045424f4d5648513458433758776f554c43776f4b654d53757269374e476763594e7141337252773759506d7a7a795a39446873422f416c72706136756a6a7a725268566638464a4868332f4d35786e31636d76556732366b63617849464e48736a527731455973515244524371745846446e536b484a48566679616a432f424f304364447a346a6e465a624653427165416554787948444e6758423535556f6c30676a426a69307a4b32507638337664376d486d75724b71617432476a66562f2f384c6a6a666d505143624b6455534135325977375057732f5369334678354a416c6a325053614a6f6c4173715435743455624a36426269482b3549476d376e4d634b6e58497954556e5632416d506a353836644a61764f687358743962413962743279626354726b52443264792b2f394d6f72727a415772704a356d686d4a556e514565427234366c48664b6d696d7577474347414171672b4a6f526a49564f467732353374375532484c7971797265306a2f68675a7a4136635a4e6c76702f424a4559382f4e2b655354543270726138656a2f684e5253564b30446f364f4c452f6f6d2b47335376446f61426b464d62784a5767744c497853745a30676c6f7938494d556f71475946756a436a7147516a6c4c6a6a6f3967766e4e5337562f33453633374e6e7a3444364c53306556546e616545655a674f2b2f2f316170316c6f7051704631724a6153546a5467396b382f2f66525858333246786a6e79617459774d44786d676f772b5a6a326a707a7252494e7a4f3842694c61745973676f687239664648744a466968444854616a766430757a33423941303130722f7a4f48446a2f794b327845706f6d734778644b68534a563838795943535849643463424e487a703069444e6b4f466b43586374614f586167723343616f6f5243384f5372784149734e6d764145717264554e656c2f4f75543230656b4d652f594439392b632b796650797a36525a6b6c454d7a4f73553845417a546d4a32544c6d5079444a5a59766f68734c5842355242424b51514a76444e486c6336745431486d584b4a44333333484f45466c495575304430533059337176554b5a73694846775857594b69317455576b726132495739412f676a6c77344d412f6a6836526779746a3452446e64587532624e6e795039415645516d2f433132544141414141456c46546b5375516d4343, 2, 0),
(3, 'subVestidos de Novia', 0x646174613a696d6167652f706e673b6261736536342c6956424f5277304b47676f414141414e5355684555674141414867414141426f4341494141414331304b4d654141414141584e535230494172733463365141414141526e51553142414143786a777638595155414141414a6345685a6377414144734d4141413744416364767147514141424a505355524256486865375a3162634650484763636c2b534a62386755625932706a456936474249504247427775355356704c704d4a675347426159615a4e4d4f6b435a4e6d4a74503249592b5a79557737504b6270537959506d647a547a49424c48704a43486e4a7253777234677247354f64526348475077426475794a646d794c4b6d2f63373744515a614e6b644352644654375038356d393973396538372b39372f66666e736b624f753161396363446f66646272656f794d6a49794d374b6c6a7759472f585259506d4b5a58352f77477131597248616747566f6150696a6a7a3736387373764a79596d4c426d327a4d784d70394d35506a377563726d4b69347676762f2f2b6e4a77636a4b46516941613575626e59723179355170372b673847676444367259423063484953676f714b697249777369383053436c6c2b2f34632f58727a55415231723136356c41686f624737576d4b74482f39354268566c64584e7a51305a47566c4b624b3642516968646c71686950334d6d54506b6b5264464946562b763538714b315a7939446738374835352f333633323431436c7931624a6a4b38644f6b537770514c5a676d45736a5672316b42786533733735454352564b31667635353849424351596a677730724b6a5178456f46793564757051316a52306a4b586d4661465930375a376176694e2f5869475a38516c2f31594f726d4279574f5933306d5a6b6c594e515141714156796b36644f69552b6b4b725230644861327470706c65667a2b633665505974655a544c676c325a43656e392f2f3430624e327a3069496d4b374e796373624778425173572b48336a734a79646e61334b66336178444743486c4a324773597365785149514a707246434a55594561576b464339637541426a654671787743385a374c3239765679435256453058657a61746575423157736f6a34794d64485a3253722b7a475455314e59693075626b354c7938504c553456484b4b55444645414461433473724a5366414130496d3249626d7472493456306a417252396658314f50372f58726b4b7935703139676b354171787351455a63714d67784844436f5a7a5a76336f785845563868786e506e7a684669514b59655a646d597430382b2f31744f6e74506a385742566e4d57735a786c3064336644444d496b306f426c6e5545646b43696f714b68413057526f413243504c5a524c734a44584c375432396658742f63337a6d444949372b597742666742396a64304c5852446e366962564355746f367171696a7a367855646a623231747059313636535459704955306d734e5549477038416d4531394146646f51422b78536b4463636f7948324b4a6749305a6f385764717563414f57794a5455314e524e5955785245444d685135377146523247747061634576593947706a34435645472f665337395635306f7a7a5745716f413946456f7151586c64426b4363784e567866766e795a79492b71476259334b3376673775642b505564304e474237334c527045344564456f5a69614c313538796252734c43734e626f446c473378715a3037356f694f4872414d616243732b4f7a4a586e7347524e7475446a6f67467a6d54777668646861784469616931374c334335584a784f706f3932796b6a6c6345696169444775324b61694339574f42774f6a70527a4b324e6d3250517a2b7a3244454a4a314a5075795a6a497869425a366533736c4c755a4570316b544435754252785864437a4547795a674b346c6852773871564b2b56316a3854437959487873387049354f32693254436b6f7279386e4d68584d79555232736e64514b427259486933386344746476663339342b506a2b666d3567344d4445532f67786b496d2f3678724c45776c6664677535342f662f3643425173534e4e686f6b436736524e48516a53744d3153624a4c67327a654754324965544d593651774e457173376867595849736e5365614335615a794f2b3762337434756e344249566171517641582b7a445050614c6e454133376e7a5a76486e6f7951552b4b527030494a643752734973466f362b76726b625a575468676b674e753664537642736d597942777734676b6350665649547356564b6d4d7877486e3330305750486a7547677451707a494157784158516e497443574d4a6c643463534a4535724a5446412b79744b7979514b6941775a756a31367656772b546d55574b576f575a59464f2b4135594b734d794e4372626f4a2b5668386c31684778703261646e6b5168534e414b464a3939307841532b4d53746a30696f714b5568346d33785732386f6f4b4c5a734b34454e6b42794d5039574b4d426a51476e45544b793876782b476257736b414c37464d4f38647061345737772b5877776935775274546c66594532467261656e5238756d46437a38614c7832666e342b6a674b505044773872487758566f56575a323759544b5549506234575a78494f4a457a74344f4467794d6749576f5a6c72534a4e59434d6b30724b6d4159524f5061386a64687946772b46493573636942734a6d74684d55674e4f4442772f6973703939396c6c494a344f635366455379592f366a554a536a2b417841566f50487a344d313457466851382f2f444475596d4267514b744c51316a627a7039372f665858315931494d356b456242354546787a7a46693161524772436c5263547a4f673667487a6853672b54355348544a634359466f6e364b4373654947533852476c704b667a715156464d6762594a5965545844654b48486962726e364c714b705a6a75706b5032544d44353579434e36565477634b435844314d76704e4430352f32336c36507042436d59426b673163574c4630635a4a707445484445683964386d35514677767544697859745268736c63496c4770424e70694e446d4d2f774a4e6c49416779504a345048495951636a3338435431396657516e685a63383543706555706f4a6270343964565832653769504f2f743272557256584b4a48716c6857594b322f667633662f44424237416335787369644d317337646d7a7837536e58474474377531353863555830585553664458484543676d6f4e7932626474333333326e575932474f6862545259464a5662514578647533622f2f6969792f456b676959382f56656b6f6a47503768634c6f4a66346f54446877385846685a7146516e41724659304b734e7039506633662f333131356f706b56414f6c435a374d5a4a596f676b474247695a4d4b3634754a68345471744c4a47425a626d7165344339524438487769414677455778396a446c66685661584c504141346b5949534452543670416f6f6947336f4b434151534b756c48387351767a483832694646434568524575594c4b2b4834672b543434664d64326f4462534f4a486830646454716457566c5a6a7a2f2b4f416f7957356831384f424243587530636e4a684a4e473575626d516d2b67774f5534634f6e516f4a64756a41626645556541664f50555255586939336b53487958454348794b68434b6d34754f5441414b4a7866424339632b644f2f49615a337a614541355a78644d6c3857674f4952737645634565504874584b61514a6b6b55776659734364554164454a2b636b6b6e595163706a523545337072415645352b586c7a5246745047536e4a634374714b6859766e7a35344f4367792b576149397034454f4d53323553566c5246396e546c7a787546774b43384470413736675867534d6e71614c6c474571514452363961744b796b706b563974536e68545656576c4d4c747935636f48486e69414f7372723136396e4f325a436c693162746d624e47757879774d4f69646a4b48365946476f5169754547687061536b57696b533951696d5267764a523168747676484874326a5561553863684663467a6a4f3774375a56767361786475355941727257314e5a6e6866546f436c717572717948743674577241774d446e4a4e524d4a5165503334384a79644855545173693244466577775044394d4f63696d4378735a4733506d71566174774f6b79443275636362674f4b534f47337271344f6438773243464834417a5a415958587a3573324b3134372b77316b757070654f6a6737696c546e476463416d5070626c446a4f51506a593268684f474b4f534c5a4d6e55317462534a6761696162786b79524b384f78657a6d6370307a5145326b42336b697253685a57526b4249726e7a352b5077436d32744c5241722b49364b4b447438764a7969556a773346794d3631443775513036756e7a354d72566378527a4f326f434567554d466a433163754a43396a6a324d4a58372b2f486c6f68426b6f37757a7378506465756e534a4e764b62596546545566546262372b4e344f31323556384e3051565749684957676b78444246676a64727564755671386544473961395a5a42706942596e676b526d436a5133616e54703343434930624e3236453861616d4a74676e673135395070397943662f424c466131423058647a4668425155464e54593159496f44587037336237655953452f364c726951414c634c5036644f6e662f72704a39684130596950464d5932624e69412b4841646b413654744e512f787074477330416d51592f6e754978725a476b776a63774549412f6430694474454c49715032444d34383277574732576f50366a316b6443744d6b4b6e356941757247686f59487362467872634f504757744b3274744d6f47727267424839393965726c72437a384b6c3046595a75722b46466378317476766158326464766e77695a7a6866354a345a532b4956706e756269346d464d38553071654a39437553537349793961514a642f682f507a7a7a3153624271566d7567397933534d6568794e484245664b324b484d362f45346e4536707a6374332b7366684c56507449684c544534317a7548486a526c39664830527a4f4b53496c78434253324448546d7243662f77535061775a79726368726c2f725076486a6a794b564d522b376c4471696b44576f576d7771335a49502b674d546f6141394f38732f3776763535352b584c612f306a667673484f697374734345337838493547546c4449304d467863573044686743656d4f416c57544a3532656147594d4b74584659704d2f3277444c796878614c4c445072736f456b4539544f594e414b4d6942776a5069626d6c713172663836725856704545596e6a4973687338786f7175724538317833694e304f334869424c5251704a594e384f544a52756753516b4b686151345a43744837397532624e32396552635639304572305271534e723243764579316a314a70617257317462586750657353657069776a46344a55486834457876316e7a375147417667425a53787261745a4c6d334151494a4d536868476f63533348504d675a476870694a39793061524f643950623258726c7952565134417851536f5a4a746a593434686e424f70314e59727179736c4371686c5477545149596578553870563663745249796949574835546d687562696157355a684744436462464b755a434a6f444e3530514c2b4e4a37736f7955425164666a4c6b5976706c6d656a383470527633727a5a336430645458646d426b4e6a343245345a414157617a445531747043526b5339746e6144326e41535949624775493648486e6f49577476623235314f5a33563164553950543164586c376f776f744a634a4e474c466930714b69715369306d3542306f6e35666d69374e476367474c304b434d53415147646141794947396442417853325973554b2f414d6e4554596b3469744554527430566c74627937556f757147685161597165747a6143473542337046714259766c374e6d7a386b63613070706c655836675578794f69516e746c7a6f7863466865756e51704c7051444e4636597053792f3345315738386d544a306e52746669636d444470416836496d6553755448356a59794f4242336c75482b76736d5151777936364630784f5049526170436f65774e6a6a6f676b316d34754c46692b783457375a737753397a46544566446469785a426f49427a70752f5858496d4b43346a763337392b4f446d4f79536b684a6342373349533477303552656f386c582b6e43347077354555753479497642624142555066662f4e7453556b787066372b2f6b63656677786d56362b754c6973724f2f436e502b666d324874366576507a6e55514b4d494d4534336e6c6f45776d2b7555475656565673457952417946547039616d4b3642795a47514566735670614e6270384f4748483749546b694841526277416a3846476c357472392f6e473659657472375330564f4a64756554656f436a367a546666704c764f7a713742775545574344705061793050447738546f5a49586479466333306e526d5662627156504e566b7549413454484e2f625959342f68736d6c322f4e695068515635355045727449396665597169695a3076584c6a416a5a673055594855705231342b4f765872384d795178426d42634b3156684448456c4a656443695a7a497a756e683461454e51574f765071317463693671416c394d4f2f2f2b5561647666323953453751395a337a4c756e6d554530786f59576b31426f7648506e546e57377368485050664845453854496b50374f4f2b2b77754d764b46736255327778516946596d4e6f376f6a656344754232746e416f51683845797045544a697a356539696635537a534b50776d4664757a5951567a426d6942576565474646374448773077344446413046424f3076506261613134566d6a587845466f424c464f63796f6859777531544c594956717834633953752f4f7833466e507a50386144507a7752633765356158564e442f2b4c723434514252484e32346d6e65666664647773526b666756642b434a534a6850756b65384e48454d6b51322f76766663656e68716c2b33772b7a747947764873776747674578546a78612b2b2f2f2f3654547a364a46676974744c724541482f4b476d496c775934732b516952556f7977414c464971757834366d466162616a386c6977384e58623150424863746d30724b554c6d7a4c4a392b33614d7976587877636a4e6b506b2f637554493774323769556b315532494142544b584d4336575741477a7341796257766e57332f6e5739314b4b654770414d37552b5868684a4e4d45346b2f2f7878782f444f4c754b5a6a55617345796b6a417868524569354b36537835434758366547776830556e326d724e344766544c37663551785a37646961615039643675736a706447526d577533326a5a753376505758762f6f6e34764a4f6b572f766a414a4359476e544c617576714b684939717634495234356e434e6855453846616f316d6c497a6b392b37642b2b6d6e6e34704672626c64425a417a4958505469654e536c436c5a664e2b536f704c35334e51374e6e71366f51454e4d52787045424f4d5648513458433758776f554c43776f4b654d53757269374e476763594e7141337252773759506d7a7a795a39446873422f416c72706136756a6a7a725268566638464a4868332f4d35786e31636d76556732366b63617849464e48736a527731455973515244524371745846446e536b484a48566679616a432f424f304364447a346a6e465a624653427165416554787948444e6758423535556f6c30676a426a69307a4b32507638337664376d486d75724b71617432476a66562f2f384c6a6a666d505143624b6455534135325977375057732f5369334678354a416c6a325053614a6f6c4173715435743455624a36426269482b3549476d376e4d634b6e58497954556e5632416d506a353836644a61764f687358743962413962743279626354726b52443264792b2f394d6f72727a415772704a356d686d4a556e514565427234366c48664b6d696d7577474347414171672b4a6f526a49564f467732353374375532484c7971797265306a2f68675a7a4136635a4e6c76702f424a4559382f4e2b655354543270726138656a2f684e5253564b30446f364f4c452f6f6d2b47335376446f61426b464d62784a5767744c497853745a30676c6f7938494d556f71475946756a436a7147516a6c4c6a6a6f3967766e4e5337562f33453633374e6e7a3444364c53306556546e616545655a674f2b2f2f316170316c6f7051704631724a6153546a5467396b382f2f66525858333246786a6e79617459774d44786d676f772b5a6a326a707a7252494e7a4f3842694c61745973676f687239664648744a466968444854616a766430757a33423941303130722f7a4f48446a2f794b327845706f6d734778644b68534a563838795943535849643463424e487a703069444e6b4f466b43586374614f586167723343616f6f5243384f5372784149734e6d764145717264554e656c2f4f75543230656b4d652f594439392b632b796650797a36525a6b6c454d7a4f73553845417a546d4a32544c6d5079444a5a59766f68734c5842355242424b51514a76444e486c6336745431486d584b4a44333333484f45466c495575304430533059337176554b5a73694846775857594b69317455576b726132495739412f676a6c77344d412f6a6836526779746a3452446e64587532624e6e795039415645516d2f433132544141414141456c46546b5375516d4343, 1, 0);
INSERT INTO `subtipoproducto` (`id`, `nombre`, `imagen`, `idtipoproducto`, `eliminado`) VALUES
(4, 'subcategoria4', 0x646174613a696d6167652f6a7065673b6261736536342c2f396a2f34414151536b5a4a5267414241514141415141424141442f3277434541416b4742784d54456855544578495646525558467867594742675647526359476867564678675846786759474267594853676747426f6c48525559495445694a536b724c6934754742387a4f444d744e7967744c69734243676f4b4467304f477841514779386c494359744c5330744c5330744c5330744c5330744c5330744c5330744c5330744c5330744c5330744c5330744c5338744c5330744c5330744c5330744c5330744c5330744c662f4141424549414c634245774d4245514143455145444551482f78414163414141434177454241514541414141414141414141414145425149444267634241416a2f784142444541414241675144425155474177554842414d4141414142416845414177516842524978426b465259584554496f47526f516379516c4b78775350523842526963704c684653517a51314f4338574f697375495746384c2f784141624151414341774542415141414141414141414141414141434177454542514147422f2f45414455524141494341514d434177634442414d4241414d414141454341414d52424249684d554546453145694d6d4678675a4768464c48774938485238554a53345255474d324c2f3267414d41774541416845444551412f414d7474564d70436c36554b54794c333834726b4b4f6b3231563859615932615669366b6b43497744423375766150396c3866374146505a68626c3778773468627438366c734c5879706f6e4c536e4c4d6357505451637252352f783273756759412f5356745172444531435a6741334a50336a796c6274572b3453727965444173577870556d577857644f4f7362766839396f72334d784f65677a4e72773351693342497a4273436d4c6e6c306a753645376e6a54727161346343584e614b394f4d4d655a375834736d564d4b474b696e577841383471585674573241527831457833317964414a344d655774786c4345745a6937774a31566a2b7a306c76533037795359745146546c466c4d334837515172475a736b725376496a50434e6e564b57366c6430616e386f75616653372f6c4b657138525656776f3568474b556557616a735a68546b31476f55434e44465055304c6b68656b6f344e39594c386335346a6243616f5a77564d5874357772773076527168356e4950457058304d6f6a4b726c6a4f6d316e6a306570787555343469716d4951387a3275546f77386f7236784d344145366b39637779696b7079677466664676535556697345446d49746474784575374250434c506b312b6b58356a536551634950614d51636d664a544841596e5a6b4a6b6b45756672484541795178485352374162767245625a3236536130646964506b6949356e4577657471784c316a4f313276476c484d4a526d434978554b30744753504831626a704438737936524f49336778646f31787879774d67726d4a73576e46697037336962624365535a7155494f6b7a794d515754547471736c34554879424c5a72484d61794b78514f762b666c384e384e4668422b7351315950326d6f37646679786f2b5a622f316d567372395a3932362f6c39596e7a4c662b736a5a58367a3056432f6c39593450623654746c6672507632686679657364356c762f41466e624539596b7238476b7a566c537052633674465236797a5a4b7a527031747453425662694b6c374e30543353727a564659314a36533650454e586a676a38526257344268456c4379716c55737047674d7853695275417a61776466695662326557464f5a532f724e6a6b54525565413046564b412f595542414675316c736f644876476b6a4c61505a4248346c57377a4b44686d422b41356d573267396d4e456f6b79486b4c4734456c4375524230384951316751344a7a48314f783549676d7a6b71584b6d2f733661594266787266634e37373456714e6256585675504d6464555647356a394a704b36596853776b4d3664786a7856746a574d624d59426d55374174695a62614b586d544d563369744a444a476b58644932436f375463384c38554b4555746744316a5859476575656a7345756e4c645a34506f49336835747169696f343954384a533854744675705a7335416a6e4556796b5a6b7159735343654d592b713051724a77636d4e703052745559455531654444734d386c544b5663412b363332696d75703273412f31693637374e492b4d644f786e744c6855384a6c736b4f64534459635359304b624e337444704e63363668777a452f536153686b4c6c4a4a5776753644393478704c66354e486d4f6341394a6b36692b7479416f356d64704a6f6e31716b35694141535735572b38554874634c6b5459755536665268694a70714b6c534a71436b676748667a7439346e773355423951417842394a6732616a7a454961614e6373486848725755475a3462456770436e335173725a6e50454946635361656b4d475232676d666545647a365476725066434a35394a45394554496c526759633953576a675a786b79494938775a564d6e424b53654556724e5174645a5939704f4d6d592f474b35536938654831747a36697a63335350417849596574347a4c52694e5761476d44787361504464594a6c5749344c327153417370654e7761657831346a61645a355a35455753396c6c69616c57594d684c4a3638594b765332467470474a5a6278437371654f544846466753455a53727645457134444d642f724768586f315135626d55724e593735433852684e5679697957416c594c6e764b4f304f3542387841372f6844326647664365666b507048655a384a336c2f77443943664359666c4d443576776e62506a4a6957535154756a68754a7a69647541474a345a536543596767656b6a6333724d4a563765544737737443547a424d5a703853636e32514a366d7277477650744d53496a6d6255315367535a684849572b6b5633316c70505761532b46365a4341466c4e5074424e49494a637855735a73354a6876346655446b435734426a7166326c7067377a46754d4931466247766431457966474b41745149375452556d4f55367947515153564d53474259376a47645a706d72544f507a78504b4c63704d41724d6343444d4a514d6f5a6a784a335231656c4c3763486d634c44754f5246456e6149494b7a4b41517066764e78306a58302f6d3044414d3968346434596c6d6e563248586d4630693172424b7043696f2f456f4d5042346332534f56356c7131555134562b505151695257545163716b6744714c526d616d6b486b395a5631476c7074724a484a6a4b6b72796746373374654b594c413455386478504d5055564f444c5531696c4f46756f6272365246746c6c6d4157504862744172566732534a58684370636b4b496c714d3166764c62516267473352713236332b6c74526554316c7658363679374366385232684f7a38394b3668544b4a49755277384e3055744a57567572336a764d36747435494861624757744a4e6a655059565831574e6c547a445a57416c336a46334a37474c6e775559344d3037452b7a474a334764695353596c535a426e69706747706748765376336a4a436b394a4343424235456d655045795a374c5665425269535a42457a574f56705334655046654961687a615542346a674f4a6d31596734796d4b2b573262544f4559345976534d2b3452366961436b584672515738387a6d45635356523766523267796b346e7379593134633175487a4f5663385253716450424c67454532484b42747459647059727154755a526d576f6b454b534c6c2b4a3443424747584851787675746e6769444a6e384a307764556e386f56783259797a676e725750764b6c31696438396667672f6c416e482f65454550617638792b6a576b6b48745a6836676766534352567a6e6359466d2f474e67456447656b4a4f705952634c71424d2f79324a6c6371615341517a486e417151526b5357727763476352727169356a7a694c6d665361612b424b3664524b68654a5943545a67434d707447453934634962355756356c5262393345794f4f314f56516d494c4b4233657350303165564b4e306c5478476b5053564d324f796332525555637a4974357372763550694f2b33573468566d67596b7578776567486245384e2b6e326e616676453663626c7a774138792b357653452f7047704a50454778646a6254482f414c50634f6b66747170696c4570524c556f4a554e46756b5a672b384234304e4859702f2f5a3245316631397830336b6f666e3870714b7a445631436c4c6c5453512b69777a6372525566537465533162452f4f58394e724b36554164667446502f782b616b2f69424e394346666e46532f5457566a4a6d6a2f384151715966302f32673150517a684d496d417251506459702f4f4b6c6f554a6c65444d7a5872573444566a35792b6254544151454a5a4c5863683338345147556a322b737954572f59522f676c424e4d7371556b4e78422f4b472f38417a72374638327267534d67657930304a5169576c4a49426d5a577a4d48626d59334c37456f6f555763766a39344e61466d4f4f6b477035747a47526f3379544c5472504a31615259474f314f745a4f464d6c4b51657372546943754a696950456453502b52686e5472365366396f712b614450696d6f2f37516630792b6b757071306c567a466e512b495774614e7869374b4142784a314d356c5133573237627752306731706c59776c4c63435054364f3364574a5659594d6e467a4d436570304a674547415a426d42326f6e4d73783466554966314c41787050457930716665474d6e456c4a6f384b6e36526d33704c494d306c4375384a303346736c6a78484e4f755056614f346a6956584568694a376d7258743169385158594873444a6f39365a53757170716c484d7339773551316e35326968347271636e43507973317448554658326c363877796e71436b417155533767586538577443746d7a645932535971344c757771786375726d676b64706f6434455a583672554b785574304d76436d6f6a4f3243544d536e66503643462f723776575746303150704a5557495463347a4c4a484330485672374134334869446270367470326a6d4f4b75705374436b422b383179546270476c667156644369392f784b465652527735375169686f6371456745734f664f4836656f72574144453358376e4a4d346e557a376d38566b546965383359346c434b73704f7347617752464d777a67773671786a75684c376f46456270462b576f4f5a6d712b63344d58616c775a543164674b474c38457870644855496e49334f4644356b485552664b6231496e6a6451634e4e506949524c6e706d536a2f4148656f377956664b7333496a4e6464366b64312f615637686c515a7374683652557853386a71413149346b365253324f32646f3752326a4946626b2f4154706d4559637043564f7764524d615768713874446e75594c32637766474d45564e4b534a6d566e3350393444574948344a6c7a53363056412b7a6d4a367a5a42536d4b5a774234356636786c666f30586a4a2b3062627256735572742f4d6f6e37477a43484651482f4149663678413071676466784b4f5a714d436b47545435464545676e546e654e50545765567053765538342b735555793871716753784e6f78745458592b47655861384467536c4b54357768554e61353959776b5375644d524c3936353451726258587a594d6d4571765a377653437278646a5a41626e48667151446856474939644a6b636d5356697957447939594e7452557777556b44534e6e337064496e793148756e4b72676449577464544547763254467658596f39726b516965376930647243537935424269557869473045397536593166444e5379657730723356353545594a56487038385a6c51796137415151345744336d647833414a637739704d55516b62685a2f474d6a5565486f624463787749366f623243784a545958526c575873796b664d46462b75735531716f5a73484f4a6f766f7971354857573472674a706d556852584c4a7364344f344674583478553853384e4e47485535552f695545737a77596668694a6848754b385152474e2b67314c484e614837513934376d4f70435662784776706448724150615445577a724a564d737161326c2f474e2b6d68776f7a326e5675464a6d652f737163354a547165496a7a7570384b3162755843352b73312f31644f414159594b46514b48536371626e72476c587062554e536b634b4d6e352b6b516231496242354d5531594a55705242446b36686f784c3363327337416a4a376a45765645425141656b587a5558697365737471654a394946344a4f5745357a78475567586a5371484d7076306d726f5a583461656b656f303666306c6d486333746d63536b37423153304261706b71576f6e2f44576f35674f62413335526c6d3274654d7a32442b4a707649414a487232694846746e3671544d4b444a577069774b41564258416872785972777748786e48574b3374417a784f7a395973503243303362385475456b36414a55784f75345157304a316b5072556267474f6349396d3036616e2b385452546b6b736c6773734e3579715943464e71305667424b463270797041475a6974744e6b35394850374a6a4e546c436b72516c544546396542746f3861656c74467162685050366f2b3049667358554a6d705868382f75352b394b4b72464d7758595043645568552b597631694d3547444f782b7971557152544b544e526c58326967656558756739437a786a48584c546577504b3853797450394d592b4d333875656c7459324b7458515642426c646b624d394b776434672f4f71626f524f326b547a796964796e306e5435687969505948704a356b46464f3869464f2b6e4876455351473751657071305a536b582f4144696e6672394f617971637831645437736d4a4d5172444c4444336a763452673261697972672b38652f704e47696b57484a3652504d6e4652636d2f7742596f737863354d767167515948535249674d77737a347074306a737941655a4a61574159752b764b4873716841564f665763446b387775546961304d6c517a446e723577354e52596f43754d6956333079506c687847744e4e536f68514e6e76794d5071565461726738452f615562465a5156496a7843626152374d44496d575467794e584e79334a30694c5832444a685670764f424d746a654a6d5963723930526861725747773752306d356f394d4b786e7646394a654b31664d74577a6259596b646b6b4f2f58375236505441655542504f616b6b326d46416752596c65554c56416b77674a537063426d46696642556449784c554b6778496b736f4e69416573515556756f6b686d48517852744267737673315445444b704964686f527674474e346e345a543554576f4d45632f417a53304f74733877567479444d6e4a3936504d306a4c69626a3949796b5271494a5565624f69542b476e6f493954534d566a35547a317839737a41346e6838677a5571375359685934483376412f61504a6b6f4a36536d3237595151434946503257714656516e497155706c35584b6a6d55516f48335568394f62786f4b796e6e50316c63366b4162647630682b4b794a435a387563745579624e53775437796b497332645141596449744d654e78357a3963514b677847776344392f6c474748536a32717770556f6f315163336675412b5a4c4e71397846634a5275494f4d794c5862594d412f486a694d68504b41784b47757a6c6e2f704256586d6e3253526a393557617062446e426d595849714a6c535a7976325a5151507778716d373275486363594839635762646e365333354e4972326254385a5473786947494763453164506c43697179556a4c7864306b74347846744e566c674c4145546d7270386f37546769626f6f53513178447a3466533634516b544c38786765524b707449746e5363335458796a4f3148684f6f5162717a752f654e533943634e78466b327249336d50505757575a7743524c71306779686463654d4c3332482f6b5977554430673032753578327774316a6c6f2b454c7775626d462b4c2b55584b416f41725047546e3752476f58615942584c7a4c4a423377753167584a4573307274514177646f58485a6b6e69494f4a4a4679334b4a786d5133417a5043327636654a494742677965656b677543335a684357346655464d774463645962575172444d58665747512b73313942564833483030365236767737565a2f7045386a7038706733316638344c74444e6158314d48346b354655626f4579387945795a486e51334d3941717779686935564b393031744572384e4d622b6e507343595634397379307a4446674756797372584e69444a416c4b702f4b427a437850555468776a684949684d73386f4d5144435a66534445417a366f6c7055677058377044486441326f6a6f56666f65734e475a57425471496a6d624e5379586c4c49354b7636786a4e344c566e6455325067655a704c346d34347345585461636f4c4b444552536174716d3274316c746242594d6962436c4863543045656e7248734359466e766d63543272326d4a5758414574495945574c386a764d65664e597462474a3671756f56727766764757487a5a6b69556c64544e43625a684b64793544674b50324556646c59733435692b624f3331694b767261326f56327165316e494937675233555a527145376a6f3238786f4e6353634d635174744e593445337545347968614a6333493361416c6c415a304d4e47346a534b41735a4c446e6e342f77434a55657663764278447039524c6e4579794170444d584c6133596339494332313262326362666a31674a57794463447a45754b564b5a4d79584a4341417339785263693179432b396f42382b38414d43574b327970596e6d4d3661736d7043696c4353526341456771355874427063773667524c6f6a6454446154464173417671416238393357454a7258573359547846767073646f77773270576f32515266586333326a663074317a734f4f5057564c366b55645970326f57424e7476414a36336a433863526631655637675a2b664d3050446754567a4d394f7159793153617131786456566b5755716c75756d614c5a756f42415339322b736375304f4d2f475a4f7672494f36555470624b4934474b68344f44476f3234417a784974724545643578497a4c454a6530516f796343435474356a4f524b434158627975656b61315641714747354d704f35667041617041444f6563556456574662325a5a724a50534272496545674757414469565a2b454669486a316a37445a6853454633414c50794d58744c593164794f4f6d63452f4f5a6d6f554e75474d52336a6c4c6d6b6d784b724d33363452366e58564236506a326d626f724e7433586a764d544f70566733517279663652357a79484236543061576f662b51686c464c5638717649785a7252765178467a4c36696169687367576272473170736841444d532f6c7a4c544675567a4b706b635a30485841795a4f5549366359644b6778466d4649686767525674564d615142387968364f6674475034355a74303250556a2f4d305044567a646e30457a32485636304b736f74774d6564306575757163633865686d74714e4f6a72794a6f4d5254326b704b78725a75687330656b315946394b324472782b5a6b36636d71776f59376c426742796a57555941457a6d4f53544f625972374f714f65704b3569706849566d56336c414c666351506448384c52515854765837724437545262576c786870666957795571657347616f5a557379556c51647443705770614b67304e694d53434930613442646f7a435a7544706c793053365a53554a51536370555759334e7943626b77743942617a5a3343516d72555a3343496c374a7a753137564578414c714a54327138727133674d574f756e47444f686372743341664845594e616e63475853396a35715a71567071585131304c4b6951514c424b67413665734366447979344a4766574750454648474a6f55344d566f416e4c537061584b564a53653654597335345767763841357551564c63664b563231677a6c5668314c6843553672556649517866444b78314a4d533272627349525459524a526349633639346b2b68734973562b483661733543632f486d432b737563594a2b30504558506c4b30794f30307a38552f776a37783548786f5a31492b512f76505165476a4e50316d5571703055305762694b496e724b694c6c615337586751765a7a6153574a776c356d554764392f53466172525765587637544f31537061577242396f63346d33784751433077454d7258724647785273446a763841764d71697a48394d3952415859633453566c6a475a494c4976455978794a425547586f716a5948646f64344a69777572744334697a554f534f38436e7a435362332b734b4754795a595251424b46477a775145594f7347556f7730434f41426a58425a3667447742423859344d794d436f376a39355331614b534a735a733872596e796a3162327661636d656656416e416e6a515369546d58537844314555786e73346151654a7979754743435a5773524a6b536c535947544c4a5359345344444a516867697a4355694445475a6e62476f64614a592b45456e716450703678356a782b334c4a574f334a2b7332764371384b7a6e767845644e4b5570514351535447445657316a6855366d61646a7171354d336c4a54414a43543849486d493939545346514a365479396c684c46683368735759695a795959535979437a54416d45494f565175474a624c4d515a4d4951596b534443705a6867676d4579344b435a636d4a677955644f6d4132786e4e50496652496a793369677a7154386850542b466a2b674a6b3669644664466d7370697171584674424868346a6d306b7855354b7053437051754d757238343061634f7072395a6e367578613346784854764f71374d5975466f3747625a546147782f35456559314e5270596b653665735872744d51526456306831584c794b626a6f6555566d553965305455336d444d6f4333313352787a67434d4b3436547736504553652b4a47594949644a4b77655972647567774931524b657a4a50644250495177656b5a7541484d6e6a4f4c4368706974545a6751514f4b7477686d6a52373777692b7566744b4e6a492b36787664416d6e3246784372724a435a382b584c6c49554155414f564b5364464d666442335236394b5750552f695946723170776f4d645656644b6c4b7972566c504f417375717062447961364c6256334b4f495854565346447571536568697858625734396b694965703139345364514853594e2b566731384e41304746715978684a4e425263675578306d57796b784967457771574959494a6c7346426736354b5661704236694550556a2b384d787975793944493039444c6c6e4d6849436a415536536d6b376b554179624e525a594e7248694579454d354a3169796f696d50595336446754477172776438494a6a63536c645349456d5342426a566a63353658696e647136712b4d2f614d48786c6b69714234673844614170316c567077447a3859653350534d4a4b3475434c4d4e6c4b6867674746494d535a457553596d445056474a6e546c4f31566346564579347357386730655931514c3373666a50566149626146455147636b6e33684377704861577a5a6954566879316830533569787843464e3573304f524c4d5a49346976315335786b52662f41476f71696d6f5747544d34472f6433754e30574e4e6c7a7558744b757231414932487642366e487338307a456e4b5358747836784c6166494f37764e6a53586f61677332654162625331415336674137733336306a4d743062312b364d723664783870573148682b54766f4f443654544c70305441465346416a6738556e72512b357838444b433250576358446d4454615659315359723753447a484c6168377a785649734a63704d547449353754686368624759757843656d57414641356a6f4859414456524c473057714b5664647850796a307978396e7044384971705754744a6173366c57613975567836777730324b3453735a4a2f4572366b4f54682b414f387a47317545544b6c5865424947362b7362476c307261595a4858755a5665354858594f6b364e7374504b3649496d6f556b70514a62683067355179536b6a776a545137716a762f414d544d74585a634e6e7a39597658686931414a6d454b4a4e684e4f623145597070744a77782b357a4e49586f4f55483234692b5468544c4f524f56516538755a2f38416c5835516e79337a676667787a576a626b3950694a424f4f56556d59556471466b66424e4253534f522b384e545633552b396e487867485430326a4f50714a6f4e6e3857375a5066546b574e5575376444764561326e75577863695a757071324750456d4c637047664e48515a5a4c45454a42684b424442426e737778786e41534757496853514554496e75614f7a4f784a70654a475a4278507a314932743578547a675a4d66784e4c68633155305a70687970345063395979723955584f41634344753949306b34784a43757a513167484930443241697635546b5a78784f7a694534696b4b515435627278557355715165386651324c467836797a424b764d6b4a4a6453517865373844486f644c6476584236694f31564f78736a6f59396b7169364a524d4d6c7167344d75426a704558375159716d6e6b4c6d712b464a59635475486e414f323153596461466d774a77366674444b556f715654676b6c79307851756464305934706339542b502f5a7067754f41786c4a787847694a434576764b6c4b2b30534b477a6b6e38526d57376b796d72784b73794d61716145365a554b494464486933765051774555446f4f666c4d784d53536f6c53696f377964596343414d41596e5979636d56396f553647437744445777316e4b6d576f7846745945305a6c705045736459307737614f5a4b4c6f6d45654e6f715861464c42375379387669434f4d4e677a53557674476e70444b79713536526e50344a5554786b53442b6b626b6a48316a6e4264724b36725745534a475a2f6975456a6d7052734957664245486377436445764a6d787173466c464156556a7456674f72765a5538774568586548574c436152614b2b503336664c347971757163747471396c666c6b2f55346e314a58304d674d6c4f556677355430484743717453733864344e314f707539342f6d4534586a744a4e554742556f757956626d6535334e5a373859753161744736695662644461673677446248616a75396b6c696b53387856635864303552344d5030494455616e65504c486353786f744673506d4e317a3069576a787061707374316b686143574f3559595036786c6c327753542f41417a536568647541503841556b617735315a534845785855414b3536776c6a6779565546526e306a696f5569664c4571636a7445376c5842535476536f585365594d5771377a6a42356c46745067376c344d58304f7a6b2b5650544e6b5648615377344b5a716d55452b544b385976615656795375424b6571633441594762796d6d6c727435677870444d7932684b5442514a6167775167776843784241794d5352554e375232665754494b576b6278486353655a3469596b32426670484445676d58705242625a473654676f4d2f48324165396d55624452347a395837753051324f5a302f5937446c317855564b5569536767416a346a7641504c377851723034543577314759377865676b495769544b427a6351587974764f2f77416555534c4e705070445963534f4d596769566c6c46546c742b2b4d787a356a3541344574364b724c62765344304f4970536f4563515044662b7555584b58324f444e4332766568426d79703538626f6d4559624c6e515547657a71314b41354c52784945344b54306e4f7474367064577951534a615334484538544647357978343654523039595163395a7a2b717764514a597643777848555377563944424252714633696434504537423635684b35717967704d486b596973484d54726b4639494d4d4a50496b42534b55575341384547456b6d433156424d4771596372724b74696b396f4f6146513469476559496a796a326e564e684e6735637557696f7135437038315756534a4c6b424358634b576b65397573706b377278527531682f344c6e2b38753036505076766a352f74382f764e74696d49564a644f58494336636853434d756a5736376f7937645663784f345948397071553662546744427a6a764d2b6e47464a555173464c427371526c306469783636394f45563978366a6a35533735494939594855666a506b555833766f62767075674e78552b304977444555307463554c633930674b44645152393473374f505a677467384744544b307a69684c4e38475939304d6f7348365135617476654c5a2b75424e4c67714b564153705578552b614c70374a30792b384576646e494848653268694c4671544959482b666d4b335857594b34412f50382f6d593756585534496c696e546d57626c31472f654a4a4a5967576a7132705959326a37784c56334432742f543452665337543037744d6c716c416b70634b7a6431327a5a566435516439373267664a724f4d4167656f352f6e336a436c6e58494a3944782f5074484578616c7975306f35676d68766773744c432b6157653976335045765136446457632f672f48694b57786432323462666e302b2f53513263783161335250516866654b53644344755a68793561776465735a573274794a4770304b466479635254746a696332686159387855685249436b5a694548636c5a3361324f2b4c394c4733335a6a32714b2b737a69506157503841556d65735038743472656b766c2b307366504d506e4862486b626c684d7232694656686e3854486257395a47524e44732f6a676e712f455552774477536963526964477732616c4b51414142792b384f553469574759787a43447a467a334e485a6e54386a3074417673786c5353385a62334c76354d4d546f6d41592f4e52536f703664444b4468536d304a3339626b7852757349626b38666d4f42414559796c6f705a526d7a5338772f4572556e63497074596247326f4a79716247784d665659714a363146652f516a64776979744a72484532716746554b4a39496e4b53514354784233486e453751656b65446a724e6c543751454451734e3573504d7873687354424b5a4d45727474564a736c767248467a6a69534b68336964574e7a5a686463776e6c7046473172637937556c6549544a716e393554776e7a6d4573436c5a5971584c4e7a4243776d54355945446d346568576a743168675977646f6779734942304b684862794a336c715a576e4241787558356942336d463559693666686842736f5135582b45553966786e706f465a676d366c6b734141547279347730474a496d70326232566c7936684b71755a4a42546353737757724e78574233513355394c5171793156344c527155574d4e77517a5434786a534f386c4532616761686747567a7a4175664f4d367935474232457a516f307263466c422f6e70695a617272366b676c4d34725164516f6c78753577674d704f487a6d58664b56656969445371372f4b6d6b763841434673354c66417654646f5934313862303664386633456b4d7566517748457045354c726b714f5a4c456a5377632b62665348554e573373754f494e753844324f733878755156396a4f6c6838364170615532556b74336d44614f2b7641772b6f415a542f5572324d77352b50316c6d783245543632596c4d6c48635151537451377165664e566a614848547378774f7067507136366b7933302b4d33633759305347544c6d414d4e4a67317537706177473755785876303771326378644775563135583764706e3671686d53796336776f66437049756e6d3778556177715a6f4c35646734474973785043564b55564b64453149643036545563526233682b7556784c6736386465346c625a74627278362f324d46774b6f6e425a564a4b7379526d655859736b356935444f4c427766574f4f35434376426a6a735a534877524f6d34506941576c5a6d79684d5746454665554a556f416d2f646137446476486e7875564749735850306d625a53526a79327750544f51507647395668464a57535a744f46652b686c494f35785a535834454f446534693752565135335574673963544f756139422f564752307a4f4a6254657a716252725a597a494a4f56593049484835547969327a737657495646595a455479384b41335247366473454b6b305145546d52695063495355714452325a324a306e4363564a53424267785245324748533146494b763049596f696d5050455043594c4547634477524d7673773545654e314a66664844306c3166745a5355716371475576676e6a7a4f364830364f3633746a346d4d465a3738546e654f62527a616c655a52594433556a5166316a61302b6a536b636466574e556865424b4b4f724c77566c6374565752784d72464d6b416b485639344556367167473352756f73396e614f386d6d6f576456456b63597353706a456d435470347757494a50704436615554416c595161484d514e497250564c4b576d444b55726843396b507a444c354d35514f6b47466e655a474b4a346137694a43475435676b6b4c536f734864394769664c4d487a524c4a3949426f485639503677785578415a387868686d48496c704b3569776c53334364424d4961345476414f387446545574327a6744724c656b55353342636e3439424539616f4139326d374e44754372336964484a75664e6f6f74744a396c7072717a48336a6b78577163414130776758594b4c70424a33754c4470423753547949575a6651315953695a4c6d5757556b4a42314a494c4d643463445343387345376830697248786753365256796c68416d6a4e4c6d4a414437693178785a77343450416247526956366943335053557a5171536f53314b7a6732537368335151564943755959705068422b793374442b487649424c44426a725a7a4170696c6d59556c4d6b674f70497a45704e776c494633636e6b48676c7335474d343734475a5876734179764737747a6962584674725a644a544a7930363561416c6768676c53526f4c6161747a7647737570557141415238356a666f6d4c6b737762346a6b546c3156747a557a46715553565358756b6764332f63425933316975364d7777544e4b7356706a4168464c69535641462b367263626a4e75483635786c32306b54515667597a536f54556d586d4c2b386857384b48446b6548555168474b4e6d4577794d7972596d6b437069313730727972535178436e63462b4266526f745757465748666a6a36784e784331346d7737583864636f67676c41576b365a726b48546547486e43374d6c7478366d56517639494f4f7878386f52573034556830736d616b756c647751526655584839596c656d5163483169716e4b74673871656f6c4b6366584e6c396e5579307a45685756627076626942626d3758447863585732737548357759542b48564b32366f34794f50542b6674465536697046724e504e6c496b465a65564f6c4268794253374b426264425536333269746f343745524e75684a7238796f3578314269784f786337506b414375436b33536f626944476d6f44644a6c4d3233673854513462374f31327a724365517559614b346b322b6b312b45374e795a44454171567856663030686755434c4c6b78304242515a394854702b4b30566b77426e4a48427a435455684f636379796c72724b317a6e34784958454a72675a5546775749726643715a34573445656a6b52315446546148786842555267636e6d4779556b6d38454267514353547a4435457143784233546f6d4362433970496c7a545043444d47624c6b646b6e532b596275572b4557576f6e424d344f6577684533594e5962386156666a6d48324d4a7331465334444d426e70474b355061564c32416e422b2f4b2f6d5639307842485055527133723647566a59656550395038416e456343423348336b6d31666a3970665237447a6c48764b51676463782f37667a68794d70377854576754535964735a5479727257705a5044756a30763677372b6b4f706944613536434d7064444a6c2f7743484a5147336d35387935684c61797465692f744f7737645445566673354d6d4b557356435a616c4736676b6c5852334259437744694d38316879574c64655a724a726c52516f516b443752542f415058556f3358564c562f4367442f7955714f3231714f735966464c443051666557443265306242356b3873543853413456754979366342416559677a414f76765059666e2f4d6f32683255705a4e484d584b5373716b704d774653796f355533554c323063395245376b734f46344a6b30367934324466302b55354e55545a6b7770544c6452766b516b4653754c4a43626b6a587a693356574165527a4c39746d426d62716a3251715a346c476372734571434f3475387979677179625a6443325937744c6d454c56355a77652b63524c36746345707a69644a6b55307041434558435133766b426d4679415146576257474b7461384c2b387a57737359376d372f442b2f615a5847396b544f576741796b49564d4369796c4f6f482f4541645076464f3532684b3037587a6e67792b4e61436842424a48772b33667048796632656c51696e6b6f796f415551456839375a696f33556f3854444e54714547462b63703155574f43352b455631324430565548556b79466c752b684b5535332b634d515334393433357770645658594d4e6b48735934432b6e336661487036664b536b624e306c4f4237387851596a504d79754831634d4234784667715833682f626a2b66775131314e396e5467664c4d2b45366a6b7a43744d6f6771624f51564132334b424c4f4e4e335742653754676863486a2b667a39345971314e7138742f50352f714f70644c4c6e424d3655633655754141474b6448436f6531433272356c58492f6235796d6258704a7173344a2b782b557071694679695a5a754c6a6934346a72614b2b466443565038455a586c4c4148694356566c534255414f46484b744944393177435331387966744330596737766f5a6f46414435587079442f4f786c474c303361533555784448737067594f774b62576663374a45466b5a794f524f527472737037694d396b646f524c5169576f4f704c7049476e634a436c4136424e724f30584b626d7059454449506155645870526557594847492b6e3763556f664b6f714131554163723966434e427464574f6e4d7a55384f74505869527064756164616d63645162667a4b4153656a774931365a775a4c65484f426b52355359744b6d614b59757a4b4755767744325068466c4c30666f5a556654324a31454f6830545079584e773658727165416972754d756852316932625367754d734d45556573724645447936686f374a6b38517552537041756f45384243327a44552b735a53554533444e4542524a5a7a3268386952427865597a70704d526963434a3253556a73356375587551684b664a494565593174704e706c75705269654c6d417334647670465631573342635a78783949304c6a70505a7338714c6e30697939724f3234794672436a456a326c69327341574f446a7243323838796f4b50452b635678554f704a2b35683448704c5a4d776746524a5a764d7779732b5570624a78366459444b4351423167697352494e303236776a39572f55722b593861634563474579352b594f4446704c6479354553796254677a334e45356e596e6d61494a6e596e32514c436b485253534350335347503368756d55732f456875426d4b4b4368707141424568425358757255713433314f377a69384c516c75346b6b6a37665152345637313972475035316d53785046706935685543317371514347435162414563412f6e464e334c746b2f7754555370555848387a33684f4759724d566e51396a6c6333664c38586e456269507241737258672b6b6e55317037513546585554657a70434c4675704a6638416845432f7577366c41544c442f774268744a55685379445a6a763841336b685832685a786e4d437844736879357164624f4f49666e70484842475a5751484f4a557647637270576c505a6b6c52336b48666c443733423844446b754a51716658502b766e2f614e4f6c79515165656e2b2f6c4273546f733651704f68736e77305479734c644955526e44434d7174326b71305634586930366d5557732b6a466e3638644e447a68744e72566e63686a72364b37317730335748566b7570526d5351685a446b6161333650303552655570666b6737576d4a64572b6e594138724d646a6c582b7a465669676857596a514b4768505063376352464c796e4462655a7171513668733547507446394e744f4a6b6c67514f3045334b445a676e34796548434a61703679516563592f4d4b74466368684d35692b3142566b6c766d546c7972494a53546346695271486373646252646f6f634b536670386f75396b445948722b59485634756b69796c6742694f38647734456b656b474b6d3659697477484f594e54567972744e4367644d316a7a42744850554f36342b55355850726d4e734e787a737932513558636c4263503038374e436a56337a39354a6164446f4e70795a61536d7041445737366550425163654d474c7246474e30724e70717963375a792b5a6835485565636135416d494330456e4a55377350446a78694e764749652f6e4d47576c522b6b466a45444f5a4e464d6f6c3344384769435951475a70634332517170313053535238792b346e774a3138486a67435a4249453375452b7a674a593145306677797750384179562b55466a48574c335a36545430574630636a334a535372356a336a356e5477697662724b7178795978615861575661375a74586a797574645549636367793955446e624636716b6344465664537037475768555a5561314f392f4b4769394959706157304b4f326d3931544a534c7472724637533648395659487a674166574263664972396f636d523267786154494b554142537a636b37682b5a6978722f4b51624b317965356b364c53586167467a774a524c78644d305766532f49786c5732466a676a7447746f3271504d487170536c4545584461516c367933496a4b3356426777716c7a49517a4f583436636f59753946774245324658624d73457959624141657347504e4a774d514d566945696a6d675a70684355384c416e6b4f486a46746442714d6272446766544d5435315a4f31426b7751315454436d316b6732765a513477612b7763667a6d5038724e59507166326d5978797455745a414e6b4335487a462f7342435332546d6156465952506e4d6e5772556c6d47717236626f73566854445a754f49307777356273345a2f4851422b642f4b457679594c446a457045395341684b4d716c4b4a7a462f6d55705a4437774371434933354c644f304d41642b3050713171457a697275324739677a6a7272435641784a474e676851714c2b462b757068654d784a47444b5670566c4f59653451397778537068647478454f586a7047466c4d746c59366955424c6e4a505a5441436c61626c4c4d78626577793661615869797457436364347471545a375348326832395a665755535a694f31437757446b707543666d486950446c436857527a495730716470456c7331564c4757586f7a6b7671344973664e3463696e5045445542534d7a555673784b6b392b5769624b5541466f574d795663387073447750534848557656375935486365737a52534779756348735a7a4432673745396c2f654b4759727369416c55712b5a4354627571636b6f6578427548336732304b3771483448666d4b5a745150655077346d41714b4661436c5075755069494174727231687759486b7a6c4439466c3071574347576f66374c2f5733724332344f566c6c417a444451365268424b4654456865564e79556a51633236516b326b6e4573667077713542676b75644c4e6b7a5354726f3539494d7134354b79754c5562674e4e5652346b674953436957736744764b5463395842696b775947577775526d615370777657332f504d7875625a356a6549746e59512b36494149367957495053436e4351502b496e45484d336e733732566b6d575a367746714369454a4e776e4b3132336e3651515876495a75303366704547434972784357556c387a694d6e5746314f4d7939527449674a56474e61435a6457456c2b7a486a395952716c506c71497454375a692b5a4d44654d564563464f6e655731553569544773544435456b416d784f352b4478615653787a4e4c53615934334e4c4b57705253532b3155543279683351545a43534139743536786f426d6f543250665034482b59466c5436757a793139776466695a695a364a382b59706141755a6d4c6c516367486d72516563476f415832767a50516f3946434247774d667a70487443465577433571772b6d525065642b4a30454961744d356d6664743152325644366e6a3851796474453643704357626a76302f4d52474142377372703461412b484d6655553774474b545a675364774268566146327750715a6a324b554f444b613361507331646c4c377438705741436f6b5a5363704e6872774e6f303074576f624b2b50553935796150654e37382f44744a34706a786d533356335567714c42793442536b453853517146366a5676637654762f50784761665272552f48582f5a342b6f6947707849695956454d364d6a486b464b536544325635434b684a494d764c537530445066502b5974714b674a6c7a4c756534427a4c5a6a3954355279563578474d6551666e456f534a67796b68774154774469317a313949734546446b5163677769724a4f564575374135315733574830506c41674b764c665351435952686b6f4b576b4779685a4949767663676548704157644f494c4e74454d726c666942797842532b36795135303551757351776636592f6e655a4f5a696b784c6d3479716347374f706a72705a6a626738615170516e694331704752473244596b366a4c557275464f5168375a5667704b692f41724236474650574677594a4a5938545430564f69624b524c55674b4b565a56416c6944765a397a35755265466334774f782f6541574b73546e47522f503532784a5944534b6b71374654464d7a33663467435152725973503049685353342b4d693677574c7537695253735335705541536f584936574c65485856584b476273486952677375444e54545445716c71533767452b542f63454747345571524b4c426c634e4b364e4b5653564a4a423935423374773867336c41616467712f4b4671464a6670316e4239744b705972465a305a6371556849335a57647878424a4a6a62433731426c4662504c5977444261635470687a717953774f385272794135777535685542786b6d4e705a72574a48515462594c55716b68557555727554686b7a473746514c466a614b4b7675596a6f5a654a365a6d507844435a74425079544e434355714769687936634930582f414b717a4c72587958783250662b656b7047497772795a632f5543666f576252386f767a416936646838644f675536675044396661496b792f434b6d5a546e756e756e564a466a7a35486e48596e5a6d6e704e6f6b4b44724252785076422f7248546f306c7a305442384b782b365839494236316359595a6b7178586b47512f5945483353334c3958696d33683966565938616c75386f7235524357416530596669326d7577504c58492b45746165785365544d6e6946566c4259687839597771716954677a636f514d77457a2b497a4f7a56633935724a35384662772f446f386274645a513450576139432b59764854312f7838704f564c6b6d576d644d535a79314a436a32696955673777454267774c324c776f3350764b6a6a48667559746a61584e536e616f4f4267632f552f346746587451584f593930426b67614138414e77677870532f4a355078686a5256316a6a7233694f75784359706666462f68532b6a372b63573071554467786f49555957584a6e646b6a764b75713351517372356a65794f6b59624f35375466374d4a7979457353584a555835364436576975624d636647656631584e684a6d65727071555435695650717056694c335a787a596d486f4d726e3478764f41523643447936307151744f623448416477464261566a77492f566f6b44623168455a5038415053417a367674436b38546d38416b672b716a4542646f4d49475734334e73707463344461614954666947654f70474d45775365414942517164595534304a4e74343771452f5177327a335349413678684f5579637774376e5573472b742f474b2f5673474d37536b315034794573374b377232766d4a752b3644436b726b5154674b5a4b70716330326156456e766d353441456630675175464749772b364150535a6245316a4b5642785a4a6270766a5370424a775a54314462566b714f714b53706a63793954784a75504b4f65734544357a6b66422b3033744258676d596c374b6c69632b684864426365495a75555a35544862726b66582f63657938672b682f6e34682b4e7a796e396c6d356e41576b6c513062522b4f3736774849475231784631444c737550555254695655557a317143743968754f59662b7252774f3770367977696a6141593977544577512f3769334a462b5164754a62776764355675596d326e503345585473613746436c4c573270562f466c4150714868534b37766849793449426b39422b30357674444a6e5430496d716c717a6439675178374d725570494f2b325974794d656e70396b626653656431574762634f38535579316f475336584c33424548596f4a7a413039684337666a47644e6935523252336f6d4a552f494f546145436e326977394a613834594150724e627431693069736f35595433703656416f4351354c6b41674455324f6e4b4371593738514e516746524f665445797450734c6953306853614f597844683871543470555152347862346d6275616670535a4a676f7143544b626c48546f4f756d45644f78424b684e6a393436644d2f696d49496c67356a34434f6b695a4f753273556d386f6c42473845672b63524f6b734d39726c5a4b4c545a614a365033753474754f59573949366469627242506174683839684d57716e58776e44752f7a697a645769436f4d6b4d524e58324d696f546d485a7a556e525353465736694557615a483934523165706450644f4a6d63573244537335704d355573693453526d534730796b4d552b7356546f46427973394270662f7741695a427474514e3865682b76592f695a35654256744f6c534653653152636855733532346a4c37337046432f77397932345456723855306d6f594864745078342f386d5372457938784338795344644a44456563536f735559784e42734f4f4344395a453136456b6b42314865626c3434314f2f76474b4a5242795a585541457973796e57705369623253414f366e71536653476a4151375a536538766142304839357671544730532b4a53575a747833672f7264474d4b7a756d666353387a7531556f7a5a6e61797931764e3262306935706241755662316c69733551447549686c564b677358495065353667737a3673597473674b7a6c4a7a6945537741704a34356742344a2f4c30685a795152386f51395a50457167715570523372575165706258665a6f3674522b30682b426944534a6f424235663139435838494e6c4a34677247695a6a6f494465387a366c676b426f724559626d4e7a67543667514d366c4d2b573435474f636b4c694c4179514a5456715a793475547277495642566a4f42477631694f706c7544774c412b746f764932434a5373475a475569344c66753336452b635354326e416435724d4b41505a7a486343564e5431417a416548346b5a316a455a487845755a33446a34536464574b376b6b2b376d64333063756f65462f4f4256665a4c534342357559706e31766154465a58756576384178443171324b4d77664e796343465473546e4a6c396e54793154466e5652736c49666a385236634e594b72513732335077507a2f414f536e714e65454f4547542b422f6d4c4b6643617559734b6e4a7a4d5841633551654f586634786f725374597858784d31745139682f71662b54553165453141555572377a424e30364546494e764f47674552444d47356969753244724b7075796c39464b736b654a2b304d584d577841377833672f73565a6c566338725079536d536e6f566d353847686769576664314d364a675779744e53702f436b6f514e35417566346c717566574f416b4d354d4e5869314d6b735a3874787a4a395242514a664d676f4d48576d4f6e5165596d4f6b3569764643516b324a69444f45355a74436c31367162655475384f4d41637867784d335070774648556a6343475056512b3336455a4d4c41674e52715949514442467948695a45736f4b716f70315a3545325a4b5037696948366757506a48627047777a61344e3758363655776e6f6c314352765063582f41444a742f77427354775a48496d2b77583275304535684e4b3664522f7742514f6e2b644c734f7252425753444e53715653567141576b3143446f526c583548555144566739592b76554f6e7548457a654a2b796d696d45716b545a6b6858423836664a562f57467453434f4a6172385373552b324d78446966737371416c6b4c4369506954793335547669754e4f366e4a35457474346b726a4134694f5a73625849566563417a393070494639584436326754556d4d62596f366c69323764394f302b71734d726b6f594a6c7135755166704342704b39325a592f576265514f666e4d374f704b74426463676d373930694c586b4c6a414d44396532637350744b716d7555426443303557624d6b2b5033686136666d4e2f584c6a5053567a3856536f616a552b4474484c70797068747245595a7a4b3648454567335675382b55466253534f6b3672564a367833683963674a424b724f2f7743754d5562616d4a3445732b63753372445a7457794f375945754f4b7951772f326945716d54672f366a45626e4d6f7161686d41765a39334a6a34757144524d3879532f65414c5675356447697742456d5568484f3238656672425a6767522f68552f4b6b6c6c61466b674f584f72634e4236525565707247327247506374535a614453384a7135366e55524c54775348504e796453654d6156656d725165737962646263374535784e5868477a794a597548504f476557427a69494e7a48676d61576a777371393146765344416979776a6d6b774d66455142792f4d7759574b5a34336b3055704c4d6a4d775a7a66314e6f5a67524a596d4a386532326f61527850716b42512f79356634692f4a4c744259677a6e6d4d2b326f6b6c4e4853742f314b6775656f516b2f654f3654687a4d30766132727148565554314c652b58335541614d4570747831666445536353614d6255327039493664784f2f7167344548504f2f7048547055754f6b356756614570517053374a5343536457413130764854706d4b7242517638525351506c5459355164353471365748505741497a4442784d7069327a313752474d5364325a6d352b466c42494c4e48455467596d72557357455342494a67356c474a7a496b4679596a4d6e47594e4d6b694a42676b536448506d79565a3555786374584643696b2b6b546d6469624c422f617869456c684d55696f535039514d7075536b7436677833426b63696276412f624e534c59546b545a4372663952442b462f534f784f7a4f68306d49793536416f4e4d5352597352364b4543523677676364494e4e77795772514e302f4932674e676a425959746e37503841796b487261493277785a46565a67796446494870413759572b4b4b725a61517257576e794564695475697964734a546e2f4c41365233507249396e754949763266536477626f5945377657474e6f35784b5637427148757a566a2f632f7742594171653445634c694f6850336c633359757050757a683155484a505547494651394977367438597a49793967616f367a6b6a6f5036775777656b5764552f72484f463742396d58584e4b2b566750534f4e495057514e5777377a5430654270466b7045534b38644974726433574d354f447471664b4742596f7647644a686148736c2b734546674d38686a75503031436a5055544d67305a4b464b4c384c4344327852616334787632327044706f36567a646c7a7a6271454a314869494c4167354d77474f626334685676327453734a5077532f7730394754636a71544853496752496a704d4e6c7932694d77735179584d41386450312b74596a4d3745733751382f4f4a6b542f32513d3d, 4, 0);
INSERT INTO `subtipoproducto` (`id`, `nombre`, `imagen`, `idtipoproducto`, `eliminado`) VALUES
(5, 'subcategoriaNovia', 0x646174613a696d6167652f6a7065673b6261736536342c2f396a2f34414151536b5a4a5267414241514141415141424141442f3277434541416b4742784d54456855544578495646525558467867594742675647526359476867564678675846786759474267594853676747426f6c48525559495445694a536b724c6934754742387a4f444d744e7967744c69734243676f4b4467304f477841514779386c494359744c5330744c5330744c5330744c5330744c5330744c5330744c5330744c5330744c5330744c5330744c5338744c5330744c5330744c5330744c5330744c5330744c662f4141424549414c634245774d4245514143455145444551482f78414163414141434177454241514541414141414141414141414145425149444267634241416a2f784142444541414241675144425155474177554842414d4141414142416845414177516842524978426b465259584554496f47526f516379516c4b78775350523842526963704c684653517a51314f4338574f697375495746384c2f784141624151414341774542415141414141414141414141414141434177454542514147422f2f45414455524141494341514d434177634442414d4241414d414141454341414d52424249684d554546453145694d6d4678675a4768464c48774938485238554a53345255474d324c2f3267414d41774541416845444551412f414d7474564d70436c36554b54794c333834726b4b4f6b3231563859615932615669366b6b43497744423375766150396c3866374146505a68626c3778773468627438366c734c5879706f6e4c536e4c4d6357505451637252352f783273756759412f5356745172444531435a6741334a50336a796c6274572b3453727965444173577870556d577857644f4f7362766839396f72334d784f65677a4e72773351693342497a4273436d4c6e6c306a753645376e6a54727161346343584e614b394f4d4d655a375834736d564d4b474b696e577841383471585674573241527831457833317964414a344d655774786c4345745a6937774a31566a2b7a306c76533037795359745146546c466c4d334837515172475a736b725376496a50434e6e564b57366c6430616e386f75616653372f6c4b657138525656776f3568474b556557616a735a68546b31476f55434e44465055304c6b68656b6f344e39594c386335346a6243616f5a77564d5874357772773076527168356e4950457058304d6f6a4b726c6a4f6d316e6a306570787555343469716d4951387a3275546f77386f7236784d344145366b39637779696b7079677466664676535556697345446d49746474784575374250434c506b312b6b58356a536551634950614d51636d664a544841596e5a6b4a6b6b45756672484541795178485352374162767245625a3236536130646964506b6949356e4577657471784c316a4f313276476c484d4a526d434978554b30744753504831626a704438737936524f49336778646f31787879774d67726d4a73576e46697037336962624365535a7155494f6b7a794d515754547471736c34554879424c5a72484d61794b78514f762b666c384e384e4668422b7351315950326d6f37646679786f2b5a622f316d567372395a3932362f6c39596e7a4c662b736a5a58367a3056432f6c39593450623654746c6672507632686679657364356c762f41466e624539596b7238476b7a566c537052633674465236797a5a4b7a527031747453425662694b6c374e30543353727a564659314a36533650454e586a676a38526257344268456c4379716c55737047674d7853695275417a61776466695662326557464f5a532f724e6a6b54525565413046564b412f595542414675316c736f644876476b6a4c61505a4248346c57377a4b44686d422b41356d573267396d4e456f6b79486b4c4734456c4375524230384951316751344a7a48314f783549676d7a6b71584b6d2f733661594266787266634e37373456714e6256585675504d6464555647356a394a704b36596853776b4d3664786a7856746a574d624d59426d55374174695a62614b586d544d563369744a444a476b58644932436f375463384c38554b4555746744316a5859476575656a7345756e4c645a34506f49336835747169696f343954384a533854744675705a7335416a6e4556796b5a6b7159735343654d592b713051724a77636d4e703052745559455531654444734d386c544b5663412b363332696d75703273412f31693637374e492b4d644f786e744c6855384a6c736b4f64534459635359304b624e337444704e63363668777a452f536153686b4c6c4a4a5776753644393478704c66354e486d4f6341394a6b36692b7479416f356d64704a6f6e31716b35694141535735572b38554874634c6b5459755536665268694a70714b6c534a71436b676748667a7439346e773355423951417842394a6732616a7a454961614e6373486848725755475a3462456770436e335173725a6e50454946635361656b4d475232676d666545647a365476725066434a35394a45394554496c526759633953576a675a786b79494938775a564d6e424b53654556724e5174645a5939704f4d6d592f474b35536938654831747a36697a63335350417849596574347a4c52694e5761476d44787361504464594a6c5749344c327153417370654e7761657831346a61645a355a35455753396c6c69616c57594d684c4a3638594b765332467470474a5a6278437371654f544846466753455a53727645457134444d642f724768586f315135626d55724e593735433852684e5679697957416c594c6e764b4f304f3542387841372f6844326647664365666b507048655a384a336c2f77443943664359666c4d443576776e62506a4a6957535154756a68754a7a69647541474a345a536543596767656b6a6333724d4a563765544737737443547a424d5a703853636e32514a366d7277477650744d53496a6d6255315367535a684849572b6b5633316c70505761532b46365a4341466c4e5074424e49494a637855735a73354a6876346655446b435734426a7166326c7067377a46754d4931466247766431457966474b41745149375452556d4f55367947515153564d53474259376a47645a706d72544f507a78504b4c63704d41724d6343444d4a514d6f5a6a784a335231656c4c3763486d634c44754f5246456e6149494b7a4b41517066764e78306a58302f6d3044414d3968346434596c6d6e563248586d4630693172424b7043696f2f456f4d5042346332534f56356c7131555134562b505151695257545163716b6744714c526d616d6b486b395a5631476c7074724a484a6a4b6b72796746373374654b594c413455386478504d5055564f444c5531696c4f46756f6272365246746c6c6d4157504862744172566732534a58684370636b4b496c714d3166764c62516267473352713236332b6c74526554316c7658363679374366385232684f7a38394b3668544b4a49755277384e3055744a57567572336a764d36747435494861624757744a4e6a655059565831574e6c547a445a57416c336a46334a37474c6e775559344d3037452b7a474a334764695353596c535a426e69706747706748765376336a4a436b394a4343424235456d655045795a374c5665425269535a42457a574f56705334655046654961687a615542346a674f4a6d31596734796d4b2b573262544f4559345976534d2b3452366961436b584672515738387a6d45635356523766523267796b346e7379593134633175487a4f5663385253716450424c67454532484b42747459647059727154755a526d576f6b454b534c6c2b4a3443424747584851787675746e6769444a6e384a307764556e386f56783259797a676e725750764b6c31696438396667672f6c416e482f65454550617638792b6a576b6b48745a6836676766534352567a6e6359466d2f474e67456447656b4a4f705952634c71424d2f79324a6c6371615341517a486e417151526b5357727763476352727169356a7a694c6d665361612b424b3664524b68654a5943545a67434d707447453934634962355756356c5262393345794f4f314f56516d494c4b4233657350303165564b4e306c5478476b5053564d324f796332525555637a4974357372763550694f2b33573468566d67596b7578776567486245384e2b6e326e616676453663626c7a774138792b357653452f7047704a50454778646a6254482f414c50634f6b66747170696c4570524c556f4a554e46756b5a672b384234304e4859702f2f5a3245316631397830336b6f666e3870714b7a445631436c4c6c5453512b69777a6372525566537465533162452f4f58394e724b36554164667446502f782b616b2f69424e394346666e46532f5457566a4a6d6a2f384151715966302f32673150517a684d496d417251506459702f4f4b6c6f554a6c65444d7a5872573444566a35792b6254544151454a5a4c5863683338345147556a322b737954572f59522f676c424e4d7371556b4e78422f4b472f38417a72374638327267534d67657930304a5169576c4a49426d5a577a4d48626d59334c37456f6f555763766a39344e61466d4f4f6b477035747a47526f3379544c5472504a31615259474f314f745a4f464d6c4b51657372546943754a696950456453502b52686e5472365366396f712b614450696d6f2f37516630792b6b757071306c567a466e512b495774614e7869374b4142784a314d356c5133573237627752306731706c59776c4c63435054364f3364574a5659594d6e467a4d436570304a674547415a426d42326f6e4d73783466554966314c41787050457930716665474d6e456c4a6f384b6e36526d33704c494d306c4375384a303346736c6a78484e4f755056614f346a6956584568694a376d7258743169385158594873444a6f39365a53757170716c484d7339773551316e35326968347271636e43507973317448554658326c363877796e71436b417155533767586538577443746d7a645932535971344c757771786375726d676b64706f6434455a583672554b785574304d76436d6f6a4f3243544d536e66503643462f723776575746303150704a5557495463347a4c4a484330485672374134334869446270367470326a6d4f4b75705374436b422b383179546270476c667156644369392f784b465652527735375169686f6371456745734f664f4836656f72574144453358376e4a4d346e557a376d38566b546965383359346c434b73704f7347617752464d777a67773671786a75684c376f46456270462b576f4f5a6d712b63344d58616c775a543164674b474c38457870644855496e49334f4644356b485552664b6231496e6a6451634e4e506949524c6e706d536a2f4148656f377956664b7333496a4e6464366b64312f615637686c515a7374683652557853386a71413149346b365253324f32646f3752326a4946626b2f4154706d4559637043564f7764524d615768713874446e75594c32637766474d45564e4b534a6d566e3350393444574948344a6c7a53363056412b7a6d4a367a5a42536d4b5a774234356636786c666f30586a4a2b3062627256735572742f4d6f6e37477a43484651482f4149663678413071676466784b4f5a714d436b47545435464545676e546e654e50545765567053765538342b735555793871716753784e6f78745458592b47655861384467536c4b54357768554e61353959776b5375644d524c3936353451726258587a594d6d4571765a377653437278646a5a41626e48667151446856474939644a6b636d5356697957447939594e7452557777556b44534e6e337064496e793148756e4b72676449577464544547763254467658596f39726b516965376930647243537935424269557869473045397536593166444e5379657730723356353545594a56487038385a6c51796137415151345744336d647833414a637739704d55516b62685a2f474d6a5565486f624463787749366f623243784a545958526c575873796b664d46462b75735531716f5a73484f4a6f766f7971354857573472674a706d556852584c4a7364344f344674583478553853384e4e47485535552f695545737a77596668694a6848754b385152474e2b67314c484e614837513934376d4f70435662784776706448724150615445577a724a564d737161326c2f474e2b6d68776f7a326e5675464a6d652f737163354a547165496a7a7570384b3162755843352b73312f31644f414159594b46514b48536371626e72476c587062554e536b634b4d6e352b6b516231496242354d5531594a55705242446b36686f784c3363327337416a4a376a45765645425141656b587a5558697365737471654a394946344a4f5745357a78475567586a5371484d7076306d726f5a583461656b656f303666306c6d486333746d63536b37423153304261706b71576f6e2f44576f35674f62413335526c6d3274654d7a32442b4a707649414a487232694846746e3671544d4b444a577069774b41564258416872785972777748786e48574b3374417a784f7a395973503243303362385475456b36414a55784f75345157304a316b5072556267474f6349396d3036616e2b385452546b6b736c6773734e3579715943464e71305667424b463270797041475a6974744e6b35394850374a6a4e546c436b72516c544546396542746f3861656c74467162685050366f2b3049667358554a6d705868382f75352b394b4b72464d7758595043645568552b597631694d3547444f782b7971557152544b544e526c58326967656558756739437a786a48584c546577504b3853797450394d592b4d333875656c7459324b7458515642426c646b624d394b776434672f4f71626f524f326b547a796964796e306e5435687969505948704a356b46464f3869464f2b6e4876455351473751657071305a536b582f4144696e6672394f617971637831645437736d4a4d5172444c4444336a763452673261697972672b38652f704e47696b57484a3652504d6e4652636d2f7742596f737863354d767167515948535249674d77737a347074306a737941655a4a61574159752b764b4873716841564f665763446b387775546961304d6c517a446e723577354e52596f43754d6956333079506c687847744e4e536f68514e6e76794d5071565461726738452f615562465a5156496a7843626152374d44496d575467794e584e79334a30694c5832444a685670764f424d746a654a6d5963723930526861725747773752306d356f394d4b786e7646394a654b31664d74577a6259596b646b6b4f2f58375236505441655542504f616b6b326d46416752596c65554c56416b77674a537063426d46696642556449784c554b6778496b736f4e69416573515556756f6b686d48517852744267737673315445444b704964686f527674474e346e345a543554576f4d45632f417a53304f74733877567479444d6e4a3936504d306a4c69626a3949796b5271494a5565624f69542b476e6f493954534d566a35547a317839737a41346e6838677a5571375359685934483376412f61504a6b6f4a36536d3237595151434946503257714656516e497155706c35584b6a6d55516f48335568394f62786f4b796e6e50316c63366b4162647630682b4b794a435a387563745579624e53775437796b497332645141596449744d654e78357a3963514b677847776344392f6c474748536a32717770556f6f315163336675412b5a4c4e71397846634a5275494f4d794c5862594d412f486a694d68504b41784b47757a6c6e2f704256586d6e3253526a393557617062446e426d595849714a6c535a7976325a5151507778716d373275486363594839635762646e365333354e4972326254385a5473786947494763453164506c43697179556a4c7864306b74347846744e566c674c4145546d7270386f37546769626f6f53513178447a3466533634516b544c38786765524b707449746e5363335458796a4f3148684f6f5162717a752f654e533943634e78466b327249336d50505757575a7743524c71306779686463654d4c3332482f6b5977554430673032753578327774316a6c6f2b454c7775626d462b4c2b55584b416f41725047546e3752476f58615942584c7a4c4a423377753167584a4573307274514177646f58485a6b6e69494f4a4a4679334b4a786d5133417a5043327636654a494742677965656b677543335a684357346655464d774463645962575172444d58665747512b73313942564833483030365236767737565a2f7045386a7038706733316638344c74444e6158314d48346b354655626f4579387945795a486e51334d3941717779686935564b393031744572384e4d622b6e507343595634397379307a4446674756797372584e69444a416c4b702f4b427a437850555468776a684949684d73386f4d5144435a66534445417a366f6c7055677058377044486441326f6a6f56666f65734e475a57425471496a6d624e5379586c4c49354b7636786a4e344c566e6455325067655a704c346d34347345585461636f4c4b444552536174716d3274316c746242594d6962436c4863543045656e7248734359466e766d63543272326d4a5758414574495945574c386a764d65664e597462474a3671756f56727766764757487a5a6b69556c64544e43625a684b64793544674b50324556646c59733435692b624f3331694b767261326f56327165316e494937675233555a527145376a6f3238786f4e6353634d635174744e593445337545347968614a6333493361416c6c415a304d4e47346a534b41735a4c446e6e342f77434a55657663764278447039524c6e4579794170444d584c6133596339494332313262326362666a31674a57794463447a45754b564b5a4d79584a4341417339785263693179432b396f42382b38414d43574b327970596e6d4d3661736d7043696c4353526341456771355874427063773667524c6f6a6454446154464173417671416238393357454a7258573359547846767073646f77773270576f32515266586333326a663074317a734f4f5057564c366b55645970326f57424e7476414a36336a433863526631655637675a2b664d3050446754567a4d394f7159793153617131786456566b5755716c75756d614c5a756f42415339322b736375304f4d2f475a4f7672494f36555470624b4934474b68344f44476f3234417a784974724545643578497a4c454a6530516f796343435474356a4f524b434158627975656b61315641714747354d704f35667041617041444f6563556456574662325a5a724a50534272496545674757414469565a2b454669486a316a37445a6853454633414c50794d58744c593164794f4f6d63452f4f5a6d6f554e75474d52336a6c4c6d6b6d784b724d33363452366e58564236506a326d626f724e7433586a764d544f70566733517279663652357a79484236543061576f662b51686c464c5638717649785a7252765178467a4c36696169687367576272473170736841444d532f6c7a4c544675567a4b706b635a30485841795a4f5549366359644b6778466d4649686767525674564d615142387968364f6674475034355a74303250556a2f4d305044567a646e30457a32485636304b736f74774d6564306575757163633865686d74714e4f6a72794a6f4d5254326b704b78725a75687330656b315946394b324472782b5a6b36636d71776f59376c426742796a57555941457a6d4f53544f625972374f714f65704b3569706849566d56336c414c666351506448384c52515854765837724437545262576c786870666957795571657347616f5a557379556c51647443705770614b67304e694d53434930613442646f7a435a7544706c793053365a53554a51536370555759334e7943626b77743942617a5a3343516d72555a3343496c374a7a753137564578414c714a54327138727133674d574f756e47444f686372743341664845594e616e63475853396a35715a71567071585131304c4b6951514c424b67413665734366447979344a4766574750454648474a6f55344d566f416e4c537061584b564a53653654597335345767763841357551564c63664b563231677a6c5668314c6843553672556649517866444b78314a4d533272627349525459524a526349633639346b2b68734973562b483661733543632f486d432b737563594a2b30504558506c4b30794f30307a38552f776a37783548786f5a31492b512f76505165476a4e50316d5571703055305762694b496e724b694c6c615337586751765a7a6153574a776c356d554764392f53466172525765587637544f31537061577242396f63346d33784751433077454d7258724647785273446a763841764d71697a48394d3952415859633453566c6a475a494c4976455978794a425547586f716a5948646f64344a69777572744334697a554f534f38436e7a435362332b734b4754795a595251424b46477a775145594f7347556f7730434f41426a58425a3667447742423859344d794d436f376a39355331614b534a735a733872596e796a3162327661636d656656416e416e6a515369546d58537844314555786e73346151654a7979754743435a5773524a6b536c535947544c4a5359345344444a516867697a4355694445475a6e62476f64614a592b45456e716450703678356a782b334c4a574f334a2b7332764371384b7a6e767845644e4b5570514351535447445657316a6855366d61646a7171354d336c4a54414a43543849486d493939545346514a365479396c684c46683368735759695a795959535979437a54416d45494f565175474a624c4d515a4d4951596b534443705a6867676d4579344b435a636d4a677955644f6d4132786e4e50496652496a793369677a7154386850542b466a2b674a6b3669644664466d7370697171584674424868346a6d306b7855354b7053437051754d757238343061634f7072395a6e367578613346784854764f71374d5975466f3747625a546147782f35456559314e5270596b653665735872744d51526456306831584c794b626a6f6555566d553965305455336d444d6f4333313352787a67434d4b3436547736504553652b4a47594949644a4b77655972647567774931524b657a4a50644250495177656b5a7541484d6e6a4f4c4368706974545a6751514f4b7477686d6a52373777692b7566744b4e6a492b36787664416d6e3246784372724a435a382b584c6c49554155414f564b5364464d666442335236394b5750552f695946723170776f4d645656644b6c4b7972566c504f417375717062447961364c6256334b4f495854565346447571536568697858625734396b694965703139345364514853594e2b566731384e41304746715978684a4e425263675578306d57796b784967457771574959494a6c7346426736354b5661704236694550556a2b384d787975793944493039444c6c6e4d6849436a415536536d6b376b554179624e525a594e7248694579454d354a3169796f696d50595336446754477172776438494a6a63536c645349456d5342426a566a63353658696e647136712b4d2f614d48786c6b69714234673844614170316c567077447a3859653350534d4a4b3475434c4d4e6c4b6867674746494d535a457553596d445056474a6e546c4f31566346564579347357386730655931514c3373666a50566149626146455147636b6e33684377704861577a5a6954566879316830533569787843464e3573304f524c4d5a49346976315335786b52662f41476f71696d6f5747544d34472f6433754e30574e4e6c7a7558744b757231414932487642366e487338307a456e4b5358747836784c6166494f37764e6a53586f61677332654162625331415336674137733336306a4d743062312b364d723664783870573148682b54766f4f443654544c70305441465346416a6738556e72512b357838444b433250576358446d4454615659315359723753447a484c6168377a785649734a63704d547449353754686368624759757843656d57414641356a6f4859414456524c473057714b5664647850796a307978396e7044384971705754744a6173366c57613975567836777730324b3453735a4a2f4572366b4f54682b414f387a47317545544b6c5865424947362b7362476c307261595a4858755a5665354858594f6b364e7374504b3649496d6f556b70514a62683067355179536b6a776a545137716a762f414d544d74585a634e6e7a39597658686931414a6d454b4a4e684e4f623145597070744a77782b357a4e49586f4f55483234692b5468544c4f524f56516538755a2f38416c5835516e79337a676667787a576a626b3950694a424f4f56556d59556471466b66424e4253534f522b384e545633552b396e487867485430326a4f50714a6f4e6e3857375a5066546b574e5575376444764561326e75577863695a757071324750456d4c637047664e48515a5a4c45454a42684b424442426e737778786e41534757496853514554496e75614f7a4f784a70654a475a4278507a314932743578547a675a4d66784e4c68633155305a70687970345063395979723955584f41634344753949306b34784a43757a513167484930443241697635546b5a78784f7a694534696b4b515435627278557355715165386651324c467836797a424b764d6b4a4a6453517865373844486f644c6476584236694f31564f78736a6f59396b7169364a524d4d6c7167344d75426a704558375159716d6e6b4c6d712b464a59635475486e414f323153596461466d774a77366674444b556f715654676b6c79307851756464305934706339542b502f5a7067754f41786c4a787847694a434576764b6c4b2b30534b477a6b6e38526d57376b796d72784b73794d61716145365a554b494464486933765051774555446f4f666c4d784d53536f6c53696f377964596343414d41596e5979636d56396f553647437744445777316e4b6d576f7846745945305a6c705045736459307737614f5a4b4c6f6d45654e6f715861464c42375379387669434f4d4e677a53557674476e70444b79713536526e50344a5554786b53442b6b626b6a48316a6e4264724b36725745534a475a2f6975456a6d7052734957664245486377436445764a6d787173466c464156556a7456674f72765a5538774568586548574c436152614b2b503336664c347971757163747471396c666c6b2f55346e314a58304d674d6c4f556677355430484743717453733864344e314f707539342f6d4534586a744a4e554742556f757956626d6535334e5a373859753161744736695662644461673677446248616a75396b6c696b53387856635864303552344d5030494455616e65504c486353786f744673506d4e317a3069576a787061707374316b686143574f3559595036786c6c327753542f41417a536568647541503841556b617735315a534845785855414b3536776c6a6779565546526e306a696f5569664c4571636a7445376c5842535476536f585365594d5771377a6a42356c46745067376c344d58304f7a6b2b5650544e6b5648615377344b5a716d55452b544b385976615656795375424b6571633441594762796d6d6c727435677870444d7932684b5442514a6167775167776843784241794d5352554e375232665754494b576b6278486353655a3469596b32426670484445676d58705242625a473654676f4d2f48324165396d55624452347a395837753051324f5a302f5937446c317855564b5569536767416a346a7641504c377851723034543577314759377865676b495769544b427a6351587974764f2f77416555534c4e705070445963534f4d596769566c6c46546c742b2b4d787a356a3541344574364b724c62765344304f4970536f4563515044662b7555584b58324f444e4332766568426d79703538626f6d4559624c6e515547657a71314b41354c52784945344b54306e4f7474367064577951534a615334484538544647357978343654523039595163395a7a2b717764514a597643777848555377563944424252714633696434504537423635684b35717967704d486b596973484d54726b4639494d4d4a50496b42534b55575341384547456b6d433156424d4771596372724b74696b396f4f6146513469476559496a796a326e564e684e6735637557696f7135437038315756534a4c6b424358634b576b65397573706b377278527531682f344c6e2b38753036505076766a352f74382f764e74696d49564a644f58494336636853434d756a5736376f7937645663784f345948397071553662546744427a6a764d2b6e47464a555173464c427371526c306469783636394f45563978366a6a35533735494939594855666a506b555833766f62767075674e78552b304977444555307463554c633930674b44645152393473374f505a677467384744544b307a69684c4e38475939304d6f7348365135617476654c5a2b75424e4c67714b564153705578552b614c70374a30792b384576646e494848653268694c4671544959482b666d4b335857594b34412f50382f6d593756585534496c696e546d57626c31472f654a4a4a5967576a7132705959326a37784c56334432742f543452665337543037744d6c716c416b70634b7a6431327a5a566435516439373267664a724f4d4167656f352f6e336a436c6e58494a3944782f5074484578616c7975306f35676d68766773744c432b6157653976335045765136446457632f672f48694b57786432323462666e302b2f53513263783161335250516866654b53644344755a68793561776465735a573274794a4770304b466479635254746a696332686159387855685249436b5a694548636c5a3361324f2b4c394c4733335a6a32714b2b737a69506157503841556d65735038743472656b766c2b307366504d506e4862486b626c684d7232694656686e3854486257395a47524e44732f6a676e712f455552774477536963526964477732616c4b51414142792b384f553469574759787a43447a467a334e485a6e54386a3074417673786c5353385a62334c76354d4d546f6d41592f4e52536f703664444b4468536d304a3339626b7852757349626b38666d4f42414559796c6f705a526d7a5338772f4572556e63497074596247326f4a79716247784d665659714a363146652f516a64776979744a72484532716746554b4a39496e4b53514354784233486e453751656b65446a724e6c543751454451734e3573504d7873687354424b5a4d45727474564a736c767248467a6a69534b68336964574e7a5a686463776e6c7046473172637937556c6549544a716e393554776e7a6d4573436c5a5971584c4e7a4243776d54355945446d346568576a743168675977646f6779734942304b684862794a336c715a576e4241787558356942336d463559693666686842736f5135582b45553966786e706f465a676d366c6b734141547279347730474a496d70326232566c7936684b71755a4a42546353737757724e78574233513355394c5171793156344c527155574d4e77517a5434786a534f386c4532616761686747567a7a4175664f4d367935474232457a516f307263466c422f6e70695a617272366b676c4d34725164516f6c78753577674d704f487a6d58664b56656969445371372f4b6d6b763841434673354c66417654646f5934313862303664386633456b4d7566517748457045354c726b714f5a4c456a5377632b62665348554e573373754f494e753844324f733878755156396a4f6c6838364170615532556b74336d44614f2b7641772b6f415a542f5572324d77352b50316c6d783245543632596c4d6c48635151537451377165664e566a614848547378774f7067507136366b7933302b4d33633759305347544c6d414d4e4a67317537706177473755785876303771326378644775563135583764706e3671686d53796336776f66437049756e6d3778556177715a6f4c35646734474973785043564b55564b64453149643036545563526233682b7556784c6736386465346c625a74627278362f324d46774b6f6e425a564a4b7379526d655859736b356935444f4c427766574f4f35434376426a6a735a534877524f6d34506941576c5a6d79684d5746454665554a556f416d2f646137446476486e7875564749735850306d625a53526a79327750544f51507647395668464a57535a744f46652b686c494f35785a535834454f446534693752565135335574673963544f756139422f564752307a4f4a6254657a716252725a597a494a4f56593049484835547969327a737657495646595a455479384b41335247366473454b6b305145546d52695063495355714452325a324a306e4363564a53424267785245324748533146494b763049596f696d5050455043594c4547634477524d7673773545654e314a66664844306c3166745a5355716371475576676e6a7a4f364830364f3633746a346d4d465a3738546e654f62527a616c655a52594433556a5166316a61302b6a536b636466574e556865424b4b4f724c77566c6374565752784d72464d6b416b485639344556367167473352756f73396e614f386d6d6f576456456b63597353706a456d435470347757494a50704436615554416c595161484d514e497250564c4b576d444b55726843396b507a444c354d35514f6b47466e655a474b4a346137694a43475435676b6b4c536f734864394769664c4d487a524c4a3949426f485639503677785578415a387868686d48496c704b3569776c53334364424d4961345476414f387446545574327a6744724c656b55353342636e3439424539616f4139326d374e44754372336964484a75664e6f6f74744a396c7072717a48336a6b78577163414130776758594b4c70424a33754c4470423753547949575a6651315953695a4c6d5757556b4a42314a494c4d643463445343387345376830697248786753365256796c68416d6a4e4c6d4a414437693178785a77343450416247526956366943335053557a5171536f53314b7a6732537368335151564943755959705068422b793374442b487649424c44426a725a7a4170696c6d59556c4d6b674f70497a45704e776c494633636e6b48676c7335474d343734475a5876734179764737747a6962584674725a644a544a7930363561416c6768676c53526f4c6161747a7647737570557141415238356a666f6d4c6b737762346a6b546c3156747a557a46715553565358756b6764332f63425933316975364d7777544e4b7356706a4168464c69535641462b367263626a4e75483635786c32306b54515667597a536f54556d586d4c2b386857384b48446b6548555168474b4e6d4577794d7972596d6b437069313730727972535178436e63462b4266526f745757465748666a6a36784e784331346d7737583864636f67676c41576b365a726b48546547486e43374d6c7478366d56517639494f4f7878386f52573034556830736d616b756c647751526655584839596c656d5163483169716e4b74673871656f6c4b6366584e6c396e5579307a45685756627076626942626d3758447863585732737548357759542b48564b32366f34794f50542b6674465536697046724e504e6c496b465a65564f6c4268794253374b426264425536333269746f343745524e75684a7238796f3578314269784f786337506b414375436b33536f626944476d6f44644a6c4d3233673854513462374f31327a724365517559614b346b322b6b312b45374e795a44454171567856663030686755434c4c6b78304242515a394854702b4b30566b77426e4a48427a435455684f636379796c72724b317a6e34784958454a72675a5546775749726643715a34573445656a6b52315446546148786842555267636e6d4779556b6d38454267514353547a4435457143784233546f6d4362433970496c7a545043444d47624c6b646b6e532b596275572b4557576f6e424d344f6577684533594e5962386156666a6d48324d4a7331465334444d426e70474b355061564c32416e422b2f4b2f6d5639307842485055527133723647566a59656550395038416e456343423348336b6d31666a3970665237447a6c48764b51676463782f37667a68794d70377854576754535964735a5479727257705a5044756a30763677372b6b4f706944613536434d7064444a6c2f7743484a5147336d35387935684c61797465692f744f7737645445566673354d6d4b557356435a616c4736676b6c5852334259437744694d38316879574c64655a724a726c52516f516b443752542f415058556f3358564c562f4367442f7955714f3231714f735966464c443051666557443265306242356b3873543853413456754979366342416559677a414f76765059666e2f4d6f32683255705a4e484d584b5373716b704d774653796f355533554c323063395245376b734f46344a6b30367934324466302b55354e55545a6b7770544c6452766b516b4653754c4a43626b6a587a693356574165527a4c39746d426d62716a3251715a346c476372734571434f3475387979677179625a6443325937744c6d454c56355a77652b63524c36746345707a69644a6b55307041434558435133766b426d4679415146576257474b7461384c2b387a57737359376d372f442b2f615a5847396b544f576741796b49564d4369796c4f6f482f4541645076464f3532684b3037587a6e67792b4e61436842424a48772b33667048796632656c51696e6b6f796f415551456839375a696f33556f3854444e54714547462b63703155574f43352b455631324430565548556b79466c752b684b5535332b634d515334393433357770645658594d4e6b48735934432b6e336661487036664b536b624e306c4f4237387851596a504d79754831634d4234784667715833682f626a2b66775131314e396e5467664c4d2b45366a6b7a43744d6f6771624f51564132334b424c4f4e4e335742653754676863486a2b667a39345971314e7138742f50352f714f70644c4c6e424d3655633655754141474b6448436f6531433272356c58492f6235796d6258704a7173344a2b782b557071694679695a5a754c6a6934346a72614b2b466443565038455a586c4c4148694356566c534255414f46484b744944393177435331387966744330596737766f5a6f46414435587079442f4f786c474c303361533555784448737067594f774b62576663374a45466b5a794f524f527472737037694d396b646f524c5169576f4f704c7049476e634a436c4136424e724f30584b626d7059454449506155645870526557594847492b6e3763556f664b6f714131554163723966434e427464574f6e4d7a55384f74505869527064756164616d63645162667a4b4153656a774931365a775a4c65484f426b52355359744b6d614b59757a4b4755767744325068466c4c30666f5a556654324a31454f6830545079584e773658727165416972754d756852316932625367754d734d45556573724645447936686f374a6b38517552537041756f45384243327a44552b735a53554533444e4542524a5a7a3268386952427865597a70704d526963434a3253556a73356375587551684b664a494565593174704e706c75705269654c6d417334647670465631573342635a78783949304c6a70505a7338714c6e30697939724f3234794672436a456a326c69327341574f446a7243323838796f4b50452b635678554f704a2b35683448704c5a4d776746524a5a764d7779732b5570624a78366459444b4351423167697352494e303236776a39572f55722b593861634563474579352b594f4446704c6479354553796254677a334e45356e596e6d61494a6e596e32514c436b485253534350335347503368756d55732f456875426d4b4b4368707141424568425358757255713433314f377a69384c516c75346b6b6a37665152345637313972475035316d53785046706935685543317371514347435162414563412f6e464e334c746b2f7754555370555848387a33684f4759724d566e51396a6c6333664c38586e456269507241737258672b6b6e55317037513546585554657a70434c4675704a6638416845432f7577366c41544c442f774268744a55685379445a6a763841336b685832685a786e4d437844736879357164624f4f49666e70484842475a5751484f4a557647637270576c505a6b6c52336b48666c443733423844446b754a51716658502b766e2f614e4f6c79515165656e2b2f6c4273546f733651704f68736e77305479734c644955526e44434d7174326b71305634586930366d5557732b6a466e3638644e447a68744e72566e63686a72364b37317730335748566b7570526d5351685a446b6161333650303552655570666b6737576d4a64572b6e594138724d646a6c582b7a465669676857596a514b4768505063376352464c796e4462655a7171513668733547507446394e744f4a6b6c67514f3045334b445a676e34796548434a61703679516563592f4d4b74466368684d35692b3142566b6c766d546c7972494a53546346695271486373646252646f6f634b536670386f75396b445948722b59485634756b69796c6742694f38647734456b656b474b6d3659697477484f594e54567972744e4367644d316a7a42744850554f36342b55355850726d4e734e787a737932513558636c4263503038374e436a56337a39354a6164446f4e70795a61536d7041445737366550425163654d474c7246474e30724e70717963375a792b5a6835485565636135416d494330456e4a55377350446a78694e764749652f6e4d47576c522b6b466a45444f5a4e464d6f6c3344384769435951475a70634332517170313053535238792b346e774a3138486a67435a4249453375452b7a674a593145306677797750384179562b55466a48574c335a36545430574630636a334a535372356a336a356e5477697662724b7178795978615861575661375a74586a797574645549636367793955446e624636716b6344465664537037475768555a5561314f392f4b4769394959706157304b4f326d3931544a534c7472724637533648395659487a674166574263664972396f636d523267786154494b554142537a636b37682b5a6978722f4b51624b317965356b364c53586167467a774a524c78644d305766532f49786c5732466a676a7447746f3271504d487170536c4545584461516c367933496a4b3356426777716c7a49517a4f583436636f59753946774245324658624d73457959624141657347504e4a774d514d566945696a6d675a70684355384c416e6b4f486a46746442714d6272446766544d5435315a4f31426b7751315454436d316b6732765a513477612b7763667a6d5038724e59507166326d5978797455745a414e6b4335487a462f7342435332546d6156465952506e4d6e5772556c6d47717236626f73566854445a754f49307777356273345a2f4851422b642f4b457679594c446a457045395341684b4d716c4b4a7a462f6d55705a4437774371434933354c644f304d41642b3050713171457a697275324739677a6a7272435641784a474e676851714c2b462b757068654d784a47444b5670566c4f59653451397778537068647478454f586a7047466c4d746c59366955424c6e4a505a5441436c61626c4c4d78626577793661615869797457436364347471545a375348326832395a665755535a694f31437757446b707543666d486950446c436857527a495730716470456c7331564c4757586f7a6b7671344973664e3463696e5045445542534d7a555673784b6b392b5769624b5541466f574d795663387073447750534848557656375935486365737a52534779756348735a7a4432673745396c2f654b4759727369416c55712b5a4354627571636b6f6578427548336732304b3771483448666d4b5a745150655077346d41714b4661436c5075755069494174727231687759486b7a6c4439466c3071574347576f66374c2f5733724332344f566c6c417a444451365268424b4654456865564e79556a51633236516b326b6e4573667077713542676b75644c4e6b7a5354726f3539494d7134354b79754c5562674e4e5652346b674953436957736744764b5463395842696b775947577775526d615370777657332f504d7875625a356a6549746e59512b36494149367957495053436e4351502b496e45484d336e733732566b6d575a367746714369454a4e776e4b3132336e3651515876495a75303366704547434972784357556c387a694d6e5746314f4d7939527449674a56474e61435a6457456c2b7a486a395952716c506c71497454375a692b5a4d44654d564563464f6e655731553569544773544435456b416d784f352b4478615653787a4e4c53615934334e4c4b57705253532b3155543279683351545a43534139743536786f426d6f543250665034482b59466c5436757a793139776466695a695a364a382b59706141755a6d4c6c516367486d72516563476f415832767a50516f3946434247774d667a70487443465577433571772b6d525065642b4a30454961744d356d6664743152325644366e6a3851796474453643704357626a76302f4d52474142377372703461412b484d6655553774474b545a675364774268566146327750715a6a324b554f444b613361507331646c4c377438705741436f6b5a5363704e6872774e6f303074576f624b2b50553935796150654e37382f44744a34706a786d533356335567714c42793442536b453853517146366a5676637654762f50784761665272552f48582f5a342b6f6947707849695956454d364d6a486b464b536544325635434b684a494d764c537530445066502b5974714b674a6c7a4c756534427a4c5a6a3954355279563578474d6551666e456f534a67796b68774154774469317a313949734546446b5163677769724a4f564575374135315733574830506c41674b764c665351435952686b6f4b576b4779685a4949767663676548704157644f494c4e74454d726c666942797842532b36795135303551757351776636592f6e655a4f5a696b784c6d3479716347374f706a72705a6a626738615170516e694331704752473244596b366a4c557275464f5168375a5667704b692f41724236474650574677594a4a5938545430564f69624b524c55674b4b565a56416c6944765a397a35755265466334774f782f6541574b73546e47522f503532784a5944534b6b71374654464d7a33663467435152725973503049685353342b4d693677574c7537695253735335705541536f584936574c65485856584b476273486952677375444e54545445716c71533767452b542f63454747345571524b4c426c634e4b364e4b5653564a4a423935423374773867336c41616467712f4b4671464a6670316e4239744b705972465a305a6371556849335a57647878424a4a6a62433731426c4662504c5977444261635470687a717953774f385272794135777535685542786b6d4e705a72574a48515462594c55716b68557555727554686b7a473746514c466a614b4b7675596a6f5a654a365a6d507844435a74425079544e434355714769687936634930582f414b717a4c72587958783250662b656b7047497772795a632f5543666f576252386f767a416936646838644f675536675044396661496b792f434b6d5a546e756e756e564a466a7a35486e48596e5a6d6e704e6f6b4b44724252785076422f7248546f306c7a305442384b782b365839494236316359595a6b7178586b47512f5945483353334c3958696d33683966565938616c75386f7235524357416530596669326d7577504c58492b45746165785365544d6e6946566c4259687839597771716954677a636f514d77457a2b497a4f7a56633935724a35384662772f446f386274645a513450576139432b59764854312f7838704f564c6b6d576d644d535a79314a436a32696955673777454267774c324c776f3350764b6a6a48667559746a61584e536e616f4f4267632f552f346746587451584f593930426b67614138414e77677870532f4a355078686a5256316a6a7233694f75784359706666462f68532b6a372b63573071554467786f49555957584a6e646b6a764b75713351517372356a65794f6b59624f35375466374d4a7979457353584a555835364436576975624d636647656631584e684a6d65727071555435695650717056694c335a787a596d486f4d726e3478764f41523643447936307151744f623448416477464261566a77492f566f6b44623168455a5038415053417a367674436b38546d38416b672b716a4542646f4d49475734334e73707463344461614954666947654f70474d45775365414942517164595534304a4e74343771452f5177327a335349413678684f5579637774376e5573472b742f474b2f5673474d37536b315034794573374b377232766d4a752b3644436b726b5154674b5a4b70716330326156456e766d353441456630675175464749772b364150535a6245316a4b5642785a4a6270766a5370424a775a54314462566b714f714b53706a63793954784a75504b4f65734544357a6b66422b3033744258676d596c374b6c69632b684864426365495a75555a35544862726b66582f63657938672b682f6e34682b4e7a796e396c6d356e41576b6c513062522b4f3736774849475231784631444c737550555254695655557a317143743968754f59662b7252774f3770367977696a6141593977544577512f3769334a462b5164754a62776764355675596d326e503345585473613746436c4c573270562f466c4150714868534b37766849793449426b39422b30357674444a6e5430496d716c717a6439675178374d725570494f2b325974794d656e70396b626653656431574762634f38535579316f475336584c33424548596f4a7a413039684337666a47644e6935523252336f6d4a552f494f546145436e326977394a613834594150724e627431693069736f35595433703656416f4351354c6b41674455324f6e4b4371593738514e516746524f665445797450734c6953306853614f597844683871543470555152347862346d6275616670535a4a676f7143544b626c48546f4f756d45644f78424b684e6a393436644d2f696d49496c67356a34434f6b695a4f753273556d386f6c42473845672b63524f6b734d39726c5a4b4c545a614a365033753474754f59573949366469627242506174683839684d57716e58776e44752f7a697a645769436f4d6b4d524e58324d696f546d485a7a556e525353465736694557615a483934523165706450644f4a6d63573244537335704d355573693453526d534730796b4d552b7356546f46427973394270662f7741695a427474514e3865682b76592f695a35654256744f6c534653653152636855733532346a4c37337046432f77397932345456723855306d6f594864745078342f386d5372457938784338795344644a44456563536f735559784e42734f4f4344395a453136456b6b42314865626c3434314f2f76474b4a5242795a585541457973796e57705369623253414f366e71536653476a4151375a536538766142304839357671544730532b4a53575a747833672f7264474d4b7a756d666353387a7531556f7a5a6e61797931764e3262306935706241755662316c69733551447549686c564b677358495065353667737a3673597473674b7a6c4a7a6945537741704a34356742344a2f4c30685a795152386f51395a50457167715570523372575165706258665a6f3674522b30682b426944534a6f424235663139435838494e6c4a34677247695a6a6f494465387a366c676b426f724559626d4e7a67543667514d366c4d2b573435474f636b4c694c4179514a5456715a793475547277495642566a4f42477631694f706c7544774c412b746f764932434a5373475a475569344c66753336452b635354326e416435724d4b41505a7a486343564e5431417a416548346b5a316a455a487845755a33446a34536464574b376b6b2b376d64333063756f65462f4f4256665a4c534342357559706e31766154465a58756576384178443171324b4d77664e796343465473546e4a6c396e54793154466e5652736c49666a385236634e594b72513732335077507a2f414f536e714e65454f4547542b422f6d4c4b6643617559734b6e4a7a4d5841633551654f586634786f725374597858784d31745139682f71662b54553165453141555572377a424e30364546494e764f47674552444d47356969753244724b7075796c39464b736b654a2b304d584d577841377833672f73565a6c566338725079536d536e6f566d353847686769576664314d364a675779744e53702f436b6f514e35417566346c717566574f416b4d354d4e5869314d6b735a3874787a4a395242514a664d676f4d48576d4f6e5165596d4f6b3569764643516b324a69444f45355a74436c31367162655475384f4d41637867784d335070774648556a6343475056512b3336455a4d4c41674e52715949514442467948695a45736f4b716f70315a3545325a4b5037696948366757506a48627047777a61344e3758363655776e6f6c314352765063582f41444a742f77427354775a48496d2b77583275304535684e4b3664522f7742514f6e2b644c734f7252425753444e53715653567141576b3143446f526c583548555144566739592b76554f6e7548457a654a2b796d696d45716b545a6b6858423836664a562f57467453434f4a6172385373552b324d78446966737371416c6b4c4369506954793335547669754e4f366e4a35457474346b726a4134694f5a73625849566563417a393070494639584436326754556d4d62596f366c69323764394f302b71734d726b6f594a6c7135755166704342704b39325a592f576265514f666e4d374f704b74426463676d373930694c586b4c6a414d44396532637350744b716d7555426443303557624d6b2b5033686136666d4e2f584c6a5053567a3856536f616a552b4474484c70797068747245595a7a4b3648454567335675382b55466253534f6b3672564a367833683963674a424b724f2f7743754d5562616d4a3445732b63753372445a7457794f375945754f4b7951772f326945716d54672f366a45626e4d6f7161686d41765a39334a6a34757144524d3879532f65414c5675356447697742456d5568484f3238656672425a6767522f68552f4b6b6c6c61466b674f584f72634e4236525565707247327247506374535a614453384a7135366e55524c54775348504e796453654d6156656d725165737962646263374535784e5868477a794a597548504f476557427a69494e7a48676d61576a777371393146765344416979776a6d6b774d66455142792f4d7759574b5a34336b3055704c4d6a4d775a7a66314e6f5a67524a596d4a386532326f61527850716b42512f79356634692f4a4c744259677a6e6d4d2b326f6b6c4e4853742f314b6775656f516b2f654f3654687a4d30766132727148565554314c652b58335541614d4570747831666445536353614d6255327039493664784f2f7167344548504f2f7048547055754f6b356756614570517053374a5343536457413130764854706d4b7242517638525351506c5459355164353471365748505741497a4442784d7069327a313752474d5364325a6d352b466c42494c4e48455467596d72557357455342494a67356c474a7a496b4679596a4d6e47594e4d6b694a42676b536448506d79565a3555786374584643696b2b6b546d6469624c422f617869456c684d55696f535039514d7075536b7436677833426b63696276412f624e534c59546b545a4372663952442b462f534f784f7a4f68306d49793536416f4e4d5352597352364b4543523677676364494e4e77795772514e302f4932674e676a425959746e37503841796b487261493277785a46565a67796446494870413759572b4b4b725a61517257576e794564695475697964734a546e2f4c41365233507249396e754949763266536477626f5945377657474e6f35784b5637427148757a566a2f632f7742594171653445634c694f6850336c633359757050757a683155484a505547494651394977367438597a49793967616f367a6b6a6f5036775777656b5764552f72484f463742396d58584e4b2b566750534f4e495057514e5777377a5430654270466b7045534b38644974726433574d354f447471664b4742596f7647644a686148736c2b734546674d38686a75503031436a5055544d67305a4b464b4c384c4344327852616334787632327044706f36567a646c7a7a6271454a314869494c4167354d77474f626334685676327453734a5077532f7730394754636a71544853496752496a704d4e6c7932694d77735179584d41386450312b74596a4d3745733751382f4f4a6b542f32513d3d, 1, 0),
(6, 'subcategoriaNovia', 0x2f696d616765732f70726f647563746f6176617461722e706e67, 1, 1);
INSERT INTO `subtipoproducto` (`id`, `nombre`, `imagen`, `idtipoproducto`, `eliminado`) VALUES
(7, 'subvestivdos3', 0x646174613a696d6167652f6a7065673b6261736536342c2f396a2f34414151536b5a4a5267414241514141415141424141442f3277434541416b4742784d544568555345784d5646525558465263594678675846786358474267574678635846685556467859594853676747426f6c48525556495445684a536b724c6934754678387a4f444d744e7967744c69734243676f4b4467304f477841514779306c494359744c5330744c5330744c5330744c5330744c5330744c5330744c5330744c5330744c5330744c5330744c5330744c5330744c5330744c5330744c5330744c5330744c662f4141424549414c634246414d4245514143455145444551482f78414163414141444151454241514542414141414141414141414145425159444167634241416a2f784141384541414241774d44416755434177594642414d424141414241674d524141516842524978426b4554496c466863544b425170476842794e6973634868464256533066416b636f4c784d304f5346762f4541426f4241414d424151454241414141414141414141414141414944424145414251622f784141304551414341674545414151444277554141674d4141414142416741444551515349544554496b46524647474242544a786b61477838435042306548784d3049564a464c2f3267414d41774541416845444551412f414c62616b4441724444453462555432724f544379425071392f624664744d7a634a326c61674d6d74327a4e387957374e5a4d455458374a556f664e5357444c5368446751427a523557536150773472664f6271335133455650714746533851577578334f6d37694269764766556c7a694a4f7150704e6b334a4e48585770504d4961686a4d56754c5759514a717976544d35386f6a6b7350724d64547331464a6b5269715470794f34336544314a514a554351445358724d51344f5a30303270556d6b575a67506b7a375961635375545457765a5577734c5a784b7074714246546645326e73774e733557794b44646e7563562b63345a42516f4b4859303147326b4d494256766558576c61776c7849484237673137745636324c784f5835773075436e5a6a496d31713768436f394b45744756727a4a6253704b69545362476e6f31714d536f30356f41376a57494d636d49766339546e556230724f306355693633636343495250574242464978475456746974784d4a6872544d5673456d6270525734676b7a5a446445424d684c62564742417a4e2f446a4e5a746e5a6b6a31687177625163356f6d47424e555a4d5164503362425353374d6b34394455796c4239365769736b63523637713971306d636365302f6c54316573644364344c47526d6f64577155736c4a4954326f747a474e464b67637a30524e78694b39444d386e452b6550516b7a5a716879756e544e3179734a6d695a46654a6f4443416e656b7365497554774b556f3350436334457a3673576c704141674b556f41667a4e4e757771385359735a4b3343385a7a586d4f4d3977434d776133667a586d585662546d4b326e4d4d446b30796b5a4d614d4350374248686f33424955666d766670477865493453643667315a77412b574438554c735359594f4a49324472716a394a4a4a394b7749544d4c69556c746f4677555345354e45644b5436545045574e6446365966456c7a48734b55326a42453378633843664c7a393276596138652b767733784d356d586955695a6b7a353436653955314148696357346e357038704f354a7178613272387978586941797330563850493538773545352f4b724b3764346a6c496a4b32306b4c426b545643637a53666149726d7943466b41556c2b35516c6878464770616f5565575946495a6a30495a35354d787339575365394c3247595349335975306e7658596d5a68725436665775784d4d4b51735673417a6475746d51706f5551676d464e706f344d36662b6d695551535a453951645071667a744a416f6a555768492b336d5348557a447254496262615635653847684f6e4d7372314b6951373136392b4a4b76754458436f4348385442565868394452625a6e6a7a2b67454d514b7078494a795257547030465630365a7156516d45424d3171455a6f59516a72526b684b4e7769542f414370594f336b54534d7a7a2f724a39313238415639434d44307a6b782b6e35565066617a504a54775a7864306b7a494675696b324c6d615a3030705469676c4e4b70724f37453556784b6d31537043516b6b2f657659556b44456669457173304c2b6f41305968596d6c767037615449514b4d4d5231424b417833617638416142545046614473454f57373544516c6a4d78504d4e555554634b727a3771777a5a4d6f52654a73477846535070784438494759754d7a5364685577447035676873707170627742677956744d51595a706a68447950384175464c5277624269646767543045616b6c744954426b2b6b563759634b495179596e76485a4d6e765357356a306b6231434a6d707334614e394a4d4a61494f4352564159474b4968624e34346e764e626854427959657a724467727644457a635a563650656c5945314f347759594f59395a58517a4966626d6945457739704e4841686256704f5656516963637a4d516b494552464f7a43416737317168584b51667457627032324b722f514c59676c5461514979594662766e624a3431723271574b5831706259337053593343494a484d54536a63767448727058497a6d656e4f7170684d6e67536a51356d346e77717270776d5a4e444367656f4b4f49456e306f4c47774961444d6f39494f3173596a48656c5a7a365173524e3157796e77314c6a336d7373484553363853627774415550536b347a4552614746754c4345416b6b2f6c5369684a774a73394d365836555332674b55504d6561747170434347424d2b6f4c545973525248754749497a785243484e3030516e51793372594a686c7959516134776657656376736b7571563731417a354d71526343457474474b5554484366517961474e426e5867554a544d4571444e3950732f3371442f4142555656586e426b397451775a53616b33394e656e5a36535a427a466c3654536d4d6142456c335a54537473504d57506161665374475a6b797474494b31705141636d744c6b436371626a6957467030516e3851584d543831696d356837526a563067397a3632686870577753434f5a3756356475735a4832764c4530536c64776a5a545932626b4c416e68536b6e6254686259526c635950726a694b6570454f47485074507475754933334343663455482b745556502f774470676677456e6451667572447a714b32787632423141456b6f506d414863704e556933484f33492b582b496e77686e4763544b7936355957726151552b353472713964533577444750704c554753492b73373970345332744b76673155727177797069437058677a743078577a6f6e312b7758634d4c62536f704a474978514f7534596768384765537639484f4252436d6a503841447766656b6975566a557a30427842716b794b444b624d316b324832566b305435316766656d4252367743786a6c2f51477969554766316d736e416e336b6e65756f626332524b6832484e523257444f4a5367347a4774766271574156434232465950637a63776658394e5534307049394b78775745776b596b7630486f4b6c376b4f543556464d66464656586b63795543656a36543079307a6b4154546c514c437848433168496f69514951456c65703341714937476c46675443784537526f78436d79445769644462635673457a653956354b462b70793978437859376954556131356c4262453346684272696d4a6f65637273714572444454685670516b51673030735749576e356f712f76514c443559377632354171312b704b766356504d357055624d54615457546f537a6f5a4f536d4d534a7750372f4147716536786c474548315055645571486c7a39505744337a7a4675704f38464b6c534157776366686b7a49417046657079663676483779784e495742616f5a2f474d334e664c4c6158454b4c365349415643434436545454725572554f4357583976387a452b7a7a61536a6556767a6d4855476e74334b5733314b46764943694442335a796b45444a50464f625a714b6c64754d2f5742536261624772547a664f4f4c566c702b334c4d6b51493769434469423270716f6a3147755233563256507550724966573949764c64344a445a577754683047516e313344745562615a712b756f314c3062373363707449645a5a5146714a5650346a362f776a306f6752574e324d2f7a30696d334f6354742f544c4335334a3270436c5a4a523556664e43746d6b7659676a422f4978762f414e6d6f412b6e36535476756d6e375a616c5737684b5152747a436a392b445531315a70664e5a7839522f444c4b3755745162787a48756939527649455863653347372f414d6f71716a584e31624a4c394d702f3855734e4f7647336b376d314251377765506b5636646469754d71637a7a33725a4468684e6c6c4d38436d3469795a49616554634a4a623541705743773870683767447a45723977705249506c435a33664935724153427a432b3865496f6631654d492f4d3936686656456e797978644f4d63786a3031727a78584553505563553754366c6963474a7570554469574e76594e764c443230626f673154596f5937704f6849346a707531464346685a6d685954476133457a4d6e304b51792f694d38316e526d59356a52335530396a574d2b4951454557385630676b744741415144563754794530595845776e4d524e476d43644e30716f70304d74334b3051545074322f4f4b462b524e555453796272465845497444464e436c324363706d536b69707a477764324b416d454a773052494e61766378756f6174326171354d5230594f704d306f357a47436645747951423372753576552f6453617434545161624d716a2f38415078373146713769713752332b332b3536583266704259336950312b2f774471526a32714c556b62314579543957636950586a6d764e427449374a2f5765797931566e67596a617743464e797478414b456c51516f7a6a423368735a372f465730365a47544c6e7230505036547a374e5136763542776655663567756f583639344a4a556b2f536b44636b62675a4943652f78484559717266362b2f3145797647306763483139442f507868392f66764d73693451704b6d2f77445678745041437766362b7446613167554f6e495036666a45566970334b507766332f435a3658317553734e5046726171424b465a42564d5241493548663169695237462b38526a3566366757366570786c41636a336a713974724f464b6464575441494a50396f39714a78567a754a6b4839554841456c4e53312b78747a4c6269314b374151506a7a5a7155303139726b787765316868704a33485864795842744f4a342f76536a6f77664d7a4850704c394d55504257554e3172694c746e61366b6f636a4469655166512b6f6f5875423874677952305a6a3666776d33566e6a326a2f396d6d6b4c527575464568494731497a35385a4a6e6b442b5939717630476e4f66464d383357586876494a5776334370723170353034304f3353786237455a55636b3179316852784d4a6b4a314576776d6e5366784b4f6664527150556e465a4572303438776b576d3633454a39363835554a6c3750675431586f2b78516c75594578587056566852504f73624a6a6652485148462b6d61643652555a3357716f514f614573424e784a54574f72694a43424a704c572b304d4a454e674837687a656f6b447446597553636d59524c4b7730776a4a4e48737a4f427847374e734252684d54737a4c553044596130695a4968536f4a48765141773538572f46626d454a2b7437676b774b30476352484e7261546b30595741544754624556754d544f3532384d5574786b54524644376842715571593447594e705574554368465a59346e4d34555131656c71465038444169664679597930725444744a5565394f715869597835692f5546424a49465a596f4d35574d584f583278436e4445416436677638745a4d746f5132574252466c6d2b70354b5671504a564873416f524d313535444256352f6d5a37696855334b76702f6953717251687877756b715a624b6c4b4534674441476553596f3673416344502b5a6c6d3678786a6a4d7a76645a61556f424b564167375a45655a424133452b2f5036436e4256786b6a6d454b3742786e6a2b2f70486d6e58545951564a7750712b50556a506f4b7a61564f612b766239354659485a734e33312b4d3362316c7368647570776547344343434a4253636e4567794f634761725668316e75545755754d4f42794a4f72304b44765166454b4d3547465236474a347a6e3271646b592b5547553161705163486a4d36764f6f43704251435647504d68512b6b384861656577726d596a48392f3751726167567a3765337438354e445158486c53303234736b3443556b6e2b5655492b56346e6d4e67486b796f3033396d56386f4a334a533266566168492b795a726a52597836684c716b5756326d2f73306353527666544863424a4f666b304a2b7a53334a4d4536383941533036666a77664437744b4b6674795035316470694e753332346b646779632b387a7531674b716b6d4b69327875534b3047446949757562634b74466763796d50736f5569355179344d62557855356e6e2b6e6161516f486b314d5641366c4f374d39473068316157343478464f514845517835684165556b776e763841797248506f4a77454631527855514a4a704c6b6a714d55526459364f7078516d685243544e646836543048534e4a53326b5971774c675245592b474257346e544e783043756e524272576f3449547a53324d4e524a555771795354514b7068543675304e6274684348325451487a52724f4d61326f564e4d454178797969525252632f4c747a5332454d47444f326f35696877424d4f594f30516b7a46646b51546e316a47304a576561495a4d774b4963474341664e5267436445446c6d436f6b6d5a704a484d5942424e53736b464f306f4367654165506b31357571645735417a2b30743037736e33546a39344263587a467355742b456c795a7959473341776c4d52324835564d2b6f5373444b67793754303233416b4f522f6551765647746c61584745705332327451564342395245515665697045597869736f735a786b634431414539477567567347624a50755a4c704d43597a3630386e306a47684f6e5774772b536c7665546a435a494139774b334f4f686d5450596963744b2b78364575794a5757305a7a752b6f51415241536b676a507254567063386e695132666146594f46475933307a70526151417438375a67375151536d594d456e456a356f317050715a4339344a79424c4870337053795a456f59515663376c2b64552f4b706a3756556c4b64346b396c396a396d556f414177414237552b4a69356a5555465245393649596777743937796b7047355559487165777243434f7075594870646b57327a75494b3145715752787550596577346f616b3244356e6b7a57624d45756d4a56544442455273313037452b336d6d4b66624b5267534a2f4f67595a45334f49497a6f7a62583853717749424f334578677a596b354f42324659544e454c613030714e42747a4e7a435636556e754b33594a3259545a6165415a6969416d513553696d696d514a36387a58476141597131432b4a774b457777735546424a6d687844366e33776a58596e5a6e614c516d75784f7a4432726443526b31336949765a6d3747505168435667416e2f41456a4e614c56494a48704d3248504d576e724a744a327051667669764e663759724138716d574c396d75526b6d59336657537a787353507a70412b32433351784844374f55444a4d3464765833454262617477505943756533565067316b63776854537077776e36777433743075723550464f5262382b5a703169616344796962765033414a4452474f2f65686336726468434a7464656a7743344d2b576655537a4c62686851483531314f75747955664759567632645552767236684b6e314a79556b67392b337a516e55366866764c7837796634656e42775a4d6a56336b6c536e424b74784363344352394a727a374e55796e67356a36644d72786170536c54754d6b6d662f56526c7367792f49516a62465631594261787a49716d7577714d43483435786b796e3054396e796e6b68546f384e73352f695077447750632f6c58703666524f336d666a393535642f326c68764c4c48702f7078717a53744c57346c66314b56456d415947414d5a5035313646564972344538362f554e63637444323233564c65436b674942547356334a326a646a30482b394d47347351527836524a78675262636946416638782f376f434a6f6a76526c536b303276714133634c7531516852396a54494a6b5662727a3936454745524743627451346f393567346d7a65716b63316f6365737a4549463844523567784a62706d6c6b786b304c366953457a464c337a73516d3174436530316d544e6a6c7179506574784d6d70495452546f4d39646a6d756d346e44577244674375426d375a6a63334b6c64344662696342413344504761374d325a493031616a4a774b7a615a75345456566b6841387976734f614378307247574d4a465a7a78415536327746464842423556696657764c663758722f384151666e4c6b2b7a3349795a7562317436557037526b556f36317257323478383452303368444a687470614d6a6b7a393570697053526b35503578445732394362366b32326c73717a786e626b35397170613275744e773636346955334d2b44504f64515a4b5853326f5935536f6377654b3853334f2f6232506c506f6147553138647a617a36655a55557155347236684b54383164567045774e7878373953612b36775a784b3277654f395453626453454a2b6c5743435057765472786e626a41485874504c636e4763357a436d4747637257504d676e76323961414d6f7a38706e6e4a7750574670647431704b30464a6a42494d2f593031536a6a654f5a685378547461532b7061436c317a633036456e6b2b6f6e30727a374e4d6c7475556242395a36564772616d76446a496a4235705454615571584d704955654a3756327362774539383853546978386954656f4e2b61596a306b64757872774c392b374a4539476c687434677246737052386f6e7437666e51316f376e4369477a67446d55656b61556c6f3731625672357a6b4a2b423350765875615054696e7a4e7966322f4365646662763448416c414e59575071466571756f3978496a535053614a315a437563482b394e46696d4c4e5a6a43317530714a4150422f556a2f6e3530593536674545525a64616275654b2b32774a48795353663655707138746d614734784f6d5850414f6544576a797a7534467265726d4953634775617a4855344c454446304161774e4e496a5270344555634366565258546f4f704e644f686a4c5943596d73504d36624e4144307241495863623244794f31476f6d455170312f474b4c624d6935347a33724d516842486d69654b346962506a567058425a684d4a525a4475614c62377a4d7856633954326a634a53744a55666d4166346a474b69625831626331386e362f724c5067624650395159452b2f356e34774b5733554b55424a536779635a77615532717363454c6a50794a684c5853726335783835336f7a616e5579704a51756678454578367a535041613542346e44666a4832324a57634c79494a6636497970386279415a456e6a6e6952555077594675316a786e754d5455734b38724456576a5a3373492f64715042544249786731366e674a5a75513848355355324e78596566786972707270753561635634734f43534a3352393450745336744c596a486745546452716b6344614d5273357044724f557238514b4d45455a48704237306d7a5250534d6f632f4b4b53304f6552694a64667353736953477043747869636476767a55316c5465556e7939356e70366243354935452f5766544b416a7777566c55536b71497a5063527a3235716f55456a484f65782f71625a71426e6363593667533964657331467030454c524577526c4a34554f7846443851314c62574248316748534a634e3648673954562b386175464a63512b554265436b78756e32534f61323279703233427341392f77445057445870624b775152312f4f3461396f71552f764332513045655a53563756456a6c5251503530384773715352786a736348386f704c43534633633539526e395a4c5848557a4c5a68726552496b455a4d6348644e524b34586c4d342f6e7250572b4449582b6f524b2f543963446a526c49456945535a494d45666e6d6e44576877636a476576786e6c3361546279446e4863797672487846704f593270424845625568497a3778557570714e724b63656d50796d56754b6c784233374e4a386b775062465856614f734a744d51326f59746d434f6149766c7534576b2b687950316f7667314833535a6e784a2f384159525a6549314a6f796c5348556a7363476946546a6a4d376570394a6a5964543350694a6264746c4a4b69414432353961306c6c376e59584562326e56545358564e654945725372494a67547a57313345444d45706b5369747465554d34494a2f742f53716c314876456d71484456576e634b776163726f305755496e4e787069564479776149312b307a644a2f5574485750776d6c464443794974614c714b455a4534696266356f6f63696933516473354f7465787264307a4571625854307154494a6f77674d346a454b6673556c4d6347744b436170784d725270534436697541496d735159656b7a52774a30576137453663452b316469645079556a31494e644f69625874665377327159564949416d506e50734b6e74744142416a367138734450467450363149576f4a5956754c67385077396f4b6b672f53726d5469652f4a46517270776742794a54626339784f636d5757724a495131644a5a6474336c454b557074514245672b5665306b414b4566324e62665955584b4c7a372f77413568365452437838574e78376576383935736a716c53556c4c6856764b54736342796b776473704754427a4a6d6f3076385276366e66754f4a652b6d52435044486c3951655970737570586c4b4b7932537431446143346b4652386d34626c4473664e3938315157566c3537674e7031446b6f6344324d4d306671753859644c62773353726e626b41656847436b2f42396f72687144574d592f6e306d746f6137664d70346c75313164347743577a7356795a456a62475950622b39443864346743715370392f6c4150325634586d666b66336d477158626a63535a4375444d796674556571705a47484f63387a6b56434352786943705a63575348647a637a42556b6953424f30547a576244393233792b32595069686555356e7a566b334e73675174546953536f4b536b7041482b677763784850656e334c62556f436b6b66582f4d646f6e7075592b4a674835794e3157374677366f6d5351416d5a4f652f396630715463783830735969767972304936365230524c5a337a75644d375353414544326e47373370696c4c473239534c56367177726a306c4262617174473574345a43544d7843686e6a735a6f4b6d656b45502f77426b4c49487756696d3165746c50376861424b694e7379492f37676b6356337843444745355035666c2f79574d746d7a6c386a2b65736558627a554a4367684a426c4a474e7839506356513139624b467341795061534b72676b72367a6c6232784f346e79774a50706a696638416e4e4f30394a63672b6b5662594f6f45693962575a4368564c42737a6c7869456b474d4768566a4f494577566348685a4839613032426544424335366e49645371594d67416e504f4b45616974764b447a4d5a574554616a307461334d724b64717a3353594d2b744f4b7142784f5632455636686f3138777454724b2f45515354743942365237564d796b63695549796b594d4930725846764877314e4b43787a6a48334a34724673627163364163787462616f3831776f6e325039366f58557373516167592b734f726b4c387267672b395631366c5737695770496a454a59644a536b674b6959707535474f3064774d4d426d413358546b53526d73384f5a75695a7a526c54776144615a764573644b756b4f4e686141514432394b617268756f4a454a55334e484d6e4f3444414272703031427270307a57736574644f673935646f61547557714232395366514476574d516f795a6f475a4f334f757263576b415169524d665642784a4653745a754f4d3445614677506e4f6452306844796c6c513875794a6b544754506f42586b334d62624e7466513935364e4b6c463877356b6a6f6e533754547374687462697043696f7149536734674a534f34354d2b77464e56697968574f66655546546b7541522f506e4c612f7447747952636f6a6277554b684b6847504b526e4e48594276473466687a4a5574594b64682f4d526e63576143317562536c53776b626477423567524d592f74546d414b63486d4952765035757033702b6774464955554a426e6349412b71434a4254794d392f51635532756c534d6a38594e313741346d6c7a7044535368514953516f44506c6d65496a7637567249716c6563632f6e41577832424863387731612f7744384e6575495a5168616433414257494943694a486230453135746c614a63536f79506165335459397449446b6a2b64796959366a5148646f5146494d46767474555435694242704a3147484f426b656e796937714d31444a3539666e37536a313234506842654155356851676e324250666d6d61327a645668736657655a70562f71596e4f6d583457416d42736a4d6d494766316d4b52704e5467424d44623739596a7236755332655968366c734c426858694a51344672473437444b4d474a4950306e4e4e3148674447776e6e32782b73713056576f31414f534d4433372b6b5432657232356b44784e306b5a496a50644a422f516730677053696b594f6635312f7965673332633537492f6e792f334d4672635753686174703365556e365644736f38786a4e42735837724876453838564e7649413669367a756e476c50427a636d4341434f347a394a5078546642437a626348474a782f6e6169684b6c6a616f7149534f534236783234724c4b32786866534b5447376d4f7a624f504d4a54757a79526b664750697139442f53424f4f2f30696230446d4b32644966623359555366706a396561743864477a6d542b4577366a65302f3664726336345170584f634a6e68496a6b31353270315950466636537254365a33507650794d6d54494870332f77444938443471494a7a6b6b2f542b356c444652774d48352b6e30393433546237557a747752424d35794d2f46586970617876416b4c50754f4359493759342f64754b4744695a6e48725445314159346737534245706263596c53334844676b4e6f387931522b6748765451345041352f43647450727845642f316f6b4f4938693035457a6b527878362f373049566964796a39596541504b54474b2b75625575536c433969675a5467464a69504b592b59706863687432336a322f774152497234786d4b3952366a5374304a6151436b7042472b51556d63704a412b383572484654444a3448796a55653165687a38342b3033556e4151562b516d436753664d4935425049714546366d334b654f2b5a616131745163666a4c625464654a416e4d313764476f33726d65546253556245624a7655484f4b7044694a775a397332787368486c78694b38716879426b536c303949485a366b7442384a785735594a79527944776356516d73584f3175346868677879484d5a542b5657775a67342b50394a2b3831733641363572545673313469794d6d45706e366c4867543248636e7342574d77417a4e417a4939765645756e78484653705866473043634a544f414b67734c735a536755546b5068352f77454c4364735365366c63374f63594271643377706c466163676d483637715867737132714359784b684139496b697649324e5933486376725175634153532f5a2b346c4b3347334d715575556b5342746745464d67474a6d724c4e6749475072433146746f35422b6b75656f5a4d4e33456c6b67467435503162686b5a2f543371693139762f6b35553947496f5262426d72687656542f502b524e2f6e36625553307054363545426662316b4a705333566867564a4d4e744937447a59555465373634574e7132304f79516479464142416a767a77666d6e32616b416731352f744a64506f316345324d426a3834626164554c654b433630493267774645676d51557169493966576c76396f6c474164595a306c57443462546a55644d756e566e77586d416b542b3743464a55556b534571584233486a30346e464d4e6a572f646236596866306b4179702f4850722b6d497475325462674b4c436d6c5468556b676577566d5035313546326e3142633577423870514c4b32484a7a43644e31354d42447266694e37747843354a547a6b65744d307475332b6e594e772b66704a62314f647963474d7262573231714c61326b784a3277416b7832685359696e4d7a73323071437634524147467a6b35676c765a75584b3357454f454947464c4979457134534f785648666975703057363038385367587055425a6a4a39495862394657545359384a62684167724b31676b2f36764b51416669765165757065317a4e2f2b54314c4e6b4e6a3559483934753158707070545951323636325571334a4b6c65494236677a42492b3954734b6a3050776a31316c68626334422b6d4a5033576b4a74326c6c3051755a385a4a5555714a37516f6e4d3869427a38567751424363352f6e38372f4f413446316732635a394a7a70576e577237626a696e6c466152434145516433715275794f63555659724b4d577a4a745456625577417844306452433351574867537048436b7867596748754d5a694d5679715647306e48746d5437736e496d7964656c734f4b425332723643434372684d376b396a4a344d592b4d3562566c4f4f767a6d725964307765384a3153567263436b4a68535144414a48632f38375643714c5732584d39424e5179316c6178676e737a4f2b313557354b4c644155465475684d776b6567397a544465574a3263667a3268616253316b5a74683968664f68486e547478426b5350765772592b4363635472367141634c7a456571363857556b68494d71496b4b6b514979422b456533614b5855454a776e66724e5776634d6e6a4838356e576c64526f644363374648436b37437153634a5546416a4d64766571787471506b4f4d2b776a506864363772426e32356d434e4c5a75447463536e664a68534a423970516639366d2b4a4374672f6e4f752b7a3132466b4d4c30726f693163644d37796e684b53594a55496d56447354492b4456316a4b4832716367395478363743557952434757645042574657714758456e6b6866494a4730797152774b6b47704c5a537759492f4b564e547377796e494d614f32696268704b30754a4c734862346843595667514342416d44452b6f785478717137464250667a2f414a2f65414131546c6654355263625334444a425473576b655655685353522f456b524750317267775168312b7549566d312b415a6e7033566a52522b395634617759556b39694f59397139494f434a43566e70476d4f4170727a61624152695073516777687867547643556b2b395730426433496b31692b73346475696e366b6a3836767a694b6e49756b48322b654b334d37453832315455315856307061424c4c636f5442475343517463636d542b674654324e6d476f784f745163513277747774464a4167656b34452b3149594848426a30497a7a466e54566b524a53417031554c6a76415541534d2b35704c3134416c4664673353323636306f4b74304630535545516652524741665938564d2b6d4e594a4a6c476976444f775831695779365643346638554a56506c47544139545537556f557970376d32616a613233475a5a74324c76684273764e7154452f543666797230457073384d4c75424834547a4764513263455264702b6d74654b64794e796c6371674441344145554e57336667696139726c635a3445563954394f7645724447314957596d5070423567556d344d726b67635171335572676d456148303257534672574e71557041527a45443150764e4b46497a346a6e48796a7a65436f525239592b2f784f5a5150597833726e73773235654a7171434d4d5a766574426257317847354b38455a4241395a716c636c5163647853344463487154714e4b733072414e7370734a6e7a53734139784d6e7a564b376257785a58783779737132336372676e326a7653377131514341515a4d5274416a3871743031744f4a48654c4431784e5737316b4c4b652f71497a365446412b7043766769463454736d524a48725055567063384f534542755245674b4a4a2f5041465461687434474a58705643386a755376537237373130416c524b4569566b6e43552f66756654326f5530355963656b76314e39597135374d7664557470625834626f414d6a62746e4237456e46556a4b747572596a35442f63386c4c4f527558507a6e6a6c7968304f76737373752f534a47785379496a796767596e6b48326f3171484250702f4f5a7a755353494b3534376169307533664377524d74714a424f6377506575616f6b6b354835785949474f4a5a5750536c322b6b42626762627875436372554f5939452f7238556d746c775a7849426a7450536c71776d566f6355426a39347679677a2f44416961493751657534797437484f7866306e4e736774724b6d53674143556f32704535374b5065614256326e672f686d656868534d4d44387a4f625056623075456d324737764d435a2b38476944585a7941444850547064754e356e3235303574384b54634d427152494b59414250655267696b715838516d77592b63575652514255325a4d61333032746f456f49556c4d5a5436646a376a37316f474353655a516c6f786738516e70355a6247346b7a7a4d314d64724d5a6c6c6e7048546d73674c33435250507a574e754a7a504f7351413852676a57304f4461366c4b306e6e634d38524950494e61747235382f4969436d507538517533302b334968707762592b6c5a4d6a767a334e566976547479702b6869764573587354386472457138586369434e695249672b737a49397146617642795133487342312b6349322b494d6265666551757639504f4635536d776b4a55416f416b642b617071757775494c4b4d7a31665441527961386d6869706c562b44314771626756364b616a426b52724d306349554f4161394f72556275346871385354367566537862504f4e714b4642424154366c586c5448334e554e67444d415a4d6e4f6b32774c64416e6743636b632b6b66794e5346736e4d64695a396277575569534276457a387963392b4b7a655351495155596d6652746e34622f2b4c635543436b496151442b475a4a5076493471585661676877676a366151564c5430526a564575536c61596e73636769734e7a4c7934676e546a75737a38725357675a436f422f444f4b344c54327635526536772f77435a742f68776b486245625a674b394b70777a44414d5777357966326b7378317743387047304e684a3279723669667655506a73726559596c4c615a51426a6d506b64534d65476646356a74456e3346572b4a554579776966415a6d38737862655a63515670644a536541526b483339616b54345a675357687458617242514a4a6f313135433170564132716a48456574523769546965685a5857414d647a5a6655436e456c746268416d516f5949396a57765a614d62544178574f51764d706d583233576b744f457241414f2b596b396a6a7656593150696a62594d794971554f354f506c496a5869376172564d624f557248702f454b5361416a6348755549346354507076567647646b4c334869616d765677344576514c3466416c6a71396970376234595370653362426a383831366d6e7279755057656276326b6b39525a5961413477777074494b48446b72556b414b554f4a322f68375667726349564978447375566e44486b664b644e58716d456745685367504e48425074586d426d72626a6d656e585174715a3668756b6456327055556c4f7861764b72474436563664566c622b764a394a46666f725231794250742f6f6a6f5370786c7a78436f6b776f77632b6846412b6d344f4478467259704956686a456a3136303830646a6f4b464351527833354872586e4e54597034504563364a36537036663233445169466b4537676f2b2b43425871364e41552b66726d4b73796e4d48366e7337687367744e74724566527766744f436164645154794349576d757236596b664f643649773836513475335568615149796b52364442794b79744354794a3131694c777238526e713171746367494b464162676f5279526b48317a585055533249704c516f7a6e496b375a616d6c4374696d6c4957634c6c4a324c373442782b564c66465a47423333375367686e584f37494858754977744c43336353564a536c4b344957676770487950513050684b3258413539524a7253366e4765505178473662667855747258426d44783968556c46572b7a6d62597a4b75635132373661524a4b466c4b534d535a416a3446585861525163716343543161676e68687a416d394741676f755568556b516f456a4834676f443654385641666877526c77446d57596231553468577339505071516b74723875344a55516f4c37655a5149417850725639745943626c36694b6d5150677a52746f494154764b3477544d356e49396f394b6835504d61334a366c4530374171665a475a426d794861304c677754694e4e50584a72314e4c33496275704c2f746130395372552b4833576a6448614641672f70586f334f46544a6b74664a78464f674b48684954674541546a76554173552f644d6f7a37784e31363251796f2f36564a4f50536330785835454c48456b744236774455744f676c4155536b6a4d547949394b5671644958594f76634f6d2f614e706e6f2f5432756f65626c4243302f4d456631464c595a387269477259355579706276576e77473970426a76322b445362744d70486b6d3173366e646d4c6451617557452f75554a5765786e6b664272713743766c48637652714c6a357a69634a732f45536a2f4142444b64353968672f4e57564e6b59626d545856727550686e69456168706c7547774330764d68525475417a3239425647784d6453554c5a6e75433662704730674e4b5874485a7a507033714f3352566e6c654954584e2f7743333651753830793363537031594a4b634b4354415076696b6c4171456b6369437066492b63554a5a307852387146456a2b4e55667a6f427161416f79764d4c5a64377838786f3650434c69437043556951435a426a3571696d68534e7747424573784277655a4e3332713236776b7538464d65344e4663716c636e714e302b347474395a6a70375456756c4c6a6145425468786759704b384463787a50574b4c6a5a30424b4d61343162746831794a503468324e573045487a4765625970596c52487162345844634957424977526d724754496b4b6b4933496b4c31573073416c49424b5235696e73423349727a6454703977796f354539505461674b63453847526467306c546f55705a6852456b56352f734745395247374b7a314852417370387537484250635664545765784939515534335465377562636a39366c4b75796751445273364c33464c7037547776306e6e317a6366344f364b4756516865556e2f544a34715478634e7553656c5652757232764c33785672614471414859534e7756676e33466567746d527545386578417262547845746831596c4b79464b6a5048636531532b4b79767a476e54466c346c6f30304857777457386273706b356a3756667333444a6b52625932305934696d377344346a6139776351446c436f422b783961426557473645324168774d474576504e714a324a4c6268354a7837526e4270704b6738647841563865346e6b6656765372397337342f4b564b6b5a7a7a4d3148596e6869574330575176706e72634a5834623555414a327135482f6152327074626a5a6c704e59684a774a56573273326a6e6c574730714a77524565304b4842715631303775516343565643384a6b5a49683774793445625571547353652b444d43632f6937666c53724c43414b31506c684b692f65493567416554796f5a4f63544239446a3270364b46554178446b6b2b57612f356b4f3165513134394a614b6f58614f71556343755632597a47554355756d4e6b51545872615867387a7a376a37516a576b4e754e4b516f6a7a435076322f57765464513646543679484f446d65574e72676b447359503272354e6d5a4749396f38744275704c695743446b48483272307162537979696f5a4d386b76324e6976616356367462376846574a734d4a306e564847466878745242376a73523645567a6f47376e4b534a36703033314d6934414b566256393039353976616f33526b366a6c59536850554467494378493961527442354d6f556a306a6d777676456a67696d6d764979706d4677446a7146584c5430456f636c486442456b66426f42714c514d5a6d67314534646566654b6276555574625170525343636a2b744e386261504f59596f4c6b37524e644c766b4f4f4c45796b3855735849374543555730736c536e316e625852724b58764552415354754950723755317155636965556247475a514643593267776b666c5431515978365242597a792f715054422f69747261675571387848594548745358555a7744505130766f5748555642447478633757306b4e746943527875397151795a45394b323449754365544b413649363832576a4b556e7561796b576a6738435157576f446d4f2b6d6445754c5542426861527765385636534e59426952577655787a3059586549536c39494b542b384d4b37794b3763515150654c5253774a39704e6457644b70746c68396c506b4b68754859653471625630414c755758614c564d54744d4a306a57536c65536f41343971687074737a7a314b72305862784d4e645a4c5a4c734653446b6e30724e5453326477356a4e4e713977326b344d6b687154623639734464783778537970524d6d564337443847576e54724677456874745754684f3434465030753975466b6d70314e4c484c696172734c704473504d4963414d376848356961743357713231774d65386d7a703258636a45664b506233714973495353325349694535696d766673455456705674596a4f4a794e637433647531514250494f444e4a2b49726242426874704c6138786730456c73714b643370427a5061714e69375333636a7951324f6f6f3161396265523454725a5641326952492f4d64366c61316d47434a5658537564774d6b4c585272457662536b70495066492b436b3971476f7147775a5462706d385065764d5a74394f4d423471536c4357675a6a675438487457577057373567317579313479637a613666516c43316d504241774f354950366973524639756f6d79776a38597374727555676c5a53534a676c4a696149344a694f5a5361666f67534a4e65616c496c72584750625732536e6756556c51456c65776d45687a7458477a42774947336a4d694f746e586d6c427843694278386568706a58324b7552464f6f6b546158336d695a396138783679546d4c4d4f763768436f624f444577652f705631466242655248306e416b6e72576e44493756516a46444b43413638795865594b443756636a68704979465a38596655685157685243687752526b416a4269387935304872514b684434672f367533337156364d64527932537973372b4346744b424873615543566a4d687535544e613841695349497166554b483548635971354f445068315a6c394f55676e33726c5274764a6a553868344d51334c4c74757678477875625035697051436a5a5765694c467358613363717445317044364144685665685863746778504f76303756746b64546271466c51626c736b597a52587356474245554257506d6e6a2b73366b74747a767537647966696f30566a5058715646425979312f5a36566f6242577771535354504f5436565a566b45547a4e58686a6b47573139634a69646d3272484939704457705072414c5858664d515241486575573064527a6159597a4774732b30566235436c5531634535694758614d5258314a2b2f486854744850464264577a7269616c7156386a6b785262364f6f4a4b6362666a4e516d6c674d526e6a4b546d55646a59536a616f426153497071564d6f393442634538635347314c6f424676636d3651464b546b37426b4139365271465a5677426d5656336278337a50714f7332306d42414b667363564a385459427773503462505a6e312f726b724849704c616d35767652673071723145317a72355849423570715745446d463465444f744b366365754662392b7968717138544f4f7051327446597869574457696c7474535650724a6a454742567956374632376a4947754e6a3532695936613870446174704b782f467a6a304e597573432b58456f6254715747376a384947454e4171754855674b37653154737a5750787848732f6872735138524c7176566a5162556c61706b2b56493539685436394f32636b794f32385a34697a523175334b6b3768746254394b42496a307a545748704a69633879706355326e424d5934674773416d52387655523631352f69675376777959525a333030784c73786231596a41657463527a42485543317930513632516f416971714d65736e7442394a3565786f65315a564f416f78376961466177482b554462784175725670495345346353667648662b6c58755274457973455a694a476f6b6a6176383656346365486d647861434f5a70514a426a657845393159526c4e554a64377964367661424b42474349707777656f7242454f3037563357544b4647505474517457473768423561365231756c5132756a616630716179676a714e563435617557313551766e304e544f687869554c626a754e644f7656665173376b314e7349456f3855486b523170544d4b4b6b774d79503730717533776a694d5a677938786735726d464a636745436d2f47462f4b594a305141444c31494470356a785831334b302f694f7748734165314b74735a58434c395a58576d36727a537066764c6b4b68747679787a69716b385876455561744f6f38786954584f6f6e54355a4b534f617837334a32786955306f4e33636a72377142386770436f392b394d54505a6b463579334548306e715a36335675336c57654365617572745070504f73727a3350517266722b33635a33717773446a76373151626830596761632b6b50306272466c354d4177666646527663426d50464a45643275744544616a4e625871512f416a527078325a516f7569555170506171794d6a42694e6f7a6b54782f7250706b473438564b534571506d6a2b64655871566441536f7a505570495941457a6d7836515a556b714b31715059412f7742425564467a4f755349316c5947644d644f4c4a2f646f327765566e39614c59443937694e7a6a75553974594c5a544a636a47514b4c7935386b6e4142504d4466316b7a6b344656494f4f59447350534a39513671434479416e3070677047654968726a6953327364587658457473706765744e324b766354754a676d6d614174546953737953636b316f625055773853347437684c4d6a474f314963346d714d784d2f7242556f6d4b376154475a41346a6c4f6f5433727774706e71375933307939794d30354f444557446956746f39497230464752505059344d4831647945476d424d514e325a43584c706b304a6e5364315a6a666e38513731717352436b35634e657455493045695a65496f444861694b676d6147496e41765a4d47734e5848453057387a5235684b7867436c4b78513878784159514637535663707079366b657353644f6653427274314a35464f46697430597259796e6d614d334b6b384b4972434244426a6979366b65522b4b666d6b7455444442784b6a512b753068593853514b6d73306d656f305863596c6b2f716474636746437850736147725468655745503468386251654a2b595579325232714a2b4c4e776e6f3133485a746c4462616f33484970793357457952307a36784c72746b3038647741393470646c68335a4d706f515977306e335032664e752b594c556e346a2b744d585574364c45326f6f4f4975642f5a666b2f3841554b507942564878624166646b6e674b544f572f32636c4d417643506a5038414f6c767169656352693171493673656a32734a334548314270564675397350474d6d426c59314f6c727468755373716a4f6166384d7462623050306d6f2f696556784f5875725874736842785454726749394e445544676d50644d315a7439727a6741786d61594e616a4c784a4c74475566796e694a4c2b335179647a4b754f526e4e52574d716e4b79796c6d78683470314871333049534254774134366b725074395a4d6170316b4a6e655665773470696163784436675248633951767647454342547857716a6d546c326147615a30303436647a684b7635554457353445344c6a75557a656c4e4d447a526968555a6e466f74316656435038413478385256436a41697963774e70703178515573376478344a6f436768683864537174656d664b4e797744364450363038496f484d53624d784f30686676586966445a366e722f4552767072626b69416130614e79654974395176724c2f51375a6344645870553659714f5a3531747762714e377178436b3570374943496b4e495457394c326b78554e6962544b4662496b323562484d3076454b4b6451735a77425767346d78486432716b3035584534785934316d714165496f6a6d624e5950745332414d617049686a5633744d48696c4e566d4e5779474b4c617853646a434d33417a352f2f41446f587752572b4d797a4e716d666e656a7a324e61757150744d384a59517a2b7a6c35596b4c412b31505855664b4b5a424d334f683732324f394b6b7141394352546936734f596f416738545139544b535963624d6a42714d304b54775a574c53427a43322b72326a79434b3777474534574348576e574c53424531686f4a394958692f4f4570363351524156464d57724870464d2f50633061366f62504c6e36306668353949506947593350554c61763841376631705a702b554d58544272716874426e784a70523032546b434f476f346d643731346b346d525866443245596d2b4f673569747a72524d5142574437503435697a717563774e585743344f304556672b7a7750574d47734a67332b63334b78745449422b615a3850577079544d6255757767697449645639524f617057786653534d7050634c736441377172477539707931783962573761494f4d564d7a6b78777278474475736255776a4643435a684145534f764b664a5354564e533435694c477848656d324731414c67456a36663841656e484a50456d7a7a4462445366455875554d546766316f774d54533075725454427445696d42594261523352695047414a464c727178475750505137485230707a465568514a4d574a6a6c6d33417851735a6f4531554d55454f4b4e527351735a7062726d4572596b6271576b375366536f6e54615a51477a454e785a775a70634b4b376931424e614f35325a694e43626353635172317036744269433930705344677a52354532422b435a6731303266564969686877687539576b594e447442504d334a416d3674646441426e696938465a6e694750744f3639324a47354e594b534a7863474f5664624e75494a495048705845456354414d7945764c744c6a696942676d6b4d68484d6f4239494b576b7a7858417469627843547069434a465a3472436474426762756d6754546c764d5731596e367a305971504e45392b4f6f41726a565853766c6e64537871446d45617841463645427961594c3450687a6c476b49373058696d6434554865596253596971463669443347566d706b416558394b6d7355356a6c50456247356245516e394b6d494d594a6863586356675977396f674c742b6542545654504a676c3864544d334a504a6f76444557584d374e386b434f39454b695942614e7445303875656338544141376e337036316752547469576c6870796a47347a2f536d525571744d3073436a5659426150554d5149706b43662f32513d3d, 1, 0),
(8, 'subcategorianovias5', 0x2f696d616765732f70726f647563746f6176617461722e706e67, 1, 0),
(9, 'loquesea', 0x2f696d616765732f70726f647563746f6176617461722e706e67, 6, 1),
(10, 'SUBCAT PRUEBA QA', 0x2f696d616765732f70726f647563746f6176617461722e706e67, 7, 0),
(11, 'ERTFZF    EWF', 0x2f696d616765732f70726f647563746f6176617461722e706e67, 7, 1),
(12, 'SUBCAT PRUEBA QA2', 0x2f696d616765732f70726f647563746f6176617461722e706e67, 8, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sucursal`
--

CREATE TABLE `sucursal` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `direccion` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  `telefono` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `idCiudad` int(11) NOT NULL,
  `idEmpresa` int(11) NOT NULL,
  `eliminado` tinyint(3) UNSIGNED NOT NULL,
  `contador` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `sucursal`
--

INSERT INTO `sucursal` (`id`, `nombre`, `direccion`, `telefono`, `idCiudad`, `idEmpresa`, `eliminado`, `contador`) VALUES
(1, 'CENTRAL', 'Av. Cañoto # 360', '76392890', 1, 1, 0, 87),
(2, 'Sucursal1', 'sn', '8788', 7, 1, 0, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipocliente`
--

CREATE TABLE `tipocliente` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `abreviatura` varchar(20) DEFAULT NULL,
  `eliminado` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `tipocliente`
--

INSERT INTO `tipocliente` (`id`, `nombre`, `abreviatura`, `eliminado`) VALUES
(1, 'Normal', 'NR', 0),
(2, 'Cliente Frecuente', 'CF', 0),
(3, 'Mayorista', 'My', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipodescuento`
--

CREATE TABLE `tipodescuento` (
  `id` int(11) NOT NULL,
  `nombre` varchar(20) NOT NULL,
  `descripcion` varchar(250) NOT NULL,
  `descuento` int(11) NOT NULL,
  `fechaFin` date DEFAULT '0000-00-00',
  `fechaInicio` date DEFAULT '0000-00-00',
  `eliminado` tinyint(4) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `tipodescuento`
--

INSERT INTO `tipodescuento` (`id`, `nombre`, `descripcion`, `descuento`, `fechaFin`, `fechaInicio`, `eliminado`) VALUES
(1, 'Sin Descuento', 'Sin Descuento', 0, '2050-12-31', '2017-04-20', 0),
(2, 'Cliente Frecuente', '', 10, '2024-04-01', '2017-04-01', 0),
(3, 'Liquidacion', 'descuento de locura', 25, '2017-05-03', '2017-05-02', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipoegreso`
--

CREATE TABLE `tipoegreso` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `eliminado` tinyint(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `tipoegreso`
--

INSERT INTO `tipoegreso` (`id`, `nombre`, `eliminado`) VALUES
(1, 'Cierre de Caja', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipogastoscompra`
--

CREATE TABLE `tipogastoscompra` (
  `idTipoGasto` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `eliminado` tinyint(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `tipogastoscompra`
--

INSERT INTO `tipogastoscompra` (`idTipoGasto`, `nombre`, `eliminado`) VALUES
(1, 'Alquiler', 0),
(2, 'Impuestos', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipoingreso`
--

CREATE TABLE `tipoingreso` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `eliminado` tinyint(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `tipoingreso`
--

INSERT INTO `tipoingreso` (`id`, `nombre`, `eliminado`) VALUES
(1, 'Apertura de Caja', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipomoneda`
--

CREATE TABLE `tipomoneda` (
  `id` int(11) NOT NULL,
  `moneda` varchar(50) DEFAULT NULL,
  `bs` decimal(65,2) DEFAULT NULL,
  `eliminado` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `tipomoneda`
--

INSERT INTO `tipomoneda` (`id`, `moneda`, `bs`, `eliminado`) VALUES
(1, 'Dolar', '6.96', 0),
(2, 'Euro', '9.00', 1),
(3, 'euro', '10.00', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipoproducto`
--

CREATE TABLE `tipoproducto` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `imagen` mediumblob NOT NULL,
  `eliminado` tinyint(3) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `tipoproducto`
--

INSERT INTO `tipoproducto` (`id`, `nombre`, `imagen`, `eliminado`, `created_at`, `updated_at`) VALUES
(1, 'Vestidos de Novia', '', 0, NULL, NULL),
(2, 'Velos', '', 0, NULL, NULL),
(3, 'Accesorios', '', 0, NULL, NULL),
(4, 'categoria4', '', 0, NULL, NULL),
(6, 'tcategoria5', 0x646174613a696d6167652f706e673b6261736536342c6956424f5277304b47676f414141414e5355684555674141414867414141426f4341494141414331304b4d654141414141584e535230494172733463365141414141526e51553142414143786a777638595155414141414a6345685a6377414144734d4141413744416364767147514141424a505355524256486865375a3162634650484763636c2b534a62386755625932706a456936474249504247427775355356704c704d4a675347426159615a4e4d4f6b435a4e6d4a74503249592b5a79557737504b6270537959506d647a547a49424c48704a43486e4a7253777234677247354f64526348475077426475794a646d794c4b6d2f63373744515a614e6b644352644654375038356d393973396538372b39372f66666e736b624f753161396363446f66646272656f794d6a49794d374b6c6a7759472f585259506d4b5a58352f77477131597248616747566f6150696a6a7a3736387373764a79596d4c426d327a4d784d70394d35506a377563726d4b69347676762f2f2b6e4a77636a4b46516941613575626e59723179355170372b673847676444367259423063484953676f714b697249777369383053436c6c2b2f34632f58727a55415231723136356c41686f624737576d4b74482f39354268566c64584e7a51305a47566c4b624b3642516968646c71686950334d6d54506b6b5264464946562b763538714b315a7939446738374835352f333633323431436c7931624a6a4b38644f6b537770514c5a676d45736a5672316b42786533733735454352564b31667635353849424351596a677730724b6a5178456f46793564757051316a52306a4b586d4661465930375a376176694e2f5869475a38516c2f31594f726d4279574f5933306d5a6b6c594e515141714156796b36644f69552b6b4b725230644861327470706c65667a2b633665505974655a544c676c325a43656e392f2f3430624e327a3069496d4b374e796373624778425173572b48336a734a79646e61334b66336178444743486c4a324773597365785149514a707246434a55594561576b464339637541426a654671787743385a374c3239765679435256453058657a61746575423157736f6a34794d64485a3253722b7a475455314e59693075626b354c7938504c553456484b4b55444645414461433473724a5366414130496d3249626d7472493456306a417252396658314f50372f58726b4b7935703139676b354171787351455a63714d67784844436f5a7a5a76336f785845563868786e506e7a684669514b59655a646d597430382b2f31744f6e74506a385742566e4d57735a786c3064336644444d496b306f426c6e5545646b43696f714b68413057526f413243504c5a524c734a44584c375432396658742f63337a6d444949372b597742666742396a64304c5852446e366962564355746f367171696a7a367855646a623231747059313636535459704955306d734e5549477038416d4531394146646f51422b78536b4463636f7948324b4a6749305a6f385764717563414f57794a5455314e524e5955785245444d685135377146523247747061634576593947706a34435645472f665337395635306f7a7a5745716f413946456f7151586c64426b4363784e567866766e795a79492b71476259334b3376673775642b505564304e474237334c527045344564456f5a69614c313538796252734c43734e626f446c473378715a3037356f694f4872414d616243732b4f7a4a586e7347524e7475446a6f67467a6d54777668646861784469616931374c334335584a784f706f3932796b6a6c6345696169444775324b61694339574f42774f6a70527a4b324e6d3250517a2b7a3244454a4a314a5075795a6a497869425a366533736c4c755a4570316b544435754252785864437a4547795a674b346c6852773871564b2b56316a3854437959487873387049354f32693254436b6f7279386e4d68584d79555232736e64514b427259486933386344746476663339342b506a2b666d3567344d4445532f67786b496d2f3678724c45776c6664677535342f662f3643425173534e4e686f6b436736524e48516a53744d3153624a4c67327a654754324965544d593651774e457173376867595849736e5365614335615a794f2b3762337434756e344249566171517641582b7a445050614c6e454133376e7a5a76486e6f7951552b4b527030494a643752734973466f362b76726b625a575468676b674e753664537642736d597942777734676b6350665649547356564b6d4d7877486e3330305750486a7547677451707a494157784158516e497443574d4a6c643463534a4535724a5446412b79744b7979514b6941775a756a31367656772b546d55574b576f575a59464f2b4135594b734d794e4372626f4a2b5668386c31684778703261646e6b5168534e414b464a3939307841532b4d53746a30696f714b5568346d33785732386f6f4b4c5a734b34454e6b42794d5039574b4d426a51476e45544b793876782b476257736b414c37464d4f38647061345737772b5877776935775274546c66594532467261656e5238756d46437a38614c7832666e342b6a674b505044773872487758566f56575a323759544b5549506234575a78494f4a457a74344f4467794d6749576f5a6c72534a4e59434d6b30724b6d4159524f5061386a64687946772b46493573636942734a6d74684d55674e4f4442772f6973703939396c6c494a344f635366455379592f366a554a536a2b417841566f50487a344d313457466851382f2f444475596d4267514b744c51316a627a7039372f665858315931494d356b456242354546787a7a46693161524772436c5263547a4f673667487a6853672b54355348544a634359466f6e364b4373654947533852476c704b667a715156464d6762594a5965545844654b48486962726e364c714b705a6a75706b5032544d44353579434e36565477634b435844314d76704e4430352f32336c36507042436d59426b673163574c4630635a4a707445484445683964386d35514677767544697859745268736c63496c4770424e70694e446d4d2f774a4e6c49416779504a345048495951636a3338435431396657516e685a63383543706555706f4a6270343964565832653769504f2f743272557256584b4a48716c6857594b322f667633662f44424237416335787369644d317337646d7a7837536e58474474377531353863555830585553664458484543676d6f4e7932626474333333326e575932474f6862545259464a5662514578647533622f2f6969792f456b676959382f56656b6f6a47503768634c6f4a66346f54446877385846685a7146516e41724659304b734e7039506633662f333131356f706b56414f6c435a374d5a4a596f676b474247695a4d4b3634754a68345471744c4a47425a626d7165344339524438487769414677455778396a446c66685661584c504141346b5949534452543670416f6f6947336f4b434151534b756c48387351767a483832694646434568524575594c4b2b4834672b543434664d64326f4462534f4a486830646454716457566c5a6a7a2f2b4f416f7957356831384f424243587530636e4a684a4e473575626d516d2b67774f5534634f6e516f4a64756a41626645556541664f50555255586939336b53487958454348794b68434b6d34754f5441414b4a7866424339632b644f2f49615a337a614541355a78644d6c3857674f4952737645634565504874584b61514a6b6b55776659734364554164454a2b636b6b6e595163706a523545337072415645352b586c7a5246745047536e4a634374714b6859766e7a35344f4367792b576149397034454f4d53323553566c5246396e546c7a787546774b43384470413736675867534d6e71614c6c474571514452363961744b796b706b563974536e68545656576c4d4c747935636f48486e69414f7372723136396e4f325a436c693162746d624e47757879774d4f69646a4b48365946476f5169754547687061536b57696b533951696d5267764a523168747676484874326a5561553863684663467a6a4f3774375a56767361786475355941727257314e5a6e6866546f436c717572717948743674577241774d446e4a4e524d4a5165503334384a79644855545173693244466577775044394d4f63696d4378735a4733506d71566174774f6b79443275636362674f4b534f47337271344f6438773243464834417a5a415958587a3573324b3134372b77316b757070654f6a6737696c546e476463416d5070626c446a4f51506a593268684f474b4f534c5a4d6e55317462534a6761696162786b79524b384f78657a6d6370307a5145326b42336b697253685a57526b4249726e7a352b5077436d32744c5241722b49364b4b447438764a7969556a773346794d3631443775513036756e7a354d72566378527a4f326f434567554d466a433163754a43396a6a324d4a58372b2f486c6f68426b6f37757a7378506465756e534a4e764b62596546545566546262372b4e344f31323556384e3051565749684957676b78444246676a64727564755671386544473961395a5a42706942596e676b526d436a5133616e54703343434930624e3236453861616d4a74676e673135395070397943662f424c466131423058647a4668425155464e54593159496f44587037336237655953452f364c726951414c634c5036644f6e662f72704a39684130596950464d5932624e69412b4841646b413654744e512f787074477330416d51592f6e754978725a476b776a63774549412f6430694474454c49715032444d34383277574732576f50366a316b6443744d6b4b6e356941757247686f59487362467872634f504757744b3274744d6f47727267424839393965726c72437a384b6c3046595a75722b46466378317476766158326464766e77695a7a6866354a345a532b4956706e756269346d464d38553071654a39437553537349793961514a642f682f507a7a7a3153624271566d7567397933534d6568794e484245664b324b484d362f45346e4536707a6374332b7366684c56507449684c544534317a7548486a526c39664830527a4f4b53496c78434253324448546d7243662f77535061775a79726368726c2f725076486a6a794b564d522b376c4471696b44576f576d7771335a49502b674d546f6141394f38732f3776763535352b584c612f306a667673484f697374734345337838493547546c4449304d467863573044686743656d4f416c57544a3532656147594d4b74584659704d2f3277444c796878614c4c445072736f456b4539544f594e414b4d6942776a5069626d6c713172663836725856704545596e6a4973687338786f7175724538317833694e304f334869424c5251704a594e384f544a52756753516b4b686151345a43744837397532624e32396552635639304572305271534e723243764579316a314a70617257317462586750657353657069776a46344a55486834457876316e7a375147417667425a53787261745a4c6d334151494a4d536868476f63533348504d675a476870694a39793061524f643950623258726c7952565134417851536f5a4a746a593434686e424f70314e59727179736c4371686c5477545149596578553870563663745249796949574835546d687562696157355a684744436462464b755a434a6f444e3530514c2b4e4a37736f7955425164666a4c6b5976706c6d656a383470527633727a5a336430645458646d426b4e6a343245345a414157617a445531747043526b5339746e6144326e41535949624775493648486e6f49577476623235314f5a33563164553950543164586c376f776f744a634a4e474c466930714b69715369306d3542306f6e35666d69374e476367474c304b434d53415147646141794947396442417853325973554b2f414d6e4554596b3469744554527430566c74627937556f757147685161597165747a6143473542337046714259766c374e6d7a386b63613070706c655836675578794f69516e746c7a6f7863466865756e51704c7051444e4636597053792f3345315738386d544a306e52746669636d444470416836496d6553755448356a59794f4242336c75482b76736d5151777936364630784f5049526170436f65774e6a6a6f676b316d34754c46692b783457375a737753397a46544566446469785a426f49427a70752f5858496d4b43346a763337392b4f446d4f79536b684a6342373349533477303552656f386c582b6e43347077354555753479497642624142555066662f4e7453556b787066372b2f6b63656677786d56362b754c6973724f2f436e502b666d324874366576507a6e55514b4d494d4534336e6c6f45776d2b7555475656565673457952417946547039616d4b3642795a47514566735670614e6270384f4748483749546b694841526277416a3846476c357472392f6e473659657472375330564f4a64756554656f436a367a546666704c764f7a713742775545574344705061793050447738546f5a49586479466333306e526d5662627156504e566b7549413454484e2f625959342f68736d6c322f4e695068515635355045727449396665597169695a3076584c6a416a5a673055594855705231342b4f765872384d795178426d42634b3156684448456c4a656443695a7a497a756e683461454e51574f765071317463693671416c394d4f2f2f2b5561647666323953453751395a337a4c756e6d554530786f59576b31426f7648506e546e57377368485050664845453854496b50374f4f2b2b77754d764b46736255327778516946596d4e6f376f6a656344754232746e416f51683845797045544a697a356539696635537a534b50776d4664757a5951567a426d6942576565474646374448773077344446413046424f3076506261613134566d6a587845466f424c464f63796f6859777531544c594956717834633953752f4f7833466e507a50386144507a7752633765356158564e442f2b4c723434514252484e32346d6e65666664647773526b666756642b434a534a6850756b65384e48454d6b51322f76766663656e68716c2b33772b7a747947764873776747674578546a78612b2b2f2f2f3654547a364a46676974744c724541482f4b476d496c775934732b516952556f7977414c464971757834366d466162616a386c6977384e58623150424863746d30724b554c6d7a4c4a392b33614d7976587877636a4e6b506b2f637554493774323769556b315532494142544b584d4336575741477a7341796257766e57332f6e5739314b4b654770414d37552b5868684a4e4d45346b2f2f7878782f444f4c754b5a6a55617345796b6a417868524569354b36537835434758366547776830556e326d724e344766544c37663551785a37646961615039643675736a706447526d577533326a5a753376505758762f6f6e34764a4f6b572f766a414a4359476e544c617576714b684939717634495234356e434e6855453846616f316d6c497a6b392b37642b2b6d6e6e34704672626c64425a417a4958505469654e536c436c5a664e2b536f704c35334e51374e6e71366f51454e4d52787045424f4d5648513458433758776f554c43776f4b654d53757269374e476763594e7141337252773759506d7a7a795a39446873422f416c72706136756a6a7a725268566638464a4868332f4d35786e31636d76556732366b63617849464e48736a527731455973515244524371745846446e536b484a48566679616a432f424f304364447a346a6e465a624653427165416554787948444e6758423535556f6c30676a426a69307a4b32507638337664376d486d75724b71617432476a66562f2f384c6a6a666d505143624b6455534135325977375057732f5369334678354a416c6a325053614a6f6c4173715435743455624a36426269482b3549476d376e4d634b6e58497954556e5632416d506a353836644a61764f687358743962413962743279626354726b52443264792b2f394d6f72727a415772704a356d686d4a556e514565427234366c48664b6d696d7577474347414171672b4a6f526a49564f467732353374375532484c7971797265306a2f68675a7a4136635a4e6c76702f424a4559382f4e2b655354543270726138656a2f684e5253564b30446f364f4c452f6f6d2b47335376446f61426b464d62784a5767744c497853745a30676c6f7938494d556f71475946756a436a7147516a6c4c6a6a6f3967766e4e5337562f33453633374e6e7a3444364c53306556546e616545655a674f2b2f2f316170316c6f7051704631724a6153546a5467396b382f2f66525858333246786a6e79617459774d44786d676f772b5a6a326a707a7252494e7a4f3842694c61745973676f687239664648744a466968444854616a766430757a33423941303130722f7a4f48446a2f794b327845706f6d734778644b68534a563838795943535849643463424e487a703069444e6b4f466b43586374614f586167723343616f6f5243384f5372784149734e6d764145717264554e656c2f4f75543230656b4d652f594439392b632b796650797a36525a6b6c454d7a4f73553845417a546d4a32544c6d5079444a5a59766f68734c5842355242424b51514a76444e486c6336745431486d584b4a44333333484f45466c495575304430533059337176554b5a73694846775857594b69317455576b726132495739412f676a6c77344d412f6a6836526779746a3452446e64587532624e6e795039415645516d2f433132544141414141456c46546b5375516d4343, 0, NULL, NULL),
(7, 'PRUEBA QA', 0x2f696d616765732f70726f647563746f6176617461722e706e67, 0, NULL, NULL),
(8, ' PRUEBA QA2', 0x2f696d616765732f70726f647563746f6176617461722e706e67, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `turno`
--

CREATE TABLE `turno` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `minutosTolerancia` int(11) NOT NULL,
  `eliminado` tinyint(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `turno`
--

INSERT INTO `turno` (`id`, `nombre`, `minutosTolerancia`, `eliminado`) VALUES
(1, 'Asesora 1', 5, 0),
(2, 'Asesora 2', 5, 0),
(3, 'Modista', 5, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `unidadmedida`
--

CREATE TABLE `unidadmedida` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `abreviatura` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `eliminado` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `unidadmedida`
--

INSERT INTO `unidadmedida` (`id`, `nombre`, `abreviatura`, `eliminado`, `created_at`, `updated_at`) VALUES
(1, 'Unidad', 'UD', 0, NULL, NULL),
(2, 'DocenA', 'Doce', 0, '2017-05-02 19:30:06', '2017-05-02 19:30:06');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `nombreUsuario` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `contraseña` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `urlFoto` longblob,
  `idEmpleado` int(11) NOT NULL,
  `idPerfil` int(11) NOT NULL,
  `eliminado` tinyint(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`nombreUsuario`, `contraseña`, `urlFoto`, `idEmpleado`, `idPerfil`, `eliminado`) VALUES
('admin', '123', 0x646174613a696d6167652f706e673b6261736536342c6956424f5277304b47676f414141414e5355684555674141415a514141414755434149414141436470305033414141415a487055574852535958636763484a765a6d6c735a5342306558426c49476c7764474d4141486a615059717844594241444d5436544d45494a4c6e4c4a2b4f38486948525562432f69436977477865573633365762422b6534676c4434646a522f6d6a703269314746354230566942494854504d4655574c3034464a786f6a566e594b653551575446784f7453384948755141414941424a524546556546377376586d59473964313448764f7253727351414f4e336a65796d3277324e354861546532324a566d7937456932493975784857667a6b6e67794c336d5457544b5a354d564a78736c6b6b736b794754386e3439694f505936584f4c5a732b566d4c5a6132554b477158534848765a724e4a397235685877756f2b2f346f4c41586759756c756f4c764250722b506e7a366f634c73416442642b646536353539364c7a743448674341496f746c6731526f5142454673526b6865424545304a53517667694361457049585152424e43636d4c4949696d684f52464545525451764969434b497049586b52424e47556b4c7749676d684b534634455154516c4a432b43494a6f536b6864424545304a79597367694b61453545555152464e43386949496f696b6865524545305a53517641694361457049586752424e43556b4c3449676d684b534630455154516e4a69794349706f546b5252424555304c79496769694b534635455154526c4a433843494a6f536b6865424545304a53517667694361457049585152424e43636d4c4949696d684f52464545525451764969434b497049586b52424e47556b4c7749676d684b534634455154516c4a432b43494a6f536b6864424545304a79597367694b61453545555152464e43386949496f696b6865524545305a53517641694361457049586752424e43556b4c3449676d684b534630455154516e4a69794349706f546b5252424555304c79496769694b534635455154526c4a433843494a6f536b6865424545304a53517667694361457049585152424e43636d4c4949696d684f52464545525451764969434b497049586b52424e47556b4c7749676d684b534634455154516c4a432b43494a6f536b6864424545304a79597367694b61453545555152464e43386949496f696b6865524545305a53517641694361457049586752424e43556b4c3449676d684b534630455154516e4a69794349706f546b5252424555304c79496769694b534635455154526c4a433843494a6f536b6865424545304a53517667694361457049585152424e43636d4c4949696d684f52464545525451764969434b497049586b52424e47556b4c7749676d684b534634455154516c4a432b43494a6f536b6864424545304a79597367694b61453545555152464e43386949496f696b6865524545305a5351764169436145726b61673049596758494a726a6d576d33765164573750656e30704730327a576f436c79524667757a5368504c324d666d566f2f4c695172577a4545514e6f4c503367577074434b493648643338786739467343642b4b7179396e655132414142514566566e6237437861397677326735737337416a6836302f2f4a353563724c43795169694f69517659713330446166335042412b49535575717a794a4141413267375941514d6238672f6530345873487046597a652f6a3739683938563948536f6a4d53524131495a746675616d3049516f7937523776325538477850634654504c584949593167417742454c577375475946687759507a55586868546d7454344d3544367335642b4d6172636b6f74653336437141444a6931674e714d44426a34655862766166685651454d397053444146587162594151454a6743436d4174774a6353384a7475314d487274614f506d394b7063713844454755682b5246724a69322f5772484a33306e62596b674647684c4437694d7470494c745756384d427268715351633271454e376f536a7a797563433136494943704138694a57414a4e682b426443467734476c7a6c5056744e576b61333078396b48794244486f32414775474534625a4b6c6b38656c3468636a6949715176496861556471316e6b38746a6271537957772f5553314d622b5565434c57565059674d455141554145413445344a6464727a2b3674535a342b616c7858794f6e794371516b5771524532303746637476375277566b356e416935455933704c786f49485542426b5a52354c69464a57577777686a5a6b482f334a52693658344c33347578716a6f6b46674a46486b5231576d394d7a7039617941434b386a4b5133486b6c592b324f4259386947754148412f3261654741366349593355324a57714672686167455232682f494478356463695772643553732b6b74597a39527878426b466153334a4d526374415541436d54434c675641513241494c79356f2f675463383847496f67424231416846586b525a754179656a776158743058586b70585042566d516a62786b42416c41513541514741424434416a492b5a354f376c39534c6f3354445a576f436270514344466342732b48412f36656d46715933736f396b415642566e46367978687435664a636572536c4a376879796e7646707755532f4a337669774e423141624a69784341434e3448516775396951705a2b63726155724a694d6d704c4b745357336f41685a38673568314d2b33746d62484e697041554855414d6d4c454743364e7a4933474e4d66723345774d5a66654b7163742f5479493747302f70446a636344755632784d315161505452444757472b4f686652456f3743546d6b457238425962427844534350745761496154314a42654156744a4a4241436a74674241426c684f3863556f487a6d51424441425156534435455555494132706f647544526c734a6f3631382b784a743551595449617374566b3162476e49477741436d6f33425664374b313078594f6367445130756c554f675670366b6753416b686552423670525750334234513145506b322b594f5a523678515737714763746f4367376d4532744979443041436d4976782f527a333374417864616b464542455a522b536370354a71536b326f69555138486b74464976466f46476b79354a61483545566b6b63442b514369676146437a746e5262675348795571707043774151575a4732454543663262696b5167724136556e434a5552456a6f4149774a686973356a517868476469496a49755a594968325042594d532f724962435147784a5346354542766d6d654b417a55546d3956615374644f4544304a507832516551655642575735414e75484c747779726e484d336d4643414335502f7049754f49694d67514f5a4d74626f2f56302b6f5a4845776e6b72476c786444436644775141474972516649694141436b546b32394b62696939466152746d724a79724d5362556d4637564d496e4773416748726c716c37646d68635a414142483143334747544a41744670632f66307441774f4a61437734505257656d556d725353433241435176416a6943644739496c304d7432674c525947497457666b4b32744a444f687443476b424e73337a59686167724c423932355838494f43494435494141614c4c6232305a47576f64336861656e66424d5855724559454663304a4338434c46636e5570324a47725731757346455931626532452b45724c596b414937675549427a43506d743262444c32486b45686c6755646e4644674d59524552416c316a4b777257566757334271796a633271735a4a595663734a4b38746a34586a3761454b366132697763526173764a43625746356265554f756b7a494f53374e3277475149344a52544e6e336b336b745147505978544d4247724b73335a77444134372b50762f3475502f386d45627254462b4a6b4c79324f725a44735a52466735554d4a724b56444359434147496c6266487373346a515a5548666b6973614e53466d724a524c32426546585167412b6241724835304241476162415a4e6264343234746d31624f4834384d6a7344784a554654512f61306b68326e7234755773637050676f79686c7850623255434c69786f44316d583666314568694142494149694b4d673772446832716763524f59497837444b2b3532794845566b3235736f706a4a5545614143675747336468323771767645647a4553462b31635546486c7461637a766948456c5537396553315965616b68765663374b6c305a624f684c4144716645552f4b357437734d6553376b7949724472737935394c444c474b4142523452637339782f415144413064746e6157756266653231325077634546634546486c745864444d34656f6f5a414f75584a41463253424c79665554395179584958724b42567949544f386e366b6479305a5a55456d337042335052466867366a77716957634964546a7a35366f356b5844474558515668464f627a394d684164784d597736366971396d5941754f4969735865662b736472587632416e464651504c61757067504a70695a437866655975555833684a71537976556c7245394672704d4b6a796f5947592f6a674e754443363654373761623469354d74374a6856333574313438454a6c76597779377749412b4e416b53657138363048584c6255796d506b6654512f4c61757244725970433156653642634c31415958724c71433157714332396663355175726d4d32674b447467426772787564594833684a2f73357a3257374d6a31426e5679656e6d55474668457a36624238674d614b4768654758556254756672372b2b2b38533746596757686d53463562464e50326c4e6d6459765849796c665746693838434e6c2b6f6e343247574766473175352f656c2f7653346373454a32624c47674b72556f68696f304555645746485a4249586f666b794d677936582f3065787447336a5076597264446b545451764c616f6968584a557131706556744a645957726d457745624c6179745135494c684d634a3058745a6d754a3739315858445a696a564d4275494d445657706b464d5679377830507452694253374c6d51355a39733349547366417666655a585334676d6850712b57394675417a79534277624d4d576e614442525077695a6c7067374969507364494a446452373779636a735251396d394961513777396d7736373836624d78564e5a45526b6d425957417852365a4250757a4b4b30782f5372625a2b75362b352b4a50483032464930413047795376725968746577724e326b71316c56756f53366f3478616543747653443752595964724b4c6232312f39636841476c467878785237776d52526d5557543544524b50434d64546446554f5a55797131464c496d5a4a4a5379474a426477524d78475a777841792f716f494f7a4b76695a6b79384830734973623832494f523938393930303938684f61534e52306b4c793249764c75354f716d2b4654516c7642676b625a4d434475645946507462787a75695a755333767665734c51474759434d6f42664b736b7863786f476844457843304d73766b49475773415958322f304c6e55755433654734445132546761417735444c6d36624e68567a3565797a786c6147397875627275766e767173556470466c467a51667332626b56733934595543383970532f2f754d304e6d5842394d424f51384b7a67304c4e536c4e384e634459516563426b4f516b6b2f4552476343757874775754554f685a4c514f65697153306f575a4b4d41575a545a677a3133694177314e325a7955396c6a43616e6e433242397436707764316e752f6f584a416c6a595764616b3543786e4b70792f345863663447686c4d6e6c362f5833787662364130525537453654327832364d41354538304479326e4c49587331356177544b613074424e47724c6d4d55334e437651466976556c71793777794334546a507363754a736e492f48553443635a624a62554c75385750594e4d41434c4c6462564d376c7a35497a646e67714858636d6b575663564d4659536472484d552f7062596f56714d375133655670354f6832626d79332b66524762465a4c586c734f324c326b5a5475513056446959694c6d7366453562724552624d7135415777796778775944646a7758306d5a6a694e6d43316258494378455a416d4f383162733476504f63303535593972656c306b726553686c684d5754357341735951345061636d45583644455a5130747658324a6d5767335475744c4e41636c72792b473449534633716d414975484c61417553366a38707043374e54664b52734c374b79746742676d7750624c6669325831744b676f54316c4263694d455347764c56316365654f633578626c6e317465716a4645526b77673857514d5550416863675a77317865487a4d4247694b7a397655467a70376c3658547862343359664a4338746877746430596b6537705557336f2f4d616374674578764551772b776b4a743651647a32674a4464784941474d43414139304b764f336a5156582f325162494334416853464b36742b7679514f2f4d37454a335172566b4376487a595266544f3479357345745847434a433456504d62445935484a543861677059745162454651557a6764536d366f2b78726c4e3830506a6a656d2f526e6a46585146326e6263706133625033332f58446e514d5459436a425a336d31416869715867315672506c2f694f67633257337648796a374773536d67655331745a446239653074366a6e4670304277325575713351706545357a79386558314d70654f4c4366757550476e4e313731706e4579554d4641704146457a42574f6f574779642b63373330557a747a632f4a4b2b746861557a68585764346c4f714c5552776d36445467754e426d45757571376c79584c58376c6274754f4979734b476548685745584d385a634844466e4d61576c7858334e745256666764683453463562433961615a6f58616b6b71304a574f427476534455463562594e43576a474268764e4f4b6333475969476f6c7237392b444132637650636454324e32706c472b474151414d68583259416937436a7150444c48742b75746c7136336379596e4e414d6c726136473061455a7467536772583651742f5742526573756f4c575a5148674230327952563436634447326b756e6347654d3364666678674d6b344679595a645256634c4f4979706d37343033566e734659694d68655730744a452f61714b327157666c536265556b6c65736e356f3467513438464a636266397648317a5853565a626a2f2b473337586a4d6579656270692f4e637563346a773879516138754267374c445565374d78495a44387470616d4730617243517258324577456249754137326a785642693347474336526a3362524a31415144414e627465334e4e7a4d526432415942525656692b38796a4a537575316c506e61764a43387468617956617458566c34507548527441514144614457686c7361786c585159637a56634465584f61782f31324d4c5a5048314f57494c4f5979377330684142774c582f494f303574476c70394756446244497365716c456356612b717261676f7259517743527843384f706b4b62797242334c344452427278304848544473674434726470715a3077515769514f41796947536771553457347269596c7a7a71377775706536796e486a766455396b4a304c6c5659566c4f6f3961566e4f4b3165716d445473324b7953767259556b63317a5659474a525674366f4c5134674154675654476f774553386264696e49687878347142333275566d376864736b4b4e37747830414b494b7a43544a5366386d6b5459533257586d732f744c7631347657444a374e6856344771637246594e757a536a774d4163495357677763726e356e594b456865577773706d2f43716e4a57767243316b614e53574243444a6e44462b4f6178705a634b75595166653353304e7539416969527555497730776c3444586c2f6859454e5a6f734a74474474764e386170464572712f3941515a49707137756933746264584f545777414a4b2b74525a47326f4e6f554834426962554768746b4469544f493243546e4869364b777979626833563134745a655a796d737270574a6b535636654d43326473515a477a6245704577394a637147714c6b573149334e615743317a69686f774b624633376e375a7143706a357a4762357a4b47585a6e2f64653764562f6e4d78495a416379433247436c414a664e514b68784d684d4c63466b42655a4a684e38724f73746741414a41355a78386d6f7a63636770614770554644644a72797a6c796b5354346c366b39455947332f4c6650716e31754373324775755071332f727168354b4a3443446744424e48392b6a742f577a74797233625273542f39724c35362f30526433596e34683663772f4c56386b675277525744354163347a73586e6a757557726e4a745962576c5669612b45396c47426d4c70565a77595a6c6f77374d4c7471566d39617347303176426849486c6d6e4d4d6f733434385577784c543865716f4d7363654d377839416b3877307a6a574f476f4447495133414f667169384f4c3358632f2b673333716d4a49496c343349456b4663654d73302b347a4e4b637575415458476559726a70516a30326445695a5636465157594f6b4e34647873782f7366422f51663863456d706d53527064474e4c2f50316538797242674f565a6b2b6556304546473232344d6e5436546a38584c766b396751714e75347463415971334577455543516c51654a6735545a6953507a4434457855446e4d4a777465714658476e39754735704b75497564773743584c502f383737396c6e464b695a697a387a6e2f6943317a74746b7748694768796534536d74725049717336663364617569476a75503254785850757771485968303764685a37635445656b5079326c716f6b5579696f4568627457546c426472437a493848436f4d53524868674147317973562f5546503734483133502f714d4e566c34426f6158672b466363654c6a46776d424235636358566c424e5a6b53576b767536786f72636c5075584c5751312f693877424f7667396d6f6e4a7459626b746657516c316d526b6d5679386f4c74475577463042655778494335784171584433697a673757615373323130774976766f46312f6d6a4b77693453726e306e436e7859342b56776173424c5a436f31726f4d562f556579376e4a554a57617951414b42794974766633567a6b71734e795376725558634a7856704377756e2b45434a747142455777777a326b4945414e41412f4b6d38716e724d634b68444d72346f352f6a57677661744c376a3945775848563866384d566c2b756b56436547742b6c634658622b7435747a567364424e415075304668514f527574644d4471664a34366c32596d4a6449586c744c524c7a557047324a494f3239494372534674796f6262416f433339754b714250355750764e37564a556d47797972463463553537646c767473526e366e61785462316f367032327668586d6f634a45572b324d744630736e517745494f354c366b6b785731645835584d5336307a64726965694b59684e5356426557384b735042536d7479544441305467484f4b473474462b4d2b78707a5564686e4f4d7a4d2b6b5462356e396236367074316a4b32583932626b6470334c2f4b75745742316f73354e2b556d412b6c686c37474b31566a385a535a3562544a49586c754c6842386877736f4e4a6b724377635243573748437949747a534272366a4465314d7a5273523331344e6e30687a42636574305064535550384f65655a344370376a763274343459386c7a48734b6f6935444d56665947716a4f76764e42636c72797847614d7455796d4168515256754967416771514b3675336f53773135735832616c6c66694c49493664743859574758475a544c356e4d53536d5271745a4f684d5073617a4846536963444759736b574b48494a44666c7644595844626d71694d314d344c774a537256564c53757632776f4d32744a62616d6e4d645275766155466264737147503847666d644d41495079614752714764736271582b32595934646a4f53736d4142446e7559784a4d61584658666d4578447044387470792b4d3870754a4c425247506b6c644d57513636764d4a4d47344e6c75346935582f6e4a36636770534848684569707876344253305330395a496f6c5670723238396d58685a43425235354678524e6e65674d347673515a49586c754f7943797141616c435672367974674241317859444145514f6b46744a596b644c356956472f58777371674641394a4b5a723949744e6146474d44712f797355435056612f486e59567151704678563841414579537a4a5a4b5a79545746354c58566d54787043576e4c5762514669735a544951536254486b4449446c6669614c6b30477246554566595a7a4e564e416e5a786f5964756d455a3159356a6d6d534575556d4178563248765062646a4437616d654545773241354c55565754696d434c56566d70575843725546426d307841426d5161356e49616f637473376267574543627958626c5572364758324442715658363053776e793030474d68522f35515664394c2f4568745077613476596850676e704e6979584b51744541306d676c466275726b79326d494d47434c6b716c4e7a383446654d637736544555612f6d3150527171314b494e5a546b435a795543514c354c49683130416f46696f3237694a49486c74525a44447a4b76573069414c444e7069706470696d4e4f57666c426d6f45456d42646169414144344533417156505a314730453676556f2f706a573533475167513545454149446565575349576e726c4538714a686b487932714a4d76327a5369306d4e48555a68566a3758547a52714b3964535174416e4137556f4341426e664158356556786c6c32344657477972484246514e626e435a43426a324a58376b625336686f566369587044387471697841506f502b32714d53757642317851714333516d37484d3139736b6351413456316a794c72657373674b2b6468796556636f726f566d682f4751677941354535734d75524b5449617a4e4238747136544235326753677258366f74427177304c744d6657435275796c35457954512f4653355169616d3934642f3231703556766b5263745a584a632b58444c6d4e37687168476f2b584f5271772f4a4b2b7453326861446f2b336c47626c4b32734c4443316c414375444e48417a4167426344454c52376b4757676455752b31417a2f634f7266496d6c6d4c66435a4b42636e6a3458647156544b71647534326143354c563153616531325266614b6d666c533758467374725344396f55414143547846554e5a794c465054685456394c55737370755853313044326f4f39366f6d4e77497378727969504665753835676e34362f512b6f3545454e556765573164556c6f364f6d337a6e2f554b74615548584257306851795167554e474363444d4d4a486d6b7a474270357a5872444979716f553974367779464e4b417a635739574834796b444873306e386b36666458506965787a7043387469347054514f417157633739476d4f777348456e4c6167524673734f3048537134425a676e434b54305546386e4c664547474e47584d303232482f72624671726351736866746a61544f414c696e525a43414141436a6f4e767039356335476241676b7236314c576b3178784c6a505050394b56396e4252414449426c78673042594477477a375468756d415a5a556d424846574d795a3972786a7453732f564f54512b784e6d797970484d366443513168744d6c4252357a452b50316675624d534751504c61756d6a4155366b554146352b73535070747767484533503978434a745351624e75525330537a6752686d535a3746623775384a317a33793139664672376c7074635433417865426731636c417872424c416f7850543163344962482b6b4c79324e47704b426352306b6f302f3257394d6230464a6569756e72567a416c576e504f474e38757831436153347a73614851725056384a4152313248776a67324b47392f35366d4d6d724647497162546e6a4779374d63776b6d412b575141446c696448717133416d4a4459486b7461574a713670654b4f4162643877643678526d35535657724b334d56357478786a4c746536786f6c384265754d364545667532524f38485668386f4653444265333439367531642f546a4165642f42424a6578494d2b564437754d41566575383667757a4b636a564f53317553423562576b53695751752b68682f706a666d7335566d35597530785343764c636a314970487663444b417a4b5169495a35726f774d666a4b34782f704a6c654e2b765234594f466d357975304c4f4c4234737a484f4a4a774d42674e35683549697853784e6c546b5a73474353764c5932714a6a55744451674d5546505a6d522f76544b655a4d437376314a592b326f694d63636236374f695155536f66664147413934626f38432b46466563717533734f44372f2f5034594872316e6c434b4e4f4d4e3531326a2b4d42586d75544e6856564a5761367a784b414f485263785850536d77414a4b2b74546a77654230444f67434e454669786e6e786a4f616375596c516541496d3168566c735a697a462b77414d617877724246774134643855502f742b2b6a6d7454764a4c6c696b474576653955502f7835582b66516d6d49754148687a397659304d4b68684d6c4175374e49344435306c65573036476c4f42517a515073566a4d3658447862414179653972543074572f3762724c694d59385545456e55582b676177734241444d69367244684e697566714a59616b6d7a6138456638505865595a7036317a5a2b516563553655396b45517a65712b2b2b4f74586171306b70384a7953576372362b634330694d45544e6b4c41766e5179557536744c414e474c46314b7861702b4b57486449586c75645744796d6351315159746d367039484466585a33736e506e4845425a62656e64795a7932494c7363324456656d49704251734f717153314874377272593446684666786e4c4b474c53764379465063784e596f416f4e69346f30317a39327464773272507672685634586d58724932584a2b39544e5556694e557747796f5664445033486a356337496247426b4c79324f707a7a6144786d637a6a7a71376c7a664f75524854642b4a4f6e7458646262364362537463557a57624338746e4c39536b53304b33684c4f7a7739787a574f554c482f71434e62654d6656735a357259684a6d4672644142426b354d4a5342535668704247436c4c4566375870322f7675706b4947505a4b7a4b4574425a342b316a5a6b784962523533756145517a45777146387555432b7034556d76546d77337639633234396e38554b303173736179374d4777636c7a4f78697538334a397265676d75626169744a616a6566777851634157493254675354494a4f776a70302b714e43563755304c79496941526a795779425638416f42634d71416e7031596575437332356a6472537a51594762514641546c7353416b4e41674f7661575a384651326e653048335056735370686476506877614e4d56666c79554236683545684c72333655726c7a4568734c79597341414167462f626d774b2f76315a6d70434f7672444137367031707932734642626573435630785a415a6e52535272697a467a7355434b52413277542b437352366e3774304c2b59447275715467665365636e4a754c6e446d624c6e544568734c79597341414967456735796e395442452f31357a3549684d5453684866336867657252487143334a71433364584a6c6e7753726a2b2f6f6b723454524a4778732f4b576d4c592b662f3857345a6a4c6b7561705042744c44726f556a6833466a337a315248704958415144414f512f36394356664d6d46587067764a514e505961342f7647583139324b677456716f74424b5067454d426c6867384e596f734353776c514e3867414850437038562b65693763627771356361416c516d4f664b64523731734576312b333276766c722b334d5147512f49694d67514366744330584e695672593541414f53497034344f76767a346456704b796157336f4c793263736b7668774950447246744670794e51584b56713965736952636d506e342b6b4a2b4458654e6b494433736d6e2f3261533231796d5661695856414d72743256327444624130343577425768354d7a2f55754e79424151455a6d6577772f363746506e657a7237416a5a62334b6774414a4379466133356a53437a4a65736d42727338794e4c38574943624746676b6c4141794a775a6754412f7a4d762f30307a4945514753677633366d666f4a6c66696f7a754a6c356a4a672f6a766b48656f4e584a6a3938597645365130716559645a5a4842455a4b7771374f4349446c424135596e783561664a3733396e6748693952455a49586b53635a6a39746233457a4f6a422f71582f726374783051315952702f48512f53464a6e39314b6d58346b5a632b563355515049372b57427742416b684f3075615a7356542f6c354b41563247655547797774414f6a727869524e4c3179487172345363476675474c4a7664793377365a43775864674644686a6a317665386b357559722f4b36494459666b5252535155704d4f747a7362647248636c782b7949744f417a5539355a79373374485546374c5a3471625a30762b6a615976714a454147673151725874624a346b702f7963345a6755786f6c7231546138647a34723530503773363569575674706639585977774c3831786f434c7551736543354d334f5050313735463056734f4351766f6f42554d696d627a53614c44566e2b323834784c7a4c645a6247595a665430646a5675372b68656c43577467725951414a4872305a6d4a77513433322b764770516963436e454c413474535a336e356f6b4e506a3331364964616463363745696c4c796d5636772f726c3474764f59433773777159352f37637461624b307a77496c47512f4969696b6c47593036504279555a63324658566d51634d7a454c49484c417851583375644d374547527678364c45654d347970646f795373316877723165334f6643534a79663841506e594a4e52596d755646344a3064753665467935394b4b465a44584657515a364c732b4c4a514c6d6e4743424b6a43464f502f4c6a38466d7137576f43534635454d5a716d705a494a52327472396f764e636c474d2f67586e7565383851353657706d66617a35305a426f5a747258355a426c3162414341684e326f4c73355935534d6d4e4141416741456c455156514241415a674d2b457544377570485a30537a6f64674d71796c4e6254496f46747370664a616a6777667566444a692f373948504a6458537a49633255366a376d775338397a356349755857545230584f54442f38514b2f3136694d30434f6e7366714e614732497034652f7464485232364d4c4177374d724a43786e6a687669464b58786b354f4b65666166615773494d674146777a42546a364345564741376d6a7567483078796e5139726c4d437a46495a546d4b51433741693454576d5251414a42427069435738637a796878794167344b51536e6e475a74383345646a4c4452456947743674626968676f6a6e59427138685178344f6e2f336276306f46672b562b4a38536d6775524669454845376c306a5a6f637a4a7939677a4767757a7041426370614a593767684b6437654768375a6333726e396f744f53314b6f4c544163524d5469497742706748416349696b6554334d3178514559496a44474c524a614a54544c504b5443664d523964756275692f344477435244507a47627569724d632b6d5039515a5157435368683130613578652f38672b68382b6646767735693830487949737243544b6265505873566b366b3037414b4a4d6342633241583569677247736762686942327477594874592f31396c337663495a4f6b6c644d57354a4a6c414c6b3177724167616b4f4e34334b557a3865313562686a63766e516a4f2f71734e715345354d6837474935692b6c75676b7a6e555278326f58366334667a4450356f2f386e7a7872344459784a433869456f6f646c76503772306f4b305a7a595848595a636943496572364b6771464a415a655436437265364c4e7665427842543332694d75535568694153467336675467504a534761776f674b7757534c4c7a495944412f35776f50786c4a4d5869436b6638526d376a626e2b5945355648464579354c6d4d5964664369792f4d2f506848346c384273566d687851694a53716952364e7a59614f66494873774753586c7a49544a41514f51496d56554d39566747774669576e68454e344b4b2f645348675a5a434a644469694a47743261384a6d435442466c5a5349496966537944564e5554565455724e777a6152707470547169716b753359395a4e77484c7a6c4c554d6e31512f513167626a46722f523944724755796b502f45735a6c48666778457330487949716f514477546d7838353137647164436139795479427752437955425564645a34617779334171427367526b4948754f303254517a456c4848666d327565434a6f36734d507465374b61736d4c4a577972544a61465158467375333177636f432f5030414141514f5874363872766667665247544c776b31676172316f4167494c6138764844756a46595964756e534d495a6455486739366570686551455668305847534b6a515850707a2b58396f63424d72324c497338375047786c447a7a6b44686332637566664d624e5075365353463545545552586c36655058305374457945776770397754452f45476c515652364f5742683236542b5661596d466a593271796d70524b43626b6d62346b474e706a7958764c4f44455864756d76456a722b4a706d727153463545625553392f6d6d336a366d4a56584f386e4c52666145337941774f47767a464374795574306d75576a585876714444574b7965764a734b7851536c415a6f68344d724661486c792f76492f66336a6975393868637a55314a433969425352436f656d33336c544445614d7663704c694a5145585a4e316b434c734b41714c53414b306f6a444b36795a4341682b77726c673351514c517a4541426f366654386a333477382b685061496e555a6f656d427845725130756c676e4d7a4a72504635484c715256575143356f4b6979534d325851457069665339655036516369327a796e4775493446476c626a5155526a415266502f697a6b543555356737466f6e75644f68616850426b71466770652f386258677156505650695852424e426f493746793074726336564e52763639397a7834756d7a4b3570354b594b322b78544e69564879374d5047566f6e445664506f7a437773356a595838514f525145614b4969696277544e555147454435315975616868326a76367973476b686578536b497a4d7a472f76333376666e74626d7a456d776c7a596c572b726831475a73554a454145504c544851475946525655656578734567695735576137376671735665322b417631496f6b4d47694b507861596666385433786876466e34466f5a6b6865784f704a7857497a72372f71374f3172323732486d63333651574f484d527432465964527869414e382b304671744c645a457a41733879724341493051314973483359466a3738313839696a71584334395030545451334a693167726f616e4a3850786332383568312b41514d6c61554275654975617055686c4259656d6f55575545594a5853545a745269595942575770554b414c487079666e48486f746575676a456c516a4a693667445846555854702f7954567a776a4f783244517a6b6b75734d574845595a667970624843553156424a674a6176537130794761696f4b6a553250376638374e504230366546373561344d71434a325553646b6131577a39414f312b43515a444a7a7a417761636b5257504f4d6e33374d726d517945614a68757a5243423551635145544f6a6c70672f5133363664667a696863576a4c345a477877706e57424a58494351766f69457757586232397a75333737433174656b4b4d376f4a444d737846366d7179453073752b684e727338496851555a2b684c31504262316e7a7a70662b4f312b4d4a69746264475843475176496a476f746a746a7635747a72352b613374627a6c6e47734d74344542464c36726b4b3637594b4a6168464970487838344854707949584c6e41714f7431696b4c7949645549796d2b3364335a614f4c6b744868366d316c5758575a5331576c66462f6a6633426e4f5a5330556838656a6f2b50525736644445784f3176745a596b7246704958735246497a4e7a694d6264364646654c374853616248624a59704773566d514d7a57614f444c51303535716d71756c5958453345745642494451595476755834776e7736516c576d4241434e4e6849625131704c4c43386c6c70657174534f4973744445624949676d684b534630455154516e4a69794349706f546b5252424555304c79496769694b534635455154526c4a433843494a6f536b6865424545304a53517667694361457049585152424e43636d4c4949696d684f52464545525451764969434b497049586b52424e47556b4c7749676d684b534634455154516c4a432b43494a6f536b6864424545304a79597367694b61453545555152464e4347334273444d4d48346d3576716c6f724d636768486d57616867476646466957673736746667667136464f376574545334354d587a4d734c55756c7834737141354c557833484c333075357235717531716f6c593144787a79586c78314848324c6676466336597475505871315465483333332f524f6e784833783165506b5a522b6c7834737141354e58305747324a6f64324a6f64324c372f6f3538433362333379682f656a6a4c61486756672f48694373656b7463566861633138753737493766656f377a347339346e48334b6e6b6c6a744a77696957614837387857497961792b382f3054762f50667837594e4a3675314a59686d6865523178644c614876377337352b39345633686167304a6f696b686556334a53484c71357a383165764d3977576f4e43614c3549486c642b647a2f79664e5876534e61725256424e426d55734e39636e443357455931552b614d676773575373746a54725231786c7a74537562484f687a3837506e64355a48356171646151494a6f476b74666d346d632f614a38634e3156726c61656c566474396266534732786237647667714e444f5a3151392b6176624c582b6a66676c5667784a554b645275626d384179652f6c4a7878632f762f31726637356e667470566f655867794f4c426d32734b307769694b534235585347634f3248355837382f394e4c54665258613350472b426154434c2b4a4b67655231356143712b4b4f7674542f2b7661467944626f4866494f37452b57654a596a6d67755231706648736a31754f5044465137746d444e31485a4248474651504b36416e6e384f36324c632b4c383136344466754678676d673653463558494b714b7a7a37634b587a4b30785a326554546855775452584a4338726b794f76577850784d5656585433624b65314658416c73336a6f764a73754b325979794c43466a45674e676e477463307a51746e5662565a44494261596f677971496d38504b595a2b642b775a4a6862523071674c58302b4a624330614c7432427458464f33536d4c6c4a61336674546d316f6239786b306162487a544f586d2f496a724a464e4a43394a5563774f683858754d446b634a6f73567053707259476f704e526d4c4a734b526543536344496330636c6b6863354f576e667346782b3375744f446f6c7147394f333366782b6433587a32486d436e59505875733433762f757a76535043756774625271392f2f69334a377235786a4c6649514c7039752b383658657262616d3773624c533559566136764835765a596e4a56714c457468736d4a78746c69634c5330416e47767851434471573437342f4a795478514141676a37784839646933627279367475756675712f6a4670744252336e6b59507a6e2f70643955742f764b30706c6a2f7a6471582f7a522b6573377669786f4f446578592f2b77664a4c2f374255447a5742422b68586d796b7168577a78547577726665714136333932315a71726949516d6458743851377536447477304e33544b796c624d596f75497045552f33467a742b7374794d392f6471724958446f39323379333339636352535150666d713679467736625a3342757a363058487238436d5a6a35435570537476323754333772334b30647743723533746773747a5333644f332f3443377078656b657036353654435a7852476f706d32686d374f52767531713930445a4761434837707a622f4e4d50334f33707754324c355a3639376f343565654f3755757648426e7939585a326476667575736e7662717a566341347931645066303762764b357646556133724630754957373034556932375244585536656975744b2b767952486f484e2f7643733131396c643668315a59593269304979713555316c58556b6d4a714778786359772b7864695446314436304d377138744852706f7537706643624c6a456d6761656c30696d2f4b74526f362b38545863574235692f61706b346b71742b7071513051626a317274497a426c4d31364b44574c39354756794f44703337475279726438636e6b3672695867366d6454536153326442673641674c4c454a466b786d53537a68645857333753316568577266654838714a70593230314a597459577436326c785778317942597a47766f59504a314f7857507865437765434d5344495332397967305a363469693850366434693653623362392f756962696b766e7a5a786a62704378434d35786364502f5a6959766d744970575a4c4c586d434c3079745954366e5a5761652f6c74587437686a635554573978645070574d4166437751536b5568563179675771396c70747a686237433375796d6457724a624f5058766d78306154346455733643374c69714f7279395857587135364179564a73547355753850706251644e437938744265646d7137372f68724c2f4856477a526241504b2b643463637863656e77724550537873386661643138743369357a2f49773345717270647269424a434a342f4f574f6132365a466a343763386d7a4f4c66706f38663673523779736e6b3862594d376f4749364e42454a422b646d5933352f375630774e5235543437487777754b69784f78756a36756a3032537a6c32737353584c6e384b3646305850784666724c306570744852684171655a6646474f4f396e5a3757317477646959774d31333778366b6a54494a33766e394f2b4e544d68447352332f52353659627877332f712b72642f484847574c442b626943732f2b57615838456332477a2f35567675325863485739754c4c4f4b584b502f70366a2f4248726c516166717578746253304465374138755a4b52614f7a3538374d6e6a6b6439666c572b5656506135476c705a6e54707862477a71586973584b74474a4d3664753553374c5a7944597267694e3642626437426f5257594b777369746e543364492f73337043696a586666482b6a73437769666576755672547543415143424a656c2f2f76374f3177373370564f5a76796e6e65503530327a2f3879636a4d7051333453363243534a423938662f5a6366544a666a575a767977766a336d2f2f4b636a463839746f54346a4e4472794d6c6c743371477935754b632b36636d412f4e7a7544706e6c52414e42474c426f4b757275365737522f69694b456d644f335a4e6e7a6d6c4a61754d4b3348457a714564567665617675714b3364473161382f7375644e7056644344617842583352433738304d5868452b6c302f6a4745616677716131444f4d432b2f2b583268372f52316a4f67636f436c57586e7a3978614c6949625a77313976652b54623370344246524757357556776f4d6b2b516c316f6f4c79594c4c6674324d6d5975424f754a68494c46386255534c532b66526a4f6557426d4f68594b646d7a66495a6b464e794a4a55546f47682b624f6e6130633562583144367a525844717978647935592b664d32544f72444370587941313368442f776132506c63744a76764e4162574e364b56336b70616749766a6a5a336e4a4a4b347157783576344961365342386d6f644746444d34747977476f6e4d6e523974584479534449646e7a7037754842355772494a4f6f746e68624f6e75385539506c54366c5933573748653064355a37564e45304e683952454970314b41574f794c4d735769386c6d4c7864674b6e5a48355a65724330363364742f4846363635575a7a4b4259423454506e5a3937336c6e69574970714e5238724b36335861502b4b75534349646d78383431656b3249744a716350586532612b6577596e655550757675366f37366c704d785159494d4554313934705649303672716e356d4d4c433656686c464d6c75327433706175626d47537939585648566c655673766e3431594e6b324462634f4c716d34505833444a6e4d6c653647547a2b336533724e6e475853654474534873365649745a733967304453415a5a66456f573135516c68636c6265764f7257772b5842374e35556d5a4c56792f4e63646a47504c4c515239626c34354546526f694c3052733752562f2f39565964503738574b504e70614f6c556e506e783770326a636957346856674f4b4b6e66324475334e6e536e374b3633634b414d5262304c3136346f4b58454a545a614b6857616e3473734c336d336237653146506333456247317232397562465434733062752f766d4671767332416f41736179617a356e53723764306878565339724f793177333076505357516542316845757736454275354b7270744a4e545637793833677a4b6478746d4c3767746e6e65654f32556450577462354f2b423061666639346b4c70385668492f764533432b36312b362b5037627452504e7678795966616c6d627258354877336f3876755553544972674744332b39497a6447724a6a35687a346c71505a51452b796872395a68316f725a776b65756a6733756a6d3462447065377574497065653679382f49462b2b674a78396c6a466a556836484e3836464d4c696d6943327239385362784d3569716f2f6a315a4263374f4c746b692b50357271645473324769353733386a534b7671374e686f37353539705356614671664c37765a452f4d57566e453576473551514477555878736171357132305647722b2f506e4f6e5475744c6e6652553549734d316d752b746c48446f71726b4e62436d54633766766850676739564c7a7a65394733332b512f654e432b634d4679454a50486549562f766b4f2f573930496f5948336a68593458486e4f482f4f7355456970574c75785a2b35627452664b61755754362b472f4e43425763544c4166316b4d545272594e4a2b2b3437354c7771544e7664686972577852462f4248694d57574e386872636e627a353775575271786372682f4141494d6d706e6b46667a364476486538474e536d66654b336a36424f656f67546367554e7a46717667504a7461586f6a5930696c2b66307354343157482b65704f4f70465976446a525072536a39436c58643365527644696932564538487363355837353473617135644a447a355173543366763236584d4a6b504f77337865656e31747066566d393442796e4a32783242772f36366a73304167426764326e3366486a707574756e4a4b6d6d583034527a70625948652b37654d7437706c35397075654a483754474976562f6836746d615634362b56726e5654664f6c6a35313753327a542f36724e31545839623975663939537561634f5039724147342f4f3970486b76522b64336236723748756f67474a4b5858507a3944553354342b6436486a38657976624d6e6d4e31463965396a6176634135516547456847684158487a57617147383535764e59506131467830303275396e705449524375534f79795651616f7958436f5257567936645371752f793564614267654469596e682b4c725875766a6143794e2f3977596b373773646a523371652f4b4633656146752f5a337237676a663937484c6473634b666a4e435a43563130337375485467302f386933427434345572624d655031352f6c47765546364b4b5858543359456e666c43487757696439753730337576455263565434353778307732634557463361752f37784d4b317435596435366d646e66766e66335066776b74503954333648612b7749316c33366e6e3330484731433849756e6b37375a786f373346615a35636c4a30415139634664686b4369624250654e754d46754e524a6558727230396a482f354f574e4e56634f53654c58336a37314f333978366a306638536c726e7273726d2f6848666d50757735385a586275356374686438593938377479446e31325154577439652f586930706a70776c6c783148506f376c6e46584c663365647439792b57715777342f566e6255652b317348306e2b39702b4e316356634f6f6a38707273752f2f61666a5866324e3671517745696435615659724d4c71684d4463624f4d4b49326f686c557945466754704a4b764c7a51787249444852374d5630745553566d48555a6c46675273704a36392f30542f2f612f586c6a4c7457567a614a2f3767347431764f4b4e58482f37354f662b344b4c5474566c2b645563654538764c356f6866643374393867424f6c33627472594c34446742386934376a4c3955364957536c58502f4f3847662b79796d587033696d314e707036777a2b35682b644e5a6c583961315a435857576c37323175477347414b4270516e47734d3447462b644a53666b53307472546b2f3138556e624572613148447a723741622f37523266335872365a75773947692f635966587567644b72756b3339727048664a3939673876654c79626f703769354f7657636a7467337662652b626f73586e6a547651465a45582f505833693873375a45363471352f663242427a38397572704d5a5332597a4f6f364c4e686235357858675169795248784c5655665a316f46304968454a426d777478654f416472636e7370524a565770707758656d776e7a764a73566b566a2f783232652f2f355764727a2b3367766f4a7135312f2b6a395064505455744678794f47696476655263586a436c6b6f776a4b49726d61557432396b5671756457336477562f3958636e2f754650427150684462357463413548487574343446634548396e6245647833512b7a454b32766168306b78383576654c5136376f68484c4b3838305a4337585458654637767546385771744d6f54383971563553797973784f504d5a4e4673396c52625a3678305a767547554539354d596b4a762b65523563327974486255743177714c374d6a2f775657453449467a6d31757436516f363950746665726837663746466338515a6768575739726853586b376b723362513755494170452f2b4f6b7854643331356f73316455786b47583770647961372b71734d7551523939746350747838373670696446482b4b317662302f6873693139362b3246566d3672684f52302f77562f3739354a662f644744443733717650652b342b30474c545a5464752b4f2b68524f76694f735a612b5447643457735a664b47727a7a543159693039315533784f372f35665056577348597162626a4c33724f4872634a35354e35764f6c6442324d4862764c744b4c386d3954705154336d5a52625873504a324b686b4c312f794f73696c6767674a7a7a776e43667959706974756a6a69566f717063626969745669624944497641506235732b5051654d352f6270397a59504e33643675394c377277396666766c6735536b4c6b443337322f4e4c3853433154354f3737324e4c67534b55724e52355466766144675a656563716256536e2f7435515870384b4f753578397a376230326474386e707230645a642f6877504453665238724c72396166395145486e3271363834484a6b716636742b354e4c697236384a7131334a6745747836723369514d5a57536a7a7775364d53736b62624f3949632b633648633449444f6d5463374876746578317a466a53423953394c4c547a7465667472524e39523537306357684e754472675031444d744e496e6b6c777546364c527178647252554b696d616f324d32724a4d544466704c47316a6448752f41646c36584a45666a575a715644762b6b35612f2f303435762f4d33752b656c4b6932354c6375706a767a5668746c66354134316348627635486e454a706337346d62612f2f6b39376a7a7a75716d7975484a7a4479646574662f753751306566374b2f51374f5a374c6f3163765a726358483135366163744b5656386d372b7466483157566136364d657070453266396a372f59576666435855543436473965466d366570424f4c6d722f3964794e662f367665797559794d6a6c752b7371663933377637336556323536396f64547a4679536356524f50564f2f437243654a734f44394b4f5a387142565a464d63586a766232376c306a4a7446593671626c394f7657762f323948553839744c33436a6b476531736a5066617853534357622b41642b5a624a43673165653666764b662b74667863524a56635748763937322f61384d56336837442f7a79354e70724f395a494b4d6a654f694a65716e4450745850743361736357376a6a506e4841776a6b2b2b326a6469736879484c6f7a3146392b7047567033765846507867352f7370714c7538336a74692f39506d523559555635452f72776f6f76754170496f694b706c476a79387759694c446556444e705634374749543377374e54756350587632746d37664c70736157446459583751302f4f77687a39662f596e63735776593958336648564e39513258713064373366587935414149415848687434364b767461356c722f64717a6a6d392f6359527a7362396132384f33626f49644651382f3668612b5130522b3233327253656e75334a666f47525372354e7a7839766b7947634e5659374879397a785939673430502b33362b7a3865584a70666651487a334a547944332b796f397a4962494f6f7237774558342b6b576a5a4d335244453869725572752f7935584c446f787a523657337632622f664f37424e4b7250677a79626b33416e4c502f375a634352596b4d764c67636a762b704267756a49416d4333383576654968384d413450586e65782f3564683179556964657366373447344c35577a713333444f7a345a57723839504b326266454d776576765856324659567064377976624c52372b4a48367a77653636613567755a47426b4e2f2b542f39392b3971584d777a363246662b66487334754b626831785778316e647352426274654b6b6c31324f51726e59303061436856506a4f30366f3650313570476a596963375233394f2b37716e31777947526476372f5757706965554c372b567a754d6177636232583331664765763444645459546873386f4c6e6f612b31317975666566524a35317376697064676437686931392b3238636d48636e4d4d5a535631387a30726d2f66573361384f487844334761636d504f645031666d6d794353342b64345a34564f6168742f2b346a62663075706a4c69502b42656c62667a65346272736131314e6577415276656a4e5565426e52556f496554756b36676f6c51614748736e4c426d4e516448744c5636752f667562393835624c59335153335935664f6d5237367a7664797a373369336f48643237573369506c45714a662f4c6c2f7072544d2f58794d50663643683333373732317456307a65724c2b476e7a394156784b7572474f316332572b6a32393566394f43383857762f35514d4e58785a777434757a4e305366364c357970707973766e44452f2f33696c515a6736556b39354951724f56754e69444f73473577496649517275504c4667634f624d6157486c56784732466e6658377232644f3465566b6f58444e687376502b6d634f436675364f32356f546a543139476e64672b49387a4c50503961334d464f663233574f574153662f6d4776384b6e2b6e637675396a586b316572456332584d596e664572372b6a31746c434c613361675550694f4d692f5a442f32386d705335705535654569634e4979464c553938587a516c5a6d30382b59505739656b38316c4e6570577732637747412b42324a596b5941534d616930366450684f5a7132694c4530754c75336276504f37434e696272506d77544f34616b6669622b426e745a49642b476378393048784c66725a4578352f70483656794542774b76504f6f534a4f55512b76482f6a523337656673586d58784b48324c66654f31746a49633374392f6e4b7a637335386e6a58576f592b7972467a6e3768582b2b4954585933594230394e34417550646c6472565166714b6139535656585938577a4445505a744b33515030397279354b587030366469776570354459376f614f2f6f3262765049706f6d74556b59505734704e7971306661516776625639524a7870657675567a675a4e334646566650745663577070734d796257552b304e4c7a345533484e684c636a76502f477150417049325937762b344f6364675669356f624d522b6f745430746e4848424f623732584b4d474231382f374679487a4664644c304868724f5a4e466f5a497774324d717433766b72486f2f4f693532584e6e34714871772f61535975726375617531663244544672576566453263752b6e645869437633694878576b44486a74622f4f35626a394f76696b33635062487a6b42514176502b3073563352792b33764649375a47626e3533554c692b4b414338386b78443471432b51584865593272435861383866536d68494c747774673744304a5770703777306b51496b30634b454777677a43643650634435324b596c51614f3763325a6d7a702b4d425152562b4563364f7a7336644f324654726b6778666c71636b6e4233354f556c4b647a6c466f5153365a5138667136654b6434694a73365a68525656337335616b306f4e4a524848313534564c78546376334e356348656c3564736b686439386a33672b5544714e527834766e6e5662467a7964596c646548473367485167414c703570654d3171506239614b564675577846746e7269426d45544661436c31425573474a735068756248526d564d6e7773754c6c5a4e36567065376f2f796575787649334a54346a2b4c32356e3850625230703453533470586c624b746e41543553496f39386e534671627a4b726475654a79716b627777754f656446723847376a31766b717a686136394f564a7550595a6a523774584d555768466a7874346d743766724b42647941416d476e772b61472b386b714c5667307433626c6e593546466c61567159675879306b6e47596b73584c6b79664f4235656d4b2b674d4b764c3364712f706f5548476b4667556478664d505a6f796f33392b7866466c6135314a4c516b66676d7a7266717779546f51574762485878596e705064654d3966524936344e516f54623369634f757744672b556671502b716e5937614b6a653962616d772b78372f5932504e446665556c72436f77626249434b4f483730654c5636794745704a4c4a7055735870302b385857354745514134326a7673396468387534357744734b5a744d5a563853785773536c6930595a666c496d34324b32324d742f44396566355238577551655333766c6463584c4c376d6c6935525435476a33664d31447758657157594c4f4a6657694a577a2b392b4b66466f6f784a714f65723541524a52515568737354573836317337694767577254676d664f65316b306f6d467366483530665070737445634a3642676332572f467239517065722f626b7269656b4a5a657955654653303347796832393962646a375138343832504c643952564c5062315253744943455a445a746e7055594c453458734f4b5072476e705a4b7a364948645659734867394a6c54616b5351563559556b367474545876713152636d6758417a305667736e7773724e2f4a6c73545638796f545a4968342f69545934574667524c7a77692f6f4d4b5a77734e3745774f6c6c6d33622f71693539794a427662456b3348784c36316364374a655747773144594b746858706544576c5654596e365831626877765962675533556655754b644c4d36744a51364f7a6171696c546f624b7662587074727839736876724255513338745557595a7a7862764b727659746450534a70354e716359614f464377557334657438784e69717635536d634c3356452b6b582f6b736362653163703144317662476e7354616d6e384c7236723066454141412b7153555242564154316c42634178455131424535763232595963574d53452b34504571767262704a614b72563434554a70436c2b326d4466502f4d6565416247416c75667a49594276546861574c4c5231527152474c72426c7359704c4e4a494a70623737764b34527a754835783275614c6454576d64357a76546856482f445a337a7a61324b764374796765576537736265784e714b746e78594e674b36584f563050594c3568784b696d4b62524d4558335a76652b6d4773674151397456354c35786b4c42706446765152724d3547465453766c4b483934687a666b6b4665716f6f687636432f4c7975706752304e58436c6b63453963574b4b78764c425a6b673835336a786944775845672b6e4732554b33766d2b355849627879453837713956487235576c4f624738746f32736544665346624674563930364e4f576f73377953345842615644506c37757264344f424c59693164676f6b64695842496138432b734d46465162473179623470766e367944507576452b6466707334584a462b6d4a3852427759456247336a643737394f664e485058477073684c494b3069712b2b4953345a734c62455435775978514137433774756a4c624d735a6a797374504e76782b4e6e31424c4b2b65626236327a6b614a3032726e67337362766842496e655546414f463577554a467373587337425250436c7366334a33646b694c344b775a46373362744a4d4d52586c4b314c31797363663235366c44453768496e6c635a4f4663515246382b4a52346f50484a706630516f7774574f32384c30336943665a544a7a6264504943674a65666369595434697148322b3662423442623767344978305941344c566e75787378483669493551584a74797a2b3156332f7275707a335662484e6265475a4c6d784f54566f684c794369777643535936653770364e576a464773566862524f704d4a354952663533376a446c4b6c327956704562563874534f624f4c76655643383266584d4a5539526b66666f322b4a513065364b3333526e51344b766d2b384f6c74736859757a34786c77386c596d4732657650692b2f4b6654743875773747443930744472765361587a2b735857712f727477536a7977634f6a4f4b5a756a2f6d4f4f46585a4671692f316c356557536f555752446450787471486874612f38346a49326f65475369736b4143417750314f36316f335a3666514f624f382f6549307357704b2f646e6970776558362f375a58796e306658533633495031625234712f53354d5479754b7375462f7a7267394d4f743131767536646275324f2b79384c6e356f613936786c686657473876786a6e6e49724b487a7363786545477a34437750475875345662496a614359792b4b355757787176642b645057374835586a39766346577473626e7643435273674c4150797a30384946564257727258316f614a33585776414f626c644568575a714c426f32534e626b634c5432442f5166754c707231323548657a75545a61747a54637661434a6176534e58353237355372727331556d344873355171762f3643594b627557792b49537a4774747353446e356d7034313853455237387a45793546526665504c4c78417a376c574a365454723868726f4d70743449326c4b2f526277536a4a79306876376a6e654d4d3770335a6655382f6c4f7272373154732f494c3747366b3544354b576c5576365a4b654654566e6472327a724f3957767448374237784f584c76736e4c7559494756316458393867655a30636e552f49394f35746e5462503855536e754a47706177374d41465468774b5071687a347957652f625635377146577a43382b47545a6e4d374977666e335046693354766464482f534e4842546e48324e68793876504e6e594a6844587933434d724b3545664f3945785062462b4f51517444556365462b73566b582f6b4e79624b7a6364634b546148396f6e66766c5175783164334769497641416a4e7a796643347253496f3733444f7a6a593650694c49336f48746a733778482b7a38504a694c4a6a50567359446773796c32645779367358495a466d525375556c3276746a66586a6e2b774d662b38317a35526277544d53565a78345742774c524d48766c36624b725972377267596d6237366c443076665133614537507a525237746d6a5032764978766431354e4b6f36644c6f437678312b4c45564e4b344c5235393052634c694f6e3662506636722f336d3864633272624a73742f46662f772b5732726a7063447a58534b486b42774e4c45524f6d496d34366a7461313735374463734b572b6d43783337646a70614266584c71666943642b6c6774784b4d6859746e527541694b34793771754b74555551745355335967744c6c3066376c58382f646538766a46665935503378373236727342374c7a33355561556e792b7a39352f7534502b395a794a3372587a77552b384d746a355a37314c396d662b636d615175443134666c48617932556e376e6b4f58657367664f42684354692b4f54332b386f3936326d4e664f377a35337532722f376d366e5272762f3737462f74334e727738776b67443561556d34677354342b57654e627461757662754d7a7672337830774f353039652f645a52506f414145314c4c34795061656e69794661344c4953726f334d566158754f364f77553146346e6f33575951566b374669752f36344f42332f6d4c553775767156514f4d7670327830745056666f724a434c34364c63713751647a35774d54762f7737553676597539427135352f38725a6c37506c7232496747416e33797a66354f4858546f6e58724d757a6464557448586b386670767931674c4c7a336c6e447866646e7a543659353837672f50484c707a4e596e326e667353762f574673584a373644614f42736f4c41474a2b7632395350483445414a4b69644f33613354593074417042434a464e707261686f6135647534556c585144414f56386150792b6368683165584377646555524a61687463385168706131653363496767466d7049655545526b734a334834772f2b4f6d46332f74664a2b2f362b6646794b58436478546e58642f3766377171376937787878463575553057643364664d2f37752f504876725055476c74706c446b73495033526e2b443339355a742b4e5a586530425943586e2b34373864706d724a416f68584e346f63787349534e42762f324e4978757a7a67726e384e3076395666594f4630787054377771364f2f2f76755842345a724c6474753755782f39484e7a6e2f3639552b55575757776f71387a7031453577626c61535a5664583262794a33654f317556756a7934754232546b317673714f6c636c7163335a31326a336579714a5a6d4267764e354d786c5579456645754f31754b376f746e68624e383576486868764d594e4b4e31643361346577663564695742414f506567694b747643653359732b492f69736d6d6d525374786174364f2b4e6466534770747672416b4e2f2b6a662b78766361744e483734745937756755686e58396c356f445a372f50326650502b7542797876766468783468586e78664f6d306f6b7654494b2b776552564e34594f33727a6b716e6135587837332f4f536247784f6b724937586e6e50633953474c7666774949774163626678386f416f737a6b6b2f2b4d72674a2f36767378567943494e3746762f4e357863766e47303739714c6e37467457345472336471653263332f383670734349316650725835747054577a34752f4a4b76424e54584c4f5737724c33726f5230653574743376626b394649314f2b5068594a714a464a31327a52455a7262624c4b34576d3973746a48534d3644465872474a4a716e3936327535704c6431393075707136646d377a7a383547664574563368585a7166543364316a4b544f424d54686655396e657257564b4765704f79472f2f38702f757148337678555163762f36583233376a6a383633694c61697957463378572b3539394974393049796f537a4f4f6f4c4c5369776d4134444e6e6e4a355647396e324753754b6247794f4f7636502f396a514b33727072614e526b3367713039337666502b6958494e6b6a486c78616472366c6f326a684f7657502b2f667836362f35506e4b7a636248466b6348466b45674b4450766a686e6a51586c5a49724a736d5a31704c776438584b6c6775764d6573674c41507a54552b6d55367532727371474f7957593332657875364f566355324f7864434b5254435a354f73303154644d30786867794a736d7962444a4a4a704e69746458596f655070314e7a3573555331586c73366b516a4d7a4c6846635a4f6b6d4c794451363044413746414942474e716f6d34766d63486b79545a5a444c62624761375337615544636a6a6f574330726d7458724a484a4335372f383963444b3130303362636b66655850646e7a6d39383958445a6f41774752576537623565725a56617964696364623131662b3266564f744956456a4c2f79303564623379735946615932386372673745616e70696d306f4c2f37555a62594d3366506853716e4748433550524c687a326d5a676e655146414b48352b575173316a473467355855454a5343794577324f396a73613039344a4b4f522b66487a36526f3276676141774d793078656b73467a32684a4e7461766262576c59317a3833523638654a4574566272782b765039663377473232723230526a595562362b382f762b4c58666e57677673364c7832706b61392f7a545877304969383432502b4541652b746f312f57335435592b6c55376a6b556333793744704d772b33784d4c44442f7a4b5749582b3431704970575447306f3375556137724a5a494968575a4f6e597a35316d6b386c5850756e35325a50584f36526e50704c4979664636346d75446f34357773317137505268507a32622f7a4e376e2f39782f62566d5576487479523936592b48547237536b476e32727a3758392f6466324e616b35744a352f68473363423230453639304e32366678465877306c4f4f722f37356e6770464d4b764774327a2f332f39314a4a6c6f654744553842636f49705653353866505731304c6e72372b716f6d71745241502b4a636e4c36767853746c5449566f714e546436726e50485473572b316c4568545573766a5a2b765a61767452684f504b53383832762f636f3636366c42334549766a4e762b732b644b667a6e6f39654b6a6550657155452f665a4876746c33374f554758684c727739795563753534652b6c736763627444375271786b36612f2b642f3266574258357172504f61374974343433507554623766564f41713052745a62586a71785944422b2b7053747462576c73367675436f73462f59485a32616f5a7267716b5658586d37426c5058332b35417631615543506868596b4c7137426e66566d63633733795650764c7a7a6e726e6d313536536e4879566433332f3368705774766e53325835616d465246783536656e7570783971585966315964614877342b3046636c722f4654623544724f423671646b4a3939382b2b366877393437763349624f2f324e525671545a7a7a507647766e654f6e31322f6470343252467742777a694e4c5335476c4a61764c3566433257567663776d564f617965744a71504c76744469777172724c5978777a70637658346f734c375830394670644b3575687263616967626e5a79464c39352b765853444b68544532306a4a39786e487a5630644135644b4567652b69723755382b354c33356e73413174797855486f67735a58484f3966726874706566637137506a5872644f482f4b504458684d62726775556333646333483648484c365048744977653662727a4c742f7641596f326c4e6a72706c487a367a6261586e6d77644f376c2b32744c5a4d486e6c6941574473574151455330744c576148302b4a776d477a326d6f6352302f46494f426d4a52494f425a4c6a2b7737654a5347522b394a7869736471385870764c5a524a746d35596a6e556a477734484973712b5766754b5a7431703853335837597963544c4a5843534644794c53712b4f586c2b526c6e5059714b676a7a332b586339502f38577a665467356646566b5943546350524175562b34554356716d4a6c77587a6a704733375a4f6a74656e4f506e53714f586f6b34493541504f5478656450524648594d68705a3034327a6c434f5074582f6b63786c357a5532326e44745774395353716f6f2f776c72796d44706e6a31764f48752b323272743258784d64326850743378467137346f4952615a704f442f746d6878336e6a39685033584d4b677a715833326d577a59314e6d47507a7434487172565a627842524e70735673316b795779525a4273595949736f5354365735706e484f55326f696e55676d45346c31546f517a69536b574b7a4f625a566e5239636f3535796c5656645655504a376575486e586d7843726e6275394b617464553877613536676d4d4235687977767946644d337241795434442f2b7a546c5061775141667643507736382b7439623836596141434331746161637262625a777330564c784a6d715973676e2b5a656c396277376c6d506a493639534f4f6471504b37473477445651356a315245747269556745524e7454456b58454968694c4e4c444875736e52306e443069633737666d4538354c652f38654a6d584d4336466a67482f344c6b5836687a57466f767271686341304673486c352b306857504b55656536457733315479424a6b497975335a5861304d517849704a7039426b556f342b36564c586e49306968477a476e42644245455256714e74494545525451764969434b497049586b52424e47556b4c7749676d684b534634455154516c4a432f692f322b6e446b6741414141414250312f335935415277684c38674b5735415573795174596b6865774a433967535637416b7279414a586b42532f49436c7551464c4d6b4c574a49587343517659456c65774a4b3867435635415576794170626b42537a4a433169534637416b4c32424a587343537649416c6551464c38674b5735415573795174596b6865774a433967535637416b7279414a586b42532f49436c7551464c4d6b4c574a49587343517659456c65774a4b3867435635415576794170626b42537a4a433169534637416b4c32424a587343537649416c6551464c38674b5735415573795174596b6865774a433967535637416b7279414a586b42532f49436c7551464c4d6b4c574a49587343517659456c65774a4b3867435635415576794170626b42537a4a433169534637416b4c32424a587343537649416c6551464c38674b5735415573795174596b6865774a433967535637416b7279414a586b42532f49436c7551464c4d6b4c5741725434795438533670316b6741414141424a52553545726b4a6767673d3d, 1, 1, 0);
INSERT INTO `usuario` (`nombreUsuario`, `contraseña`, `urlFoto`, `idEmpleado`, `idPerfil`, `eliminado`) VALUES
('jtarau', '123456', 0x2e2e2f696d616765732f6176617461722e6a7067, 2, 1, 0),
('rafa', '123', 0x2e2e2f696d616765732f6176617461722e6a7067, 4, 1, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `venta`
--

CREATE TABLE `venta` (
  `id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `idPuntoVenta` int(11) NOT NULL,
  `idCliente` int(11) DEFAULT NULL,
  `formaPago` varchar(19) COLLATE utf8_spanish_ci NOT NULL,
  `Pago` decimal(65,2) DEFAULT NULL,
  `Cambio` decimal(65,2) DEFAULT NULL,
  `estado` tinyint(3) DEFAULT NULL,
  `eliminado` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `idAlmacen` int(11) NOT NULL,
  `idMesa` int(11) DEFAULT NULL,
  `Motivo` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `horaentrega` time NOT NULL,
  `fechaentrega` date NOT NULL,
  `entregadomicilio` tinyint(4) NOT NULL,
  `cobroalentregar` tinyint(4) NOT NULL,
  `direccionenvio` varchar(500) COLLATE utf8_spanish_ci NOT NULL,
  `importetransporte` int(11) NOT NULL,
  `personaentrega` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `minutostoleranciaentrega` time NOT NULL,
  `geolocalizacion` varchar(300) COLLATE utf8_spanish_ci NOT NULL,
  `idTipoDescuento` int(11) NOT NULL DEFAULT '0',
  `porcentajedescuento` int(11) NOT NULL DEFAULT '0',
  `importedescuento` decimal(65,2) NOT NULL DEFAULT '0.00',
  `total` decimal(65,2) DEFAULT '0.00',
  `aCuenta` decimal(65,2) DEFAULT '0.00',
  `saldoACobrar` decimal(65,2) DEFAULT '0.00',
  `cuotasSaldo` decimal(65,2) DEFAULT '0.00',
  `cobrarCada` int(11) DEFAULT '0',
  `ordennumero` int(11) DEFAULT '0',
  `observaciones` text COLLATE utf8_spanish_ci,
  `garantia` int(11) DEFAULT '0',
  `telefono` int(11) DEFAULT '0',
  `ci` int(11) DEFAULT '0',
  `ciudad` int(11) DEFAULT '0',
  `estadoVenta` int(11) DEFAULT '1',
  `nroCuenta` bigint(100) DEFAULT '0',
  `etapa` varchar(20) COLLATE utf8_spanish_ci DEFAULT 'venta',
  `comision` decimal(65,2) DEFAULT '0.00',
  `alquiler` tinyint(4) DEFAULT '1',
  `cancelado` tinyint(4) DEFAULT '0',
  `descuentocliente` decimal(65,2) NOT NULL,
  `idProforma` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `venta`
--

INSERT INTO `venta` (`id`, `fecha`, `hora`, `idPuntoVenta`, `idCliente`, `formaPago`, `Pago`, `Cambio`, `estado`, `eliminado`, `idAlmacen`, `idMesa`, `Motivo`, `created_at`, `updated_at`, `horaentrega`, `fechaentrega`, `entregadomicilio`, `cobroalentregar`, `direccionenvio`, `importetransporte`, `personaentrega`, `minutostoleranciaentrega`, `geolocalizacion`, `idTipoDescuento`, `porcentajedescuento`, `importedescuento`, `total`, `aCuenta`, `saldoACobrar`, `cuotasSaldo`, `cobrarCada`, `ordennumero`, `observaciones`, `garantia`, `telefono`, `ci`, `ciudad`, `estadoVenta`, `nroCuenta`, `etapa`, `comision`, `alquiler`, `cancelado`, `descuentocliente`, `idProforma`) VALUES
(171, '2017-06-01', '15:13:04', 1, 15, 'Efectivo', '0.00', '0.00', 1, 0, 1, NULL, '', NULL, NULL, '00:00:00', '0000-00-00', 0, 0, 'S/E', 0, 'S/R', '00:00:00', '', 2, 10, '610.00', '6100.00', '1647.00', '3843.00', '0.00', 0, 85, '', 0, 0, 0, 0, 1, NULL, 'venta', '549.00', 1, 0, '25.00', 0),
(172, '2017-06-01', '09:16:36', 1, NULL, 'Efectivo', NULL, NULL, 0, 0, 1, NULL, '', NULL, NULL, '00:00:00', '0000-00-00', 0, 0, '', 0, '', '00:00:00', '', 0, 0, '0.00', '0.00', '0.00', '0.00', '0.00', 0, 0, NULL, 0, 0, 0, 0, 1, 0, 'venta', '0.00', 1, 0, '0.00', 0),
(173, '2017-06-01', '14:46:53', 1, 15, 'Efectivo', NULL, NULL, 1, 0, 1, NULL, '', NULL, NULL, '00:00:00', '0000-00-00', 0, 0, 'S/E', 0, 'S/R', '00:00:00', '', 1, 0, '0.00', '10000.00', '3000.00', '7000.00', '2.00', 7, 83, 'obs', 0, 0, 0, 0, 1, NULL, 'credito', '1000.00', 1, 0, '25.00', 0),
(174, '2017-06-01', '14:49:35', 1, 15, 'Efectivo', NULL, NULL, 1, 0, 1, NULL, '', NULL, NULL, '00:00:00', '0000-00-00', 0, 0, 'S/E', 0, 'S/R', '00:00:00', '', 2, 10, '1205.00', '12050.00', '3253.50', '7591.50', '2.00', 7, 84, 'oyoy', 0, 0, 0, 0, 1, NULL, 'credito', '1084.50', 1, 0, '0.00', 0),
(175, '2017-06-01', '15:13:42', 1, 15, 'Efectivo', '0.00', '0.00', 1, 0, 1, NULL, '', NULL, NULL, '00:00:00', '0000-00-00', 0, 0, 'S/E', 0, 'S/R', '00:00:00', '', 2, 10, '201.00', '2010.00', '542.70', '1266.30', '0.00', 0, 86, '', 0, 0, 0, 0, 1, NULL, 'venta', '180.90', 1, 0, '0.00', 0),
(176, '2017-06-01', '15:14:27', 1, 15, 'Efectivo', NULL, NULL, 1, 0, 1, NULL, '', NULL, NULL, '00:00:00', '0000-00-00', 0, 0, 'S/E', 0, 'S/R', '00:00:00', '', 2, 10, '100.00', '1000.00', '270.00', '630.00', '2.00', 7, 87, 'gfgfgfgf', 0, 0, 0, 0, 1, NULL, 'credito', '90.00', 1, 0, '0.00', 0),
(177, '2017-06-01', '15:17:02', 1, NULL, 'Efectivo', NULL, NULL, 0, 0, 1, NULL, '', NULL, NULL, '00:00:00', '0000-00-00', 0, 0, '', 0, '', '00:00:00', '', 0, 0, '0.00', '0.00', '0.00', '0.00', '0.00', 0, 0, NULL, 0, 0, 0, 0, 1, 0, 'venta', '0.00', 1, 0, '0.00', 0),
(178, '2017-06-01', '15:18:29', 1, NULL, 'Efectivo', NULL, NULL, 0, 0, 1, NULL, '', NULL, NULL, '00:00:00', '0000-00-00', 0, 0, '', 0, '', '00:00:00', '', 0, 0, '0.00', '0.00', '0.00', '0.00', '0.00', 0, 0, NULL, 0, 0, 0, 0, 1, 0, 'venta', '0.00', 1, 0, '0.00', 0),
(179, '2017-06-01', '20:56:38', 1, NULL, 'Efectivo', NULL, NULL, 0, 0, 1, NULL, '', NULL, NULL, '00:00:00', '0000-00-00', 0, 0, '', 0, '', '00:00:00', '', 0, 0, '0.00', '0.00', '0.00', '0.00', '0.00', 0, 0, NULL, 0, 0, 0, 0, 1, 0, 'venta', '0.00', 1, 0, '0.00', 0);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `v_ingredientecomposicion`
--
CREATE TABLE `v_ingredientecomposicion` (
`idventa` int(11)
,`idproducto` int(11)
,`idseguimiento` int(11)
,`cantidadoriginal` int(11)
,`cantidadpedida` int(11)
,`costooriginal` decimal(12,2)
,`costocompartido` decimal(12,2)
,`fechahora` datetime
,`eliminado` tinyint(4)
,`idalmacen` int(11)
,`Tipoproducto` varchar(11)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `v_movimientoproductoalmacensucursal`
--
CREATE TABLE `v_movimientoproductoalmacensucursal` (
`Tipo` varchar(18)
,`idProducto` int(11)
,`Cant` bigint(20)
,`idAlmacen` int(11)
,`idsucursal` int(11)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `v_stockalmacensucursal`
--
CREATE TABLE `v_stockalmacensucursal` (
`idalmacen` int(11)
,`idsucursal` int(11)
,`idproducto` int(11)
,`stock` decimal(41,0)
);

-- --------------------------------------------------------

--
-- Estructura para la vista `ejercicio1`
--
DROP TABLE IF EXISTS `ejercicio1`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `ejercicio1`  AS  select `producto`.`nombre` AS `producto`,`detalleventa`.`idProducto` AS `productoid`,`venta`.`idAlmacen` AS `almacenid`,ifnull((select `v_stockalmacensucursal`.`stock` AS `cantidad` from `v_stockalmacensucursal` where ((`v_stockalmacensucursal`.`idproducto` = `producto`.`id`) and (`v_stockalmacensucursal`.`idalmacen` = `venta`.`idAlmacen`))),0) AS `cantidad`,sum(`detalleventa`.`cantidad`) AS `ideal` from ((`venta` join `detalleventa` on((`venta`.`id` = `detalleventa`.`idVenta`))) join `producto` on((`producto`.`id` = `detalleventa`.`idProducto`))) where ((year(`venta`.`fecha`) = (select year(now()))) and (month(`venta`.`fecha`) = 5)) group by `producto`.`nombre`,`detalleventa`.`idProducto`,`venta`.`idAlmacen` order by sum(`detalleventa`.`cantidad`) desc ;

-- --------------------------------------------------------

--
-- Estructura para la vista `idealtodoslosanios`
--
DROP TABLE IF EXISTS `idealtodoslosanios`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `idealtodoslosanios`  AS  select `producto`.`nombre` AS `producto`,`detalleventa`.`idProducto` AS `productoid`,`venta`.`idAlmacen` AS `almacenid`,year(`venta`.`fecha`) AS `anio`,month(`venta`.`fecha`) AS `mes`,sum(`detalleventa`.`cantidad`) AS `ideal` from ((`venta` join `detalleventa` on((`venta`.`id` = `detalleventa`.`idVenta`))) join `producto` on((`producto`.`id` = `detalleventa`.`idProducto`))) group by `producto`.`nombre`,`detalleventa`.`idProducto`,`venta`.`idAlmacen`,year(`venta`.`fecha`),month(`venta`.`fecha`) order by year(`venta`.`fecha`),sum(`detalleventa`.`cantidad`) desc ;

-- --------------------------------------------------------

--
-- Estructura para la vista `v_ingredientecomposicion`
--
DROP TABLE IF EXISTS `v_ingredientecomposicion`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_ingredientecomposicion`  AS  select `c`.`idventa` AS `idventa`,`c`.`idproducto` AS `idproducto`,`c`.`idcomposicion` AS `idseguimiento`,`c`.`cantidadoriginal` AS `cantidadoriginal`,`c`.`cantidadpedida` AS `cantidadpedida`,`c`.`costooriginal` AS `costooriginal`,`c`.`costocompartido` AS `costocompartido`,`c`.`fechahora` AS `fechahora`,`c`.`eliminado` AS `eliminado`,`c`.`idalmacen` AS `idalmacen`,'comida' AS `Tipoproducto` from `composicionproductodetalleventa` `c` where (`c`.`eliminado` = 0) union select `i`.`idventa` AS `idventa`,`i`.`idproducto` AS `idproducto`,`i`.`idingrediente` AS `idseguimiento`,`i`.`cantidadoriginal` AS `cantidadoriginal`,`i`.`cantidadpedida` AS `cantidadpedida`,`i`.`costooriginal` AS `costooriginal`,`i`.`costocompartido` AS `costocompartido`,`i`.`fechahora` AS `fechahora`,`i`.`eliminado` AS `eliminado`,`i`.`idalmacen` AS `idalmacen`,'ingrediente' AS `Tipoproducto` from `ingredienteproductodetalleventa` `i` where (`i`.`eliminado` = 0) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `v_movimientoproductoalmacensucursal`
--
DROP TABLE IF EXISTS `v_movimientoproductoalmacensucursal`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_movimientoproductoalmacensucursal`  AS  select `v_ingredientecomposicion`.`Tipoproducto` AS `Tipo`,`v_ingredientecomposicion`.`idseguimiento` AS `idProducto`,-(`v_ingredientecomposicion`.`cantidadpedida`) AS `Cant`,`v_ingredientecomposicion`.`idalmacen` AS `idAlmacen`,`almacen`.`idsucursal` AS `idsucursal` from ((`almacen` join `sucursal`) join `v_ingredientecomposicion`) where ((`v_ingredientecomposicion`.`idalmacen` = `almacen`.`id`) and (`almacen`.`idsucursal` = `sucursal`.`id`)) union all select 'Compra' AS `Tipo`,`detallecompra`.`idProducto` AS `idProducto`,`detallecompra`.`cantidad` AS `Cant`,`compra`.`idAlmacen` AS `idAlmacen`,`almacen`.`idsucursal` AS `idsucursal` from ((`almacen` join `sucursal`) join (`detallecompra` join `compra` on(((`compra`.`id` = `detallecompra`.`idcompra`) and (`compra`.`eliminado` = 0) and ((`compra`.`estado` = 1) or (`compra`.`estado` = 2)))))) where ((`compra`.`idAlmacen` = `almacen`.`id`) and (`almacen`.`idsucursal` = `sucursal`.`id`)) union all select 'IngresoInventario' AS `Tipo`,`detalleinventario`.`idProducto` AS `idProducto`,`detalleinventario`.`cantidad` AS `Cant`,`inventario`.`idAlmacenDestino` AS `idAlmacen`,`almacen`.`idsucursal` AS `idsucursal` from ((`almacen` join `sucursal`) join (`detalleinventario` join `inventario` on(((`inventario`.`id` = `detalleinventario`.`IdInventario`) and (`inventario`.`eliminado` = 0) and (`inventario`.`estado` = 1) and (`inventario`.`idtipoinventario` = 'Ingreso'))))) where ((`inventario`.`idAlmacenDestino` = `almacen`.`id`) and (`almacen`.`idsucursal` = `sucursal`.`id`)) union all select 'Venta' AS `Tipo`,`detalleventa`.`idProducto` AS `idProducto`,-(`detalleventa`.`cantidad`) AS `-cantidad`,`venta`.`idAlmacen` AS `idAlmacen`,`almacen`.`idsucursal` AS `idsucursal` from ((`almacen` join `sucursal`) join (`detalleventa` join `venta` on(((`venta`.`id` = `detalleventa`.`idVenta`) and (`venta`.`eliminado` = 0) and (`venta`.`estado` = 1) and (`detalleventa`.`estado` <> 0))))) where ((`venta`.`idAlmacen` = `almacen`.`id`) and (`almacen`.`idsucursal` = `sucursal`.`id`)) union all select 'EgresoInventario' AS `Tipo`,`detalleinventario`.`idProducto` AS `idProducto`,-(`detalleinventario`.`cantidad`) AS `-cantidad`,`inventario`.`idAlmacen` AS `idAlmacen`,`almacen`.`idsucursal` AS `idsucursal` from ((`almacen` join `sucursal`) join (`detalleinventario` join `inventario` on(((`inventario`.`id` = `detalleinventario`.`IdInventario`) and (`inventario`.`eliminado` = 0) and (`inventario`.`estado` = 1) and (`inventario`.`idtipoinventario` = 'Egreso'))))) where ((`inventario`.`idAlmacen` = `almacen`.`id`) and (`almacen`.`idsucursal` = `sucursal`.`id`)) union all select 'TraspasoInventario' AS `Tipo`,`detalleinventario`.`idProducto` AS `idProducto`,-(`detalleinventario`.`cantidad`) AS `-cantidad`,`inventario`.`idAlmacen` AS `idAlmacen`,`almacen`.`idsucursal` AS `idsucursal` from ((`almacen` join `sucursal`) join (`detalleinventario` join `inventario` on(((`inventario`.`id` = `detalleinventario`.`IdInventario`) and (`inventario`.`eliminado` = 0) and (`inventario`.`estado` = 1) and (`inventario`.`idtipoinventario` = 'Traspaso'))))) where ((`inventario`.`idAlmacen` = `almacen`.`id`) and (`almacen`.`idsucursal` = `sucursal`.`id`)) union all select 'TraspasoInventario' AS `Tipo`,`detalleinventario`.`idProducto` AS `idProducto`,`detalleinventario`.`cantidad` AS `Cant`,`inventario`.`idAlmacenDestino` AS `idAlmacen`,`almacen`.`idsucursal` AS `idsucursal` from ((`almacen` join `sucursal`) join (`detalleinventario` join `inventario` on(((`inventario`.`id` = `detalleinventario`.`IdInventario`) and (`inventario`.`eliminado` = 0) and (`inventario`.`estado` = 1) and (`inventario`.`idtipoinventario` = 'Traspaso'))))) where ((`inventario`.`idAlmacenDestino` = `almacen`.`id`) and (`almacen`.`idsucursal` = `sucursal`.`id`)) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `v_stockalmacensucursal`
--
DROP TABLE IF EXISTS `v_stockalmacensucursal`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_stockalmacensucursal`  AS  (select `v_movimientoproductoalmacensucursal`.`idAlmacen` AS `idalmacen`,`v_movimientoproductoalmacensucursal`.`idsucursal` AS `idsucursal`,`v_movimientoproductoalmacensucursal`.`idProducto` AS `idproducto`,sum(`v_movimientoproductoalmacensucursal`.`Cant`) AS `stock` from `v_movimientoproductoalmacensucursal` group by `v_movimientoproductoalmacensucursal`.`idAlmacen`,`v_movimientoproductoalmacensucursal`.`idProducto`) ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `almacen`
--
ALTER TABLE `almacen`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `banco`
--
ALTER TABLE `banco`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `bitacora`
--
ALTER TABLE `bitacora`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `cargo`
--
ALTER TABLE `cargo`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `ciudad`
--
ALTER TABLE `ciudad`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`),
  ADD KEY `FK__Ciudad__idPais__1273C1CD` (`idPais`);

--
-- Indices de la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `cobroacuota`
--
ALTER TABLE `cobroacuota`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `cobrocuota`
--
ALTER TABLE `cobrocuota`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK__CobroCuot__idCue__76969D2E` (`idCuentaCobrar`),
  ADD KEY `FK__CobroCuot__idPun__75A278F5` (`idPuntoVenta`);

--
-- Indices de la tabla `composicionproducto`
--
ALTER TABLE `composicionproducto`
  ADD PRIMARY KEY (`idProducto`,`id`),
  ADD KEY `FK__Composici__idPro__5CD6CB2B` (`id`);

--
-- Indices de la tabla `composicionproductodetalleventa`
--
ALTER TABLE `composicionproductodetalleventa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idventa` (`idventa`),
  ADD KEY `idproducto` (`idproducto`);

--
-- Indices de la tabla `compra`
--
ALTER TABLE `compra`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `concepto`
--
ALTER TABLE `concepto`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `cuentaacobrar`
--
ALTER TABLE `cuentaacobrar`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `cuentabancaria`
--
ALTER TABLE `cuentabancaria`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `cuentacobrar`
--
ALTER TABLE `cuentacobrar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK__CuentaCob__idPun__72C60C4A` (`idPuntoVenta`),
  ADD KEY `FK__CuentaCob__idVen__71D1E811` (`idVenta`);

--
-- Indices de la tabla `detallecompra`
--
ALTER TABLE `detallecompra`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `detallefactura`
--
ALTER TABLE `detallefactura`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK__DetalleFa__idFac__01142BA1` (`idFactura`);

--
-- Indices de la tabla `detalleinventario`
--
ALTER TABLE `detalleinventario`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `detalleproforma`
--
ALTER TABLE `detalleproforma`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `detalleventa`
--
ALTER TABLE `detalleventa`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `egreso`
--
ALTER TABLE `egreso`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK__Egreso__idProvee__6EF57B66` (`idProveedor`),
  ADD KEY `FK__Egreso__idPuntoV__6E01572D` (`idPuntoVenta`),
  ADD KEY `FK__Egreso__idTipoEg__6D0D32F4` (`idTipoEgreso`);

--
-- Indices de la tabla `empleado`
--
ALTER TABLE `empleado`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK__Empleado__idCarg__412EB0B6` (`idCargo`),
  ADD KEY `FK__Empleado__idTurn__4222D4EF` (`idTurno`);

--
-- Indices de la tabla `empresa`
--
ALTER TABLE `empresa`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `factura`
--
ALTER TABLE `factura`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idVenta` (`idVenta`),
  ADD KEY `FK__Factura__idLibro__7C4F7684` (`idLibroOrden`),
  ADD KEY `FK__Factura__idPunto__7E37BEF6` (`idPuntoVenta`),
  ADD KEY `FK__Factura__idVenta__7D439ABD` (`idVenta`);

--
-- Indices de la tabla `gastocompra`
--
ALTER TABLE `gastocompra`
  ADD PRIMARY KEY (`idGastoCompra`);

--
-- Indices de la tabla `horario`
--
ALTER TABLE `horario`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK__Horario__idTurno__44FF419A` (`idTurno`);

--
-- Indices de la tabla `ingrediente`
--
ALTER TABLE `ingrediente`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `ingredienteproducto`
--
ALTER TABLE `ingredienteproducto`
  ADD PRIMARY KEY (`id`,`idIngrediente`),
  ADD KEY `FK__Ingredien__idIng__5812160E` (`idIngrediente`),
  ADD KEY `FK__Ingredien__idUni__59063A47` (`idUnidadMedida`),
  ADD KEY `eliminar` (`eliminar`);

--
-- Indices de la tabla `ingredienteproductodetalleventa`
--
ALTER TABLE `ingredienteproductodetalleventa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idventa` (`idventa`),
  ADD KEY `idproducto` (`idproducto`);

--
-- Indices de la tabla `ingreso`
--
ALTER TABLE `ingreso`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK__Ingreso__idPunto__6A30C649` (`idPuntoVenta`),
  ADD KEY `FK__Ingreso__idTipoI__693CA210` (`idTipoIngreso`);

--
-- Indices de la tabla `inventario`
--
ALTER TABLE `inventario`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `libroorden`
--
ALTER TABLE `libroorden`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK__LibroOrde__idSuc__797309D9` (`idSucursal`);

--
-- Indices de la tabla `marca`
--
ALTER TABLE `marca`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `marcacion`
--
ALTER TABLE `marcacion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK__Marcacion__idEmp__47DBAE45` (`idEmpleado`);

--
-- Indices de la tabla `mesa`
--
ALTER TABLE `mesa`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `modulo`
--
ALTER TABLE `modulo`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `motivomovimiento`
--
ALTER TABLE `motivomovimiento`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `objeto`
--
ALTER TABLE `objeto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK__Objeto__idModulo__300424B4` (`idModulo`);

--
-- Indices de la tabla `origen`
--
ALTER TABLE `origen`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pagocredito`
--
ALTER TABLE `pagocredito`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pais`
--
ALTER TABLE `pais`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `perfil`
--
ALTER TABLE `perfil`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `perfilobjeto`
--
ALTER TABLE `perfilobjeto`
  ADD PRIMARY KEY (`idPerfil`,`idObjeto`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `FK__PerfilObj__idObj__35BCFE0A` (`idObjeto`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigoDeBarra` (`codigoDeBarra`),
  ADD KEY `FK__Producto__idTipo__5441852A` (`idTipoProducto`);

--
-- Indices de la tabla `productosucursal`
--
ALTER TABLE `productosucursal`
  ADD PRIMARY KEY (`idproducto`,`idsucursal`),
  ADD KEY `idsucursal` (`idsucursal`);

--
-- Indices de la tabla `proforma`
--
ALTER TABLE `proforma`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK__Venta__idCliente__628FA481` (`idCliente`),
  ADD KEY `FK__Venta__idPuntoVe__619B8048` (`idPuntoVenta`);

--
-- Indices de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`),
  ADD KEY `FK__Proveedor__idCiu__3C69FB99` (`idCiudad`);

--
-- Indices de la tabla `puntoventa`
--
ALTER TABLE `puntoventa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK__PuntoVent__idEmp__4BAC3F29` (`idEmpleado`),
  ADD KEY `FK__PuntoVent__idSuc__4AB81AF0` (`idSucursal`);

--
-- Indices de la tabla `subtipoproducto`
--
ALTER TABLE `subtipoproducto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idtipoproducto` (`idtipoproducto`);

--
-- Indices de la tabla `sucursal`
--
ALTER TABLE `sucursal`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`),
  ADD KEY `FK__Sucursal__idCiud__25869641` (`idCiudad`);

--
-- Indices de la tabla `tipocliente`
--
ALTER TABLE `tipocliente`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tipodescuento`
--
ALTER TABLE `tipodescuento`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tipoegreso`
--
ALTER TABLE `tipoegreso`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `tipogastoscompra`
--
ALTER TABLE `tipogastoscompra`
  ADD PRIMARY KEY (`idTipoGasto`);

--
-- Indices de la tabla `tipoingreso`
--
ALTER TABLE `tipoingreso`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `tipomoneda`
--
ALTER TABLE `tipomoneda`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tipoproducto`
--
ALTER TABLE `tipoproducto`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `turno`
--
ALTER TABLE `turno`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `unidadmedida`
--
ALTER TABLE `unidadmedida`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`nombreUsuario`),
  ADD KEY `FK__Usuario__idEmple__4E88ABD4` (`idEmpleado`),
  ADD KEY `FK__Usuario__idPerfi__4F7CD00D` (`idPerfil`);

--
-- Indices de la tabla `venta`
--
ALTER TABLE `venta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK__Venta__idCliente__628FA481` (`idCliente`),
  ADD KEY `FK__Venta__idPuntoVe__619B8048` (`idPuntoVenta`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `almacen`
--
ALTER TABLE `almacen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT de la tabla `banco`
--
ALTER TABLE `banco`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `bitacora`
--
ALTER TABLE `bitacora`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `cargo`
--
ALTER TABLE `cargo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT de la tabla `ciudad`
--
ALTER TABLE `ciudad`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT de la tabla `cliente`
--
ALTER TABLE `cliente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT de la tabla `cobroacuota`
--
ALTER TABLE `cobroacuota`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `cobrocuota`
--
ALTER TABLE `cobrocuota`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;
--
-- AUTO_INCREMENT de la tabla `composicionproducto`
--
ALTER TABLE `composicionproducto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
--
-- AUTO_INCREMENT de la tabla `composicionproductodetalleventa`
--
ALTER TABLE `composicionproductodetalleventa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=192;
--
-- AUTO_INCREMENT de la tabla `compra`
--
ALTER TABLE `compra`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
--
-- AUTO_INCREMENT de la tabla `concepto`
--
ALTER TABLE `concepto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `cuentaacobrar`
--
ALTER TABLE `cuentaacobrar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT de la tabla `cuentabancaria`
--
ALTER TABLE `cuentabancaria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `cuentacobrar`
--
ALTER TABLE `cuentacobrar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;
--
-- AUTO_INCREMENT de la tabla `detallecompra`
--
ALTER TABLE `detallecompra`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
--
-- AUTO_INCREMENT de la tabla `detallefactura`
--
ALTER TABLE `detallefactura`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `detalleinventario`
--
ALTER TABLE `detalleinventario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;
--
-- AUTO_INCREMENT de la tabla `detalleproforma`
--
ALTER TABLE `detalleproforma`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `detalleventa`
--
ALTER TABLE `detalleventa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=406;
--
-- AUTO_INCREMENT de la tabla `egreso`
--
ALTER TABLE `egreso`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `empleado`
--
ALTER TABLE `empleado`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT de la tabla `empresa`
--
ALTER TABLE `empresa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `factura`
--
ALTER TABLE `factura`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT de la tabla `gastocompra`
--
ALTER TABLE `gastocompra`
  MODIFY `idGastoCompra` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `horario`
--
ALTER TABLE `horario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;
--
-- AUTO_INCREMENT de la tabla `ingrediente`
--
ALTER TABLE `ingrediente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `ingredienteproducto`
--
ALTER TABLE `ingredienteproducto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=384;
--
-- AUTO_INCREMENT de la tabla `ingredienteproductodetalleventa`
--
ALTER TABLE `ingredienteproductodetalleventa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;
--
-- AUTO_INCREMENT de la tabla `ingreso`
--
ALTER TABLE `ingreso`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `inventario`
--
ALTER TABLE `inventario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
--
-- AUTO_INCREMENT de la tabla `libroorden`
--
ALTER TABLE `libroorden`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `marca`
--
ALTER TABLE `marca`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT de la tabla `marcacion`
--
ALTER TABLE `marcacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `mesa`
--
ALTER TABLE `mesa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `modulo`
--
ALTER TABLE `modulo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT de la tabla `motivomovimiento`
--
ALTER TABLE `motivomovimiento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `objeto`
--
ALTER TABLE `objeto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;
--
-- AUTO_INCREMENT de la tabla `origen`
--
ALTER TABLE `origen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT de la tabla `pagocredito`
--
ALTER TABLE `pagocredito`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `pais`
--
ALTER TABLE `pais`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT de la tabla `perfil`
--
ALTER TABLE `perfil`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT de la tabla `perfilobjeto`
--
ALTER TABLE `perfilobjeto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=433;
--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=384;
--
-- AUTO_INCREMENT de la tabla `proforma`
--
ALTER TABLE `proforma`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `puntoventa`
--
ALTER TABLE `puntoventa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `subtipoproducto`
--
ALTER TABLE `subtipoproducto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT de la tabla `sucursal`
--
ALTER TABLE `sucursal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `tipocliente`
--
ALTER TABLE `tipocliente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `tipodescuento`
--
ALTER TABLE `tipodescuento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `tipoegreso`
--
ALTER TABLE `tipoegreso`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `tipogastoscompra`
--
ALTER TABLE `tipogastoscompra`
  MODIFY `idTipoGasto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `tipoingreso`
--
ALTER TABLE `tipoingreso`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `tipomoneda`
--
ALTER TABLE `tipomoneda`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `tipoproducto`
--
ALTER TABLE `tipoproducto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT de la tabla `turno`
--
ALTER TABLE `turno`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `unidadmedida`
--
ALTER TABLE `unidadmedida`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `venta`
--
ALTER TABLE `venta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=180;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `composicionproductodetalleventa`
--
ALTER TABLE `composicionproductodetalleventa`
  ADD CONSTRAINT `composicionproductodetalleventa_ibfk_1` FOREIGN KEY (`idventa`) REFERENCES `venta` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `composicionproductodetalleventa_ibfk_2` FOREIGN KEY (`idproducto`) REFERENCES `producto` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `ingredienteproductodetalleventa`
--
ALTER TABLE `ingredienteproductodetalleventa`
  ADD CONSTRAINT `ingredienteproductodetalleventa_ibfk_1` FOREIGN KEY (`idventa`) REFERENCES `venta` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `ingredienteproductodetalleventa_ibfk_2` FOREIGN KEY (`idproducto`) REFERENCES `producto` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `productosucursal`
--
ALTER TABLE `productosucursal`
  ADD CONSTRAINT `productosucursal_ibfk_1` FOREIGN KEY (`idproducto`) REFERENCES `producto` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `productosucursal_ibfk_2` FOREIGN KEY (`idsucursal`) REFERENCES `sucursal` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `subtipoproducto`
--
ALTER TABLE `subtipoproducto`
  ADD CONSTRAINT `subtipoproducto_ibfk_1` FOREIGN KEY (`idtipoproducto`) REFERENCES `tipoproducto` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
