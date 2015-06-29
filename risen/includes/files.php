<?php
/**
 * File Functions
 *
 * File and download related functions
 */

/**
 * Force download of certain file types via ?download=path/filename.type
 *
 * This prompts "Save As" -- handy for MP3, PDF, etc. Only works on local files.
 *
 * This information was useful: http://wordpress.stackexchange.com/questions/3480/how-can-i-force-a-file-download-in-the-wordpress-backend
 *
 * @since 2.0
 * @global object $wp_query
 */

function risen_force_download() {

    global $wp_query;

	// Check if this URL is a request for file download
	if ( is_front_page() && ! empty( $_GET['download'] ) ) {

		// relative file path
		$relative_file_path = ltrim( $_GET['download'], '/' ); // remove preceding slash, if any

		// check for directory traversal attack
		if ( ! validate_file( $relative_file_path ) ) { // false means it passed validation

			// path to file in uploads folder (only those can be downloaded)
			$upload_dir = wp_upload_dir();
			$upload_file_path = $upload_dir['basedir'] . '/' . $relative_file_path;

			// make sure file valid as upload (valid type, extension, etc.)
			$validate = wp_check_filetype_and_ext( $upload_file_path, basename( $upload_file_path ) );
			if ( $validate['type'] && $validate['ext'] ) { // empty if type not in upload_mimes, doesn't exist, etc.

				// headers to prompt "save as"
				$filename = basename( $upload_file_path );
				$filesize = filesize( $upload_file_path );
				header( 'Content-Type: application/octet-stream', true, 200 ); // replace WordPress 404 Not Found with 200 Okay
				header( 'Content-Disposition: attachment; filename=' . $filename );
				header( 'Expires: 0' );
				header( 'Cache-Control: must-revalidate' );
				header( 'Pragma: public' );
				header( 'Content-Length: ' . $filesize );

				// clear buffering just in case
				@ob_end_clean();
				flush();

				// output file contents
				@readfile( $upload_file_path ); // @ to prevent printing any error messages

				// we're done, stop further execution
				exit;

			}

		}

		// failure of any type results in 404 file not found
	    $wp_query->set_404();
	    status_header( 404 );

	}

}

/**
 * Convert regular URL to one that forces download ("Save As")
 *
 * This keeps the browser from doing what it wants with the file (such as play MP3 or show PDF).
 * Note that file must be in uploads folder and extension must be allowed by WordPress.
 *
 * Makes this:	http://yourname.com/?download=%2F2009%2F10%2Ffile.pdf
 * Out of:		http://yourname.com/wp-content/uploads/2013/05/file.pdf
 * 				http://yourname.com/wp-content/uploads/sites/6/2013/05/file.pdf (multisite)
 *
 * @since 2.0
 * @param string $url URL for file
 * @return string URL forcing "Save As" on file if local
 */
if ( ! function_exists( 'risen_force_download_url' ) ) {

	function risen_force_download_url( $url ) {

		// In case URL is not local or feature not supported by theme
		$download_url = $url;

		// Is URL local?
		if ( risen_is_local_url( $url ) ) {

			// Get URL to upload directory
			$upload_dir = wp_upload_dir();
			$upload_dir_url = $upload_dir['baseurl'];

			// Get relative URL for file
			$relative_url = str_replace( $upload_dir_url, '', $url ); // remove base URL
			$relative_url = ltrim( $relative_url ); // remove preceding slash

			// Add ?download=file to site URL
			$download_url = home_url( '/' ) . '?download=' . urlencode( $relative_url ) . '&nocache'; // avoid caching issues HS 5179

		}

		return apply_filters( 'risen_force_download_url', $download_url, $url );

	}

}
