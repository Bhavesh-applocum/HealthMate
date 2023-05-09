<?php
namespace App\Helpers;

class KeyEncryption
{
  public static function generateSalt_e($length)
  {
    $random = "";
    srand((double) microtime() * 1000000);

    $data = "AbcDE123IJKLMN67QRSTUVWXYZ";
    $data .= "aBCdefghijklmn123opq45rs67tuv89wxyz";
    $data .= "0FGH45OP89";

    for ($i = 0; $i < $length; $i++) {
            $random .= substr($data, (rand() % (strlen($data))), 1);
    }
    return $random;
  }

  public static function encrypt($input)
  {
    $secret_key = config('params.secret_key');
    $iv = config('params.iv');
    $key = hash('sha256', $secret_key);
    $salt = KeyEncryption::generateSalt_e(4);

    $input=$input.'||'.$salt;
    $data = base64_encode ( $input);

    // $data = openssl_encrypt ( $input , "AES-128-CBC" , $key, 0, $iv );
    // $data = str_replace("/","000",$data);
    $data = str_replace("=","123",$data);

    return $data;
  }
  public static function decrypt($input)
  {
    $secret_key = config('params.secret_key');
    $iv = config('params.iv');
    $key = hash('sha256', $secret_key);

    $input = str_replace("123","=",$input);
    // $input = str_replace("000","/",$input);
    // $output = openssl_decrypt($input, "AES-128-CBC" , $key, 0, $iv);

    $output = base64_decode ($input);

    $data =  explode('||',$output);

    return $data[0];
  }

}
