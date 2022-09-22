<?php
/* 
 * Generated by CRUDigniter v3.2 
 * www.crudigniter.com
 */

class Jamaah_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    /*
     * Get luasan by id_luasan
     */
    function get_jamaah($id_jamaah)
    {
        return $this->db->get_where('jamaah', array('id_jamaah' => $id_jamaah))->row_array();
    }

    /*
     * Get all jamaah count
     */
    function get_all_jamaah_count()
    {
        $this->db->from('jamaah');
        return $this->db->count_all_results();
    }

    function get_jamaah_by_nik($nik)
    {
        $this->db->order_by('jamaah.id_jamaah', 'asc');
        return $this->db->get_where('jamaah', array('nik' => $nik))->row_array();
    }

    function get_jamaah_by_uuid($uuid)
    {
        $this->db->order_by('jamaah.id_jamaah', 'asc');
        return $this->db->get_where('jamaah', array('uuid' => $uuid))->row_array();
    }

    /*
     * Get all jamaah
     */
    function get_all_jamaah($params = array())
    {
        $this->db->order_by('jamaah.id_jamaah', 'asc');
        $this->db->join('tbl_users', 'tbl_users.user_id=jamaah.created_by', 'left');
        return $this->db->get('jamaah')->result_array();
    }

    function get_all_jamaah_by_cabang($user_id)
    {
        $this->db->order_by('jamaah.id_jamaah', 'asc');
        return $this->db->get_where('jamaah', array('created_by' => $user_id))->result_array();
    }

    /*
     * function to add new jamaah
     */
    function add_jamaah($params, $qr_code, $gambar)
    {
        $this->db->set('nik', $params['nik']);
        $this->db->set('nama_jamaah', $params['nama_jamaah']);
        $this->db->set('nomor_telepon', $params['nomor_telepon']);
        $this->db->set('jenis_kelamin', $params['jenis_kelamin']);
        $this->db->set('grup_keberangkatan', $params['grup_keberangkatan']);
        $this->db->set('alamat', $params['alamat']);
        $this->db->set('nomor_paspor', $params['nomor_paspor']);
        $this->db->set('paket', $params['paket']);
        $this->db->set('jamaah_img', $gambar);
        $this->db->set('qr_code', $qr_code);
        $this->db->set('created_by', $params['created_by']);
        $this->db->insert('jamaah');
    }

    /*
     * function to update jamaah
     */
    function update_jamaah($id_jamaah, $params)
    {
        $this->db->where('id_jamaah', $id_jamaah);
        return $this->db->update('jamaah', $params);
    }

    function update_scan($result_code, $params)
    {
        $this->db->where('uuid', $result_code);
        return $this->db->update('jamaah', $params);
    }

    /*
     * function to delete jamaah
     */
    function delete_jamaah($id_jamaah)
    {
        return $this->db->delete('jamaah', array('id_jamaah' => $id_jamaah));
    }
}
