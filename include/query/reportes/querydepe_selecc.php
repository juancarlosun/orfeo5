<?
	/**
	  * CONSULTA VERIFICACION PREVIA A LA RADICACION
	  */
	switch($db->driver)
	{  
	 case 'mssql':
			$radi_nume_sal = "convert(varchar(14), RADI_NUME_SAL)";
			$where_depe = " and ".$db->conn->substr."(".$radi_nume_sal.", 5, ".$longitud_codigo_dependencia.") in ($lista_depcod)";
	break;		
	case 'oracle':
	case 'oci8':
	case 'oci805':		
			$where_depe = "and ".$db->conn->substr."(a.radi_nume_sal, 5, ".$longitud_codigo_dependencia.") in ($lista_depcod)";
	break;		
	
	//Modificado skina
	default:
		//$where_depe = "and cast(".$db->conn->substr."(a.radi_nume_sal, 5, ".$longitud_codigo_dependencia.") as integer) in ($lista_depcod)";
		$where_depe = "and ".$db->conn->substr."(cast(a.radi_nume_sal as varchar(15)), 5, ".$longitud_codigo_dependencia.") in ($lista_depcod)";
	}
?>
