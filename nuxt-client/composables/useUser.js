
import { useSanctumClient } from '#imports';

export function useUser() {
    const sanctumFetch = useSanctumClient();
    const userStore = useUserStore();

    async function updateUserName(formBody) {
        const response = await sanctumFetch(`/set-username/${userStore.user.id}`, {
            method: 'POST',
            body: formBody
        });
        return response;
    }

    async function requestChangeMail(formBody) {
        const response = await sanctumFetch('/request-email-change', {
            method: 'POST',
            body: formBody
        });
        return response;
    }

    async function confirmedChangeEmail(token) {
        const formBody = { token: token };
        const response = await sanctumFetch('/change-email-confirmed', {
            method: 'POST',
            body: JSON.stringify(formBody)
        });
        return response;
    }

    async function requestResetPassword(formBody) {
        const response = await sanctumFetch('/request-password-reset', {
            method: 'POST',
            body: formBody
        });
        return response;
    }

    return {
        updateUserName,
        requestChangeMail,
        confirmedChangeEmail,
        requestResetPassword
    }
}