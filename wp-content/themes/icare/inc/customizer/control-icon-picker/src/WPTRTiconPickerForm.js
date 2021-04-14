/* globals _, wp, React */
import FontIconPicker from '@fonticonpicker/react-fonticonpicker';

const WPTRTiconPickerForm = (props) => {
	const [pickerValue, setValue] = React.useState(( props.value ) ? props.value : '');

	const handleChange = (e) => {
		let value = e.target.value;
		setState({ value });
		wp.customize.control( props.customizerSetting.id ).setting.set( value );
	}
	
	return (
		<div> 
			<label className="customize-control-title" for={ props.control.id + '-input' }>{ props.label }</label>
			<span className="description customize-control-description" dangerouslySetInnerHTML={{ __html: props.description }}></span>
			<div className="customize-control-notifications-container" ref={ props.setNotificationContainer }></div>
			<FontIconPicker 
				icons= { props.icons }
				theme= 'bluegrey'
				renderUsing= 'class'
				value= { pickerValue }
				onChange= { val => { 
					setValue( val );
					wp.customize.control( props.customizerSetting.id ).setting.set( val );
				} }
				isMulti= { false }
			/>
		</div>
	);
}


export default WPTRTiconPickerForm;