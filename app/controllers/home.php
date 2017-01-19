<?php

require_once 'medialist.php';

class Home extends Controller
{
    public function index($name = '') {
        $user = $this->model('user');
        $user->name = $name;

        $mediaController = new MediaList();

        $first_file = $mediaController->first();

        $this->view('home/index', [
          'name' => $user->name,
          'file_path' => $first_file['file_path'],
          'file_title' => $first_file['file_title']
        ]);
    }

    public function create($username = '', $email = '') {
        User::create([
            'username' => $username,
            'email' => $email
        ]);
    }
}