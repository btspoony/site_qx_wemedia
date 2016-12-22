<?php

class Publics extends DEFAULT_Controller {

    /**
     * 图片上传接口地址
     */
    public function uploadimg() {

        // 文件类型
        $ftype = !empty($_FILES['file']['type']) ? substr(strrchr($_FILES['file']['name'], "."), 1) : null;

        $tmp_path = 'upload/' . $ftype . '/' . date('Ymd');

        if (!is_dir(FCPATH . "public/" . $tmp_path)) {
            @mkdir(FCPATH . "public/" . $tmp_path, 0777, true);
        }

        $tmpfile = $tmp_path . '/' . time() . '.' . $ftype;

        if (move_uploaded_file($_FILES['file']['tmp_name'], FCPATH . "public/" . $tmpfile)) {
            // 権限
            @chmod(FCPATH . $tmpfile, 0777);
            $this->json_success(array('filepath' => 'public/'.$tmpfile));
        }
        $this->json_error();
    }

    /**
     * 删除文件
     */
    public function delete_file() {
        set_time_limit(3);

        header("Cache-Control: no-cache");
        header("Pragma: no-cache");

        if (!empty($_REQUEST['fullName'])) {
            @unlink(FCPATH . $_REQUEST['fullName']);
            $this->json_success();
        }
        $this->json_error();
    }

}
