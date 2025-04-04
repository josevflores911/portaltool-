<?php
    define ("X", 1);
    define ("Y", 2);
    define ("BOTH", "both");
    define ("ALL", "both");

    class cls_graphics {
        static $vtype= array("pie", "hbar", "vbar", "line", "area", "multiline","donut");
        static $Type="hbar";
        static $label_eixoX = array();
        static $label_eixoY = array();
        static $show_labelX = TRUE;
        static $show_labelY = TRUE;
        static $show_title = TRUE;
        static $view_width="400";
        static $view_height="400";
        static $max_width="400";
        static $max_height="400";
        static $min_value = 0;
        static $max_value= 0;
        static $nbase=0;
        static $num_values=0;
        static $raio=300;
        static $transparency="0.4";
        static $pointerX=200;
        static $pointerY=200;
        static $export=TRUE;
        static $typeExport = array("Pdf", "Excel", "Csv", "Txt", "Json");
        static $bexpand=FALSE;
        static $drilldown=FALSE;
        static $vet_dridown = array();
        static $vTitle= "";
        static $linegap = 0;
        static $interval=0;
        static $vColor = array();
        static $vColor_num = array();
        static $btitulo = TRUE;
        static $blabel_X = TRUE;
        static $blabel_Y = TRUE;
        static $max_label_X = 0;
        static $max_label_Y = 0;

        static $bgcolor="transparent";
        static $stroke_axis_color = "cyan";
        static $stroke_axis_width = "1";
        static $stroke_bar_color = "rgba(230,230,230,0.35)";
        static $stroke_bar_width = "0.7";
        static $stroke_line_color = "rgba(230,230,230,0.35)";
        static $stroke_line_width = "0.7";
        static $stroke_line_weigth = "1.7";
        static $stroke_line_point = "rgba(45,50,150,0.50)";
        static $fill_rect = "rgba(129,120,150,0.35)";
        static $rect_width = "0.7";
        static $padding="0 10px 10px 0";
        static $margin="4px 4px 4px 4px";
        static $textColor = "white";
        static $fontFamily = "Verdana";
        static $fontSize = "9pt";
        static $Data = array();
        
        static $ndiv = 0;

        function __construct ($data, $type, $labels = NULL, $colors=NULL, $title=NULL,$Dimensions=NULL ) {
            $this->setType($type);
            $this->setColor($data,$colors);
            self::setData($data);
            $this->setLabel($labels);
            $this->setTitle($title);
            $this->setDimensions($Dimensions);
        }

        public function setDimensions($Dimensions) {
            if (! is_null($Dimensions)) {
                if (gettype($Dimensions) == 'array') {
                    $vetor = $Dimensions;
                    if (array_key_exists('view_width', $vetor)) {
                        self::$view_width = $vetor['view_width'];   
                    }
                    if (array_key_exists('view_height', $vetor)) {
                        self::$view_height = $vetor['view_height'];   
                    }
                    if (array_key_exists('max_width', $vetor)) {
                        self::$max_width = $vetor['max_width'];
                    }
                    if (array_key_exists('max_height', $vetor)) {
                        self::$max_height = $vetor['max_height'];
                    }
                    if (array_key_exists('stroke_axis_color', $vetor)) {
                        self::$stroke_axis_color = $vetor['stroke_axis_color'];
                    }
                    if (array_key_exists('stroke_bar_color', $vetor)) {
                        self::$stroke_bar_color = $vetor['stroke_bar_color'];
                    }
                    if (array_key_exists('stroke_bar_width', $vetor)) {
                        self::$stroke_bar_width = $vetor['stroke_bar_width'];
                    }
                    if (array_key_exists('stroke_line_color', $vetor)) {
                        self::$stroke_line_color = $vetor['stroke_line_color'];
                    }
                    if (array_key_exists('stroke_line_width', $vetor)) {
                        self::$stroke_line_width = $vetor['stroke_line_width'];
                    }
                    if (array_key_exists('stroke_line_point', $vetor)) {
                        self::$stroke_line_point = $vetor['stroke_line_point'];
                    }
                    if (array_key_exists('fill_rect', $vetor)) {
                        self::$fill_rect = $vetor['fill_rect'];
                    }
                    if (array_key_exists('rect_width', $vetor)) {
                        self::$rect_width = $vetor['rect_width'];
                    }
                    if (array_key_exists('textColor', $vetor)) {
                        self::$textColor = $vetor['textColor'];
                    }
                }
                

            }
        }
        public function setTransparency($newValue) {
            if (!is_null($newValue)) {
                self::$transparency = $newValue;
            }
        }
        public function getTransparency() {
            return self::$transparency;
        }

        public function setGridLine($vetColor) {
            if (gettype($vetColor) == 'array') {
                if (array_key_exists('gridColor',$vetColor)) {
                    $ccolor = $vetColor['gridColor'];
                    if (gettype($ccolor) == 'string') {
                        $bcolor = true;
                    } elseif (gettype($ccolor) == 'array') {
                        if (count($ccolor) >= 3 and count($ccolor) <= 4) {
                            $bcolor=true;
                        } else {
                            if (count($ccolor) == 3) {
                                $r = $ccolor[0];
                                $g = $ccolor[1];
                                $b = $ccolor[2];
                            }
                        }
                    }
                    self::$stroke_line_color = $vetColor['gridColor'];
                }

                if (array_key_exists('width', $vetColor)) {
                    self::$stroke_line_width = $vetColor['width'];
                }
            }
        }
        public function setTitle($title) {
            if (! is_null($title)) {
                if (gettype($title) == "array") {
                    if (count($title) == 2) {
                        if (array_key_exists('title', $title)) {
                            $ctitle = $title['title'];
                        } else {
                            $ctitle = $title[0];
                        }
                        if (array_key_exists('color', $title)) {
                            $color = $title['color'];
                        } else {
                            $color = $title[1];
                        }
                    } elseif (count($title) == 1) {
                        if (array_key_exists('title', $title)) {
                            $ctitle = $title['title'];
                        } else {
                            $ctitle = $title[0];
                        }
                        $color = 'white';
                    } else {
                        $ctitle='';
                        $color = '';
                    }
                } elseif (gettype($title) == 'string') {
                    $color = "white";
                    $ctitle= $title;
                } else {
                    $ctitle='';
                    $color = '';
                }
            } else {
                $ctitle='';
                $color = '';
            }
        
            self::$vTitle = array('title'=> $ctitle, 'color'=> $color);
        }

        public function hasTitle() {
            $title = self::$vTitle['title'];
            return strlen($title) > 0;
        }

        public function getTitle() {
            if (! empty(self::$vTitle)) {
                return self::$vTitle;
            } else {
                return NULL;
            }
        }
     
        public function getColor() {
            $r = rand(10,255);
            $g = rand(10,255);
            $b = rand(10,255);
            
            $aux = array($r, $g, $b);
            $color = "rgba($r,$g,$b," . self::$transparency . ")";
            preg_match_all("/([\d.]+)/", $color, $matches);
            $hex = sprintf(
                "#%02X%02X%02X%02X",
                $matches[1][2], // blue
                $matches[1][1], // green
                $matches[1][0], // red
                $matches[1][3] * 255, // adjusted opacity
            );
            $color = $hex;
            if (array_search($color, self::$vColor) == FALSE) {
                array_push(self::$vColor, $color);
                return $color;
            } else {
                return self::getColor();
            }
        }

        public function setColor($data, $colors=NULL) {
            if (! is_null($colors)) {
                if (gettype($colors) == 'array') {
                    self::$vColor = $colors;
                } elseif (gettype($colors) == 'string') {
                    $colors = explode(",", $colors);
                    self::$vColor = $colors;
                }
                if (count(self::$vColor) != count($data)) {
                    if (count(self::$vColor) < count($data)) {
                        $ndif = count($data) - count(self::$vColor);
                        $ni=0;
                        while ($ni < $ndif) {
                            $color = self::getColor();
                            $ni+=1;
                        }
                    } elseif (count(self::$vColor) > count($data)) {
                        $ndif = count(self::$vColor) - count($data);
                        $ni=0;
                        while ($ni < $ndif) {
                            array_pop(self::$vColor);
                            $ni+=1;
                        }
                    }
                }
            } else {
                // calculo do total
                $nlen = count($data);
                $ni = 0;
                self::$vColor = array();
                while ($ni < $nlen) {
                    $color = self::getColor();
                    $ni +=1;
                }
            }
         
        }

        static function StringtoFloat($value) {
            
            if (gettype($value) == "string") {
                // verifica se todos os campos são numeros, ponto ou vírgula
                $aux = preg_replace('/(([0-9.]+)(\,\d+){0,1})/', '', $value);
                if (empty($aux)) {
                    $npoints = substr_count($value, ".");
                    if ($npoints > 1) {
                        $value = preg_replace('/(\.)+/', '', $value);

                    } elseif ($npoints == 1) {
                        return floatval($value);
                    } 
                    if (stripos($value, ',') > 0) {
                        $value = str_replace(",", ".", $value);
                    }
                    return floatval($value);

                } else {
                    return 0;
                }
            } elseif (gettype($value) == "integer") {
                $value = strval($value) . ".00";
                return floatval($value);
            } elseif (gettype($value) == "decimal") {
                return $value;
            } elseif (gettype($value) == "float") {
                return $value;
            } else {
                return 0;
            }
        }

        static function getMinValue($varray) {
            $minimo =0;
            // remove todos os valores zeros e nulos
            $aux = array();
            $nvez = 0;
            foreach ($varray as $value) {
               if (is_null($value) or empty($value)) continue;
               $value = strval($value); 
               $npoints = substr_count($value,".");
               if ($npoints > 1) {
                    $ix = stripos($value, ",");
                    if ($ix > 0) {
                        $vaux = explode(",", $value);
                        $inteiro = $aux[0];
                        if (count($vaux) > 1) {
                            $cent = $vaux[1];
                        } else {
                            $cent = "00";
                        }
                        $inteiro = preg_replace("/\D+/", "", $value);
                        $value = $inteiro . "." . $cent;
                    } else {
                        $inteiro = preg_replace("/\D+/", "", $value);
                        $value = $inteiro . ".00";
                    }
               } elseif ($npoints == 1) {
                    $ix = stripos($value, ",");
                    if ($ix > 0) {
                        $vaux = explode(",", $value);
                        $inteiro = $aux[0];
                        if (count($vaux) > 1) {
                            $cent = $vaux[1];
                        } else {
                            $cent = "00";
                        }
                        $inteiro = preg_replace("/\D+/", "", $value);
                        $value = $inteiro . "." . $cent;
                    } else {
                        $vaux = explode(".", $value);
                        $inteiro = $vaux[0];
                        $cent = $vaux[1];
                        if (strlen($cent) > 2) {
                            $value = $inteiro . $cent . ".00";
                        } else {
                            $value = $inteiro . "." . $cent;
                        }
                    }
                } else {
                    $ix = stripos($value, ",");
                    if ($ix > 0) {
                        $vaux = explode(",", $value);
                        $inteiro = $aux[0];
                        if (count($vaux) > 1) {
                            $cent = $vaux[1];
                        } else {
                            $cent = "00";
                        }
                        $value = $inteiro . "." . $cent;
                    } else {
                        $value .= ".00";
                    }
                }
               $value = floatval($value);
               if ($value == 0.0) continue;
               if ($nvez == 0) {
                    $nvez = 1;
                    $minimo = $value;
               }
               array_push($aux,$value);
            }
            foreach ($aux as $value) {
                if ($value < $minimo) $minimo = $value;
            }
            return $minimo;
        }
        static function setData($data) {
            if (!is_null($data)) {
                if (gettype($data) == 'array') {
                    self::$Data = $data;
                } elseif (gettype($data) == 'string') {
                    $auxData = explode(",", $data);

                    $ntotal = 0.0;
                    foreach ($auxData as $ni=>$value) {
                        // check se é float ou mascarado de edicao
                        $nvalue = self::StringtoFloat($value);
                        $auxData[$ni] = $nvalue;
                        $ntotal += $nvalue;
                    }
                    $vperc = array();
                    foreach ($auxData as $value) {
                        $nperc = round($value * 100/$ntotal, 2);
                        array_push($vperc, $nperc);
                    }
                    $data = array();
                    foreach ($auxData as $ni => $value) {
                        $perc = $vperc[$ni];
                        $aux = array('perc' => $perc, 'value' => $value);
                        array_push($data, $aux);
                    }
                    self::$Data = $data;
                }
                self::$num_values = count(self::$Data);
                $lvalues = array_column(self::$Data, 'value');
                $lperc = array_column(self::$Data, 'perc');
                self::$max_value = max($lvalues);
                self::$min_value = self::getMinValue($lvalues);
                if (count(self::$vColor) == 0) {

                    self::setColor($lvalues);
                }
                self::$Data = array();
                foreach ($lvalues as $ni => $value) {
                    $perc = $lperc[$ni];
                    if ($ni < count(self::$vColor)) {
                        $color = self::$vColor[$ni];
                    } else {
                        $nrand = rand(0, count(self::$vColor)-1);
                        $color = self::$vColor[$nrand];
                    }
                    $aux = array('perc' => $perc, 'value' => $value, 'color' => $color);
                    array_push(self::$Data, $aux);
                }
            }
        }

        public function configData($data,$color=NULL) {
            if (getType($data) == 'string') {
                self::setData($data);
            } else {
                $this->setColor($data,$color);
                $vperc = array_column($data,"perc");
                if (empty($vperc) or is_null($vperc)) {
                    $ntotal = 0;
                    foreach ($data as $value) {
                        $ntotal += floatval($value);
                    }
                    $vperc = array();
                    foreach ($data as $value) {
                        $value = floatval($value);
                        $perc = round($value*100/$ntotal, 2);
                        array_push($vperc, $perc);
                    }
                }
                $vvalues = array_column($data, "value");
                if (empty($vvalues) or is_null($vvalues)) {
                    $vvalues = array();
                    foreach ($data as $value) {
                        $value = floatval($value);
                        array_push($vvalues, $perc);
                    }
                }
                $vet = self::$vColor;
                self::$Data = array();
                foreach ($vet as $nrow => $color) {
                    $value = $vvalues[$nrow];
                    $perc = $vperc[$nrow];
                    $aux = array("perc" => $perc, "value" => $value, "color" => $color);
                    array_push(self::$Data, $aux);
                }
            }
        }
        public function setLabel_X ($label_x) {
            if (!is_null($label_x)) {
                if (gettype($label_x) == 'array') {
                    self::$label_eixoX = $label_x;
                    self::$blabel_X = TRUE;
                } elseif (gettype($label_x) == 'string') {
                    self::$label_eixoX = explode(",", $label_x);
                    self::$blabel_X = TRUE;
                }    
            } else {
                self::$blabel_X = FALSE;
            }
        }
        public function setLabel_Y ($label_y) {
            if (!is_null($label_y)) {
                self::$label_eixoY = $label_y;
            } else {
                self::$blabel_Y = FALSE;
            }
        }

        public function setParamTitle ($ctitle, $FontName='Verdana', $FontSize='9px', $FontColor='white', $FontWeight='bold', $fontStyle=NULL) {
            if (is_null($fontStyle)) {
                self::$vTitle = array($ctitle, $FontColor, $FontSize, $FontWeight);
            } else {
                self::$vTitle = array($ctitle, $FontColor, $FontSize, $FontWeight,$fontStyle);
            }
        }

        public function setViewWidth ($nwidth) {
            if (!empty($nwidth) and $nwidth > 0) {
                self::$view_width = $nwidth;    
            }
        }

        public function setViewHeight($nheight) {
            if (!empty($nheight) and $nheight > 0) {
                self::$view_height = $nheight;
            }
        }

        public function setMaxWidth($nwidth) {
            if (!empty($nwidth) and $nwidth > 0) {
                self::$max_width = $nwidth;
            }
        }
        public function getMaxWidth() {
            return self::$max_width;
        }

        public function setMaxHeight($nheight) {
            self::$max_height = $nheight;
        }

        public function setStrokeAxisColor($ccolor) {
            if (!is_null($ccolor)) {
                self::$stroke_axis_color = $ccolor;
            }
        }

        public function setStrokeBarColor($ccolor) {
            if (!is_null($ccolor)) {
                self::$stroke_bar_color = $ccolor;
            }
        }

        public function setStrokeBarWidth($nwidth) {
            if (!empty($nwidth) and $nwidth > 0) {
                self::$stroke_bar_width = $nwidth;
            }
        }

        public function setStrokeLineColor($ccolor) {
            if (!is_null($ccolor)) {
                self::$stroke_line_color = $ccolor;
            }
        }
        public function setStrokeLineWeight($nwidth) {
            if (!empty($nwidth) and $nwidth > 0) {
                self::$stroke_line_weigth = $nwidth;
            }
        }

        public function setStrokeLineWidth($nwidth) {
            if (!empty($nwidth) and $nwidth > 0) {
                self::$stroke_line_width = $nwidth;
            }
        }

        public function setFillRect($ccolor) {
            if (!is_null($ccolor)) {
                self::$fill_rect = $ccolor;
            }
        }

        public function setrect_width($nwidth) {
            if (!empty($nwidth) and $nwidth > 0) {
                self::$rect_width = $nwidth;
            }
        }

        public function setTextColor($ccolor) {
            if (!is_null($ccolor)) {
                self::$textColor = $ccolor;
            }
        }

        public function setFontFamily($fontName) {
            self::$fontFamily = $fontName;
        }

        public function setFontSize($nwidth) {
            if (!empty($nwidth) and $nwidth > 0) {
                self::$fontSize = $nwidth;
            }
        }

        static function getMaxLabel($vetor) {
            $nlenmax = 0;
            foreach ($vetor as $element) {
                if (gettype($element) == 'string') {
                    $length = strlen($element);
                    if ($length > $nlenmax) {
                        $nlenmax = $length;
                    }
                } elseif (gettype($element) == "integer" or gettype($element) == "float" or gettype($element) == "double") {
                    $aux = strval($element);
                    $length = strlen($aux);
                    if ($length > $nlenmax) {
                        $nlenmax = $length;
                    }
                }
            }
            return $nlenmax;
        }
        public function setLabel($labels) {
            self::$label_eixoX = array();
            self::$blabel_X= false;
            self::$max_label_X = 0;
            self::$label_eixoY = array();
            self::$blabel_Y= false;
            self::$max_label_Y = 0;
            $bnotarray=false;
         
            if (! is_null($labels)) {
              
                if (gettype($labels) == 'array') {
                    if (array_key_exists('labels_x', $labels)) {
                        self::$label_eixoX = $labels['labels_x'];

                        if (gettype(self::$label_eixoX) == 'string') {
                            $vaux = explode(",", self::$label_eixoX);
                            self::$label_eixoX = $vaux;
                        } 

                        if (gettype(self::$label_eixoX) == 'array') {
                            self::$blabel_X = count(self::$label_eixoX) > 0;
                            if (self::$blabel_X) self::$max_label_X = self::getMaxLabel(self::$label_eixoX);
                        }
                    } else {
                        $bnotarray=true;
                     
                    }
                    if (array_key_exists('labels_y', $labels)) {
                        self::$label_eixoY = $labels['labels_y'];
                        if (gettype(self::$label_eixoY) == 'string') {
                            $vaux = explode(",", self::$label_eixoY);
                            self::$label_eixoY = $vaux;
                        } 
                        if (gettype(self::$label_eixoY) == 'array') {
                            self::$blabel_Y = count(self::$label_eixoY) > 0;
                            if (self::$blabel_Y) self::$max_label_Y = self::getMaxLabel(self::$label_eixoY);
                        }
                    } else {
                        $bnotarray=true;
                    }
                    
                    if ($bnotarray) {
                        if (count($labels) > 0) {
                           
                            if (self::$Type == 'vbar' or self::$Type == 'pie' ) {
                                self::$label_eixoY = $labels;
                                self::$blabel_Y = true;
                                self::$max_label_Y =self::getMaxLabel($labels);
                            } elseif (self::$Type == 'hbar' or self::$Type == "line") {
                                self::$label_eixoX = $labels;
                                self::$blabel_X = true;
                                self::$max_label_X =self::getMaxLabel($labels);
                            } else {
                                self::$label_eixoY = $labels;
                                self::$blabel_Y = true;
                                self::$max_label_Y =self::getMaxLabel($labels);
                            }
                        }
                    }
                } else {
                    if (stripos($labels, ",") > 0) {
                        $vaux = explode(",", $labels);
                        $this->setLabel($vaux);
                    }
                }

                
            }
        }

        public function getlabel() {
            $labels = array();
            $labels['labels_x'] = array();
            $labels['labels_y'] = array();
            if (self::$blabel_X) {
                $labels['labels_x'] = self::$label_eixoX;
            }
            if (self::$blabel_Y) {

                $labels['labels_y'] = self::$label_eixoY;
            }
            return $labels;
        }

        public function hasLabel_X() {
            return self::$blabel_X;
        }
        
        public function hasLabel_Y() {
            return self::$blabel_Y;
        }

        public function setType($type) {
            if (is_null($type)) $type = '$vbar';
            if (in_array($type, self::$vtype)) {
                self::$Type = $type;
            }
        }

        public function getType() {
            return self::$Type;
        }

        // desenhar gráficos

        static function gettmpClass($length) {
            $key = '';
            $keys = array_merge(range(0, 9), range('a', 'z'));
            for ($i = 0; $i < $length; $i++) {
                $key .= $keys[array_rand($keys)];
            }
            return $key;
        }
        public function draw_pie() {
            $cline="";
            $view_width = self::$max_width/10;
            $view_height= self::$max_height/10;
            $raio = round($view_width /2,2)-6;
            $mw = round($view_width /2,2) -6;
            $mh =round( $view_height /2,2);
            if (self::hasLabel_Y()) {
                $maxvalue = max(array_column(self::$Data,'value'));
                $tam = strlen(number_format($maxvalue,2,',','.'));
                $tam /=2;
                $tam += $raio;
            } else {
                $tam = 0;
            }
            $name_class='pie_' . self::gettmpClass(12);
            $svg_width = $view_width + $tam;
            $cline.= "<svg xmlns='http://www.w3.org/2000/svg' viewbox='0 0 $svg_width $view_height' class='$name_class'>";
            $cline.= "<style  type='text/css'>"; 
            $cline .= "svg.$name_class {
                border: 2px dashed block;
                width: 100%;
                height:100%
            }";
            $cline .= ".line {
                width=100%;
                border: thin dotted #cca5;
            }";
            $cline .= ".perc {
                font-family: Verdana;
                font-size: Calc($view_width * 0.16%);
                color:white;
            }";

            $cline .= ".title {
                font-family: Verdana;
                font-size: Calc($view_height * 0.19%);
                color:white;
            }";
            $cline .= ".cls-labels {
                background-color: rgba(25,25,25,0.5);
            }";
          
            $cline.=".titulo_pie {
                display:block;
                position:fixed;
                font-size: Calc($view_width * 0.35%);
                font-family:Verdana;
                font-weight:bold;
                text-align:center;
                }";

            $cline.= "</style>";
            $rotate = 0;
            $ntimes = 0;
            $ny = 1.45;
            $vtitle = $this->getTitle();
            if (! is_null($vtitle)) {
                $title = $vtitle["title"];
                $colortitle = $vtitle["color"];
            } else {
                $title = "";
                $colortitle="";
            }

            if (strlen($title) > 0) {
                $nlentitulo = strlen($title) /2;
                $tit_x = $raio/2 + $nlentitulo/2;
                $tit_y = 3.6;
                $cline .= "<g class='line'>";
                $cline .= "  <text x='$tit_x' y='$tit_y' fill='$colortitle' class='titulo_pie'>$title</text>"; 
                $cline .= "</g>";
            }
            if (self::hasLabel_Y()) {
                $data_aux = self::$label_eixoY;
                $vcolor = array_column(self::$Data, "color");
             
                $ny = $view_height/3 - 2.6; 
                $cline .= "<g class='cls-labels'>";
         
                $ty = $ny+0.85;
                $maxvalue = max(array_column(self::$Data,'value'));
                $tam = strlen(number_format($maxvalue,2,',','.'));
                $nx = $raio * 2 + 0.5;

                foreach ($data_aux as $nrow=> $label) {
                    $color = $vcolor[$nrow];

                    $cline .= "<rect x='$nx' y='$ny' width='0.95' height='0.95' fill='$color' />";
                    $tx = $nx + 2.5;
                    $cline .= "<text x='$tx' y='$ty' class='title' fill='white'>$label</text>";
                        
                    $ny += 1.50;
                    $ty += 1.50;
                }
                $cline .= "</g>";
            }

            $cline .= "<g>";
            foreach (self::$Data as $key => $row) {
                $perc = $row["perc"];
                $value = $row["value"];
                $color = $row["color"];
                $dash_radio = $raio /2;
                $stroke_dash = round(2 * pi() * $dash_radio,2);
                $perc_dash = round($perc * ($stroke_dash / 100),2);
                $value = number_format(floatval($value),2, ',', '.');
                if (strlen($value) > 8) {
                    # xxx.xxx,xx
                    $vaux = explode(".", $value);
                    $len = count($vaux);
                    if ($len > 1) {
                        if ($len == 2) {
                            $term = "mil";
                        } else {
                            $term = "milh";
                        }
                        $num = "";
                        for ($i = 0; $i < $len; $i++) {
                            $num .= $vaux[$i];
                        }
                        $num = floatval(str_replace(",", ".", $num));
                        if ($len == 2) {
                            $num = round($num /1000,2);
                        } else {
                            $num = round($num /1000000,2);
                        }
                        $value = number_format($num, 2, ",", ".");
                    } else {
                        $term = "";
                    }
                    $value .= " $term";
                    $value = trim($value);
                }
                $cline .= "<circle r='$dash_radio' cx='$mw' cy='$mh' fill='transparent' fill-opacity='". self::$transparency."' ";
                $cline .= "        stroke='$color' ";
                $cline .= "        stroke-width='$raio' ";
                $cline .= "        stroke-dasharray='$perc_dash $stroke_dash'";
                $cline .= "        transform='rotate($rotate, $mw,$mh) '>";
                $cline .= "<title class='perc' fill='black'><center>$perc%&#10;$value</center></title>";
                $cline .= "</circle>";
                $rotate += round($perc*360/100,2);            
            } 
            $cline .= "</g>";
    
            $cline.= "</svg>"; 
            return $cline;
        }

        public function draw_line() {
         
            $vet = self::$Data;
            $max = self::$max_value;
            $min = self::$min_value;
            $nlen = count($vet);
            if (self::$blabel_X) {
                $label_x = self::$label_eixoX;
                $nmax_label = 0;
                foreach ($label_x as $label) {
                    if (strlen($label) > $nmax_label) $nmax_label = strlen($label);
                }
                $hlabel = 12 * $nmax_label * cos(45);
            } else {
                $nmax_label = 0;
                $hlabel = 0;
                $label_x = array();
            }

            $vtitle = $this->getTitle();
            if (! is_null($vtitle)) {
                $title = $vtitle["title"];
                $colortitle = $vtitle["color"];
                if (empty($colortitle)) $colortitle= "white";
            } else {
                $title = "";
                $colortitle="";
            }
          
            if (strlen($title) > 0) {
                $nlentitulo = strlen($title);
                $htitulo = 17.35;
            } else {
                $htitulo = 0;
                $nlentitulo = 0;
            }

            $nlenmax = strlen(ceil($max));
            $potencia = pow(10, $nlenmax-1);
            $nlenmin = strlen(ceil($min));
            $pmin = pow(10, $nlenmin-1);
            $dif = ($pmin * 100) / $potencia;
            
            if ($dif < 10) $dif = 10;
            $inter = $potencia /$dif;
            $gap = 16 *(1+ ($nlenmax -1));
    
            $div = round($max/$potencia,2) * $inter;
            $ndiv = round($max/$div,0);
    
            $nview_width = self::$view_width;
            $nview_height = self::$view_height;
            $nheight_util = $nview_height + $hlabel + $htitulo;
            
    
            $fh = round($nview_height / $ndiv,3);
            $max_label = ceil(strlen(max($label_x)) * cos(45) * 16)+1;
    
            $nview_height = ($fh * ($ndiv-1))+ $max_label;
            $nmax_value = $div * $ndiv;
            $nmax_height = $fh * $ndiv;
            
            $nbarras = count($vet);
            
            $spcbar = $nbarras +1;
            $ntot = $nbarras + $spcbar;
            $nboxw = ceil($nview_width / $ntot);
            $nspace = $nboxw/2;
            $conjunto = ($nboxw+$nspace);
    
            $max_width = ($conjunto * ($nbarras + $gap / $conjunto)+$nspace) +16;
            $name_class="line_" . self::gettmpClass(15);
            $cline="";
            $cline.= "<svg xmlns='http://www.w3.org/2000/svg' viewbox='0 0 $max_width $nheight_util' class='$name_class' >";
            $cline.= "<style  type='text/css'>"; 
            $cline.= ".labels {";
            $cline.= "  display: flex;
                        position: relative;
                        float:right; 
                        left: 0px !important;
                        transform: scaleY(-1);
                        color: #white;
                        font-size: 0.45rem !important;
                        font-family: Verdana;
                    }";
            $cline.= ".labels_axisx {";
                $cline.= "  display: flex;
                            position: relative;
                            float:right; 
                            left: 0px !important;
                            transform: scaleY(-1);
                            color: #white;
                            font-size: 0.32rem !important;
                            font-family: Verdana;
                        }";
            $cline .= ".line {
                        width=100%;
                        border: thin dotted #cca5;
                    }
                    .linesep {
                        width=100%;
                        border: thin solid #cac0cd;
                    }";
            $cline .= "title {
                        font-size: 10px;
                        font-family: Verdana;
                        border-radius: 5px;
                    }";
    
            $cline.= "svg.$name_class {
                    transform: scaleY(-1);
                }";
            $cline.=".titulo {
                    display:block;
                    position:fixed;
                    botton: 0;
                    font-size: 8pt !important;
                    font-family:Verdana;
                    font-weight:bold;
                    text-align:center;
                    transform: scaleY(-1);
                    }";
          
            $cline.=".label_axisx {
                display:block;
                position:fixed;
                botton: 0;
                font-size: 8pt !important;
                font-family:Verdana;
                font-weight:bold;
                text-align:center;
                transform: rotate(-45);
                transform: scaleY(-1);
                }";
            $cline.= "</style>";
      
            $ni = 0;
            $x= $gap;
           
            $last_y = $nheight_util;
            $ny = $hlabel;

            if ($nlentitulo > 0) {
                $nlentitulo /= 2;
                $tit_x = ($x + $max_width-($nlentitulo+$gap))/3;
                $tit_y = ($nheight_util-8) *-1;
                $cline .= "<g class='line'>";
                $cline .= "  <text x='$tit_x' y='$tit_y' fill='white' class='titulo'>$title</text>"; 
                $cline .= "</g>";
                
            }
                       
            $last_x = $max_width + $conjunto -16;
            $cline.= "<g fill='transparent' stroke='cyan' stroke-width='1' class='line'>";
            $cline.= "  <line x1='$x' x2='$x' y1='$ny' y2='$last_y' fill-opactiy='0.4'></line>";
            $cline.= "  <line x1='$x' x2='$last_x' y1='$ny' y2='$ny' fill-opacity='0.4'></line>";
            $cline.= "</g>";

            $ni = 0;
            $cline.= "<g fill='transparent' class='linesep' stroke='rgba(130,110,130,0.5)' stroke-width='0.7' fill-opacity='0.35'>";
            $p = 0;
            $tx = $gap * 90/100;
            $ty = ($ny + $fh) *-1;
            
            while ($ni < $ndiv) {
                $ny += $fh;
                $cline.= "<line x1='$x' x2='$max_width' y1='$ny' y2='$ny' fill-opacity='0.4' />";
                $ni+=1;
            }
            $cline.= "</g>";
            $ni = 0;
            $cline.= "<g class='labels'>";
            while ($ni < $ndiv) {
                $p += $div;
                $value = number_format($p,0,',','.');
                $cline.= "   <text x='$tx' y='$ty' text-anchor='end' fill='white'>$value</text>";
                $ty -= $fh;
                $ni += 1;
            }
            $cline .= "</g>";
        
            // calculando as distancias entre os pontos.
            $vet_pontos = array();
            $px1=$gap;
            $y=  $hlabel;
            $py1 = $y;
            $ni = 0;
            while ($ni < $nlen) {
                $px2 = $px1+($conjunto);
                $row = $vet[$ni];
                $value = $row['value'];
                $color = $row['color'];
                $h = ($value / $nmax_value) * $nmax_height;
                $py2 = ($y+$h);
                $label = $label_x[$ni];
                $p = number_format($value,2,',', '.');
                $cline.= "<g fill='black' class='linesep' stroke='rgba(130,130,123,0.65)' stroke-width='0.85' fill-opacity='0.5'>";
                $cline .= "<circle cx='$px2' cy='$py2' r='2.5' fill='$color' fill-opcatiy='0.44' class='ponto' id='ponto_$ni' style='cursor:pointer;'>";
                $cline .= "<title class='perc' fill='black'>$label\n$p</title>";
                $cline .= "</circle>";
                $cline .="<line x1='$px1' y1='$py1' x2='$px2' y2='$py2' fill='cyan' />";
                $cline.= "</g>";
                $py1=$py2;
                $px1=$px2;
                $ni+=1;
            }
            $ni = 0;
            $nlen = count($label_x);
            $ty = ($hlabel-24) *-1;
            
            $tx = $gap+$conjunto-20;
            while ($ni < $nlen) {
                $label = $label_x[$ni];
                $cline.= "<g class='labels_axisx'>";
                $cline.= "   <text x='$tx' y='$ty' transform='rotate(-45, $tx, $ty)' fill='white'>$label</text>";
                $cline .= "</g>";
                $tx += $conjunto+0.096;
                $ni +=1;
            }
        
            $cline .= "<script xlink:href='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.0/jquery.min.js'></script>";
            $cline .= "<script type='text/ecmascript'><![CDATA[";
    
            $cline .= "$(document).ready(()=> {
                        let vcircle = $('.ponto');
                        vcircle.each((ix,elem) => {
                            var obj = $(elem);
                            var id = obj.attr('id').replace(/\D+/g,'');
                            var objtext = $('text#title_'+id);
                            obj.on('mouseout', (evt)=>{
                                evt.stopPropagation();
                                evt.preventDefault();
                                objtext.css('display', '');
                                objtext.css('display', 'none');
                            });
                         
                            obj.on('mouseover', (evt)=>{
                                evt.stopPropagation();
                                evt.preventDefault();
                                objtext.css('display', '');
                                objtext.css('display', 'block');
                            });
                         
    
    
                        });
            })";
            $cline .= "]]></script>";
            $cline.= "</svg>";
            return $cline;
        }
  

        public function draw_vbar() {
            $cline ="";
            $vet = self::$Data;
            $vValues = array_column($vet, "value");
            $vPerc = array_column($vet, "perc");
            $total = 0;
            foreach ($vPerc as $perc) {
                $total +=$perc;
            }
            if ($total > 1) {
                foreach ($vet as $nrow => $row) {
                    $value = $row['value'];
                    $color = $row['color'];
                    $perc = $row['perc'];
                    $perc = round($perc / 100,2);
                    $aux = array("value" => $value, "perc" => $perc, "color" => $color);
                    $vet[$nrow] = $aux;
                }
            }
            $max = max($vValues);
            $min = min($vValues);
            $nlen = count($vet);
            $label_x = self::$label_eixoX;
            $nlenmax = strlen(ceil($max));
            $potencia = pow(10, $nlenmax-1);
            $nlenmin = strlen(ceil($min));
            $pmin = pow(10, $nlenmin-1);
            $dif = ($pmin * 100) / $potencia;
                      
            if ($dif < 10) {
                $media = ($potencia+$pmin) /2;
                $div = $potencia + $media;
                $ndiv = ceil($max/$div)+1;
            } else {
                $inter = $potencia /$dif;
                $div = round($max/$potencia,2) * $inter;
                $ndiv = round($max/$div,0)+1;
            }
            $gap = 16 *(1+ ($nlenmax -1));
    
            $nview_width = self::$view_width;
            $nview_height = self::$view_height;
    
            $fh = round($nview_height / $ndiv-1,3);
            if (self::$blabel_X) {
                $max_label = ceil(strlen(max($label_x)) * cos(45) * 16)+1;
            } else {
                $max_label = 0;
            }
            $vtitle = $this->getTitle();
            if (! is_null($vtitle)) {
                $title = $vtitle["title"];
                $colortitle = $vtitle["color"];
            } else {
                $title = "";
                $colortitle="";
            }

            if (strlen($title) > 0) {
                $lentitle = 21;
            } else {
                $lentitle = 0;
            }

            $nheigth_util = ($fh * ($ndiv));
            $nview_height = $nheigth_util+$lentitle + $max_label;

            $nmax_value = $div * ($ndiv-1);
            $nmax_height = $fh * ($ndiv-1);
            $nbarras = count($vet);
    
            $spcbar = $nbarras +1;
            $ntot = $nbarras + $spcbar;
            $nboxw = ceil($nview_width / $ntot);
            $nspace = $nboxw/3;
            $conjunto = ($nboxw+$nspace);
            $last_y = $nview_height;

    
            $max_width = $conjunto * ($nbarras + $gap / $conjunto)+$nspace;
            $class_name = "bar_" . self::gettmpClass(12);
            $cline="";
            $cline.= "<svg xmlns='http://www.w3.org/2000/svg' viewbox='0 0 $max_width $nview_height' class='$class_name' >";
            $cline.= "<style  type='text/css'>"; 
            $cline.= ".labels {";
            $cline.= "  display: flex;
                    position: relative;
                    float:right; 
                    left: 0px !important;
                    transform: scaleY(-1);
                    color: #fff;
                    font-size: Calc($max_width * 0.19%);
                    font-family: Arial;
                    }";
            $cline .= ".line {
                        width=100%;
                        border: thin dotted #cca5;
                    }
                    .linesep {
                        width=100%;
                        border: thin solid #cac0cd;
        
                    }";
            $cline .= ".title {
                        font-size: Calc($max_width * 0.21%);
                        font-family: Arial;
                        color: #fff;
                    }";
    
            $cline.= "svg.$class_name {
                    transform: scaleY(-1);
                }";
            $cline.=".titulo {
                    display:block;
                    position:fixed;
                    botton: 0;
                    font-size:Calc($max_width * 0.21%);
                    font-family:Arial;
                    color: #fff;
                    text-align:center;
                    transform: scaleY(-1);
                    }";
                
            $cline.= "</style>";
            $ni = 0;
            $x= $gap;
            $ny = $max_label * 90/100;
          
            $last_x = $max_width + $conjunto;
            $cline.= "<g fill='transparent' stroke='cyan' stroke-width='1' class='line'>";
            $cline.= "  <line x1='$x' x2='$last_x' y1='$ny' y2='$ny' fill-opacity='0.4'></line>";
            
            $cline.= "<g fill='transparent' class='linesep' stroke='rgba(230,230,230,0.35)' stroke-width='0.7' fill-opacity='0.35'>";
            $p = 0;
            $tx = $gap * 90/100;
            $ty = ($ny + $fh) *-1;
            $ni = 0;
            while ($ni < $ndiv-1) {
                $ny += $fh;
                $cline.= "<line x1='$x' x2='$max_width' y1='$ny' y2='$ny' fill-opacity='0.4' />";
                $ni+=1;
            }
            
            $cline.= "</g>";
            $last_y = $ny;
            $ny = $max_label * 90/100;
            $cline.= "<g fill='transparent' stroke='cyan' stroke-width='1' class='line'>";
            $cline.= "  <line x1='$x' x2='$x' y1='$ny' y2='$last_y' fill-opactiy='0.4'></line>";
            $cline.= "</g>";

            if (strlen($title) > 0) {
                $nlentitulo = strlen($title) /2;
                $tit_x = $max_label + ($max_width-($nlentitulo+$gap))/3;
                $tit_y = ($last_y) *-1;
                $cline .= "<g class='line'>";
                $cline .= "  <text x='$tit_x' y='$tit_y' fill='$colortitle' class='titulo'>$title</text>"; 
                $cline .= "</g>";
            }
            $ni = 0;
            $cline.= "<g class='labels'>";
            while ($ni < $ndiv-1) {
                $p += $div;
                $value = number_format($p,0,',','.');
                $cline.= "   <text x='$tx' y='$ty' text-anchor='end' fill='white'>$value</text>";
                $ty -= $fh;
                $ni += 1;
            }
            $cline.= "</g>";
    
            $cline.= "<g fill='transparent' class='linesep' stroke='rgba(230,230,230,0.35)' stroke-width='0.7' fill-opacity='0.35'>";
            $ni = 0;
            $tx = $gap+$nspace;
            $ty = $max_label * 90/100;
            
            while ($ni < $nlen) {
                $row = $vet[$ni];
                $value = $row['value'];
                $perc = $row['perc'];
                $color = $row['color'];

                $hperc = $value / $nmax_value;
                $height = $nmax_height * $hperc;

                $nvalue =number_format($value,2,',','.');
                $cline.= "<rect x='$tx' y='$ty' width='$nboxw' height='$height' fill='$color' fill-opacity='$perc' style='cursor:pointer;'>";
                $cline.= "<title style='font-size:9px'>$nvalue</title>";
                $cline.= "</rect>";
                $tx += $nboxw+$nspace;
                $ni +=1; 
            }
            $cline.= "</g>";
    
            $ni = 0;
            $tx = $gap+$nspace *2.33;
            $ty = ($max_label *3/4) *-1;
            while ($ni < $nlen) {
                $label = $label_x[$ni];
                $cline.= "<g class='labels'>";
                $cline .= "  <text x='$tx' y='$ty' fill='white' text-anchor='end' transform='rotate(-45,$tx,$ty)'>$label<title style='font-size:9px'>$label</title></text>";
                $cline.= "</g>";
    
                $tx += $nboxw+$nspace;
                $ni+=1;
            }
          
            $cline.= "</svg>";
        return $cline;
        }
    }
?>