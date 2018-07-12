<?php

namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
 use Carbon\Carbon;
use App\Http\Requests;

class CodigocontrolController extends Controller
{
    

    public function index()
    {
 //allegedrc4('7904006306693' . '876814' . '1665979' . '19/05/2008' . '35958,6', 'PNRU4cgz7if)[tr#J69j=yCS57i=uVZ$n@nv6wxaRFP+AUf*L7Adiq3TT[Hw-@wt' . 'asda');VERHOEFF
  $autorizacion ='1904008691195';
 $factura ='978256';
 $nitci = '0';
 $fecha = '20080201';
 $monto = '26006';
 $llave = 'pPgiFS%)v}@N4W3aQqqXCEHVS2[aDw_n%3)pFyU%bEB9)YXt%xNBub4@PZ4S9)ct';
  $usandometodo=$this->generate($autorizacion,$factura,$nitci,$fecha,$monto,$llave);

   $date = Carbon::now();
        $fecha = $date->toDateString();
       $lafecha= str_replace('-','',$fecha);
    return      $lafecha;
    }

 
    
/**
     * @param String $authorizationNumber Numero de autorizacion
     * @param String $invoiceNumber Numero de factura
     * @param String $nitci Número de Identificación Tributaria o Carnet de Identidad
     * @param String $dateOfTransaction fecha de transaccion de la forma AAAAMMDD
     * @param String $transactionAmount Monto de la transacción 
     * @param String $dosageKey Llave de dosificación
     */
    function generate($authorizationNumber, $invoiceNumber, $nitci, $dateOfTransaction, $transactionAmount, $dosageKey){        
        
        //validación de datos
        if( empty($authorizationNumber) || empty($invoiceNumber) || empty($dateOfTransaction) || 
                empty($transactionAmount) || empty($dosageKey) || (!strlen($nitci)>0 )  ){            
            throw new InvalidArgumentException('<b>Todos los campos son obligatorios</b>');
        }else{
            $this->validateNumber($authorizationNumber);
            $this->validateNumber($invoiceNumber);
            $this->validateNumber($dateOfTransaction);
            $this->validateNumber($nitci);
            $this->validateNumber($transactionAmount);
            $this->validateDosageKey($dosageKey);
        }
        
        //redondea monto de transaccion 
        $transactionAmount = $this->roundUp($transactionAmount);
                
        /* ========== PASO 1 ============= */
        $invoiceNumber = self::addVerhoeffDigit($invoiceNumber,2);
        $nitci = self::addVerhoeffDigit($nitci,2);
        $dateOfTransaction = self::addVerhoeffDigit($dateOfTransaction,2);
        $transactionAmount = self::addVerhoeffDigit($transactionAmount,2);
        //se suman todos los valores obtenidos
        $sumOfVariables = $invoiceNumber
                          + $nitci
                          + $dateOfTransaction
                          + $transactionAmount;
        //A la suma total se añade 5 digitos Verhoeff
        $sumOfVariables5Verhoeff = self::addVerhoeffDigit($sumOfVariables,5);  
        
         /* ========== PASO 2 ============= */
        $fiveDigitsVerhoeff = substr($sumOfVariables5Verhoeff,strlen($sumOfVariables5Verhoeff)-5);        
        $numbers = str_split($fiveDigitsVerhoeff);
        for($i=0;$i<5;$i++){
             $numbers[$i] = $numbers[$i] + 1;             
        }
                
        $string1 = substr($dosageKey,0, $numbers[0] );
        $string2 = substr($dosageKey,$numbers[0], $numbers[1] );
        $string3 = substr($dosageKey,$numbers[0]+ $numbers[1], $numbers[2] );
        $string4 = substr($dosageKey,$numbers[0]+ $numbers[1]+ $numbers[2], $numbers[3] );
        $string5 = substr($dosageKey,$numbers[0]+ $numbers[1]+ $numbers[2]+ $numbers[3], $numbers[4] );        
        
        $authorizationNumberDKey = $authorizationNumber . $string1;
        $invoiceNumberdKey = $invoiceNumber . $string2;
        $NITCIDKey = $nitci . $string3;
        $dateOfTransactionDKey = $dateOfTransaction . $string4;        
        $transactionAmountDKey = $transactionAmount . $string5;
        
          /* ========== PASO 3 ============= */        
        //se concatena cadenas de paso 2
        $stringDKey = $authorizationNumberDKey . $invoiceNumberdKey . $NITCIDKey . $dateOfTransactionDKey . $transactionAmountDKey;         
        //Llave para cifrado + 5 digitos Verhoeff generado en paso 2
        $keyForEncryption = $dosageKey . $fiveDigitsVerhoeff;              
        //se aplica AllegedRC4
        $allegedRC4String =  self::allegedrc4($stringDKey, $keyForEncryption,true);
        
        /* ========== PASO 4 ============= */
        //cadena encriptada en paso 3 se convierte a un Array         
        $chars = str_split($allegedRC4String);
        //se suman valores ascii
        $totalAmount=0;
        $sp1=0;
        $sp2=0;
        $sp3=0;
        $sp4=0;
        $sp5=0;
        
        $tmp=1;
        for($i=0; $i<strlen($allegedRC4String);$i++){
            $totalAmount += ord($chars[$i]);
            switch($tmp){
                case 1: $sp1 += ord($chars[$i]); break;
                case 2: $sp2 += ord($chars[$i]); break;
                case 3: $sp3 += ord($chars[$i]); break;
                case 4: $sp4 += ord($chars[$i]); break;
                case 5: $sp5 += ord($chars[$i]); break;
            }            
            $tmp = ($tmp<5)?$tmp+1:1;
        }
        
        /* ========== PASO 5 ============= */    
        //suma total * sumas parciales dividido entre resultados obtenidos 
        //entre el dígito Verhoeff correspondiente más 1 (paso 2)
        $tmp1 = floor($totalAmount*$sp1/$numbers[0]);
        $tmp2 = floor($totalAmount*$sp2/$numbers[1]);
        $tmp3 = floor($totalAmount*$sp3/$numbers[2]);
        $tmp4 = floor($totalAmount*$sp4/$numbers[3]);
        $tmp5 = floor($totalAmount*$sp5/$numbers[4]);
        //se suman todos los resultados
        $sumProduct = $tmp1 + $tmp2 + $tmp3 + $tmp4 + $tmp5;        
        //se obtiene base64
        $base64SIN = self::big_base_convert($sumProduct); //
        
        /* ========== PASO 6 ============= */        
        //Aplicar el AllegedRC4 a la anterior expresión obtenida
        return self::allegedrc4($base64SIN, $dosageKey.$fiveDigitsVerhoeff);
    }

 
     public function big_base_convert($value) {
       $dictionary = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9", 
                            "A", "B", "C", "D", "E", "F", "G", "H", "I", "J",
                            "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", 
                            "U", "V", "W", "X", "Y", "Z", "a", "b", "c", "d",
                            "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", 
                            "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", 
                            "y", "z", "+", "/");        
        $quotient = 1;                 
        $word = "";
        while ($quotient > 0)
        {
            $quotient = floor($value / 64);
            $remainder = $value % 64;
            $word = $dictionary[$remainder] . $word;
            $value = $quotient;
        }
        return $word;
    }    
 
     function allegedrc4($message, $key,$unscripted=false) {
      
        $state = range(0, 255);
        $x=0;
        $y=0;
        $index1=0;
        $index2=0;        
        $nmen="";        
        $messageEncryption="";
        
        for($i=0;$i<=255;$i++){
            //Index2 = ( ObtieneASCII(key[Index1]) + State[I] + Index2 ) MODULO 256
            $index2 =  ( ord($key[$index1]) +  $state[$i] + $index2) % 256;
            //IntercambiaValor( State[I], State[Index2] )
            $aux = $state[$i];
            $state[$i] = $state[$index2];
            $state[$index2] = $aux;
            //Index1 = (Index1 + 1) MODULO LargoCadena(Key)
            $index1 = ($index1 + 1 ) % strlen($key);
        }        
        //PARA I = 0 HASTA LargoCadena(Mensaje)-1 HACER
        for($i=0; $i<strlen($message);$i++ ){
            //X = (X + 1) MODULO 256
            $x = ($x + 1) % 256;
            //Y = (State[X] + Y) MODULO 256
            $y = ($state[$x] + $y) % 256;
            //IntercambiaValor( State[X] , State[Y] )
            $aux = $state[$x];
            $state[$x] = $state[$y];
            $state[$y] = $aux; 
            //NMen = ObtieneASCII(Mensaje[I]) XOR State[(State[X] + State[Y]) MODULO 256]
            $nmen = ( ord($message[$i])) ^ $state[($state[$x]+$state[$y]) % 256];
            //MensajeCifrado = MensajeCifrado + "-" + RellenaCero(ConvierteAHexadecimal(NMen))            
            $nmenHex = strtoupper(dechex($nmen));               
            $messageEncryption = $messageEncryption . (($unscripted)?"":"-"). ((strlen($nmenHex)==1)?('0'.$nmenHex):$nmenHex);            
        }        
        return (($unscripted)?$messageEncryption:substr($messageEncryption,1,strlen($messageEncryption))); 
    }

 
      static public $d = array(
        array(0,1,2,3,4,5,6,7,8,9),
        array(1,2,3,4,0,6,7,8,9,5),
        array(2,3,4,0,1,7,8,9,5,6),
        array(3,4,0,1,2,8,9,5,6,7),
        array(4,0,1,2,3,9,5,6,7,8),
        array(5,9,8,7,6,0,4,3,2,1),
        array(6,5,9,8,7,1,0,4,3,2),
        array(7,6,5,9,8,2,1,0,4,3),
        array(8,7,6,5,9,3,2,1,0,4),
        array(9,8,7,6,5,4,3,2,1,0)
    );

    static public $p = array(
        array(0,1,2,3,4,5,6,7,8,9),
        array(1,5,7,6,2,8,3,0,9,4),
        array(5,8,0,3,7,9,6,1,4,2),
        array(8,9,1,6,0,4,3,5,2,7),
        array(9,4,5,3,1,2,6,8,7,0),
        array(4,2,8,6,5,7,3,9,0,1),
        array(2,7,9,3,8,0,6,4,1,5),
        array(7,0,4,6,9,1,3,2,5,8)
    );
    static public $inv = array(0,4,3,2,1,5,6,7,8,9);
    private function calcsum($num) {

     if(!preg_match('/^[0-9]+$/', $num)) {
         throw new \InvalidArgumentException(sprintf("Error! Value is restricted to the number, %s is not a number.",
                                                    $num));
        }
    
        $r = 0;
        foreach(array_reverse(str_split($num)) as $n => $N) {
            $r = self::$d[$r][self::$p[($n+1)%8][$N]];
        }
        return self::$inv[$r];

    }

    private function verhoeff_add_recursive($number, $digits) {
   return sprintf("%s%s", $number, self::calcsum($number));
    }
 private function addVerhoeffDigit($value,$max){
        for($i=1;$i<=$max;$i++){
            $value .= self::calcsum($value);            
        }            
        return $value;
    }
     private   function roundUp($value){        
        //reemplaza (,) por (.)        
        $value2 = str_replace(',','.',$value);
        //redondea a 0 decimales        
        return round($value2, 0, PHP_ROUND_HALF_UP);
    }

        function validateNumber($value){
        if(!preg_match('/^[0-9,.]+$/', $value)){
            throw new InvalidArgumentException(sprintf("Error! Valor restringido a número, %s no es un número.",$value));
        }
    }
    
    function validateDosageKey($value){
        if(!preg_match('/^[A-Za-z0-9=#()*+-_\@\[\]{}%$]+$/', $value)){
            throw new InvalidArgumentException(sprintf("Error! llave de dosificación,<b> %s </b>contiene caracteres NO permitidos.",$value));
        }
    }

}
