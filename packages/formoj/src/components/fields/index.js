import Text from './Text';
import Textarea from './Textarea';
import Select from './Select';


export function getFieldByType(key) {
    if(key === 'text') {
        return Text;
    } else if(key === 'textarea') {
        return Textarea;
    } else if(key === 'select') {
        return Select;
    }
}