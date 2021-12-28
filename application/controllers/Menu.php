<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        // user access
        is_logged_in();
    }

    // index view menu
    public function index()
    {
        $data['title'] = 'Menu Manajemen';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $data['menu'] = $this->db->get('user_menu')->result_array();

        $this->load->view('templates/admin_header', $data);
        $this->load->view('templates/admin_sidebar');
        $this->load->view('templates/admin_topbar', $data);
        $this->load->view('menu/index', $data);
        $this->load->view('templates/admin_footer');
    }

    // add menu
    public function addmenu()
    {
        $data['title'] = 'Menu Manajemen';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $data['menu'] = $this->db->get('user_menu')->result_array();

        $this->form_validation->set_rules('menu', 'Menu', 'required', [
            'required' => 'Nama menu tidak boleh kosong!'
        ]);

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/admin_header', $data);
            $this->load->view('templates/admin_sidebar');
            $this->load->view('templates/admin_topbar', $data);
            $this->load->view('menu/index', $data);
            $this->load->view('templates/admin_footer');
        } else {
            $this->db->insert('user_menu', ['menu' => $this->input->post('menu')]);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
            Menu baru ditambahkan!</div>');
            redirect('menu');
        }
    }

    // edit menu
    public function editmenu($id = null)
    {   
        $this->form_validation->set_rules('menu', 'Menu', 'required', [
            'required' => 'Nama menu tidak boleh kosong!'
        ]);
        
        if ($this->form_validation->run() == false) {
            $data['title'] = 'Menu Manajemen';
            $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
            $data['menu'] = $this->db->get_where('user_menu', ['id' => $id])->row_array();

            $this->load->view('templates/admin_header', $data);
            $this->load->view('templates/admin_sidebar');
            $this->load->view('templates/admin_topbar', $data);
            $this->load->view('menu/edit_menu', $data);
            $this->load->view('templates/admin_footer');
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
            Gagal mengubah menu!</div>');
        } else {
            $data = [
                'id' => $this->input->post('id'),
                'menu' => $this->input->post('menu')
            ];

            $this->db->update('user_menu', $data, ['id' => $id]);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
            Berhasil mengubah menu!</div>');
            redirect('menu');
        }
    }

    // delete menu
    public function deletemenu($id = null)
    {
        $this->db->delete('user_menu', ['id' => $id]);
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
        Menu berhasil dihapus!</div>');
        redirect('menu');
    }

    // index view sub menu
    public function submenu()
    {
        $data['title'] = 'Submenu Manajemen';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $data['menu'] = $this->db->get('user_menu')->result_array();
        $this->load->model('Menu_model', 'menu');
        $data['submenu'] = $this->menu->getSubMenu();

        $this->load->view('templates/admin_header', $data);
        $this->load->view('templates/admin_sidebar');
        $this->load->view('templates/admin_topbar', $data);
        $this->load->view('menu/submenu', $data);
        $this->load->view('templates/admin_footer');
    }

    // add sub menu
    public function addsubmenu()
    {
        $data['title'] = 'Submenu Manajemen';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $data['menu'] = $this->db->get('user_menu')->result_array();
        $this->load->model('Menu_model', 'menu');
        $data['submenu'] = $this->menu->getSubMenu();

        $this->form_validation->set_rules('title', 'Submenu', 'required', [
            'required' => 'Submenu tidak boleh kosong!'
        ]);
        $this->form_validation->set_rules('menu_id', 'Menu', 'required', [
            'required' => 'Menu harus di pilih!'
        ]);
        $this->form_validation->set_rules('url', 'Url', 'required', [
            'required' => 'Url tidak boleh kosong!'
        ]);
        $this->form_validation->set_rules('icon', 'Ikon', 'required', [
            'required' => 'Ikon tidak boleh kosong!'
        ]);

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/admin_header', $data);
            $this->load->view('templates/admin_sidebar');
            $this->load->view('templates/admin_topbar', $data);
            $this->load->view('menu/submenu', $data);
            $this->load->view('templates/admin_footer');
        } else {
            $data = [
                'title' => $this->input->post('title'),
                'menu_id' => $this->input->post('menu_id'),
                'url' => $this->input->post('url'),
                'icon' => $this->input->post('icon'),
                'is_active' => $this->input->post('is_active')
            ];

            $this->db->insert('user_sub_menu', $data);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
            Data submenu berhasil ditambahkan!</div>');
            redirect('menu/submenu');
        }
    }

    // edit sub menu
    public function editsubmenu($id = null)
    {
        $this->form_validation->set_rules('title', 'Submenu', 'required', [
            'required' => 'Submenu tidak boleh kosong!'
        ]);
        $this->form_validation->set_rules('menu_id', 'Menu', 'required', [
            'required' => 'Menu harus di pilih!'
        ]);
        $this->form_validation->set_rules('url', 'Url', 'required', [
            'required' => 'Url tidak boleh kosong!'
        ]);
        $this->form_validation->set_rules('icon', 'Ikon', 'required', [
            'required' => 'Ikon tidak boleh kosong!'
        ]);

        if ($this->form_validation->run() == false) {
            $this->load->model('Menu_model', 'menu');
            $data['title'] = 'Submenu Manajemen';
            $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
            $data['menu'] = $this->db->get('user_menu')->result_array();
            $data['submenu'] = $this->menu->getSubMenu();
            $data['submenu'] = $this->db->get_where('user_sub_menu', ['id' => $id])->row_array();
    
            $this->load->view('templates/admin_header', $data);
            $this->load->view('templates/admin_sidebar');
            $this->load->view('templates/admin_topbar', $data);
            $this->load->view('menu/edit_submenu', $data);
            $this->load->view('templates/admin_footer');
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
            Gagal mengubah data submenu!</div>');
        } else {
            $data = [
                'id' => $this->input->post('id'),
                'title' => $this->input->post('title'),
                'menu_id' => $this->input->post('menu_id'),
                'url' => $this->input->post('url'),
                'icon' => $this->input->post('icon'),
                'is_active' => $this->input->post('is_active')
            ];
            
            $this->db->update('user_sub_menu', $data, ['id' => $data['id']]);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
            Berhasil mengubah data submenu!</div>');
            redirect('menu/submenu');
        }
    }

    // delete sub menu
    public function deletesubmenu($id = null)
    {
        $this->db->delete('user_sub_menu', ['id' => $id]);
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
        Submenu berhasil dihapus!</div>');
        redirect('menu/submenu');
    }

}