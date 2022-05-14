export default function handleErrors(error) {
    let errorMessages = {};

    if (error.response.status === 500) {
        errorMessages['general_message'] = 'We got an error! Please try again.';
    }

    if (error.response.status === 422) {
        errorMessages = error.response.data.errors;
    }

    return Promise.reject(errorMessages);
}
