<?php
/* 
 * Generated by CRUDigniter v3.2 
 * www.crudigniter.com
 */

class Scan extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Jamaah_model');
    }

    /*
     * Listing of luasan
     */
    function index()
    {
        $params['limit'] = RECORDS_PER_PAGE;
        $params['offset'] = ($this->input->get('per_page')) ? $this->input->get('per_page') : 0;

        $config = $this->config->item('pagination');
        $config['base_url'] = site_url('scan/index?');
        $this->pagination->initialize($config);
        $data['_view'] = 'scan/index';
        $data['jamaah'] = $this->Jamaah_model->get_all_jamaah($params);
        $this->load->view('layouts/main', $data);
    }

    function public()
    {
        $params['limit'] = RECORDS_PER_PAGE;
        $params['offset'] = ($this->input->get('per_page')) ? $this->input->get('per_page') : 0;

        $config = $this->config->item('pagination');
        $config['base_url'] = site_url('scan/public?');
        $this->pagination->initialize($config);
        $data['_view'] = 'scan/public';
        $result_code = $this->input->post('uuid');
        $data['jamaah'] = $this->Jamaah_model->get_jamaah_by_uuid($result_code);
        $this->load->view('layouts/main', $data);
    }

    public function messageAlert($type, $title)
    {
        $messageAlert = "const Toast = Swal.mixin({
			toast: true,
			position: 'top-end',
			showConfirmButton: false,
			timer: 3000
		});
		Toast.fire({
			type: '" . $type . "',
			title: '" . $title . "'
		});";
        return $messageAlert;
    }

    // function add_scan()
    // {
    //     $result_code = $this->input->post('nik');
    //     $get_jamaah = $this->Scan_model->get_jamaah($result_code);
    //     if (isset($_POST) && count($_POST) > 0) {
    //         $params = array(
    //             'id_jamaah' => $get_jamaah['id_jamaah'],
    //             'nik' => $get_jamaah['nik'],
    //             'nama_jamaah' => $get_jamaah['nama_jamaah'],
    //             'kehadiran' => 'Tidak Hadir / Belum Hadir',
    //         );
    //         $this->Scan_model->add_scan($params);
    //     } else {
    //         $data['_view'] = 'jamaah/add';
    //         $this->load->view('layouts/main', $data);
    //     }
    // }

    function cek_id()
    {
        $result_code = $this->input->post('uuid');
        $tgl = date('Y-m-d');
        $jam_msk = date('h:i:s');
        $jam_klr = date('h:i:s');
        $get_jamaah = $this->Jamaah_model->get_jamaah_by_uuid($result_code);
        if ($get_jamaah != null) {
            $params = array(
                'kehadiran' => 'Hadir',
            );
            $this->Jamaah_model->update_scan($result_code, $params);
            $this->session->set_flashdata('jk', $get_jamaah['jenis_kelamin']);
            $this->session->set_flashdata('nama_jamaah', $get_jamaah['nama_jamaah']);
            redirect('scan/index');
        } else {
            $this->session->set_flashdata('jk', '');
            $this->session->set_flashdata('error', 'Jamaah Tidak Ditemukan');
            redirect('scan/index');
        }
        // $result_code = $this->input->post('nik');
        // $tgl = date('Y-m-d');
        // $jam_msk = date('h:i:s');
        // $jam_klr = date('h:i:s');
        // $get_jamaah = $this->Scan_model->get_jamaah($result_code);
        // if (isset($_POST) && count($_POST) > 0) {
        //     $params = array(
        //         'id_jamaah' => $get_jamaah['id_jamaah'],
        //         'nik' => $get_jamaah['nik'],
        //         'nama_jamaah' => $get_jamaah['nama_jamaah'],
        //         'kehadiran' => 'Hadir',
        //     );
        //     $this->session->set_flashdata('nama', $params['nama_jamaah']);
        //     $this->session->set_flashdata('jk', $params['jenis_kelamin']);
        //     $this->Scan_model->add_scan($params);
        //     redirect('scan/index');
        // } else {
        //     $data['_view'] = 'scan/index';
        //     $this->load->view('layouts/main', $data);
        // }

        // if (!$cek_id) {
        // 	$this->session->set_flashdata('messageAlert', $this->messageAlert('error', 'absen gagal data QR tidak ditemukan'));
        // 	redirect('scan/index');
        // } elseif ($cek_kehadiran && $cek_kehadiran->jam_msk != '00:00:00' && $cek_kehadiran->jam_klr == '00:00:00' && $cek_kehadiran->id_status == 1) {
        // 	$data = array(
        // 		'jam_klr' => $jam_klr,
        // 		'id_status' => 2,
        // 	);
        // 	$this->Scan_model->absen_pulang($result_code, $data);
        // 	$this->session->set_flashdata('messageAlert', $this->messageAlert('success', 'absen pulang'));
        // 	redirect('scan/index');
        // } elseif ($cek_kehadiran && $cek_kehadiran->jam_msk != '00:00:00' && $cek_kehadiran->jam_klr != '00:00:00' && $cek_kehadiran->id_status == 2) {
        // 	$this->session->set_flashdata('messageAlert', $this->messageAlert('warning', 'sudah absen'));
        // 	redirect('scan/index');
        // 	return false;
        // } else {
        // 	$data = array(
        // 		'nik' => $result_code,
        // 		'tgl' => $tgl,
        // 		'jam_msk' => $jam_msk,
        // 		'id_khd' => 1,
        // 		'id_status' => 1,
        // 	);
        // 	$this->Scan_model->absen_masuk($data);
        // 	$this->session->set_flashdata('messageAlert', $this->messageAlert('success', 'absen masuk'));
        // 	redirect('scan/index');
        // }
    }
    function remove($id_kehadiran)
    {
        $kehadiran = $this->Scan_model->get_kehadiran($id_kehadiran);

        // check if the kehadiran exists before trying to delete it
        if (isset($kehadiran['id_kehadiran'])) {
            $this->Scan_model->delete_kehadiran($id_kehadiran);
            redirect('scan/index');
        } else
            show_error('The kehadiran you are trying to delete does not exist.');
    }
}
