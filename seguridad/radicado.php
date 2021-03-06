<?php
/**
 * En este frame se van cargado cada una de las funcionalidades del sistema
 *
 * Descripcion Larga
 *
 * @category
 * @package      SGD Orfeo
 * @subpackage   Main
 * @author       Community
 * @author       Skina Technologies SAS (http://www.skinatech.com)
 * @license      GNU/GPL <http://www.gnu.org/licenses/gpl-2.0.html>
 * @link         http://www.orfeolibre.org
 * @version      SVN: $Id$
 * @since
 */

        /*---------------------------------------------------------+
        |                     INCLUDES                             |
        +---------------------------------------------------------*/


        /*---------------------------------------------------------+
        |                    DEFINICIONES                          |
        +---------------------------------------------------------*/


        /*---------------------------------------------------------+
        |                       MAIN                               |
        +---------------------------------------------------------*/


error_reporting(7);
$krdold = $krd;
session_start();
$ruta_raiz = "..";
if (!$krd)
    $krd = $krdold;
//include "$ruta_raiz/rec_session.php";	
error_reporting(7);

if (!$nurad)
    $nurad = $rad;
if ($nurad) {
    $ent = substr($nurad, -1);
}
include_once("$ruta_raiz/include/db/ConnectionHandler.php");
$db = new ConnectionHandler("$ruta_raiz");
include_once "$ruta_raiz/include/tx/Historico.php";
include_once ("$ruta_raiz/class_control/TipoDocumental.php");
include_once "$ruta_raiz/include/tx/Expediente.php";
$trd = new TipoDocumental($db);
$encabezadol = "$PHP_SELF?" . session_name() . "=" . session_id() . "&opcionExp=$opcionExp&numeroExpediente=$numeroExpediente&dependencia=$dependencia&krd=$krd&nurad=$nurad&coddepe=$coddepe&codusua=$codusua&depende=$depende&ent=$ent&tdoc=$tdoc&codiTRDModi=$codiTRDModi&codiTRDEli=$codiTRDEli&codserie=$codserie&tsub=$tsub&ind_ProcAnex=$ind_ProcAnex";
?>
<html>
    <head>
        <title>Tipificar Expediente</title>
        <link href="<?= $ruta_raiz . $ESTILOS_PATH2 ?>bootstrap.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="<?= $ruta_raiz . $_SESSION['ESTILOS_PATH_ORFEO'] ?>">
        <script>
            function regresar() {
                document.TipoDocu.submit();
            }
        </script><style type="text/css">
            <!--
            .style1 {font-size: 14px}
            -->
        </style>
    </head>
    <body bgcolor="#FFFFFF">
        <?
        ?>
        <form method="post" action="radicado.php?krd=<?= $krd ?>&numRad=<?= $numRad ?>" name="TipoDocu">
            <?
            /*
             * Adicion nuevo Registro
             */
//if ($tdoc !=0 && $tsub !=0 && $codserie !=0 && $varInser == "Aceptar"  )
            if ($Actualizar && $tsub != 0 && $codserie != 0) {
                if (!$digCheck) {
                    $digCheck = "E";
                }
                $codiSRD = $codserie;
                $codiSBRD = $tsub;
                $trdExp = substr("00" . $codiSRD, -2) . substr("00" . $codiSBRD, -2);
                $expediente = new Expediente($db);
                if (!$expManual) {
                    $secExp = $expediente->secExpediente($dependencia, $codiSRD, $codiSBRD, $anoExp);
                } else {
                    $secExp = $consecutivoExp;
                }
                $consecutivoExp = substr("00000" . $secExp, -5);
                $numeroExpediente = $anoExp . $dependencia . $trdExp . $consecutivoExp . $digCheck;

                //	$db->conn->debug = true;

                /**  Procedimiento que Crea el Numero de  Expediente
                 *  @param $numeroExpediente String  Numero Tentativo del expediente, Hya que recordar que en la creacion busca la ultima secuencia creada.
                 *  @param $nurad  Numeric Numero de radicado que se insertara en un expediente.
                 */
                $numeroExpedienteE = $expediente->crearExpediente($numeroExpediente, $nurad, $dependencia, $codusuario, $usua_doc, $usuaDocExp, $codiSRD, $codiSBRD, 'false', $fechaExp);
                if ($numeroExpedienteE == 0) {
                    echo "<CENTER><table class=borde_tab><tr><td class=titulosError>EL EXPEDIENTE QUE INTENTO CREAR YA EXISTE.</td></tr></table>";
                } else {
                    /**  Procedimiento que Inserta el Radicado en el Expediente
                     *  @param $insercionExp Numeric  Devuelve 1 si inserto el expediente correctamente 0 si Fallo.
                     *
                     */
                    $insercionExp = $expediente->insertar_expediente($numeroExpediente, $nurad, $dependencia, $codusuario, $usua_doc);
                }
                $codiTRDS = $codiTRD;
                $i++;
                $TRD = $codiTRD;
                $observa = "*TRD*" . $codserie . "/" . $codiSBRD . " (Creacion de Expediente.)";
                include_once "$ruta_raiz/include/tx/Historico.php";
                $radicados[] = $nurad;
                $tipoTx = 51;
                $Historico = new Historico($db);
                $Historico->insertarHistoricoExp($numeroExpediente, $radicados, $dependencia, $codusuario, $observa, $tipoTx, 0);
            }
            ?>
<!--            <table border=0 width=70% align="center" class="borde_tab" cellspacing="0">
                <tr align="center" class="titulos2">
                    <td height="15" class="titulos2">Nivel de seguridad del radicado No.<?= $numRad ?></td>
                </tr>
            </table>-->
            <center>
                <div id="titulo" style="width: 80%;" align="center">Nivel de seguridad del radicado No.<?= $numRad ?></div>
                <table width="80%" border="1" cellspacing="1" cellpadding="0" align="center" class="borde_tab">
                    <tr >
                        <td width="62%" class="titulos5" >Nivel</td>
                        <td width="38%" class=listado2>
                            <select name=nivelRad class=select>
                                <?
                                if ($nivelRad == 0)
                                    $datoss = " selected ";
                                else
                                    $datoss = "";
                                ?>
                                <option value=0 <?= $datoss ?>>Publico</option>
                                <?
                                if ($nivelRad == 1)
                                    $datoss = " selected ";
                                else
                                    $datoss = "";
                                ?>
                                <option value=1 <?= $datoss ?>>Confidencial</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <TD class=titulos5 COLSPAN=2 >
                            <center>
                            Si selecciona Confidencial, La persona que posee el Radicado sera la unica que lo podra ver.
                            </center>
                        </TD>
                    </tr>
                    <tr class="titulos5">
                        <td align="center">
                        <input type="submit" class="botones" name=grbNivel value="Grabar Nivel">
                        </td>
                        <td align="center">
                        <input name="Cerrar" type="button" class="botones" id="envia22" onClick="opener.regresar();window.close();"value="Cerrar"></center>
                    </td>
                    </tr>
                    <tr>
                        <td class="titulos5" colspan="2" ><center>&nbsp;<?= $descTipoExpediente ?> - <?= $expDesc ?></center></td>
                    </tr>
                </table>
                <br>
                <br>
                </form>
                </span>
                <p>
                    <?
                    if ($grbNivel and $numRad) {
                        if ($nivelRad == 1) {
                            $query = "UPDATE RADICADO SET SGD_SPUB_CODIGO=1 where radi_nume_radi='$numRad'";
                            $observa = "Radicado Confidencial";
                        } else {
                            $query = "UPDATE RADICADO SET SGD_SPUB_CODIGO=0 where radi_nume_radi='$numRad'";
                            $observa = "Radicado Publico.";
                        }
                        if ($db->conn->Execute($query)) {
                            echo "<span class=alarmas>El nivel de seguridad se actualiz&oacute; correctamente.";
                            include_once "$ruta_raiz/include/tx/Historico.php";
                            $codiRegH = "";
                            $Historico = new Historico($db);
                            $codiRegE[0] = $numRad;
                            $radiModi = $Historico->insertarHistorico($codiRegE, $dependencia, $codusuario, $dependencia, $codusuario, $observa, 54);
                        } else {
                            echo "<span class=titulosError> !No se pudo actualizar el nivel de seguridad!";
                        }
                    }
                    ?>
                    <b><?= $mensaje_err ?></b>
                </p>
                </span>
            </center>
    </body>
</html>
