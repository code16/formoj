import Text from './Text';
import Textarea from './Textarea';
import Select from './Select';
import MultipleSelect from './MultipleSelect';
import Heading from './Heading';


export function getFieldByType(type, { isMultiple }={}) {
    if(type === 'text') {
        return Text;
    } else if(type === 'textarea') {
        return Textarea;
    } else if(type === 'select') {
        return isMultiple
            ? MultipleSelect
            : Select;
    } else if(type === 'heading') {
        return Heading;
    }
}

export function isFieldset(fieldType, { isMultiple }={}) {
    return fieldType === 'select' && isMultiple;
}

export function isContentOnly(fieldType) {
    return fieldType === 'heading';
}