<?php

class MediaList extends Controller
{

    private $media_list = []; // keeps folder and media
    private $play_list = []; // just keeps media
    private $media_dir = '/media/pi/Music';

    public function __construct() {
        $this->recursiveScan($this->media_dir);
    }

    public function index($path = '') {
        header('Content-type: application/json');
        echo json_encode($this->media_list);
    }

    public function first() {
      $first_file = $this->play_list[0];
      $file_path = str_replace("/pi/Music", "", $first_file['id']);
      $file_title = $first_file['text'];
      return array('file_path' => $file_path, 'file_title' => $file_title);
    }

    public function get_play_list() {
        header('Content-type: application/json');
        echo json_encode($this->play_list);
    }


    private function recursiveScan($root) {

        $iter = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($root, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST,
        RecursiveIteratorIterator::CATCH_GET_CHILD // Ignore "Permission denied"
        );

        $paths = array($root);
        $count = 0;
        foreach ($iter as $path => $dir) {

            $file_name_array = explode("/", $path);
            $file_parent_array = array_slice($file_name_array, 0, sizeof($file_name_array) -1 );
            $path_parent = implode("/", $file_parent_array);

            $path_parent = ($path_parent == $this->media_dir) ? '#' : $path_parent;

            $node = $this->process_node($path_parent, $path);
            
            if (!$dir->isDir() && preg_match("/^(.*)\.(mp3|mp4|webm|avi)$/i", $node['text'])) {
                $node["list_number"] = $count++;
                $node["li_attr"]["list_number"] = $node["list_number"];
                array_push($this->play_list, $node);
            }
            array_push($this->media_list, $node);
        }

        if (sizeof($this->media_list) < 1) {
            array_push($this->media_list, $this->node_no_data());
            array_push($this->play_list, $this->node_no_data());
        }

    }

    private function process_node($parent, $file) {

        $file_name_array = explode("/",$file);
        $file_name = explode("/",$file)[sizeof($file_name_array) -1];

        return array(
            "id" => $file,
            "parent" => $parent,
            "text" => $file_name,
            "icon" => is_dir($file) ? 'jstree-custom-folder' : 'jstree-custom-file',
            "state" => array(
              "opened" => false,
              "disabled" => false,
              "selected"=> false
            ),
            "li_attr" => array(
                "base" => $parent . '/' . $file_name,
                "isLeaf" => !is_dir($file),
                "relative_path" => str_replace("/pi/Music", "", $file)
            )
        );
    }

    private function node_no_data() {
        return array(
            "id" => 'No Data',
            "parent" => '#',
            "text" => 'No Data',
            "icon" => 'jstree-custom-folder',
            "state" => array(
                "opened" => false,
                "disabled" => false,
                "selected"=> false
            ),
            "li_attr" => array(
                "base" =>'',
                "isLeaf" => false,
                "relative_path" => ''
            )
        );
    }

}
