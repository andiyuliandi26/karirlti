<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pendaftaran extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		//$this->load->database();
		//$this->load->library(array('ion_auth', 'form_validation'));
		$this->load->helper(array('url', 'language'));
		$this->load->helper(array('form', 'url'));
		
	}
	
	public function index()
	{
		$getSetting = $this->Appsetting_model->get_setting();		
		$today = new DateTime("now");
		$openRekrut = new DateTime($getSetting->tanggal_pembukaan);
		$closedRekrut = new DateTime($getSetting->tanggal_penutupan);
		
		$getPosisi = $this->Posisi_model->get_as_list();
		$getUniversitas = $this->Instansi_model->get_all_instansi_as_list();
		$getJurusan = $this->db->get("setup_jurusan");
		$getJenjang = $this->db->get("setup_pendidikan");
		
		$data['posisiList'] = $getPosisi;
		$data['universitasList'] = $getUniversitas;
		$data['jurusanList'] = $getJurusan->result();
		$data['jenjangList'] = $getJenjang->result();
		
		$this->load->view('layouts/header');
		if($today >= $openRekrut && $today <= $closedRekrut && $getSetting->status_rekrutmen) 
		{
			// $this->load->view('pendaftaran', $data);
			$this->load->view('pendaftaran2', $data);
		}else{
			$this->load->view('welcome_message');
		}
		$this->load->view('layouts/footer');
	}
	
	public function check_ktp(){
		$noKTP = $this->input->post('noKTP');
		$data = [];
		if($this->DataPelamar_model->check_noktp($noKTP)){
			$data = [ 'status' => false, 'errorList' => "No KTP ".$noKTP." sudah terdaftar" ];
		}
		
		echo json_encode($data);
	}
	
	public function input_data_pelamar(){
		$this->load->library('form_validation');
		$isValid =  true;	
		$error = array();	

		#region Validation Rules
		// $this->form_validation->set_rules('kode_posisi', 'Kode Posisi', 'required', array( 'required' => "Kode Posisi belum dipilih" ));
		// $this->form_validation->set_rules('no_ktp', 'No KTP', 'required|min_length[16]|max_length[16]|is_unique[data_pelamar.no_ktp]|numeric',
		// 							array(
		// 								'required' => "No KTP belum terisi",
		// 								'min_length' => "No KTP terdiri dari 16 digit angka",
		// 								'max_length' => "No KTP terdiri dari 16 digit angka",
		// 								'is_unique' => "No KTP sudah terdaftar",
		// 								'numeric' => "No KTP harus berupa angka"
		// 							));
		// $this->form_validation->set_rules('nama', 'Nama', 'required', array( 'required' => "Nama belum terisi" ));						
		// $this->form_validation->set_rules('tanggal_lahir', 'Tanggal Lahir', 'required', array( 'required' => "Tanggal Lahir belum terisi" ));
		// $this->form_validation->set_rules('tempat_lahir', 'Tempat Lahir', 'required', array( 'required' => "Tempat Lahir belum terisi" ));
		// $this->form_validation->set_rules('jenis_kelamin', 'Jenis Kelamin', 'required', array( 'required' => "Jenis Kelamin belum terisi" ));
		// $this->form_validation->set_rules('agama', 'Jenis Kelamin', 'required', array( 'required' => "Agama belum dipilih" ));
		// $this->form_validation->set_rules('status_perkawinan', 'Jenis Kelamin', 'required', array( 'required' => "Status perkawinan belum dipilih" ));
		// $this->form_validation->set_rules('foto_url', 'Foto', 'required', array( 'required' => "Silahkah Upload foto Anda" ));
		// $this->form_validation->set_rules('cv_url', 'CV', 'required', array( 'required' => "Silahkah Upload CV Anda" ));
		// $this->form_validation->set_rules('info_loker', 'Info Loker', 'required', array( 'required' => "Silahkah pilih Info loker" ));
		// $this->form_validation->set_rules('no_handphone', 'No HP', 'required|numeric',
		// 							array(
		// 								'required' => "No Handphone belum terisi",
		// 								'numeric' => "No Handphone harus berupa angka"
		// 							));
		// $this->form_validation->set_rules('email', 'Email', 'required|valid_email',
		// 						array(
		// 							'required' => "Email belum terisi",
		// 							'valid_email' => "Format email salah"
		// 						));
		// $this->form_validation->set_rules('domisili', 'Domisili', 'required', array( 'required' => "Domisili belum terisi" ));
		// $this->form_validation->set_rules('alamat_asli', 'Alamat Asli', 'required', array( 'required' => "Alamat Asli belum terisi" ));
		$this->form_validation->set_rules('universitas', 'Universitas', 'required', array( 'required' => "Universitas belum ditambahkan" ));
		// $this->form_validation->set_rules('jurusan', 'Jurusan', 'required', array( 'required' => "Jurusan belum ditambahkan" ));
		// $this->form_validation->set_rules('jenjang', 'Jenjang', 'required', array( 'required' => "Jenjang belum ditambahkan" ));
		// $this->form_validation->set_rules('no_ijazah', 'No Ijazah', 'required', array( 'required' => "No Ijazah belum ditambahkan" ));
		// $this->form_validation->set_rules('ipk', 'IPK', 'required|less_than_equal_to[4]|decimal', 
		// 					array( 
		// 						'required' => "IPK belum ditambahkan",
		// 						'less_than_equal_to' => "IPK tidak boleh melebihi 4",
		// 						'decimal' => "IPK harus berupa decimal"
		// 				 	));
		// $this->form_validation->set_rules('tahun_lulus', 'Tahun Lulus', 'required|max_length[4]', 
		// 					array( 
		// 						'required' => "Tahun lulus belum ditambahkan", 
		// 						'max_length' => "Tahun lulus maksimal 4 digit",
		// 					));

		#endregion

		#region Assign Value from Post
			// $kodePosisi = $this->input->post('kode_posisi');
			// $noKTP = $this->input->post('no_ktp');
			// $nama = $this->input->post('nama');
			// $tempatLahir = $this->input->post('tempat_lahir');
			// $tanggalLahir = $this->input->post('tanggal_lahir');
			// $jenisKelamin = $this->input->post('jenis_kelamin');
			// $agama = $this->input->post('agama');
			// $statusPerkawinan = $this->input->post('status_perkawinan');
			// $fotoUrl = $this->input->post('foto_url');
			// $cvUrl = $this->input->post('cv_url');
			// $infoLoker = $this->input->post('info_loker');
			// $noHandphone = $this->input->post('no_handphone');
			// $email = $this->input->post('email');
			// $domisili = $this->input->post('domisili');
			// $alamatAsli = $this->input->post('alamat_asli');
			// $universitas = $this->input->post('universitas');
			$test = $this->input->post('test');
			// $jurusan = $this->input->post('jurusan');
			// $jenjang = $this->input->post('jenjang');
			// $ipk = $this->input->post('ipk');
			// $tahunLulus = $this->input->post('tahun_lulus');
			// $noIjazah = $this->input->post('no_ijazah');
			//var_dump($universitas);
			//print_r($this->input->post());
		#endregion

		#region Action
			if ($this->form_validation->run() == FALSE)
			{
				$data = [ 'status' => false, 'errorList' => validation_errors() ];
			}else{
				$data = [ 'status' => true, 'errorList' => $this->input->post('test') ];				
			}
		
		#endregion
		echo json_encode($data);
	}

	public function upload_foto(){
		#region upload foto
		$config['upload_path']  = './assets/documents/foto';
		$config['allowed_types']= 'jpg|png';
		$config['max_size']     = 1024;
		$config['file_name']	= "Foto-".$this->input->post('no_ktp');
		$config['overwrite'] = true;
		// $config['encrypt_name'] = true;

		$this->load->library('upload', $config);

		if ( !$this->upload->do_upload('input_foto'))
		{
			$data = [ 'status' => false, 'errorList' => $this->upload->display_errors() ];
			//$isValid = false;
		}
		else
		{
			//$data = array('upload_data' => $this->upload->data());
			$data = [ 'status' => true, 'errorList' => $this->upload->data() ];
			//$isValid = true;
		}

		echo json_encode($data);	
	}

	public function upload_cv(){
		#region upload cv
		$config['upload_path']  = './assets/documents/cv';
		$config['allowed_types']= 'pdf';
		$config['max_size']     = 512;
		$config['file_name']	= "CV-".$this->input->post('no_ktp');
		$config['overwrite'] = true;
		// $config['encrypt_name'] = true;

		$this->load->library('upload', $config);

		if ( !$this->upload->do_upload('input_cv'))
		{
			$data = [ 'status' => false, 'errorList' => $this->upload->display_errors('','') ];
		}
		else
		{
			$data = [ 'status' => true, 'errorList' => $this->upload->data() ];
		}

		echo json_encode($data);	
	}
}
?>