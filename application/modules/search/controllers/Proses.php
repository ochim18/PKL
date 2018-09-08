<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Proses extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		date_default_timezone_set("Asia/Jakarta");

		$this->load->model('databuku/keluarga_model');
		$this->load->model('katadasar_model');
		$this->load->model('stopword_model');
	}

	public $fitur = array();


	public function objectKeArray($object) // merubah object menjadi array
	{
	    $array = array();
	    if (is_object($object)) {
	        $array = get_object_vars($object);
	    }
	    return $array;
	}

	public function index() // merubah data set menjadi data array
	{ 
		$data = $this->keluarga_model->gets_view();
		
		foreach ($data as $key => $value) {
			$dataArray[$value->buku_id] = $this->frequen($this->token(preg_replace("/\r|\n/", " ",$value->judul." ".$value->kategori_nama." ".$value->penerbit." ".$value->penerbit." ".$value->abstract." ".$value->keyword." ".$value->pengarang)),1);
		}
		reset($dataArray);
		$index = key($dataArray);
		foreach ($dataArray as $key) {
				$dataArray[$index] = $this->frequen_fix($key, 1);
				$index = key($dataArray);
				next($dataArray);
		}
		reset($dataArray);
		$index = key($dataArray);
		foreach ($dataArray as $key) {
				$tf_weight[$index] = $this->tf_weight($key);
				$index = key($dataArray);
				next($dataArray);
		}
		$df = $this->df($dataArray);
		$idft = $this->idft($df, count($dataArray));

		reset($tf_weight);
		$index = key($tf_weight);
		foreach ($tf_weight as $key) {
				$wtd[$index] = $this->wtd($key, $idft);
				$index = key($tf_weight);
				next($tf_weight);
		}

		$normal_wtd = $this->normal_wtd($wtd);
		$query=array();
		$tf_weight_query=array();
		$wtd_query=array();
		if ($get = $this->input->get()) {
			if (isset($get['search'])) {
				$query = $this->frequen($this->token($get['search']),0);
				$tf_weight_query = $this->tf_weight($query);
				$wtd_query = $this->normal_wtd_query($this->wtd($tf_weight_query, $idft));
			}
		}
		$cosine=$this->cosine_similarity($normal_wtd, $wtd_query);
		arsort($cosine);
		$id_buku=array();
		reset($cosine);
		$index = key($cosine);
		foreach ($cosine as $key => $value) {
			array_push($id_buku, $index);
			$index = key($cosine);
			next($cosine);
		}
			/*$json = json_encode($normal_wtd);
                        echo $json;
                        die();*/
        if(count($id_buku) != 0){
			$params = array(
				'title' 		=> "Data Keluarga",			
				'data_buku'		=> $this->keluarga_model->gets_in($id_buku),
			);
		}else{
			$params = array(
				'title' 		=> "Data Keluarga",			
			);
		}
		

		$this->load->view('simple-header', $params);
		$this->load->view('search_view', $params);
		$this->load->view('simple-footer', $params);
	}

	public function cosine_similarity($wtd, $wtd_query){
		$cosine=array();
		reset($wtd);
		$index = key($wtd);
		foreach ($wtd as $key) {
			foreach ($wtd_query as $key1 => $value) {
				if($key[$key1] != 0){
					if(isset($cosine[$index]))
						$cosine[$index] += $key[$key1]*$value;
					else
						$cosine[$index] = $key[$key1]*$value; 
				}
			}
		$index = key($wtd);
		next($wtd);
		}
		return $cosine;
	}

	public function frequen($data, $check) // membuat frequency dalam data array
	{
		$fituur=$this->fitur;
		$str_count=array();
		foreach($data as $key => $value)
		{
    		if ($check == 1) {
	    		if(isset($str_count[$value]))
	        		$str_count[$value]++;
	    		else{
	        		$str_count[$value]=1;
	        		if(!isset($fituur[$value]))
	        			$fituur[$value] = 0;
	    		}
    		}elseif(isset($fituur[$value])){
    			if(isset($str_count[$value]))
	        		$str_count[$value]++;
	    		else{
	        		$str_count[$value]=1;
	    		}
    		}
		}
		$this->fitur = $fituur;
		return $str_count;
	}
	public function frequen_fix($data, $check) // menambahkan kata yang ada di dokumen lain tapi dengan frequen nol
	{
		$fituur = $this->fitur;
		$dataa =$data;
			foreach($fituur as $key => $value)
			{
	    		if(!isset($dataa[$key])){
	        		$dataa[$key]=0;
	    		}
	        	else if($check == 1){
	        		if($value == 0)
	        			$fituur[$key]=1;
	        		else
        				$fituur[$key]++;
        		}
			}
		$this->fitur = $fituur;
		return $dataa;
	}
	public function token($kalimat) // menghilangkan tanda baca, stemming, tokenisasi (pre-processing)
	{
		$teks = strtolower($kalimat);
		$tokenKarakter=array('?','?',' ','/',',','?','.',':',';',',','!','[',']','{','}','(',')','-','_','+','=','<','>','\'','"','\\','@','#','$','%','^','&','*','`','~','0','1','2','3','4','5','6','7','8','9','â?','?','?');
		$teks= str_replace($tokenKarakter,' ',$teks);
		$tok = strtok($teks, "\n\t");
	 
		while ($tok !== false) {
			$teks = $tok;
			$tok = strtok("\n\t");
		}
		$data=array();
		$split = explode(' ',$teks);
		$result = array();
		foreach($split as $key => $kata){
			if($kata != ""){
				$stem = $this->proses1(trim($kata));
				if (!$this->stopword_model->katastop_exists($stem)) {
					$result[] = $stem;
				}
			}
		}
		return $result;
	}

	public function tf_weight($data)
	{
		$tf_weight = $data;
		foreach ($tf_weight as $key => $value) {
			if($value > 0){
				$tf_weight[$key] = 1 + log10($value);
			}
		}
		return $tf_weight;
	}

	public function df($data){
		$df=array();
		foreach ($data as $key) {
			foreach ($key as $key1 => $value) {
				if ($value > 0) {
					if(isset($df[$key1]))
						$df[$key1]++;
					else 
						$df[$key1]=1;
				}
			}
		}
		return $df;
	}

	public function idft($df, $n){
		$idft = array();
		foreach ($df as $key => $value) 
		{
			$idft[$key] = log10($n/$value);
		}
		return $idft;
	}

	public function wtd($wtf, $idft){
		$wtd = $wtf;
		foreach ($wtd as $key => $value ) {
			$wtd[$key] = $value*$idft[$key];
		}
		return $wtd;
	}

	public function normal_wtd($wtd){
		$normal_wtd = $wtd;
		$pembagi=array();
		reset($normal_wtd);
		$index = key($normal_wtd);
		foreach ($normal_wtd as $key) {
			foreach ($key as $key1 => $value) {
				if(isset($pembagi[$index]))
					$pembagi[$index] += pow($value, 2);
				else
					$pembagi[$index] = pow($value, 2);
			}
			$index = key($normal_wtd);
			next($normal_wtd);
		}
		foreach ($pembagi as $key => $value) {
			$pembagi[$key] = sqrt($value);
		}
		reset($normal_wtd);
		$index = key($normal_wtd);
		foreach ($normal_wtd as $key) {
			foreach ($key as $key1 => $value) {
				$normal_wtd[$index][$key1] = $value/$pembagi[$index];
			}
			$index = key($normal_wtd);
			next($normal_wtd);
		}
		return $normal_wtd;
	}

	public function normal_wtd_query($wtd){
		$normal_wtd = $wtd;
		$pembagi=0;
		foreach ($normal_wtd as $key => $value) 
		{
				$pembagi += pow($value, 2);
		}
			$pembagi = sqrt($pembagi);
		foreach ($normal_wtd as $key => $value) {
			if($value > 0)
				$normal_wtd[$key] = $value/$pembagi;
		}
		return $normal_wtd;
	}

	function cekKamus($kata){
	// cari di database
		$sql = "SELECT * from tb_katadasar where katadasar ='$kata' LIMIT 1";
	//echo $sql.'<br/>';
		$result = $this->katadasar_model->gets_kata($kata);;
		if(count($result)==1){
			return true; // True jika ada
		}else{
			return false; // jika tidak ada FALSE
		}
	}
	 
	// Hapus Inflection Suffixes (?-lah?, ?-kah?, ?-ku?, ?-mu?, atau ?-nya?)
	function Del_Inflection_Suffixes($kata){
		$kataAsal = $kata;
		if(preg_match('/([km]u|nya|[kl]ah|pun)$/',$kata)){ // Cek Inflection Suffixes
			$__kata = preg_replace('/([km]u|nya|[kl]ah|pun)$/','',$kata);
				if(preg_match('/([klt]ah|pun)$/',$kata)){ // Jika berupa particles (?-lah?, ?-kah?, ?-tah? atau ?-pun?)
					if(preg_match('/([km]u|nya)$/',$__kata)){ // Hapus Possesive Pronouns (?-ku?, ?-mu?, atau ?-nya?)
						$__kata__ = preg_replace('/([km]u|nya)$/','',$__kata);
							return $__kata__;
					}
				}
			return $__kata;
		}
		return $kataAsal;
	}
	 
	function Cek_Rule_Precedence($kata){
		if(preg_match('/^(be)[[:alpha:]]+(lah|an)$/',$kata)){ // be- dan -i
			return true;
		}
		if(preg_match('/^(di|([mpt]e))[[:alpha:]]+(i)$/',$kata)){ // di- dan -an
			return true;
		}
		return false;
	}
	// Cek Prefix Disallowed Sufixes (Kombinasi Awalan dan Akhiran yang tidak diizinkan)
	function Cek_Prefix_Disallowed_Sufixes($kata){
		if(preg_match('/^(be)[[:alpha:]]+(i)$/',$kata)){ // be- dan -i
			return true;
		}
		if(preg_match('/^(di)[[:alpha:]]+(an)$/',$kata)){ // di- dan -an
			return true;
		}
		if(preg_match('/^(ke)[[:alpha:]]+(i|kan)$/',$kata)){ // ke- dan -i,-kan
			return true;
		}
		if(preg_match('/^(me)[[:alpha:]]+(an)$/',$kata)){ // me- dan -an
			return true;
		}
		if(preg_match('/^(se)[[:alpha:]]+(i|kan)$/',$kata)){ // se- dan -i,-kan
			return true;
		}
		return false;
	}
	 
	// Hapus Derivation Suffixes (?-i?, ?-an? atau ?-kan?)
	function Del_Derivation_Suffixes($kata){
		$kataAsal = $kata;
		if(preg_match('/(kan)$/',$kata)){ // Cek Suffixes
			$__kata = preg_replace('/(kan)$/','',$kata);
			if($this->cekKamus($__kata)){ // Cek Kamus
				return $__kata;
			}
		}
		if(preg_match('/(an|i)$/',$kata)){ // cek -kan
			$__kata__ = preg_replace('/(an|i)$/','',$kata);
			if($this->cekKamus($__kata__)){ // Cek Kamus
				return $__kata__;
			}
		}
		if($this->Cek_Prefix_Disallowed_Sufixes($kata)){
			return $kataAsal;
		}
		return $kataAsal;
	}
	 
	// Hapus Derivation Prefix (?di-?, ?ke-?, ?se-?, ?te-?, ?be-?, ?me-?, atau ?pe-?)
	function Del_Derivation_Prefix($kata){
		$kataAsal = $kata;
	/* ------ Tentukan Tipe Awalan ------------*/
		if(preg_match('/^(di|[ks]e)\S{1,}/',$kata)){ // Jika di-,ke-,se-
			$__kata = preg_replace('/^(di|[ks]e)/','',$kata);
			if($this->cekKamus($__kata)){
				return $__kata; // Jika ada balik
			}
			$__kata__ = $this->Del_Derivation_Suffixes($__kata);
			if($this->cekKamus($__kata__)){
				return $__kata__;
			}
		}
		if(preg_match('/^([^aiueo])e\\1[aiueo]\S{1,}/i',$kata)){ // aturan  37
			$__kata = preg_replace('/^([^aiueo])e/','',$kata);
			if($this->cekKamus($__kata)){
				return $__kata; // Jika ada balik
			}
			$__kata__ = $this->Del_Derivation_Suffixes($__kata);
			if($this->cekKamus($__kata__)){
				return $__kata__;
			}
		}
		if(preg_match('/^([tmbp]e)\S{1,}/',$kata)){ //Jika awalannya adalah ?te-?, ?me-?, ?be-?, atau ?pe-?
	/*------------ Awalan ?be-?, ---------------------------------------------*/
			if(preg_match('/^(be)\S{1,}/',$kata)){ // Jika awalan ?be-?,
				if(preg_match('/^(ber)[aiueo]\S{1,}/',$kata)){ // aturan 1.
					$__kata = preg_replace('/^(ber)/','',$kata);
					if($this->cekKamus($__kata)){
						return $__kata; // Jika ada balik
					}
					$__kata__ = $this->Del_Derivation_Suffixes($__kata);
					if($this->cekKamus($__kata__)){
						return $__kata__;
					}
					$__kata = preg_replace('/^(ber)/','r',$kata);
					if($this->cekKamus($__kata)){
						return $__kata; // Jika ada balik
					}
					$__kata__ = $this->Del_Derivation_Suffixes($__kata);
					if($this->cekKamus($__kata__)){
						return $__kata__;
					}
				}
	 
				if(preg_match('/^(ber)[^aiueor][[:alpha:]](?!er)\S{1,}/',$kata)){ //aturan  2.
					$__kata = preg_replace('/^(ber)/','',$kata);
					if($this->cekKamus($__kata)){
						return $__kata; // Jika ada balik
					}
					$__kata__ = $this->Del_Derivation_Suffixes($__kata);
					if($this->cekKamus($__kata__)){
						return $__kata__;
					}
				}
	 
				if(preg_match('/^(ber)[^aiueor][[:alpha:]]er[aiueo]\S{1,}/',$kata)){ //aturan  3.
					$__kata = preg_replace('/^(ber)/','',$kata);
				if($this->cekKamus($__kata)){
					return $__kata; // Jika ada balik
				}
				$__kata__ = $this->Del_Derivation_Suffixes($__kata);
				if($this->cekKamus($__kata__)){
					return $__kata__;
				}
			}
	 
			if(preg_match('/^belajar\S{0,}/',$kata)){ //aturan  4.
				$__kata = preg_replace('/^(bel)/','',$kata);
				if($this->cekKamus($__kata)){
					return $__kata; // Jika ada balik
				}
				$__kata__ = $this->Del_Derivation_Suffixes($__kata);
				if($this->cekKamus($__kata__)){
					return $__kata__;
				}
			}
	 
			if(preg_match('/^(be)[^aiueolr]er[^aiueo]\S{1,}/',$kata)){ //aturan  5.
				$__kata = preg_replace('/^(be)/','',$kata);
				if($this->cekKamus($__kata)){
					return $__kata; // Jika ada balik
				}
				$__kata__ = $this->Del_Derivation_Suffixes($__kata);
				if($this->cekKamus($__kata__)){
					return $__kata__;
				}
			}
		}
	/*------------end ?be-?, ---------------------------------------------*/
	/*------------ Awalan ?te-?, ---------------------------------------------*/
		if(preg_match('/^(te)\S{1,}/',$kata)){ // Jika awalan ?te-?,
	 
			if(preg_match('/^(terr)\S{1,}/',$kata)){
				return $kata;
			}
			if(preg_match('/^(ter)[aiueo]\S{1,}/',$kata)){ // aturan 6.
				$__kata = preg_replace('/^(ter)/','',$kata);
				if($this->cekKamus($__kata)){
					return $__kata; // Jika ada balik
				}
				$__kata__ = $this->Del_Derivation_Suffixes($__kata);
				if($this->cekKamus($__kata__)){
					return $__kata__;
				}
				$__kata = preg_replace('/^(ter)/','r',$kata);
				if($this->cekKamus($__kata)){
					return $__kata; // Jika ada balik
				}
				$__kata__ = $this->Del_Derivation_Suffixes($__kata);
				if($this->cekKamus($__kata__)){
					return $__kata__;
				}
			}
		 
			if(preg_match('/^(ter)[^aiueor]er[aiueo]\S{1,}/',$kata)){ // aturan 7.
				$__kata = preg_replace('/^(ter)/','',$kata);
				if($this->cekKamus($__kata)){
					return $__kata; // Jika ada balik
				}
				$__kata__ = $this->Del_Derivation_Suffixes($__kata);
				if($this->cekKamus($__kata__)){
					return $__kata__;
				}
			}
			if(preg_match('/^(ter)[^aiueor](?!er)\S{1,}/',$kata)){ // aturan 8.
				$__kata = preg_replace('/^(ter)/','',$kata);
				if($this->cekKamus($__kata)){
					return $__kata; // Jika ada balik
				}
				$__kata__ = $this->Del_Derivation_Suffixes($__kata);
				if($this->cekKamus($__kata__)){
					return $__kata__;
				}
			}
			if(preg_match('/^(te)[^aiueor]er[aiueo]\S{1,}/',$kata)){ // aturan 9.
				$__kata = preg_replace('/^(te)/','',$kata);
				if($this->cekKamus($__kata)){
					return $__kata; // Jika ada balik
				}
				$__kata__ = $this->Del_Derivation_Suffixes($__kata);
				if($this->cekKamus($__kata__)){
					return $__kata__;
				}
			}
		 
			if(preg_match('/^(ter)[^aiueor]er[^aiueo]\S{1,}/',$kata)){ // aturan  35 belum bisa
				$__kata = preg_replace('/^(ter)/','',$kata);
				if($this->cekKamus($__kata)){
					return $__kata; // Jika ada balik
				}
		 
				$__kata__ = $this->Del_Derivation_Suffixes($__kata);
				if($this->cekKamus($__kata__)){
					return $__kata__;
				}
			}
		} 
	/*------------end ?te-?, ---------------------------------------------*/
	/*------------ Awalan ?me-?, ---------------------------------------------*/
		if(preg_match('/^(me)\S{1,}/',$kata)){ // Jika awalan ?me-?,
	 
			if(preg_match('/^(me)[lrwyv][aiueo]/',$kata)){ // aturan 10
				$__kata = preg_replace('/^(me)/','',$kata);
			if($this->cekKamus($__kata)){
				return $__kata; // Jika ada balik
			}
			$__kata__ = $this->Del_Derivation_Suffixes($__kata);
			if($this->cekKamus($__kata__)){
				return $__kata__;
			}
		}
	 
		if(preg_match('/^(mem)[bfvp]\S{1,}/',$kata)){ // aturan 11.
			$__kata = preg_replace('/^(mem)/','',$kata);
			if($this->cekKamus($__kata)){
				return $__kata; // Jika ada balik
			}
			$__kata__ = $this->Del_Derivation_Suffixes($__kata);
			if($this->cekKamus($__kata__)){
				return $__kata__;
			}
		}
	if(preg_match('/^(mempe)\S{1,}/',$kata)){ // aturan 12
	$__kata = preg_replace('/^(mem)/','pe',$kata);
	 
	if($this->cekKamus($__kata)){
	 
	return $__kata; // Jika ada balik
	}
	$__kata__ = $this->Del_Derivation_Suffixes($__kata);
	if($this->cekKamus($__kata__)){
	return $__kata__;
	}
	}
	if (preg_match('/^(mem)((r[aiueo])|[aiueo])\S{1,}/', $kata)){//aturan 13
	$__kata = preg_replace('/^(mem)/','m',$kata);
	if($this->cekKamus($__kata)){
	return $__kata; // Jika ada balik
	}
	$__kata__ = $this->Del_Derivation_Suffixes($__kata);
	if($this->cekKamus($__kata__)){
	return $__kata__;
	}
	$__kata = preg_replace('/^(mem)/','p',$kata);
	if($this->cekKamus($__kata)){
	return $__kata; // Jika ada balik
	}
	$__kata__ = $this->Del_Derivation_Suffixes($__kata);
	if($this->cekKamus($__kata__)){
	return $__kata__;
	}
	}
	 
	if(preg_match('/^(men)[cdjszt]\S{1,}/',$kata)){ // aturan 14.
	$__kata = preg_replace('/^(men)/','',$kata);
	if($this->cekKamus($__kata)){
	return $__kata; // Jika ada balik
	}
	$__kata__ = $this->Del_Derivation_Suffixes($__kata);
	if($this->cekKamus($__kata__)){
	return $__kata__;
	}
	}
	 
	if (preg_match('/^(men)[aiueo]\S{1,}/',$kata)){//aturan 15
	$__kata = preg_replace('/^(men)/','n',$kata);
	if($this->cekKamus($__kata)){
	return $__kata; // Jika ada balik
	}
	$__kata__ = $this->Del_Derivation_Suffixes($__kata);
	if($this->cekKamus($__kata__)){
	return $__kata__;
	}
	$__kata = preg_replace('/^(men)/','t',$kata);
	if($this->cekKamus($__kata)){
	return $__kata; // Jika ada balik
	}
	$__kata__ = $this->Del_Derivation_Suffixes($__kata);
	if($this->cekKamus($__kata__)){
	return $__kata__;
	}
	}
	 
	if(preg_match('/^(meng)[ghqk]\S{1,}/',$kata)){ // aturan 16.
	$__kata = preg_replace('/^(meng)/','',$kata);
	if($this->cekKamus($__kata)){
	return $__kata; // Jika ada balik
	}
	$__kata__ = $this->Del_Derivation_Suffixes($__kata);
	if($this->cekKamus($__kata__)){
	return $__kata__;
	}
	}
	 
	if(preg_match('/^(meng)[aiueo]\S{1,}/',$kata)){ // aturan 17
	$__kata = preg_replace('/^(meng)/','',$kata);
	if($this->cekKamus($__kata)){
	return $__kata; // Jika ada balik
	}
	$__kata__ = $this->Del_Derivation_Suffixes($__kata);
	if($this->cekKamus($__kata__)){
	return $__kata__;
	}
	$__kata = preg_replace('/^(meng)/','k',$kata);
	if($this->cekKamus($__kata)){
	return $__kata; // Jika ada balik
	}
	$__kata__ = $this->Del_Derivation_Suffixes($__kata);
	if($this->cekKamus($__kata__)){
	return $__kata__;
	}
	$__kata = preg_replace('/^(menge)/','',$kata);
	if($this->cekKamus($__kata)){
	return $__kata; // Jika ada balik
	}
	$__kata__ = $this->Del_Derivation_Suffixes($__kata);
	if($this->cekKamus($__kata__)){
	return $__kata__;
	}
	}
	 
	if(preg_match('/^(meny)[aiueo]\S{1,}/',$kata)){ // aturan 18.
	$__kata = preg_replace('/^(meny)/','s',$kata);
	if($this->cekKamus($__kata)){
	return $__kata; // Jika ada balik
	}
	$__kata__ = $this->Del_Derivation_Suffixes($__kata);
	if($this->cekKamus($__kata__)){
	return $__kata__;
	}
	$__kata = preg_replace('/^(me)/','',$kata);
	if($this->cekKamus($__kata)){
	return $__kata; // Jika ada balik
	}
	$__kata__ = $this->Del_Derivation_Suffixes($__kata);
	if($this->cekKamus($__kata__)){
	return $__kata__;
	}
	}
	}
	/*------------end ?me-?, ---------------------------------------------*/
	 
	/*------------ Awalan ?pe-?, ---------------------------------------------*/
	if(preg_match('/^(pe)\S{1,}/',$kata)){ // Jika awalan ?pe-?,
	 
	if(preg_match('/^(pe)[wy]\S{1,}/',$kata)){ // aturan 20.
	$__kata = preg_replace('/^(pe)/','',$kata);
	if($this->cekKamus($__kata)){
	return $__kata; // Jika ada balik
	}
	$__kata__ = $this->Del_Derivation_Suffixes($__kata);
	if($this->cekKamus($__kata__)){
	return $__kata__;
	}
	}
	 
	if(preg_match('/^(per)[aiueo]\S{1,}/',$kata)){ // aturan 21
	$__kata = preg_replace('/^(per)/','',$kata);
	if($this->cekKamus($__kata)){
	return $__kata; // Jika ada balik
	}
	$__kata__ = $this->Del_Derivation_Suffixes($__kata);
	if($this->cekKamus($__kata__)){
	return $__kata__;
	}
	$__kata = preg_replace('/^(per)/','r',$kata);
	if($this->cekKamus($__kata)){
	return $__kata; // Jika ada balik
	}
	$__kata__ = $this->Del_Derivation_Suffixes($__kata);
	if($this->cekKamus($__kata__)){
	return $__kata__;
	}
	}
	if(preg_match('/^(per)[^aiueor][[:alpha:]](?!er)\S{1,}/',$kata)){ // aturan  23
	$__kata = preg_replace('/^(per)/','',$kata);
	if($this->cekKamus($__kata)){
	return $__kata; // Jika ada balik
	}
	 
	$__kata__ = $this->Del_Derivation_Suffixes($__kata);
	if($this->cekKamus($__kata__)){
	return $__kata__;
	}
	}
	 
	if(preg_match('/^(per)[^aiueor][[:alpha:]](er)[aiueo]\S{1,}/',$kata)){ // aturan  24
	$__kata = preg_replace('/^(per)/','',$kata);
	if($this->cekKamus($__kata)){
	return $__kata; // Jika ada balik
	}
	 
	$__kata__ = $this->Del_Derivation_Suffixes($__kata);
	if($this->cekKamus($__kata__)){
	return $__kata__;
	}
	}
	 
	if(preg_match('/^(pem)[bfv]\S{1,}/',$kata)){ // aturan  25
	$__kata = preg_replace('/^(pem)/','',$kata);
	if($this->cekKamus($__kata)){
	return $__kata; // Jika ada balik
	}
	 
	$__kata__ = $this->Del_Derivation_Suffixes($__kata);
	if($this->cekKamus($__kata__)){
	return $__kata__;
	}
	}
	 
	if(preg_match('/^(pem)(r[aiueo]|[aiueo])\S{1,}/',$kata)){ // aturan  26
	$__kata = preg_replace('/^(pem)/','m',$kata);
	if($this->cekKamus($__kata)){
	return $__kata; // Jika ada balik
	}
	 
	$__kata__ = $this->Del_Derivation_Suffixes($__kata);
	if($this->cekKamus($__kata__)){
	return $__kata__;
	}
	$__kata = preg_replace('/^(pem)/','p',$kata);
	if($this->cekKamus($__kata)){
	return $__kata; // Jika ada balik
	}
	 
	$__kata__ = $this->Del_Derivation_Suffixes($__kata);
	if($this->cekKamus($__kata__)){
	return $__kata__;
	}
	}
	 
	if(preg_match('/^(pen)[cdjzt]\S{1,}/',$kata)){ // aturan  27
	$__kata = preg_replace('/^(pen)/','',$kata);
	if($this->cekKamus($__kata)){
	return $__kata; // Jika ada balik
	}
	 
	$__kata__ = $this->Del_Derivation_Suffixes($__kata);
	if($this->cekKamus($__kata__)){
	return $__kata__;
	}
	}
	 
	if(preg_match('/^(pen)[aiueo]\S{1,}/',$kata)){ // aturan  28
	$__kata = preg_replace('/^(pen)/','n',$kata);
	if($this->cekKamus($__kata)){
	return $__kata; // Jika ada balik
	}
	 
	$__kata__ = $this->Del_Derivation_Suffixes($__kata);
	if($this->cekKamus($__kata__)){
	return $__kata__;
	}
	$__kata = preg_replace('/^(pen)/','t',$kata);
	if($this->cekKamus($__kata)){
	return $__kata; // Jika ada balik
	}
	 
	$__kata__ = $this->Del_Derivation_Suffixes($__kata);
	if($this->cekKamus($__kata__)){
	return $__kata__;
	}
	}
	 
	if(preg_match('/^(peng)[^aiueo]\S{1,}/',$kata)){ // aturan  29
	$__kata = preg_replace('/^(peng)/','',$kata);
	if($this->cekKamus($__kata)){
	return $__kata; // Jika ada balik
	}
	 
	$__kata__ = $this->Del_Derivation_Suffixes($__kata);
	if($this->cekKamus($__kata__)){
	return $__kata__;
	}
	}
	 
	if(preg_match('/^(peng)[aiueo]\S{1,}/',$kata)){ // aturan  30
	$__kata = preg_replace('/^(peng)/','',$kata);
	if($this->cekKamus($__kata)){
	return $__kata; // Jika ada balik
	}
	 
	$__kata__ = $this->Del_Derivation_Suffixes($__kata);
	if($this->cekKamus($__kata__)){
	return $__kata__;
	}
	$__kata = preg_replace('/^(peng)/','k',$kata);
	if($this->cekKamus($__kata)){
	return $__kata; // Jika ada balik
	}
	 
	$__kata__ = $this->Del_Derivation_Suffixes($__kata);
	if($this->cekKamus($__kata__)){
	return $__kata__;
	}
	$__kata = preg_replace('/^(penge)/','',$kata);
	if($this->cekKamus($__kata)){
	return $__kata; // Jika ada balik
	}
	 
	$__kata__ = $this->Del_Derivation_Suffixes($__kata);
	if($this->cekKamus($__kata__)){
	return $__kata__;
	}
	}
	 
	if(preg_match('/^(peny)[aiueo]\S{1,}/',$kata)){ // aturan  31
	$__kata = preg_replace('/^(peny)/','s',$kata);
	if($this->cekKamus($__kata)){
	return $__kata; // Jika ada balik
	}
	 
	$__kata__ = $this->Del_Derivation_Suffixes($__kata);
	if($this->cekKamus($__kata__)){
	return $__kata__;
	}
	$__kata = preg_replace('/^(pe)/','',$kata);
	if($this->cekKamus($__kata)){
	return $__kata; // Jika ada balik
	}
	 
	$__kata__ = $this->Del_Derivation_Suffixes($__kata);
	if($this->cekKamus($__kata__)){
	return $__kata__;
	}
	}
	 
	if(preg_match('/^(pel)[aiueo]\S{1,}/',$kata)){ // aturan  32
	$__kata = preg_replace('/^(pel)/','l',$kata);
	if($this->cekKamus($__kata)){
	return $__kata; // Jika ada balik
	}
	 
	$__kata__ = $this->Del_Derivation_Suffixes($__kata);
	if($this->cekKamus($__kata__)){
	return $__kata__;
	}
	}
	 
	if (preg_match('/^(pelajar)\S{0,}/',$kata)){
	$__kata = preg_replace('/^(pel)/','',$kata);
	if($this->cekKamus($__kata)){
	return $__kata; // Jika ada balik
	}
	 
	$__kata__ = $this->Del_Derivation_Suffixes($__kata);
	if($this->cekKamus($__kata__)){
	return $__kata__;
	}
	}
	 
	if(preg_match('/^(pe)[^rwylmn]er[aiueo]\S{1,}/',$kata)){ // aturan  33
	$__kata = preg_replace('/^(pe)/','',$kata);
	if($this->cekKamus($__kata)){
	return $__kata; // Jika ada balik
	}
	 
	$__kata__ = $this->Del_Derivation_Suffixes($__kata);
	if($this->cekKamus($__kata__)){
	return $__kata__;
	}
	}
	 
	if(preg_match('/^(pe)[^rwylmn](?!er)\S{1,}/',$kata)){ // aturan  34
	$__kata = preg_replace('/^(pe)/','',$kata);
	if($this->cekKamus($__kata)){
	return $__kata; // Jika ada balik
	}
	 
	$__kata__ = $this->Del_Derivation_Suffixes($__kata);
	if($this->cekKamus($__kata__)){
	return $__kata__;
	}
	}
	 
	if(preg_match('/^(pe)[^aiueor]er[^aiueo]\S{1,}/',$kata)){ // aturan  36
	$__kata = preg_replace('/^(pe)/','',$kata);
	if($this->cekKamus($__kata)){
	return $__kata; // Jika ada balik
	}
	 
	$__kata__ = $this->Del_Derivation_Suffixes($__kata);
	if($this->cekKamus($__kata__)){
	return $__kata__;
	}
	}
	}
	}
	/*------------end ?pe-?, ---------------------------------------------*/
	/*------------ Awalan ?memper-?, ---------------------------------------------*/
	if(preg_match('/^(memper)\S{1,}/',$kata)){
	$__kata = preg_replace('/^(memper)/','',$kata);
	if($this->cekKamus($__kata)){
	return $__kata; // Jika ada balik
	}
	$__kata__ = $this->Del_Derivation_Suffixes($__kata);
	if($this->cekKamus($__kata__)){
	return $__kata__;
	}
	//*-- Cek luluh -r ----------
	$__kata = preg_replace('/^(memper)/','r',$kata);
	if($this->cekKamus($__kata)){
	return $__kata; // Jika ada balik
	}
	$__kata__ = $this->Del_Derivation_Suffixes($__kata);
	if($this->cekKamus($__kata__)){
	return $__kata__;
	}
	}
	if(preg_match('/^(mempel)\S{1,}/',$kata)){
	$__kata = preg_replace('/^(mempel)/','',$kata);
	if($this->cekKamus($__kata)){
	return $__kata; // Jika ada balik
	}
	$__kata__ = $this->Del_Derivation_Suffixes($__kata);
	if($this->cekKamus($__kata__)){
	return $__kata__;
	}
	//*-- Cek luluh -r ----------
	$__kata = preg_replace('/^(mempel)/','l',$kata);
	if($this->cekKamus($__kata)){
	return $__kata; // Jika ada balik
	}
	$__kata__ = $this->Del_Derivation_Suffixes($__kata);
	if($this->cekKamus($__kata__)){
	return $__kata__;
	}
	}
	if(preg_match('/^(menter)\S{1,}/',$kata)){
	$__kata = preg_replace('/^(menter)/','',$kata);
	if($this->cekKamus($__kata)){
	return $__kata; // Jika ada balik
	}
	$__kata__ = $this->Del_Derivation_Suffixes($__kata);
	if($this->cekKamus($__kata__)){
	return $__kata__;
	}
	//*-- Cek luluh -r ----------
	$__kata = preg_replace('/^(menter)/','r',$kata);
	if($this->cekKamus($__kata)){
	return $__kata; // Jika ada balik
	}
	$__kata__ = $this->Del_Derivation_Suffixes($__kata);
	if($this->cekKamus($__kata__)){
	return $__kata__;
	}
	}
	if(preg_match('/^(member)\S{1,}/',$kata)){
	$__kata = preg_replace('/^(member)/','',$kata);
	if($this->cekKamus($__kata)){
	return $__kata; // Jika ada balik
	}
	$__kata__ = $this->Del_Derivation_Suffixes($__kata);
	if($this->cekKamus($__kata__)){
	return $__kata__;
	}
	//*-- Cek luluh -r ----------
	$__kata = preg_replace('/^(member)/','r',$kata);
	if($this->cekKamus($__kata)){
	return $__kata; // Jika ada balik
	}
	$__kata__ = $this->Del_Derivation_Suffixes($__kata);
	if($this->cekKamus($__kata__)){
	return $__kata__;
	}
	}
	/*------------end ?diper-?, ---------------------------------------------*/
	if(preg_match('/^(diper)\S{1,}/',$kata)){
	$__kata = preg_replace('/^(diper)/','',$kata);
	if($this->cekKamus($__kata)){
	return $__kata; // Jika ada balik
	}
	$__kata__ = $this->Del_Derivation_Suffixes($__kata);
	if($this->cekKamus($__kata__)){
	return $__kata__;
	}
	/*-- Cek luluh -r ----------*/
	$__kata = preg_replace('/^(diper)','r',$kata);
	if($this->cekKamus($__kata)){
	return $__kata; // Jika ada balik
	}
	$__kata__ = $this->Del_Derivation_Suffixes($__kata);
	if($this->cekKamus($__kata__)){
	return $__kata__;
	}
	}
	/*------------end ?diper-?, ---------------------------------------------*/
	/*------------end ?diter-?, ---------------------------------------------*/
	if(preg_match('/^(diter)\S{1,}/',$kata)){
	$__kata = preg_replace('/^(diter)/','',$kata);
	if($this->cekKamus($__kata)){
	return $__kata; // Jika ada balik
	}
	$__kata__ = $this->Del_Derivation_Suffixes($__kata);
	if($this->cekKamus($__kata__)){
	return $__kata__;
	}
	/*-- Cek luluh -r ----------*/
	$__kata = preg_replace('/^(diter)','r',$kata);
	if($this->cekKamus($__kata)){
	return $__kata; // Jika ada balik
	}
	$__kata__ = $this->Del_Derivation_Suffixes($__kata);
	if($this->cekKamus($__kata__)){
	return $__kata__;
	}
	}
	/*------------end ?diter-?, ---------------------------------------------*/
	/*------------end ?dipel-?, ---------------------------------------------*/
	if(preg_match('/^(dipel)\S{1,}/',$kata)){
	$__kata = preg_replace('/^(dipel)/','l',$kata);
	if($this->cekKamus($__kata)){
	return $__kata; // Jika ada balik
	}
	$__kata__ = $this->Del_Derivation_Suffixes($__kata);
	if($this->cekKamus($__kata__)){
	return $__kata__;
	}
	/*-- Cek luluh -l----------*/
	$__kata = preg_replace('/^(dipel)/','',$kata);
	if($this->cekKamus($__kata)){
	return $__kata; // Jika ada balik
	}
	$__kata__ = $this->Del_Derivation_Suffixes($__kata);
	if($this->cekKamus($__kata__)){
	return $__kata__;
	}
	}
	/*------------end ?dipel-?, ---------------------------------------------*/
	if(preg_match('/^(diber)\S{1,}/',$kata)){
	$__kata = preg_replace('/^(diber)/','',$kata);
	if($this->cekKamus($__kata)){
	return $__kata; // Jika ada balik
	}
	$__kata__ = $this->Del_Derivation_Suffixes($__kata);
	if($this->cekKamus($__kata__)){
	return $__kata__;
	}
	/*-- Cek luluh -l----------*/
	$__kata = preg_replace('/^(diber)/','r',$kata);
	if($this->cekKamus($__kata)){
	return $__kata; // Jika ada balik
	}
	$__kata__ = $this->Del_Derivation_Suffixes($__kata);
	if($this->cekKamus($__kata__)){
	return $__kata__;
	}
	}
	if(preg_match('/^(keber)\S{1,}/',$kata)){
	$__kata = preg_replace('/^(keber)/','',$kata);
	if($this->cekKamus($__kata)){
	return $__kata; // Jika ada balik
	}
	$__kata__ = $this->Del_Derivation_Suffixes($__kata);
	if($this->cekKamus($__kata__)){
	return $__kata__;
	}
	/*-- Cek luluh -l----------*/
	$__kata = preg_replace('/^(keber)/','r',$kata);
	if($this->cekKamus($__kata)){
	return $__kata; // Jika ada balik
	}
	$__kata__ = $this->Del_Derivation_Suffixes($__kata);
	if($this->cekKamus($__kata__)){
	return $__kata__;
	}
	}
	if(preg_match('/^(keter)\S{1,}/',$kata)){
	$__kata = preg_replace('/^(keter)/','',$kata);
	if($this->cekKamus($__kata)){
	return $__kata; // Jika ada balik
	}
	$__kata__ = $this->Del_Derivation_Suffixes($__kata);
	if($this->cekKamus($__kata__)){
	return $__kata__;
	}
	/*-- Cek luluh -l----------*/
	$__kata = preg_replace('/^(keter)/','r',$kata);
	if($this->cekKamus($__kata)){
	return $__kata; // Jika ada balik
	}
	$__kata__ = $this->Del_Derivation_Suffixes($__kata);
	if($this->cekKamus($__kata__)){
	return $__kata__;
	}
	}
	if(preg_match('/^(berke)\S{1,}/',$kata)){
	$__kata = preg_replace('/^(berke)/','',$kata);
	if($this->cekKamus($__kata)){
	return $__kata; // Jika ada balik
	}
	$__kata__ = $this->Del_Derivation_Suffixes($__kata);
	if($this->cekKamus($__kata__)){
	return $__kata__;
	}
	}
	/* --- Cek Ada Tidaknya Prefik/Awalan (?di-?, ?ke-?, ?se-?, ?te-?, ?be-?, ?me-?, atau ?pe-?) ------*/
	if(preg_match('/^(di|[kstbmp]e)\S{1,}/',$kata) == FALSE){
	return $kataAsal;
	}
	 
	return $kataAsal;
	}
	 
	function proses1($kata){
	 
		$kataAsal = $kata;
	 
		/* 1. Cek Kata di Kamus jika Ada SELESAI */
		if($this->cekKamus($kata)){ // Cek Kamus
			return $kata; // Jika Ada kembalikan
		}
		/* 2. Buang Infection suffixes (\-lah", \-kah", \-ku", \-mu", atau \-nya") */
		$kata = $this->Del_Inflection_Suffixes($kata);
	 
		/* 3. Buang Derivation suffix (\-i" or \-an") */
		$kata = $this->Del_Derivation_Suffixes($kata);
	 
		/* 4. Buang Derivation prefix */
		$kata = $this->Del_Derivation_Prefix($kata);
	 
		return $kata;
	}
}