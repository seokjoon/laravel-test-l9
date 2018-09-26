import React, {Component} from 'react';
import ReactDOM from 'react-dom';

class T2 extends Component {

	constructor(props) {
		super(props);
		this.state = {}
	}

	render() {
		console.log(this.props);
		return (
			<div>
				TEST T2: {this.props.foo}
			</div>
		);
	}
}

T2.defaultProps = {
	foo: 'default props.foo value',
};

export default T2;

//https://www.laracasts.com/discuss/channels/laravel/pass-parameter-from-laravel-controller-to-react-component-prop
if(document.getElementById('T2')) {
	const element = document.getElementById('T2');
	const props = Object.assign({}, element.dataset);
	ReactDOM.render(<T2 {...props} />, element);
}
