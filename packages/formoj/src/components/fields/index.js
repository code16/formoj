import Text from './Text';
import Textarea from './Textarea';
import Select from './Select';
import MultipleSelect from './MultipleSelect'


export function getFieldByType(type, { isMultiple }={}) {
    if(type === 'text') {
        return Text;
    } else if(type === 'textarea') {
        return Textarea;
    } else if(type === 'select') {
        return isMultiple
            ? MultipleSelect
            : Select;
    }
}

export function isFieldset(fieldType, { isMultiple }={}) {
    return fieldType === 'select' && isMultiple;
}