<?php
ini_set('include_path', '.;C:\xampp\php\PEAR');
require_once "../vendor/autoload.php";
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

define ('PLAN_READ', 0);
define ('PLAN_WRITE', 1);
define ('PLAN_READWRITE', 2);

define ("EXCEL_XLS", 0);
define ("EXCEL_XLSX", 1);
define ("EXCEL_CSV", 2);
define ("EXCEL_ODS", 3);

define ("ALL", 0);
define ("FOLDER", 1);

class cls_excel {

    static $plan_file = array('Xls', 'Xlsx', 'CSV',  'Ods');
    static $file_type = null;
    static $oper = PLAN_READ;
    static $plan_loader = NULL;
    static $dir_filename= NULL;
    static $filename = NULL;
    static $file_extesion = NULL;
    static $reader = NULL;
    static $writer = NULL;
    static $numrows=0;
    static $lastletter=NULL;
    static $spreadsheet = NULL;
    static $datasheet = NULL;
    static $vfirstRow = array();
    static $originalData = NULL;

    function __construct ($filename, $type_oper = PLAN_READ, $type_file = NULL) {


        if (self::$filename === NULL) {
            if (gettype($filename) === "string") {
                self::$filename = $filename;
            } else {
                self::$filename = NULL;
                return;
            }
            
        } 
        if (self::url_exists(self::$filename)) {
            self::$dir_filename = dirname(self::$filename);
            self::$file_extesion = self::getExtension();
            if ($type_file === NULL) {
                $idx = self::getArrayType();
                if ($idx !== false) {
                    self::$file_type = self::$plan_file[$idx];
                } else {
                    self::$dir_filename = NULL;
                    self::$filename = NULL;
                    self::$file_extesion = NULL;
                    return;
                }
            } else {
                if ($type_file >=0 and $type_file <= 3) {
                    self::$file_type = self::$plan_file[$type_file];
                } else {
                    self::$dir_filename = NULL;
                    self::$filename = NULL;
                    self::$file_extesion = NULL;
                    return;
                }
            }
           if ($type_oper === PLAN_READ or $type_oper === PLAN_READWRITE) {
                self::$reader =  PhpOffice\PhpSpreadsheet\IOFactory::createReader(self::$file_type);

                if ($type_oper === PLAN_READWRITE) {
                    self::$spreadsheet = new PhpOffice\PhpSpreadsheet\Spreadsheet();
                    self::$writer = PhpOffice\PhpSpreadsheet\IOFactory::createWriter(self::$spreadsheet,self::$file_type);
                }
                if (self::$file_type == PLAN_READ) {
                    self::$reader->setLoadSheetsOnly(true);
                }
                self::$spreadsheet = self::$reader->load(self::$filename);
            } else {
                self::$dir_filename = NULL;
                self::$filename = NULL;
                self::$file_extesion = NULL;
                return;
            }
        } else {
            if ($type_oper !== PLAN_WRITE) {
                self::$dir_filename = NULL;
                self::$filename = NULL;
                return;
            } else {
                self::$oper = $type_oper;
                if (stripos(self::$filename, '/') === FALSE) {
                    self::$dir_filename = "./files/";
                    self::$filename = self::$dir_filename . self::$filename;
                }
                self::$spreadsheet = new PhpOffice\PhpSpreadsheet\Spreadsheet();
                self::$writer = PhpOffice\PhpSpreadsheet\IOFactory::createWriter(self::$spreadsheet,self::$file_type);
                
            }
        }
    }

    static function url_exists($url) {
        return curl_init($url) !== false;
    }

    /*
        retorna a pasta informada pelo indice, default retorna a primeira pasta
    */

    static function getSheetInfo($index=0) {
        $sheet = self::$spreadsheet->getSheet($index);
        return $sheet;
    }

    /*

        metodo: skipRows
        parametros: vetor, numero de linhas
    */

    static function skipRows($vetor, $nrows) {
        if ($nrows > 0) {
            $aux = array();
            foreach ($vetor as $idx => $row)  {
                if ($idx <= $nrows) {
                    continue;
                }
                $columns = array();
                foreach ($row as $key => $value) {
                    $cell = $key . strval($idx);
                    $columns[$cell] = $value;
                }
                array_push($aux, $columns);
            }
            return $aux;
        } else {
            return $vetor;
        }
    }
    /*
        retorna o número da coluna da celula
    */
    static function formatKeys($val) {
        $cell = preg_replace("/[0-9]+/", "", $val);
        $ncel = strlen($cell);
        $npos = 0;
        for ($i = 0; $i < $ncel; $i++) {
            $letter = substr($cell, $i,1);
            $npos = (26* $i)+ (ord($letter) -65);
        }
        $npos +=1;
        return $npos;
        
    }

    /*
        metodo: FilterData
        parametros: vetor, string ou dicionário com as regras de filtragem, ncols
        realizar o filtro observando se o parametro é string ou se o conteúdo for um dicionario
        estrutura do dicionario
        chave = "endereço da celula, coluna ou range de endereços de celulas"
        conteúdo da chave = campo de dado válido no excell tipos (string, números, data completa ou data abrevida)
        números podem ser inteiros, decimais ou percentuais
    */

    static function FilterData($data, $filter_string) {
        // primeiro limpa as linhas nulas
        $aux = array();
        foreach ($data as $key => $row) {
            $bachei = false;
            foreach($row as $cel=> $value) {
                $value .= "";
                if (!empty($value) or strlen($value) > 0) {
                    $bachei = true;
                    break;
                }
            }
            if ($bachei) {
                array_push($aux, $row);
            } else {
                break;
            }
        }
        $data = $aux;
        if ($filter_string !== NULL or !empty($filter_string)) {
            // verifica se o filtro é uma string se for verifica o conteudo em todas as colunas
            $aux = array();

            if (gettype($filter_string) == 'string') {
                foreach ($data as $key => $row) {
                    $bachei= false;
                    foreach ($row as $cel=> $value) {
                        if (strpos($value, $filter_string) !== false) {
                            $bachei =true;
                            break;
                        }
                    }
                    if ($bachei) {
                        array_push($aux, $row);
                    }
                }
            } else {
                foreach ($data as $row) {
                    foreach ($filter_string as $range => $value) {
                        if (strpos($range, ':') !== false) {
                            $pcells = explode(":", $range);
                            $cell1 = $pcells[0];
                            $cell2 = $pcells[1];
                        } else {
                            $cell1 = $cell2 = $range;
                        }
                        $bachei = false;
                        foreach ($row as $cell => $value_cell) {
                            if ($cell >= $cell1 and $cell <= $cell2) {
                                if (strpos($value_cell,$value) !== false) {
                                    $bachei = true;
                                    break;
                                }
                            }
                        }    
                        if ($bachei) {
                            array_push ($aux, $row);
                        }
                    }
                }
            }
            $data = $aux;
            return $data;
        } else {
            return $data;
        }

    } 

    /*
        metodo: getCellAddress
        parametros: nrow, ncol
        retorna o endereço relativo da celula
    */

    static function getCellAddress($nrow, $ncol) {
        $vetor = array ("-1" => "A", "0" => "A", "1" => "B", "2" => "C", "3" => "D", "4" => "E", "5" => "F",
            "6" => "G", "7" => "H", "8" => "I", "9" => "J", "10" => "K", "11" => "L", "12" => "M",
            "13" => "N", "14" => "O", "15" => "P", "16" => "Q", "17" => "R", "18" => "S", "19" => "T",
            "20" => "U", "21" => "V", "22" => "W", "23" => "X", "24" => "Y", "25" => "Z");
        $npos = intval($ncol / 26) -1;
        if ($npos >= 0) {
            $nrest = intval($ncol % 26);
            $npos = strval($npos);
            $cletter = $vetor[$npos] . $vetor[$nrest];
        } else {
            $npos = $ncol;
            $cletter = $vetor[$npos];
        }
        $crow = strval($nrow);
        return ($cletter.$crow);
    }

    /*
        metodo: skipCelss
        parametros: vetor, numero de colunas para pular
        retorna as colunas a partir do indice informado
    */
    static function skipCells($vetor, $ncells) {
        if ($ncells > 0) {
            $aux = array();
            foreach ($vetor as $row) {
                $columns = array();
                foreach ($row as $cell=>$value) {
                    $num_cell = self::formatKeys($cell);
                    if ($num_cell <= $ncells) {
                        continue;
                    }
                    $columns[$cell] = $value;
                }
                array_push ($aux, $columns);
            }
            return $aux;
        } else {
            return $vetor;
        }
    }
    /*
        selGroupRows
        Seleciona blocos de linhas de uma planilha dentro de um subgrupo
        exemplo:
        A   
        1-Nota 1
        2-a)................................................................
        3-b)................................................................
        4-c)................................................................
        5-
        6-Nota 2
        7-a)................................................................
        8-b)................................................................
        ler apenas linhas com conteúdos (pula linhas em branco)
        o parametro block, informa qual a coluna e valores que vão ser pesquisados
        exemplo:
        $block = ("A"=>"Nota")

    */

    static function selGroupRows($sheetname, $block, $start_row=0) {
        $result = array();
        if (self::$datasheet !== NULL) {
            if (in_array($sheetname, array_keys(self::$datasheet))) {
                $data = self::$datasheet[$sheetname];
                $vblock = array();
                $aux= array();
                $key_block="";
                $key_ant = "";
                for ($i=0; $i < count($data); $i++) {
                    if ($i >= $start_row) {
                        $row = $data[$i];
                        $bachei = false;
                        foreach ($row as $key => $value) {
                            $letter = preg_replace('/\D+/', '', $key);
                            if (in_array($letter, array_keys($block))) {
                                $value_block = strtolower($block[$letter]);
                                $value = strtolower($value);
                                if (strpos($value, $value_block) !== FAlSE) {
                                    $bachei = true;
                                    $key_block = $value;
                                    break;
                                }
                            }
                        }
                        if ($bachei) {
                            if ($key_ant == "")  { // primeira vez
                                $key_ant = $key_block;
                            } else {
                                $vblock[$key_ant] = $aux;
                                $aux = array();
                                $key_ant = $key_block;
                            }
                            continue;
                        } else {
                            array_push($aux, $row);
                        }
                    }
                }
            }
        }
    }
    /* 
        metodo : getArrayType
    */
    static function getArrayType(){
        $bachei = false;
        foreach (self::$plan_file as $ix =>$value) {
            $aux = strtolower($value);
            $aux2 = self::$file_extesion;
            if ($aux2 === $aux) {
                $bachei=$ix;
                break;
            }
        }
        return $bachei;
    }

    static function getColumnName($cellKey) {
        return preg_replace("/\d+/", "", $cellKey);
    }

    public function get_ColumnName($cellKey) {
        return self::getColumnName($cellKey);
    }

    public function get_CellRow($cellKey) {
        return intval(preg_replace("/\D+/", "", $cellKey));
    }

    public function get_LastRow($data) {
        $nlast_row=0;
        if (count($data) > 0) {
            $row = end($data);
            if (count($row) > 0) {
                $varray_keys = array_keys($row);
                $cell_one = $varray_keys[0];
                $nlast_row = $this->get_CellRow($cell_one);
            }
        }   
        return $nlast_row;
    }

   
    static function getLstColumn($data) {
        if (count($data) > 0) {
            $end_cell = end($data);
            $last_cell = self::getColumnName($end_cell);
        } else {
            $last_cell = "";
        }
        return $last_cell;        
    }
    public function getLastColumn($data) {
        return self::getLstColumn($data);
    }

    public function getColValues($data, $columnName, $firstRow=0, $endRow=NULL) {
        $vet_result=array();
        if (is_null($endRow)) {
            $endRow = count($data);
        }
      
        // pega a última coluna de data
        if (count($data) > 0) {
            $last_cell = self::getFirstRow($data)["last_column"];
           
            $bteste_ok = FALSE;
            if (strlen($last_cell) > strlen($columnName)) {
                $bteste_ok=TRUE;
            } elseif ($last_cell >= $columnName) {
                $bteste_ok=TRUE;
            }
            
            if ($bteste_ok) {
                // pode pegar as colunas da linha indica em firstRow
                foreach ($data as $row => $columns) {
                    $first_cell = array_keys($columns)[0];
                    $nrow = $this->get_CellRow($first_cell);
                    if ($nrow >= $firstRow) {
                        if ($row == $endRow) break;
                        foreach ($columns as $cell => $value) {
                            $cell_name = self::getColumnName($cell);
                            if ($cell_name != $columnName) continue;
                            $vet_result["$cell"]= $value;
                        }
                    }
                }
            }
        }
        return $vet_result;
    }

    /*
        ler os dados da planilha e devolve um json
        $type = tipo de leitura Bollean - ALL ler todas as pastas, FOLDER - false, ler uma pasta específica
        $namefolder = nome da pasta a ser lida (se existir na planilha) quando $type=false ou FOLDER
        $skip_rows = informa quantas linhas serão puladas antes de iniciar a leitura, default=1
        $skip_cells = informa quantas celulas serão puladas a cada linha lida, default =0 
        $filter_string = filtra a leitura com uma string existente em cada linha ou para um vetor de dicionarios contendo a string procurada em cada linha ou
        exemplo: [ {'E': 'teste'}, { 'B3:B10' : 100.00}] => irá buscar em todas as linhas e coluna E a palavra teste, e no intervalo de B3 a B10, o valor 100.00
        O conteúdo pode ser qualquer tipo de dado válido no excell, incluindo fórmulas.
    */

    static function getFirstRow($data=NULL) {
        $vet_result = array();
        if (is_null($data)) {
            return self::$vfirstRow;
        } else {
            if (count($data) > 0) {
                $row = $data[0];
                $array_keys = array_keys($row);

                $end_cell = self::getLstColumn($array_keys);
                $first_cell = $array_keys[0];
                $row_number = preg_replace("/\D+/", "", $first_cell);
                $first_column = self::getColumnName($first_cell);
                $vet_result = array("row_number" => $row_number, "first_column" => $first_column, "last_column" => $end_cell);
            }
        }
        return $vet_result;
    }
    static function getData($type, $namefolder=NULL, $skip_rows=1, $skip_cells=0,$filter_string=NULL) {
        $vsheetnames = self::$spreadsheet->getSheetNames();

        $sheetcount = self::$spreadsheet->getSheetCount();
        
        if ($type == FOLDER) {
            if (in_array($namefolder, $vsheetnames)) {
                $nindex = array_search($namefolder, $vsheetnames);
                $sheet = self::getSheetInfo($nindex);
                $nrows = $sheet->getHighestRow();
                self::$lastletter= $sheet->getHighestColumn();
                $data = $sheet->toArray(null, true, true, true);
                self::$originalData = $data;
                $data = self::skipRows($data, $skip_rows);
                $data = self::skipCells($data, $skip_cells);
                $data = self::FilterData($data, $filter_string);
                self::$numrows = count($data);
                self::$vfirstRow = self::getFirstRow($data);
                $vaux = array("folderName" => $namefolder,
                              "forderInfo" => array('FirstRow' => self::$vfirstRow, 'Lastrow' => self::$numrows, 'Lastcol' => self::$lastletter, 'Data' => $data, 'originalData' => self::$originalData));
                return $vaux;

            } else {
                return NULL;
            }
        } elseif ($type == ALL) {
            $vaux = array();
            for ($i=0; $i < $sheetcount; $i++) {
                $name = $vsheetnames[$i];
                $vfolder = self::getData(FOLDER, $name, $skip_rows, $skip_cells, $filter_string);
                array_push($vaux, $vfolder);
            }
            return $vaux;
        } else {
            return NULL;
        }
    }

    public function getNumRows() {
        return self::$numrows;
    }

    public function get_FirstRow() {
        return self::getFirstRow();
    }

    public function getLastLetter() {
        return self::$lastletter;
    }

    public function readSheets ($type=ALL, $namefolder=NULL, $skip_rows=1, $skip_cells=0, $filter_string=NULL) {
        self::$datasheet = self::getData($type, $namefolder, $skip_rows, $skip_cells, $filter_string);
        return self::$datasheet;
    }
    /*
        seleciona o folder da planilha e coloca ela como ativada para leitura e escrita
        retorna objeto da planilha ativa.
    */

    static function selectFolder($name) {
        $vsheetnames = self::$spreadsheet->getSheetNames();
        
        if (in_array($name, $vsheetnames) !== false) {
            $npos = array_search($name, $vsheetnames);
            return self::$spreadsheet->setActiveSheetIndex($npos);
        } else {
            return NULL;
        }
    }

    /*
     *
     * StoreCell - armazena valores na celula válida
     * parametros: nome da pasta
     * endereço da celula no formato excel
     * valor
    */

    public function StoreCell($sheetname, $cellAddress, $value) {
        $objFolder = self::selectFolder($sheetname);
        if ($objFolder !== NULL) {
            $objFolder->setCellValue($cellAddress, $value);
        }
    }

        
    /*
     * Metodo: colorir fonte de celulas
     * celladress é um endereço ou um range de celulas
     */

    public function setFontColor($sheetname, $cellAddress, $color) {
        $objFolder = self::selectFolder($sheetname);
        if ($objFolder !== NULL) {
            $objFolder->getStyle($cellAddress)->getFont()->getColor()->setARGB($color);
        }
    }
    /*
     * Metodo: colorir celulas 
     * celladress é um endereço ou um range de celulas
     */


    public function setCellColor($sheetname, $cellAddress, $color) {
        $objFolder = self::selectFolder($sheetname);
        if ($objFolder !== NULL) {
            $objFolder->getStyle($cellAddress)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($color);
        }
    }

    /*
     * Metodo: formatar celulas com campos numéricos
     * celladress é um endereço ou um range de celulas
     */


    public function setNumberFormat($sheetname, $cellAddress) {
        $objFolder = self::selectFolder($sheetname);
        if ($objFolder !== NULL) {
            $objFolder->getStyle($cellAddress)->getNumberFormat()->setFormatCode(PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        }
    }

    /*
     * Metodo: Salvar planilha corrente
     * 
     */

     public function SaveSheet($planame) {
        self::$writer->save($planame);
     }


    /*
        get column values
    */

    public function getColumnValues($sheetname, $columnLetter) {
        $result = array();
        if (self::$datasheet !== NULL) {
            $data = self::$datasheet[$sheetname];

        }
    }
    /*
     *
     * getCellvalue - retorna o valor de uma celula
     * parametros: nome da pasta, endereço da celula no formato excel
     * retorn um vetor com as celulas e valores dos endereços informados, se for celula unica retorna um vetor com uma posição
    */

    public function getCellvalue($sheetname, $cellAddress) {
        $result = array();

        if (self::$datasheet !== NULL) {
            if (in_array ($sheetname, array_keys(self::$datasheet))) {
                $data = self::$datasheet[$sheetname];
                // verifica se cellAddress é um endereço único ou um range de celulas
                $npos= strpos($cellAddress, ":");
                if ($npos !== false) {
                    $vaddress = explode(":", $cellAddress);
                    $cell1 = $vaddress[0];
                    $cell2 = $vaddress[1];
                    $brange=true;
                } else {
                    $cell1 = $cellAddress;
                    $cell2 = $cell1;
                    $brange = false;
                }
                foreach ($data as $row) {
                    $bachei = false;
                    foreach ($row as $cell => $value) {
                        if ($brange === false) {
                            if ($cell == $cell1) {
                                $result[$cell] = $value;
                                $bachei=true;
                                break;
                            }
                        } else {
                            if ($cell >= $cell1 and $cell <= $cell2) {
                                $result[$cell] = $value;
                            }
                        }
                    }
                    if ($bachei) {
                        break;
                    }
                }
            }
        }
        return $result; // se não tiver valores retorna um vetor nulo.
    }

    static function getExtension() {
        if (self::$filename !== NULL) {
            $filename = self::$filename;
            $var = explode(".", $filename);
            $extension = end($var);
            return $extension;
        } else {
            return NULL;
        }
    }
}