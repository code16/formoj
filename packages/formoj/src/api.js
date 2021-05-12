import axios from 'axios';

export function getForm(baseUrl, { formId }) {
    return axios.get(`${baseUrl}/form/${formId}`)
        .then(response => response.data.data);
}

export function postSection(baseUrl, { formId, sectionId, data }) {
    return axios.post(`${baseUrl}/form/${formId}/validate/${sectionId}`,  { ...data });
}

export function postForm(baseUrl, { formId, validateAll, data }) {
    return axios.post(`${baseUrl}/form/${formId}`, { ...data }, {
        params: {
            validate_all: validateAll ? '1' : undefined,
        }
    });
}

export function postUploadUrl(baseUrl, { formId, fieldId }) {
    return `${baseUrl}/form/${formId}/upload/${fieldId}`;
}

export function getAnswer(baseUrl, { answerId }) {
    return axios.get(`${baseUrl}/answer/${answerId}`)
        .then(response => response.data.data);
}
