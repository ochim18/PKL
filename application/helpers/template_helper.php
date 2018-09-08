<?php 

function link_get()
{
    $data = "?";
    $CI =& get_instance();
    $no = 1;
    foreach ($CI->input->get() as $key => $value) {
        if ($value != "") {
            $data .= (($no++ != 1) ? "&" : "").$key."=".$value;
        }
    }
    return ($data == "?" ? "" : $data);
}

function date_html($date){
	return date('d M Y', strtotime($date));
}

function datetime_html($datetime){
    return date_html($datetime) . '<small class="text-muted"> (' .date('H:i', strtotime($datetime)) . ')</small>';
}