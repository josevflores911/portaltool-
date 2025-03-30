<?php
   header('Content-type: text/html; charset=utf-8');
   error_reporting(0);
   error_reporting(E_ALL);
  
   require_once "../../vendor/autoload.php";
   use PHPMailer\PHPMailer\PHPMailer;
   use PHPMailer\PHPMailer\SMTP;
   use PHPMailer\PHPMailer\Exception;

   include_once('cls_gravarlog.php');

   class cls_email extends cls_connect {
         static $conn=NULL;
         static $cursor=NULL;
         static $connected=FALSE;
       
         static $name_destinatario = NULL;
         static $email_destinatario = NULL;
         static $email_copy = NULL;
         static $subject = NULL;
         static $body = NULL;
         static $omail = NULL;
         static $anexo = NULL;
         static $host = NULL;
         static $username=NULL;
         static $password=NULL;
         static $Error = '0';
         static $email_from=NULL;
         static $nome_from = NULL;
         static $id_user = NULL;
         static $id_nota = NULL;
         static $dbname=NULL;

         function __construct ($id_user,$id_nota, $mailto, $name, $subject, $body=NULL,$email_copy=NULL, $fileanexo=NULL,$dbname=NULL) {
            self::$id_user = $id_user;
            self::$id_nota = $id_nota;
            self::$name_destinatario = ucfirst(strtolower($name));
            self::$email_destinatario = $mailto;
            self::$email_copy = $email_copy;
            self::$anexo = $fileanexo;
            self::$subject = $subject;
            self::$dbname = $dbname;
            $name_signature= self::getUserName($id_user);
            $signature ="<p><p><b><i><br>Atenciosamente,<br>$name_signature</i></b><br><p><p><p><p>";
            $lgpd = "<hr><br><I style='font-family:Arial;font-size:10pt'>Esta mensagem contém informações confidenciais. Se você não se encontra na lista de destinatários ou tenha recebido por engano,<br>";
            $lgpd .= "não a copie, imprima, envie, ou utilize, de qualquer forma, seu conteúdo. Neste caso, destrua a mensagem e, por favor, notifique o remetente.<br>";
            $lgpd .= "A empresa considera opiniões, conclusões e outras informações que não se relacionem com o negócio da corporação, de responsabilidade exclusiva do usuário do serviço..<I>";
            $agora = new \DateTime();
            $dt_hota = $agora->format('Y-m-d H:i:s');
            $vhora = explode(" ", $dt_hota);
            $hora = $vhora[1];
            if ($hora < '12:00:00' and $hora > "05:00:01") {
                $saudacao = 'Bom dia';
            } elseif ($hora > "12:00:01" and $hora < "19:00:00") {
                $saudacao = "Boa tarde";
            } else {
                $saudacao = "Boa noite";
            }
            if (strpos($body, $saudacao) == false) {
                $saudacao .= " " . $name . "!<p>";
            }
            $body = $saudacao . $body . $signature . $lgpd;

            self::$body = $body;
            self::$host ='smtp.kinghost.com.br';
            self::$username  = "portal@portaltools.com.br.com";
            self::$password  = "pf23Wi_cea1at@";
            self::$email_from = "portal@portaltools.com.br.com";
            self::$nome_from ="Atendimento-portaltools.com.br";
            // conectar com mail server
            ini_set('SMTP', 'smtp.hostinger.com'); 
            ini_set('smtp_port', 587); 
            self::$omail = new PHPMailer();
            if (self::$omail) {

                self::$connected = TRUE;
                self::$omail->isSMTP();
                self::$omail->Subject = self::$subject;
                self::$omail->SMTPAuth = true;
                self::$omail->SMTPSecure = "tls";
                self::$omail->Host = self::$host;
                self::$omail->Username=self::$username;
                self::$omail->Password = self::$password;
                self::$omail->SMTPAutoTLS = true;
                self::$omail->SMTPAuth = true;
                self::$omail->Port=587;
                self::$omail->IsHTML(true);
                self::$omail->CharSet   = "UTF-8";
                self::$omail->SMTPDebug = 0;
                self::$omail->Debugoutput = 'html';

            }   
            
         }

         function sendEmail() {
            if (self::$connected) {
                self::$omail->setFrom(self::$email_from, self::$nome_from);
                self::$omail->addAddress(self::$email_destinatario, self::$name_destinatario);
                if (! is_null(self::$email_copy) and ! empty(self::$email_copy)) {
                    self::$omail->addCC(self::$email_copy,$name='');
                }
                self::$omail->Subject = self::$subject;
                self::$omail->Body = self::$body;

                if (!is_null(self::$anexo)) {
                     $dir_upload = scandir("../uploads");
                     foreach (self::$anexo as $file) {
                        sleep(10);
                        $file = addslashes($file);
                        $basename = basename($file);
                        self::$omail->addAttachment($file,$basename);
                    }
                }

                try {
                    self::$omail->send();
                    $ret = array("Error" => "0", "Error_email" =>  self::$omail->ErrorInfo, "Message" => "Mensagem enviada com sucesso");    
                    // gravar log
                    $id_oper = 12;
                    $table_name="";
                    $te_descricao = "Enviou e-mail para " . self::$email_destinatario . ' com cópia para ' . self::$email_copy . "\n";
                    $te_descricao .= "Assunto " . self::$subject . '\n';
                    $olog = new cls_gravarlog(self::$id_user,$id_oper,self::$id_nota,$table_name,$te_descricao,self::$dbname);
                    $olog->gravar_log();
                } catch(Exception $err) {
                    $ret = array("hots" => self::$host, "username" => self::$username, 'password' => self::$password,"Error" => $err->getCode(),'Message' => "Mensagem não pode ser enviada:{" . self::$omail->ErrorInfo . "}");
                }

                return $ret;
            } else {
                return array("Error" => "504", "Message" => "Não conectou com servidor de email");
            }
         }

         static function getUserName($id_user) {
            $cmd = "SELECT nome FROM users WHERE id =?";
            $result = (new cls_connect)->dbquery($cmd, $id_user);
            $result = json_decode($result);
            if ($result->nrecords > 0) {
                $nome = ($result->records[0])->nome;
            } else {
                $nome = "";
            }
            return $nome;
         }

   }
?>