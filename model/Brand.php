<?php

/**
 *
 * Author : David Marquez
 * Date   : 23 September 2020
 * 
 */
require(MDLPH . 'Base.php');

class Brand extends Base
{   
    /**
     * Information about the construction 
     * of the module
     * 
     * @return string
     */
    public $author = 'Totto Marquez';
    public $email = 'davidmarsant@gmail.com';
    public $version = '1.0.2';

    /** Table */
    private $table = "tm_manufacturer";

    /** Table Head */
    public $thead = ['#', 'Brand', 'Status', 'Updated'];

    /** Brand ID */
    private $id = 0;

    public function readBrand()
    {
        $this->db = new DB('prestashop');

        if ( $this->db->connection->isConnected() ) {
            $this->data = $this->db->connection
                ->getAll(
                    "SELECT ROW_NUMBER() OVER (ORDER BY tm.id_manufacturer) AS nro, tm.name, CASE WHEN tm.active = 1 THEN 'active' ELSE 'inactive' END AS status, tm.date_upd
                    FROM $this->table tm 
                    INNER JOIN ". $this->table ."_lang tml ON tml.id_manufacturer = tm.id_manufacturer AND tml.id_lang = 2"
                );
            $this->title = $this->module;
            $this->description = ucfirst($this->module) . ' details';
        } else {
            $this->_error = $this->db->connection->errorMsg();
        }
    }

    public function infoBrand()
    {
        $this->db = new DB('prestashop');

        if ($this->db->connection->isConnected()) {
            $this->setInfo();
            $this->getLastDay();
            $this->getLastSynchronized($this->table);
            $this->data = array(
                'icon'      =>  $this->getIcon('module', $this->module),
                'status'    =>  'active',
                'author'    =>  $this->author,
                'email'     =>  $this->email,
                'version'   =>  $this->version,
                'modified'  =>  $this->lastTime
            );
        }

        $this->db->connection->close();
    }
}
