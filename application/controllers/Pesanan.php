<?php
/*
 * Generated by CRUDigniter v3.2
 * www.crudigniter.com
 */

class Pesanan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Pesanan_model');
        $this->load->model('Barang_model');
        $this->load->model('Users_model');
    }

    /*
     * Listing of pesanan
     */
    public function index()
    {
        $params['limit'] = RECORDS_PER_PAGE;
        $params['offset'] = ($this->input->get('per_page')) ? $this->input->get('per_page') : 0;

        $config = $this->config->item('pagination');
        $config['base_url'] = site_url('pesanan/index?');
        $config['total_rows'] = $this->Pesanan_model->get_all_pesanan_count();
        $this->pagination->initialize($config);

        $user_level = $this->session->userdata('user_level');
        $user_id = $this->session->userdata('user_id');

        $data['pesanan'] = $this->Pesanan_model->get_all_pesanan($params);

        $data['_view'] = 'pesanan/index';
        $this->load->view('layouts/main', $data);
    }

    /*
     * Adding a new pesanan
     */
    public function add()
    {
        $data['barang_list'] = $this->Barang_model->get_all_barang();
        if (isset($_POST) && count($_POST) > 0) {
            $params = array(
                'nomor_pesanan' => $this->input->post('nomor_pesanan'),
                'nama_pelanggan' => $this->input->post('nama_pelanggan'),
                'alamat' => $this->input->post('alamat'),
                'tanggal_pesanan' => $this->input->post('tanggal_pesanan'),
            );

            $id_pesanan = $this->Pesanan_model->add_pesanan($params);
            redirect('pesanan/index');
        } else {
            $data['_view'] = 'pesanan/add';
            $this->load->view('layouts/main', $data);
        }
    }

    public function add_barang_di_pesanan($id_pesanan)
    {
        $data['pesanan'] = $this->Pesanan_model->get_pesanan($id_pesanan);
        $data['barang_list'] = $this->Barang_model->get_all_barang();

        if (isset($_POST) && count($_POST) > 0) {
            $params = array(
                'fk_id_pesanan' => $id_pesanan,
                'fk_id_barang' => $this->input->post('id_barang'),
                'jumlah_pesanan' => $this->input->post('jumlah_pesanan'),
            );

            // print_r($params);
            // exit();

            $id_pesanan = $this->Pesanan_model->add_barang_di_pesanan($params);
            redirect('pesanan/index');
        } else {
            $data['_view'] = 'pesanan/add_barang_di_pesanan';
            $this->load->view('layouts/main', $data);
        }
    }

    public function add_pengiriman($id_pesanan)
    {
        $data['barang_pesanan'] = $this->Pesanan_model->get_barang_pesanan($id_pesanan);
        $data['pesanan'] = $this->Pesanan_model->get_pesanan($id_pesanan);

        if (isset($_POST) && count($_POST) > 0) {
            $params = array(
                'fk_id_pesanan' => $id_pesanan,
                'fk_id_barang' => $this->input->post('id_barang'),
                'jumlah_kirim' => $this->input->post('jumlah_kirim'),
                'tanggal_kirim' => $this->input->post('tanggal_kirim'),
            );

            // print_r($params);
            // exit();

            $id_pesanan_kirim = $this->Pesanan_model->add_pengiriman($params);
            redirect('pesanan/detail/'.$id_pesanan);
        } else {
            $data['_view'] = 'pesanan/add_surat_jalan';
            $this->load->view('layouts/main', $data);
        }
    }

    /*
     * Cek Stok di halaman add barang di pesanan
     */

    public function cek_stok_barang($id_barang)
    {
        $jumlah = $this->Barang_model->get_jumlah_barang($id_barang);
        echo $jumlah;
    }

    /*
     * Editing a pesanan
     */

    public function edit($id_pesanan)
    {
        // check if the pesanan exists before trying to edit it
        $data['pesanan'] = $this->Pesanan_model->get_pesanan($id_pesanan);

        if (isset($data['pesanan']['id_pesanan'])) {
            if (isset($_POST) && count($_POST) > 0) {
                $params = array(
                    'nomor_pesanan' => $this->input->post('nomor_pesanan'),
                    'nama_pelanggan' => $this->input->post('nama_pelanggan'),
                    'alamat' => $this->input->post('alamat'),
                    'tanggal_pesanan' => $this->input->post('tanggal_pesanan'),
                );

                $this->Pesanan_model->update_pesanan($id_pesanan, $params);
                redirect('pesanan/index');
            } else {
                $data['_view'] = 'pesanan/edit';
                $this->load->view('layouts/main', $data);
            }
        } else {
            show_error('The pesanan you are trying to edit does not exist.');
        }
    }

    public function selesaikan($id_pesanan)
    {
        // check if the pesanan exists before trying to edit it
        $data['pesanan'] = $this->Pesanan_model->get_pesanan($id_pesanan);

        $params = array(
            'is_selesai' => '1',
        );
        // print_r($params);
        // exit();
        
        $this->Pesanan_model->update_pesanan($id_pesanan, $params);
        redirect($_SERVER['HTTP_REFERER']);
    }

    public function detail($id_pesanan)
    {
        // check if the pesanan exists before trying to edit it
        $data['pesanan'] = $this->Pesanan_model->get_pesanan($id_pesanan);
        $data['barang_pesanan'] = $this->Pesanan_model->get_barang_pesanan($id_pesanan);
        $data['pengiriman'] = $this->Pesanan_model->get_barang_pengiriman($id_pesanan);

        if (isset($data['pesanan']['id_pesanan'])) {
            if (isset($_POST) && count($_POST) > 0) {
                redirect('pesanan/index');
            } else {
                $data['_view'] = 'pesanan/detail';
                $this->load->view('layouts/main', $data);
            }
        } else {
            show_error('The pesanan you are trying to edit does not exist.');
        }
    }

    public function detail_pengiriman($id_pesanan)
    {
        // check if the pesanan exists before trying to edit it
        $data['pesanan'] = $this->Pesanan_model->get_pesanan($id_pesanan);
        $data['barang_pesanan'] = $this->Pesanan_model->get_barang_pesanan($id_pesanan);
        $data['pengiriman'] = $this->Pesanan_model->get_barang_pengiriman($id_pesanan);
        $data['detail'] = $this->Pesanan_model->get_barang_pengiriman_grouped($id_pesanan);

        if (isset($data['pesanan']['id_pesanan'])) {
            if (isset($_POST) && count($_POST) > 0) {
                redirect('pesanan/index');
            } else {
                $data['_view'] = 'pesanan/detail_pengiriman';
                $this->load->view('layouts/main', $data);
            }
        } else {
            show_error('The pengiriman you are trying to edit does not exist.');
        }
    }

    /*
     * Deleting pesanan
     */
    public function remove($id_pesanan)
    {
        $pesanan = $this->Pesanan_model->get_pesanan($id_pesanan);

        // check if the pesanan exists before trying to delete it
        if (isset($pesanan['id_pesanan'])) {
            $this->Pesanan_model->delete_pesanan($id_pesanan);
            redirect('pesanan/index');
        } else {
            show_error('The pesanan you are trying to delete does not exist.');
        }
    }
    /*
     * Deleting barang dari pesanan
     */
    public function remove_barang($id_barang_pesanan)
    {
        $pesanan = $this->Pesanan_model->get_barang_pesanan_only($id_barang_pesanan);

        // check if the pesanan exists before trying to delete it
        if (isset($pesanan['id_barang_pesanan'])) {
            $this->Pesanan_model->delete_barang_pesanan($id_barang_pesanan);
            redirect($_SERVER['HTTP_REFERER']);
        } else {
            show_error('The barang you are trying to delete does not exist.');
        }
    }
}