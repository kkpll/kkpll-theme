<?php

namespace App\Components;

use App\Base;

class Csv extends Base{

    public $option_name;

    public function __construct( $option_name ){

        parent::__construct();

        $this->option_name = $option_name;

    }

	public function import( $data ) {

		$option_data = get_option( $this->option_name );

		$allow_ext = array( "csv" );
		$file_name = explode( ".", $data["name"] );
		$file_ext = end( $file_name );
		$message = '';

		$mime_types = array(
			'application/csv',
			'application/excel',
			'application/ms-excel',
			'application/x-excel',
			'application/vnd.ms-excel',
			'application/vnd.msexcel',
			'application/octet-stream',
			'application/data',
			'application/x-csv',
			'application/txt',
			'plain/text',
			'text/anytext',
			'text/csv',
			'text/x-csv',
			'text/plain',
			'text/comma-separated-values'
		);

		if ( in_array( $data["type"], $mime_types ) && ( $data["size"] < 10000000 ) && in_array( $file_ext, $allow_ext ) ) {
			if ( $data["error"] > 0 ) {
				$message .= "Return Code: " . $data["error"] . "<br>";
			} else {
				$message .= "Upload: " . $data["name"] . "<br />";
				$message .= "Size: " . ( $data["size"] / 1024 ) . " kB<br /><br />";

                $num = 0;

				if ( ( $handle = fopen( $data["tmp_name"], "r" ) ) !== false ) {
					while( ( $data = fgetcsv( $handle, 1000, "," ) ) !== false ) {
                        if( count( $option_data[$num] ) === count( $data ) ){
                            for($i=0; $i<count($data); $i++){
                                $option_data[$num][$i] = $data[$i];
                                $message .= $data[$i] . 'に変更されました';
                            }
                        }
                        $num ++;
					}
					fclose( $handle );
					update_option( $this->option_name, $option_data );
				}
			}
		} else {
			$message .= __( 'このファイル形式はサポートされていません', 'fnsk' ) . "<br /><br />";
		}
		return $message;
	}

}
