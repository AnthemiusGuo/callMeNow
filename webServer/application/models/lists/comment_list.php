<?php
include_once(APPPATH."models/list_model.php");
include_once(APPPATH."models/records/comment_model.php");
class Comment_list extends List_model {
    public function __construct() {
        parent::__construct("cComments");
        parent::init("Comment_list","Comment_model");
        
    }
}
?>