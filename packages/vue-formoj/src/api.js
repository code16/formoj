import axios from 'axios';

export function getForm(baseUrl, { formId }) {
    return axios.get(`${baseUrl}/form/${formId}`)
        .then(response => response.data);
}

export function postSection(baseUrl, { formId, sectionId }) {
    return axios.post(`${baseUrl}/form/${formId}/validate/${sectionId}`);
}

export function postForm(baseUrl, { formId, data }) {
    return axios.post(`${baseUrl}/form/${formId}`, { data });
}