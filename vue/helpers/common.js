import {errorDialog} from '@/assets/libs/bs-dialog';

export function showErrorDialog( response, message = 'Oops! Something bad happened.' ) {

    if ( response.data.hasOwnProperty( 'payload' ) ) {
        message = response.data.payload.error;
    }

    errorDialog( {
        message: message,
    } );

}
