<?php

require(dirname(__FILE__) . '/Controller.php');
require(MDLPH . 'Category.php');
/*
|--------------------------------------------------------------------------
| Category Controller
|--------------------------------------------------------------------------
|
| This code handles all request about category controller
|
*/
if (isset($_SESSION['temp']) || isset($_SESSION['User_ID'])) {

    $category = new Category();
    
    $category->keys = array('GT' => $_GET['token'], 'PT' => $_POST['token']);
    if ( $category->isValid() ) {
        $category->module = (isset($_POST['module'])) ? strtolower(base64_decode($_POST['module'])) : null ;    
        $category->method = (isset($_POST['method'])) ? base64_decode($_POST['method']) : null ;
        
        if ( $category->isModule() && $category->isCRUD()) {
            /** This is the method to execute */
            $act = $category->method . ucfirst($category->module) ;
        }
    }

    $category->getView();
    if ($category->isView()) {
        if(!$category->view->isCached($category->html)) {
            /** Execute */
            $category->$act();
            $category->view
                ->assign('title', $category->module)
                ->assign('description', $category->description)
                ->assign('thead', $category->thead)
                ->assign('data', $category->data)
                ->assign('synchronized', $category->synchronized);
        }
        $category->view->display($category->html);
    } 
}