<?php
    include_once "cls_traitimg.php";
    class cls_loadimg {
        use default_images;
        function __construct() {
        }
        function getImageUrl($id_img) {
           return $this->getSource($id_img);
        }
    }
?>