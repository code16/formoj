import axios from 'axios';

export function getForm(baseUrl, { formId }) {
    return axios.get(`${baseUrl}/form/${formId}`)
        .then(response => response.data.data);
}

export function postSection(baseUrl, { formId, sectionId, data }) {
    return axios.post(`${baseUrl}/form/${formId}/validate/${sectionId}`, { data });
}

export function postForm(baseUrl, { formId, data }) {
    return axios.post(`${baseUrl}/form/${formId}`, { data });
}