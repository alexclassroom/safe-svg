<?php
/**
 * Vector Express API
 *
 * @package SafeSvg
 */

namespace SafeSvg\API;

use \WP_Error;

/**
 * Interface for optmization APIs
 */
class VectorExpress implements Optimizer {

	const ENDPOINT = 'https://vector.express/api/v2/public/convert/svg/svgo/svg';

	public function optimize_image( $image_path ) {
		$body = file_get_contents( $image_path );

		$request = wp_remote_post(
			self::ENDPOINT,
			[
				'body' => $body,
			]
		);

		$response_code = wp_remote_retrieve_response_code( $request );

		if ( 201 === $response_code ) {
			$response_body = wp_remote_retrieve_body( $request );
			$response_json = json_decode( $response_body );

			if ( null === $response_json ) {
				return new WP_Error( 'optimize-error', __( 'JSON decode failed on response.', 'safe-svg' ) );
			}

			return $response_json;
		}

		return new WP_Error( 'optimize-error', __( 'API request failed.', 'safe-svg' ) );
	}

}