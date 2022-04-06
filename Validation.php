<?php
class Validation
{
	public function Validate($data=array(), $reqFields=array())
	{
        $result=0;
        $errorMsg=array();
        foreach($reqFields as $keys)
        {
            if(!is_array($data[$keys])){
                $str=str_replace(" ", "", $data[$keys]);
                $length=strlen($str);
                if($data[$keys]==''||$data[$keys]==null
                ||$length==0)
                {
                    $errorMsg[]=
                    ucwords(str_replace("_", " ", $keys)).
                    " is required ";
                    $result=1;
                    break;
                }
                else if($length>=255)
                {
                    $errorMsg[]=
                    ucwords(str_replace("_", " ", $keys)).
                    " Exceeds the character length";
                    $result=1;
                    break;
                }
            }
            else
            {
                $size=sizeof($data[$keys]);
                if($size==0){
                    $errorMsg[]=
                    ucwords(str_replace("_", " ", $keys)).
                    " is required ";
                    $result=1;
                    break;
                }
                else
                {
                    for($i=0;$i<$size;$i++){
                        $substr=$data[$keys][$i];
                        $substr=str_replace(" ", "", $substr);
                        $lengthsub=strlen($substr);
                        if($substr==null||$lengthsub==0||$substr=="")
                        {
                            $errorMsg[]=
                            ucwords(str_replace("_", " ", $keys)).
                            " is required ";
                             $result=1;
                             break;
                        }
                    }
                }
            }
        }
        $returnArray=array(
            'block'=> $result,
            'code' => 400,
            'message' => "Required Fields missing",
            'data' => $errorMsg
        );
        return $returnArray;
    }
}
?>