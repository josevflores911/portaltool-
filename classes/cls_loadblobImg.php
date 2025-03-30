<?php
    include_once "cls_traitimg.php";
    class cls_loadblobImg {
        static $id_img = NULL;
        use default_images;

        function __construct($id_img=NULL) {
            if (! is_null($id_img)) {
                self::$id_img = $id_img;
            }
        }

        function getImageUrl($id_img=NULL) {
            if (is_null($id_img)) {
                $id_img = self::$id_img;
            }
           return $this->getSource($id_img);
        }
    }
?>