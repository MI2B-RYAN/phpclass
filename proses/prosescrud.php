<?php

    class ProsesCrud {

        protected $db;
        
        function __construct($db) {
            $this->db = $db;
        }

        function proses_login($user, $pass)
        {
            $row = $this->db->prepare('SELECT * FROM tbl_user WHERE username=? AND password=md5(?)');
            $row->execute(array($user, $pass));
            $count = $row->rowCount();
            if ($count > 0) 
            {
                return $hasil = $row->fetch();
            }else{
                return 'Gagal';
            }
        }

        function tampil_data($tabel)
        {
            $row = $this->db->prepare("SELECT * FROM $tabel");
            $row->execute();
            return $hasil = $row->fetchAll();
        }

        function tampil_data_id($tabel, $where, $id)
        {
            $row = $this->db->prepare("SELECT * FROM $tabel WHERE $where = ?");
            $row->execute(array($id));
            return $hasil = $row->fetch();
        }

        function tambah_data($tabel, $data)
        {
            $rowSql = array();
            $toBind = array();
            $columNames = array_keys($data[0]);
            foreach ($data as $arrayIndex => $row) {
                $params = array();
                foreach ($row as $columName => $columValue) {
                    $param = ":" . $columName . $arrayIndex;
                    $params[] = $param;
                    $toBind[$param] = $columValue;
                }
                $rowSql[] = "(" . implode(", ", $params) . ")";
            }
            $sql = "INSERT INTO $tabel (" . implode(", ", $columNames) . ") VALUES " . implode(", ", $rowSql);
            $row = $this->db->prepare($sql);
            foreach ($toBind as $param => $val) {
                $row->bindValue($param, $val);
            }
            return $row->execute();
        }

        function edit_data($tabel, $data, $where, $id)
        {
            $setPart = array();
            foreach ($data as $key => $value) 
            {
                $setPart[] = $key."=:".$key; 
            }
            $sql = "UPDATE $tabel SET ".implode(', ', $setPart)." WHERE $where = :id";
            $row = $this->db->prepare($sql);
            $row ->bindValue(':id',$id);
            foreach ($data as $param => $val) 
            {
                $row ->bindValue($param, $val);
            }
            return $row->execute(); 
        }

        function hapus_data($tabel, $where, $id)
        {
            $sql = "DELETE FROM $tabel WHERE $where = ?";
            $row = $this->db->prepare($sql);
            return $row->execute(array($id));
        }
    }
?>