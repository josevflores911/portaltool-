<?php
    include_once "cls_connect.php";
    class cls_imgnotas extends cls_connect {
        static $conn= NULL;
        static $cursor=NULL;
        static $oimg = NULL;
        static $content = NULL;
        static $id_nota=NULL;
        static $id_user = NULL;
        static $tipo_reg = NULL;
        static $xml = NULL;
        static $img = NULL;
        static $erro = 0;
        static $message="";
        static $mimetype = array( "/9j/2w==" => "image/jpg",				
                                  "/9j/7g==" => "image/jpeg",
                                  "JVBERi0"  => "application/pdf",
                                  "R0lGODdh" => "image/gif",
                                  "R0lGODlh" => "image/gif",
                                  "iVBORw0KGgo" => "image/png" );

        function __construct($id_nota, $id_user, $tdr, $validar=NULL) {
            parent::__construct();
            if (self::$conn === NULL) {
                self::$conn = parent::$conn;
            }
            self::$id_nota = $id_nota;
            self::$tipo_reg = $tdr;
            self::$id_user = $id_user;
            if (self::$id_nota === NULL or self::$tipo_reg === NULL) {
                return NULL;
            }
            if ($validar == NULL) {
                $sql ="SELECT * from notasimg WHERE id_Nota=?";
                $result = json_decode($this->dbquery($sql, $id_nota));
                $reglido = ($result->nrecords > 0);
            } else {
                $reglido = FALSE;
            }
            if ($reglido) {
                self::$cursor  = $result->records[0];
                self::$content = self::$cursor->blob_nota;
                if ($tdr=='7') {
                    self::$xml = self::$cursor->te_xml;
                }
            } else {
                self::$content = self::getimgpdf();
            }
        }

        static function getimgpdf() {
            $content = NULL;
            if (self::$tipo_reg !== '7') {
                $link = "http://app-26314.nuvem-us-02.absamcloud.com/pdf/" . self::$id_nota;
           
                $contextOptions = array(
        
                'ssl' => array(
                    'header'        => "https://portalfornecedores.com",
                    'user_agent'    => "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.169 Safari/537.36",
                    'verify_peer'   => false,
                    'timeout'       => 60,
                    'connecttimeout' => 100,));
                
                $sslContext = stream_context_create($contextOptions);
                try {
                    $data=file_get_contents($link,false,$sslContext); 
                    $json = json_decode($data,true);
                    if ($json === FALSE) {
                        $content = NULL;
                        self::$erro="500";
                        self::$message = "Link errado ou campos faltando";
                    } else {
                        if (in_array("error", $json) == FALSE) {
                            if (in_array("data_img", $json)) {
                                $content = $json['data_img'];
                            } else {
                                $content = "";
                                self::$erro="300";
                                self::$message="Arquivo corrompido ou inválido";
                            }
                        } else {
                            self::$erro="400";
                            self::$message = $json['error'];
                        }
                    }
                } catch (Exception $e) {
                    self::$erro="300";
                    self::$message="Arquivo corrompido ou inválido";
                }
            } else {
                try {
                   if (is_null(self::$img)) {
                        if (self::$xml !== NULL) {
                            $soap_url = "https://ws.meudanfe.com/api/v1/get/nfe/xmltodanfepdf/API";
                            $headers = array(
                                "POST /package/package_1.3/packageservices.asmx HTTP/1.1",
                                "Host: privpakservices.schenker.nu",
                                "Content-Type: application/soap+xml; charset=utf-8",
                                "Content-Length: ".strlen(self::$xml)
                                ); 
                            $ch = curl_init();
                            curl_setopt($ch, CURLOPT_URL, $soap_url);
                            curl_setopt($ch, CURLOPT_POST, true);
                            curl_setopt($ch, CURLOPT_POSTFIELDS, self::$xml);
                            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                            $response = curl_exec($ch); 
                            curl_close($ch);
                            if ($response) {
                                $response1 = str_replace("<soap:Body>","",$response);
                                $response2 = str_replace("</soap:Body>","",$response1);
                                $content = simplexml_load_string($response2);
                            } else {
                                self::$erro="300";
                                self::$message="Arquivo corrompido ou inválido";
                            }
                        } else {
                            self::$erro="300";
                            self::$message="Arquivo corrompido ou inválido";
                        }  
                    } else {
                        $content = base64_decode(self::$content,true);
                    }
                } catch (Exception $e) {
                    self::$erro="300";
                    self::$message="Arquivo corrompido ou inválido";
                }
            }
            return $content;
        }
        static function getMimeType($text) {
            $ret="";
            foreach (self::$mimetype as $key => $mime) {
                if (strpos($text, $key) !== FALSE) {
                    $ret = $mime;
                    break;
                }
            }             
            return ( strlen($ret) > 0 ) ? $ret : NULL;       
        }

        static function gravarLog($objConn) {
            $cmd = "INSERT INTO log(id_user, id_oper, id_nota, Nm_Tabela, Te_Descricao) VALUES (?,?,?,?,?);";
            $id_oper=3;
            $nm_table="notasimg";
            $description="Consultou imagem da nota";
            $result = json_decode($objConn->dbquery($cmd, self::$id_user, $id_oper,self::$id_nota, $nm_table, $description));
            return $result->error;
        }

        public function getContent() {
            $tipo = "";
            $mime=NULL;
            if (self::$content) {
                $texto = substr(self::$content,0,13);
                $mime = self::getMimeType($texto);
                if ($mime !== NULL) {
                    if (stripos($mime, "image") !== false) {
                        $tipo = "img";
                    } else {
                        $tipo = "pdf";
                    }
                    self::$erro=self::gravarLog($this);
                } else {
                    self::$message="Arquivo corrompido ou inválido";
                    self::$erro = "300";
                }
            }
            return json_encode(array("error" => self::$erro, "msg" => self::$message, "tipo" => $tipo, "mime" => $mime, "data" => self::$content));
        }
    }
?>