import Text from './Text';
import Textarea from './Textarea';
import Select from './Select';
import Checkboxes from './Checkboxes';
import Radios from "./Radios";
import Heading from './Heading';
import Upload from './Upload';


export function getFieldByType(type, { isMultiple, isRadios }={}) {
    if(type === 'text') {
        return Text;
    } else if(type === 'textarea') {
        return Textarea;
    } else if(type === 'select') {
        if(isMultiple) {
            return Checkboxes;
        } else if(isRadios) {
            return Radios;
        }
        return Select;
    } else if(type === 'heading') {
        return Heading;
    } else if(type === 'upload') {
        return Upload;
    }
}

export function isFieldset(fieldType, { isMultiple }={}) {
    return fieldType === 'select' && isMultiple;
}

export function isContentOnly(fieldType) {
    return fieldType === 'heading';
}