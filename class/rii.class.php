<?php
/**
 * @package WordPress
 * @subpackage RII Radio Widget
 * @version 1.2
 */

/**
 * Generate XML to JSON
 */
class RII_Data {

	private $options = array();

	function __construct( $url = '', $is_xml = true, $args = array() ) {
		$this->options['url'] = $url;
		$this->options['is_xml'] = $is_xml;
		$this->options['args'] = $args;
	}

	private function get_json( $url ) {
		$response = wp_remote_post( $url );

		if ( !empty( $this->options['args'] ) ) {
			$response = wp_remote_post( $url, $this->options['args'] );
		}

		if ( is_wp_error( $response ) ) {
		 	$error_message = $response->get_error_message();
		}

		$data = wp_remote_retrieve_body( $response );
		$json = '';

		if ( !is_wp_error( $data ) ) {			
			if ( true === $this->options['is_xml'] ) {
				$data = str_replace( array( "\n", "\r", "\t"), '', $data );
				$data = trim (str_replace( '"', "'", $data ) );
				$xml = simplexml_load_string( $data );
				$json = json_encode( $xml );
			} else {
				$json = $data;
			}
		}

		return $json;
	}

	private function print_json( $json ) {
		header('Content-Type: application/json');
		echo $json;
		exit;
	}

	public function generate_json() {
		$json = $this->get_json( $this->options['url'] );
		$this->print_json( $json );
	}
}
