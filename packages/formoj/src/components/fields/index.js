import Text from './Text';
import Textarea from './Textarea';
import Select from './select/Select';
import Checkboxes from './select/Checkboxes';
import Radios from "./select/Radios";
import Heading from './Heading';
import Upload from './Upload';
import Rating from "./Rating.vue";


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
    } else if(type === 'rating') {
        return Rating;
    }
}

export function isFieldset(fieldType, { isMultiple }={}) {
    return fieldType === 'select' && isMultiple;
}

export function isContentOnly(fieldType) {
    return fieldType === 'heading';
}
