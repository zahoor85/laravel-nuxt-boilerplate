import { useSanctumClient } from "#imports";

export const useAuth = () => {
    const sanctumFetch = useSanctumClient()

    async function register(form) {
        const response = await sanctumFetch('/register', {
            method: 'POST',
            body: form
        })
        return response
    }

    //api/email/verification-notification .
    async function resendEmail() {
        const response = await sanctumFetch('/email/verification-notification', {
            method: 'POST'
        })
        return response
    }

    // api/verify-email/{id}/{hash}
    async function verifyEmail(verifyUrl) {
        const response = await sanctumFetch(verifyUrl)
        return response
    }

    // api/forgot-password
    async function forgotPassword(formBody) {
        const response = await sanctumFetch('/forgot-password', {
            method: 'POST',
            body: formBody
        })
        return response;
    }

    // api/rest-password
    async function restPassword(formBody) {
        const response = await sanctumFetch('/reset-password', {
            method: 'POST',
            body: formBody
        })
        return response;
    }

    // api/user
    async function refreshUser() {
        const response = await sanctumFetch('/user', {
            method: 'GET',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        return response;

    }

    return {
        register,
        resendEmail,
        verifyEmail,
        forgotPassword,
        restPassword,
        refreshUser
    }
}