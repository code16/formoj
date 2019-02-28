import axios from 'axios';

export function getForm(baseUrl, { formId }) {
    return axios.get(`${baseUrl}/${formId}`)
        .then(response => response.data);
}

export function postSection(baseUrl, { formId, sectionId }) {
    return axios.post(`${baseUrl}/${formId}/validate/${sectionId}`);
}

export function postForm(baseUrl, { formId, data }) {
    return axios.post(`${baseUrl}/${formId}`, { data });
}